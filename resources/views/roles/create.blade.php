@extends('layouts.app')
@section('title', 'Créer un rôle')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Créer un rôle</h2>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nom du rôle" value="{{ old('name') }}" class="form-field" required>
            <button type="submit" class="btn-primary">Créer</button>
        </form>
    </div>
</div>
@endsection