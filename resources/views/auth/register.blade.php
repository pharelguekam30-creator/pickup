@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Créer un compte</h2>

        @if ($errors->any())
            <div style="border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; background: #fee2e2; color: #b91c1c; margin-bottom: 16px;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nom complet" value="{{ old('name') }}" class="form-field" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-field" required>
            <input type="password" name="password" placeholder="Mot de passe" class="form-field" required>
            <input type="password" name="password_confirmation" placeholder="Confirmer mot de passe" class="form-field" required>
            <input type="text" name="phone" placeholder="Téléphone" value="{{ old('phone') }}" class="form-field" required>
            <input type="text" name="country" placeholder="Pays" value="{{ old('country') }}" class="form-field" required>
            <input type="text" name="region" placeholder="Région" value="{{ old('region') }}" class="form-field" required>
            <input type="text" name="city" placeholder="Ville" value="{{ old('city') }}" class="form-field" required>
            <input type="text" name="quarter" placeholder="Quartier" value="{{ old('quarter') }}" class="form-field" required>
            <input type="text" name="address" placeholder="Lieux dit" value="{{ old('address') }}" class="form-field" required>
            <input type="date" name="birthdate" placeholder="Date de naissance (facultatif)" value="{{ old('birthdate') }}" class="form-field">

            <div style="margin: 14px 0; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <span style="font-weight: 600; color: #334155;">Vous êtes :</span>
                <label style="display: inline-flex; align-items: center; gap: 5px; color: #1d4ed8;">
                    <input type="radio" id="vidangeur" name="role" value="vidangeur" onclick="toggleFields();" {{ (old('role', $role ?? '') === 'vidangeur') ? 'checked' : '' }}> Vidangeur
                </label>
                <label style="display: inline-flex; align-items: center; gap: 5px; color: #1d4ed8;">
                    <input type="radio" id="menagere" name="role" value="menagere" onclick="toggleFields();" {{ (old('role', $role ?? '') === 'menagere') ? 'checked' : '' }}> Ménagère
                </label>
            </div>

            <div id="vidangeur-fields" style="display: none; margin-bottom: 14px;">
                <input type="text" name="tarif" placeholder="Tarif (ex: 5000 FCFA)" value="{{ old('tarif') }}" class="form-field">
                <input type="text" name="disponibilite" placeholder="Disponibilités (ex: 8h-18h)" value="{{ old('disponibilite') }}" class="form-field">
            </div>

            <div id="menagere-fields" style="display: none; margin-bottom: 14px;">
                <input type="text" name="experience" placeholder="Années d'expérience" value="{{ old('experience') }}" class="form-field">
            </div>

            <button type="submit" class="btn-primary">S'inscrire</button>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    var vidangeurChecked = document.getElementById('vidangeur').checked;
    document.getElementById('vidangeur-fields').style.display = vidangeurChecked ? 'block' : 'none';
    document.getElementById('menagere-fields').style.display = vidangeurChecked ? 'none' : 'block';
}

window.onload = function() {
    toggleFields();
}
</script>
@endsection
