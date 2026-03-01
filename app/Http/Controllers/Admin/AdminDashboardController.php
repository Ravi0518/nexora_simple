<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Incident;
use App\Models\Request as AssistanceRequest;
use App\Models\Snake;

/**
 * Professional English Documentation
 * Class: AdminDashboardController
 * Purpose: Provides high-level analytics and management capabilities for the Nexora Administrator.
 * Implementation: Aggregates data from across the system to monitor safety trends and user activity.
 */
class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetching stats for the Dashboard tiles
        $data = [
            'total_users' => User::count(),
            'total_snakes' => Snake::count(),
            'pending_requests' => AssistanceRequest::where('status', 'pending')->count(),
            'recent_incidents' => Incident::with('user')->latest()->take(5)->get(),
            'new_enthusiasts' => User::where('role', 'snake_enthusiast')
                                     ->where('activation_status', 0)->get(),
        ];

        return view('admin.dashboard', $data);
    }

    // Professional English: Method to verify and activate Snake Enthusiasts
    public function verifyEnthusiast($id)
    {
        $user = User::findOrFail($id);
        $user->activation_status = 1;
        $user->save();

        return back()->with('success', 'Enthusiast verified and activated successfully.');
    }
}