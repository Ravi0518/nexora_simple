<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // added import
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SnakeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dev\MailTestController; // added for test route

Route::get('/', function () { return redirect()->route('admin.dashboard'); });

// Enable email verification routes
Auth::routes(['verify' => true]);

// Local-only mail test route: visit /dev/send-test-email?to=you@gmail.com
if (app()->environment('local')) {
    Route::get('/dev/send-test-email', [MailTestController::class, 'send']);

    // Local-only: view effective mailer config (masked)
    Route::get('/dev/mail-config', function () {
        $mailer = config('mail.default');
        $cfg = config("mail.mailers.{$mailer}", []);
        if (isset($cfg['username'])) $cfg['username'] = '***';
        if (isset($cfg['password'])) $cfg['password'] = '***';
        return response()->json(['default_mailer' => $mailer, 'config' => $cfg]);
    });
}

Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::resource('snakes', SnakeController::class);
    // Snake gallery image management
    Route::delete('/snakes/{snake}/images/{image}', [SnakeController::class, 'destroyImage'])
         ->name('snakes.images.destroy');
    Route::get('/incidents', [AdminController::class, 'viewIncidents'])->name('admin.incidents');
    Route::get('/requests', [AdminController::class, 'viewRequests'])->name('admin.requests');
});