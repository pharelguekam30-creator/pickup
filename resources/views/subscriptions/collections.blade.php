@extends('layouts.app')

@section('title', 'Collectes - Abonnement #'.$subscription->id)

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem;">
        <div>
            <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;">Collectes — {{ $subscription->plan->name ?? 'N/A' }}</h1>
            <p style="color:#6b7280;font-size:.85rem;">
                Client : {{ $subscription->client->name ?? 'N/A' }} —
                Mois : {{ optional($subscription->current_month_start)->format('d/m/Y') }} au {{ optional($subscription->current_month_end)->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route(auth()->user()->role === 'menagere' ? 'subscriptions.my' : 'subscriptions.vidangeur') }}" style="color:#2563eb;text-decoration:none;">&larr; Retour</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #dc2626;background:#fef2f2;color:#991b1b;">{{ session('error') }}</div>
    @endif

    <div style="display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap;">
        <span style="padding:.3rem .7rem;border-radius:8px;font-size:.8rem;font-weight:600;{{ $subscription->month_status === 'active' ? 'background:#e0e7ff;color:#4338ca;' : ($subscription->month_status === 'completed_vidangeur' ? 'background:#fef3c7;color:#92400e;' : ($subscription->month_status === 'paid' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;')) }}">
            Mois : {{ match($subscription->month_status) { 'active' => 'En cours', 'completed_vidangeur' => 'Terminé (attente client)', 'paid' => 'Payé', 'disputed' => 'Litige', default => $subscription->month_status } }}
        </span>
        <span style="padding:.3rem .7rem;border-radius:8px;font-size:.8rem;font-weight:600;background:#f3f4f6;color:#374151;">
            Prix : {{ number_format($subscription->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA
        </span>
    </div>

    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;overflow:hidden;">
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:420px;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Date</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Heure</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Statut</th>
                    @if(auth()->user()->role === 'vidangeur' && $subscription->month_status === 'active')
                        <th style="padding:.8rem 1rem;text-align:center;color:#374151;font-size:.85rem;">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $c)
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="padding:.7rem 1rem;color:#374151;">{{ $c->scheduled_date->format('d/m/Y') }}</td>
                        <td style="padding:.7rem 1rem;color:#6b7280;">{{ $c->time_slot ? substr($c->time_slot, 0, 5) : '—' }}</td>
                        <td style="padding:.7rem 1rem;">
                            @php
                                $badge = match($c->status) {
                                    'completed' => 'background:#dcfce7;color:#166534;',
                                    'cancelled' => 'background:#fef2f2;color:#991b1b;',
                                    'missed' => 'background:#fef3c7;color:#92400e;',
                                    default => 'background:#e0e7ff;color:#4338ca;',
                                };
                                $label = match($c->status) {
                                    'completed' => 'Effectuée',
                                    'cancelled' => 'Annulée',
                                    'missed' => 'Manquée',
                                    default => 'Planifiée',
                                };
                            @endphp
                            <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;{{ $badge }}">{{ $label }}</span>
                        </td>
                        @if(auth()->user()->role === 'vidangeur' && $subscription->month_status === 'active')
                            <td style="padding:.7rem 1rem;text-align:center;">
                                @if($c->status === 'scheduled')
                                    <form method="POST" action="{{ route('subscriptions.collections.complete', $c) }}" class="inline">
                                        @csrf
                                        <button type="submit" style="padding:.3rem .8rem;background:#16a34a;color:#fff;border:none;border-radius:6px;font-weight:600;font-size:.8rem;cursor:pointer;">Effectuée</button>
                                    </form>
                                @else
                                    <span style="color:#9ca3af;font-size:.8rem;">—</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ auth()->user()->role === 'vidangeur' && $subscription->month_status === 'active' ? 4 : 3 }}" style="padding:2rem;text-align:center;color:#9ca3af;">Aucune collecte pour cette période.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- PAIEMENT EFFECTUÉ --}}
    @if($subscription->month_status === 'paid')
        <div style="margin-top:1.5rem;padding:1.5rem;border-radius:1rem;background:#dcfce7;border:2px solid #16a34a;text-align:center;">
            <span style="font-size:2rem;line-height:1;">✅</span>
            <h2 style="color:#166534;margin:.5rem 0 .3rem;">Mois payé</h2>
            <p style="color:#065f46;font-size:.9rem;">
                @if(auth()->user()->role === 'vidangeur')
                    Vous avez reçu <strong style="font-size:1.1rem;">{{ number_format(($subscription->plan->price_per_month ?? 0) * 0.65, 0, ',', ' ') }} FCFA</strong>
                    (65 % du prix du plan)
                @elseif(auth()->user()->role === 'menagere')
                    Vous avez payé <strong style="font-size:1.1rem;">{{ number_format($subscription->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA</strong>
                @else
                    Mois payé — {{ number_format($subscription->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA
                @endif
            </p>
        </div>
    @endif

    {{-- Boutons d'action selon le rôle et le statut --}}
    <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">

        {{-- Vidangeur : Terminer le mois --}}
        @if(auth()->user()->role === 'vidangeur' && $subscription->month_status === 'active' && $allCompleted)
            <form method="POST" action="{{ route('subscriptions.month.complete', $subscription) }}">
                @csrf
                <button type="submit" style="padding:.8rem 2rem;background:#7c3aed;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;box-shadow:0 2px 8px #7c3aed44;">
                    Terminer le mois
                </button>
            </form>
        @endif

        {{-- Client : Confirmer le mois --}}
        @if(auth()->user()->role === 'menagere' && $subscription->month_status === 'completed_vidangeur')
            <form method="POST" action="{{ route('subscriptions.month.confirm', $subscription) }}" onsubmit="return confirm('Confirmer le paiement de {{ number_format($subscription->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA ?');">
                @csrf
                <button type="submit" style="padding:.8rem 2rem;background:#059669;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;box-shadow:0 2px 8px #05966944;">
                    Confirmer et payer le mois
                </button>
            </form>
        @endif

        {{-- Client : Voir si litige --}}
        @if(auth()->user()->role === 'menagere' && $subscription->month_status === 'completed_vidangeur')
            <p style="width:100%;text-align:center;color:#6b7280;font-size:.85rem;">Vous pouvez aussi signaler un problème à l'administrateur.</p>
        @endif

    </div>
</div>
@endsection
