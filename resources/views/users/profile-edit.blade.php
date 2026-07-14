@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="dashboard-wrapper" style="max-width:700px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:bold;flex-shrink:0;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <h2 style="font-size:clamp(1.2rem,5vw,1.6rem);font-weight:bold;color:#1e3a8a;">Modifier mon profil</h2>
    </div>

    @if ($errors->any())
        <div style="border:1px solid #fca5a5;padding:12px;border-radius:8px;background:#fee2e2;color:#b91c1c;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($user->photo)
        <div style="text-align:center;margin-bottom:1.5rem;">
            <img src="{{ asset($user->photo) }}" alt="Photo de profil" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #2563eb;">
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="profile-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Nom</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;" required>
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;" required>
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Pays</label>
                <input type="text" name="country" value="{{ old('country', $user->country) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Région</label>
                <input type="text" name="region" value="{{ old('region', $user->region) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Ville</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Quartier</label>
                <input type="text" name="quarter" value="{{ old('quarter', $user->quarter) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Photo de profil</label>
                <input type="file" name="photo" accept="image/*" style="width:100%;padding:10px;border:2px solid #cbd5e1;border-radius:10px;">
            </div>

            @if($user->role === 'vidangeur')
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Tarif (FCFA)</label>
                <input type="number" name="tarif" value="{{ old('tarif', $user->tarif) }}"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Position sur la carte</label>
                <div id="mapTileError" style="display:none;padding:.5rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:.5rem;color:#991b1b;text-align:center;font-size:.85rem;margin-bottom:.5rem;">&#9888; Verifiez votre connexion internet.</div>
                <div id="map" style="height:300px;border-radius:10px;border:2px solid #cbd5e1;margin-bottom:.5rem;"></div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $user->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $user->longitude) }}">
                <p style="color:#94a3b8;font-size:.85rem;">Cliquez sur la carte pour placer votre position.</p>
            </div>
            @endif
        </div>

        @if($user->role === 'vidangeur')
        <link rel="stylesheet" href="/leaflet/leaflet.min.css" />
        <script src="/leaflet/leaflet.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lat = {{ $user->latitude ?? 4.0441 }};
            var lng = {{ $user->longitude ?? 9.7299 }};
            var map = L.map('map').setView([lat, lng], 13);
            var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '\u00a9 OpenStreetMap'
            }).addTo(map);
            tileLayer.on('tileerror', function() { if (!window._tileError) { window._tileError = true; document.getElementById('mapTileError').style.display = 'block'; } });
            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            marker.on('dragend', function () {
                var pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(7);
                document.getElementById('longitude').value = pos.lng.toFixed(7);
            });
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
                document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
            });
        });
        </script>
        @endif

        <div style="display:flex;gap:1rem;margin-top:2rem;flex-wrap:wrap;">
            <button type="submit" style="padding:.8rem 2rem;background:#2563eb;color:#fff;border:none;border-radius:10px;font-weight:700;cursor:pointer;">
                Enregistrer
            </button>
            <a href="{{ route('profile') }}" style="padding:.8rem 2rem;background:#e5e7eb;color:#374151;border-radius:10px;text-decoration:none;font-weight:600;text-align:center;">
                Annuler
            </a>
        </div>
    </form>
</div>
<style>
@media (max-width: 600px) {
    .profile-form-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
