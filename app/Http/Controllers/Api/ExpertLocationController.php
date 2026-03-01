<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ExpertLocationController extends Controller
{
    /**
     * POST /api/experts/location
     * Update enthusiast's current location and availability status.
     */
    public function updateLocation(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'enthusiast') {
            return response()->json(['success' => false, 'message' => 'Only enthusiasts can update location tracking.'], 403);
        }

        $request->validate([
            'lat'          => 'required|numeric',
            'lng'          => 'required|numeric',
            'is_available' => 'required|boolean',
        ]);

        $user->update([
            'last_lat'                 => $request->lat,
            'last_lng'                 => $request->lng,
            'is_available'             => $request->is_available,
            'last_location_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location and status updated successfully.'
        ]);
    }

    /**
     * GET /api/experts/nearby?lat=X&lng=Y
     * Return available enthusiasts sorted by distance to the given coordinates.
     */
    public function getNearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        // Haversine formula to sort by distance (in km)
        $experts = User::where('role', 'enthusiast')
            ->where('is_available', true)
            ->whereNotNull('last_lat')
            ->whereNotNull('last_lng')
            ->select('*')
            ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(last_lat)) * cos(radians(last_lng) - radians(?)) + sin(radians(?)) * sin(radians(last_lat)))) AS distance', [$lat, $lng, $lat])
            ->orderBy('distance', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $experts
        ]);
    }
}
