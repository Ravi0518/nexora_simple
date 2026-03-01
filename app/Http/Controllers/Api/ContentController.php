<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * ContentController
 * Handles POST /api/content — enthusiast content submission for admin review.
 */
class ContentController extends Controller
{
    /**
     * POST /api/content
     * Submit an educational article or safety tip.
     * Requires enthusiast or admin role (enforced via route middleware).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'    => 'required|in:article,safety_tip',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:20480', // 20 MB per file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('content_media', 'public');
            }
        }

        Content::create([
            'user_id'     => $request->user()->user_id,
            'type'        => $request->type,
            'title'       => $request->title,
            'content'     => $request->content,
            'media_paths' => $mediaPaths ?: null,
            'status'      => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submitted for review.',
        ], 201);
    }
}
