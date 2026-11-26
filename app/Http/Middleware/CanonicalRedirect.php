<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalRedirect
{
    /**
     * Enforce single canonical host (non-www) and strip trailing slashes on GET/HEAD requests.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $host = $request->getHost();
        $rawUri = (string) ($request->server->get('REQUEST_URI') ?: $request->getRequestUri());
        $pathInfo = (string) (parse_url($rawUri, PHP_URL_PATH) ?: $request->getPathInfo());
        $needsHostRedirect = str_starts_with(strtolower($host), 'www.');
        $needsSlashRedirect = $pathInfo !== '/' && str_ends_with($pathInfo, '/');

        if (!$needsHostRedirect && !$needsSlashRedirect) {
            return $next($request);
        }

        $targetScheme = $request->getScheme();
        $targetHost = $needsHostRedirect ? substr($host, 4) : $host;
        $targetPath = $needsSlashRedirect ? rtrim($pathInfo, '/') : $pathInfo;
        if ($targetPath === '') {
            $targetPath = '/';
        }

        $qs = $request->getQueryString();
        $targetUrl = $targetScheme . '://' . $targetHost . $targetPath . ($qs ? '?' . $qs : '');

        return redirect()->to($targetUrl, 301);
    }
}
