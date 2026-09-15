@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('signalements.index') }}" class="text-xs text-blue-600 font-semibold hover:underline flex items-center space-x-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Retour aux signalements</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900">{{ $signalement->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Déclaré le {{ $signalement->created_at?->format('d/m/Y à H:i') ?? 'Récemment' }} 
                par {{ $signalement->user?->name ?? 'Demandeur' }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2.5 py-1 rounded border text-xs font-semibold uppercase {{ $signalement->severity_color }}">
                {{ $signalement->severity }}
            </span>

            <span class="px-3 py-1 rounded-full border text-xs font-semibold {{ $signalement->status_color }}">
                {{ str_replace('_', ' ', $signalement->status) }}
            </span>

            @if(auth()->user()?->hasRole('admin'))
            <form action="{{ route('signalements.destroy', $signalement->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition flex items-center space-x-1 border border-rose-200" title="Supprimer le signalement">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Supprimer</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Détails de l'incident</h2>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Localisation :</span>
                        <span class="font-medium text-slate-800 text-sm mt-0.5 block">{{ $signalement->location }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Catégorie technique :</span>
                        <span class="font-medium text-slate-800 text-sm mt-0.5 block capitalize">{{ $signalement->category }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold text-xs uppercase mb-1">Description :</span>
                    <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 leading-relaxed border border-slate-100">
                        {{ $signalement->description }}
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Historique des interventions techniques</h2>

                @forelse($signalement->interventions as $intervention)
                <div class="border-l-2 border-blue-500 pl-4 py-2 space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800">{{ $intervention->technicien?->name ?? 'Technicien' }}</span>
                        <span class="text-slate-400">
                            {{ $intervention->created_at?->format('d/m/Y à H:i') ?? 'Récemment' }} 
                            ({{ $intervention->duration_minutes }} min)
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        {{ $intervention->notes }}
                    </p>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic py-2">Aucune intervention enregistrée pour le moment.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            @if(auth()->user()?->hasRole(['technicien', 'admin']))
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center space-x-2">
                    <i data-lucide="wrench" class="w-4 h-4 text-amber-600"></i>
                    <span>Ajouter une intervention</span>
                </h3>

                <form method="POST" action="{{ route('interventions.store') }}" class="space-y-3 text-xs">
                    @csrf
                    <input type="hidden" name="signalement_id" value="{{ $signalement->id }}">

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nouveau statut *</label>
                        <select name="status" required class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500">
                            <option value="pris_en_charge" {{ $signalement->status == 'pris_en_charge' ? 'selected' : '' }}>Pris en charge</option>
                            <option value="resolu" {{ $signalement->status == 'resolu' ? 'selected' : '' }}>Résolu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Durée (minutes) *</label>
                        <input type="number" name="duration_minutes" value="30" min="5" max="1440" required
                               class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Rapport d'intervention *</label>
                        <textarea name="notes" rows="4" required
                                  class="w-full border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-blue-500"
                                  placeholder="Détaillez le travail effectué..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                        Enregistrer l'intervention
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-xs space-y-2">
                <span class="font-bold text-slate-800 uppercase tracking-wider block">Informations ticket</span>
                <div class="space-y-1.5 text-slate-600 pt-2 border-t border-slate-100">
                    <div class="flex justify-between">
                        <span class="text-slate-400">ID :</span>
                        <span class="font-mono">#{{ $signalement->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Demandeur :</span>
                        <span>{{ $signalement->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Créé le :</span>
                        <span>{{ $signalement->created_at?->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Dernière mise à jour :</span>
                        <span>{{ $signalement->updated_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection