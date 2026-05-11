@extends('layouts.app')

@section('title', 'Page expirée')

@section('content')
<div style="padding: 2rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; max-width: 640px; margin: 2rem auto;">
    <h1 style="color: #b91c1c;">419 - Page expirée</h1>
    <p>Votre session a probablement expiré ou le jeton CSRF n’est plus valide.</p>
    <ul>
        <li>Actualisez la page (F5).</li>
        <li>Reconnectez-vous si besoin.</li>
        <li>Recommencez l’action (formulaire / validation).</li>
    </ul>
    <p>
        <a href="{{ url()->previous() }}" style="display:inline-block; margin-top:1rem; padding:0.5rem 1rem; color:#ffffff; background:#2563eb; border-radius:0.25rem;">Retour</a>
        <a href="{{ route('home') }}" style="display:inline-block; margin-top:1rem; padding:0.5rem 1rem; color:#ffffff; background:#4b5563; border-radius:0.25rem; margin-left:0.5rem;">Accueil</a>
    </p>
</div>
@endsection