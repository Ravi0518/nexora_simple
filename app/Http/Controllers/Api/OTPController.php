<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller 
{
    // STEP 1: Check availability and send OTP
    public function sendRegistrationOTP(Request $request) 
    {
        $request->validate(['email' => 'required|email']);

        if (User::where('email', $request->email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Email already registered.'], 409);
        }

        $otp = rand(100000, 999999);
        Cache::put('reg_otp_' . $request->email, $otp, now()->addMinutes(10));

        // Log it for local testing
        Log::info("Nexora Registration OTP for {$request->email}: {$otp}");

        // Send real email (Ensure .env is configured)
        try {
            Mail::raw("Your Nexora verification code is: $otp", function ($message) use ($request) {
                $message->to($request->email)->subject('Nexora Email Verification');
            });
        } catch (\Exception $e) {
            Log::error("Mail failed: " . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'OTP sent successfully!']);
    }

    // STEP 2: Verify OTP and Create the User Account
    public function verifyAndRegister(Request $request) 
    {
        $request->validate([
            'fname' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required',
            'otp' => 'required|numeric'
        ]);

        $cachedOtp = Cache::get('reg_otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        // Create the user
        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname ?? 'User', 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        Cache::forget('reg_otp_' . $request->email);

        return response()->json([
            'success' => true, 
            'message' => 'Account created successfully!',
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }
}