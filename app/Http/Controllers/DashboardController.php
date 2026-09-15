<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = auth()->user();
        $stats = $this->dashboardService->getMetrics($user);
        $recentSignalements = $this->dashboardService->getRecentSignalements($user, 6);
        $notifications = $this->dashboardService->getUnreadNotifications($user);

        return view('dashboard.index', compact('stats', 'recentSignalements', 'notifications', 'user'));
    }

    public function markAsRead($id)
    {
        $read = session('read_notifications', []);
        if (!in_array((int) $id, $read)) {
            $read[] = (int) $id;
            session(['read_notifications' => $read]);
        }
        return back();
    }

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