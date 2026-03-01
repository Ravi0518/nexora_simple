<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * UserController
 * Handles /api/user/profile and /api/user/update/{id} endpoints.
 */
class UserController extends Controller
{
    /**
     * GET /api/user/profile
     * Returns the authenticated user's full profile including counts.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'                    => $user->user_id,
            'fname'                 => $user->fname,
            'lname'                 => $user->lname,
            'email'                 => $user->email,
            'phone'                 => $user->phone,
            'role'                  => $user->role,
            'profile_image_url'     => $user->profile_pic
                ? asset('storage/' . $user->profile_pic)
                : null,
            'created_at'            => $user->created_at,
            'sightings_count'       => $user->incidents()->count(),
            'identifications_count' => $user->incidents()
                ->whereNotNull('snake_name')
                ->count(),
        ]);
    }

    /**
     * PUT /api/user/update/{id}
     * Update user profile (name, phone). Returns 200 on success.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Ensure users can only update their own profile (admins can update any)
        if ($user->user_id != $id && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $target = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'fname' => 'sometimes|string|max:255',
            'lname' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $target->user_id . ',user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $target->update($request->only('fname', 'lname', 'phone', 'email'));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user'    => [
                'id'    => $target->user_id,
                'fname' => $target->fname,
                'lname' => $target->lname,
                'email' => $target->email,
                'phone' => $target->phone,
                'role'  => $target->role,
            ],
        ], 200);
    }
}
