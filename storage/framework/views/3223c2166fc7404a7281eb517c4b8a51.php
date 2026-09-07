<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- En-tête de bienvenue -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Bonjour, <?php echo e($user->name); ?> 👋</h1>
            <p class="text-xs text-slate-500 mt-1">
                Espace <strong class="capitalize text-blue-600 font-semibold"><?php echo e($user->role_name); ?></strong> — Suivi des signalements et interventions de maintenance.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('signalements.create')); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition flex items-center space-x-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nouveau Signalement</span>
            </a>
        </div>
    </div>

    <!-- KHANA DE NOTIFICATIONS : Visible UNIQUEMENT par le Technicien et l'Admin -->
    <?php if($user->hasRole(['technicien', 'admin'])): ?>
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4 border-b border-blue-200/60 pb-3">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="bell-ring" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Centre d'Alertes & Nouvelles Pannes</h2>
                    <p class="text-[11px] text-slate-500">Signalements récents nécessitant une prise en charge</p>
                </div>
            </div>
            <span class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                <?php echo e($notifications->count()); ?> en attente
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider <?php echo e($notif->severity == 'critique' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'); ?>">
                            <?php echo e($notif->severity); ?> • IA <?php echo e($notif->ai_score); ?>/100
                        </span>
                        <span class="text-[10px] text-slate-400">
                            <?php echo e($notif->created_at?->diffForHumans() ?? 'Récemment'); ?>

                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-xs truncate"><?php echo e($notif->title); ?></h3>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center space-x-1">
                        <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                        <span><?php echo e($notif->location); ?></span>
                    </p>
                    <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                        <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                        <span>Signalé par <strong><?php echo e($notif->user?->name ?? 'Demandeur'); ?></strong></span>
                    </p>
                </div>

                <div class="mt-3 pt-2 border-t border-slate-100 flex justify-end">
                    <a href="<?php echo e(url('/signalements/' . $notif->id)); ?>" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-semibold transition flex items-center space-x-1">
                        <span>Prendre en charge</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-4 text-center text-slate-500 text-xs flex items-center justify-center space-x-2 bg-white rounded-xl border border-blue-100">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                <span>Aucune nouvelle panne en attente. Tout est sous contrôle !</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Grille des Métriques (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Signalements</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($stats['total_signalements']); ?></h3>
                <span class="text-[11px] text-slate-400 mt-1 block">Tous bâtiments</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">En Attente</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1"><?php echo e($stats['en_attente']); ?></h3>
                <span class="text-[11px] text-slate-400 mt-1 block">À traiter d'urgence</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">En Cours</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($stats['en_cours']); ?></h3>
                <span class="text-[11px] text-slate-400 mt-1 block">Interventions actives</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Résolus (Score IA)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1"><?php echo e($stats['resolus']); ?> <span class="text-xs font-normal text-slate-500">(<?php echo e($stats['avg_ai_score']); ?>/100)</span></h3>
                <span class="text-[11px] text-slate-400 mt-1 block"><?php echo e($stats['critiques']); ?> cas critiques résolus</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="check-check" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Table des Signalements Récapitulatifs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i data-lucide="activity" class="w-4 h-4 text-blue-600"></i>
                <span>Historique Récent</span>
            </h2>
            <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs text-blue-600 font-semibold hover:underline">
                Voir tout le registre &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4">Titre & Lieu</th>
                        <th class="py-3 px-4">Demandeur</th>
                        <th class="py-3 px-4">Score IA</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentSignalements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900"><?php echo e($s->title); ?></div>
                            <div class="text-[11px] text-slate-500 mt-0.5"><?php echo e($s->location); ?> • <?php echo e(ucfirst($s->category)); ?></div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            <?php echo e($s->user?->name ?? 'Demandeur'); ?>

                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold <?php echo e($s->ai_score >= 70 ? 'text-rose-600' : ($s->ai_score >= 40 ? 'text-amber-600' : 'text-slate-600')); ?>">
                                <?php echo e($s->ai_score); ?>/100
                            </span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border uppercase font-semibold <?php echo e($s->severity_color); ?> ml-1">
                                <?php echo e($s->severity); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-[10px] px-2 py-0.5 rounded-full border font-medium <?php echo e($s->status_color); ?>">
                                <?php echo e(str_replace('_', ' ', $s->status)); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="<?php echo e(url('/signalements/' . $s->id)); ?>" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition">
                                Gérer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Aucun signalement enregistré.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\CampusFix-Laravel-Full\campusfix\resources\views/dashboard/index.blade.php ENDPATH**/ ?>