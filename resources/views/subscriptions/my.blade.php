@extends('layouts.app')

@section('title', 'Mes abonnements')

@section('sidebar')
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Interventions</a>
    <a href="{{ route('subscriptions.my') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="{{ route('subscriptions.plans') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Plans de collecte</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Profil</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Historique</a>
@endsection

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;margin-bottom:1.5rem;">
        <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;">Mes abonnements</h1>
        <a href="{{ route('subscriptions.plans') }}" style="padding:.5rem 1rem;background:#4f46e5;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:.85rem;">Voir les plans</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
    @endif

    @forelse($subscriptions as $sub)
        <div style="background:#fff;border-radius:1rem;padding:1.2rem 1.5rem;box-shadow:0 2px 8px #00000011;margin-bottom:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                <div>
                    <h3 style="font-weight:700;color:#1e3a8a;">{{ $sub->plan->name ?? 'Plan supprimé' }}</h3>
                    <p style="color:#6b7280;font-size:.85rem;">
                        Vidangeur : {{ $sub->vidangeur->name ?? 'N/A' }} —
                        Depuis le {{ $sub->start_date->format('d/m/Y') }}
                    </p>
                    <div style="display:flex;gap:.5rem;margin-top:.3rem;flex-wrap:wrap;">
                        <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;{{ $sub->status === 'active' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;' }}">
                            {{ $sub->status === 'active' ? 'Actif' : 'Annulé' }}
                        </span>
                        @if($sub->month_status)
                            <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;
                                {{ $sub->month_status === 'active' ? 'background:#e0e7ff;color:#4338ca;' : ($sub->month_status === 'completed_vidangeur' ? 'background:#fef3c7;color:#92400e;' : ($sub->month_status === 'paid' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;')) }}">
                                Mois : {{ match($sub->month_status) { 'active' => 'En cours', 'completed_vidangeur' => 'En attente de confirmation', 'paid' => 'Payé', 'disputed' => 'Litige', default => $sub->month_status } }}
                            </span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
                    <a href="{{ route('subscriptions.collections', $sub) }}" style="padding:.4rem .9rem;background:#e0e7ff;color:#4338ca;border-radius:8px;text-decoration:none;font-weight:600;font-size:.8rem;">Collectes</a>
                    @if($sub->month_status === 'paid')
                        <span style="padding:.4rem .9rem;background:#dcfce7;color:#166534;border-radius:8px;font-weight:700;font-size:.8rem;">
                            Payé : {{ number_format($sub->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA
                        </span>
                    @elseif($sub->month_status === 'completed_vidangeur')
                        <form method="POST" action="{{ route('subscriptions.month.confirm', $sub) }}" onsubmit="return confirm('Confirmer le paiement de {{ number_format($sub->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA ?');">
                            @csrf
                            <button type="submit" style="padding:.4rem .9rem;background:#059669;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:.8rem;cursor:pointer;">Payer le mois</button>
                        </form>
                    @endif
                    @if($sub->status === 'active' && $sub->month_status !== 'paid')
                        <form method="POST" action="{{ route('subscriptions.cancel', $sub) }}" onsubmit="return confirm('Annuler cet abonnement ?');">
                            @csrf
                            <button type="submit" style="padding:.4rem .9rem;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;font-weight:600;font-size:.8rem;cursor:pointer;">Annuler</button>
                        </form>
                    @endif
                </div>
            </div>
            @if($sub->current_month_start && $sub->current_month_end)
                <div style="margin-top:.5rem;font-size:.8rem;color:#6b7280;">
                    Période en cours : {{ $sub->current_month_start->format('d/m/Y') }} — {{ $sub->current_month_end->format('d/m/Y') }}
                </div>
            @endif
        </div>
    @empty
        <div style="background:#fff;border-radius:1rem;padding:2rem;text-align:center;color:#6b7280;">Aucun abonnement pour le moment.</div>
    @endforelse
</div>
@endsection
