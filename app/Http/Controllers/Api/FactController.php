<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fact;

/**
 * FactController
 * Handles GET /api/facts/random — "Did You Know?" widget on the home screen.
 */
class FactController extends Controller
{
    /**
     * GET /api/facts/random
     * Returns a single random snake fact.
     */
    public function random()
    {
        $fact = Fact::inRandomOrder()->first();

        if (!$fact) {
            return response()->json([
                'success' => false,
                'message' => 'No facts available.',
            ], 404);
        }

        return response()->json([
            'id'        => $fact->id,
            'fact'      => $fact->fact,
            'image_url' => $fact->image_url ? asset('storage/' . $fact->image_url) : null,
        ]);
    }
}
