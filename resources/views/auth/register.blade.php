@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-slate-900">Créer un compte Demandeur</h1>
            <p class="text-xs text-slate-500 mt-1">Étudiants et personnels du campus</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Prénom et Nom">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="nom@campus.fr">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Minimum 6 caractères">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Confirmation du mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Répétez le mot de passe">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm shadow-sm transition">
                Valider l'inscription
            </button>

            <p class="text-center text-xs text-slate-500 mt-3">
                Déjà inscrit ? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
            </p>
        </form>
    </div>
</div>
@endsection
