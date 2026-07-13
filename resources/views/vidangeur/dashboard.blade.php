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
    <div class="dashboard-top">
        <div class="dashboard-card total">
            <p class="text-xs uppercase tracking-wide">Total Interventions</p>
            <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card pending">
            <p class="text-xs uppercase tracking-wide">En attente</p>
            <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card accepted">
            <p class="text-xs uppercase tracking-wide">Acceptées</p>
            <p class="text-3xl font-bold">{{ $stats['accepted'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card canceled">
            <p class="text-xs uppercase tracking-wide">Annulées</p>
            <p class="text-3xl font-bold">{{ $stats['canceled'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card" style="background:#ede9fe;color:#7c3aed;">
            <p class="text-xs uppercase tracking-wide">A confirmer</p>
            <p class="text-3xl font-bold">{{ $stats['awaiting_confirmation'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card completed" style="background:#dbeafe;color:#1e3a8a;">
            <p class="text-xs uppercase tracking-wide">Terminées</p>
            <p class="text-3xl font-bold">{{ $stats['completed'] ?? 0 }}</p>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Interventions</h2>

        @if(session('success'))
            <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 rounded" style="border:1px solid #dc2626;background:#fef2f2;color:#991b1b;">{{ session('error') }}</div>
        @endif

        <div class="tabs" style="display:flex;gap:0;margin-bottom:1.5rem;border-bottom:2px solid #e5e7eb;">
            <button class="tab-btn active" data-tab="en-cours" style="padding:0.75rem 1.5rem;border:none;background:none;font-weight:600;color:#4f46e5;border-bottom:2px solid #4f46e5;margin-bottom:-2px;cursor:pointer;transition:all 0.2s;font-size:0.95rem;">
                En cours
                @if(($stats['pending']+$stats['accepted']+$stats['awaiting_confirmation']) > 0)
                    <span style="background:#4f46e5;color:#fff;font-size:0.7rem;padding:1px 7px;border-radius:10px;margin-left:5px;font-weight:700;">{{ $stats['pending']+$stats['accepted']+$stats['awaiting_confirmation'] }}</span>
                @endif
            </button>
            <button class="tab-btn" data-tab="historique" style="padding:0.75rem 1.5rem;border:none;background:none;font-weight:500;color:#6b7280;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;transition:all 0.2s;font-size:0.95rem;">
                Historique
                @if(($stats['completed']+$stats['canceled']) > 0)
                    <span style="background:#9ca3af;color:#fff;font-size:0.7rem;padding:1px 7px;border-radius:10px;margin-left:5px;font-weight:700;">{{ $stats['completed']+$stats['canceled'] }}</span>
                @endif
            </button>
        </div>

        <div id="tab-en-cours" class="tab-content">
            <div class="overflow-x-auto">
                <table class="dashboard-table table-auto text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">N°</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Date prévue</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $activeInterventions = $interventions->filter(fn($i) => in_array($i->status, ['pending', 'accepted', 'completed_vidangeur'])); @endphp
                        @forelse($activeInterventions as $intervention)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $intervention->id }}</td>
                                <td class="px-4 py-3">{{ optional($intervention->client)->name ?? 'Inconnu' }}</td>
                                <td class="px-4 py-3">{{ optional($intervention->service)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ optional($intervention->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        ['badge' => $badge, 'label' => $label] = match($intervention->status) {
                                            'pending' => ['badge' => 'badge-pending', 'label' => 'En attente'],
                                            'accepted' => ['badge' => 'badge-accepted', 'label' => 'Acceptée'],
                                            'completed_vidangeur' => ['badge' => 'bg-purple-100 text-purple-700', 'label' => 'Attente client'],
                                            default => ['badge' => 'bg-gray-100 text-gray-600', 'label' => $intervention->status]
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    @if($intervention->status === 'pending')
                                        <form method="POST" action="{{ route('reservations.accept', $intervention->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white hover:bg-green-700 transition">Accepter</button>
                                        </form>
                                    @endif

                                    @if($intervention->status === 'accepted')
                                        <form method="POST" action="{{ route('reservations.complete', $intervention->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">Terminé</button>
                                        </form>
                                    @endif

                                    @if($intervention->status === 'completed_vidangeur')
                                        <span style="display:inline-block;padding:.3rem .6rem;background:#ede9fe;color:#7c3aed;border-radius:8px;font-size:.8rem;font-weight:600;">Attente client</span>
                                    @endif

                                    @if($intervention->status === 'pending')
                                        <form method="POST" action="{{ route('reservations.cancel', $intervention->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 transition">Refuser</button>
                                        </form>
                                    @endif

                                    @if($intervention->client_id)
                                        @php $nb = $unreadCounts[$intervention->id] ?? 0; @endphp
                                        <a href="{{ route('chat.show', $intervention->id) }}" class="px-3 py-1 rounded inline-block text-center" style="font-size:.85rem;text-decoration:none;background:{{ $nb ? '#dbeafe' : '#e5e7eb' }};color:{{ $nb ? '#2563eb' : '#374151' }};display:inline-flex;align-items:center;gap:4px;">
                                            Discuter
                                            @if($nb > 0)
                                                <span style="background:#ef4444;color:#fff;font-size:.65rem;padding:1px 6px;border-radius:10px;font-weight:700;">{{ $nb }}</span>
                                            @endif
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-5 text-center text-gray-500">Aucune intervention en cours.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-historique" class="tab-content" style="display:none;">
            <div class="overflow-x-auto">
                <table class="dashboard-table table-auto text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">N°</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Montant</th>
                            <th class="px-4 py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $history = $interventions->filter(fn($i) => in_array($i->status, ['completed', 'canceled'])); @endphp
                        @forelse($history as $intervention)
                            <tr class="border-b" style="{{ $intervention->status === 'completed' ? 'background:#f0fdf4;' : 'background:#fafafa;' }}">
                                <td class="px-4 py-3" style="color:#6b7280;">{{ $intervention->id }}</td>
                                <td class="px-4 py-3" style="color:#6b7280;">{{ optional($intervention->client)->name ?? 'Inconnu' }}</td>
                                <td class="px-4 py-3" style="color:#6b7280;">{{ optional($intervention->service)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3" style="color:#6b7280;">{{ optional($intervention->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td class="px-4 py-3" style="color:#6b7280;">
                                    @if($intervention->status === 'completed' && $intervention->service)
                                        {{ number_format($intervention->service->price, 0, ',', ' ') }} FCFA
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($intervention->status === 'completed')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.25rem .6rem;background:#dcfce7;color:#166534;border-radius:8px;font-size:.8rem;font-weight:600;">
                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Payée
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.25rem .6rem;background:#fef2f2;color:#991b1b;border-radius:8px;font-size:.8rem;font-weight:600;">
                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                            Annulée
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-5 text-center text-gray-500">Aucun historique.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Statistiques avancées</h2>
        <div class="dashboard-top" style="grid-template-columns: repeat(auto-fit,minmax(180px,1fr));">
            <div class="dashboard-card total" style="color:#FFF; box-shadow:0 4px 10px rgba(79,70,229,.25);">
                <p class="text-xs uppercase tracking-wide">Interventions totales</p>
                <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="dashboard-card pending" style="color:#FFF; box-shadow:0 4px 10px rgba(245,158,11,.25);">
                <p class="text-xs uppercase tracking-wide">En attente</p>
                <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="dashboard-card accepted" style="color:#FFF; box-shadow:0 4px 10px rgba(16,185,129,.25);">
                <p class="text-xs uppercase tracking-wide">Acceptées</p>
                <p class="text-3xl font-bold">{{ $stats['accepted'] ?? 0 }}</p>
            </div>
            <div class="dashboard-card canceled" style="color:#FFF; box-shadow:0 4px 10px rgba(239,68,68,.25);">
                <p class="text-xs uppercase tracking-wide">Annulées</p>
                <p class="text-3xl font-bold">{{ $stats['canceled'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.style.color = '#6b7280';
            b.style.borderBottomColor = 'transparent';
            b.style.fontWeight = '500';
        });
        this.style.color = '#4f46e5';
        this.style.borderBottomColor = '#4f46e5';
        this.style.fontWeight = '600';
        document.querySelectorAll('.tab-content').forEach(function(tc) {
            tc.style.display = 'none';
        });
        document.getElementById('tab-' + this.dataset.tab).style.display = '';
    });
});
</script>
@endsection
