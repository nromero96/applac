<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateWebQuotationApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener el token del encabezado de autorización
        $token = $request->header('Authorization');

        // Validar si no se proporcionó el token
        if (!$token) {
            return response()->json(['error' => 'Token de autorización no proporcionado'], 401);
        }

        // Validar si coincide con el token del .env
        if ($token !== 'Bearer ' . config('services.quotation_api_token')) {
            Log::warning('Intento fallido de acceso a Web Quotation API', ['ip' => $request->ip()]);
            //Mostrar env('WEB_QUOTATION_API_TOKEN')
            return response()->json(['error' => 'Token inválido o no autorizado.'], 401);
        }

        return $next($request);
    }
}
