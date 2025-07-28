<?php

namespace App\Http\Middleware;

use App\Services\Api\V1\ApiClientServiceV1;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'Token no proporcionado'], 401);
        }

        $service = app(ApiClientServiceV1::class);

        $client = $service->validateToken($token, $request->ip());

        if (! $client) {
            return response()->json(['message' => 'Token inválido o cliente deshabilitado'], 401);
        }

        // Opcional: agregar el ApiClient autenticado al request para uso posterior
        $request->merge(['api_client' => $client]);

        return $next($request);
    }
}
