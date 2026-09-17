<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Calcule les métriques clés (KPIs) en temps réel selon le rôle de l'utilisateur
     */
    public function getMetrics(User $user): array
    {
        // Requête de base cloisonnée selon les droits
        $baseQuery = Signalement::query();
        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $baseQuery->where('user_id', $user->id);
        }

        // Calculs isolés via clonage de la requête de base
        $total          = (clone $baseQuery)->count();
        $enAttente      = (clone $baseQuery)->where('status', 'signale')->count();
        $enCours        = (clone $baseQuery)->where('status', 'pris_en_charge')->count();
        $resolus        = (clone $baseQuery)->where('status', 'resolu')->count();
        $tauxResolution = $total > 0 ? round(($resolus / $total) * 100) . '%' : '0%';

        return [
            'total_signalements' => $total,
            'en_attente'          => $enAttente,
            'en_cours'            => $enCours,
            'resolus'             => $resolus,
            'taux_resolution'     => $tauxResolution,
        ];
    }

    /**
     * Récupère les derniers signalements avec leurs relations préchargées
     */
    public function getRecentSignalements(User $user, int $limit = 6): Collection
    {
        $query = Signalement::with(['user', 'salle']);
        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->take($limit)->get();
    }

    /**
     * Récupère les notifications non lues pour les techniciens et administrateurs
     */
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