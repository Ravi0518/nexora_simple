<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin {
    public function handle($request, Closure $next) {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        // If not admin, logout and send to login
        Auth::logout();
        return redirect('/login')->with('error', 'Access Restricted to Administrators.');
    }
}