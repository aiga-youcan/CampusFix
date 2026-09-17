<?php $__env->startSection('title', 'Déclarer un incident — CC FBS | CampusFix'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs text-slate-500 hover:text-slate-900 font-medium flex items-center space-x-1 mb-1.5">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Retour au registre</span>
        </a>
        <h1 class="text-lg font-semibold text-slate-900">Déclarer un Incident — CC Fquih Ben Salah</h1>
        <p class="text-xs text-slate-500">Renseignez les détails de la panne pour planifier l'intervention technique.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200">
        <form method="POST" action="<?php echo e(route('signalements.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Titre de la panne *</label>
                <input type="text" name="title" value="<?php echo e(old('title')); ?>" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none"
                       placeholder="ex: Vidéoprojecteur en panne, Fuite d'eau sous lavabo...">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Salle / Emplacement (CC FBS) *</label>
                    <select name="salle_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none">
                        <option value="">Sélectionnez une salle...</option>
                        <?php $__currentLoopData = $sallesByBuilding; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building => $salles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <optgroup label="<?php echo e($building); ?>">
                                <?php $__currentLoopData = $salles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($salle->id); ?>" <?php echo e(old('salle_id') == $salle->id ? 'selected' : ''); ?>>
                                        <?php echo e($salle->name); ?> <?php if($salle->code): ?> (<?php echo e($salle->code); ?>) <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Catégorie *</label>
                    <select name="category" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none">
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="mobilier">Mobilier</option>
                        <option value="autre">Autre équipement</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Niveau d'Urgence / Sévérité *</label>
                    <select name="severity" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none font-medium">
                        <option value="faible">Faible (Mineure)</option>
                        <option value="moyen" selected>Moyen (Sous 24h)</option>
                        <option value="critique">Critique (Urgence immédiate)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Description détaillée du dysfonctionnement *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none"
                          placeholder="Décrivez précisément ce que vous observez..."></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <a href="<?php echo e(route('signalements.index')); ?>" class="px-3.5 py-2 border border-slate-200 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition flex items-center space-x-1.5">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Enregistrer le signalement</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\V.PRO  CampusFix-Laravel-Full\campusfix\resources\views/signalements/create.blade.php ENDPATH**/ ?>