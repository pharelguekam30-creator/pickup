@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">
    @if (session('success'))
        <div style="padding:12px 16px;border-radius:10px;background:#dcfce7;color:#16a34a;font-weight:600;margin-bottom:1rem;border:1px solid #bbf7d0;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="padding:12px 16px;border-radius:10px;background:#fee2e2;color:#dc2626;font-weight:600;margin-bottom:1rem;border:1px solid #fca5a5;">{{ session('error') }}</div>
    @endif

    <h1 class="text-2xl font-bold mb-6 text-blue-700">Dashboard Administrateur</h1>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;">
            <p style="color:#94a3b8;font-size:.75rem;">Utilisateurs</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ \App\Models\User::count() }}</p>
            <a href="{{ route('users.index') }}" style="font-size:.85rem;color:#2563eb;">Gerer</a>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;">
            <p style="color:#94a3b8;font-size:.75rem;">Services</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ \App\Models\Service::count() }}</p>
            <a href="{{ route('services.index') }}" style="font-size:.85rem;color:#2563eb;">Gerer</a>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;">
            <p style="color:#94a3b8;font-size:.75rem;">Reservations</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ \App\Models\Reservation::count() }}</p>
            <a href="{{ route('reservations.index') }}" style="font-size:.85rem;color:#2563eb;">Gerer</a>
        </div>
        <div style="background:#ede9fe;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;border:2px solid #7c3aed;">
            <p style="color:#7c3aed;font-size:.75rem;font-weight:700;">A confirmer</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#5b21b6;">{{ \App\Models\Reservation::where('status', 'completed_vidangeur')->count() }}</p>
        </div>
    </div>

    @php
        $pendingConfirmations = \App\Models\Reservation::with(['client', 'user', 'service'])
            ->where('status', 'completed_vidangeur')
            ->orderBy('updated_at', 'desc')
            ->get();
    @endphp

    @if($pendingConfirmations->count() > 0)
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;font-weight:bold;color:#7c3aed;margin-bottom:1rem;">Interventions en attente de confirmation</h2>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb;">
                        <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">N</th>
                        <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Client</th>
                        <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Vidangeur</th>
                        <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Service</th>
                        <th style="text-align:left;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Montant</th>
                        <th style="text-align:center;padding:.75rem .5rem;color:#64748b;font-size:.85rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingConfirmations as $r)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.75rem .5rem;">#{{ $r->id }}</td>
                        <td style="padding:.75rem .5rem;">{{ optional($r->client)->name ?? '?' }}</td>
                        <td style="padding:.75rem .5rem;">{{ optional($r->user)->name ?? '?' }}</td>
                        <td style="padding:.75rem .5rem;">{{ optional($r->service)->name ?? '?' }}</td>
                        <td style="padding:.75rem .5rem;font-weight:600;">{{ number_format(optional($r->service)->price ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td style="padding:.75rem .5rem;text-align:center;">
                            @php
                                $unreadMsg = \App\Models\Message::where('reservation_id', $r->id)->where('sender_id', '!=', auth()->id())->where('is_read', 0)->count();
                            @endphp
                            <form action="{{ route('admin.forceComplete', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Forcer le paiement ? Le montant sera verse au vidangeur.')">
                                @csrf
                                <button type="submit" style="padding:.3rem .8rem;background:#16a34a;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Payer</button>
                            </form>
                            <form action="{{ route('admin.forceCancel', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Annuler cette intervention ? Aucun paiement ne sera effectue.')">
                                @csrf
                                <button type="submit" style="padding:.3rem .8rem;background:#dc2626;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Annuler</button>
                            </form>
                            <a href="{{ route('chat.show', $r->id) }}" style="display:inline-flex;align-items:center;gap:3px;padding:.3rem .8rem;background:{{ $unreadMsg ? '#dbeafe' : '#f1f5f9' }};color:{{ $unreadMsg ? '#2563eb' : '#475569' }};border-radius:6px;font-size:.8rem;font-weight:600;text-decoration:none;">
                                Discuter
                                @if($unreadMsg > 0)
                                    <span style="background:#ef4444;color:#fff;font-size:.6rem;padding:1px 5px;border-radius:8px;font-weight:700;">{{ $unreadMsg }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div style="display:flex;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('users.index') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Utilisateurs</a>
        <a href="{{ route('services.index') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Services</a>
        <a href="{{ route('roles.index') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Roles</a>
        <a href="{{ route('reservations.index') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Reservations</a>
        <a href="{{ route('payments.index') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Portefeuille</a>
        <a href="{{ route('admin.stats') }}" style="padding:.8rem 1.5rem;background:#16a34a;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Statistiques</a>
    </div>
</div>
@endsection
