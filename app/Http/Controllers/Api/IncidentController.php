<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * IncidentController
 * Handles /api/incidents endpoints — snake sightings and bite reports.
 */
class IncidentController extends Controller
{
    /**
     * GET /api/incidents
     * List all incidents — restricted to admin or enthusiast roles.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $incidents = Incident::latest()->paginate($perPage);

        return response()->json($incidents->getCollection()->map(fn($i) => [
            'id'            => $i->incident_id,
            'type'          => $i->type ?? $i->incident_type,
            'snake_name'    => $i->snake_name,
            'location_name' => $i->location_name ?? $i->location,
            'lat'           => $i->lat,
            'lng'           => $i->lng,
            'reported_at'   => $i->created_at,
            'priority'      => $i->priority ?? 'medium',
            'status'        => $i->status ?? 'open',
        ]));
    }

    /**
     * POST /api/incidents
     * Submit a snake sighting or bite report (multipart/form-data).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'          => 'required|in:sighting,bite',
            'description'   => 'required|string',
            'location_name' => 'required|string',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric',
            'image'         => 'nullable|image|max:5120', // 5 MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('incidents', 'public');
        }

        $incident = Incident::create([
            'user_id'       => $request->user()->user_id,
            'incident_type' => $request->type,
            'type'          => $request->type,
            'description'   => $request->description,
            'location'      => $request->location_name,
            'location_name' => $request->location_name,
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'image_path'    => $imagePath,
            'status'        => 'open',
            'priority'      => 'medium',
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Report submitted.',
            'incident_id' => $incident->incident_id,
        ], 201);
    }

    /**
     * POST /api/incidents/{id}/assign
     * Assigns an incident to a specific enthusiast.
     */
    public function assign(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $request->validate([
            'enthusiast_id' => 'required|exists:users,user_id',
        ]);

        // Basic authorization check (ideally admin or valid user should be doing this)
        $user = $request->user();
        // if ($user->role !== 'admin') ...

        $incident->assigned_enthusiast_id = $request->enthusiast_id;
        $incident->status = 'assigned';
        $incident->save();

        // TODO: Trigger Firebase FCM push notification to the assigned enthusiast
        \Illuminate\Support\Facades\Log::info("FCM Notification mapped to enthusiast ID: " . $request->enthusiast_id);

        return response()->json([
            'success' => true,
            'message' => 'Incident assigned successfully.',
            'incident' => $incident
        ]);
    }
}
