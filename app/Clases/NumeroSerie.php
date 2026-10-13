<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Cow;
use App\Exceptions\ExcepcionEjecucion;
use App\Http\Inputs\NumeroSerieInput;
use Exception;

class NumeroSerie
{

    public static function validar(NumeroSerieInput $input)
    {
        $data = null;
        try {
            $runCiudadano = $input->{'run-ciudadano'};
            $nroSerie = $input->{'numero-serie'};
            $con = new Cow();
            if(env('COW_REST')){
                $verificar = $con->getDataDocumentRest($runCiudadano, $nroSerie);
            }else{
                $verificar = $con->getDataDocument($runCiudadano, $nroSerie);
            }
            $estadoRespuesta = isset($verificar->EstadoRespuesta)?$verificar->EstadoRespuesta:null;
            if (isset($verificar->estado) && $verificar->estado == "error") {
                return [
                    'estado' => '0',
                    'msg' => $verificar->msg,
                ];
            } else if (isset($verificar->estado) && $verificar->estado == "0"
                && (is_null($estadoRespuesta)
                || (!(is_null($estadoRespuesta)) && $verificar->EstadoRespuesta != 000))){
                return [
                    'estado' => '0',
                    'msg' => $verificar->msg
                ];
            } else {
                if ($verificar->EstadoRespuesta == 301) {
                    return [
                        'estado' => '0',
                        'msg' => 'Advertencia: El número de serie o número de documento ingresado no es correcto, favor ingresar nuevamente.'
                    ];
                } elseif ($verificar->EstadoRespuesta == 207) {
                    return [
                        'estado' => '0',
                        'msg' => 'Advertencia: El servicio de validación del número de serie se encuentra con problemas, favor intentas más tarde.'
                    ];
                } elseif ($verificar->EstadoRespuesta == 206) {
                    return [
                        'estado' => '0',
                        'msg' => 'Advertencia: Formato incorrecto en número de serie o número de documento, favor ingresar nuevamente.'
                    ];
                } elseif ($verificar->EstadoRespuesta == 201) {
                    return [
                        'estado' => '0',
                        'msg' => 'Advertencia: Ha ocurrido un problema al intentar validar el número de serie o número de documento, favor dar aviso al soporte técnico.'
                    ];
                } elseif ($verificar->EstadoRespuesta == 000) {
                    /* caso exitoso */
                    if ($verificar->IndVigencia == "N") {
                        $fechaVencimientoDoc = date_create_from_format('d/m/Y H:i:s', $verificar->FhoVcto);
                        $OK = true;
                        if(env('FECHA_VIGENCIA_CEDULA_MIN') && env('FECHA_VIGENCIA_CEDULA_MAX')){
                            $fechaVigenciaCedulaMin =
                                date_create_from_format('d/m/Y', env('FECHA_VIGENCIA_CEDULA_MIN'));
                            $fechaVigenciaCedulaMax =
                                date_create_from_format('d/m/Y', env('FECHA_VIGENCIA_CEDULA_MAX'));
                            if ($fechaVencimientoDoc >= $fechaVigenciaCedulaMin &&
                                $fechaVencimientoDoc <= $fechaVigenciaCedulaMax
                            ) {
                                $OK = false;
                            }
                        }
                        if($OK) {
                            return [
                                'estado' => '0',
                                'msg' => 'Advertencia: Su número de serie o número de documento se encuentra no vigente, favor regularice su situación.'
                            ];
                        }
                    }
                    $data = $verificar;
                }
            }
        } catch (Exception $ex) {
            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }
        return $data;
    }
}
