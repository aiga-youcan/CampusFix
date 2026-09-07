<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name', 'CampusFix')); ?> — Solution & Maintenance Intelligente</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js pour les menus et interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col font-sans">

    <?php
        // Récupération automatique des alertes non traitées pour Admin et Technicien
        $unreadCount = 0;
        $urgentAlerts = collect();
        if(auth()->check() && auth()->user()->hasRole(['admin', 'technicien'])) {
            $unreadCount = \App\Models\Signalement::where('status', 'signale')->count();
            $urgentAlerts = \App\Models\Signalement::where('status', 'signale')->latest()->take(5)->get();
        }
    ?>

    <!-- Barre de Navigation Supérieure -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Logo avec votre image locale LOGO.png -->
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-200">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" 
                             alt="CampusFix Logo" 
                             class="w-full h-full object-contain rounded-lg">
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-900 tracking-tight flex items-center">
                            Campus<span class="text-blue-600 ml-0.5">Fix</span>
                        </span>
                        <span class="block text-[10px] text-slate-400 font-semibold tracking-wider uppercase">
                            Smart Maintenance
                        </span>
                    </div>
                </a>
            </div>

            <!-- Liens de navigation -->
            <?php if(auth()->guard()->check()): ?>
            <nav class="hidden md:flex items-center space-x-6">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600'); ?>">
                    Tableau de bord
                </a>
                <a href="<?php echo e(route('signalements.index')); ?>" class="text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('signalements.*') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600'); ?>">
                    Signalements
                </a>
                <a href="<?php echo e(route('signalements.create')); ?>" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-600/20 transition-all duration-200 hover:-translate-y-0.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Déclarer une panne</span>
                </a>
            </nav>

            <!-- Actions Utilisateur & Notifications -->
            <div class="flex items-center space-x-4">
                
                <!-- Cloche de Notifications (Visible uniquement par Technicien et Admin) -->
                <?php if(auth()->user()->hasRole(['admin', 'technicien'])): ?>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="relative p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition focus:outline-none" title="Notifications de pannes">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <?php if($unreadCount > 0): ?>
                            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white shadow">
                                <?php echo e($unreadCount); ?>

                            </span>
                        <?php endif; ?>
                    </button>

                    <!-- Menu déroulant des notifications -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50 text-xs"
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-800 text-sm flex items-center space-x-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-500"></i>
                                <span>Pannes en attente</span>
                            </span>
                            <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-semibold text-[10px]">
                                <?php echo e($unreadCount); ?> nouvelle(s)
                            </span>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $urgentAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(url('/signalements/' . $alert->id)); ?>" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-slate-800 truncate max-w-[180px]"><?php echo e($alert->title); ?></span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded <?php echo e($alert->ai_score >= 70 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'); ?>">
                                        IA <?php echo e($alert->ai_score); ?>/100
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 flex items-center space-x-1">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                    <span><?php echo e($alert->location); ?></span>
                                </div>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-4 py-6 text-center text-slate-400">
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-500 mx-auto mb-1"></i>
                                <span>Toutes les pannes sont prises en charge !</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="px-4 pt-2 border-t border-slate-100 text-center">
                            <a href="<?php echo e(route('signalements.index')); ?>" class="text-blue-600 font-semibold hover:underline text-[11px]">
                                Voir tous les signalements &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Badge utilisateur & Déconnexion -->
                <div class="text-right hidden sm:block border-l border-slate-200 pl-4">
                    <div class="text-xs font-semibold text-slate-800"><?php echo e(auth()->user()->name); ?></div>
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider
                        <?php echo e(auth()->user()->hasRole('admin') ? 'bg-purple-100 text-purple-700' : (auth()->user()->hasRole('technicien') ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700')); ?>">
                        <?php echo e(auth()->user()->role_name); ?>

                    </span>
                </div>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Se déconnecter">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Messages Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php if(session('success')): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <span><?php echo e(session('info')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm mb-4">
                <ul class="list-disc list-inside space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- Contenu Principal -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Pied de page épuré -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center space-x-1">
                <span class="font-bold text-slate-700">CampusFix</span>
                <span>— Plateforme intelligente de gestion des infrastructures</span>
            </div>
            <div class="text-slate-400">
                Développé sous Laravel 10 & Moteur IA Triage
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html><?php /**PATH C:\Users\user\Desktop\CampusFix-Laravel-Full\campusfix\resources\views/layouts/app.blade.php ENDPATH**/ ?>