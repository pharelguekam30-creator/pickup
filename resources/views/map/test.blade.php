<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vidangeurs près de chez vous - Pickup</title>
<link rel="stylesheet" href="/leaflet/leaflet.min.css" />
<style>
body { margin:0; padding:20px; font-family:sans-serif; background:#f3f4f6; }
#map { height:520px; width:100%; border-radius:1rem; border:1px solid #e5e7eb; }
.leaflet-popup-content { margin:10px 14px; font-size:.95rem; line-height:1.5; }
.leaflet-popup-content-wrapper { border-radius:12px; }
</style>
</head>
<body>
<h2 style="margin-bottom:.5rem;">Vidangeurs près de chez vous</h2>
<p style="color:#64748b;margin-bottom:1rem;" id="status">Cliquez sur un marqueur pour voir les détails</p>
<button onclick="locateMe()" style="margin-bottom:1rem;padding:.5rem 1rem;background:#16a34a;color:#fff;border:none;border-radius:.7rem;font-weight:bold;cursor:pointer;">Me localiser</button>

<div id="map"></div>

<script src="/leaflet/leaflet.min.js"></script>
<script>
const vidangeurs = @json($vidangeurs);
const userRole = {{ json_encode(auth()->check() ? auth()->user()->role : null) }};
const map = L.map('map').setView([4.05, 11.5], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '\u00a9 OpenStreetMap',
    maxZoom: 18,
}).addTo(map);

const markers = [];
vidangeurs.forEach(v => {
    if (v.latitude) {
        let html = '<b>'+v.name+'</b><br><span style="color:#64748b;font-size:.85rem;">'+(v.quarter||'')+' '+(v.city||'')+'</span>';
        if (v.phone) html += '<br><span style="color:#64748b;font-size:.85rem;">Tel: '+v.phone+'</span>';
        if (userRole === 'menagere') {
            html += '<br><br><a href="/reservations/create?vidangeur_id='+v.id+'" style="display:inline-block;padding:.5rem 1rem;background:#2563eb;color:#fff;border-radius:.7rem;font-size:.85rem;font-weight:600;text-decoration:none;">Demander une intervention</a>';
        }
        var marker = L.marker([v.latitude, v.longitude]).addTo(map).bindPopup(html);
        markers.push(marker);
    }
});
if (markers.length > 0) {
    map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
}

function locateMe() {
    document.getElementById('status').textContent = 'Recherche...';
    if (!navigator.geolocation) { document.getElementById('status').textContent = 'Géolocalisation non supportée.'; return; }
    navigator.geolocation.getCurrentPosition(pos => {
        const {latitude, longitude} = pos.coords;
        document.getElementById('status').textContent = 'Position trouvée';
        if (window.userMarker) map.removeLayer(window.userMarker);
        window.userMarker = L.marker([latitude, longitude]).addTo(map).bindPopup('Vous êtes ici');
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
