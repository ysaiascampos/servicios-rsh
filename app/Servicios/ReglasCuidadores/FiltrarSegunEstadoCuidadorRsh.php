<?php


namespace App\Servicios\ReglasCuidadores;


use App\Constantes\Constante;
use Closure;


class FiltrarSegunEstadoCuidadorRsh
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

            $result = $this->consultaCuidado->filtrarDataCuidadoCuidadores($lista_cuidadores);

            $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $result['nuevo_listado'];
            $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_ESTADO11_DEPENDIENTE] = $result["borradores"];
        }

        return $next($request);
    }
}