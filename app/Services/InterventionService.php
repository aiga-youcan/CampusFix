<?php

namespace App\Services;

use App\Models\Intervention;
use App\Models\Signalement;
use App\Models\User;

class InterventionService
{
    public function recordIntervention(User $technicien, Signalement $signalement, array $data): Intervention
    {
        $intervention = Intervention::create([
            'signalement_id' => $signalement->id,
            'technicien_id' => $technicien->id,
            'notes' => $data['notes'],
            'duration_minutes' => (int) ($data['duration_minutes'] ?? 30),
            'status' => 'termine',
        ]);

        $newStatus = $data['status'] ?? 'resolu';
        $signalement->update(['status' => $newStatus]);

        return $intervention;
    }

    public function getInterventionsForSignalement(int $signalementId)
    {
        return Intervention::with('technicien')
            ->where('signalement_id', $signalementId)
            ->latest()
            ->get();
    }
}