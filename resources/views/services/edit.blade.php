@extends('layouts.app')
@section('title', 'Modifier un service')
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Modifier le service</h2>
        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="text" name="name" value="{{ old('name', $service->name) }}" class="form-field" required>
            <textarea name="description" class="form-field" required>{{ old('description', $service->description) }}</textarea>
            <input type="number" step="0.01" name="price" value="{{ old('price', $service->price) }}" class="form-field" required>
            <button type="submit" class="btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection