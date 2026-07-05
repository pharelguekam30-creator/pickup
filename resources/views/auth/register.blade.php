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

        <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Nom complet" value="{{ old('name') }}" class="form-field" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-field">
            <input type="password" name="password" placeholder="Mot de passe" class="form-field" required>
            <input type="password" name="password_confirmation" placeholder="Confirmer mot de passe" class="form-field" required>
            <input type="text" name="phone" placeholder="Téléphone" value="{{ old('phone') }}" class="form-field">
            <div style="margin: 8px 0 14px; color: #334155; font-size: 0.95rem;">
                <label style="display:block; font-weight:600; margin-bottom:8px;">Canal de vérification</label>
                <select name="verification_channel" class="form-field" required>
                    <option value="">Choisir un canal</option>
                    <option value="email" {{ old('verification_channel') === 'email' ? 'selected' : '' }}>Par e-mail</option>
                    <option value="phone" {{ old('verification_channel') === 'phone' ? 'selected' : '' }}>Par téléphone</option>
                    <option value="both" {{ old('verification_channel') === 'both' ? 'selected' : '' }}>Par e-mail et téléphone</option>
                </select>
                <div style="font-size: 0.9rem; color: #64748b; margin-top: 6px;">Le compte reste bloqué jusqu’à la confirmation du canal choisi.</div>
            </div>
            <input type="text" name="country" placeholder="Pays" value="{{ old('country') }}" class="form-field" required>
            <input type="text" name="region" placeholder="Région" value="{{ old('region') }}" class="form-field" required>
            <input type="text" name="city" placeholder="Ville" value="{{ old('city') }}" class="form-field" required>
            <input type="text" name="quarter" placeholder="Quartier" value="{{ old('quarter') }}" class="form-field" required>
            <input type="text" name="address" placeholder="Lieux dit" value="{{ old('address') }}" class="form-field" required>
            <input type="date" name="birthdate" placeholder="Date de naissance (facultatif)" value="{{ old('birthdate') }}" class="form-field">
            <div style="margin:8px 0;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;">Photo de profil</label>
                <input type="file" name="photo" accept="image/*" style="width:100%;padding:10px;border:2px solid #cbd5e1;border-radius:10px;background:#fff;">
            </div>

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
