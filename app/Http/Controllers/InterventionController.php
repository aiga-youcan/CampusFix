<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Signalement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Seuls techniciens et admins peuvent intervenir
        if (!$user->hasRole(['technicien', 'admin'])) {
            abort(403, 'Action réservée aux techniciens et administrateurs.');
        }

        $validated = $request->validate([
            'signalement_id' => 'required|exists:signalements,id',
            'notes' => 'required|string|min:5',
            'duration_minutes' => 'required|integer|min:5|max:1440',
            'status' => 'required|in:pris_en_charge,resolu',
        ]);

        $signalement = Signalement::findOrFail($validated['signalement_id']);

        // Créer le log d'intervention
        Intervention::create([
            'signalement_id' => $signalement->id,
            'technicien_id' => $user->id,
            'notes' => $validated['notes'],
            'duration_minutes' => $validated['duration_minutes'],
        ]);

        // Mettre à jour le statut du signalement
        $signalement->update(['status' => $validated['status']]);

        return redirect()->route('signalements.show', $signalement)
            ->with('success', 'Rapport d\'intervention enregistré et statut mis à jour.');
    }
}
