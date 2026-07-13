@extends('layouts.app')

@section('title', 'Vidangeurs près de chez vous')

@section('fullwidth', '1')

@push('styles')
<link rel="stylesheet" href="/leaflet/leaflet.min.css" />
<style>
    #mapWrapper { position: relative; height: 520px; width: 100%; border-radius: 1rem; border: 1px solid #e5e7eb; overflow: scroll; z-index: 1; }
    #mapWrapper::-webkit-scrollbar { width: 12px; height: 12px; }
    #mapWrapper::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 6px; }
    #mapWrapper::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 6px; }
    #mapWrapper::-webkit-scrollbar-thumb:hover { background: #64748b; }
    #map { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
    #mapOverflow { width: 200%; height: 200%; pointer-events: none; }
    .leaflet-popup-content { margin: 10px 14px; font-size: .95rem; line-height: 1.5; }
    .leaflet-popup-content-wrapper { border-radius: 12px; }
</style>
@endpush

@push('scripts')
<script src="/leaflet/leaflet.min.js"></script>
<script>
    const vidangeurs = @json($vidangeurs);
    const mapWrapper = document.getElementById('mapWrapper');
    let map = L.map('map', { scrollWheelZoom: false }).setView([4.05, 11.5], 6);

    var tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
        maxZoom: 19,
    }).addTo(map);
    tileLayer.on('tileerror', function() { if (!window._tileErrorShown) { window._tileErrorShown = true; document.getElementById('mapTileError').style.display = 'block'; } });

    const greenIcon = L.divIcon({
        className: '',
        html: '<div style="background:#16a34a;color:#fff;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,.25);"><i class="fa fa-user"></i></div>',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -36],
    });

    const markers = [];

    const userRole = {{ json_encode(auth()->check() ? auth()->user()->role : null) }};

    vidangeurs.forEach(v => {
        if (v.latitude && v.longitude) {
            let popupHtml = `
                <strong style="color:#1e3a8a;">${v.name}</strong><br>
                <span style="color:#64748b;font-size:.85rem;">${v.quarter || ''} ${v.city || ''}</span><br>
                ${v.phone ? `<span style="color:#64748b;font-size:.85rem;"><i class="fa fa-phone"></i> ${v.phone}</span><br>` : ''}
                ${v.tarif ? `<span style="color:#16a34a;font-weight:600;"><i class="fa fa-tag"></i> ${v.tarif}</span>` : ''}
            `;
            if (userRole === 'menagere') {
                popupHtml += `<br><a href="/reservations/create?vidangeur_id=${v.id}" style="display:inline-block;text-align:center;padding:.5rem 1rem;background:#2563eb;color:#fff;border-radius:.7rem;font-size:.85rem;font-weight:600;text-decoration:none;"><i class="fa fa-calendar-plus"></i> Demander une intervention</a>`;
            }
            const marker = L.marker([v.latitude, v.longitude], {icon: greenIcon})
                .addTo(map)
                .bindPopup(popupHtml);
            markers.push(marker);
        }
    });

    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.15));
    }

    function updateScrollbar() {
        var sw = mapWrapper.scrollWidth - mapWrapper.clientWidth;
        var sh = mapWrapper.scrollHeight - mapWrapper.clientHeight;
        var bounds = map.getBounds();
        var center = map.getCenter();
        var x = sw > 0 ? (center.lng - bounds.getWest()) / (bounds.getEast() - bounds.getWest()) : 0.5;
        var y = sh > 0 ? (bounds.getNorth() - center.lat) / (bounds.getNorth() - bounds.getSouth()) : 0.5;
        mapWrapper.scrollLeft = x * sw;
        mapWrapper.scrollTop = y * sh;
    }

    function updateMapFromScroll() {
        var sw = mapWrapper.scrollWidth - mapWrapper.clientWidth;
        var sh = mapWrapper.scrollHeight - mapWrapper.clientHeight;
        var x = sw > 0 ? mapWrapper.scrollLeft / sw : 0.5;
        var y = sh > 0 ? mapWrapper.scrollTop / sh : 0.5;
        var bounds = map.getBounds();
        var lng = bounds.getWest() + (bounds.getEast() - bounds.getWest()) * x;
        var lat = bounds.getNorth() - (bounds.getNorth() - bounds.getSouth()) * y;
        map.off('move', updateScrollbar);
        map.panTo([lat, lng], { animate: false });
        map.on('move', updateScrollbar);
    }

    mapWrapper.addEventListener('scroll', updateMapFromScroll);
    map.on('move', updateScrollbar);

    map.whenReady(function () {
        mapWrapper.scrollLeft = (mapWrapper.scrollWidth - mapWrapper.clientWidth) / 2;
        mapWrapper.scrollTop = (mapWrapper.scrollHeight - mapWrapper.clientHeight) / 2;
    });

    map.on('zoomend', function () {
        mapWrapper.scrollLeft = (mapWrapper.scrollWidth - mapWrapper.clientWidth) / 2;
        mapWrapper.scrollTop = (mapWrapper.scrollHeight - mapWrapper.clientHeight) / 2;
    });

    function locateMe() {
        document.getElementById('status').textContent = 'Recherche de votre position...';
        if (!navigator.geolocation) {
            document.getElementById('status').textContent = 'Géolocalisation non supportée par votre navigateur.';
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => {
                const {latitude, longitude} = pos.coords;
                document.getElementById('status').textContent = `Vous êtes à ${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;

                if (window.userMarker) map.removeLayer(window.userMarker);

                window.userMarker = L.marker([latitude, longitude], {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="background:#2563eb;color:#fff;width:24px;height:24px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);"></div>',
                        iconSize: [24, 24],
                        iconAnchor: [12, 12],
                    })
                }).addTo(map).bindPopup('<strong>Vous êtes ici</strong>');

                if (markers.length > 0) {
                    const all = L.featureGroup([...markers, window.userMarker]);
                    map.fitBounds(all.getBounds().pad(0.15));
                } else {
                    map.setView([latitude, longitude], 12);
                }
            },
            () => {
                document.getElementById('status').textContent = 'Impossible de vous localiser. Vérifiez les permissions.';
            },
            {enableHighAccuracy: true}
        );
    }
</script>
@endpush

@section('content')
<div class="dashboard-wrapper" style="max-width:1100px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="font-size:1.8rem;font-weight:bold;color:#2563eb;">Vidangeurs près de chez vous</h2>
            <p style="color:#64748b;margin-top:.25rem;" id="status">Cliquez sur "Me localiser" pour voir les vidangeurs autour de vous</p>
        </div>
        <button onclick="locateMe()" style="padding:.7rem 1.5rem;background:#16a34a;color:#fff;border:none;border-radius:1rem;font-weight:bold;cursor:pointer;display:flex;align-items:center;gap:.5rem;">
            <i class="fa fa-location-dot"></i> Me localiser
        </button>
    </div>

    <div id="mapWrapper">
        <div id="mapTileError" style="display:none;padding:.7rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:.5rem;color:#991b1b;text-align:center;font-size:.9rem;">&#9888; Verifiez votre connexion internet.</div>
        <div id="map"></div>
        <div id="mapOverflow" style="width:200%;height:200%;pointer-events:none;"></div>
    </div>

    <div style="margin-top:1.5rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
        @forelse($vidangeurs as $v)
            <div style="background:#f8fafc;border-radius:1rem;padding:1rem;border:1px solid #e5e7eb;display:flex;flex-direction:column;gap:.3rem;">
                <strong style="color:#1e3a8a;">{{ $v->name }}</strong>
                <span style="font-size:.85rem;color:#64748b;"><i class="fa fa-map-pin"></i> {{ $v->quarter ?? '' }}, {{ $v->city ?? '' }}</span>
                @if($v->phone)
                    <span style="font-size:.85rem;color:#64748b;"><i class="fa fa-phone"></i> {{ $v->phone }}</span>
                @endif
                @if($v->tarif)
                    <span style="font-size:.85rem;color:#16a34a;font-weight:600;"><i class="fa fa-tag"></i> {{ $v->tarif }}</span>
                @endif
                @auth
                    @if(auth()->user()->role === 'menagere')
                        <a href="{{ route('reservations.create', ['vidangeur_id' => $v->id]) }}"
                           style="margin-top:.5rem;display:inline-block;text-align:center;padding:.4rem .8rem;background:#2563eb;color:#fff;border-radius:.7rem;font-size:.8rem;font-weight:600;text-decoration:none;">
                            <i class="fa fa-calendar-plus"></i> Demander une intervention
                        </a>
                    @endif
                @endauth
            </div>
        @empty
            <p style="color:#64748b;grid-column:1/-1;text-align:center;padding:2rem;">Aucun vidangeur n'a encore renseigné sa position.</p>
        @endforelse
    </div>
</div>
@endsection
