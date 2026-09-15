<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SignalementService
{
    public function getPaginatedForUser(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Signalement::with(['user', 'salle', 'interventions.technicien']);

        if ($user->hasRole('demandeur') && !$user->hasRole(['admin', 'technicien'])) {
            $query->where('user_id', $user->id);
        }

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

    public function createSignalement(User $user, array $data): Signalement
    {
        $salle = Salle::findOrFail($data['salle_id']);

        return Signalement::create([
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'title' => $data['title'],
            'location' => $salle->name . ' (' . $salle->building . ')',
            'category' => $data['category'],
            'severity' => $data['severity'],
            'description' => $data['description'],
            'status' => 'signale',
        ]);
    }

    public function getById(int $id): Signalement
    {
        return Signalement::with(['user', 'salle', 'interventions.technicien'])->findOrFail($id);
    }

    public function updateStatus(Signalement $signalement, string $newStatus): Signalement
    {
        $signalement->update(['status' => $newStatus]);
        return $signalement;
    }
}