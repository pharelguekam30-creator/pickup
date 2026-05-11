@extends('layouts.app')

@section('title', 'Nos Services')

@section('content')

<div class="dashboard-wrapper" style="max-width:1100px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h1 style="font-size:2.2rem;font-weight:bold;color:#2563eb;margin-bottom:2.5rem;text-align:center;">Nos Services</h1>

    @if(session('success'))
        <div style="border:1px solid #16a34a;background:#dcfce7;color:#065f46;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;text-align:center;">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:2rem;">
        @foreach($services as $service)
            <div style="background:linear-gradient(135deg,#e0e7ff 0%,#fff 100%);border-radius:1.2rem;box-shadow:0 4px 16px #2563eb11;padding:2rem 1.5rem;display:flex;flex-direction:column;justify-content:space-between;position:relative;min-height:260px;">
                <div style="position:absolute;top:1.2rem;right:1.2rem;">
                    <span style="background:#16a34a;color:#fff;font-size:.95rem;font-weight:bold;padding:.4rem 1rem;border-radius:1rem;box-shadow:0 1px 4px #16a34a22;">Prix : {{ $service->price }} FCFA</span>
                </div>
                <div>
                    <h2 style="font-size:1.5rem;font-weight:600;color:#1e3a8a;margin-bottom:.7rem;">{{ $service->name }}</h2>
                    <p style="color:#374151;font-size:1.08rem;margin-bottom:1.5rem;min-height:48px;">{{ $service->description }}</p>

                    <!-- Affichage des avis -->
                    @if($service->avis->count() > 0)
                        <div style="margin-bottom:1rem;">
                            <h4 style="font-size:1rem;font-weight:600;color:#1e3a8a;margin-bottom:.5rem;">Avis ({{ $service->avis->count() }})</h4>
                            <div style="display:flex;gap:.5rem;margin-bottom:.5rem;">
                                @php
                                    $avgRating = $service->avis->avg('note');
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $avgRating ? '#fbbf24' : '#d1d5db' }};font-size:1rem;">★</span>
                                @endfor
                                <span style="font-size:.9rem;color:#6b7280;">({{ number_format($avgRating, 1) }})</span>
                            </div>
                            @foreach($service->avis->take(2) as $avis)
                                <div style="background:#f8fafc;padding:.75rem;border-radius:.5rem;margin-bottom:.5rem;border-left:3px solid #3b82f6;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:.25rem;">
                                        <strong style="font-size:.9rem;color:#1e3a8a;">{{ $avis->user->name }}</strong>
                                        <div style="display:flex;gap:.25rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span style="color:{{ $i <= $avis->note ? '#fbbf24' : '#d1d5db' }};font-size:.8rem;">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <p style="font-size:.9rem;color:#374151;margin:0;">{{ Str::limit($avis->contenu, 100) }}</p>
                                </div>
                            @endforeach
                            @if($service->avis->count() > 2)
                                <p style="font-size:.8rem;color:#6b7280;text-align:center;margin:.5rem 0;">+ {{ $service->avis->count() - 2 }} autres avis</p>
                            @endif
                        </div>
                    @endif
                </div>
                @auth
                    @if(auth()->user()->role == 'menagere' || auth()->user()->role == 'Menagere')
                        <div style="display:flex;justify-content:flex-end;">
                            <a href="{{ route('avis.create', ['service' => $service->id]) }}"
                               style="background:#2563eb;color:#fff;padding:.7rem 1.5rem;border-radius:1.2rem;font-weight:bold;box-shadow:0 2px 8px #2563eb33;transition:background .2s;{{ $service->avis->where('user_id', auth()->id())->count() > 0 ? 'pointer-events:none;opacity:0.6;' : '' }}"
                               onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#2563eb'">
                               {{ $service->avis->where('user_id', auth()->id())->count() > 0 ? 'Avis déjà donné' : 'Donner un avis' }}
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>
</div>

<style>
.services-container {
    font-family: 'Inter', sans-serif;
}

.service-card {
    background: linear-gradient(135deg, #4f4d4d 0%, #f9fafb 100%);
    border-radius: 1rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.btn-primary {
    background-color: #34D399;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0eeb0e;
}
</style>
@endsection
