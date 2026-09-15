<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFix | EST Fquih Ben Salah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-base text-slate-900">Campus<span class="text-blue-600">Fix</span></span>
            </a>

            @auth
            <nav class="flex items-center space-x-4 text-xs font-medium">
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-semibold">Tableau de bord</a>
                <a href="{{ route('signalements.index') }}" class="text-slate-600 hover:text-slate-900">Signalements</a>
                <a href="{{ route('signalements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg flex items-center space-x-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Déclarer une panne</span>
                </a>

                <span class="text-slate-500 pl-2 border-l border-slate-200">{{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1" title="Déconnexion">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </nav>
            @endauth
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto px-4 py-6 w-full">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400 mt-auto">
        CampusFix &copy; 2026 — EST Fquih Ben Salah
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>