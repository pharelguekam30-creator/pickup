<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vidangeurs près de chez vous - Pickup</title>
<link rel="stylesheet" href="/leaflet/leaflet.min.css" />
<style>
body { margin:0; padding:20px; font-family:sans-serif; background:#f3f4f6; }
#map { height:520px; width:100%; border-radius:1rem; border:1px solid #e5e7eb; background:#e8e8e8; }
.leaflet-popup-content { margin:10px 14px; font-size:.95rem; line-height:1.5; }
.leaflet-popup-content-wrapper { border-radius:12px; }
</style>
</head>
<body>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="font-size:1.8rem;font-weight:bold;color:#2563eb;margin:0;">Vidangeurs près de chez vous</h2>
        <p style="color:#64748b;margin-top:.25rem;" id="status">Cliquez sur un marqueur pour voir les détails</p>
    </div>
    <button onclick="locateMe()" style="padding:.7rem 1.5rem;background:#16a34a;color:#fff;border:none;border-radius:1rem;font-weight:bold;cursor:pointer;">
        Me localiser
    </button>
</div>

<div id="map" style="height:520px;width:100%;border-radius:1rem;border:1px solid #e5e7eb;background:#e8e8e8;"></div>

<div style="margin-top:1.5rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
    @forelse($vidangeurs as $v)
        <div style="background:#fff;border-radius:1rem;padding:1rem;border:1px solid #e5e7eb;display:flex;flex-direction:column;gap:.3rem;">
            <strong style="color:#1e3a8a;">{{ $v->name }}</strong>
            <span style="font-size:.85rem;color:#64748b;">📌 {{ $v->quarter ?? '' }}, {{ $v->city ?? '' }}</span>
            @if($v->phone)
                <span style="font-size:.85rem;color:#64748b;">📞 {{ $v->phone }}</span>
            @endif
            @auth
                @if(auth()->user()->role === 'menagere')
                    <a href="{{ route('reservations.create', ['vidangeur_id' => $v->id]) }}"
                       style="margin-top:.5rem;display:inline-block;text-align:center;padding:.4rem .8rem;background:#2563eb;color:#fff;border-radius:.7rem;font-size:.8rem;font-weight:600;text-decoration:none;">
                        Demander une intervention
                    </a>
                @endif
            @endauth
        </div>
    @empty
        <p style="color:#64748b;grid-column:1/-1;text-align:center;padding:2rem;">Aucun vidangeur n'a encore renseigné sa position.</p>
    @endforelse
</div>

<script src="/leaflet/leaflet.min.js"></script>
<script>
const vidangeurs = @json($vidangeurs);
const userRole = {{ json_encode(auth()->check() ? auth()->user()->role : null) }};
const map = L.map('map').setView([4.05, 11.5], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap',
    maxZoom: 18,
}).addTo(map);

const greenIcon = L.divIcon({
    className: '',
    html: '<div style="background:#16a34a;color:#fff;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:bold;box-shadow:0 2px 8px rgba(0,0,0,.25);">V</div>',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -36],
});

const markers = [];

vidangeurs.forEach(v => {
    if (v.latitude && v.longitude) {
        let popupHtml = `<b>${v.name}</b><br>
            <span style="color:#64748b;font-size:.85rem;">${v.quarter || ''} ${v.city || ''}</span>`;
        if (v.phone) popupHtml += `<br><span style="color:#64748b;font-size:.85rem;">📞 ${v.phone}</span>`;
        if (userRole === 'menagere') {
            popupHtml += `<br><br><a href="/reservations/create?vidangeur_id=${v.id}" style="display:inline-block;text-align:center;padding:.5rem 1rem;background:#2563eb;color:#fff;border-radius:.7rem;font-size:.85rem;font-weight:600;text-decoration:none;">Demander une intervention</a>`;
        }
        const marker = L.marker([v.latitude, v.longitude], {icon: greenIcon})
            .addTo(map)
            .bindPopup(popupHtml);
        markers.push(marker);
    }
});

if (markers.length > 0) {
    map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
}

function locateMe() {
    document.getElementById('status').textContent = 'Recherche de votre position...';
    if (!navigator.geolocation) { document.getElementById('status').textContent = 'Géolocalisation non supportée.'; return; }
    navigator.geolocation.getCurrentPosition(pos => {
        const {latitude, longitude} = pos.coords;
        document.getElementById('status').textContent = 'Position trouvée';
        if (window.userMarker) map.removeLayer(window.userMarker);
        window.userMarker = L.marker([latitude, longitude], {
            icon: L.divIcon({
                className: '',
                html: '<div style="background:#2563eb;color:#fff;width:24px;height:24px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:bold;">M</div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12],
            })
        }).addTo(map).bindPopup('<strong>Vous êtes ici</strong>');
        if (markers.length > 0) {
            map.fitBounds(L.featureGroup([...markers, window.userMarker]).getBounds().pad(0.15));
        } else {
            map.setView([latitude, longitude], 12);
        }
    }, () => {
        document.getElementById('status').textContent = 'Impossible de vous localiser.';
    });
}
</script>
</body>
</html>
