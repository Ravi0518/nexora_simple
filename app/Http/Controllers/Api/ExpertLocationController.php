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
            'is_available' => 'nullable|boolean',  // optional — preserve existing if not sent
        ]);

        $updates = [
            'last_lat'                 => $request->lat,
            'last_lng'                 => $request->lng,
            'last_location_updated_at' => now(),
        ];

        // Only update availability if explicitly provided
        if ($request->has('is_available')) {
            $updates['is_available'] = $request->is_available;
        }

        $user->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully.'
        ]);
    }

    /**
     * POST /api/experts/status
     * Update enthusiast's availability status.
     */
    public function updateStatus(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'enthusiast') {
            return response()->json(['success' => false, 'message' => 'Only enthusiasts can update status.'], 403);
        }

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $user->update([
            'is_available' => $request->is_available,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }

    /**
     * GET /api/experts/nearby?lat=X&lng=Y
     * Return available enthusiasts sorted by distance to the given coordinates.
     */
    public function getNearby(Request $request)
    {
        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $lat = $request->lat ?? 8.3444; // Fallback lat (central Sri Lanka)
        $lng = $request->lng ?? 80.5024; // Fallback lng

        // Return ALL enthusiasts. Calculate distance (km) when GPS data is present,
        // otherwise use a large sentinel value so they appear at the bottom.
        $experts = User::where('role', 'enthusiast')
            ->select('user_id', 'fname', 'lname', 'phone', 'affiliation',
                     'experience_years', 'profile_pic', 'is_available',
                     'last_lat', 'last_lng')
            ->selectRaw("
                CASE
                  WHEN last_lat IS NOT NULL AND last_lng IS NOT NULL
                  THEN (6371 * acos(
                      cos(radians(?)) * cos(radians(last_lat))
                      * cos(radians(last_lng) - radians(?))
                      + sin(radians(?)) * sin(radians(last_lat))
                  ))
                  ELSE NULL
                END AS distance
            ", [$lat, $lng, $lat])
            ->orderByRaw('distance IS NULL ASC')  // nulls (no GPS) go last
            ->orderBy('distance', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $experts
        ]);
    }

    /**
     * GET /api/enthusiasts
     * Public discovery of all verified enthusiasts.
     */
    public function getAllEnthusiasts(Request $request)
    {
        $experts = User::where('role', 'enthusiast')->get();

        return response()->json([
            'success' => true,
            'data' => $experts->map(function ($u) {
                return [
                    'id'                       => $u->user_id,
                    'fname'                    => $u->fname,
                    'lname'                    => $u->lname,
                    'role'                     => $u->role,
                    'phone'                    => $u->phone,
                    'experience_years'         => $u->experience_years,
                    'affiliation'              => $u->affiliation,
                    'profile_pic'              => $u->profile_pic,
                    'is_available'             => $u->is_available,
                    'last_lat'                 => $u->last_lat,
                    'last_lng'                 => $u->last_lng,
                    'last_location_updated_at' => $u->last_location_updated_at,
                ];
            }),
        ]);
    }

    /**
     * GET /api/enthusiasts/{id}
     * Public info about a single enthusiast.
     */
    public function getEnthusiastDetails($id)
    {
        $expert = User::where('role', 'enthusiast')->where('user_id', $id)->first();

        if (!$expert) {
            return response()->json(['success' => false, 'message' => 'Enthusiast not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'                       => $expert->user_id,
                'fname'                    => $expert->fname,
                'lname'                    => $expert->lname,
                'role'                     => $expert->role,
                'phone'                    => $expert->phone,
                'experience_years'         => $expert->experience_years,
                'affiliation'              => $expert->affiliation,
                'profile_pic'             => $expert->profile_pic,
                'is_available'            => $expert->is_available,
                'last_lat'                => $expert->last_lat,
                'last_lng'                => $expert->last_lng,
                'last_location_updated_at'=> $expert->last_location_updated_at,
            ]
        ]);
    }
}
