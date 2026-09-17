<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SignalementService
{
    /**
     * Récupère la liste paginée et filtrée avec optimisation des requêtes (Eager Loading)
     */
    public function getPaginatedForUser(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        // Évite le problème N+1 en préchargeant les relations
        $query = Signalement::with(['user', 'salle', 'interventions.technicien']);

        // Sécurité : Un demandeur ne voit que ses propres déclarations
        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $query->where('user_id', $user->id);
        }

        // Filtres dynamiques par critères
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Enregistre un nouveau signalement rattaché à une salle
     */
    public function createSignalement(User $user, array $data): Signalement
    {
        $salle = Salle::findOrFail($data['salle_id']);

        return Signalement::create([
            'user_id'     => $user->id,
            'salle_id'    => $salle->id,
            'title'       => $data['title'],
            'location'    => $salle->name . ' (' . $salle->building . ')',
            'category'    => $data['category'],
            'severity'    => $data['severity'],
            'description' => $data['description'],
            'status'      => 'signale',
        ]);
    }

    /**
     * Récupère un incident par son ID avec toutes ses dépendances
     */
    public function getById(int $id): Signalement
    {
        return Signalement::with(['user', 'salle', 'interventions.technicien'])->findOrFail($id);
    }
}