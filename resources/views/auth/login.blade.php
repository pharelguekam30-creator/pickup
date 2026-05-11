@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Connexion</h2>

        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Champ Email -->
            <div>
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                    class="form-field @error('email') border-red-500 @enderror" 
                    placeholder="Votre email" required>
            </div>

            <!-- Champ Mot de passe -->
            <div>
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" 
                    class="form-field @error('password') border-red-500 @enderror" 
                    placeholder="Votre mot de passe" required>
            </div>

            <!-- Bouton Connexion -->
            <button type="submit" class="btn-primary">
                Se connecter
            </button>

            <!-- Lien Créer un compte -->
            <p style="text-align:center; color:#64748b; font-size:0.9rem; margin-top:16px;">
                Pas encore inscrit ?
                <a href="{{ route('register') }}" class="text-link">Créer un compte</a>
            </p>
        </form>
    </div>
</div>
@endsection
