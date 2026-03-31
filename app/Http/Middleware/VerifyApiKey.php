<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (! $apiKey) {
            return response()->json(['error' => 'API key is required'], 401);
        }

        $key = ApiKey::where('key', $apiKey)->first();

        if (! $key) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        if (! $key->isValid()) {
            return response()->json(['error' => 'API key is expired or inactive'], 401);
        }

        return $next($request);
    }
}
