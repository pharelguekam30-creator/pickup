@extends('layouts.app')

@section('title', 'Deposer de l\'argent')

@section('content')
<div class="dashboard-wrapper" style="max-width:500px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h2 style="font-size:1.6rem;font-weight:bold;color:#1e3a8a;margin-bottom:.5rem;">Deposer de l'argent</h2>
    <p style="color:#64748b;margin-bottom:2rem;">Ajoutez du solde a votre portefeuille.</p>

    @if ($errors->any())
        <div style="border:1px solid #fca5a5;padding:12px;border-radius:8px;background:#fee2e2;color:#b91c1c;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payments.deposit') }}" method="POST">
        @csrf

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Montant (FCFA)</label>
            <input type="number" name="montant" min="100" step="100" value="{{ old('montant') }}"
                   style="width:100%;padding:14px;border:2px solid #cbd5e1;border-radius:10px;outline:none;font-size:1.2rem;" required>
        </div>

        <div style="margin-bottom:2rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.8rem;">Methode de paiement</label>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <label style="flex:1;padding:1rem;border:2px solid #cbd5e1;border-radius:10px;text-align:center;cursor:pointer;transition:.2s;">
                    <input type="radio" name="methode" value="om" required>
                    <div style="margin-top:.3rem;font-weight:600;">Orange Money</div>
                </label>
                <label style="flex:1;padding:1rem;border:2px solid #cbd5e1;border-radius:10px;text-align:center;cursor:pointer;transition:.2s;">
                    <input type="radio" name="methode" value="momo" required>
                    <div style="margin-top:.3rem;font-weight:600;">MTN MoMo</div>
                </label>
                <label style="flex:1;padding:1rem;border:2px solid #cbd5e1;border-radius:10px;text-align:center;cursor:pointer;transition:.2s;">
                    <input type="radio" name="methode" value="carte" required>
                    <div style="margin-top:.3rem;font-weight:600;">Carte bancaire</div>
                </label>
            </div>
        </div>

        <button type="submit" style="width:100%;padding:14px;border:none;border-radius:10px;background:linear-gradient(90deg,#4f46e5,#2563eb);color:#fff;font-weight:700;font-size:1rem;cursor:pointer;">
            Deposer
        </button>
    </form>

    <div style="margin-top:1.5rem;text-align:center;">
        <a href="{{ route('payments.index') }}" style="color:#2563eb;font-weight:600;text-decoration:none;">Retour au portefeuille</a>
    </div>
</div>
@endsection
