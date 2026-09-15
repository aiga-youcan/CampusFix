<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\Intervention;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'signalement_id' => 'required|exists:signalements,id',
            'notes' => 'required|string|min:5',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'status' => 'required|in:pris_en_charge,resolu',
        ]);

        $signalement = $this->signalementService->getById((int) $validated['signalement_id']);

        // Vérification d'autorisation Policy
        $this->authorize('intervene', $signalement);

        $this->interventionService->recordIntervention(auth()->user(), $signalement, $validated);

        return redirect()->route('signalements.show', $signalement->id)
            ->with('success', 'Intervention enregistrée avec succès ! Statut mis à jour.');
    }
}
