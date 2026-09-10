@extends('layouts.app')

@section('title', 'Déclarer un incident — EST FBS | CampusFix')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('signalements.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-medium flex items-center space-x-1 mb-1.5">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Retour au registre</span>
        </a>
        <h1 class="text-lg font-semibold text-slate-900">Déclarer un Incident — EST Fquih Ben Salah</h1>
        <p class="text-xs text-slate-500">Sélectionnez la salle concernée et le niveau d'urgence.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200">
        <form method="POST" action="{{ route('signalements.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Titre de la panne *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none"
                       placeholder="ex: Vidéoprojecteur en panne, Prise électrique déboîtée...">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- قاعات ومختبرات EST FBS -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Localisation (EST FBS) *</label>
                    <select name="location" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none">
                        <optgroup label="Amphithéâtres">
                            <option value="Amphithéâtre A">Amphithéâtre A</option>
                            <option value="Amphithéâtre B">Amphithéâtre B</option>
                        </optgroup>
                        <optgroup label="Départements & Laboratoires">
                            <option value="Labo Informatique 1">Labo Informatique 1</option>
                            <option value="Labo Informatique 2">Labo Informatique 2</option>
                            <option value="Labo Génie Électrique">Labo Génie Électrique</option>
                            <option value="Labo Agro-Alimentaire">Labo Agro-Alimentaire & Chimie</option>
                            <option value="Salle Serveurs">Salle Serveurs / Datacenter</option>
                        </optgroup>
                        <optgroup label="Salles d'Enseignement">
                            <option value="Salle 1">Salle 1</option>
                            <option value="Salle 2">Salle 2</option>
                            <option value="Salle 3">Salle 3</option>
                            <option value="Salle 4">Salle 4</option>
                            <option value="Salle 5">Salle 5</option>
                            <option value="Salle 6">Salle 6</option>
                        </optgroup>
                        <optgroup label="Services & Espaces Communs">
                            <option value="Bibliothèque Universitaire">Bibliothèque Universitaire</option>
                            <option value="Bloc Administratif">Bloc Administratif (Direction)</option>
                            <option value="Sanitaires RDC">Sanitaires RDC</option>
                            <option value="Cafétéria">Espace Cafétéria</option>
                        </optgroup>
                    </select>
                </div>

                <!-- ميزة استعجال الحصة والوقت -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Séance de cours prévue ? *</label>
                    <select name="occupancy" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none font-medium">
                        <option value="imminent">🚨 Cours prévu dans moins d'1h (Urgence pédagogique)</option>
                        <option value="today" selected>Cours prévu plus tard aujourd'hui</option>
                        <option value="free">Salle libre / Aucun cours immédiat</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Catégorie *</label>
                    <select name="category" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none">
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="mobilier">Mobilier</option>
                        <option value="autre">Autre équipement</option>
                    </select>
                </div>

                <!-- اختيار الخطورة يدوياً -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Sévérité constatée *</label>
                    <select name="severity" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none font-medium">
                        <option value="faible">Faible (Panne mineure)</option>
                        <option value="moyen" selected>Moyen (Gênant sous 24h)</option>
                        <option value="critique">Critique (Danger / Blocage)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1">Description détaillée du dysfonctionnement *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:border-slate-900 focus:outline-none"
                          placeholder="Décrivez précisément ce que vous observez..."></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <a href="{{ route('signalements.index') }}" class="px-3.5 py-2 border border-slate-200 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition flex items-center space-x-1.5">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Enregistrer le signalement</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection