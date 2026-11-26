<?php

use App\Http\Middleware\CanonicalRedirect;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\NoIndex;
use App\Http\Middleware\ResolveApiToken;
use App\Http\Middleware\RobotsHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(CanonicalRedirect::class);
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'api.auth' => ResolveApiToken::class . ':required',
            'api.optional' => ResolveApiToken::class . ':optional',
            'noindex' => NoIndex::class,
            'admin' => EnsureAdmin::class,
        ]);
        $middleware->append(RobotsHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
        $exceptions->respond(function (Response $response, \Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->is('api') || $response->getStatusCode() === 404 || $response->getStatusCode() === 405) {
                $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
            }
            return $response;
        });
    })->create();
