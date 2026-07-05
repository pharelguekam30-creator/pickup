@extends('layouts.app')
@section('title', 'Gestion des rôles')
@section('content')
<div class="dashboard-wrapper" style="max-width:900px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;">Gestion des rôles</h2>
        <a href="{{ route('roles.create') }}" style="padding:.7rem 1.5rem;background:#2563eb;color:#fff;border-radius:1rem;font-weight:bold;">+ Nouveau rôle</a>
    </div>
    @if(session('success'))
        <div style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif
    <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#1e3a8a;color:#fff;">
            <tr>
                <th style="padding:1rem;text-align:left;">ID</th>
                <th style="padding:1rem;text-align:left;">Nom</th>
                <th style="padding:1rem;text-align:left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:1rem;">{{ $role->id }}</td>
                    <td style="padding:1rem;">{{ $role->name }}</td>
                    <td style="padding:1rem;">
                        <a href="{{ route('roles.show', $role->id) }}" style="color:#2563eb;margin-right:.5rem;">Voir</a>
                        <a href="{{ route('roles.edit', $role->id) }}" style="color:#f59e0b;margin-right:.5rem;">Modifier</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce rôle ?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="color:#dc2626;background:none;border:none;cursor:pointer;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" style="padding:2rem;text-align:center;color:#64748b;">Aucun rôle trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection