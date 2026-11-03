<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301 www.rdmdev.co.za to the apex host, keeping the path and query string.
 * Nginx Proxy Manager should forward the original Host header. docker/nginx.conf
 * repeats this redirect for requests that reach the container with that Host.
 */
class RedirectWwwToApex
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strcasecmp($request->getHost(), 'www.rdmdev.co.za') !== 0) {
            return $next($request);
        }

        $target = 'https://rdmdev.co.za'.$request->getRequestUri();

        return redirect()->away($target, 301);
    }
}
