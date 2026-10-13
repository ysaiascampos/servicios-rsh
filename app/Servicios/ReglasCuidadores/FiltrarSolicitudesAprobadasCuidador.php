<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use Closure;

class FiltrarSolicitudesAprobadasCuidador
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {

        if (isset($request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE])) {
            $lista_cuidadores = $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE];

            $result = $this->consultaCuidado->filtrarAprobadaCeseCambioRol($lista_cuidadores);

            $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $result['nuevo_listado'];
            $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_APROBADO_SOLICITUD_DEPENDIENTE] = $result["borradores"];
        }

        return $next($request);
    }
}