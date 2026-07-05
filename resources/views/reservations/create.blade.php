@extends('layouts.app')

@section('title', 'Réserver un service')

@section('content')
<div class="dashboard-wrapper" style="max-width:600px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;margin-bottom:2rem;text-align:center;">Nouvelle demande d’intervention</h2>

    @if($errors->any())
        <div style="background:#fecaca;color:#991b1b;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="POST" style="display:flex;flex-direction:column;gap:1.2rem;">
        @csrf

        <div>
            <label for="vidangeur" style="font-weight:500;color:#1e3a8a;">Choisir un vidangeur</label>
            <select name="user_id" required style="width:100%;padding:.7rem 1rem;border-radius:1rem;border:1px solid #cbd5e1;margin-top:.3rem;">
                <option value="">Sélectionnez un vidangeur</option>
                @foreach($vidangeurs as $vidangeur)
                    <option value="{{ $vidangeur->id }}" {{ (isset($selectedVidangeur) && $selectedVidangeur->id == $vidangeur->id) ? 'selected' : '' }}>
                        {{ $vidangeur->name }} - {{ $vidangeur->city ?? '' }} {{ $vidangeur->quarter ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="service" style="font-weight:500;color:#1e3a8a;">Service</label>
            <select name="service_id" required style="width:100%;padding:.7rem 1rem;border-radius:1rem;border:1px solid #cbd5e1;margin-top:.3rem;">
                <option value="">Sélectionnez un service</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;gap:1rem;">
            <div style="flex:1;">
                <label for="date" style="font-weight:500;color:#1e3a8a;">Date</label>
                <input type="date" name="date" required style="width:100%;padding:.7rem 1rem;border-radius:1rem;border:1px solid #cbd5e1;margin-top:.3rem;">
            </div>
            <div style="flex:1;">
                <label for="time" style="font-weight:500;color:#1e3a8a;">Heure</label>
                <input type="time" name="time" required style="width:100%;padding:.7rem 1rem;border-radius:1rem;border:1px solid #cbd5e1;margin-top:.3rem;">
            </div>
        </div>

        <button type="submit" style="margin-top:1.5rem;padding:1rem 0;background:#2563eb;color:#fff;font-size:1.1rem;font-weight:bold;border:none;border-radius:1.5rem;box-shadow:0 2px 8px #2563eb33;transition:background .2s;cursor:pointer;">Envoyer la demande</button>
    </form>
</div>
@endsection
