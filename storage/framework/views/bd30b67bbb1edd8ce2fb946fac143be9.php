<?php $__env->startSection('title', 'Connexion | CampusFix'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 w-full max-w-md shadow-sm">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 mx-auto mb-3 p-1.5 bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-center">
                <img src="<?php echo e(asset('images/LOGO.png')); ?>" alt="CampusFix" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-semibold text-slate-900 tracking-tight">CampusFix</h1>
            <p class="text-xs text-slate-500 mt-0.5">Portail de maintenance et d'interventions</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-4 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded p-3">
                Identifiants incorrects. Veuillez vérifier votre adresse email et mot de passe.
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="votre.email@ccfbs.usms.ac.ma">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider">Mot de passe</label>
                    <a href="#" class="text-[11px] text-slate-500 hover:text-slate-800">Oublié ?</a>
                </div>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-0.5">
                <label class="flex items-center space-x-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-slate-900 border-slate-300 focus:ring-0">
                    <span>Se souvenir de moi</span>
                </label>
                <a href="<?php echo e(route('register')); ?>" class="text-slate-900 font-medium hover:underline">Créer un compte</a>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition-colors">
                Se connecter
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 bg-slate-50/90 p-3.5 rounded-xl border border-slate-200">
            <span class="font-medium text-slate-700 block mb-2">Comptes de test (Mot de passe: password) :</span>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillLogin('admin@ccfbs.usms.ac.ma', 'password')"
                        class="p-2 text-center bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition text-[11px] font-medium text-slate-700 shadow-xs">
                    Direction
                </button>
                <button type="button" onclick="fillLogin('technicien@ccfbs.usms.ac.ma', 'password')"
                        class="p-2 text-center bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition text-[11px] font-medium text-slate-700 shadow-xs">
                    Technicien
                </button>
                <button type="button" onclick="fillLogin('etudiant@usms.ma', 'password')"
                        class="p-2 text-center bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition text-[11px] font-medium text-slate-700 shadow-xs">
                    Demandeur
                </button>
            </div>
        </div>

    </div>
</div>

<script>
function fillLogin(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\user\Desktop\V.PRO  CampusFix-Laravel-Full\campusfix\resources\views/auth/login.blade.php ENDPATH**/ ?>