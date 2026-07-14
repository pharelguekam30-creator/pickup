@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Dashboard</a>
    <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Utilisateurs</a>
    <a href="{{ route('services.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Services</a>
    <a href="{{ route('admin.plans.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Plans de collecte</a>
    <a href="{{ route('admin.subscriptions') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="{{ route('reservations.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Réservations</a>
    <a href="{{ route('admin.stats') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Statistiques</a>
@endsection

@section('content')
<div class="dashboard-wrapper">
    @if (session('success'))
        <div class="alert-success-v">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error-v">{{ session('error') }}</div>
    @endif

    <h2 class="admin-title">Dashboard Administrateur</h2>

    <div class="admin-stats">
        <div class="admin-stat-card"><p class="admin-stat-label">Utilisateurs</p><p class="admin-stat-value">{{ \App\Models\User::count() }}</p><a href="{{ route('users.index') }}" class="admin-stat-link">Gerer</a></div>
        <div class="admin-stat-card"><p class="admin-stat-label">Services</p><p class="admin-stat-value">{{ \App\Models\Service::count() }}</p><a href="{{ route('services.index') }}" class="admin-stat-link">Gerer</a></div>
        <div class="admin-stat-card"><p class="admin-stat-label">Réservations</p><p class="admin-stat-value">{{ \App\Models\Reservation::count() }}</p><a href="{{ route('reservations.index') }}" class="admin-stat-link">Gérer</a></div>
        <div class="admin-stat-card"><p class="admin-stat-label">Plans de collecte</p><p class="admin-stat-value">{{ \App\Models\CollectionPlan::count() }}</p><a href="{{ route('admin.plans.index') }}" class="admin-stat-link">Gérer</a></div>
        <div class="admin-stat-card admin-stat-highlight"><p class="admin-stat-label" style="color:#7c3aed;font-weight:700;">A confirmer</p><p class="admin-stat-value" style="color:#5b21b6;">{{ \App\Models\Reservation::where('status', 'completed_vidangeur')->count() }}</p></div>
    </div>

    @php
        $pendingConfirmations = \App\Models\Reservation::with(['client', 'user', 'service'])
            ->where('status', 'completed_vidangeur')
            ->orderBy('updated_at', 'desc')
            ->get();
    @endphp

    @if($pendingConfirmations->count() > 0)
    <div class="admin-section">
        <h3 class="admin-section-title">Interventions en attente de confirmation</h3>
        <div class="table-scroll">
            <table class="admin-table">
                <thead><tr>
                    <th>N</th><th>Client</th><th>Vidangeur</th><th>Service</th><th>Montant</th><th>Action</th>
                </tr></thead>
                <tbody>
                    @foreach($pendingConfirmations as $r)
                    <tr>
                        <td data-label="N">#{{ $r->id }}</td>
                        <td data-label="Client">{{ optional($r->client)->name ?? '?' }}</td>
                        <td data-label="Vidangeur">{{ optional($r->user)->name ?? '?' }}</td>
                        <td data-label="Service">{{ optional($r->service)->name ?? '?' }}</td>
                        <td data-label="Montant" class="admin-price">{{ number_format(optional($r->service)->price ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td data-label="Action" class="admin-action-cell">
                            @php
                                $unreadMsg = \App\Models\Message::where('reservation_id', $r->id)->where('sender_id', '!=', auth()->id())->where('is_read', 0)->count();
                            @endphp
                            <form action="{{ route('admin.forceComplete', $r->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Forcer le paiement ?')">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-pay">Payer</button>
                            </form>
                            <form action="{{ route('admin.forceCancel', $r->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Annuler cette intervention ?')">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-cancel">Annuler</button>
                            </form>
                            <a href="{{ route('chat.show', $r->id) }}" class="admin-chat-link {{ $unreadMsg ? 'admin-chat-unread' : '' }}">
                                Discuter
                                @if($unreadMsg > 0)
                                    <span class="admin-chat-badge">{{ $unreadMsg }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="admin-links">
        <a href="{{ route('users.index') }}" class="admin-link">Utilisateurs</a>
        <a href="{{ route('services.index') }}" class="admin-link">Services</a>
        <a href="{{ route('roles.index') }}" class="admin-link">Roles</a>
        <a href="{{ route('reservations.index') }}" class="admin-link">Reservations</a>
        <a href="{{ route('payments.index') }}" class="admin-link">Portefeuille</a>
        <a href="{{ route('admin.stats') }}" class="admin-link admin-link-green">Statistiques</a>
    </div>
</div>
<style>
.admin-title { font-size:clamp(1.1rem,3vw,1.5rem); font-weight:bold; color:#1d4ed8; margin-bottom:1rem; }
.admin-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:0.75rem; margin-bottom:1.5rem; }
.admin-stat-card { background:#fff; padding:0.75rem; border-radius:1rem; box-shadow:0 2px 8px #00000011; }
.admin-stat-highlight { background:#ede9fe; border:2px solid #7c3aed; }
.admin-stat-label { color:#94a3b8; font-size:0.7rem; margin-bottom:2px; }
.admin-stat-value { font-size:clamp(1.2rem,5vw,1.8rem); font-weight:bold; color:#1e293b; }
.admin-stat-link { font-size:0.8rem; color:#2563eb; text-decoration:none; }
.admin-section { background:#fff; border-radius:1rem; box-shadow:0 2px 8px #00000011; padding:1.2rem; margin-bottom:1.5rem; }
.admin-section-title { font-size:clamp(0.95rem,3vw,1.1rem); font-weight:bold; color:#7c3aed; margin-bottom:0.75rem; }
.table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
.admin-table { width:100%; border-collapse:collapse; font-size:0.8rem; }
.admin-table th { text-align:left; padding:0.5rem 0.4rem; color:#64748b; font-size:0.75rem; border-bottom:2px solid #e5e7eb; }
.admin-table td { padding:0.5rem 0.4rem; border-bottom:1px solid #f1f5f9; }
.admin-price { font-weight:600; }
.admin-action-cell { display:flex; flex-wrap:wrap; gap:4px; align-items:center; }
.admin-btn { border:none; padding:0.25rem 0.6rem; border-radius:6px; font-weight:600; cursor:pointer; color:#fff; font-size:0.7rem; }
.admin-btn-pay { background:#16a34a; }
.admin-btn-cancel { background:#dc2626; }
.admin-chat-link { display:inline-flex; align-items:center; gap:3px; padding:0.25rem 0.6rem; background:#f1f5f9; color:#475569; border-radius:6px; font-size:0.7rem; font-weight:600; text-decoration:none; }
.admin-chat-unread { background:#dbeafe; color:#2563eb; }
.admin-chat-badge { background:#ef4444; color:#fff; font-size:0.55rem; padding:1px 4px; border-radius:8px; font-weight:700; }
.admin-links { display:flex; gap:0.6rem; flex-wrap:wrap; }
.admin-link { padding:0.6rem 1.2rem; background:#2563eb; color:#fff; border-radius:10px; text-decoration:none; font-weight:600; font-size:0.85rem; }
.admin-link-green { background:#16a34a; }
.alert-success-v { padding:8px 12px; border-radius:8px; border:1px solid #16a34a; background:#dcfce7; color:#065f46; margin-bottom:12px; font-size:0.85rem; }
.alert-error-v { padding:8px 12px; border-radius:8px; border:1px solid #dc2626; background:#fef2f2; color:#991b1b; margin-bottom:12px; font-size:0.85rem; }
.inline-form { display:inline; }
@media (max-width:600px) {
    .admin-stats { grid-template-columns:1fr 1fr; gap:6px; }
    .admin-stat-card { padding:0.5rem; }
    .admin-stat-value { font-size:1.2rem; }
    .admin-table { font-size:0.7rem; }
    .admin-table th, .admin-table td { padding:0.35rem 0.25rem; }
    .admin-section { padding:0.8rem; }
    .admin-link { font-size:0.75rem; padding:0.5rem 0.8rem; }
}
</style>
@endsection
