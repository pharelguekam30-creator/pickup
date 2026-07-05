@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="dashboard-wrapper" style="max-width:1000px;margin:0 auto;">

    <h1 style="font-size:1.8rem;font-weight:bold;color:#1e3a8a;margin-bottom:1.5rem;">Statistiques</h1>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;">Reservations</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ $totalReservations }}</p>
        </div>
        <div style="background:#dcfce7;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#16a34a;font-size:.75rem;">Terminees</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#065f46;">{{ $totalCompleted }}</p>
        </div>
        <div style="background:#dbeafe;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#2563eb;font-size:.75rem;">Revenu total</p>
            <p style="font-size:1.5rem;font-weight:bold;color:#1e40af;">{{ number_format($totalRevenue, 0, ',', ' ') }} F</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;">Vidangeurs</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ $totalVidangeurs }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;">Menageres</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ $totalMenageres }}</p>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 2px 8px #00000011;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;">Services</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#1e293b;">{{ $totalServices }}</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;">
            <h2 style="font-size:1.1rem;font-weight:bold;color:#1e3a8a;margin-bottom:1rem;">Interventions par mois</h2>
            <canvas id="chartReservations" height="200"></canvas>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;">
            <h2 style="font-size:1.1rem;font-weight:bold;color:#1e3a8a;margin-bottom:1rem;">Revenu par mois (FCFA)</h2>
            <canvas id="chartRevenue" height="200"></canvas>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;">
            <h2 style="font-size:1.1rem;font-weight:bold;color:#1e3a8a;margin-bottom:1rem;">Statut des interventions</h2>
            <canvas id="chartStatus" height="200"></canvas>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;">
            <h2 style="font-size:1.1rem;font-weight:bold;color:#1e3a8a;margin-bottom:1rem;">Top 5 Vidangeurs</h2>
            <ul style="list-style:none;padding:0;">
                @foreach($topVidangeurs as $i => $v)
                    <li style="padding:.6rem 0;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;">
                        <span>{{ $i+1 }}. {{ $v->name }}</span>
                        <span style="font-weight:700;color:#2563eb;">{{ $v->completed_count }} interventions</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

<script src="/chartjs/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var months = {!! json_encode($monthlyReservations->pluck('month')->map(function($m) { return date('M', mktime(0,0,0,$m,1)); })) !!};
    var resData = {!! json_encode($monthlyReservations->pluck('total')) !!};
    var revData = {!! json_encode($monthlyRevenue->pluck('total')) !!};

    new Chart(document.getElementById('chartReservations'), {
        type: 'bar',
        data: { labels: months, datasets: [{ label: 'Interventions', data: resData, backgroundColor: '#3b82f6' }] }
    });
    new Chart(document.getElementById('chartRevenue'), {
        type: 'line',
        data: { labels: months, datasets: [{ label: 'Revenu (FCFA)', data: revData, borderColor: '#16a34a', tension: 0.3 }] }
    });
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Acceptees', 'A confirmer', 'Terminees', 'Annulees'],
            datasets: [{
                data: [{{ $statusCounts['pending'] }}, {{ $statusCounts['accepted'] }}, {{ $statusCounts['awaiting'] }}, {{ $statusCounts['completed'] }}, {{ $statusCounts['canceled'] }}],
                backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#16a34a', '#ef4444']
            }]
        }
    });
});
</script>
@endsection
