<?php


namespace App\Servicios\ReglasCuidadores;



use Closure;


class ResultadoCuidadoresRsh
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }
    public function handle($request, Closure $next)
    {
        // elimina datos insesesarios dentro de la salida
        $keysRemover = [
            "origen-registro",
            "data_dependiente",
            "periodo_rsh",
            "motivos_bloqueo"
        ];

        $request= $this->consultaCuidado->removerData($request,$keysRemover);


        return $next($request);
    }
}