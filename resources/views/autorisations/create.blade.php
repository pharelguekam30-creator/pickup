@extends('layouts.app')
@section('title', 'Créer une autorisation')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Nouvelle autorisation</h2>
        <form action="{{ route('autorisations.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nom" value="{{ old('name') }}" class="form-field" required>
            <textarea name="description" placeholder="Description (optionnelle)" class="form-field">{{ old('description') }}</textarea>
            <button type="submit" class="btn-primary">Créer</button>
        </form>
    </div>
</div>
@endsection