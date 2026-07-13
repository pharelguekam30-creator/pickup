<?php

namespace App\Http\Controllers;

use App\Models\CollectionPlan;
use App\Models\Subscription;
use App\Models\SubscriptionCollection;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = CollectionPlan::where('is_active', true)->orderBy('type')->orderBy('price_per_month')->get();
        return view('subscriptions.plans', compact('plans'));
    }

    public function subscribeForm(CollectionPlan $plan)
    {
        $vidangeurs = User::where('role', 'vidangeur')
            ->where(function ($q) { $q->where('disponibilite', 1)->orWhereNull('disponibilite'); })
            ->get();
        return view('subscriptions.subscribe', compact('plan', 'vidangeurs'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'collection_plan_id' => 'required|exists:collection_plans,id',
            'vidangeur_id' => 'required|exists:users,id',
        ]);

        $plan = CollectionPlan::findOrFail($request->collection_plan_id);
        $vidangeur = User::where('id', $request->vidangeur_id)->where('role', 'vidangeur')->firstOrFail();

        $start = now()->addDay()->startOfDay();
        $monthEnd = $start->copy()->addMonth()->subDay();

        $subscription = Subscription::create([
            'collection_plan_id' => $plan->id,
            'client_id' => auth()->id(),
            'vidangeur_id' => $vidangeur->id,
            'start_date' => $start->toDateString(),
            'status' => 'active',
            'current_month_start' => $start->toDateString(),
            'current_month_end' => $monthEnd->toDateString(),
            'month_status' => 'active',
        ]);

        $this->generateCollections($subscription, $plan, $start, $monthEnd);

        return redirect()->route('subscriptions.my')->with('success', 'Abonnement souscrit avec succès !');
    }

    private function generateCollections(Subscription $subscription, CollectionPlan $plan, Carbon $start, Carbon $end)
    {
        $daysMap = [
            'monday' => Carbon::MONDAY, 'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY, 'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY, 'saturday' => Carbon::SATURDAY,
            'sunday' => Carbon::SUNDAY,
        ];

        $dayNumbers = collect($plan->collection_days)->map(fn($d) => $daysMap[strtolower($d)] ?? null)->filter();

        $current = $start->copy();
        while ($current <= $end) {
            if ($dayNumbers->contains($current->dayOfWeek)) {
                SubscriptionCollection::create([
                    'subscription_id' => $subscription->id,
                    'scheduled_date' => $current->toDateString(),
                    'time_slot' => '08:00:00',
                    'status' => 'scheduled',
                ]);
            }
            $current->addDay();
        }
    }

    public function mySubscriptions()
    {
        $subscriptions = Subscription::with(['plan', 'vidangeur'])
            ->where('client_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('subscriptions.my', compact('subscriptions'));
    }

    public function vidangeurSubscriptions()
    {
        $subscriptions = Subscription::with(['plan', 'client'])
            ->where('vidangeur_id', auth()->id())
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('subscriptions.vidangeur', compact('subscriptions'));
    }

    public function myCollections(Subscription $subscription)
    {
        if ($subscription->client_id !== auth()->id() && $subscription->vidangeur_id !== auth()->id()) {
            abort(403);
        }
        $collections = $subscription->collections()->orderBy('scheduled_date')->get();

        $allCompleted = $collections->where('scheduled_date', '<=', now()->toDateString())
            ->where('status', 'scheduled')
            ->isEmpty();

        return view('subscriptions.collections', compact('subscription', 'collections', 'allCompleted'));
    }

    public function cancel(Subscription $subscription)
    {
        if ($subscription->client_id !== auth()->id()) abort(403);
        $subscription->update(['status' => 'cancelled', 'end_date' => now()->toDateString()]);
        return back()->with('success', 'Abonnement annulé.');
    }

    public function completeCollection(SubscriptionCollection $collection)
    {
        $subscription = $collection->subscription;
        if ($subscription->vidangeur_id !== auth()->id()) abort(403);
        if ($collection->status !== 'scheduled') return back()->with('error', 'Déjà traitée.');

        $collection->update(['status' => 'completed', 'completed_at' => now()]);
        return back()->with('success', 'Collecte marquée comme effectuée.');
    }

    // === CYCLE MENSUEL ===

    public function completeMonth(Subscription $subscription)
    {
        if ($subscription->vidangeur_id !== auth()->id()) abort(403);
        if ($subscription->month_status !== 'active') return back()->with('error', 'Mois déjà traité.');

        $collections = $subscription->collections()
            ->whereBetween('scheduled_date', [$subscription->current_month_start, $subscription->current_month_end])
            ->get();

        $pending = $collections->where('scheduled_date', '<=', now()->toDateString())
            ->where('status', 'scheduled');

        if ($pending->isNotEmpty()) {
            return back()->with('error', 'Toutes les collectes passées doivent être effectuées avant de terminer le mois.');
        }

        $subscription->update(['month_status' => 'completed_vidangeur']);
        return back()->with('success', 'Mois marqué comme terminé. En attente de confirmation du client.');
    }

    public function confirmMonth(Subscription $subscription)
    {
        if ($subscription->client_id !== auth()->id()) abort(403);
        if ($subscription->month_status !== 'completed_vidangeur') {
            return back()->with('error', 'Le vidangeur n\'a pas encore terminé le mois.');
        }

        $montant = $subscription->plan->price_per_month ?? 0;
        if ($montant <= 0) return back()->with('error', 'Ce plan n\'a pas de prix défini.');

        $client = $subscription->client;
        $vidangeur = $subscription->vidangeur;
        if (!$client || !$vidangeur) return back()->with('error', 'Client ou vidangeur introuvable.');

        if (($client->solde ?? 0) < $montant) return back()->with('error', 'Solde insuffisant. Veuillez créditer votre compte.');

        $commission = round($montant * 0.35, 2);
        $net = $montant - $commission;

        DB::beginTransaction();
        try {
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
                'description' => 'Paiement abonnement ' . $subscription->plan->name . ' (mois du ' . $subscription->current_month_start->format('d/m/Y') . ')',
                'methode' => 'interne',
                'statut' => 'completed',
            ]);

            Transaction::create([
                'user_id' => $vidangeur->id,
                'type' => 'paiement',
                'montant' => $net,
                'solde_avant' => $solde_vidangeur_avant,
                'solde_apres' => $vidangeur->solde,
                'description' => 'Reception abonnement ' . $subscription->plan->name . ' (de ' . $client->name . ')',
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
                    'description' => 'Commission 35% abonnement ' . $subscription->plan->name,
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
                        'description' => 'Commission 35% abonnement #' . $subscription->id . ' (' . $subscription->plan->name . ')',
                        'methode' => 'interne',
                        'statut' => 'completed',
                    ]);
                }
            }

            // Démarrer le mois suivant
            $nextStart = Carbon::parse($subscription->current_month_end)->addDay();
            $nextEnd = $nextStart->copy()->addMonth()->subDay();

            $subscription->update([
                'month_status' => 'active',
                'current_month_start' => $nextStart->toDateString(),
                'current_month_end' => $nextEnd->toDateString(),
            ]);

            // Générer les collectes du mois suivant
            $this->generateCollections($subscription, $subscription->plan, $nextStart, $nextEnd);

            DB::commit();
            return back()->with('success', 'Paiement confirmé. Le mois suivant a démarré automatiquement.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    public function adminDisputes()
    {
        $disputes = Subscription::with(['plan', 'client', 'vidangeur'])
            ->whereIn('month_status', ['completed_vidangeur', 'disputed'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.subscriptions', compact('disputes'));
    }

    public function adminForcePay(Subscription $subscription)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        if (!in_array($subscription->month_status, ['completed_vidangeur', 'disputed'])) {
            return back()->with('error', 'Statut incompatible pour un paiement forcé.');
        }

        $montant = $subscription->plan->price_per_month ?? 0;
        if ($montant <= 0) return back()->with('error', 'Prix non défini.');

        $client = $subscription->client;
        $vidangeur = $subscription->vidangeur;
        if (!$client || !$vidangeur) return back()->with('error', 'Utilisateur introuvable.');

        $commission = round($montant * 0.35, 2);
        $net = $montant - $commission;

        DB::beginTransaction();
        try {
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
                'description' => 'Paiement forcé abonnement #' . $subscription->id . ' par admin',
                'methode' => 'interne',
                'statut' => 'completed',
            ]);

            Transaction::create([
                'user_id' => $vidangeur->id,
                'type' => 'paiement',
                'montant' => $net,
                'solde_avant' => $solde_vidangeur_avant,
                'solde_apres' => $vidangeur->solde,
                'description' => 'Reception forcée abonnement #' . $subscription->id,
                'methode' => 'interne',
                'statut' => 'completed',
            ]);

            $admin = User::where('role', 'admin')->orderBy('id')->first();
            if ($admin && $commission > 0) {
                $solde_admin_avant = $admin->solde;
                $admin->solde += $commission;
                $admin->save();

                Transaction::create([
                    'user_id' => $admin->id,
                    'type' => 'commission',
                    'montant' => $commission,
                    'solde_avant' => $solde_admin_avant,
                    'solde_apres' => $admin->solde,
                    'description' => 'Commission forcée abonnement #' . $subscription->id,
                    'methode' => 'interne',
                    'statut' => 'completed',
                ]);
            }

            $nextStart = Carbon::parse($subscription->current_month_end)->addDay();
            $nextEnd = $nextStart->copy()->addMonth()->subDay();

            $subscription->update([
                'month_status' => 'active',
                'current_month_start' => $nextStart->toDateString(),
                'current_month_end' => $nextEnd->toDateString(),
            ]);

            $this->generateCollections($subscription, $subscription->plan, $nextStart, $nextEnd);

            DB::commit();
            return back()->with('success', 'Paiement forcé effectué par l\'administrateur.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function adminForceCancelMonth(Subscription $subscription)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        if (!in_array($subscription->month_status, ['completed_vidangeur', 'disputed'])) {
            return back()->with('error', 'Statut incompatible.');
        }

        $subscription->update(['month_status' => 'cancelled']);

        return back()->with('success', 'Mois annulé par l\'administrateur (aucun paiement).');
    }
}
