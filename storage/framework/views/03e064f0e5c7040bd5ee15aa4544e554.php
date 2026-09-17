<?php $__env->startSection('title', 'Tableau de bord | CampusFix'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Espace de travail — <?php echo e($user->name); ?></h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Profil <span class="capitalize font-medium text-slate-700"><?php echo e($user->role_name); ?></span> • Supervision des infrastructures de l'CC Fquih Ben Salah.
            </p>
        </div>
        <div>
            <a href="<?php echo e(route('signalements.create')); ?>" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Nouveau signalement</span>
            </a>
        </div>
    </div>

    

    <!-- 4 Cartes KPIs Standard (Zéro IA) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Signalements</div>
            <div class="text-2xl font-semibold text-slate-900 mt-1"><?php echo e($stats['total_signalements']); ?></div>
            <div class="text-[11px] text-slate-400 mt-1">Tous bâtiments confondus</div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-[11px] font-medium text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <span>En Attente</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-1"><?php echo e($stats['en_attente']); ?></div>
            <div class="text-[11px] text-slate-400 mt-1">Non traitées</div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-[11px] font-medium text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>En Cours</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-1"><?php echo e($stats['en_cours']); ?></div>
            <div class="text-[11px] text-slate-400 mt-1">Interventions en direct</div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-[11px] font-medium text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Résolus</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-1">
                <?php echo e($stats['resolus']); ?>

                <span class="text-xs font-normal text-slate-400 font-mono">(<?php echo e($stats['taux_resolution']); ?>)</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Taux de résolution global</div>
        </div>

    </div>

    <!-- Table Récente (Zéro IA) -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700">Derniers tickets enregistrés</h2>
            <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs text-slate-500 hover:text-slate-900 font-medium">
                Voir tout l'historique &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider font-medium">
                        <th class="py-2.5 px-4 font-normal">Titre &amp; Localisation</th>
                        <th class="py-2.5 px-4 font-normal">Auteur</th>
                        <th class="py-2.5 px-4 font-normal">Sévérité</th>
                        <th class="py-2.5 px-4 font-normal">Statut</th>
                        <th class="py-2.5 px-4 font-normal text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentSignalements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-900"><?php echo e($s->title); ?></div>
                            <div class="text-[11px] text-slate-400 mt-0.5"><?php echo e($s->location); ?> • <?php echo e(ucfirst($s->category)); ?></div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            <?php echo e($s->user?->name ?? 'Demandeur'); ?>

                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border
                                <?php echo e($s->severity == 'critique' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($s->severity == 'moyen' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200')); ?>">
                                <?php echo e(ucfirst($s->severity)); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center text-[11px] text-slate-700 font-medium">
                                <?php if($s->status == 'resolu'): ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Résolu
                                <?php elseif($s->status == 'pris_en_charge'): ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> En cours
                                <?php else: ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Signalé
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="<?php echo e(url('/signalements/' . $s->id)); ?>" class="text-xs font-medium text-slate-900 hover:underline">
                                Ouvrir &rarr;
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Aucun signalement dans la base.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\V.PRO  CampusFix-Laravel-Full\campusfix\resources\views/dashboard/index.blade.php ENDPATH**/ ?>