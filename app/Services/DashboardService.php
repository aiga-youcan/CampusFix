<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getMetrics(User $user): array
    {
        $baseQuery = Signalement::query();
        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $baseQuery->where('user_id', $user->id);
        }

        $total = (clone $baseQuery)->count();
        $enAttente = (clone $baseQuery)->where('status', 'signale')->count();
        $enCours = (clone $baseQuery)->where('status', 'pris_en_charge')->count();
        $resolus = (clone $baseQuery)->where('status', 'resolu')->count();
        $tauxResolution = $total > 0 ? round(($resolus / $total) * 100) . '%' : '0%';

        return [
            'total_signalements' => $total,
            'en_attente' => $enAttente,
            'en_cours' => $enCours,
            'resolus' => $resolus,
            'taux_resolution' => $tauxResolution,
            'total' => $total,
            'urgents' => (clone $baseQuery)->where('severity', 'critique')->where('status', '!=', 'resolu')->count(),
            'interventions' => Intervention::count(),
        ];
    }

    public function getRecentSignalements(User $user, int $limit = 5): Collection
    {
        $query = Signalement::with(['user', 'salle']);
        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->take($limit)->get();
    }

    public function getUnreadNotifications(User $user): Collection
    {
        if (!$user->hasRole(['admin', 'technicien'])) {
            return collect();
        }

        $readIds = session('read_notifications', []);
        return Signalement::whereNotIn('id', $readIds)
            ->where('status', 'signale')
            ->latest()
            ->take(5)
            ->get();
    }
}