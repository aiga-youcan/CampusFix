<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Affiche le tableau de bord principal avec métriques selon le rôle
     */
    public function index()
    {
        $user = auth()->user();
        $stats = $this->dashboardService->getMetrics($user);
        $recentSignalements = $this->dashboardService->getRecentSignalements($user, 6);
        $notifications = $this->dashboardService->getUnreadNotifications($user);

        return view('dashboard.index', compact('stats', 'recentSignalements', 'notifications', 'user'));
    }

    /**
     * Marque un signalement spécifique comme lu dans la session
     */
    public function markAsRead($id)
    {
        $read = session('read_notifications', []);
        if (!in_array((int) $id, $read)) {
            $read[] = (int) $id;
            session(['read_notifications' => $read]);
        }
        return back();
    }

    /**
     * Marque toutes les notifications non lues comme lues
     */
    public function markAllAsRead()
    {
        $user = auth()->user();
        $unread = $this->dashboardService->getUnreadNotifications($user);
        $read = session('read_notifications', []);
        foreach ($unread as $item) {
            if (!in_array($item->id, $read)) {
                $read[] = $item->id;
            }
        }
        session(['read_notifications' => $read]);
        return back();
    }
}