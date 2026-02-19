<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StreamDeckAuth
{
    public function handle(Request $request, Closure $next)
    {
        $expected = env('STREAMDECK_TOKEN');

        // Aceptar token por: Bearer header, header X-StreamDeck-Token, o query param ?token=
        $provided = $request->bearerToken()
            ?? $request->header('X-StreamDeck-Token')
            ?? $request->query('token');

        if (!$expected || $provided !== $expected) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
