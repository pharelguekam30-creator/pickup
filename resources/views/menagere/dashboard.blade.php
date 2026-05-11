@extends('layouts.app')

@section('title', 'Dashboard Ménagère')

@section('sidebar')
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Réservations</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Profil</a>
    <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Historique</a>
@endsection

@section('content')
<div class="dashboard-wrapper">
    <div style="margin-bottom:1rem;color:#b91c1c;font-weight:bold;">
        Rôle utilisateur connecté : <span style="color:#1e40af;">{{ Auth::user()->role ?? 'non défini' }}</span>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-bottom:2rem;">
        <a href="{{ route('reservations.create') }}" style="font-size:1.2rem;padding:1rem 2.5rem;background:#2563eb;color:#fff;border-radius:1.5rem;font-weight:bold;box-shadow:0 2px 8px #2563eb33;transition:background .2s;" class="hover:bg-blue-700">+ Nouvelle demande d’intervention</a>
    </div>
    <div class="dashboard-top">
        <div class="dashboard-card total">
            <p class="text-xs uppercase tracking-wide">Réservations totales</p>
            <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card pending">
            <p class="text-xs uppercase tracking-wide">En attente</p>
            <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="dashboard-card accepted">
            <p class="text-xs uppercase tracking-wide">Confirmées</p>
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
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h2>Vos demandes d’intervention</h2>
            <a href="{{ route('reservations.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Nouvelle demande</a>
        </div>

        <ul style="list-style: disc; margin-left: 1rem; color: #374151;">
            @foreach($reservations ?? collect() as $reservation)
                <li>
                    {{ optional($reservation->service)->name ?? 'Service inconnu' }} -
                    {{ optional($reservation->reservation_date)->format('Y-m-d H:i') ?? 'Date inconnue' }} -
                    {{ ucfirst($reservation->status ?? 'pending') }}
                </li>
            @endforeach
            @unless($reservations && $reservations->count())
                <li>Aucune demande disponible.</li>
            @endunless
        </ul>
    </div>

    <div class="dashboard-section">
        <h2>Statistiques détaillées</h2>
        <p style="color:#4b5563; margin: 0.5rem 0;">Nombre de réservations : {{ $stats['total'] ?? 0 }}</p>
        <p style="color:#4b5563; margin: 0.5rem 0;">Réservations en attente : {{ $stats['pending'] ?? 0 }}</p>
        <p style="color:#4b5563; margin: 0.5rem 0;">Acceptées : {{ $stats['accepted'] ?? 0 }}</p>
        <p style="color:#4b5563; margin: 0.5rem 0;">Terminées : {{ $stats['completed'] ?? 0 }}</p>
        <p style="color:#4b5563; margin: 0.5rem 0;">Annulées : {{ $stats['canceled'] ?? 0 }}</p>
    </div>
</div>
@endsection
