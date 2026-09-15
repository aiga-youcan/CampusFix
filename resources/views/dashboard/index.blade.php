@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-slate-900">Bonjour, {{ $user->name }} 👋</h1>
            <p class="text-xs text-slate-500 mt-0.5">Espace {{ $user->role_name }} — Suivi des signalements et interventions</p>
        </div>
        <a href="{{ route('signalements.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Nouveau Signalement</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Signalements</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ $stats['total_signalements'] }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Tous bâtiments</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">En Attente</span>
                <span class="text-2xl font-bold text-rose-600 mt-1 block">{{ $stats['en_attente'] }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">À traiter d'urgence</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">En Cours</span>
                <span class="text-2xl font-bold text-amber-600 mt-1 block">{{ $stats['en_cours'] }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Interventions actives</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Résolus</span>
                <span class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $stats['resolus'] }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Pannes clôturées</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    @if($user->hasRole(['technicien', 'admin']) && $notifications->count() > 0)
    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                <i data-lucide="bell" class="w-4 h-4 text-blue-600"></i>
                <span>Centre d'Alertes — Pannes récentes à traiter</span>
            </h2>
            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                {{ $notifications->count() }} en attente
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($notifications as $notif)
            <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider {{ $notif->severity == 'critique' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $notif->severity }}
                        </span>
                        <span class="text-[10px] text-slate-400">
                            {{ $notif->created_at?->diffForHumans() ?? 'Récemment' }}
                        </span>
                    </div>
                    <div class="font-bold text-xs text-slate-900 truncate">{{ $notif->title }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">📍 {{ $notif->location }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Demandeur : {{ $notif->user?->name ?? 'Demandeur' }}</div>
                </div>

                <div class="mt-3 pt-2 border-t border-slate-100 text-right">
                    <a href="{{ route('signalements.show', $notif->id) }}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                        Prendre en charge &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-1.5">
                <i data-lucide="activity" class="w-4 h-4 text-blue-600"></i>
                <span>Historique Récent</span>
            </h2>
            <a href="{{ route('signalements.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">
                Voir tout le registre &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase border-b border-slate-100">
                        <th class="py-3 px-4">Titre & Lieu</th>
                        <th class="py-3 px-4">Demandeur</th>
                        <th class="py-3 px-4">Sévérité</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSignalements as $s)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-900">{{ $s->title }}</div>
                            <div class="text-[11px] text-slate-400">{{ $s->location }} • {{ ucfirst($s->category) }}</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $s->user?->name ?? 'Demandeur' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded border uppercase text-[10px] font-semibold {{ $s->severity_color }}">
                                {{ $s->severity }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full border text-[10px] font-medium {{ $s->status_color }}">
                                {{ str_replace('_', ' ', $s->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('signalements.show', $s->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition">
                                Gérer
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Aucun signalement enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection