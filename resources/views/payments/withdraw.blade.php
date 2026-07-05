@extends('layouts.app')

@section('title', 'Retirer de l\'argent')

@section('content')
<div class="dashboard-wrapper" style="max-width:500px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h2 style="font-size:1.6rem;font-weight:bold;color:#1e3a8a;margin-bottom:.5rem;">Retirer de l'argent</h2>
    <p style="color:#64748b;margin-bottom:2rem;">Solde disponible : <strong>{{ number_format(auth()->user()->solde ?? 0, 0, ',', ' ') }} FCFA</strong></p>

    @if ($errors->any())
        <div style="border:1px solid #fca5a5;padding:12px;border-radius:8px;background:#fee2e2;color:#b91c1c;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div style="padding:12px 16px;border-radius:10px;background:#fee2e2;color:#dc2626;font-weight:600;margin-bottom:1rem;border:1px solid #fca5a5;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('payments.withdraw') }}" method="POST">
        @csrf

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Montant a retirer (FCFA)</label>
            <input type="number" name="montant" min="100" step="100" value="{{ old('montant') }}"
                   style="width:100%;padding:14px;border:2px solid #cbd5e1;border-radius:10px;outline:none;font-size:1.2rem;" required>
        </div>

        <button type="submit" style="width:100%;padding:14px;border:none;border-radius:10px;background:linear-gradient(90deg,#dc2626,#ef4444);color:#fff;font-weight:700;font-size:1rem;cursor:pointer;">
            Retirer
        </button>
    </form>

    <div style="margin-top:1.5rem;text-align:center;">
        <a href="{{ route('payments.index') }}" style="color:#2563eb;font-weight:600;text-decoration:none;">Retour au portefeuille</a>
    </div>
</div>
@endsection
