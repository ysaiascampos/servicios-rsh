<?php


namespace App\Servicios\ReglasCuidadores;


use App\Constantes\Constante;
use Closure;

class ValidarDependienteRsh
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        $tipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADO;

        $data = [
            "tipo" => $tipo,
            "run" => $request[Constante::DATOS_DEPENDIENTE]['run'],
            'rsh' => $request['periodo_rsh']
        ];

        $result = $this->consultaCuidado->mostrarData($data); // trae lista de cuidadores
        $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR] = $result->toArray();


        return $next($request);
    }
}