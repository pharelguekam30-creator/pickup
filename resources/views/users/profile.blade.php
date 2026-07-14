@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="dashboard-wrapper" style="max-width:800px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">

    @if (session('success'))
        <div style="padding:12px 16px;border-radius:10px;background:#dcfce7;color:#16a34a;font-weight:600;margin-bottom:1rem;border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    {{-- En-tête du profil --}}
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
        @if($user->photo)
            <img src="{{ asset($user->photo) }}" alt="Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #2563eb;flex-shrink:0;">
        @else
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.2rem;font-weight:bold;flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div style="flex:1;">
            <h2 style="font-size:clamp(1.3rem,5vw,1.8rem);font-weight:bold;color:#1e3a8a;margin-bottom:.25rem;">{{ $user->name }}</h2>
            <p style="color:#64748b;">
                <span style="display:inline-block;padding:.2rem .8rem;border-radius:1rem;background:#dbeafe;color:#1e40af;font-size:.8rem;font-weight:600;">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
        </div>
        <a href="{{ route('profile.edit') }}" style="padding:.6rem 1.2rem;background:#2563eb;color:#fff;border-radius:1rem;text-decoration:none;font-weight:600;font-size:.9rem;">
            ✏️ Modifier
        </a>
    </div>

    {{-- Informations personnelles --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Email</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->email }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Téléphone</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->phone ?? 'Non renseigné' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Ville</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->city ?? 'Non renseigné' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Quartier</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->quarter ?? 'Non renseigné' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Adresse</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->address ?? 'Non renseigné' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Membre depuis</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Infos spécifiques au rôle --}}
    @if($user->role === 'vidangeur')
    <div style="background:linear-gradient(135deg,#ecfdf5,#f0fdf4);border-radius:1rem;padding:1.25rem;margin-bottom:2rem;">
        <h3 style="color:#065f46;font-weight:600;margin-bottom:1rem;">📋 Infos Vidangeur</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
            <div>
                <p style="color:#6b7280;font-size:.85rem;">Tarif</p>
                <p style="color:#065f46;font-weight:700;font-size:1.1rem;">{{ $user->tarif ?? 'Non défini' }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:.85rem;">Disponibilité</p>
                <p style="color:#065f46;font-weight:600;">{{ $user->disponibilite ?? 'Non définie' }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Solde --}}
    <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:1rem;padding:1.25rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <p style="color:#1e40af;font-weight:600;">Solde disponible</p>
            <p style="font-size:clamp(1.2rem,5vw,1.8rem);font-weight:bold;color:#2563eb;">{{ number_format($user->solde ?? 0, 0, ',', ' ') }} FCFA</p>
        </div>
        <a href="{{ route('payments.index') }}" style="padding:.6rem 1.2rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">
            Gerer mon portefeuille
        </a>
    </div>

    {{-- Dernières réservations --}}
    <div style="border-top:1px solid #e5e7eb;padding-top:1.5rem;">
        <h3 style="color:#1e3a8a;font-weight:600;margin-bottom:1rem;">
            📅 Mes dernières réservations
        </h3>
        @if($user->reservations->count() > 0)
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                @foreach($user->reservations->sortByDesc('reservation_date')->take(5) as $reservation)
                    <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;padding:.75rem 1rem;border-radius:.75rem;border-left:4px solid {{ $reservation->status === 'completed' ? '#16a34a' : ($reservation->status === 'accepted' ? '#2563eb' : ($reservation->status === 'canceled' ? '#dc2626' : '#f59e0b')) }};">
                        <div>
                            <span style="font-weight:500;color:#1e293b;">{{ optional($reservation->service)->name ?? 'Service' }}</span>
                            <span style="color:#94a3b8;font-size:.85rem;margin-left:.5rem;">
                                {{ optional($reservation->reservation_date)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <span style="font-size:.75rem;font-weight:600;padding:.2rem .6rem;border-radius:1rem;background:{{ $reservation->status === 'completed' ? '#dcfce7' : ($reservation->status === 'accepted' ? '#dbeafe' : ($reservation->status === 'canceled' ? '#fee2e2' : '#fef3c7')) }};color:{{ $reservation->status === 'completed' ? '#16a34a' : ($reservation->status === 'accepted' ? '#2563eb' : ($reservation->status === 'canceled' ? '#dc2626' : '#d97706')) }};">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color:#94a3b8;text-align:center;padding:1.5rem;">Aucune réservation pour le moment.</p>
        @endif
    </div>
</div>
@endsection