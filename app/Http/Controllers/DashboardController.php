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

        $stats = [
            'total_signalements' => Signalement::count(),
            'en_attente' => Signalement::where('status', 'signale')->count(),
            'en_cours' => Signalement::where('status', 'pris_en_charge')->count(),
            'resolus' => Signalement::where('status', 'resolu')->count(),
            'critiques' => Signalement::where('severity', 'critique')->count(),
            'interventions_count' => Intervention::count(),
            'avg_ai_score' => round(Signalement::avg('ai_score') ?? 0, 1),
        ];

        $readIds = session('read_notifications', []);

        $notifications = Signalement::where('status', 'signale')
            ->whereNotIn('id', $readIds)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        if ($user && $user->hasRole('demandeur')) {
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

    public function markAsRead($id)
    {
        $read = session('read_notifications', []);
        if (!in_array((int) $id, $read)) {
            $read[] = (int) $id;
            session(['read_notifications' => $read]);
        }
        return back()->with('info', 'Alerte marquée comme lue.');
    }

    public function markAllAsRead()
    {
        $allIds = Signalement::where('status', 'signale')->pluck('id')->toArray();
        session(['read_notifications' => $allIds]);
        return back()->with('info', 'Toutes les alertes ont été marquées comme lues.');
    }
}