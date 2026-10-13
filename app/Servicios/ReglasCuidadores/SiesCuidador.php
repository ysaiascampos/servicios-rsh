<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use Closure;

class SiesCuidador
{
    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        if($request['tipo-registro'] <= 2){
            $tipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADO;
            $data = [
                "tipo" => $tipo,
                "run" => $request[Constante::DOTOS_CUIDADOR]['run'],
                'rsh' => $request['periodo_rsh']
            ];

            $result = $this->consultaCuidado->mostrarData($data); // ve si trae data de cuidado
            $resultado = $result->toArray();
            if(count($resultado) > 0){
                return response()->json([
                    'status' => 'error',
                    'message' => Constante::$bloqueosListadeCuidador[8],
                ], 200);
            }
        }

        return $next($request);
    }
}