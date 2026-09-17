<?php

namespace App\Services;

use App\Models\Intervention;
use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InterventionService
{
    /**
     * Enregistre l'intervention technique et met à jour le statut du ticket
     * Opération atomique sécurisée par une transaction de base de données (ACID).
     */
    public function recordIntervention(User $technicien, Signalement $signalement, array $data): Intervention
    {
        return DB::transaction(function () use ($technicien, $signalement, $data) {
            // 1. Création du compte-rendu technique
            $intervention = Intervention::create([
                'signalement_id'   => $signalement->id,
                'technicien_id'    => $technicien->id,
                'notes'            => $data['notes'],
                'duration_minutes' => (int) ($data['duration_minutes'] ?? 30),
                'status'           => 'termine',
            ]);

            // 2. Mise à jour immédiate de l'état du signalement parent
            $newStatus = $data['status'] ?? 'resolu';
            $signalement->update(['status' => $newStatus]);

            return $intervention;
        });
    }
}