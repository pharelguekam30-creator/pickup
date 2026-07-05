@extends('layouts.app')
@section('title', 'Détail du rôle')
@section('content')
<div class="dashboard-wrapper" style="max-width:600px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <h2 style="font-size:2rem;font-weight:bold;color:#2563eb;margin-bottom:2rem;">Rôle : {{ $role->name }}</h2>
    <p><strong>ID :</strong> {{ $role->id }}</p>
    <p><strong>Date de création :</strong> {{ $role->created_at->format('d/m/Y H:i') }}</p>
    <div style="margin-top:2rem;">
        <a href="{{ route('roles.index') }}" class="btn btn-primary" style="padding:.7rem 1.5rem;background:#2563eb;color:#fff;border-radius:1rem;text-decoration:none;">Retour</a>
    </div>
</div>
@endsection