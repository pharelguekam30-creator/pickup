@extends('layouts.app')
@section('title', $service->name)
@section('content')
<div class="dashboard-wrapper" style="max-width:700px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;margin-bottom:1.5rem;">{{ $service->name }}</h2>
    <p style="font-size:1.1rem;color:#374151;margin-bottom:1rem;">{{ $service->description }}</p>
    <p style="font-size:1.3rem;font-weight:bold;color:#16a34a;">Prix : {{ $service->price }} FCFA</p>
    <div style="margin-top:2rem;">
        <a href="{{ route('services.index') }}" style="padding:.7rem 1.5rem;background:#2563eb;color:#fff;border-radius:1rem;text-decoration:none;">Retour aux services</a>
    </div>
</div>
@endsection