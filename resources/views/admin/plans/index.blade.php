@extends('layouts.app')

@section('title', 'Gestion des plans de collecte')

@section('content')
<div style="max-width:900px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;">Plans de collecte</h1>
        <a href="{{ route('admin.plans.create') }}" style="padding:.5rem 1rem;background:#4f46e5;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:.85rem;">+ Nouveau plan</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
    @endif

    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Nom</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Type</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Collectes/sem</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Prix/mois</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Actif</th>
                    <th style="padding:.8rem 1rem;text-align:center;color:#374151;font-size:.85rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="padding:.7rem 1rem;font-weight:600;">{{ $plan->name }}</td>
                        <td style="padding:.7rem 1rem;color:#6b7280;">{{ $plan->type === 'familial' ? 'Familial' : 'Entreprise' }}</td>
                        <td style="padding:.7rem 1rem;color:#6b7280;">{{ $plan->collections_per_week }}x/sem</td>
                        <td style="padding:.7rem 1rem;color:#059669;font-weight:600;">{{ number_format($plan->price_per_month, 0, ',', ' ') }} FCFA</td>
                        <td style="padding:.7rem 1rem;">{!! $plan->is_active ? '<span style="color:#16a34a;font-weight:600;">Oui</span>' : '<span style="color:#dc2626;">Non</span>' !!}</td>
                        <td style="padding:.7rem 1rem;text-align:center;">
                            <a href="{{ route('admin.plans.edit', $plan) }}" style="padding:.3rem .7rem;background:#e0e7ff;color:#4338ca;border-radius:6px;text-decoration:none;font-size:.8rem;font-weight:600;">Modifier</a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Supprimer ce plan ?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:.3rem .7rem;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="padding:2rem;text-align:center;color:#9ca3af;">Aucun plan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
