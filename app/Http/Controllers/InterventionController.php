<?php

namespace App\Http\Controllers;

use App\Services\InterventionService;
use App\Services\SignalementService;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    protected InterventionService $interventionService;
    protected SignalementService $signalementService;

    public function __construct(InterventionService $interventionService, SignalementService $signalementService)
    {
        $this->interventionService = $interventionService;
        $this->signalementService = $signalementService;
    }

    /**
     * Enregistre le compte-rendu technique et met à jour l'état du signalement
     */
    public function store(Request $request)
    {
        // 1. Validation stricte des données de l'intervention
        $validated = $request->validate([
            'signalement_id'   => 'required|exists:signalements,id',
            'notes'            => 'required|string|min:5',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'status'           => 'required|in:pris_en_charge,resolu',
        ]);

        $signalement = $this->signalementService->getById((int) $validated['signalement_id']);

        // 2. Vérification d'autorisation (Seul le Technicien est autorisé)
        $this->authorize('intervene', $signalement);

        // 3. Délégation de la logique métier au Service
        $this->interventionService->recordIntervention(auth()->user(), $signalement, $validated);

        return redirect()->route('signalements.show', $signalement->id)
            ->with('success', 'Intervention enregistrée avec succès ! Statut mis à jour.');
    }
}