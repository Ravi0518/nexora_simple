<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MobileController extends Controller
{
    // --- REGISTER ---
    public function register(Request $request) {
        $request->validate([
            'fname' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname ?? 'User',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'general_public',
        ]);

        return response()->json(['message' => 'User Created', 'user' => $user], 201);
    }

    // --- LOGIN ---
    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    // --- REPORT SIGHTING (With Image) ---
    public function reportSighting(Request $request) {
        $request->validate([
            'snake_name' => 'required',
            'location' => 'required',
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('incidents', 'public');

        $incident = Incident::create([
            'user_id' => auth()->id(),
            'snake_name' => $request->snake_name,
            'location' => $request->location,
            'image_path' => $path,
            'confidence_level' => $request->confidence ?? 'Manual',
        ]);

        return response()->json(['message' => 'Report Saved', 'data' => $incident], 201);
    }
}