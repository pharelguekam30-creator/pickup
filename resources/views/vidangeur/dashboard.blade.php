@extends('layouts.app')

@section('title', 'Dashboard Vidangeur')

@section('sidebar')
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Interventions</a>
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
        <div class="dashboard-card completed" style="background:#dbeafe;color:#1e3a8a;">
            <p class="text-xs uppercase tracking-wide">Terminées</p>
            <p class="text-3xl font-bold">{{ $stats['completed'] ?? 0 }}</p>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Interventions récentes</h2>

        @if(session('success'))
            <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
        @endif

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
                    @forelse($interventions as $intervention)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $intervention->id }}</td>
                            <td class="px-4 py-3">{{ optional($intervention->user)->name ?? 'Inconnu' }}</td>
                            <td class="px-4 py-3">{{ optional($intervention->service)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ optional($intervention->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = 'badge-pending';
                                    if($intervention->status === 'accepted') $badge = 'badge-accepted';
                                    if($intervention->status === 'pending') $badge = 'badge-pending';
                                    if($intervention->status === 'canceled') $badge = 'badge-canceled';
                                    if($intervention->status === 'completed') $badge = 'badge-completed';
                                @endphp
                                <span class="{{ $badge }}">{{ $intervention->status ?? 'pending' }}</span>
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

                                <form method="POST" action="{{ route('reservations.cancel', $intervention->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 transition">Refuser</button>
                                </form>

                                <form method="POST" action="{{ route('reservations.destroy', $intervention->id) }}" class="inline" onsubmit="return confirm('Supprimer cette intervention ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded bg-gray-600 text-white hover:bg-gray-700 transition">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-5 text-center text-gray-500">Aucune intervention disponible.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
@endsection
