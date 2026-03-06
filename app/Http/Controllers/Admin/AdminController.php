<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Snake;
use App\Models\Incident;
use App\Models\Request as AssistanceRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_users'      => User::count(),
            'total_snakes'     => Snake::count(),
            'total_incidents'  => Incident::count(),
            'total_experts'    => User::where('role', 'enthusiast')->count(),
            'pending_requests' => AssistanceRequest::where('status', 'pending')->count(),
        ];
        return view('layouts.dashboard', $data);
    }

    public function viewIncidents()
    {
        $incidents = Incident::with(['user', 'assignedEnthusiast'])->latest()->paginate(15);
        return view('admin.incidents.index', compact('incidents'));
    }

    public function viewRequests()
    {
        $requests = AssistanceRequest::with('user')->latest()->paginate(10);
        return view('admin.requests.index', compact('requests'));
    }

    public function enthusiastMap()
    {
        // All enthusiasts for the map (JS skips null-coord ones)
        $enthusiasts = User::where('role', 'enthusiast')->get();

        // Active incidents with coordinates (for map markers)
        $incidents = Incident::with(['assignedEnthusiast', 'user'])
            ->whereIn('status', ['open', 'pending', 'assigned', 'in_progress'])
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        // Enthusiasts active in the last 24 h (last GPS ping received)
        $enthusiastsRecent = User::where('role', 'enthusiast')
            ->where(function ($q) {
                $q->where('last_location_updated_at', '>=', now()->subHours(24))
                  ->orWhere('updated_at', '>=', now()->subHours(24));
            })
            ->orderByDesc('last_location_updated_at')
            ->get();

        // Incidents reported in the last 24 h
        $incidentsRecent = Incident::with(['user', 'assignedEnthusiast'])
            ->where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->get();

        return view('admin.enthusiasts.map', compact(
            'enthusiasts', 'incidents',
            'enthusiastsRecent', 'incidentsRecent'
        ));
    }

    public function incidentDispatch()
    {
        $incidents = Incident::with(['user', 'assignedEnthusiast'])
            ->whereIn('status', ['open', 'pending'])
            ->latest()
            ->paginate(15);

        // All enthusiasts for assignment (not just available ones)
        $experts = User::where('role', 'enthusiast')->orderBy('fname')->get();

        return view('admin.incidents.dispatch', compact('incidents', 'experts'));
    }

    /**
     * GET /admin/incidents/{id}
     * Show full incident detail page.
     */
    public function showIncident($id)
    {
        $incident = Incident::with(['user', 'assignedEnthusiast'])->findOrFail($id);

        // All enthusiasts for the reassign dropdown
        $enthusiasts = User::where('role', 'enthusiast')->orderBy('fname')->get();

        return view('admin.incidents.show', compact('incident', 'enthusiasts'));
    }

    /**
     * POST /admin/incidents/{id}/assign
     * Web-based enthusiast (re)assignment from admin panel.
     */
    public function assignEnthusiast(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $request->validate([
            'enthusiast_id' => 'required|exists:users,user_id',
        ]);

        $incident->assigned_enthusiast_id = $request->enthusiast_id;
        $incident->status = 'assigned';
        $incident->save();

        return redirect()->route('admin.incidents.show', $id)
            ->with('success', 'Enthusiast assigned successfully.');
    }

    public function catchReports()
    {
        $reports = \App\Models\CatchReport::with(['incident', 'enthusiast', 'user'])
            ->latest()
            ->paginate(15);
        return view('admin.catch_reports.index', compact('reports'));
    }
}