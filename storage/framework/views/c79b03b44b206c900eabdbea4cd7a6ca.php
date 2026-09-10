<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'CampusFix | EST Fquih Ben Salah'); ?></title>
    
    <!-- Favicon في لسان المتصفح -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col font-sans">

    <?php
        $readIds = session('read_notifications', []);
        $unreadCount = 0;
        $urgentAlerts = collect();
        if(auth()->check() && auth()->user()->hasRole(['admin', 'technicien'])) {
            $unreadCount = \App\Models\Signalement::where('status', 'signale')->whereNotIn('id', $readIds)->count();
            $urgentAlerts = \App\Models\Signalement::where('status', 'signale')->whereNotIn('id', $readIds)->latest()->take(6)->get();
        }
    ?>

    <!-- شريط التنقل العلوي المخصص لـ EST FBS -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-2.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 p-1 flex items-center justify-center">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-base text-slate-900 tracking-tight flex items-center">
                            Campus<span class="text-indigo-600 ml-0.5">Fix</span>
                        </span>
                        <span class="block text-[10px] text-slate-400 font-semibold tracking-wider uppercase">
                            EST Fquih Ben Salah
                        </span>
                    </div>
                </a>
            </div>

            <?php if(auth()->guard()->check()): ?>
            <nav class="hidden md:flex items-center space-x-6">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-xs font-medium transition-colors <?php echo e(request()->routeIs('dashboard') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900'); ?>">
                    Tableau de bord
                </a>
                <a href="<?php echo e(route('signalements.index')); ?>" class="text-xs font-medium transition-colors <?php echo e(request()->routeIs('signalements.*') ? 'text-slate-900 font-semibold' : 'text-slate-500 hover:text-slate-900'); ?>">
                    Signalements
                </a>
                <a href="<?php echo e(route('signalements.create')); ?>" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-900 hover:bg-slate-800 text-white transition-colors">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Déclarer une panne</span>
                </a>
            </nav>

            <div class="flex items-center space-x-3">
                <?php if(auth()->user()->hasRole(['admin', 'technicien'])): ?>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="relative p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition focus:outline-none" title="Notifications">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <?php if($unreadCount > 0): ?>
                            <span class="absolute top-1.5 right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white">
                                <?php echo e($unreadCount); ?>

                            </span>
                        <?php endif; ?>
                    </button>

                    <div x-show="open" class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-slate-200 py-2 z-50 text-xs" style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="font-semibold text-slate-800 text-xs">Pannes à traiter (EST FBS)</span>
                            <?php if($unreadCount > 0): ?>
                            <form method="POST" action="<?php echo e(route('notifications.markAllRead')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-[10px] text-indigo-600 hover:underline font-medium">Tout marquer lu</button>
                            </form>
                            <?php endif; ?>
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $urgentAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-3 hover:bg-slate-50 transition flex items-start justify-between gap-2">
                                <a href="<?php echo e(url('/signalements/' . $alert->id)); ?>" class="flex-1 block">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-slate-900 truncate max-w-[170px]"><?php echo e($alert->title); ?></span>
                                        <span class="text-[9px] font-semibold px-1.5 py-0.2 rounded border uppercase <?php echo e($alert->severity == 'critique' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-100 text-slate-700 border-slate-200'); ?>">
                                            <?php echo e($alert->severity); ?>

                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        📍 <?php echo e($alert->location); ?> • <span class="text-slate-400"><?php echo e($alert->created_at?->diffForHumans() ?? 'Récemment'); ?></span>
                                    </div>
                                </a>
                                <form method="POST" action="<?php echo e(route('notifications.markRead', $alert->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-1 text-slate-400 hover:text-slate-700" title="Marquer lu">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-5 text-center text-slate-400 text-xs">
                                <span>Aucune notification non lue</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="px-4 pt-2 border-t border-slate-100 text-center">
                            <a href="<?php echo e(route('signalements.index')); ?>" class="text-slate-600 hover:text-slate-900 font-medium text-[11px]">
                                Voir tous les tickets &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-right hidden sm:block border-l border-slate-200 pl-3">
                    <div class="text-xs font-medium text-slate-800"><?php echo e(auth()->user()->name); ?></div>
                    <span class="inline-block px-1.5 py-0.2 text-[10px] font-medium rounded bg-slate-100 text-slate-600 border border-slate-200 capitalize">
                        <?php echo e(auth()->user()->role_name); ?>

                    </span>
                </div>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Se déconnecter">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php if(session('success')): ?>
            <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-xs mb-3">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('info')): ?>
            <div class="p-3 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-between text-xs mb-3">
                <div class="flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4 text-slate-600"></i>
                    <span><?php echo e(session('info')); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-white border-t border-slate-200 py-5 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>CampusFix &copy; 2026 — École Supérieure de Technologie de Fquih Ben Salah (Université Sultan Moulay Slimane)</div>
            <div class="text-slate-400">Système de Maintenance &amp; Triage IA</div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>