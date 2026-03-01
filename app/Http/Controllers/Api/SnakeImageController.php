<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Snake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * SnakeImageController
 * Handles POST /api/snakes/{id}/images — upload a gallery image for a snake.
 * Authenticated endpoint (auth:sanctum).
 */
class SnakeImageController extends Controller
{
    /**
     * POST /api/snakes/{id}/images
     * Upload one image. The sort_order is auto-assigned as current count (0, 1, 2).
     * First upload = hero (sort_order 0), subsequent = gallery slides.
     */
    public function store(Request $request, $id)
    {
        $snake = Snake::findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Limit to 3 images per snake
        if ($snake->images()->count() >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum of 3 images allowed per snake. Delete an existing image first.',
            ], 422);
        }

        $path      = $request->file('image')->store('snakes', 'public');
        $url       = asset(Storage::url($path));
        $sortOrder = $snake->images()->count(); // 0, 1, or 2

        $img = $snake->images()->create([
            'image_url'  => $url,
            'sort_order' => $sortOrder,
        ]);

        return response()->json([
            'success'    => true,
            'image_id'   => $img->id,
            'image_url'  => $img->image_url,
            'sort_order' => $img->sort_order,
        ], 201);
    }

    /**
     * DELETE /api/snakes/{snakeId}/images/{imageId}
     * Remove a specific gallery image.
     */
    public function destroy($snakeId, $imageId)
    {
        $snake = Snake::findOrFail($snakeId);
        $image = $snake->images()->findOrFail($imageId);

        // Remove the file from storage
        $path = str_replace(asset('storage/'), '', $image->image_url);
        Storage::disk('public')->delete(ltrim($path, '/'));

        $image->delete();

        // Re-number sort_order for remaining images
        $snake->images()->orderBy('sort_order')->get()
              ->each(fn($img, $i) => $img->update(['sort_order' => $i]));

        return response()->json(['success' => true, 'message' => 'Image deleted.']);
    }
}
