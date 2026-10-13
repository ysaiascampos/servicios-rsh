<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Models\LogConsultaQrCuidadoras;
use App\Models\Personas;
use App\Models\QrCuidadoras;
use App\Sequence;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass;
use DateTime;

class Qr
{

    public static function validar($input)
    {
        $runUsuario = $input['run-usuario'];
        $hash = $input['hash'];

        if (!isset($hash) && !$hash) {
            //TODOC CREAR PANTALLA ERROR 404
            Log::error("Ha ocurrido un problema en el hash al obtener cartola del Ciudadano.");
            return [
                'estado' => '0',
                'msg' => 'Advertencia: Servicio de Registo Civil con problemas de comunicacion o error no codificable'
            ];
        }
        $data = null;
        try {
            /*
             * $verificar->estado
             * return [
                    'estado' => '0',
                    'msg' => 'Advertencia: Servicio de Registo Civil con problemas de comunicacion o error no codificable'
                ];
             */
            $hashDecode = base64_decode($hash);
            $arrayHash = explode('&', $hashDecode);
            if(!isset($arrayHash[0])){
                Log::error("Ha ocurrido un problema en el hash al obtener el cuidador: " . $hashDecode);
            }
            $runCiudadano = $arrayHash[0];
            $hashBd = $arrayHash[1];

            $ciudadano = Personas::findByRun($runCiudadano);
            if (is_numeric($ciudadano)) {
                if ($ciudadano < 0) {
                    throw (new ExcepcionConsistencia("PERSONA NO EN BD Y RUNIFICADOR CAIDO",
                        Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
                }

            }
            $ciudadano->ESTADO_CUIDADORAS = 0;

            $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR',$runCiudadano)
                            ->where('ID_RUN_FECHA_ORIGEN_ESTADO',$hashBd)
                            ->where('ESTADO', Constante::ESTADO_1_ACTIVO)
                            ->first();
            if($qrCuidadoras){
                $ciudadano->ESTADO_CUIDADORAS = 1;
                $ciudadano->ROL_CUIDADORAS = 1;
            }

            $estampaTiempo = date('Y-m-d H:i:s');

            $logConsultaQrCuidadoras = new LogConsultaQrCuidadoras();

            $logConsultaQrCuidadoras->ID = Sequence::nextValue('SEQ_LOG_CONSULTA_QR_CUIDADORAS');
            $logConsultaQrCuidadoras->RUN_USUARIO = $runUsuario;
            $logConsultaQrCuidadoras->RUN_CUIDADOR = $runCiudadano;
            $logConsultaQrCuidadoras->ESTADO_CUIDADOR = isset($ciudadano->ESTADO_CUIDADORAS)&&$ciudadano->ESTADO_CUIDADORAS==1?1:0;
            $logConsultaQrCuidadoras->HASH = $hash;
            $logConsultaQrCuidadoras->FECHA = $estampaTiempo;

            if ($logConsultaQrCuidadoras->save()) {
                DB::connection()->getPdo()->commit();
            }


            $data = $ciudadano;
        } catch (Exception $ex) {
            Log::info('Error Clase Validar QR');
            Log::info(print_r($ex->getMessage(),true));
            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }
        return $data;
    }

    public static function generarQR($runCuidadoras, $imagen = false)
    {
        $fechaHora = date('Y-m-d H:i:s');


        $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR', $runCuidadoras)
            ->where('ESTADO',Constante::ESTADO_1_ACTIVO)
            ->first();

        if(!$qrCuidadoras){
            return 0;
        }
        $hashMd5 = base64_encode($runCuidadoras.'&'.$qrCuidadoras->ID_RUN_FECHA_ORIGEN_ESTADO);

        if($imagen){
            $qrcode = QrCode::format('png')->size(350)->generate($hashMd5);
        } else {
            //TODO CAMBIAR RUTA DINAMICA POR RSHMUNICIPAL
            //$qrcode = base64_encode(QrCode::format('png')->size(100)->generate(asset('/api/validacuidadores').'/'.$hashMd5));
            $qrcode = base64_encode(QrCode::format('png')->size(350)->generate($hashMd5));
        }

        return $qrcode;
    }


    public static function obtenerDatosCuidadores($runCiudadano)
    {   try {
        $ciudadano = Personas::findByRun($runCiudadano, true);

        if (is_numeric($ciudadano)) {
            if ($ciudadano < 0) {
                throw (new ExcepcionConsistencia("PERSONA NO EN BD Y RUNIFICADOR CAIDO",
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
            }
        }

        $ciudadano->ESTADO_CUIDADORAS = 0;

        $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR',$runCiudadano)->first();
        if($qrCuidadoras){
            $ciudadano->ESTADO_CUIDADORAS = 1;
            $ciudadano->ROL_CUIDADORAS = 1;
        }

        } catch (Exception $ex) {
        throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
            Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $ciudadano;
    }

    public static function validarRunNSerie($input)
    {
        $runUsuario = $input['run-usuario'];
        $runCiudadano = $input['run-ciudadano'];
        $numeroSerie = $input['numero-serie'];
        $data = null;
        try {

            $ciudadano = Personas::findByRun($runCiudadano);
            if (is_numeric($ciudadano)) {
                if ($ciudadano < 0) {
                    throw (new ExcepcionConsistencia("PERSONA NO EN BD Y RUNIFICADOR CAIDO",
                        Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
                }

            }
            $ciudadano->ESTADO_CUIDADORAS = 0;

            $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR',$runCiudadano)
                ->where('ESTADO', Constante::ESTADO_1_ACTIVO)
                ->first();
            if($qrCuidadoras){
                $ciudadano->ESTADO_CUIDADORAS = 1;
                $ciudadano->ROL_CUIDADORAS = 1;
            }

            $estampaTiempo = date('Y-m-d H:i:s');

            $logConsultaQrCuidadoras = new LogConsultaQrCuidadoras();

            $logConsultaQrCuidadoras->ID = Sequence::nextValue('SEQ_LOG_CONSULTA_QR_CUIDADORAS');
            $logConsultaQrCuidadoras->RUN_USUARIO = $runUsuario;
            $logConsultaQrCuidadoras->RUN_CUIDADOR = $runCiudadano;
            $logConsultaQrCuidadoras->ESTADO_CUIDADOR = isset($ciudadano->ESTADO_CUIDADORAS)&&$ciudadano->ESTADO_CUIDADORAS==1?1:0;
            $logConsultaQrCuidadoras->HASH = "Qr-Cedula";
            $logConsultaQrCuidadoras->FECHA = $estampaTiempo;

            if ($logConsultaQrCuidadoras->save()) {
                DB::connection()->getPdo()->commit();
            }


            $data = $ciudadano;
        } catch (Exception $ex) {
            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }
        return $data;
    }

    public static function obtenerRunes(){

        try {
            $qrCuidadoras = QrCuidadoras::where('AUTOMATICO', 100)->where('ESTADO',1)->get();

        } catch (Exception $ex) {
            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $qrCuidadoras;
    }

    public static function actualizaEstadoRunes($runCiudadano){

        try {
            $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR', $runCiudadano)->where('ESTADO',1)
                ->first();
            if($qrCuidadoras){
                $qrCuidadoras->AUTOMATICO = 1;
                $qrCuidadoras->ESTADO = 1;

                if ($qrCuidadoras->save()){
                    return 1;
                }
            }else{

                return 0;
            }

        } catch (Exception $ex) {
            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

    }

}
