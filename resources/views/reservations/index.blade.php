@extends('layouts.app')

@section('title', 'Liste des réservations')

@section('content')
    <div class="dashboard-wrapper" style="max-width:900px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
        <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;margin-bottom:2rem;text-align:center;">Liste des réservations</h2>
        @if(session('success'))
            <div style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;background:#f8fafc;border-radius:1rem;overflow:hidden;box-shadow:0 1px 4px #2563eb11;font-size:1.15rem;color:#222;">
            <thead style="background:#1e3a8a;color:#fff;">
                <tr>
                    <th style="padding:1.1rem 0.5rem;">ID</th>
                    <th style="padding:1.1rem 0.5rem;">Client</th>
                    <th style="padding:1.1rem 0.5rem;">Vidangeur</th>
                    <th style="padding:1.1rem 0.5rem;">Service</th>
                    <th style="padding:1.1rem 0.5rem;">Date</th>
                    <th style="padding:1.1rem 0.5rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr style="border-bottom:1px solid #e5e7eb; background:{{ $loop->even ? '#f1f5f9' : '#fff' }}; color:#222;">
                        <td style="padding:1rem .5rem;">{{ $reservation->id }}</td>
                        <td style="padding:1rem .5rem;">{{ optional($reservation->client)->name ?? $reservation->client_name ?? 'N/A' }}</td>
                        <td style="padding:1rem .5rem;">{{ optional($reservation->user)->name ?? 'N/A' }}</td>
                        <td style="padding:1rem .5rem;">{{ optional($reservation->service)->name ?? 'N/A' }}</td>
                        <td style="padding:1rem .5rem;">{{ optional($reservation->reservation_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td style="padding:1rem .5rem;">
                            @php $color = match($reservation->status ?? 'pending') {
                                'pending' => '#f59e42', 'accepted' => '#2563eb', 'completed' => '#16a34a', 'canceled' => '#dc2626', default => '#64748b'
                            }; @endphp
                            <span style="display:inline-block;padding:.3rem .8rem;border-radius:1rem;background:{{ $color }}22;color:{{ $color }};font-weight:bold;">
                                {{ ucfirst($reservation->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem 0;color:#64748b;background:#fff;">Aucune réservation pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection