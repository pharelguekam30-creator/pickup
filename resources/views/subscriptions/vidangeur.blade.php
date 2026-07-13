@extends('layouts.app')

@section('title', 'Mes abonnements')

@section('sidebar')
    <a href="{{ route('vidangeur.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Interventions</a>
    <a href="{{ route('subscriptions.vidangeur') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Profil</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Historique</a>
@endsection

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;margin-bottom:1.5rem;">Mes abonnements attribués</h1>

    @forelse($subscriptions as $sub)
        <div style="background:#fff;border-radius:1rem;padding:1.2rem 1.5rem;box-shadow:0 2px 8px #00000011;margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <div>
                <h3 style="font-weight:700;color:#1e3a8a;">{{ $sub->plan->name ?? 'Plan supprimé' }}</h3>
                <p style="color:#6b7280;font-size:.85rem;">
                    Client : {{ $sub->client->name ?? 'N/A' }} —
                    Début : {{ $sub->start_date->format('d/m/Y') }} —
                    Prix : {{ number_format($sub->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA/mois
                </p>
                <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;{{ $sub->status === 'active' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;' }}">
                    {{ $sub->status === 'active' ? 'Actif' : 'Annulé' }}
                </span>
                @if($sub->month_status)
                    <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;
                        {{ $sub->month_status === 'active' ? 'background:#e0e7ff;color:#4338ca;' : ($sub->month_status === 'completed_vidangeur' ? 'background:#fef3c7;color:#92400e;' : ($sub->month_status === 'paid' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;')) }}">
                        Mois : {{ match($sub->month_status) { 'active' => 'En cours', 'completed_vidangeur' => 'Attente client', 'paid' => 'Payé ✅', 'disputed' => 'Litige', default => $sub->month_status } }}
                    </span>
                @endif
            </div>
            <div style="display:flex;gap:.5rem;align-items:center;">
                @if($sub->month_status === 'paid')
                    <span style="padding:.4rem .9rem;background:#dcfce7;color:#166534;border-radius:8px;font-weight:700;font-size:.8rem;">
                        Reçu : {{ number_format(($sub->plan->price_per_month ?? 0) * 0.65, 0, ',', ' ') }} FCFA
                    </span>
                @endif
                <a href="{{ route('subscriptions.collections', $sub) }}" style="padding:.4rem .9rem;background:#e0e7ff;color:#4338ca;border-radius:8px;text-decoration:none;font-weight:600;font-size:.8rem;">Voir les collectes</a>
            </div>
        </div>
    @empty
        <div style="background:#fff;border-radius:1rem;padding:2rem;text-align:center;color:#6b7280;">Aucun abonnement attribué pour le moment.</div>
    @endforelse
</div>
@endsection
