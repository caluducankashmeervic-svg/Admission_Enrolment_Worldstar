<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class LanOnlyMiddleware
{
    protected array $allowed = [
        '127.0.0.1', '::1',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! IpUtils::checkIp($request->ip(), $this->allowed)) {
            abort(403, 'Access restricted to LAN.');
        }
        return $next($request);
    }
}
