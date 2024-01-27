<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPanel;
use App\Models\Panel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Services\NotificationService;

class DashboardController extends Controller
{
    public function index()
    {
        $notificationService = new NotificationService();

        $totalUserPanels = UserPanel::count();

        $panelCounts = UserPanel::select(
            'panels.name',
            'panels.domain',
            DB::raw('count(user_panels.id) as panel_count'),
            DB::raw('ROUND(count(user_panels.id) / ' . $totalUserPanels . ' * 100, 0) as panel_percentage')
        )
            ->join('panels', 'panels.id', '=', 'user_panels.panel_id')
            ->orderBy('panel_percentage', 'desc')
            ->groupBy('panels.id')
            ->limit(6)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'meta' => [
                'total_users' => [
                    'total' => User::where('role_id', 'user')->count(),
                    'new' => User::where('role_id', 'user')->whereDate('created_at', '>=', Carbon::now()->subDay())->count()
                ],
                'total_panels' => [
                    'total' => Panel::count(),
                    'active' => Panel::where('status', 'active')->count(),
                    'inactive' => Panel::where('status', '!=', 'active')->count(),
                ],
                'total_user_panels' => [
                    'total' => UserPanel::count(),
                    'new' => UserPanel::whereDate('created_at', '>=', Carbon::now()->subWeek())->count(),
                    'active' => UserPanel::where('status', 'active')->count(),
                    'inactive' => UserPanel::where('status', '!=', 'active')->count()
                ],
                'total_user_panels_expired' => [
                    'total' => UserPanel::where('status', 'expired')->count(),
                    'new' => UserPanel::where('status', 'expired')->whereDate('expired_at', '>=', Carbon::now()->subWeek())->count(),
                ],
            ],
            'top_panels' => $panelCounts->toArray(),
            'notifications' => $notificationService->today(),
            'recent_users' => User::where(['role_id' => 'user'])->orderBy('created_at', 'desc')->limit(10)->get()->toArray(), // User::where(['role_id' => 'user', 'expired_at' => null])->orderBy('created_at', 'desc')->limit(10)->get()->toArray()
        ]);
    }
}
