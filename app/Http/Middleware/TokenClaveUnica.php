<?php

namespace App\Http\Middleware;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Utiles;
use Closure;
use Illuminate\Support\Facades\Validator;

class TokenClaveUnica
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $origenRegistro = request()->get('origen-registro');

        if (Utiles::inArrayMinuscula($origenRegistro, Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS)) {
            $input = [
                'token-clave-unica' => request()->header('token-clave-unica')
            ];
            $reglas = [
                'token-clave-unica' => 'required'
            ];
            $mensajes = [
                'token-clave-unica.required' => 'El token de Clave Única es requerido para consumir servicio'
            ];
            $validator = Validator::make($input, $reglas, $mensajes);
            if ($validator->fails()) {
                throw new ExcepcionValidacion($validator->errors());
            }
        }
        return $next($request);
    }
}
