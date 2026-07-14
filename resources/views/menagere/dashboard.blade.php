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
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="menagere-actions">
        <a href="{{ route('subscriptions.plans') }}" class="btn-action btn-purple">+ Abonnement collecte</a>
        <a href="{{ route('reservations.create') }}" class="btn-action btn-blue">+ Nouvelle demande d'intervention</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><p class="stat-label">Total</p><p class="stat-value" style="color:#1e293b;">{{ $stats['total'] ?? 0 }}</p></div>
        <div class="stat-card"><p class="stat-label">En attente</p><p class="stat-value" style="color:#f59e0b;">{{ $stats['pending'] ?? 0 }}</p></div>
        <div class="stat-card"><p class="stat-label">Acceptées</p><p class="stat-value" style="color:#2563eb;">{{ $stats['accepted'] ?? 0 }}</p></div>
        <div class="stat-card stat-card-highlight"><p class="stat-label" style="color:#2563eb;font-weight:700;">A confirmer</p><p class="stat-value" style="color:#1e40af;">{{ $stats['awaiting_confirmation'] ?? 0 }}</p></div>
        <div class="stat-card"><p class="stat-label">Terminées</p><p class="stat-value" style="color:#16a34a;">{{ $stats['completed'] ?? 0 }}</p></div>
        <div class="stat-card"><p class="stat-label">Annulées</p><p class="stat-value" style="color:#dc2626;">{{ $stats['canceled'] ?? 0 }}</p></div>
    </div>

    <div class="table-card">
        <h2 class="table-title">Vos demandes d'intervention</h2>

        @if($reservations && $reservations->count() > 0)
            <div class="table-scroll">
                <table class="responsive-table">
                    <thead><tr>
                        <th>Service</th><th>Vidangeur</th><th>Date</th><th>Statut</th><th>Action</th>
                    </tr></thead>
                    <tbody>
                        @foreach($reservations as $r)
                        <tr>
                            <td data-label="Service">{{ optional($r->service)->name ?? 'Service' }}</td>
                            <td data-label="Vidangeur">{{ optional($r->user)->name ?? '?' }}</td>
                            <td data-label="Date">{{ optional($r->reservation_date)->format('d/m/Y H:i') }}</td>
                            <td data-label="Statut">
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
                                <span class="badge-custom" style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};">{{ $badge['label'] }}</span>
                            </td>
                            <td data-label="Action" class="action-cell">
                                @if($r->status === 'completed_vidangeur')
                                    <form action="{{ route('reservations.confirm', $r->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Confirmer la réception et effectuer le paiement de {{ number_format($r->service->price ?? 0, 0, ',', ' ') }} FCFA ?');">
                                        @csrf
                                        <button type="submit" class="btn-confirm">Confirmer</button>
                                    </form>
                                @elseif($r->status === 'accepted' || $r->status === 'pending')
                                    <span class="text-muted">En cours...</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if($r->user_id)
                                    @php $nb = $unreadCounts[$r->id] ?? 0; @endphp
                                    <a href="{{ route('chat.show', $r->id) }}" class="btn-chat {{ $nb ? 'btn-chat-active' : '' }}">
                                        Discuter
                                        @if($nb > 0)
                                            <span class="chat-badge">{{ $nb }}</span>
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
            <p class="empty-state">Aucune demande pour le moment.</p>
        @endif
    </div>
</div>
<style>
.menagere-actions { display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem; }
.btn-action { display:inline-block; padding:0.7rem 1.3rem; border-radius:1.5rem; font-weight:bold; text-decoration:none; text-align:center; font-size:0.95rem; flex:1; min-width:200px; }
.btn-purple { background:#7c3aed; color:#fff; box-shadow:0 2px 8px #7c3aed33; }
.btn-blue { background:#2563eb; color:#fff; box-shadow:0 2px 8px #2563eb33; }
.stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:0.75rem; margin-bottom:1.5rem; }
.stat-card { background:#fff; padding:0.75rem; border-radius:1rem; box-shadow:0 2px 8px #00000011; text-align:center; }
.stat-card-highlight { background:#eff6ff; border:2px solid #2563eb; }
.stat-label { color:#94a3b8; font-size:0.7rem; text-transform:uppercase; margin-bottom:4px; }
.stat-value { font-size:clamp(1.2rem, 5vw, 1.8rem); font-weight:bold; }
.table-card { background:#fff; border-radius:1rem; box-shadow:0 2px 8px #00000011; padding:1.2rem; }
.table-title { font-size:clamp(1rem, 3vw, 1.3rem); font-weight:bold; color:#1e3a8a; margin-bottom:0.75rem; }
.table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
.responsive-table { width:100%; border-collapse:collapse; font-size:0.85rem; }
.responsive-table th { text-align:left; padding:0.6rem 0.4rem; color:#64748b; font-size:0.8rem; border-bottom:2px solid #e5e7eb; }
.responsive-table td { padding:0.6rem 0.4rem; border-bottom:1px solid #f1f5f9; }
.badge-custom { display:inline-block; padding:0.15rem 0.5rem; border-radius:1rem; font-size:0.7rem; font-weight:600; }
.action-cell { display:flex; flex-wrap:wrap; gap:4px; align-items:center; }
.btn-confirm { padding:0.3rem 0.8rem; background:#7c3aed; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.8rem; }
.text-muted { color:#94a3b8; font-size:0.8rem; }
.btn-chat { display:inline-flex; align-items:center; gap:4px; padding:0.3rem 0.7rem; background:#f1f5f9; color:#475569; border-radius:8px; font-size:0.75rem; font-weight:600; text-decoration:none; }
.btn-chat-active { background:#dbeafe; color:#2563eb; }
.chat-badge { background:#ef4444; color:#fff; font-size:0.6rem; padding:1px 5px; border-radius:10px; font-weight:700; }
.empty-state { color:#94a3b8; text-align:center; padding:1.5rem; }
.alert-success { padding:10px 14px; border-radius:10px; background:#dcfce7; color:#16a34a; font-weight:600; margin-bottom:0.75rem; border:1px solid #bbf7d0; font-size:0.9rem; }
.alert-error { padding:10px 14px; border-radius:10px; background:#fee2e2; color:#dc2626; font-weight:600; margin-bottom:0.75rem; border:1px solid #fca5a5; font-size:0.9rem; }
.inline-form { display:inline; }
@media (max-width:600px) {
    .btn-action { min-width:100%; font-size:0.85rem; padding:0.6rem 1rem; }
    .stats-grid { grid-template-columns:1fr 1fr; gap:6px; }
    .stat-card { padding:0.5rem; }
    .stat-value { font-size:1.2rem; }
    .table-card { padding:0.8rem; }
    .responsive-table { font-size:0.75rem; }
    .responsive-table th, .responsive-table td { padding:0.4rem 0.3rem; }
}
</style>
@endsection
