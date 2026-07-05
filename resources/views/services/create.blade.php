@extends('layouts.app')
@section('title', 'Créer un service')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Nouveau service</h2>
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nom du service" value="{{ old('name') }}" class="form-field" required>
            <textarea name="description" placeholder="Description" class="form-field" required>{{ old('description') }}</textarea>
            <input type="number" step="0.01" name="price" placeholder="Prix (FCFA)" value="{{ old('price') }}" class="form-field" required>
            <button type="submit" class="btn-primary">Créer</button>
        </form>
    </div>
</div>
@endsection