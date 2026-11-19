<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RobotsHeaders
{
    /**
     * Set strict noindex robots headers on private, admin, or API requests.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->is('api/*')
            || $request->is('api')
            || $request->is('admin*')
            || $request->is('history')
            || $request->is('blog-studio*')
        ) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
