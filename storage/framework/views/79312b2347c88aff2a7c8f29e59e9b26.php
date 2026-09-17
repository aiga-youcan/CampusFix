<?php $__env->startSection('title', $signalement->title . ' | CampusFix'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs text-slate-500 hover:text-slate-900 font-medium flex items-center space-x-1 mb-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Retour au registre</span>
            </a>
            <h1 class="text-xl font-semibold text-slate-900"><?php echo e($signalement->title); ?></h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Signalé le <span class="font-mono text-slate-700"><?php echo e($signalement->created_at?->format('d/m/Y à H:i') ?? 'Récemment'); ?></span>
                par <span class="text-slate-700 font-medium"><?php echo e($signalement->user?->name ?? 'Demandeur'); ?></span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-medium bg-white border-slate-200 text-slate-800">
                <?php if($signalement->status == 'resolu'): ?>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Résolu
                <?php elseif($signalement->status == 'pris_en_charge'): ?>
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> En cours
                <?php else: ?>
                    <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span> Signalé
                <?php endif; ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Colonne Gauche : Détails et Historique (Sans carte IA) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informations de la panne -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3.5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">Informations de l'incident</h2>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block uppercase font-medium">Salle / Lieu :</span>
                        <span class="font-medium text-slate-900 text-sm mt-0.5 block">📍 <?php echo e($signalement->location); ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block uppercase font-medium">Catégorie &amp; Sévérité :</span>
                        <span class="font-medium text-slate-900 text-sm mt-0.5 block capitalize">
                            <?php echo e($signalement->category); ?> • 
                            <span class="text-xs uppercase px-1.5 py-0.2 rounded border font-medium <?php echo e($signalement->severity == 'critique' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($signalement->severity == 'moyen' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200')); ?>">
                                <?php echo e($signalement->severity); ?>

                            </span>
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 block uppercase font-medium text-xs mb-1">Description du problème :</span>
                    <div class="bg-slate-50 p-3.5 rounded-lg text-xs text-slate-700 leading-relaxed border border-slate-200">
                        <?php echo e($signalement->description); ?>

                    </div>
                </div>
            </div>

            <!-- Historique des interventions techniques -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">Historique des interventions</h2>

                <?php $__empty_1 = true; $__currentLoopData = $signalement->interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-l-2 border-slate-900 pl-3.5 py-1 space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-medium text-slate-900"><?php echo e($intervention->technicien?->name ?? 'Technicien'); ?></span>
                        <span class="text-slate-400 font-mono">
                            <?php echo e($intervention->created_at?->format('d/m/Y H:i') ?? 'Récemment'); ?> 
                            (<?php echo e($intervention->duration_minutes); ?> min)
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                        <?php echo e($intervention->notes); ?>

                    </p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs text-slate-400 italic py-2">Aucune intervention enregistrée pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonne Droite : Formulaire technicien -->
        <div class="space-y-6">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('intervene', $signalement)): ?>
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3.5">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2 flex items-center space-x-1.5">
                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-slate-700"></i>
                    <span>Espace Technicien</span>
                </h3>

                <form method="POST" action="<?php echo e(route('interventions.store')); ?>" class="space-y-3 text-xs">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="signalement_id" value="<?php echo e($signalement->id); ?>">

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Nouveau Statut *</label>
                        <select name="status" required class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none font-medium">
                            <option value="pris_en_charge" <?php echo e($signalement->status == 'pris_en_charge' ? 'selected' : ''); ?>>En cours (Pris en charge)</option>
                            <option value="resolu" <?php echo e($signalement->status == 'resolu' ? 'selected' : ''); ?>>Résolu (Clôturer le ticket)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Durée d'intervention (minutes) *</label>
                        <input type="number" name="duration_minutes" value="30" min="5" max="1440" required
                               class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Rapport technique &amp; Actions menées *</label>
                        <textarea name="notes" rows="4" required
                                  class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none"
                                  placeholder="Décrivez les réparations effectuées, pièces changées..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg transition-colors">
                        Enregistrer l'intervention
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <div class="bg-white p-4 rounded-xl border border-slate-200 text-xs space-y-2">
                <span class="font-medium text-slate-800 uppercase tracking-wider block text-[11px]">Traçabilité</span>
                <p class="text-slate-500 leading-relaxed">
                    Toutes les actions techniques sont journalisées avec intégrité relationnelle dans la base de données.
                </p>
                <div class="pt-2 border-t border-slate-100 text-slate-400 font-mono text-[11px]">
                    <div>ID Ticket : #<?php echo e($signalement->id); ?></div>
                    <div>Horodatage : <?php echo e($signalement->created_at?->format('d/m/Y H:i:s') ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\V.PRO  CampusFix-Laravel-Full\campusfix\resources\views/signalements/show.blade.php ENDPATH**/ ?>