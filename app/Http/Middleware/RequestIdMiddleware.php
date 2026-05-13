<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequestIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = $request->header('X-Request-Id') ?? Str::uuid()->toString();

        $request->attributes->set('request_id', $requestId);

        Log::withContext([
            'request_id' => $requestId,
        ]);

        $response = $next($request);

        return $response->header('X-Request-Id', $requestId);
    }
}
