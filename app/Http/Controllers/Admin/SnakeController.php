<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Snake;
use App\Models\SnakeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Admin SnakeController — manages snake species in the admin panel.
 * Handles all CRUD + multi-image gallery uploads.
 */
class SnakeController extends Controller
{
    public function index()
    {
        $snakes = Snake::with('images')->latest()->get();
        return view('admin.snakes.index', compact('snakes'));
    }

    public function create()
    {
        return view('admin.snakes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'images.*'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $snake = Snake::create($this->buildData($request));

        // Handle up to 3 gallery images
        if ($request->hasFile('images')) {
            foreach (array_slice($request->file('images'), 0, 3) as $i => $file) {
                if (!$file) continue;
                $path = $file->store('snakes', 'public');
                $url  = asset(Storage::url($path));
                $snake->images()->create([
                    'image_url'  => $url,
                    'sort_order' => $i,
                ]);
                // Set first image as legacy image_url for backwards compat
                if ($i === 0) {
                    $snake->update(['image_url' => $path]);
                }
            }
        }

        return redirect()->route('snakes.index')->with('success', 'Species added to Nexora DB.');
    }

    public function edit($id)
    {
        $snake = Snake::with('images')->findOrFail($id);
        return view('admin.snakes.edit', compact('snake'));
    }

    public function update(Request $request, $id)
    {
        $snake = Snake::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'images.*'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $this->buildData($request);

        // Update slug if name changed
        if ($snake->name !== $request->name) {
            $data['slug'] = Str::slug($request->name, '_');
        }

        $snake->update($data);

        // Handle new image uploads (fill remaining slots up to 3)
        if ($request->hasFile('images')) {
            $currentCount = $snake->images()->count();
            foreach ($request->file('images') as $file) {
                if (!$file || $currentCount >= 3) break;
                $path = $file->store('snakes', 'public');
                $url  = asset(Storage::url($path));
                $snake->images()->create([
                    'image_url'  => $url,
                    'sort_order' => $currentCount,
                ]);
                // Keep legacy image_url pointing to hero image
                if ($currentCount === 0) {
                    $snake->update(['image_url' => $path]);
                }
                $currentCount++;
            }
        }

        return redirect()->route('snakes.edit', $id)->with('success', 'Species updated successfully!');
    }

    /**
     * DELETE /admin/snakes/{snakeId}/images/{imageId}
     * Remove a gallery image from the admin edit form.
     */
    public function destroyImage($snakeId, $imageId)
    {
        $snake = Snake::findOrFail($snakeId);
        $image = SnakeImage::where('snake_id', $snake->snake_id)->findOrFail($imageId);

        // Delete file from storage
        $path = str_replace(url('/storage') . '/', '', $image->image_url);
        Storage::disk('public')->delete($path);
        $image->delete();

        // Re-number sort_order for remaining images
        $snake->images()->orderBy('sort_order')->get()
              ->each(fn($img, $idx) => $img->update(['sort_order' => $idx]));

        // Update legacy image_url to new hero (sort_order=0), or null
        $hero = $snake->images()->where('sort_order', 0)->first();
        $snake->update(['image_url' => $hero ? str_replace(asset('storage/'), '', $hero->image_url) : null]);

        return redirect()->route('snakes.edit', $snakeId)->with('success', 'Image removed.');
    }

    public function destroy($id)
    {
        $snake = Snake::with('images')->findOrFail($id);

        // Delete all gallery images from storage
        foreach ($snake->images as $img) {
            $path = str_replace(url('/storage') . '/', '', $img->image_url);
            Storage::disk('public')->delete($path);
        }

        if ($snake->image_url) {
            Storage::disk('public')->delete($snake->image_url);
        }

        $snake->delete();
        return redirect()->route('snakes.index')->with('success', 'Species removed.');
    }

    // ─── PRIVATE HELPERS ──────────────────────────────────────────────────────

    /**
     * Build the data array from request, converting line-by-line textareas
     * into JSON arrays for first_aid and donts fields.
     */
    private function buildData(Request $request): array
    {
        $toJson = fn($val) => json_encode(
            array_values(array_filter(array_map('trim', explode("\n", $val ?? ''))))
        );

        return [
            // Names
            'name'              => $request->name,
            'common_name'       => $request->name, // keep legacy field in sync
            'name_si'           => $request->name_si,
            'name_ta'           => $request->name_ta,
            'scientific_name'   => $request->scientific_name,

            // Venom
            'is_venomous'       => $request->boolean('is_venomous'),
            'venomous_status'   => $request->danger_level, // keep legacy in sync
            'danger_level'      => $request->danger_level,
            'danger_level_si'   => $request->danger_level_si,
            'danger_level_ta'   => $request->danger_level_ta,

            // Location
            'region'            => $request->region,

            // About
            'about'             => $request->about,
            'description'       => $request->about, // keep legacy in sync
            'about_si'          => $request->about_si,
            'about_ta'          => $request->about_ta,

            // Habitat
            'habitat'           => $request->habitat,
            'habitat_si'        => $request->habitat_si,
            'habitat_ta'        => $request->habitat_ta,

            // Behavior
            'behavior'          => $request->behavior,
            'behavior_si'       => $request->behavior_si,
            'behavior_ta'       => $request->behavior_ta,

            // Diet
            'diet'              => $request->diet,
            'diet_si'           => $request->diet_si,
            'diet_ta'           => $request->diet_ta,

            // First Aid — line-by-line textarea → JSON array
            'first_aid'         => $toJson($request->first_aid),
            'first_aid_steps'   => $request->first_aid, // keep legacy plain text
            'first_aid_si'      => $toJson($request->first_aid_si),
            'first_aid_ta'      => $toJson($request->first_aid_ta),

            // Do NOTs — line-by-line textarea → JSON array
            'donts'             => $toJson($request->donts),
            'donts_si'          => $toJson($request->donts_si),
            'donts_ta'          => $toJson($request->donts_ta),
        ];
    }
}