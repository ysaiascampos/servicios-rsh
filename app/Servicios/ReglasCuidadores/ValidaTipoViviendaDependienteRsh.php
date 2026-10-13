<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use App\Models\GrupoFamiliar;
use Closure;
use Exception;


class ValidaTipoViviendaDependienteRsh
{
    public function handle($request, Closure $next)
    {
        $dependiente = $request[Constante::DATOS_DEPENDIENTE];
        // Valida que la persona cuidada no tenga V1 entre 8 y 11
        $cuidadoGF = (new GrupoFamiliar())->findVigenteAprobadoByRunCiudadano($dependiente['run']);
        if (!is_null($cuidadoGF)) {
            if (in_array($cuidadoGF->GRUPFAMI_TIPOVIVIENDA, Constante::$BLOQUEO_V1_8_Y_10_VIVIENDA_NO_VALIDA)) {
                throw new Exception('El tipo de vivienda informada en el Registro de la persona que requiere cuidados no puede ser “Hospedería, Vivienda colectiva, Residencial o pensión”.');
            }
        } else {

            throw new Exception("El hogar de la persona que requiere cuidados no se encuentra vigente en sistema.");
        }

        return $next($request);
    }
}
