@extends('layouts.app')

@section('title', 'Accès refusé')

@section('content')
<div style="text-align: center; margin-top: 50px;">
    <h1 style="font-size: 80px; color: #dc3545;">403</h1>
    <h2 style="color: #555;">Accès refusé</h2>
    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none;">Retour à l'accueil</a>
</div>
@endsection
