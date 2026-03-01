<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * CheckRole Middleware
 * Usage in routes: ->middleware('check.role:admin,enthusiast')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient role permissions.',
            ], 403);
        }

        return $next($request);
    }
}
