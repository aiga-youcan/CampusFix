<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFix — Mot de passe oublié</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">CAMPUS<span class="text-indigo-600">FIX</span></h1>
            <p class="text-xs text-slate-500 mt-1">Réinitialisation de mot de passe</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded p-3">
                {{ session('status') }}
            </div>
        @endif

        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
            Mot de passe oublié ? Indiquez votre adresse email institutionnelle pour recevoir un lien de réinitialisation.
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                @error('email')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded text-sm transition">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs text-indigo-600 hover:underline">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
