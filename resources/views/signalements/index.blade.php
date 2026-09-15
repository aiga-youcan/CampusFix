@extends('layouts.app')

@section('title', 'Registre des Signalements | CampusFix')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Registre des Signalements</h1>
            <p class="text-xs text-slate-500 mt-0.5">Supervision et filtrage des pannes déclarées à l'EST Fquih Ben Salah.</p>
        </div>
        <a href="{{ route('signalements.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition flex items-center space-x-1.5 self-start">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Nouveau signalement</span>
        </a>
    </div>

    <!-- Barre de Filtrage -->
    <div class="bg-white p-3.5 rounded-xl border border-slate-200">
        <form method="GET" action="{{ route('signalements.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-1">Statut</label>
                <select name="status" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:border-slate-900 focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="signale" {{ request('status') == 'signale' ? 'selected' : '' }}>Signalé</option>
                    <option value="pris_en_charge" {{ request('status') == 'pris_en_charge' ? 'selected' : '' }}>Pris en charge</option>
                    <option value="resolu" {{ request('status') == 'resolu' ? 'selected' : '' }}>Résolu</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-1">Catégorie</label>
                <select name="category" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:border-slate-900 focus:outline-none">
                    <option value="">Toutes les catégories</option>
                    <option value="plomberie" {{ request('category') == 'plomberie' ? 'selected' : '' }}>Plomberie</option>
                    <option value="electricite" {{ request('category') == 'electricite' ? 'selected' : '' }}>Électricité</option>
                    <option value="mobilier" {{ request('category') == 'mobilier' ? 'selected' : '' }}>Mobilier</option>
                    <option value="autre" {{ request('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-1">Sévérité</label>
                <select name="severity" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:border-slate-900 focus:outline-none">
                    <option value="">Toutes les sévérités</option>
                    <option value="critique" {{ request('severity') == 'critique' ? 'selected' : '' }}>Critique</option>
                    <option value="moyen" {{ request('severity') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                    <option value="faible" {{ request('severity') == 'faible' ? 'selected' : '' }}>Faible</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-medium transition flex items-center justify-center space-x-1 border border-slate-200">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filtrer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider font-medium">
                        <th class="py-2.5 px-4 font-normal"># ID</th>
                        <th class="py-2.5 px-4 font-normal">Titre &amp; Lieu</th>
                        <th class="py-2.5 px-4 font-normal">Auteur</th>
                        <th class="py-2.5 px-4 font-normal">Catégorie</th>
                        <th class="py-2.5 px-4 font-normal">Sévérité</th>
                        <th class="py-2.5 px-4 font-normal">Statut</th>
                        <th class="py-2.5 px-4 font-normal text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($signalements as $s)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">#{{ $s->id }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-900">{{ $s->title }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $s->location }}</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $s->user?->name ?? 'Demandeur' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-slate-600 capitalize">
                                {{ $s->category }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border
                                {{ $s->severity == 'critique' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($s->severity == 'moyen' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200') }}">
                                {{ ucfirst($s->severity) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center text-[11px] text-slate-700 font-medium">
                                @if($s->status == 'resolu')
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Résolu
                                @elseif($s->status == 'pris_en_charge')
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> En cours
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Signalé
                                @endif
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ url('/signalements/' . $s->id) }}" class="text-xs font-medium text-slate-900 hover:underline">
                                Ouvrir &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">Aucun signalement ne correspond à vos filtres.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3.5 border-t border-slate-100">
            {{ $signalements->links() }}
        </div>
    </div>
</div>
@endsection
