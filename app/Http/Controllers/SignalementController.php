<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Signalement::with(['user', 'interventions.technicien']);

        if ($user && $user->hasRole('demandeur')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $signalements = $query->latest()->paginate(10)->withQueryString();

        return view('signalements.index', compact('signalements'));
    }

    public function create()
    {
        return view('signalements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|in:plomberie,electricite,mobilier,autre',
            'severity' => 'required|in:faible,moyen,critique',
            'description' => 'required|string|min:10',
        ]);

        $occupancy = $request->input('occupancy', 'today');

        // 🤖 Istikhdam l-AI mn config/ai.php mni ḥwldnah
        $aiEvaluator = config('ai.evaluate');
        $aiResult = $aiEvaluator(
            $validated['title'],
            $validated['description'],
            $validated['location'],
            $validated['category'],
            $occupancy
        );

        $signalement = Signalement::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'status' => 'signale',
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        return redirect()->route('signalements.index');
    }

    public function show($id)
    {
        $signalement = Signalement::with(['user', 'interventions.technicien'])->findOrFail($id);

        $user = auth()->user();
        if ($user && $user->hasRole('demandeur') && $signalement->user_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce signalement.');
        }

        if ($user && $user->hasRole(['technicien', 'admin'])) {
            $read = session('read_notifications', []);
            if (!in_array((int) $id, $read)) {
                $read[] = (int) $id;
                session(['read_notifications' => $read]);
            }
        }

        return view('signalements.show', compact('signalement'));
    }

    public function retriage($id)
    {
        $signalement = Signalement::findOrFail($id);

        // 🤖 Réévaluation mn config/ai.php
        $aiEvaluator = config('ai.evaluate');
        $aiResult = $aiEvaluator(
            $signalement->title,
            $signalement->description,
            $signalement->location,
            $signalement->category,
            'today'
        );

        $signalement->update([
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        return back()->with('info', "Diagnostic IA réévalué avec succès (Nouveau score : {$aiResult['score']}/100).");
    }

    // 🗑️ Méthode dyal Suppression khassa b l-Admin
    public function destroy($id)
    {
        $signalement = Signalement::findOrFail($id);

        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Action non autorisée.');
        }

        // Mḥa l-interventions li m-lssiqin biha lowl bach t-fadi database foreign key error
        $signalement->interventions()->delete();

        // Mḥa l-signalement
        $signalement->delete();

        return redirect()->route('signalements.index')->with('success', 'Signalement supprimé avec succès.');
    }
}