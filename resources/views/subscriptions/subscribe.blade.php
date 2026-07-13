@extends('layouts.app')

@section('title', 'Souscrire - ' . $plan->name)

@section('content')
<div style="max-width:600px;margin:0 auto;">
    <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;margin-bottom:.5rem;">Souscrire au plan</h1>
    <p style="color:#4f46e5;font-weight:600;font-size:1.1rem;margin-bottom:1.5rem;">{{ $plan->name }} — {{ number_format($plan->price_per_month, 0, ',', ' ') }} FCFA/mois</p>

    @if(session('success'))
        <div class="mb-4 p-3 rounded" style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('subscriptions.subscribe') }}" style="background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 2px 8px #00000011;">
        @csrf
        <input type="hidden" name="collection_plan_id" value="{{ $plan->id }}">

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Choisir un vidangeur</label>
            <select name="vidangeur_id" required class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">
                <option value="">-- Sélectionner --</option>
                @foreach($vidangeurs as $v)
                    <option value="{{ $v->id }}">{{ $v->name }} — {{ $v->city ?? 'Ville non spécifiée' }}</option>
                @endforeach
            </select>
            @error('vidangeur_id') <p style="color:#dc2626;font-size:.8rem;margin-top:.2rem;">{{ $message }}</p> @enderror
        </div>

        <p style="color:#6b7280;font-size:.85rem;margin-bottom:1rem;">Les collectes démarreront à partir de demain selon les jours prévus par ce plan.</p>

        <button type="submit" style="width:100%;padding:.8rem;background:#4f46e5;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Confirmer la souscription</button>
    </form>
</div>
@endsection
