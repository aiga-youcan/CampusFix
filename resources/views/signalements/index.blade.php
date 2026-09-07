@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Registre des Signalements</h1>
            <p class="text-xs text-slate-500 mt-0.5">Filtrage multicritère et supervision des pannes</p>
        </div>
        <a href="{{ route('signalements.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center space-x-2 self-start">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Déclarer une panne</span>
        </a>
    </div>

    <!-- Barre de Filtrage -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('signalements.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Statut</label>
                <select name="status" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="signale" {{ request('status') == 'signale' ? 'selected' : '' }}>Signalé</option>
                    <option value="pris_en_charge" {{ request('status') == 'pris_en_charge' ? 'selected' : '' }}>Pris en charge</option>
                    <option value="resolu" {{ request('status') == 'resolu' ? 'selected' : '' }}>Résolu</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Catégorie</label>
                <select name="category" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    <option value="plomberie" {{ request('category') == 'plomberie' ? 'selected' : '' }}>Plomberie</option>
                    <option value="electricite" {{ request('category') == 'electricite' ? 'selected' : '' }}>Électricité</option>
                    <option value="mobilier" {{ request('category') == 'mobilier' ? 'selected' : '' }}>Mobilier</option>
                    <option value="autre" {{ request('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Sévérité (IA)</label>
                <select name="severity" class="w-full text-xs border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les sévérités</option>
                    <option value="critique" {{ request('severity') == 'critique' ? 'selected' : '' }}>Critique (&ge; 70)</option>
                    <option value="moyen" {{ request('severity') == 'moyen' ? 'selected' : '' }}>Moyen (40-69)</option>
                    <option value="faible" {{ request('severity') == 'faible' ? 'selected' : '' }}>Faible (&lt; 40)</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition flex items-center justify-center space-x-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filtrer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-4"># ID</th>
                        <th class="py-3 px-4">Titre & Lieu</th>
                        <th class="py-3 px-4">Demandeur</th>
                        <th class="py-3 px-4">Catégorie</th>
                        <th class="py-3 px-4">Score IA</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($signalements as $s)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-xs font-mono text-slate-400">#{{ $s->id }}</td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-900">{{ $s->title }}</div>
                            <div class="text-xs text-slate-500">{{ $s->location }}</div>
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-700">
                            {{ $s->user->name }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                {{ ucfirst($s->category) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-xs {{ $s->ai_score >= 70 ? 'text-rose-600' : ($s->ai_score >= 40 ? 'text-amber-600' : 'text-slate-600') }}">
                                {{ $s->ai_score }}/100
                            </span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border uppercase font-semibold {{ $s->severity_color }} ml-1">
                                {{ $s->severity }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-[11px] px-2.5 py-1 rounded-full border font-medium {{ $s->status_color }}">
                                {{ str_replace('_', ' ', $s->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('signalements.show', $s) }}" class="px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition">
                                Gérer
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-sm">
                            Aucun signalement ne correspond à vos filtres.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $signalements->links() }}
        </div>
    </div>
</div>
@endsection
