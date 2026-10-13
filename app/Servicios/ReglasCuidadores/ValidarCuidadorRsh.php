<?php


namespace App\Servicios\ReglasCuidadores;


use App\Constantes\Constante;
use Closure;

class ValidarCuidadorRsh
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        $tipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADOR;

        $data = [
            "tipo" => $tipo,
            "run" => $request[Constante::DOTOS_CUIDADOR]['run'],
            'rsh' => $request['periodo_rsh']
        ];

        $result = $this->consultaCuidado->mostrarData($data); // trae lista de cuidadores
        $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $result->toArray();

        return $next($request);
    }
}