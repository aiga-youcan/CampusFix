@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto mb-3 p-2 bg-white rounded-xl border border-slate-100 shadow-sm flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="CampusFix Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Campus<span class="text-blue-600">Fix</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Plateforme de maintenance et d'interventions</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                       placeholder="technicien@campusfix.test">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center space-x-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                    <span>Se souvenir de moi</span>
                </label>
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Créer un compte</a>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition duration-150">
                Se connecter
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
            <span class="font-bold text-slate-700 block mb-2">Comptes de test (Mot de passe: password) :</span>
            <ul class="space-y-1 font-mono text-[11px] text-slate-600">
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
@endsection