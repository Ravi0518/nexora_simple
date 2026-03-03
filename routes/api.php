<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\Api\SightingController;
use App\Http\Controllers\Api\SnakeController;
use App\Http\Controllers\Api\SnakeImageController;
use App\Http\Controllers\Api\ExpertController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\RescueRequestController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FactController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExpertLocationController;
use App\Http\Controllers\Api\ExpertIncidentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes - Nexora Backend
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. PUBLIC ROUTES — No token required
// =========================================================================

// --- Auth ---
Route::post('/send-otp', [OTPController::class, 'sendRegistrationOTP']);
Route::post('/verify-register', [OTPController::class, 'verifyAndRegister']);
Route::post('/login', [AuthController::class, 'login']);

// --- Snake Species (public catalog) ---
Route::get('/snakes/search', [SnakeController::class, 'search']);  // Must be before {id}
Route::get('/snakes/filter', [SnakeController::class, 'filter']);  // Must be before {id}
Route::get('/snakes', [SnakeController::class, 'index']);
Route::get('/snakes/{id}', [SnakeController::class, 'show']);

// --- Experts (public discovery) ---
Route::get('/experts', [ExpertController::class, 'index']);
Route::get('/experts/{id}', [ExpertController::class, 'show'])->where('id', '[0-9]+');

// --- Enthusiasts (public discovery - new users schema) ---
Route::get('/enthusiasts', [ExpertLocationController::class, 'getAllEnthusiasts']);
Route::get('/enthusiasts/{id}', [ExpertLocationController::class, 'getEnthusiastDetails'])->where('id', '[0-9]+');

// --- Did You Know? facts ---
Route::get('/facts/random', [FactController::class, 'random']);


// =========================================================================
// 2. PROTECTED ROUTES — Any authenticated user (role: user | enthusiast | admin)
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- Auth management ---
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

    // --- User profile ---
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/update/{id}', [UserController::class, 'update']);

    // --- Snake sighting / bite reports ---
    Route::get('/my-incidents', [IncidentController::class, 'myIncidents']);
    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::post('/incidents/{id}/assign', [IncidentController::class, 'assign']);

    // --- Experts ---
    Route::get('/experts/nearby', [ExpertLocationController::class, 'getNearby']);
    Route::post('/experts/location', [ExpertLocationController::class, 'updateLocation']);

    // --- Snake gallery image upload (admin/enthusiast) ---
    Route::post('/snakes/{id}/images', [SnakeImageController::class, 'store']);
    Route::delete('/snakes/{snakeId}/images/{imageId}', [SnakeImageController::class, 'destroy']);

    // --- Legacy sighting endpoint (keep backward compat) ---
    Route::post('/sighting/report', [SightingController::class, 'report']);

    // --- Email verification ---
    Route::post('/email/resend', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1');


    // =====================================================================
    // 3. ADMIN or ENTHUSIAST ROUTES
    // =====================================================================
    Route::middleware('check.role:admin,enthusiast')->group(function () {
        Route::get('/incidents', [IncidentController::class, 'index']);
    });


    // =====================================================================
    // 4. ENTHUSIAST-ONLY ROUTES
    // =====================================================================
    Route::middleware('check.role:enthusiast,admin')->group(function () {
        // Rescue requests (enthusiast dashboard)
        Route::get('/rescue-requests', [RescueRequestController::class, 'index']);
        Route::post('/rescue-requests/{id}/accept', [RescueRequestController::class, 'accept']);
        Route::post('/rescue-requests/{id}/reject', [RescueRequestController::class, 'reject']);

        // Content contribution
        Route::post('/content', [ContentController::class, 'store']);

        // Incident Requests & Catch Reports (enthusiast specific)
        Route::get('/experts/requests', [ExpertIncidentController::class, 'index']);
        Route::post('/experts/catch-report', [ExpertIncidentController::class, 'storeCatchReport']);
    });
});


// =========================================================================
// 5. EMAIL VERIFICATION SYSTEM
// =========================================================================
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['success' => true, 'message' => 'Email verified successfully!']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');