@extends('layouts.app')
@section('title', 'Profil de ' . $user->name)
@section('content')
<div class="dashboard-wrapper" style="max-width:800px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-size:clamp(1.5rem,5vw,2rem);font-weight:bold;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div style="flex:1;">
            <h2 style="font-size:clamp(1.3rem,5vw,1.8rem);font-weight:bold;color:#1e3a8a;">{{ $user->name }}</h2>
            <span style="display:inline-block;padding:.2rem .8rem;border-radius:1rem;background:#dbeafe;color:#1e40af;font-size:.8rem;font-weight:600;">{{ ucfirst($user->role) }}</span>
        </div>
        <a href="{{ route('users.edit', $user->id) }}" style="padding:.6rem 1.2rem;background:#f59e0b;color:#fff;border-radius:1rem;text-decoration:none;font-weight:600;">Modifier</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Email</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->email }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Téléphone</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->phone ?? '-' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Ville</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->city ?? '-' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Quartier</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->quarter ?? '-' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Adresse</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->address ?? '-' }}</p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;">Membre depuis</p>
            <p style="color:#1e293b;font-weight:500;">{{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    @if($user->role === 'vidangeur')
    <div style="background:#ecfdf5;border-radius:1rem;padding:1.25rem;margin-bottom:2rem;">
        <h3 style="color:#065f46;margin-bottom:1rem;">Infos Vidangeur</h3>
        <p><strong>Tarif :</strong> {{ $user->tarif ?? 'Non défini' }}</p>
        <p><strong>Disponibilité :</strong> {{ $user->disponibilite ?? 'Non définie' }}</p>
    </div>
    @endif

    <h3 style="color:#1e3a8a;margin-bottom:1rem;">Réservations ({{ $user->reservations->count() }})</h3>
    @if($user->reservations->count() > 0)
    <ul style="list-style:none;padding:0;">
        @foreach($user->reservations->sortByDesc('reservation_date') as $reservation)
            <li style="padding:.5rem .75rem;background:#f8fafc;border-radius:.5rem;margin-bottom:.5rem;border-left:4px solid #2563eb;">
                {{ optional($reservation->service)->name ?? 'Service' }} —
                {{ optional($reservation->reservation_date)->format('d/m/Y H:i') }} —
                <span style="font-weight:600;">{{ ucfirst($reservation->status) }}</span>
            </li>
        @endforeach
    </ul>
    @else
        <p style="color:#94a3b8;">Aucune réservation.</p>
    @endif

    <div style="margin-top:2rem;">
        <a href="{{ route('users.index') }}" style="padding:.6rem 1.2rem;background:#64748b;color:#fff;border-radius:1rem;text-decoration:none;">← Retour</a>
    </div>
</div>
@endsection