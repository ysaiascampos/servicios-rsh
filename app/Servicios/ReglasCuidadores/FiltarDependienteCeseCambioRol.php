<?php


namespace App\Servicios\ReglasCuidadores;


use App\Constantes\Constante;
use Closure;

class FiltarDependienteCeseCambioRol
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {

        if (isset($request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR])) {
            $lista_cuidadores = $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR];

            $result = $this->consultaCuidado->filtrarDataCeseCambioRol($lista_cuidadores);

            $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR] = $result['nuevo_listado'];
            $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_ESTADO_SOLICITUD_CUIDADOR] = $result["borradores"];
        }

        return $next($request);
    }
}