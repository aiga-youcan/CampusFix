<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CampusFix') }} — Solution & Maintenance Intelligente</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js pour les menus et interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col font-sans">

    @php
        // Récupération automatique des alertes non traitées pour Admin et Technicien
        $unreadCount = 0;
        $urgentAlerts = collect();
        if(auth()->check() && auth()->user()->hasRole(['admin', 'technicien'])) {
            $unreadCount = \App\Models\Signalement::where('status', 'signale')->count();
            $urgentAlerts = \App\Models\Signalement::where('status', 'signale')->latest()->take(5)->get();
        }
    @endphp

    <!-- Barre de Navigation Supérieure -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Logo avec votre image locale LOGO.png -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-200">
                        <img src="{{ asset('images/logo.png') }}" 
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
            @auth
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                    Tableau de bord
                </a>
                <a href="{{ route('signalements.index') }}" class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('signalements.*') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                    Signalements
                </a>
                <a href="{{ route('signalements.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-600/20 transition-all duration-200 hover:-translate-y-0.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Déclarer une panne</span>
                </a>
            </nav>

            <!-- Actions Utilisateur & Notifications -->
            <div class="flex items-center space-x-4">
                
                <!-- Cloche de Notifications (Visible uniquement par Technicien et Admin) -->
                @if(auth()->user()->hasRole(['admin', 'technicien']))
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="relative p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition focus:outline-none" title="Notifications de pannes">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white shadow">
                                {{ $unreadCount }}
                            </span>
                        @endif
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
                                {{ $unreadCount }} nouvelle(s)
                            </span>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                            @forelse($urgentAlerts as $alert)
                            <a href="{{ url('/signalements/' . $alert->id) }}" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-slate-800 truncate max-w-[180px]">{{ $alert->title }}</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded {{ $alert->ai_score >= 70 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                        IA {{ $alert->ai_score }}/100
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 flex items-center space-x-1">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ $alert->location }}</span>
                                </div>
                            </a>
                            @empty
                            <div class="px-4 py-6 text-center text-slate-400">
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-500 mx-auto mb-1"></i>
                                <span>Toutes les pannes sont prises en charge !</span>
                            </div>
                            @endforelse
                        </div>

                        <div class="px-4 pt-2 border-t border-slate-100 text-center">
                            <a href="{{ route('signalements.index') }}" class="text-blue-600 font-semibold hover:underline text-[11px]">
                                Voir tous les signalements &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Badge utilisateur & Déconnexion -->
                <div class="text-right hidden sm:block border-l border-slate-200 pl-4">
                    <div class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider
                        {{ auth()->user()->hasRole('admin') ? 'bg-purple-100 text-purple-700' : (auth()->user()->hasRole('technicien') ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ auth()->user()->role_name }}
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Se déconnecter">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    <!-- Messages Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 flex items-center justify-between text-sm shadow-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Contenu Principal -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
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
</html>