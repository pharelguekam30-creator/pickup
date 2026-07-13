<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carte</title>
<link rel="stylesheet" href="/leaflet/leaflet.min.css" />
<style>
body { margin:0; padding:20px; font-family:sans-serif; background:#f3f4f6; }
#map { height:520px; width:100%; border-radius:1rem; border:1px solid #e5e7eb; }
</style>
</head>
<body>
<h2 style="margin-bottom:1rem;">Vidangeurs</h2>
<div id="mapTileError" style="display:none;padding:.7rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:.5rem;color:#991b1b;text-align:center;font-size:.9rem;margin-bottom:.5rem;">&#9888; Verifiez votre connexion internet.</div>
<div id="map"></div>
<script src="/leaflet/leaflet.min.js"></script>
<script>
var data = @json($vidangeurs);
var role = @json(auth()->check() ? auth()->user()->role : null);
var map = L.map('map').setView([4.05, 11.5], 6);
var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
tileLayer.on('tileerror', function() { if (!window._tileError) { window._tileError = true; document.getElementById('mapTileError').style.display = 'block'; } });
for (var i = 0; i < data.length; i++) {
    var v = data[i];
    if (v.latitude) {
        var disp = v.disponibilite === false || v.disponibilite === 0 ? 'Non disponible' : 'Disponible';
        var html = '<b>' + v.name + '</b><br>';
        if (v.city) html += '<span>' + (v.quarter || '') + ' ' + v.city + '</span><br>';
        if (v.tarif) html += '<span style="font-weight:bold;color:#2563eb;">' + v.tarif + ' FCFA</span><br>';
        if (v.phone) html += '<span>Tel: ' + v.phone + '</span><br>';
        html += '<span style="color:' + (v.disponibilite ? '#16a34a' : '#dc2626') + ';">' + disp + '</span>';
        if (role === 'menagere') {
            html += '<br><br><a href="/reservations/create?vidangeur_id=' + v.id + '" style="display:inline-block;padding:8px 16px;background:#2563eb;color:#fff;border-radius:10px;font-size:14px;font-weight:bold;text-decoration:none;">Demander une intervention</a>';
        }
        L.marker([v.latitude, v.longitude]).addTo(map).bindPopup(html);
    }
}
</script>
</body>
</html>
