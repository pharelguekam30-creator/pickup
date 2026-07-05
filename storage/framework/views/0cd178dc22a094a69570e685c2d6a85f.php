

<?php $__env->startSection('title', 'Modifier mon profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-wrapper" style="max-width:700px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
        <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:bold;flex-shrink:0;">
            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

        </div>
        <h2 style="font-size:1.6rem;font-weight:bold;color:#1e3a8a;">Modifier mon profil</h2>
    </div>

    <?php if($errors->any()): ?>
        <div style="border:1px solid #fca5a5;padding:12px;border-radius:8px;background:#fee2e2;color:#b91c1c;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if($user->photo): ?>
        <div style="text-align:center;margin-bottom:1.5rem;">
            <img src="<?php echo e(asset($user->photo)); ?>" alt="Photo de profil" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #2563eb;">
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Nom</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;" required>
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;" required>
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Téléphone</label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Pays</label>
                <input type="text" name="country" value="<?php echo e(old('country', $user->country)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Région</label>
                <input type="text" name="region" value="<?php echo e(old('region', $user->region)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Ville</label>
                <input type="text" name="city" value="<?php echo e(old('city', $user->city)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Quartier</label>
                <input type="text" name="quarter" value="<?php echo e(old('quarter', $user->quarter)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Adresse</label>
                <input type="text" name="address" value="<?php echo e(old('address', $user->address)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Photo de profil</label>
                <input type="file" name="photo" accept="image/*" style="width:100%;padding:10px;border:2px solid #cbd5e1;border-radius:10px;">
            </div>

            <?php if($user->role === 'vidangeur'): ?>
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Tarif (FCFA)</label>
                <input type="number" name="tarif" value="<?php echo e(old('tarif', $user->tarif)); ?>"
                       style="width:100%;padding:12px;border:2px solid #cbd5e1;border-radius:10px;outline:none;">
            </div>
            <div style="grid-column:1/-1;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Position sur la carte</label>
                <div id="map" style="height:300px;border-radius:10px;border:2px solid #cbd5e1;margin-bottom:.5rem;"></div>
                <input type="hidden" name="latitude" id="latitude" value="<?php echo e(old('latitude', $user->latitude)); ?>">
                <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', $user->longitude)); ?>">
                <p style="color:#94a3b8;font-size:.85rem;">Cliquez sur la carte pour placer votre position.</p>
            </div>
            <?php endif; ?>
        </div>

        <?php if($user->role === 'vidangeur'): ?>
        <link rel="stylesheet" href="/leaflet/leaflet.min.css" />
        <script src="/leaflet/leaflet.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lat = <?php echo e($user->latitude ?? 4.0441); ?>;
            var lng = <?php echo e($user->longitude ?? 9.7299); ?>;
            var map = L.map('map').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '\u00a9 OpenStreetMap'
            }).addTo(map);
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
        <?php endif; ?>

        <div style="display:flex;gap:1rem;margin-top:2rem;">
            <button type="submit" style="padding:.8rem 2rem;background:#2563eb;color:#fff;border:none;border-radius:10px;font-weight:700;cursor:pointer;">
                Enregistrer
            </button>
            <a href="<?php echo e(route('profile')); ?>" style="padding:.8rem 2rem;background:#e5e7eb;color:#374151;border-radius:10px;text-decoration:none;font-weight:600;text-align:center;">
                Annuler
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pharel\Desktop\cours\API\moi\PICKUP\resources\views/users/profile-edit.blade.php ENDPATH**/ ?>