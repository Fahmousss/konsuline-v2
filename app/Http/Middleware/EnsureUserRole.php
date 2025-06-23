<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has required role
        if (! in_array($user->role->value, $roles)) {
            // Redirect to their appropriate dashboard instead of throwing 403
            return redirect()->route($user->role->redirectPath())
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
