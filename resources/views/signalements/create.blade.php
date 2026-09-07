@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('signalements.index') }}" class="text-xs text-blue-600 font-semibold hover:underline flex items-center space-x-1 mb-2">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Retour au registre</span>
        </a>
        <h1 class="text-xl font-bold text-slate-900">Déclarer un Incident ou une Panne</h1>
        <p class="text-xs text-slate-500">Le système CampusAiAgent évaluera automatiquement la priorité et la criticité de l'intervention.</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('signalements.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Titre de la panne *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="ex: Fuite d'eau sous lavabo, Disjoncteur sauté...">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Emplacement précis *</label>
                    <input type="text" name="location" value="{{ old('location') }}" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="ex: Bâtiment C - Laboratoire 204">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Catégorie technique *</label>
                    <select name="category" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="mobilier">Mobilier</option>
                        <option value="autre">Autre infrastructure</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description détaillée du dysfonctionnement *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                          placeholder="Décrivez précisément ce que vous observez (bruit, fumée, eau au sol, ampoule brûlée...)."></textarea>
                <span class="text-[11px] text-slate-400 mt-1 block">Plus la description est précise, plus le calcul du score d'urgence par l'agent IA sera pertinent.</span>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('signalements.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition flex items-center space-x-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Soumettre et Analyser avec l'IA</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
