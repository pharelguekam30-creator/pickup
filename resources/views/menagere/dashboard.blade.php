@extends('layouts.app')

@section('title', 'Dashboard Ménagère')

@section('sidebar')
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Interventions</a>
    <a href="{{ route('subscriptions.my') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="{{ route('subscriptions.plans') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Plans de collecte</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Profil</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Historique</a>
@endsection

@section('content')
<div class="dashboard-wrapper">
    @if (session('success'))
        <div style="padding:12px 16px;border-radius:10px;background:#dcfce7;color:#16a34a;font-weight:600;margin-bottom:1rem;border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="padding:12px 16px;border-radius:10px;background:#fee2e2;color:#dc2626;font-weight:600;margin-bottom:1rem;border:1px solid #fca5a5;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;gap:0.75rem;margin-bottom:2rem;">
        <a href="{{ route('subscriptions.plans') }}" style="font-size:1rem;padding:0.75rem 1.5rem;background:#7c3aed;color:#fff;border-radius:1.5rem;font-weight:bold;box-shadow:0 2px 8px #7c3aed33;text-decoration:none;text-align:center;">+ Abonnement collecte</a>
        <a href="{{ route('reservations.create') }}" style="font-size:1rem;padding:0.75rem 1.5rem;background:#2563eb;color:#fff;border-radius:1.5rem;font-weight:bold;box-shadow:0 2px 8px #2563eb33;text-decoration:none;text-align:center;">+ Nouvelle demande d'intervention</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;">Total</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;">En attente</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#f59e0b;">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;">Acceptées</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#2563eb;">{{ $stats['accepted'] ?? 0 }}</p>
        </div>
        <div style="background:#eff6ff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;border:2px solid #2563eb;">
            <p style="color:#2563eb;font-size:.75rem;text-transform:uppercase;font-weight:700;">A confirmer</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e40af;">{{ $stats['awaiting_confirmation'] ?? 0 }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;">Terminées</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#16a34a;">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;">Annulées</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#dc2626;">{{ $stats['canceled'] ?? 0 }}</p>
        </div>
    </div>

    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;">
        <h2 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;margin-bottom:1rem;">Vos demandes d'intervention</h2>

        @if($reservations && $reservations->count() > 0)
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #e5e7eb;">
                            <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Service</th>
                            <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Vidangeur</th>
                            <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Date</th>
                            <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Statut</th>
                            <th style="text-align:center;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $r)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:.75rem .5rem;font-weight:500;">{{ optional($r->service)->name ?? 'Service' }}</td>
                            <td style="padding:.75rem .5rem;color:#64748b;">{{ optional($r->user)->name ?? '?' }}</td>
                            <td style="padding:.75rem .5rem;color:#64748b;">{{ optional($r->reservation_date)->format('d/m/Y H:i') }}</td>
                            <td style="padding:.75rem .5rem;">
                                @php
                                    $badge = match($r->status) {
                                        'pending' => ['color' => '#f59e0b', 'bg' => '#fef3c7', 'label' => 'En attente'],
                                        'accepted' => ['color' => '#2563eb', 'bg' => '#dbeafe', 'label' => 'Acceptée'],
                                        'completed_vidangeur' => ['color' => '#7c3aed', 'bg' => '#ede9fe', 'label' => 'A confirmer'],
                                        'completed' => ['color' => '#16a34a', 'bg' => '#dcfce7', 'label' => 'Terminée'],
                                        'canceled' => ['color' => '#dc2626', 'bg' => '#fee2e2', 'label' => 'Annulée'],
                                        default => ['color' => '#64748b', 'bg' => '#f1f5f9', 'label' => $r->status]
                                    };
                                @endphp
                                <span style="display:inline-block;padding:.2rem .6rem;border-radius:1rem;font-size:.75rem;font-weight:600;background:{{ $badge['bg'] }};color:{{ $badge['color'] }};">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td style="padding:.75rem .5rem;text-align:center;">
                                @if($r->status === 'completed_vidangeur')
                                    <form action="{{ route('reservations.confirm', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la réception et effectuer le paiement de {{ number_format($r->service->price ?? 0, 0, ',', ' ') }} FCFA ?');">
                                        @csrf
                                        <button type="submit" style="padding:.4rem 1rem;background:#7c3aed;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                                            Confirmer
                                        </button>
                                    </form>
                                @elseif($r->status === 'accepted' || $r->status === 'pending')
                                    <span style="color:#94a3b8;font-size:.85rem;">En cours...</span>
                                @else
                                    <span style="color:#94a3b8;font-size:.85rem;">-</span>
                                @endif
                                @if($r->user_id)
                                    @php $nb = $unreadCounts[$r->id] ?? 0; @endphp
                                    <a href="{{ route('chat.show', $r->id) }}" style="display:inline-flex;align-items:center;gap:4px;padding:.4rem .8rem;background:{{ $nb ? '#dbeafe' : '#f1f5f9' }};color:{{ $nb ? '#2563eb' : '#475569' }};border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;margin-left:4px;">
                                        Discuter
                                        @if($nb > 0)
                                            <span style="background:#ef4444;color:#fff;font-size:.65rem;padding:1px 6px;border-radius:10px;font-weight:700;">{{ $nb }}</span>
                                        @endif
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color:#94a3b8;text-align:center;padding:2rem;">Aucune demande pour le moment.</p>
        @endif
    </div>
</div>
@endsection
