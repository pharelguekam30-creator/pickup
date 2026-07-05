@extends('layouts.app')
@section('title', 'Modifier une autorisation')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Modifier l'autorisation</h2>
        <form action="{{ route('autorisations.update', $autorisation->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="text" name="name" value="{{ old('name', $autorisation->name) }}" class="form-field" required>
            <textarea name="description" class="form-field">{{ old('description', $autorisation->description) }}</textarea>
            <button type="submit" class="btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection