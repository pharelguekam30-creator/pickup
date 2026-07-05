<?php $__env->startSection('title', 'Mon Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-wrapper" style="max-width:800px;margin:2rem auto;background:#fff;border-radius:1.5rem;box-shadow:0 2px 16px #2563eb22;padding:2.5rem 2rem;">

    <?php if(session('success')): ?>
        <div style="padding:12px 16px;border-radius:10px;background:#dcfce7;color:#16a34a;font-weight:600;margin-bottom:1rem;border:1px solid #bbf7d0;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
        <?php if($user->photo): ?>
            <img src="<?php echo e(asset($user->photo)); ?>" alt="Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #2563eb;flex-shrink:0;">
        <?php else: ?>
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.2rem;font-weight:bold;flex-shrink:0;">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
        <?php endif; ?>
        <div style="flex:1;">
            <h2 style="font-size:1.8rem;font-weight:bold;color:#1e3a8a;margin-bottom:.25rem;"><?php echo e($user->name); ?></h2>
            <p style="color:#64748b;">
                <span style="display:inline-block;padding:.2rem .8rem;border-radius:1rem;background:#dbeafe;color:#1e40af;font-size:.8rem;font-weight:600;">
                    <?php echo e(ucfirst($user->role)); ?>

                </span>
            </p>
        </div>
        <a href="<?php echo e(route('profile.edit')); ?>" style="padding:.6rem 1.2rem;background:#2563eb;color:#fff;border-radius:1rem;text-decoration:none;font-weight:600;font-size:.9rem;">
            ✏️ Modifier
        </a>
    </div>

    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;">
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Email</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->email); ?></p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Téléphone</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->phone ?? 'Non renseigné'); ?></p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Ville</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->city ?? 'Non renseigné'); ?></p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Quartier</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->quarter ?? 'Non renseigné'); ?></p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Adresse</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->address ?? 'Non renseigné'); ?></p>
        </div>
        <div style="background:#f8fafc;padding:1rem;border-radius:1rem;">
            <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;font-weight:600;margin-bottom:.25rem;">Membre depuis</p>
            <p style="color:#1e293b;font-weight:500;"><?php echo e($user->created_at->format('d/m/Y')); ?></p>
        </div>
    </div>

    
    <?php if($user->role === 'vidangeur'): ?>
    <div style="background:linear-gradient(135deg,#ecfdf5,#f0fdf4);border-radius:1rem;padding:1.25rem;margin-bottom:2rem;">
        <h3 style="color:#065f46;font-weight:600;margin-bottom:1rem;">📋 Infos Vidangeur</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
            <div>
                <p style="color:#6b7280;font-size:.85rem;">Tarif</p>
                <p style="color:#065f46;font-weight:700;font-size:1.1rem;"><?php echo e($user->tarif ?? 'Non défini'); ?></p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:.85rem;">Disponibilité</p>
                <p style="color:#065f46;font-weight:600;"><?php echo e($user->disponibilite ?? 'Non définie'); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:1rem;padding:1.25rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <p style="color:#1e40af;font-weight:600;">Solde disponible</p>
            <p style="font-size:1.8rem;font-weight:bold;color:#2563eb;"><?php echo e(number_format($user->solde ?? 0, 0, ',', ' ')); ?> FCFA</p>
        </div>
        <a href="<?php echo e(route('payments.index')); ?>" style="padding:.6rem 1.2rem;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">
            Gerer mon portefeuille
        </a>
    </div>

    
    <div style="border-top:1px solid #e5e7eb;padding-top:1.5rem;">
        <h3 style="color:#1e3a8a;font-weight:600;margin-bottom:1rem;">
            📅 Mes dernières réservations
        </h3>
        <?php if($user->reservations->count() > 0): ?>
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                <?php $__currentLoopData = $user->reservations->sortByDesc('reservation_date')->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;padding:.75rem 1rem;border-radius:.75rem;border-left:4px solid <?php echo e($reservation->status === 'completed' ? '#16a34a' : ($reservation->status === 'accepted' ? '#2563eb' : ($reservation->status === 'canceled' ? '#dc2626' : '#f59e0b'))); ?>;">
                        <div>
                            <span style="font-weight:500;color:#1e293b;"><?php echo e(optional($reservation->service)->name ?? 'Service'); ?></span>
                            <span style="color:#94a3b8;font-size:.85rem;margin-left:.5rem;">
                                <?php echo e(optional($reservation->reservation_date)->format('d/m/Y H:i')); ?>

                            </span>
                        </div>
                        <span style="font-size:.75rem;font-weight:600;padding:.2rem .6rem;border-radius:1rem;background:<?php echo e($reservation->status === 'completed' ? '#dcfce7' : ($reservation->status === 'accepted' ? '#dbeafe' : ($reservation->status === 'canceled' ? '#fee2e2' : '#fef3c7'))); ?>;color:<?php echo e($reservation->status === 'completed' ? '#16a34a' : ($reservation->status === 'accepted' ? '#2563eb' : ($reservation->status === 'canceled' ? '#dc2626' : '#d97706'))); ?>;">
                            <?php echo e(ucfirst($reservation->status)); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p style="color:#94a3b8;text-align:center;padding:1.5rem;">Aucune réservation pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pharel\Desktop\cours\API\moi\PICKUP\resources\views/users/profile.blade.php ENDPATH**/ ?>