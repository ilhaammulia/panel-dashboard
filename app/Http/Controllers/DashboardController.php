<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPanel;
use App\Models\Panel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Services\NotificationService;

class DashboardController extends Controller
{
    public function index()
    {
        $notificationService = new NotificationService();

        return Inertia::render('Admin/Dashboard', [
            'meta' => [
                'total_users' => User::where('role_id', 'user')->count(),
                'total_panels' => [
                    'total' => Panel::count(),
                    'active' => Panel::where('status', 'active')->count(),
                    'inactive' => Panel::where('status', '!=', 'active')->count(),
                ],
                'total_user_panels' => [
                    'total' => UserPanel::count(),
                    'active' => UserPanel::where('status', 'active')->count(),
                    'inactive' => UserPanel::where('status', '!=', 'active')->count()
                ]
            ],
            'notifications' => $notificationService->today(),
            'recent_users' => User::where(['role_id' => 'user'])->orderBy('created_at', 'desc')->limit(10)->get()->toArray(), // User::where(['role_id' => 'user', 'expired_at' => null])->orderBy('created_at', 'desc')->limit(10)->get()->toArray()
        ]);
    }
}