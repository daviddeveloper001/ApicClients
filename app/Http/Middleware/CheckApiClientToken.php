<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiClientToken
{

    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->bearerToken();

        if (!$header) {
            return response()->json(['message' => 'Token faltante'], 401);
        }

        $client = ApiClient::where('token', hash('sha256', $header))
                           ->where('active', true)
                           ->first();


        if (!$client) {
            return response()->json(['message' => 'Token inválido o cliente deshabilitado'], 401);
        }

        $client->updateQuietly(['last_connected_at' => Carbon::now()]);

        $request->merge(['api_client' => $client]);

        return $next($request);
    }
}
