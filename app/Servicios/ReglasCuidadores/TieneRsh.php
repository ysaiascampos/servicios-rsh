<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use App\Models\GrupoFamiliar;
use Closure;
use Exception;

class TieneRsh
{
    public function handle($request, Closure $next)
    {
        $cuidadorRsh = $request[Constante::DOTOS_CUIDADOR];
        // Valida que la persona cuidada no tenga V1 entre 8 y 11
        $cuidadoGF = (new GrupoFamiliar())->findVigenteAprobadoByRunCiudadano($cuidadorRsh['run']);


        return $next($request);
    }
}