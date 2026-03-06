<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

/**
 * PROFESSIONAL ENGLISH DOCUMENTATION
 * FILE: AuthController.php
 * PURPOSE: Handles Login, Logout, and Profile Management.
 * Note: Registration is handled by OTPController to ensure verified status.
 */
class AuthController extends Controller
{
    /**
     * --- LOGIN ---
     * Authenticates user and returns a Sanctum Token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Attempt authentication
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // BLOCK LOGIN IF NOT VERIFIED
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is not verified. Please complete registration.',
                'needs_verification' => true
            ], 403);
        }

        // Generate Sanctum Token
        $token = $user->createToken('nexora_auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'user_id' => $user->user_id,
                'fname'   => $user->fname,
                'lname'   => $user->lname,
                'email'   => $user->email,
                'role'    => $user->role,
                'profile_pic' => $user->profile_pic,
            ]
        ], 200);
    }

    /**
     * --- LOGOUT ---
     * Revokes the user's current token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * --- FORGOT PASSWORD ---
     * Sends a password reset link to the user's email.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email address not found.' 
            ], 404);
        }

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Your password reset link has been sent to your email.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 400);
    }

    /**
     * --- UPDATE PROFILE ---
     * Updates user details. Only accessible via auth:sanctum middleware.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user(); // Get logged-in user from token

        $validator = Validator::make($request->all(), [
            'fname' => 'sometimes|string|max:255',
            'lname' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'sometimes|nullable|string|max:20',
            'experience_years' => 'sometimes|nullable|integer',
            'affiliation' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user->update($request->only('fname', 'lname', 'email', 'role', 'phone', 'experience_years', 'affiliation'));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user'    => $user
        ]);
    }

    /**
     * --- DELETE ACCOUNT ---
     * Fully removes the user record.
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your account has been permanently deleted.'
        ]);
    }
}