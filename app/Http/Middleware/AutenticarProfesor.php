<?php

namespace App\Http\Middleware;

use App\Services\TokenProfesor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutenticarProfesor
{
    public function handle(Request $request, Closure $next): Response
    {
        $cabecera = $request->bearerToken();

        if (! $cabecera) {
            return response()->json(['detail' => 'Credencial requerida.'], 401);
        }

        try {
            $request->attributes->set('id_profesor', app(TokenProfesor::class)->validar($cabecera));
        } catch (\Throwable $error) {
            return response()->json(['detail' => $error->getMessage()], 401);
        }

        return $next($request);
    }
}
