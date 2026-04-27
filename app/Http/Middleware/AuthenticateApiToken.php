<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();

        if (!$bearer || !($apiToken = ApiToken::findByToken($bearer))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($apiToken->isExpired()) {
            return response()->json(['message' => 'API-Token ist abgelaufen.'], 401);
        }

        if (!$apiToken->user?->is_active) {
            return response()->json(['message' => 'Konto ist deaktiviert.'], 403);
        }

        $apiToken->update(['last_used_at' => now()]);

        $request->merge(['api_token' => $apiToken]);

        return $next($request);
    }
}
