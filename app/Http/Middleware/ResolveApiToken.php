<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResolveApiToken
{
    /**
     * Authenticate mobile API requests using a Bearer token from `api_tokens`.
     *
     * Modes:
     * - "required": rejects unauthenticated requests with 401 JSON.
     * - "optional": allows guest access when no Bearer header is sent,
     *   but still rejects invalid/expired Bearer tokens with 401 JSON.
     */
    public function handle(Request $request, Closure $next, string $mode = 'required'): Response
    {
        $bearer = $request->bearerToken();

        if ($bearer !== null && trim($bearer) !== '') {
            $apiToken = ApiToken::findValid($bearer);

            if (!$apiToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired authentication token.',
                ], 401);
            }

            if ($apiToken->last_used_at === null || $apiToken->last_used_at->lt(now()->subMinute())) {
                $apiToken->forceFill(['last_used_at' => now()])->saveQuietly();
            }

            $user = $apiToken->user;
            Auth::setUser($user);
            $request->setUserResolver(static fn () => $user);
            $request->attributes->set('api_token', $apiToken);

            return $next($request);
        }

        if ($mode === 'required') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please provide a valid Bearer token.',
            ], 401);
        }

        return $next($request);
    }
}
