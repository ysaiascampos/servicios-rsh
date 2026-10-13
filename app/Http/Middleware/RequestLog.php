<?php

namespace App\Http\Middleware;

use App\Utiles;
use Closure;

class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('ACTIVAR_LOG_SERVICIOS')) {
            Utiles::guardadoDatosServicios(); // guarda el detalle del consumo en la tabla LOG_SERVICIOS de asignacion
        }
        return $next($request);
    }
}
