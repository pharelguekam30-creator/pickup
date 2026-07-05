@extends('layouts.app')
@section('title', 'Modifier un rôle')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Modifier le rôle</h2>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-field" required>
            <button type="submit" class="btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection