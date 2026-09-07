<?php $__env->startSection('content'); ?>
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-200/80 w-full max-w-md">
        
        <!-- Logo Image Ampoule & Titre -->
        <div class="text-center mb-7">
            <div class="w-20 h-20 mx-auto mb-3.5 p-2 bg-white rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center hover:shadow-md transition-shadow duration-200">
                <img src="<?php echo e(asset('images/logo.png')); ?>" 
     alt="CampusFix Logo" 
     class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center justify-center">
                Campus<span class="text-blue-600 ml-1">Fix</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Plateforme intelligente de maintenance & d'interventions</p>
        </div>

        <!-- Formulaire de Connexion -->
        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Adresse Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                       class="w-full px-3.5 py-2.5 bg-slate-50/60 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                       placeholder="technicien@campusfix.test">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3.5 py-2.5 bg-slate-50/60 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center space-x-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                    <span class="text-slate-600">Se souvenir de moi</span>
                </label>
                <a href="<?php echo e(route('register')); ?>" class="text-blue-600 font-semibold hover:underline">Créer un compte</a>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-600/25 transition-all duration-200">
                Se connecter
            </button>
        </form>

        <!-- Comptes de démonstration pour la soutenance -->
        <div class="mt-7 pt-5 border-t border-slate-100 text-xs text-slate-500 bg-slate-50/90 p-4 rounded-2xl border border-slate-100">
            <span class="font-bold text-slate-700 block mb-2 flex items-center space-x-1.5">
                <i data-lucide="key-round" class="w-4 h-4 text-blue-600"></i>
                <span>Comptes de test (Mot de passe: password) :</span>
            </span>
            <ul class="space-y-1.5 font-mono text-[11px] text-slate-600">
                <li class="flex items-center justify-between">
                    <span>• <strong>Admin :</strong> admin@campusfix.test</span>
                    <span class="text-[10px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded font-sans font-semibold">Direction</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>• <strong>Technicien :</strong> technicien@campusfix.test</span>
                    <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-sans font-semibold">Terrain</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>• <strong>Étudiant :</strong> etudiant@campusfix.test</span>
                    <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-sans font-semibold">Demandeur</span>
                </li>
            </ul>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\CampusFix-Laravel-Full\campusfix\resources\views/auth/login.blade.php ENDPATH**/ ?>