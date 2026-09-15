@extends('layouts.app')

@section('title', 'Inscription Demandeur | CampusFix')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 w-full max-w-md">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 mx-auto mb-3 p-1.5 bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-center">
                <img src="{{ asset('images/LOGO.png') }}" alt="CampusFix" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-semibold text-slate-900 tracking-tight">Créer un compte</h1>
            <p class="text-xs text-slate-500 mt-0.5">Espace dédié aux étudiants et personnels du campus</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="Prénom et Nom">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="nom@campus.fr">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="Minimum 6 caractères">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Confirmation du mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none transition"
                       placeholder="Répétez le mot de passe">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition-colors mt-1">
                Valider l'inscription
            </button>

            <p class="text-center text-xs text-slate-500 pt-2">
                Déjà un compte ? <a href="{{ route('login') }}" class="text-slate-900 font-medium hover:underline">Se connecter</a>
            </p>
        </form>

    </div>
</div>
@endsection
