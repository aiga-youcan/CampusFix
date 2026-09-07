<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\Intervention;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Métriques globales
        $stats = [
            'total_signalements' => Signalement::count(),
            'en_attente' => Signalement::where('status', 'signale')->count(),
            'en_cours' => Signalement::where('status', 'pris_en_charge')->count(),
            'resolus' => Signalement::where('status', 'resolu')->count(),
            'critiques' => Signalement::where('severity', 'critique')->count(),
            'interventions_count' => Intervention::count(),
            'avg_ai_score' => round(Signalement::avg('ai_score') ?? 0, 1),
        ];

        // 2. Boîte de notifications des nouvelles pannes (pour Admin et Technicien)
        $notifications = Signalement::where('status', 'signale')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // 3. Signalements récents
        if ($user->hasRole('demandeur')) {
            $recentSignalements = Signalement::where('user_id', $user->id)
                ->with('interventions.technicien')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $recentSignalements = Signalement::with(['user', 'interventions.technicien'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact('stats', 'notifications', 'recentSignalements', 'user'));
    }
}