<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use Closure;
use Exception;

class ResultadoCuidadorDependienteRsh
{

    private $consultaCuidado;
    private $dataGolbal;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        $this->dataGolbal = $request;

        // Salida de cuidadores tipo 1
        if($this->dataGolbal['tipo-registro'] == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR){
            // pregunto si pertenece al mismo grupo de hogar sino eliminamos datos que no se tienen que mostrar
            if(!$this->dataGolbal['mismo_grupofamiliar']){
                $data = $this->dataGolbal[Constante::DATOS_DEPENDIENTE];
                $keysRemover = [
                    Constante::DEPENDIENTE_LISTA_CUIDADOR,
                    Constante::DEPENDIENTE_LISTA_ESTADO11_CUIDADOR,
                    Constante::DEPENDIENTE_LISTA_APROBADO_SOLICITUD_CUIDADOR ,
                ];

                $nuevoRequest = $this->consultaCuidado->removerData($data,$keysRemover);
                $this->dataGolbal[Constante::DATOS_DEPENDIENTE] = $nuevoRequest;
            }
        }

        // Salida de dependiente tipo 2
        if($this->dataGolbal['tipo-registro'] == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO){
            if(!$this->dataGolbal['mismo_grupofamiliar']){
                $data = $this->dataGolbal[Constante::DOTOS_CUIDADOR];

                $keysRemover = [
                    Constante::CUIDADOR_LISTA_DEPENDIENTE,
                    Constante::CUIDADOR_LISTA_ESTADO11_DEPENDIENTE,
                    Constante::CUIDADOR_LISTA_APROBADO_SOLICITUD_DEPENDIENTE,
                ];

                $nuevoRequest = $this->consultaCuidado->removerData($data,$keysRemover);
                $this->dataGolbal[Constante::DOTOS_CUIDADOR] = $nuevoRequest;
            }
        }

        // elimina datos insesesarios dentro de la salida general
        $keysRemover = [
            "origen-registro",
            "periodo_rsh",
        ];

        $request = $this->consultaCuidado->removerData($this->dataGolbal,$keysRemover);


        return $next($request);
    }
}