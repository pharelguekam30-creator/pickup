<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NouvelleDemandeNotification;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $services = \App\Models\Service::all();
        $vidangeurs = User::where('role', 'vidangeur')
            ->where(function ($query) {
                $query->where('disponibilite', 1)
                      ->orWhereNull('disponibilite');
            })
            ->get();

        return view('reservations.create', compact('services', 'vidangeurs'));
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

    public function complete($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'accepted') {
            return back()->with('error', 'Une intervention ne peut être marquée comme terminée que si elle est acceptée.');
        }

        $reservation->status = 'completed';
        $reservation->save();

        return back()->with('success', 'Intervention marquée comme terminée.');
    }
}
