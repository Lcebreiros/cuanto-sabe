<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StreamDeckAuth
{
    public function handle(Request $request, Closure $next)
    {
        $expected = config('services.streamdeck.token');

        // Aceptar token solo por header (Bearer o X-StreamDeck-Token) — nunca por query string,
        // que queda expuesto en logs de acceso, headers Referer y proxies intermedios.
        $provided = $request->bearerToken()
            ?? $request->header('X-StreamDeck-Token');

        if (!$expected || !$provided || !hash_equals((string) $expected, (string) $provided)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
