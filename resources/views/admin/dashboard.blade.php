<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">
    <h1 class="text-2xl font-bold mb-6 text-blue-700 flex items-center">
        <i class="fa fa-chart-bar me-2"></i> Dashboard Administrateur
    </h1>

    <div class="dashboard-top">
        <!-- Carte: Utilisateurs -->
        <div class="dashboard-card total flex flex-col justify-between">
            <div class="flex items-center mb-2">
                <i class="fa fa-users fa-2x me-3"></i>
                <span class="font-semibold text-lg">Utilisateurs</span>
            </div>
            <div class="text-2xl font-bold">{{ \App\Models\User::count() }}</div>
            <a href="{{ route('users.index') }}" class="text-indigo-100 text-link mt-2">Voir tous <i class="fa fa-arrow-right"></i></a>
        </div>

        <!-- Carte: Services -->
        <div class="dashboard-card accepted flex flex-col justify-between">
            <div class="flex items-center mb-2">
                <i class="fa fa-cogs fa-2x me-3"></i>
                <span class="font-semibold text-lg">Services</span>
            </div>
            <div class="text-2xl font-bold">{{ \App\Models\Service::count() }}</div>
            <a href="{{ route('services.index') }}" class="text-indigo-100 text-link mt-2">Voir tous <i class="fa fa-arrow-right"></i></a>
        </div>

        <!-- Carte: Réservations -->
        <div class="dashboard-card pending flex flex-col justify-between">
            <div class="flex items-center mb-2">
                <i class="fa fa-calendar-check fa-2x me-3"></i>
                <span class="font-semibold text-lg">Réservations</span>
            </div>
            <div class="text-2xl font-bold">{{ \App\Models\Reservation::count() }}</div>
            <a href="{{ route('reservations.index') }}" class="text-indigo-100 text-link mt-2">Voir tous <i class="fa fa-arrow-right"></i></a>
        </div>

        <!-- Carte: Avis -->
        <div class="dashboard-card canceled flex flex-col justify-between">
            <div class="flex items-center mb-2">
                <i class="fa fa-star fa-2x me-3"></i>
                <span class="font-semibold text-lg">Avis</span>
            </div>
            <div class="text-2xl font-bold">{{ \App\Models\Avis::count() }}</div>
            <a href="{{ route('avis.index') }}" class="text-indigo-100 text-link mt-2">Voir tous <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- Section Actions -->
    <div class="dashboard-section mt-4">
        <h2 class="font-semibold mb-4"><i class="fa fa-cogs me-2"></i>Gestion</h2>
        <ul class="list-unstyled">
            <li class="mb-2">
                <a href="{{ route('users.index') }}" class="text-link"><i class="fa fa-users me-1"></i> Gestion des utilisateurs</a>
            </li>
            <li class="mb-2">
                <a href="{{ route('services.index') }}" class="text-link"><i class="fa fa-cogs me-1"></i> Gestion des services</a>
            </li>
            <li class="mb-2">
                <a href="{{ route('roles.index') }}" class="text-link"><i class="fa fa-user-shield me-1"></i> Gestion des rôles</a>
            </li>
            <li>
                <a href="{{ route('reservations.index') }}" class="text-link"><i class="fa fa-calendar-check me-1"></i> Gestion des réservations</a>
            </li>
        </ul>
    </div>

    <!-- Espace pour graphiques/statistiques (optionnel) -->
    <!-- <div class="dashboard-section mt-4">
        <h2 class="font-semibold mb-4"><i class="fa fa-chart-pie me-2"></i>Statistiques</h2>
        <div style="min-height:180px;">[Graphique ici]</div>
    </div> -->
</div>
@endsection
