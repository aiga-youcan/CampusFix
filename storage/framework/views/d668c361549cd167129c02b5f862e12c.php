<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs text-blue-600 font-semibold hover:underline flex items-center space-x-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Retour aux signalements</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900"><?php echo e($signalement->title); ?></h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Déclaré le <?php echo e($signalement->created_at?->format('d/m/Y à H:i') ?? 'Récemment'); ?> 
                par <?php echo e($signalement->user?->name ?? 'Demandeur'); ?>

            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 rounded-full border text-xs font-semibold <?php echo e($signalement->status_color); ?>">
                <?php echo e(str_replace('_', ' ', $signalement->status)); ?>

            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Gauche : Détails & Rapport IA -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de la panne -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Détails de l'incident</h2>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Localisation :</span>
                        <span class="font-medium text-slate-800 text-sm mt-0.5 block"><?php echo e($signalement->location); ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Catégorie technique :</span>
                        <span class="font-medium text-slate-800 text-sm mt-0.5 block capitalize"><?php echo e($signalement->category); ?></span>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold text-xs uppercase mb-1">Description signalée :</span>
                    <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 leading-relaxed border border-slate-100">
                        <?php echo e($signalement->description); ?>

                    </div>
                </div>
            </div>

            <!-- Carte de Triage IA (CampusAiAgent) -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-md space-y-4 border border-slate-700">
                <div class="flex items-center justify-between border-b border-slate-700/60 pb-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm tracking-wide">CampusAiAgent — Analyse & Diagnostic</h3>
                            <span class="text-[10px] text-slate-400">Triage algorithmique multicritère</span>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(url('/signalements/' . $signalement->id . '/retriage')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-3 py-1 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs font-semibold text-slate-200 transition" title="Recalculer le score">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5 inline mr-1"></i>
                            Réévaluer
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-semibold">Score d'urgence</span>
                        <span class="text-2xl font-extrabold <?php echo e($signalement->ai_score >= 70 ? 'text-rose-400' : ($signalement->ai_score >= 40 ? 'text-amber-400' : 'text-emerald-400')); ?>">
                            <?php echo e($signalement->ai_score); ?> / 100
                        </span>
                    </div>

                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-semibold">Sévérité calculée</span>
                        <span class="text-sm font-bold uppercase mt-1 block <?php echo e($signalement->severity == 'critique' ? 'text-rose-400' : ($signalement->severity == 'moyen' ? 'text-amber-400' : 'text-slate-300')); ?>">
                            <?php echo e($signalement->severity); ?>

                        </span>
                    </div>

                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-semibold">Délai estimé</span>
                        <span class="text-sm font-bold text-sky-400 mt-1 block">
                            ~<?php echo e($signalement->ai_estimated_hours); ?> heure(s)
                        </span>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/60">
                        <strong class="text-blue-300 block mb-1">Diagnostic de l'Agent :</strong>
                        <p class="text-slate-300 leading-relaxed"><?php echo e($signalement->ai_diagnostic ?? 'Analyse standard effectuée.'); ?></p>
                    </div>

                    <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/60">
                        <strong class="text-emerald-300 block mb-1">Action d'intervention recommandée :</strong>
                        <p class="text-slate-300 leading-relaxed"><?php echo e($signalement->ai_recommended_action ?? 'Planifier l\'intervention selon les disponibilités.'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Historique des interventions -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Historique des interventions techniques</h2>

                <?php $__empty_1 = true; $__currentLoopData = $signalement->interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-l-2 border-blue-500 pl-4 py-2 space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800"><?php echo e($intervention->technicien?->name ?? 'Technicien'); ?></span>
                        <span class="text-slate-400">
                            <?php echo e($intervention->created_at?->format('d/m/Y à H:i') ?? 'Récemment'); ?> 
                            (<?php echo e($intervention->duration_minutes); ?> min)
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <?php echo e($intervention->notes); ?>

                    </p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs text-slate-400 italic py-2">Aucune intervention enregistrée pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonne Droite : Formulaire d'action Technicien / Admin -->
        <div class="space-y-6">
            <?php if(auth()->user()?->hasRole(['technicien', 'admin'])): ?>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center space-x-2">
                    <i data-lucide="wrench" class="w-4 h-4 text-amber-600"></i>
                    <span>Espace Intervention Technicien</span>
                </h3>

                <form method="POST" action="<?php echo e(route('interventions.store')); ?>" class="space-y-3 text-xs">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="signalement_id" value="<?php echo e($signalement->id); ?>">

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Statut après intervention *</label>
                        <select name="status" required class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500">
                            <option value="pris_en_charge" <?php echo e($signalement->status == 'pris_en_charge' ? 'selected' : ''); ?>>Prise en charge (En cours)</option>
                            <option value="resolu" <?php echo e($signalement->status == 'resolu' ? 'selected' : ''); ?>>Résolue (Clôturer)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Durée réelle (en minutes) *</label>
                        <input type="number" name="duration_minutes" value="30" min="5" max="1440" required
                               class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Rapport d'intervention & Pièces *</label>
                        <textarea name="notes" rows="4" required
                                  class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500"
                                  placeholder="Indiquez les manipulations réalisées, pièces remplacées ou mesures prises..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm transition">
                        Enregistrer l'intervention
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Résumé des informations rapides -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-xs space-y-3">
                <span class="font-bold text-slate-800 uppercase tracking-wider block">Rappel d'intégrité</span>
                <p class="text-slate-600 leading-relaxed">
                    Toutes les actions sont tracées dans la base de données relationnelle avec contraintes d'intégrité référentielle en cascade.
                </p>
                <div class="pt-2 border-t border-slate-200">
                    <span class="text-slate-500 block">ID Signalement : #<?php echo e($signalement->id); ?></span>
                    <span class="text-slate-500 block">Dernière mise à jour : <?php echo e($signalement->updated_at?->diffForHumans() ?? 'Récemment'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/signalements/show.blade.php ENDPATH**/ ?>