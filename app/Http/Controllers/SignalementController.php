<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Services\AiTriageService;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    protected AiTriageService $aiTriage;

    public function __construct(AiTriageService $aiTriage)
    {
        $this->aiTriage = $aiTriage;
    }

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
            'description' => 'required|string|min:10',
        ]);

        $aiResult = $this->aiTriage->analyze(
            $validated['title'],
            $validated['description'],
            $validated['location'],
            $validated['category']
        );

        $signalement = Signalement::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'severity' => $aiResult['severity'],
            'status' => 'signale',
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        return redirect()->to('/signalements/' . $signalement->id)
            ->with('success', "Signalement créé avec succès ! L'IA a évalué un score de {$aiResult['score']}/100.");
    }

    public function show($id)
    {
        $signalement = Signalement::with(['user', 'interventions.technicien'])->findOrFail($id);

        $user = auth()->user();
        if ($user && $user->hasRole('demandeur') && $signalement->user_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce signalement.');
        }

        return view('signalements.show', compact('signalement'));
    }

    public function retriage($id)
    {
        $signalement = Signalement::findOrFail($id);

        $aiResult = $this->aiTriage->analyze(
            $signalement->title,
            $signalement->description,
            $signalement->location,
            $signalement->category
        );

        $signalement->update([
            'severity' => $aiResult['severity'],
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        return back()->with('info', "Recalcul du triage IA terminé : nouveau score de {$aiResult['score']}/100.");
    }
}