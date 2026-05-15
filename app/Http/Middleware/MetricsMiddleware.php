<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetricsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        cache()->increment('requests_count');

        $response = $next($request);

        $duration = (microtime(true) - $start) * 1000;

        cache()->put(
            'response_time',
            round($duration, 2)
        );

        return $response;
    }
}
