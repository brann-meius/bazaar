<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireJsonAccept
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson()) {
            return response()->json([
                'error' => 'Accept header must be application/json.'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
