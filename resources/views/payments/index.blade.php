@extends('layouts.app')

@section('title', 'Mes transactions')

@section('content')
<div class="dashboard-wrapper" style="max-width:800px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">

    @if (session('success'))
        <div style="padding:12px 16px;border-radius:10px;background:#dcfce7;color:#16a34a;font-weight:600;margin-bottom:1rem;border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding:12px 16px;border-radius:10px;background:#fee2e2;color:#dc2626;font-weight:600;margin-bottom:1rem;border:1px solid #fca5a5;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="font-size:1.6rem;font-weight:bold;color:#1e3a8a;">Portefeuille</h2>
            <p style="color:#64748b;">Solde disponible</p>
        </div>
        <div style="text-align:right;">
            <span style="font-size:2rem;font-weight:bold;color:#2563eb;">{{ number_format($user->solde ?? 0, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>

    <div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;">
        <a href="{{ route('payments.deposit.form') }}" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">
            + Deposer
        </a>
        <a href="{{ route('payments.withdraw.form') }}" style="padding:.8rem 1.5rem;background:#e5e7eb;color:#374151;border-radius:10px;text-decoration:none;font-weight:700;">
            - Retirer
        </a>
    </div>

    <div style="border-top:1px solid #e5e7eb;padding-top:1.5rem;">
        <h3 style="color:#1e3a8a;font-weight:600;margin-bottom:1rem;">Historique des transactions</h3>

        @if($transactions->count() > 0)
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                @foreach($transactions as $t)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:#f8fafc;border-radius:.75rem;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="font-size:1.1rem;">
                                    @switch($t->type)
                                        @case('depot') +@break
                                        @case('retrait') -@break
                                        @case('paiement') @if($t->montant > 0)+ @else - @endif @break
                                        @case('commission') -@break
                                        @default *@endswitch
                                </span>
                                <span style="font-weight:500;color:#1e293b;">
                                    @switch($t->type)
                                        @case('depot') Depot @break
                                        @case('retrait') Retrait @break
                                        @case('paiement') @if($t->montant > 0) Reception @else Paiement @endif @break
                                        @case('commission') Commission @break
                                        @default {{ ucfirst($t->type) }} @endswitch
                                </span>
                            </div>
                            <p style="color:#94a3b8;font-size:.8rem;margin-top:.2rem;">
                                {{ $t->description }}
                                <span style="margin-left:.5rem;">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-weight:700;color:{{ $t->montant > 0 ? '#16a34a' : '#dc2626' }};">
                                {{ $t->montant > 0 ? '+' : '' }}{{ number_format($t->montant, 0, ',', ' ') }}
                            </span>
                            <p style="color:#94a3b8;font-size:.75rem;">Solde: {{ number_format($t->solde_apres, 0, ',', ' ') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:1.5rem;">
                {{ $transactions->links() }}
            </div>
        @else
            <p style="color:#94a3b8;text-align:center;padding:2rem;">Aucune transaction pour le moment.</p>
        @endif
    </div>
</div>
@endsection
