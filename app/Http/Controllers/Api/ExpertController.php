<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;

/**
 * ExpertController
 * Handles /api/experts endpoints — list of snake enthusiasts available for rescue.
 */
class ExpertController extends Controller
{
    /**
     * GET /api/experts
     * Returns paginated list of all verified experts.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $experts = Expert::paginate($perPage);

        return response()->json($experts->getCollection()->map(fn($e) => $this->format($e)));
    }

    /**
     * GET /api/experts/{id}
     * Returns a single expert's full detail.
     */
    public function show($id)
    {
        $expert = Expert::findOrFail($id);
        return response()->json($this->format($expert));
    }

    /**
     * Format an Expert model into the API response shape.
     */
    private function format(Expert $expert): array
    {
        return [
            'id'                => $expert->id,
            'name'              => $expert->name,
            'role'              => $expert->role,
            'phone'             => $expert->phone,
            'distance_km'       => null, // Calculated on client side using lat/lng
            'status'            => $expert->status,
            'profile_image_url' => $expert->profile_image_url
                ? asset('storage/' . $expert->profile_image_url)
                : null,
            'lat'               => $expert->lat,
            'lng'               => $expert->lng,
            'rating'            => $expert->rating,
            'total_rescues'     => $expert->total_rescues,
        ];
    }
}
