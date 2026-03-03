<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Snake;
use Illuminate\Http\Request;

/**
 * API SnakeController — serves /api/snakes endpoints for the Nexora Flutter app.
 * Response format matches the v2 contract with multilingual names, details, and gallery images.
 */
class SnakeController extends Controller
{
    // -------------------------------------------------------------------------
    // GET /api/snakes
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Snake::with('images');

        // Search: name (EN/SI/TA), scientific name, slug
        if ($request->filled('q')) {
            $q = $request->q;
            $slug = str_replace(' ', '_', strtolower($q));
            $query->where(function ($q2) use ($q, $slug) {
                $q2->where('name',            'LIKE', "%$q%")
                   ->orWhere('common_name',   'LIKE', "%$q%")
                   ->orWhere('name_si',       'LIKE', "%$q%")
                   ->orWhere('name_ta',       'LIKE', "%$q%")
                   ->orWhere('scientific_name','LIKE', "%$q%")
                   ->orWhere('slug',          'LIKE', "%$slug%");
            });
        }

        // Filter by venomous
        if ($request->filled('venomous')) {
            $query->where('is_venomous', filter_var($request->venomous, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by region
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $perPage = $request->get('per_page', 50);
        $snakes  = $query->paginate($perPage);

        return response()->json([
            'data'  => $snakes->map(fn($s) => $this->formatSnake($s))->values(),
            'total' => $snakes->total(),
        ]);
    }

    // -------------------------------------------------------------------------
    // GET /api/snakes/search?q={query}
    // -------------------------------------------------------------------------
    public function search(Request $request)
    {
        return $this->index($request);
    }

    // -------------------------------------------------------------------------
    // GET /api/snakes/filter?venomous=true&region=Wet+Zone
    // -------------------------------------------------------------------------
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    // -------------------------------------------------------------------------
    // GET /api/snakes/{id}
    // -------------------------------------------------------------------------
    public function show($id)
    {
        $snake = Snake::with('images')->find($id);
        
        if (!$snake) {
            return response()->json([
                'success' => false,
                'message' => 'this image cant identify result'
            ], 404);
        }

        return response()->json($this->formatSnake($snake));
    }

    // -------------------------------------------------------------------------
    // Private: format a Snake into the v2 API response shape
    // -------------------------------------------------------------------------
    private function formatSnake(Snake $s): array
    {
        $images    = $s->images->sortBy('sort_order')->pluck('image_url')->values()->toArray();
        $heroImage = $images[0] ?? ($s->image_url ? asset('storage/' . ltrim($s->image_url, '/')) : null);

        /** Decode JSON string → array, or return value as-is if already array */
        $decode = fn($v) => is_string($v) ? (json_decode($v, true) ?? []) : ($v ?? []);

        return [
            'id'              => $s->snake_id,
            'slug'            => $s->slug,
            'is_venomous'     => (bool) $s->is_venomous,
            'scientific_name' => $s->scientific_name,
            'region'          => $s->region,

            'names' => [
                'en' => $s->name ?? $s->common_name,
                'si' => $s->name_si,
                'ta' => $s->name_ta,
            ],

            'danger_level' => [
                'en' => $s->danger_level ?? $s->venomous_status,
                'si' => $s->danger_level_si,
                'ta' => $s->danger_level_ta,
            ],

            'details' => [
                'en' => [
                    'about'     => $s->about ?? $s->description,
                    'habitat'   => $s->habitat,
                    'behavior'  => $s->behavior,
                    'diet'      => $s->diet,
                    'first_aid' => $decode($s->first_aid) ?: ($s->first_aid_steps ? [$s->first_aid_steps] : []),
                    'donts'     => $decode($s->donts),
                ],
                'si' => [
                    'about'     => $s->about_si,
                    'habitat'   => $s->habitat_si,
                    'behavior'  => $s->behavior_si,
                    'diet'      => $s->diet_si,
                    'first_aid' => $decode($s->first_aid_si),
                    'donts'     => $decode($s->donts_si),
                ],
                'ta' => [
                    'about'     => $s->about_ta,
                    'habitat'   => $s->habitat_ta,
                    'behavior'  => $s->behavior_ta,
                    'diet'      => $s->diet_ta,
                    'first_aid' => $decode($s->first_aid_ta),
                    'donts'     => $decode($s->donts_ta),
                ],
            ],

            'image_url'  => $heroImage,
            'image_urls' => array_slice($images ?: ($heroImage ? [$heroImage] : []), 0, 3),
        ];
    }
}
