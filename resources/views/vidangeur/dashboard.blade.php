@extends('layouts.app')

@section('title', 'Dashboard Vidangeur')

@section('sidebar')
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Interventions</a>
    <a href="{{ route('subscriptions.vidangeur') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Profil</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Historique</a>
@endsection

@section('content')
<div class="dashboard-wrapper">
    <div class="stats-row">
        <div class="stat-card-v total"><p class="stat-label-v">Total</p><p class="stat-value-v">{{ $stats['total'] ?? 0 }}</p></div>
        <div class="stat-card-v pending"><p class="stat-label-v">En attente</p><p class="stat-value-v">{{ $stats['pending'] ?? 0 }}</p></div>
        <div class="stat-card-v accepted"><p class="stat-label-v">Acceptées</p><p class="stat-value-v">{{ $stats['accepted'] ?? 0 }}</p></div>
        <div class="stat-card-v canceled"><p class="stat-label-v">Annulées</p><p class="stat-value-v">{{ $stats['canceled'] ?? 0 }}</p></div>
        <div class="stat-card-v awaiting"><p class="stat-label-v">A confirmer</p><p class="stat-value-v">{{ $stats['awaiting_confirmation'] ?? 0 }}</p></div>
        <div class="stat-card-v completed-v"><p class="stat-label-v">Terminées</p><p class="stat-value-v">{{ $stats['completed'] ?? 0 }}</p></div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Interventions</h2>

        @if(session('success'))
            <div class="alert-success-v">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error-v">{{ session('error') }}</div>
        @endif

        <div class="tabs-row">
            <button class="tab-btn-v active" data-tab="en-cours">
                En cours
                @if(($stats['pending']+$stats['accepted']+$stats['awaiting_confirmation']) > 0)
                    <span class="tab-count tab-count-active">{{ $stats['pending']+$stats['accepted']+$stats['awaiting_confirmation'] }}</span>
                @endif
            </button>
            <button class="tab-btn-v" data-tab="historique">
                Historique
                @if(($stats['completed']+$stats['canceled']) > 0)
                    <span class="tab-count">{{ $stats['completed']+$stats['canceled'] }}</span>
                @endif
            </button>
        </div>

        <div id="tab-en-cours" class="tab-panel">
            <div class="table-scroll">
                <table class="table-v">
                    <thead><tr>
                        <th>N°</th><th>Client</th><th>Service</th><th>Date</th><th>Statut</th><th>Actions</th>
                    </tr></thead>
                    <tbody>
                        @php $activeInterventions = $interventions->filter(fn($i) => in_array($i->status, ['pending', 'accepted', 'completed_vidangeur'])); @endphp
                        @forelse($activeInterventions as $intervention)
                            <tr>
                                <td data-label="N°">{{ $intervention->id }}</td>
                                <td data-label="Client">{{ optional($intervention->client)->name ?? 'Inconnu' }}</td>
                                <td data-label="Service">{{ optional($intervention->service)->name ?? 'N/A' }}</td>
                                <td data-label="Date">{{ optional($intervention->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td data-label="Statut">
                                    @php
                                        $label = match($intervention->status) {
                                            'pending' => 'En attente',
                                            'accepted' => 'Acceptée',
                                            'completed_vidangeur' => 'Attente client',
                                            default => $intervention->status
                                        };
                                        $cls = match($intervention->status) {
                                            'pending' => 'badge-v-pending',
                                            'accepted' => 'badge-v-accepted',
                                            'completed_vidangeur' => 'badge-v-waiting',
                                            default => 'badge-v-default'
                                        };
                                    @endphp
                                    <span class="badge-v {{ $cls }}">{{ $label }}</span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    @if($intervention->status === 'pending')
                                        <form method="POST" action="{{ route('reservations.accept', $intervention->id) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn-v btn-v-accept">Accepter</button>
                                        </form>
                                        <form method="POST" action="{{ route('reservations.cancel', $intervention->id) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn-v btn-v-reject">Refuser</button>
                                        </form>
                                    @endif
                                    @if($intervention->status === 'accepted')
                                        <form method="POST" action="{{ route('reservations.complete', $intervention->id) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn-v btn-v-done">Terminé</button>
                                        </form>
                                    @endif
                                    @if($intervention->status === 'completed_vidangeur')
                                        <span class="waiting-badge">Attente client</span>
                                    @endif
                                    @if($intervention->client_id)
                                        @php $nb = $unreadCounts[$intervention->id] ?? 0; @endphp
                                        <a href="{{ route('chat.show', $intervention->id) }}" class="btn-v-chat {{ $nb ? 'chat-active' : '' }}">
                                            Discuter
                                            @if($nb > 0)<span class="chat-count">{{ $nb }}</span>@endif
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-cell">Aucune intervention en cours.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-historique" class="tab-panel" style="display:none;">
            <div class="table-scroll">
                <table class="table-v">
                    <thead><tr>
                        <th>N°</th><th>Client</th><th>Service</th><th>Date</th><th>Montant</th><th>Statut</th>
                    </tr></thead>
                    <tbody>
                        @php $history = $interventions->filter(fn($i) => in_array($i->status, ['completed', 'canceled'])); @endphp
                        @forelse($history as $intervention)
                            <tr class="{{ $intervention->status === 'completed' ? 'row-completed' : 'row-canceled' }}">
                                <td data-label="N°">{{ $intervention->id }}</td>
                                <td data-label="Client">{{ optional($intervention->client)->name ?? 'Inconnu' }}</td>
                                <td data-label="Service">{{ optional($intervention->service)->name ?? 'N/A' }}</td>
                                <td data-label="Date">{{ optional($intervention->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td data-label="Montant">
                                    @if($intervention->status === 'completed' && $intervention->service)
                                        {{ number_format($intervention->service->price, 0, ',', ' ') }} FCFA
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="Statut">
                                    @if($intervention->status === 'completed')
                                        <span class="status-badge status-paid">Payée</span>
                                    @else
                                        <span class="status-badge status-canceled">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-cell">Aucun historique.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Statistiques avancées</h2>
        <div class="stats-row-sm">
            <div class="stat-card-v total"><p class="stat-label-v">Total</p><p class="stat-value-v">{{ $stats['total'] ?? 0 }}</p></div>
            <div class="stat-card-v pending"><p class="stat-label-v">En attente</p><p class="stat-value-v">{{ $stats['pending'] ?? 0 }}</p></div>
            <div class="stat-card-v accepted"><p class="stat-label-v">Acceptées</p><p class="stat-value-v">{{ $stats['accepted'] ?? 0 }}</p></div>
            <div class="stat-card-v canceled"><p class="stat-label-v">Annulées</p><p class="stat-value-v">{{ $stats['canceled'] ?? 0 }}</p></div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn-v').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn-v').forEach(function(b) {
            b.classList.remove('active');
        });
        this.classList.add('active');
        document.querySelectorAll('.tab-panel').forEach(function(tc) {
            tc.style.display = 'none';
        });
        document.getElementById('tab-' + this.dataset.tab).style.display = '';
    });
});
</script>
<style>
.stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:10px; margin-bottom:16px; }
.stats-row-sm { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:10px; }
.stat-card-v { border-radius:12px; padding:12px; color:#fff; box-shadow:0 4px 14px rgba(0,0,0,0.08); }
.stat-card-v.total { background:linear-gradient(90deg,#4f46e5,#3b82f6); }
.stat-card-v.pending { background:linear-gradient(90deg,#f59e0b,#f97316); }
.stat-card-v.accepted { background:linear-gradient(90deg,#10b981,#059669); }
.stat-card-v.canceled { background:linear-gradient(90deg,#ef4444,#b91c1c); }
.stat-card-v.awaiting { background:#ede9fe; color:#7c3aed; }
.stat-card-v.completed-v { background:#dbeafe; color:#1e3a8a; }
.stat-label-v { font-size:0.65rem; text-transform:uppercase; letter-spacing:0.05em; opacity:0.9; }
.stat-value-v { font-size:clamp(1.1rem,4vw,1.6rem); font-weight:bold; margin-top:2px; }
.section-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin-bottom:14px; }
.section-title { font-size:clamp(1rem,3vw,1.2rem); font-weight:bold; color:#1f2937; margin-bottom:10px; }
.alert-success-v { padding:8px 12px; border-radius:8px; border:1px solid #16a34a; background:#dcfce7; color:#065f46; margin-bottom:12px; font-size:0.85rem; }
.alert-error-v { padding:8px 12px; border-radius:8px; border:1px solid #dc2626; background:#fef2f2; color:#991b1b; margin-bottom:12px; font-size:0.85rem; }
.tabs-row { display:flex; gap:0; margin-bottom:12px; border-bottom:2px solid #e5e7eb; overflow-x:auto; }
.tab-btn-v { padding:0.5rem 1rem; border:none; background:none; font-weight:500; color:#6b7280; border-bottom:2px solid transparent; margin-bottom:-2px; cursor:pointer; font-size:0.85rem; white-space:nowrap; transition:all 0.2s; }
.tab-btn-v.active { color:#4f46e5; border-bottom-color:#4f46e5; font-weight:600; }
.tab-count { background:#9ca3af; color:#fff; font-size:0.6rem; padding:1px 6px; border-radius:10px; margin-left:4px; font-weight:700; }
.tab-count-active { background:#4f46e5; }
.table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
.table-v { width:100%; border-collapse:collapse; font-size:0.85rem; }
.table-v th { text-align:left; padding:8px 6px; color:#64748b; font-size:0.75rem; border-bottom:2px solid #e5e7eb; text-transform:uppercase; }
.table-v td { padding:8px 6px; border-bottom:1px solid #e5e7eb; }
.badge-v { display:inline-block; padding:2px 8px; border-radius:999px; font-size:0.65rem; font-weight:700; text-transform:uppercase; }
.badge-v-pending { background:#fef3c7; color:#92400e; }
.badge-v-accepted { background:#d1fae5; color:#064e3b; }
.badge-v-waiting { background:#ede9fe; color:#6d28d9; }
.badge-v-default { background:#f1f5f9; color:#475569; }
.actions-cell { display:flex; flex-wrap:wrap; gap:4px; align-items:center; }
.inline-form { display:inline; }
.btn-v { border:none; padding:4px 10px; border-radius:6px; font-size:0.7rem; cursor:pointer; color:#fff; font-weight:600; }
.btn-v-accept { background:#059669; }
.btn-v-reject { background:#dc2626; }
.btn-v-done { background:#2563eb; }
.waiting-badge { display:inline-block; padding:3px 8px; background:#ede9fe; color:#7c3aed; border-radius:8px; font-size:0.7rem; font-weight:600; }
.btn-v-chat { display:inline-flex; align-items:center; gap:3px; padding:3px 8px; background:#e5e7eb; color:#374151; border-radius:6px; font-size:0.7rem; text-decoration:none; font-weight:600; }
.chat-active { background:#dbeafe; color:#2563eb; }
.chat-count { background:#ef4444; color:#fff; font-size:0.55rem; padding:1px 5px; border-radius:8px; font-weight:700; }
.empty-cell { padding:16px; text-align:center; color:#9ca3af; }
.row-completed { background:#f0fdf4; }
.row-canceled { background:#fafafa; }
.status-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:8px; font-size:0.7rem; font-weight:600; }
.status-paid { background:#dcfce7; color:#166534; }
.status-canceled { background:#fef2f2; color:#991b1b; }
@media (max-width:600px) {
    .stats-row { grid-template-columns:1fr 1fr; gap:6px; }
    .stats-row-sm { grid-template-columns:1fr 1fr; gap:6px; }
    .stat-card-v { padding:8px; }
    .stat-value-v { font-size:1.1rem; }
    .table-v { font-size:0.7rem; }
    .table-v th, .table-v td { padding:5px 3px; }
    .tab-btn-v { font-size:0.75rem; padding:0.4rem 0.7rem; }
    .btn-v { font-size:0.65rem; padding:3px 7px; }
}
</style>
@endsection
