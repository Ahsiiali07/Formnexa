<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Allow only authenticated administrators to access Admin Dashboard routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('error', 'Please sign in with an administrator account to access the dashboard.');
        }

        if (!Auth::user()->is_admin) {
            abort(403, 'Administrator access required.');
        }

        return $next($request);
    }
}
