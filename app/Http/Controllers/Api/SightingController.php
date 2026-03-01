<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident; // Ensure this Model exists!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SightingController extends Controller
{
    public function report(Request $request)
    {
        try {
            $request->validate([
                'snake_name' => 'required|string',
                'location' => 'required|string',
                'image' => 'required|image|max:2048', 
            ]);

            // Save image
            $path = $request->file('image')->store('incidents', 'public');

            // Save to DB
            $incident = Incident::create([
                'user_id' => auth()->id(), 
                'snake_name' => $request->snake_name,
                'location' => $request->location,
                'image_path' => $path,
            ]);

            return response()->json(['message' => 'Success', 'data' => $incident], 201);

        } catch (\Exception $e) {
            // This will return the ACTUAL error instead of a 500 page
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}