@extends('layouts.app')
@section('title', 'Associer rôle et autorisation')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Associer une autorisation à un rôle</h2>
        <form action="{{ route('role_autorisations.store') }}" method="POST">
            @csrf
            <select name="role_id" class="form-field" required>
                <option value="">Sélectionner un rôle</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <select name="autorisation_id" class="form-field" required>
                <option value="">Sélectionner une autorisation</option>
                @foreach($autorisations as $auth)
                    <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Associer</button>
        </form>
    </div>
</div>
@endsection