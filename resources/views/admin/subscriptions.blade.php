@extends('layouts.app')

@section('title', 'Gestion des abonnements')

@section('sidebar')
    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Dashboard</a>
    <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Utilisateurs</a>
    <a href="{{ route('services.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Services</a>
    <a href="{{ route('admin.plans.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Plans de collecte</a>
    <a href="{{ route('admin.subscriptions') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Abonnements</a>
    <a href="{{ route('reservations.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Réservations</a>
    <a href="{{ route('admin.stats') }}" class="block px-4 py-2 rounded hover:bg-indigo-500 transition">Statistiques</a>
@endsection

@section('content')
<div style="max-width:900px;margin:0 auto;">
    <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;margin-bottom:1.5rem;">Abonnements en attente</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #dc2626;background:#fef2f2;color:#991b1b;">{{ session('error') }}</div>
    @endif

    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">N°</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Plan</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Client</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Vidangeur</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Montant</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Statut</th>
                    <th style="padding:.8rem 1rem;text-align:center;color:#374151;font-size:.85rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disputes as $sub)
                    <tr style="border-top:1px solid #e5e7eb;{{ $sub->month_status === 'disputed' ? 'background:#fef2f2;' : '' }}">
                        <td style="padding:.7rem 1rem;">{{ $sub->id }}</td>
                        <td style="padding:.7rem 1rem;font-weight:600;">{{ $sub->plan->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;">{{ $sub->client->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;">{{ $sub->vidangeur->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;color:#059669;font-weight:600;">{{ number_format($sub->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td style="padding:.7rem 1rem;">
                            <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;
                                {{ $sub->month_status === 'completed_vidangeur' ? 'background:#fef3c7;color:#92400e;' : 'background:#fef2f2;color:#991b1b;' }}">
                                {{ $sub->month_status === 'completed_vidangeur' ? 'Attente client' : 'Litige' }}
                            </span>
                        </td>
                        <td style="padding:.7rem 1rem;text-align:center;">
                            @if(in_array($sub->month_status, ['completed_vidangeur', 'disputed']))
                                <form method="POST" action="{{ route('admin.subscriptions.force-pay', $sub) }}" class="inline" onsubmit="return confirm('Forcer le paiement de {{ number_format($sub->plan->price_per_month ?? 0, 0, ',', ' ') }} FCFA au vidangeur ?');">
                                    @csrf
                                    <button type="submit" style="padding:.3rem .7rem;background:#059669;color:#fff;border:none;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;">Payer</button>
                                </form>
                                <form method="POST" action="{{ route('admin.subscriptions.force-cancel-month', $sub) }}" class="inline" onsubmit="return confirm('Annuler ce mois sans paiement ?');">
                                    @csrf
                                    <button type="submit" style="padding:.3rem .7rem;background:#dc2626;color:#fff;border:none;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;">Annuler</button>
                                </form>
                            @endif
                            <a href="{{ route('subscriptions.collections', $sub) }}" style="padding:.3rem .7rem;background:#e0e7ff;color:#4338ca;border-radius:6px;text-decoration:none;font-size:.8rem;font-weight:600;">Détails</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="padding:2rem;text-align:center;color:#9ca3af;">Aucun abonnement en attente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2 style="font-size:1.1rem;font-weight:bold;color:#1e3a8a;margin:2rem 0 1rem;">Tous les abonnements actifs</h2>
    @php $activeSubs = \App\Models\Subscription::with(['plan', 'client', 'vidangeur'])->where('status', 'active')->orderBy('created_at', 'desc')->get(); @endphp
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Plan</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Client</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Vidangeur</th>
                    <th style="padding:.8rem 1rem;text-align:left;color:#374151;font-size:.85rem;">Période</th>
                    <th style="padding:.8rem 1rem;text-align:center;color:#374151;font-size:.85rem;">Mois</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeSubs as $sub)
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="padding:.7rem 1rem;font-weight:600;">{{ $sub->plan->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;">{{ $sub->client->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;">{{ $sub->vidangeur->name ?? 'N/A' }}</td>
                        <td style="padding:.7rem 1rem;color:#6b7280;">
                            {{ optional($sub->current_month_start)->format('d/m/Y') }} - {{ optional($sub->current_month_end)->format('d/m/Y') }}
                        </td>
                        <td style="padding:.7rem 1rem;text-align:center;">
                            <span style="display:inline-block;padding:.15rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;
                                {{ $sub->month_status === 'active' ? 'background:#e0e7ff;color:#4338ca;' : ($sub->month_status === 'completed_vidangeur' ? 'background:#fef3c7;color:#92400e;' : ($sub->month_status === 'paid' ? 'background:#dcfce7;color:#166534;' : 'background:#fef2f2;color:#991b1b;')) }}">
                                {{ match($sub->month_status) { 'active' => 'En cours', 'completed_vidangeur' => 'Attente', 'paid' => 'Payé', 'disputed' => 'Litige', default => $sub->month_status } }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:2rem;text-align:center;color:#9ca3af;">Aucun abonnement actif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
