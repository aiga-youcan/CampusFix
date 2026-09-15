@extends('layouts.app')

@section('title', $signalement->title . ' | CampusFix')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('signalements.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-medium flex items-center space-x-1 mb-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Retour au registre</span>
            </a>
            <h1 class="text-xl font-semibold text-slate-900">{{ $signalement->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Signalé le <span class="font-mono text-slate-700">{{ $signalement->created_at?->format('d/m/Y à H:i') ?? 'Récemment' }}</span>
                par <span class="text-slate-700 font-medium">{{ $signalement->user?->name ?? 'Demandeur' }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-medium bg-white border-slate-200 text-slate-800">
                @if($signalement->status == 'resolu')
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Résolu
                @elseif($signalement->status == 'pris_en_charge')
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> En cours
                @else
                    <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span> Signalé
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Colonne Gauche : Détails et Historique (Sans carte IA) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informations de la panne -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3.5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">Informations de l'incident</h2>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block uppercase font-medium">Salle / Lieu :</span>
                        <span class="font-medium text-slate-900 text-sm mt-0.5 block">📍 {{ $signalement->location }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block uppercase font-medium">Catégorie &amp; Sévérité :</span>
                        <span class="font-medium text-slate-900 text-sm mt-0.5 block capitalize">
                            {{ $signalement->category }} • 
                            <span class="text-xs uppercase px-1.5 py-0.2 rounded border font-medium {{ $signalement->severity == 'critique' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($signalement->severity == 'moyen' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200') }}">
                                {{ $signalement->severity }}
                            </span>
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 block uppercase font-medium text-xs mb-1">Description du problème :</span>
                    <div class="bg-slate-50 p-3.5 rounded-lg text-xs text-slate-700 leading-relaxed border border-slate-200">
                        {{ $signalement->description }}
                    </div>
                </div>
            </div>

            <!-- Historique des interventions techniques -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">Historique des interventions</h2>

                @forelse($signalement->interventions as $intervention)
                <div class="border-l-2 border-slate-900 pl-3.5 py-1 space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-medium text-slate-900">{{ $intervention->technicien?->name ?? 'Technicien' }}</span>
                        <span class="text-slate-400 font-mono">
                            {{ $intervention->created_at?->format('d/m/Y H:i') ?? 'Récemment' }} 
                            ({{ $intervention->duration_minutes }} min)
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                        {{ $intervention->notes }}
                    </p>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic py-2">Aucune intervention enregistrée pour le moment.</p>
                @endforelse
            </div>
        </div>

        <!-- Colonne Droite : Formulaire technicien -->
        <div class="space-y-6">
            @can('intervene', $signalement)
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3.5">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2 flex items-center space-x-1.5">
                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-slate-700"></i>
                    <span>Espace Technicien</span>
                </h3>

                <form method="POST" action="{{ route('interventions.store') }}" class="space-y-3 text-xs">
                    @csrf
                    <input type="hidden" name="signalement_id" value="{{ $signalement->id }}">

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Nouveau Statut *</label>
                        <select name="status" required class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none font-medium">
                            <option value="pris_en_charge" {{ $signalement->status == 'pris_en_charge' ? 'selected' : '' }}>En cours (Pris en charge)</option>
                            <option value="resolu" {{ $signalement->status == 'resolu' ? 'selected' : '' }}>Résolu (Clôturer le ticket)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Durée d'intervention (minutes) *</label>
                        <input type="number" name="duration_minutes" value="30" min="5" max="1440" required
                               class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 uppercase mb-1">Rapport technique &amp; Actions menées *</label>
                        <textarea name="notes" rows="4" required
                                  class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:border-slate-900 focus:outline-none"
                                  placeholder="Décrivez les réparations effectuées, pièces changées..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg transition-colors">
                        Enregistrer l'intervention
                    </button>
                </form>
            </div>
            @endcan

            <div class="bg-white p-4 rounded-xl border border-slate-200 text-xs space-y-2">
                <span class="font-medium text-slate-800 uppercase tracking-wider block text-[11px]">Traçabilité</span>
                <p class="text-slate-500 leading-relaxed">
                    Toutes les actions techniques sont journalisées avec intégrité relationnelle dans la base de données.
                </p>
                <div class="pt-2 border-t border-slate-100 text-slate-400 font-mono text-[11px]">
                    <div>ID Ticket : #{{ $signalement->id }}</div>
                    <div>Horodatage : {{ $signalement->created_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
