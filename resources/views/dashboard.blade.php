@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="mb-6 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-6 shadow-lg">
        <h1 class="text-3xl font-bold">Bienvenue, {{ auth()->user()->name }}</h1>
        <p class="mt-2 text-indigo-100">Votre rôle :
            <span class="font-semibold">
                {{ auth()->user()->role == 'vidangeur' ? 'Vidangeur' : (auth()->user()->role == 'menagere' ? 'Ménagère' : 'Non défini') }}
            </span>
        </p>
    </div>

    <div class="grid gap-4 md:grid-cols-3 mb-6">
        <div class="rounded-lg bg-blue-50 border border-blue-100 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-blue-600">Total utilisateurs</h2>
            <p class="mt-2 text-2xl font-bold text-blue-800">{{ $totalUsers }}</p>
        </div>
        <div class="rounded-lg bg-emerald-50 border border-emerald-100 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Vidangeurs actifs</h2>
            <p class="mt-2 text-2xl font-bold text-emerald-800">{{ $activeVidangeurs }}</p>
        </div>
        <div class="rounded-lg bg-orange-50 border border-orange-100 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-orange-600">Total transactions</h2>
            <p class="mt-2 text-2xl font-bold text-orange-700">{{ $totalTransactions }}</p>
        </div>
    </div>

    <div class="rounded-lg bg-white border border-gray-200 p-5 shadow-sm">
        <div class="mb-4 text-sm text-gray-600">Détails du profil</div>
        <div class="space-y-2">
            <div class="text-gray-700"><strong>Type d'utilisateur :</strong> {{ auth()->user()->role == 'vidangeur' ? 'Vidangeur' : (auth()->user()->role == 'menagere' ? 'Ménagère' : 'Non défini') }}</div>
            @if(auth()->user()->role == 'vidangeur')
                <div class="text-gray-700"><strong>Tarif :</strong> {{ auth()->user()->tarif ?? 'Non renseigné' }}</div>
                <div class="text-gray-700"><strong>Disponibilités :</strong> {{ auth()->user()->disponibilite ?? 'Non renseigné' }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
