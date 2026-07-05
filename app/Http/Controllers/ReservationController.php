<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NouvelleDemandeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $services = \App\Models\Service::all();
        $vidangeurs = User::where('role', 'vidangeur')
            ->where(function ($query) {
                $query->where('disponibilite', 1)
                      ->orWhereNull('disponibilite');
            })
            ->get();

        $selectedVidangeur = null;
        if ($request->query('vidangeur_id')) {
            $selectedVidangeur = User::find($request->query('vidangeur_id'));
        }

        return view('reservations.create', compact('services', 'vidangeurs', 'selectedVidangeur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required'
        ]);

        $vidangeur = User::where('id', $request->user_id)->where('role', 'vidangeur')->first();
        if (!$vidangeur) {
            return back()->withErrors(['user_id' => 'Le vidangeur sélectionné est invalide ou indisponible.'])->withInput();
        }

        $data = [
            'user_id' => $vidangeur->id,
            'service_id' => $request->service_id,
            'client_id' => auth()->id(),
            'client_name' => auth()->user()->name ?? 'Client',
            'reservation_date' => date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time)),
            'status' => 'pending',
        ];

        $reservation = Reservation::create($data);

        $vidangeur->notify(new NouvelleDemandeNotification($reservation));

        return redirect()->route('menagere.dashboard')->with('success', 'Demande d’intervention envoyée au vidangeur et en attente de confirmation.');
    }

    public function show($id)
    {
        $reservation = Reservation::with(['client', 'user', 'service'])->findOrFail($id);
        return view('reservations.index', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $services = \App\Models\Service::all();
        return view('reservations.create', compact('reservation', 'services'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->only('status'));
        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour');
    }

    public function destroy($id)
    {
        Reservation::destroy($id);
        return back()->with('success', 'Réservation supprimée');
    }

    public function accept($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'accepted';
        $reservation->save();

        return back()->with('success', 'Intervention acceptée avec succès');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'canceled';
        $reservation->save();

        return back()->with('success', 'Intervention refusée / annulée avec succès');
    }

    private function processPayment($reservation)
    {
        $montant = $reservation->service->price ?? 0;
        if ($montant <= 0) return 'Le service n\'a pas de prix défini.';

        $client = $reservation->client;
        $vidangeur = $reservation->user;
        if (!$client || !$vidangeur) return 'Client ou vidangeur introuvable.';
        if (($client->solde ?? 0) < $montant) return 'Le client n\'a pas assez de solde.';

        $commission = round($montant * 0.10, 2);
        $net = $montant - $commission;

        $solde_client_avant = $client->solde;
        $solde_vidangeur_avant = $vidangeur->solde;

        $client->solde -= $montant;
        $vidangeur->solde += $net;
        $client->save();
        $vidangeur->save();

        Transaction::create([
            'user_id' => $client->id,
            'type' => 'paiement',
            'montant' => -$montant,
            'solde_avant' => $solde_client_avant,
            'solde_apres' => $client->solde,
            'description' => 'Paiement pour ' . $reservation->service->name . ' (vers ' . $vidangeur->name . ')',
            'reservation_id' => $reservation->id,
            'methode' => 'interne',
            'statut' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $vidangeur->id,
            'type' => 'paiement',
            'montant' => $net,
            'solde_avant' => $solde_vidangeur_avant,
            'solde_apres' => $vidangeur->solde,
            'description' => 'Reception du paiement pour ' . $reservation->service->name . ' (de ' . $client->name . ')',
            'reservation_id' => $reservation->id,
            'methode' => 'interne',
            'statut' => 'completed',
        ]);

        if ($commission > 0) {
            Transaction::create([
                'user_id' => $vidangeur->id,
                'type' => 'commission',
                'montant' => -$commission,
                'solde_avant' => $vidangeur->solde,
                'solde_apres' => $vidangeur->solde,
                'description' => 'Commission plateforme 10% sur ' . $reservation->service->name,
                'reservation_id' => $reservation->id,
                'methode' => 'interne',
                'statut' => 'completed',
            ]);

            $admin = User::where('role', 'admin')->orderBy('id')->first();
            if ($admin) {
                $solde_admin_avant = $admin->solde;
                $admin->solde += $commission;
                $admin->save();

                Transaction::create([
                    'user_id' => $admin->id,
                    'type' => 'commission',
                    'montant' => $commission,
                    'solde_avant' => $solde_admin_avant,
                    'solde_apres' => $admin->solde,
                    'description' => 'Commission 10% sur intervention #' . $reservation->id . ' (' . $reservation->service->name . ')',
                    'reservation_id' => $reservation->id,
                    'methode' => 'interne',
                    'statut' => 'completed',
                ]);
            }
        }

        $reservation->status = 'completed';
        $reservation->save();

        return null;
    }

    public function complete($id)
    {
        $reservation = Reservation::with(['service', 'client', 'user'])->findOrFail($id);

        if ($reservation->status !== 'accepted') {
            return back()->with('error', 'L\'intervention doit d\'abord être acceptée.');
        }

        $reservation->status = 'completed_vidangeur';
        $reservation->save();

        return back()->with('success', 'Intervention marquée comme terminée. En attente de confirmation du client.');
    }

    public function confirmComplete($id)
    {
        $reservation = Reservation::with(['service', 'client', 'user'])->findOrFail($id);

        if ($reservation->client_id !== auth()->id()) {
            return back()->with('error', 'Seul le client peut confirmer.');
        }

        if ($reservation->status !== 'completed_vidangeur') {
            return back()->with('error', 'Le vidangeur n\'a pas encore marqué l\'intervention comme terminée.');
        }

        DB::beginTransaction();
        try {
            $error = $this->processPayment($reservation);
            if ($error) {
                DB::rollBack();
                return back()->with('error', $error);
            }
            DB::commit();
            return back()->with('success', 'Intervention confirmée et paiement effectué.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    public function adminForceComplete($id)
    {
        $reservation = Reservation::with(['service', 'client', 'user'])->findOrFail($id);

        if (!in_array($reservation->status, ['completed_vidangeur', 'accepted'])) {
            return back()->with('error', 'Statut incompatible pour un paiement forcé.');
        }

        DB::beginTransaction();
        try {
            $error = $this->processPayment($reservation);
            if ($error) {
                DB::rollBack();
                return back()->with('error', $error);
            }
            DB::commit();
            return back()->with('success', 'Paiement forcé effectué par l\'administrateur.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function adminForceCancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!in_array($reservation->status, ['completed_vidangeur', 'accepted', 'pending'])) {
            return back()->with('error', 'Cette intervention ne peut pas être annulée.');
        }

        $reservation->status = 'canceled';
        $reservation->save();

        return back()->with('success', 'Intervention annulée par l\'administrateur.');
    }
}
