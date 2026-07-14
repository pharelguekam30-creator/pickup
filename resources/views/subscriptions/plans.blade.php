@extends('layouts.app')

@section('title', 'Plans de collecte')

@section('content')
<div style="max-width:900px;margin:0 auto;">
    <h1 style="font-size:1.5rem;font-weight:bold;color:#1e3a8a;margin-bottom:1.5rem;">Plans de collecte</h1>

    @foreach(['familial' => 'Familial', 'entreprise' => 'Entreprise'] as $type => $label)
        <h2 style="font-size:1.1rem;font-weight:600;color:#374151;margin:1.5rem 0 1rem;">{{ $label }}</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach($plans->where('type', $type) as $plan)
                <div style="background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 2px 8px #00000011;display:flex;flex-direction:column;">
                    <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin-bottom:.3rem;">{{ $plan->name }}</h3>
                    <p style="color:#6b7280;font-size:.85rem;flex:1;">{{ $plan->description }}</p>
                    <div style="margin:.8rem 0;display:flex;flex-wrap:wrap;gap:.3rem;">
                        @foreach($plan->collection_days ?? [] as $day)
                            <span style="background:#e0e7ff;color:#4338ca;padding:.15rem .5rem;border-radius:6px;font-size:.75rem;font-weight:500;">{{ ucfirst($day) }}</span>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;margin-top:.5rem;">
                        <span style="font-size:clamp(1rem,4vw,1.3rem);font-weight:bold;color:#059669;word-break:break-word;">{{ number_format($plan->price_per_month, 0, ',', ' ') }} FCFA<small style="font-size:.8rem;color:#6b7280;font-weight:400;">/mois</small></span>
                        <a href="{{ route('subscriptions.subscribe.form', $plan) }}" style="padding:.5rem 1.2rem;background:#4f46e5;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:.9rem;">Souscrire</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
