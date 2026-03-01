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

    public function enthusiastMap()
    {
        $enthusiasts = User::where('role', 'enthusiast')
            ->where('is_available', true)
            ->whereNotNull('last_lat')
            ->whereNotNull('last_lng')
            ->get();
        return view('admin.enthusiasts.map', compact('enthusiasts'));
    }

    public function incidentDispatch()
    {
        $incidents = Incident::with('user')
            ->whereIn('status', ['open', 'pending'])
            ->latest()
            ->paginate(15);
            
        $experts = User::where('role', 'enthusiast')
            ->where('is_available', true)
            ->whereNotNull('last_lat')
            ->whereNotNull('last_lng')
            ->get();    
            
        return view('admin.incidents.dispatch', compact('incidents', 'experts'));
    }

    public function catchReports()
    {
        $reports = \App\Models\CatchReport::with(['incident', 'enthusiast', 'user'])
            ->latest()
            ->paginate(15);
        return view('admin.catch_reports.index', compact('reports'));
    }
}