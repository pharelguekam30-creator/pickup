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
<div id="map"></div>
<script src="/leaflet/leaflet.min.js"></script>
<script>
var data = <?php echo json_encode($vidangeurs, 15, 512) ?>;
var role = <?php echo json_encode(auth()->check() ? auth()->user()->role : null, 15, 512) ?>;
var map = L.map('map').setView([4.05, 11.5], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
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
<?php /**PATH C:\Users\pharel\Desktop\cours\API\moi\PICKUP\resources\views/map/simple.blade.php ENDPATH**/ ?>