<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Snake;
use App\Models\Incident;
use App\Models\Request as AssistanceRequest; // aliased to avoid conflict with HTTP Request

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_snakes' => Snake::count(),
            'total_incidents' => Incident::count(),
            'total_experts' => User::where('role', 'snake_enthusiast')->count(),
            // optional additional KPI
            'pending_requests' => AssistanceRequest::where('status', 'pending')->count(),
        ];

        // Matches resources/views/layouts/dashboard.blade.php
        return view('layouts.dashboard', $data);
    }

    public function viewIncidents()
    {
        $incidents = Incident::with('user')->latest()->paginate(15);
        return view('admin.incidents.index', compact('incidents'));
    }

    public function viewRequests()
    {
        $requests = AssistanceRequest::with('user')->latest()->paginate(10);
        return view('admin.requests.index', compact('requests'));
    }
}