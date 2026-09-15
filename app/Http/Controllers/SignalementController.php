<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Services\SignalementService;
use App\Services\SalleService;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    protected SignalementService $signalementService;
    protected SalleService $salleService;

    public function __construct(SignalementService $signalementService, SalleService $salleService)
    {
        $this->signalementService = $signalementService;
        $this->salleService = $salleService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Signalement::class);

        $signalements = $this->signalementService->getPaginatedForUser(
            auth()->user(),
            $request->only(['status', 'category', 'severity']),
            10
        );

        return view('signalements.index', compact('signalements'));
    }

    public function create()
    {
        $this->authorize('create', Signalement::class);

        $sallesByBuilding = $this->salleService->getGroupedByBuilding();
        return view('signalements.create', compact('sallesByBuilding'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Signalement::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'salle_id' => 'required|exists:salles,id',
            'category' => 'required|in:plomberie,electricite,mobilier,reseau,autre',
            'severity' => 'required|in:faible,moyen,critique',
            'description' => 'required|string|min:10',
        ]);

        $signalement = $this->signalementService->createSignalement(auth()->user(), $validated);

        return redirect()->route('signalements.show', $signalement->id)
            ->with('success', 'Incident déclaré avec succès !');
    }

    public function show($id)
    {
        $signalement = $this->signalementService->getById((int) $id);
        $this->authorize('view', $signalement);

        // Marquer automatiquement comme lu pour le technicien/admin
        if (auth()->user()->hasRole(['technicien', 'admin'])) {
            $read = session('read_notifications', []);
            if (!in_array((int) $id, $read)) {
                $read[] = (int) $id;
                session(['read_notifications' => $read]);
            }
        }

        return view('signalements.show', compact('signalement'));
    }
}
