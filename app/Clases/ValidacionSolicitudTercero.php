<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\ValidaTerceroController;
use App\Models\Solicitud;
use App\Models\ValidaSolicitudTercero;
use App\Http\Inputs\ValidacionTerceroSolicitudInput;
use http\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\Log;
use Throwable;

class ValidacionSolicitudTercero
{
    const ACCION_RECHAZAR_SOLICITUD = 0;
    const ACCION_ACEPTAR_SOLICITUD = 1;
    const ACCION_VALIDA = [self::ACCION_RECHAZAR_SOLICITUD, self::ACCION_ACEPTAR_SOLICITUD];

    const ACEPTAR_SOLICITUD = 1;
    const RECHAZAR_SOLICITUD = 2;

    const ESTADO_PENDIENTE = Constante::VALIDACION_TERCERO_ESTADO_0_PENDIENTE;
    const ESTADO_APROBADO = Constante::VALIDACION_TERCERO_ESTADO_1_APROBADO;
    const ESTADO_RECHAZADO = Constante::VALIDACION_TERCERO_ESTADO_2_RECHAZADO;
    const ESTADO_DESISTIDO = Constante::VALIDACION_TERCERO_ESTADO_3_DESISTIDO;

    public $validacion;

    /**
     * @return ValidaSolicitudTercero|Builder|mixed|null
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Throwable
     */

