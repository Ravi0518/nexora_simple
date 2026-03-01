<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpertIncidentController extends Controller
{
    /**
     * GET /api/experts/requests
     * Fetch incidents assigned to the logged-in enthusiast.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'enthusiast') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $incidents = \App\Models\Incident::where('assigned_enthusiast_id', $user->user_id)
            ->whereIn('status', ['pending', 'assigned'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $incidents
        ]);
    }

    /**
     * POST /api/experts/catch-report
     * Submit a catch report for an incident.
     */
    public function storeCatchReport(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'enthusiast') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'incident_id'        => 'required|exists:incidents,incident_id',
            'caught_lat'         => 'nullable|numeric',
            'caught_lng'         => 'nullable|numeric',
            'snake_image'        => 'nullable|image|max:5120',
            'species_identified' => 'required|string',
            'snake_condition'    => 'required|string',
            'enthusiast_comments'=> 'nullable|string',
        ]);

        $incident = \App\Models\Incident::where('incident_id', $request->incident_id)
            ->where('assigned_enthusiast_id', $user->user_id)
            ->firstOrFail();

        $imagePath = null;
        if ($request->hasFile('snake_image')) {
            $imagePath = $request->file('snake_image')->store('catch_reports', 'public');
        }

        $report = \App\Models\CatchReport::create([
            'incident_id'        => $incident->incident_id,
            'enthusiast_id'      => $user->user_id,
            'user_id'            => $incident->user_id, // the original reporter
            'caught_lat'         => $request->caught_lat,
            'caught_lng'         => $request->caught_lng,
            'snake_image_path'   => $imagePath,
            'species_identified' => $request->species_identified,
            'snake_condition'    => $request->snake_condition,
            'enthusiast_comments'=> $request->enthusiast_comments,
        ]);

        // Mark incident as resolved
        $incident->status = 'resolved';
        $incident->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Catch report submitted successfully.',
            'catch_report' => $report
        ], 201);
    }
}
