@extends('layouts.app')
@section('title', 'Rôles et autorisations')
@section('content')
<div class="dashboard-wrapper" style="max-width:900px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;">Rôles & Autorisations</h2>
        <a href="{{ route('role_autorisations.create') }}" style="padding:.7rem 1.5rem;background:#2563eb;color:#fff;border-radius:1rem;font-weight:bold;">+ Associer</a>
    </div>
    @if(session('success'))
        <div style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif
    @forelse($roles as $role)
        <div style="margin-bottom:1.5rem;padding:1.25rem;background:#f8fafc;border-radius:1rem;">
            <h3 style="color:#1e3a8a;margin-bottom:.5rem;">{{ $role->name }}</h3>
            @if($role->autorisations->count() > 0)
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach($role->autorisations as $auth)
                        <li style="color:#374151;">{{ $auth->name }}</li>
                    @endforeach
                </ul>
            @else
                <p style="color:#64748b;font-size:.9rem;">Aucune autorisation associée.</p>
            @endif
        </div>
    @empty
        <p style="color:#64748b;">Aucun rôle trouvé.</p>
    @endforelse
</div>
@endsection