    public static function guardarSolicitud(ValidacionTerceroSolicitudInput $input = null, $idSolicitud = null)
    {
        $solicitud = null;
        $tipoSolicitud = null;
        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia('No se encuentra la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        }
        if (!empty($input)) {
            $tipoSolicitud = $input->tipoSolicitud->getTipo();
            if (!empty($solicitud)) {
                if ($tipoSolicitud != $solicitud->ID_TIPO_SOLICITUD) {
                    throw ExcepcionValidacion::new("El campo 'tipo-solicitud' no corresponde a la solicitud")
                        ->setOrigen('tipo-solicitud');
                }
            }
        }
        DB::beginTransaction();
        try {
            $body = $input->get('tipo-solicitud')->get($input->get('tipo-solicitud')->getTipo());
            $tipoValidaSolicitudTerceros = $body->get('tipo-validar-solicitud');
            $integrantes = $body->get('integrantes');
            $accion = $input->get('accion');
            $borrador = $input->get('borrador');

            if(count($integrantes)) {
                foreach ($integrantes as $integrante) {
                    $idSolicitud = $integrante->get('id-solicitud');
                    $runtitular = $integrante->get('run-titular');
                    $email = strtolower($integrante->get('email'));
                    if ($borrador == Constante::BORRADOR_NULL && $email == "") {
                        throw new ExcepcionConsistencia('El campo correo electrónico es requerido ' ,
                            Constante::ERR_VALIDACION_CAMPO_REQUERIDO);
                    }
                    $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)->first();
                    if (empty($solicitud)) {
                        throw new ExcepcionConsistencia('No se encuentra la solicitud ' . $idSolicitud,
                            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
                    }
                    $validaSolicitudTerceros_new = ValidaSolicitudTercero::where('IDSOLICITUD', $idSolicitud)
                        ->where('PER_RUN', $runtitular)->first();
                    if (empty($validaSolicitudTerceros_new)) {
                        $id_valida_solicitud_terceros = Sequence::nextValue('SEQ_VALIDA_SOLICITUD_TERCEROS');
                        $validaSolicitudTerceros_new = new ValidaSolicitudTercero();
                        $validaSolicitudTerceros_new->ID = $id_valida_solicitud_terceros;
                    }
                    $validaSolicitudTerceros_new->IDSOLICITUD = $solicitud->ID_SOLICITUD;
                    $validaSolicitudTerceros_new->PER_RUN = $runtitular;
                    $validaSolicitudTerceros_new->EMAIL = $email;
                    $validaSolicitudTerceros_new->ENVIO_EMAIL_AVISO_SOLICITUD = self::ESTADO_PENDIENTE;
                    $validaSolicitudTerceros_new->ESTADO_VALIDACION = self::ESTADO_PENDIENTE;
                    $validaSolicitudTerceros_new->ENVIO_EMAIL_VALIDACION = self::ESTADO_PENDIENTE;
                    $validaSolicitudTerceros_new->FECHA_VALIDACION = null;
                    $validaSolicitudTerceros_new->save();
                    $solicitud->TIPO_VALIDACION_TERCEROS = $tipoValidaSolicitudTerceros;
                    $solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
                    if($borrador == Constante::BORRADOR_NULL &&
                        $accion == Constante::ACCION_BORRADOR_PENDIENTE_FIRMA_DIGITAL_19) {
                        $solicitud->ID_ESTADO = Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL;
                        try {
                            EnvioMail::enviaMailAvisoValidacion($solicitud, $validaSolicitudTerceros_new);
                        } catch (Throwable $e) {
                            Log::info('Falló mail, en Aprobar validacion: ' . $validaSolicitudTerceros_new->ID . PHP_EOL . $e);
                        }

                    }
                    $solicitud->save();
                }
            } else {
                $solicitud->TIPO_VALIDACION_TERCEROS = $tipoValidaSolicitudTerceros;
                $solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
                $solicitud->save();
            }

            if($tipoSolicitud == Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE){
                $getVxTSolicitud = ValidaSolicitudTercero::where('IDSOLICITUD',$idSolicitud)->first();

                $updateSolicitud = Solicitud::where('ID_SOLICITUD',$idSolicitud)->first();
                $updateSolicitud->ID_USUARIO_MODIFICACION = $getVxTSolicitud->PER_RUN;
                $updateSolicitud->save();
            }

            DB::commit();
            return [
                'id-solicitud' => intval($solicitud->ID_SOLICITUD),
                'id-padre-solicitud' => intval($solicitud->ID_PADRE_SOLICITUD),
                'id-tipo-solicitud' => intval($solicitud->ID_TIPO_SOLICITUD),
                'id-sub-tipo-solicitud' => $solicitud->ID_SUB_TIPO_SOLICITUD,
            ];
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }


    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Throwable
     */
    public static function ActualizarValidacion($input = null)
    {
        $accion = $input['accion'];
        $idValidacion = $input['idValidacion'];
        $idSolicitud = $input['idSolicitud'];
        $tipoSolicitud = $input['idTipoSolicitud'];

        if (!empty($accion)) {
            if (!in_array($accion, self::ACCION_VALIDA)) {
                throw new ExcepcionConsistencia('Acción no valida para la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        }
        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia('No se encuentra la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        }
        if (!empty($solicitud)) {
            if ($tipoSolicitud != $solicitud->ID_TIPO_SOLICITUD) {
                throw ExcepcionValidacion::new("El campo 'tipo-solicitud' no corresponde a la solicitud")
                    ->setOrigen('tipo-solicitud');
            }
        }
        if (!empty($idValidacion)) {
            $solicitudValidacion = ValidaSolicitudTercero::where('ID', $idValidacion)->first();
            if (empty($solicitudValidacion)) {
                throw new ExcepcionConsistencia('No se encuentra validacion asociada al ID ' . $idValidacion,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        }

        DB::beginTransaction();
        try {
            $textAccion = "";
            //Actualizacion datos tabla VALIDACION TERCERO
            if ($accion == self::ACCION_ACEPTAR_SOLICITUD) {
                $solicitudValidacion->ESTADO_VALIDACION = self::ACEPTAR_SOLICITUD;
                $textAccion = "aprobado";
            } else {
                $solicitudValidacion->ESTADO_VALIDACION = self::RECHAZAR_SOLICITUD;
                $textAccion = "rechazado";
            }
            $solicitudValidacion->FECHA_VALIDACION = date('Ymd');
            $solicitudValidacion->save();
            $nombreSolicitud ='';
            if (!empty($idSolicitud)) {
                $solicitudValidacionRechazo = ValidaSolicitudTercero::where('IDSOLICITUD', $idSolicitud)
                    ->where('ESTADO_VALIDACION', self::ESTADO_RECHAZADO)->first();

                $tramite = Tramite::instanciarSubClase(null, $idSolicitud);
                $nombreSolicitud = $tramite->nombreSolicitud;
                if ($solicitudValidacionRechazo) {
                    $tramite->setAprobacionTercero();
                    $tramite->setAccion(Constante::ACCION_DESISTIR_VA_ESTADO_9);
                    $tramite->validacionporTerceros=true;
                    $tramite->Desistir();
                    ValidaSolicitudTercero::where('IDSOLICITUD', $idSolicitud)
                          ->where('ESTADO_VALIDACION', self::ESTADO_PENDIENTE)
                          ->update(['ESTADO_VALIDACION' => self::ESTADO_DESISTIDO]);

                    $updateSolicitud = Solicitud::findOrFail($idSolicitud);

                    if (!in_array($updateSolicitud->ID_ESTADO, [5,7,9])) {
                        $updateSolicitud->FECHA_MODIFICACION = now();
                        $updateSolicitud->ID_ESTADO = ConstanteEstado::ESTADO_SOLICITUD_7_RECHAZADA;
                        $updateSolicitud->VIGENTE = Constante::VIGENTE_1_VIGENTE;
                        $updateSolicitud->save();
                    }

                    if($tipoSolicitud == Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE){
                        $updateVxTSolicitud = ValidaSolicitudTercero::where('IDSOLICITUD',$idSolicitud)->first();
                        $updateVxTSolicitud->PER_RUN = $tramite->titular->PER_RUN;
                        $updateVxTSolicitud->save();
                    }

                    $solicitudValidacionRechazodos = ValidaSolicitudTercero::where('IDSOLICITUD', $idSolicitud)
                        ->get();
                    if ($solicitudValidacionRechazodos) {
                        try {
                            EnvioMail::enviaMailValidacionSolicitante($solicitud, $solicitudValidacion, $textAccion,$nombreSolicitud);
                        } catch (Throwable $e) {
                            Log::info('Falló mail, en Aprobar validacion: ' . $idValidacion . PHP_EOL . $e);
                        }
                        foreach ($solicitudValidacionRechazodos as $solicitudValidacionRechazodo) {
                            if($solicitudValidacion->PER_RUN != $solicitudValidacionRechazodo->PER_RUN){
                                try {
                                    EnvioMail::enviaMailValidacion($solicitudValidacion, $solicitudValidacionRechazodo,
                                        $textAccion, $nombreSolicitud);
                                } catch (Throwable $e) {
                                    Log::info('Falló mail, en Rechazar validacion: ' . $idValidacion . PHP_EOL . $e);
                                }
                            }
                        }
                    }

                } else {
                    $solicitudValidacionAprobado = ValidaSolicitudTercero::where('IDSOLICITUD', $idSolicitud)
                        ->where('ESTADO_VALIDACION','<>', self::ESTADO_APROBADO)->first();
                    if (empty($solicitudValidacionAprobado)) {
                        $tramite->setAprobacionTercero();
                        $tramite->setAccion(Constante::ACCION_APROBAR_VA_ESTADO_5);
                        $tramite->Aprobar();

                        //actualizar solicitud
                        $idsolicitudUpdate = $tramite->solicitud->ID_SOLICITUD;

                        $updateSolicitud = Solicitud::findOrFail($idsolicitudUpdate);
                        if (!in_array($updateSolicitud->ID_ESTADO, [5,7,9])) {
                            //$updateSolicitud->ID_USUARIO_MODIFICACION = $input['runCiudadanoAsociado'];
                            $updateSolicitud->FECHA_MODIFICACION = now();
                            $updateSolicitud->ID_ESTADO = ConstanteEstado::ESTADO_SOLICITUD_5_APROBADO;
                            $updateSolicitud->VIGENTE = Constante::VIGENTE_1_VIGENTE;
                            $updateSolicitud->save();
                        }

                        if($tipoSolicitud == Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE){
                            $updateVxTSolicitud = ValidaSolicitudTercero::where('IDSOLICITUD',$idsolicitudUpdate)->first();
                            $updateVxTSolicitud->PER_RUN = $tramite->titular->PER_RUN;
                            $updateVxTSolicitud->save();

                            $updateSolicitud = Solicitud::where('ID_SOLICITUD',$idsolicitudUpdate)->first();
                            $updateSolicitud->ID_USUARIO_MODIFICACION = $tramite->titular->PER_RUN;
                            $updateSolicitud->save();
                        }
                    }
                    try {
                        EnvioMail::enviaMailValidacionSolicitante($solicitud, $solicitudValidacion, $textAccion,$nombreSolicitud);
                    } catch (Throwable $e) {
                        Log::info('Falló mail, en Aprobar validacion: ' . $idValidacion . PHP_EOL . $e);
                    }
                }

            }
            DB::commit();
            return [
                'id-solicitud' => intval($solicitud->ID_SOLICITUD),
                'id-padre-solicitud' => intval($solicitud->ID_PADRE_SOLICITUD),
                'id-tipo-solicitud' => intval($solicitud->ID_TIPO_SOLICITUD),
                'id-sub-tipo-solicitud' => $solicitud->ID_SUB_TIPO_SOLICITUD,
            ];
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public static function showSolicitudes($idSolicitud)
    {
        $solicitud = null;
        $tipoSolicitud = null;
        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia('No se encuentra la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        }
        $solicitudes = null;
        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->whereNotIn('id_estado', ConstanteEstado::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->select('ID_PADRE_SOLICITUD','ID_TIPO_SOLICITUD','TIPO_VALIDACION_TERCEROS')->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia('No se encuentra la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        }
        $validaSolicitudTerceros = null;
        $listaSolicitud = array();
        $respuesta = ['tipo-validacion-tercero' => false,
                      'solicitudes-integrantes' => [],
                      'existe-menor' => false,
                      'lista-integrante-con-menor' => []
        ];

        try {

            $dataSolicitudes = Solicitud::where('ID_PADRE_SOLICITUD', $solicitud->ID_PADRE_SOLICITUD)
                ->whereNotIn('id_estado', ConstanteEstado::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->select('ID_SOLICITUD')
                ->get();

            $titulares = [];

            $newTramite = new TramiteController;
            foreach ($dataSolicitudes as $data) {
                $dataTramite = json_decode(json_encode($newTramite->show($data->ID_SOLICITUD)->campos['personas-asociadas']));
                $titular = $dataTramite->titular;
                $titulares[] = $titular;
            }

            $validacionMenores = ValidaTerceroController::validaMenores($titulares);

            if (count($validacionMenores) >  0){
                $respuesta['existe-menor'] = true;
                $respuesta['lista-integrante-con-menor'] = $validacionMenores;
            }

            $tipoValidacionTercero = 0;

            foreach ($dataSolicitudes as $dataSolicitud) {
                $validaSolicitudTerceros = ValidaSolicitudTercero::where('IDSOLICITUD', $dataSolicitud->ID_SOLICITUD)
                    ->orderBy('id')
                    ->get();
                foreach ($validaSolicitudTerceros as $validaSolicitudTercero) {
                    $validaSolicitudTercero->TIPO_VALIDACION_TERCEROS = $solicitud->TIPO_VALIDACION_TERCEROS;
                    $listaSolicitud[$dataSolicitud->ID_SOLICITUD][] = $validaSolicitudTercero;
                    $tipoValidacionTercero =  $validaSolicitudTercero->TIPO_VALIDACION_TERCEROS;
                }
            }

            if (count($listaSolicitud) > 0){
                $respuesta['tipo-validacion-tercero'] = $tipoValidacionTercero;
                $respuesta['solicitudes-integrantes'] = $listaSolicitud;
            }

        } catch (Exception $ex) {
            throw $ex;
        }

        return $respuesta;
    }

    public function generaBody($idTipoSolicitud, $data, $accion)
    {
        $this->campos = [
            'run-ciudadano' => $data['persona']['run'],
            'run-usuario' => env('RUTAPROBADOR'),
            'origen-registro' => Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL,
            'canal' => Constante::CANAL_1_STRING_MINUS_MUNICIPAL,
            'accion' => $accion ,
            'observacion' => 'Aprobación Automatica IngresoRSH Validacion Tercero',
            'tipo-solicitud' => null
        ];
        switch ($idTipoSolicitud) {
            case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                if(isset($data['datos-solicitud'])){
                    foreach ($data['datos-solicitud'] as $integrante) {
                        $this->generaBodyIntegranteGrupoFamiliar($idTipoSolicitud, $integrante);
                    }
                }
                break;
            default:
                break;
        }
        if(!isset($this->campos['tipo-solicitud'])){
            $mensaje = 'El cuerpo del tramite solicitud: '.$data['solicitud']['id-solicitud'].' se encuentra vacio';
            Log::channel('aprobacion_' . $idTipoSolicitud)->warning($mensaje);
        }
    }


}
