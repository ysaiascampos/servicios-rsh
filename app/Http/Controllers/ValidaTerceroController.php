<?php

namespace App\Http\Controllers;

use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\JsonMapper;
use App\Http\ResponseMappers\ValidacionSolicitudTerceroMapper;
use App\Http\ResponseMappers\ValidaTerceroMapper;
use App\Models\Solicitud;
use App\Models\ValidaSolicitudTercero;
use App\Clases\ValidacionSolicitudTercero;
use App\Http\Inputs\ValidacionTerceroSolicitudInput;

use App\Utiles;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ValidaTerceroController extends Controller
{
    public function show($runCiudadano, $idSolicitud)
    {
        try {
            $lista = ValidaSolicitudTercero::getSolicitud($runCiudadano, $idSolicitud);
            return new JsonMapper($lista);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function showListaSolicitudes($runCiudadano)
    {
        try {
            $lista = [];
            $solicitudes = ValidaSolicitudTercero::listSolicitudes($runCiudadano);
            if (!empty($solicitudes)) {
                foreach ($solicitudes as $solicitud) {
                    $lista[] = new ValidaTerceroMapper($solicitud);
                }
            }
            return new JsonMapper($lista);

        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function update($idSolicitud)
    {
        try {
            $input = new ValidacionTerceroSolicitudInput(request()->all());
            $response = ValidacionSolicitudTercero::guardarSolicitud($input, $idSolicitud);
            return new JsonMapper($response);
        } catch (Exception $ex) {

            return $this->handleException($ex);
        }
    }

    public function showSolicitudes($idSolicitud)
    {
        try {
            $lista = [];
            $validaSolicitudTercero = ValidacionSolicitudTercero::showSolicitudes($idSolicitud);
            if (!empty($validaSolicitudTercero)) {
                return new ValidacionSolicitudTerceroMapper($validaSolicitudTercero);
            } else {
                return new JsonMapper($lista);
            }
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function updateValidacion()
    {
        try {
            $this->validarParametros(request()->all());
            $input=request()->all();
            $validacion = ValidacionSolicitudTercero::ActualizarValidacion($input);
            return json_encode($validacion);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * @throws ExcepcionValidacion
     */
    private function validarParametros( $parametros)
    {
        $reglas = [
            'accion'=>'required|nullable|numeric',
            'idValidacion'=>'required|nullable|numeric',
            'idSolicitud' => 'required|nullable|numeric',
            'idTipoSolicitud' => 'required|nullable|numeric',

        ];
        $mensajes = [
            'accion.numeric' => 'Acción no valida.',
            'accion.required'=> 'Acción es requerido para procesar la solicitud.',
            'idValidacion.numeric' => 'Id validación no valido.',
            'idValidacion.required' => 'Id validación es requerido para procesar la solicitud.',
            'idSolicitud.numeric' => 'Id solicitud no valido.',
            'idSolicitud.required' => 'Id solicitud es requerido para procesar la solicitud.',
            'idTipoSolicitud.numeric' => 'Tipo solicitud no valido.',
            'idTipoSolicitud.required' => 'Tipo solicitud es requerido para procesar la solicitud.',
        ];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }

    public static function validaMenores($titulares)
    {
        $metodo = 'POST';
        $url = '/datos-globales';
        $lista = [];
        $titularRun = [];
        foreach ($titulares as $titular) {
            $titularRun[] = $titular->run;
        }
        foreach ($titulares as $titular) {
            $datosFormulario['origen-registro'] = Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA;
            $datosFormulario['run-ciudadano'] = $titular->run;

            $consultaServicio = Utiles::consumoConGuzzle($metodo, $url, $datosFormulario);
            $datosGlobales = $consultaServicio->data;
            $mensaje = false;
            $datosContacto = $datosGlobales['datos-de-contacto'];
            $composicion = $datosGlobales['grupo-familiar-asignacion'] ? $datosGlobales['grupo-familiar-asignacion']['composicion'] : null;

            //1. El adulto a incorporar tiene RSH?
            if ($composicion) {
                $runAdultosAdicionales = [];
                $contadorMenores = 0;
                $contadorAdultoAdicional = 0;

                //contador menores y adultos en hogar
                foreach ($composicion as $integrante) {
                    $persona = $integrante['persona'];
                    if ($persona['edad'] < Constante::EDAD_18_MAYOR_DE_EDAD
                    && !in_array($persona['run'],$titularRun)) {
                        $contadorMenores++;
                    } else {
                        if (!in_array($persona['run'],$titularRun)) {
                            $contadorAdultoAdicional++;
                            array_push($runAdultosAdicionales, $persona['run']);
                        }
                    }
                }

                //2.- En el hogar que este adulto deja, hay menores?
                if ($contadorMenores > 0) {
                    //3. En el hogar que este adulto deja, hay otros adultos
                    if (count($runAdultosAdicionales) > 0) {
                        //4. Al menos 1 adulto de los que permanecerá no posee solicitudes de ingreso, incorporación o desvinculación pendientes o aprobadas posterior a fecha de cierre?
                        $tiposolicitud = [
                            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE
                        ];
                        $estadoSolicitud = [
                            ConstanteEstado::ESTADO_SOLICITUD_5_APROBADO,
                            ConstanteEstado::ESTADO_SOLICITUD_7_RECHAZADA,
                            ConstanteEstado::ESTADO_SOLICITUD_9_DESISTIDA,
                            ConstanteEstado::ESTADO_SOLICITUD_11_EN_DIGITACION
                        ];
                        $validaSolicitudPendiente = self::validaSolicitudPendiente($runAdultosAdicionales, $tiposolicitud);
                        if ($validaSolicitudPendiente) {
                            $mensaje = true;
                        }
                    } else {
                        $mensaje = true;
                    }
                }

                if ($mensaje) {
                    $lista[$datosContacto['run']] = [
                        'run' => $datosContacto['run'],
                        'dv' => $datosContacto['dv'],
                        'nombres' => $datosContacto['nombres'],
                        'apellido-paterno' => $datosContacto['apellido-paterno'],
                        'apellido-materno' => $datosContacto['apellido-materno'],
                        'error' => Constante::MENSAJE_ERROR_MENOR_GRUPO_FAMILIAR_VIGENTE
                    ];
                }
            }
        }

        return $lista;
    }

    public static function validaMenoresIdSolicitud($idSolicitud){

        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_PADRE_SOLICITUD','TIPO_VALIDACION_TERCEROS','ID_TIPO_SOLICITUD')
                ->first();
            if (empty($solicitud)) {
                $msg = 'No se encuentra la solicitud ' . $idSolicitud;
                return Response::json(['errors' => [$msg]], Constante::STATUS_401_ERROR_UNAUTHORIZED);
            }
            $solicitudVigenteValidacion = self::solicitudVigenteValidacionTercero($solicitud->ID_TIPO_SOLICITUD);

            if($solicitudVigenteValidacion == false){
                $msg = 'Este metodo de validación no se encuentra disponible para este tipo de solicitud.';
                return Response::json(['errors' => [$msg]], Constante::STATUS_401_ERROR_UNAUTHORIZED);
            }
        }

        $validaSolicitudTerceros = null;
        $titulares=[];
        $menorgrupofamiliar = [];
        try {
            $dataSolicitudes = Solicitud::where('ID_PADRE_SOLICITUD', $solicitud->ID_PADRE_SOLICITUD)
                ->whereNotIn('id_estado', ConstanteEstado::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->select('ID_SOLICITUD')
                ->get();

            $newTramite= new TramiteController;
            foreach ($dataSolicitudes as $data){
                $dataTramite= json_decode(json_encode($newTramite->show($data->ID_SOLICITUD)->campos['personas-asociadas']));
                $titular=$dataTramite->titular;
                $titulares[]= $titular;
            }

            $menorgrupofamiliar['existe-menor'] = false;
            $menorgrupofamiliar['lista-integrante-con-menor'] = ValidaTerceroController::validaMenores($titulares);

            if ($menorgrupofamiliar['lista-integrante-con-menor'] != null){
                $menorgrupofamiliar['existe-menor'] = true;
            }

            return $menorgrupofamiliar;

        } catch (\http\Exception $ex) {
            throw $ex;
        }
    }

    public static function validaMenoresRun($runCiudadano)
    {
        $menorgrupofamiliar = [];
        $menorgrupofamiliar['existe-menor'] = false;

        try {
            $metodo = 'POST';
            $url = '/datos-globales';
            $datosFormulario['origen-registro'] = Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA;
            $datosFormulario['run-ciudadano'] = $runCiudadano;
            $consultaServicio = Utiles::consumoConGuzzle($metodo, $url, $datosFormulario);

            $datosGlobales = $consultaServicio->data;
            $bloqueoMenor= false;
            $composicion = $datosGlobales['grupo-familiar-asignacion'] ? $datosGlobales['grupo-familiar-asignacion']['composicion'] : null;

            if ($composicion) {
                $runAdultosAdicionales = [];
                $contadorMenores = 0;
                $contadorAdultoAdicional = 0;

                foreach ($composicion as $integrante) {
                    $persona = $integrante['persona'];
                    if ($persona['edad'] < Constante::EDAD_18_MAYOR_DE_EDAD) {
                        $contadorMenores++;
                    } else {
                        if ($persona['run'] != $runCiudadano) {
                            $contadorAdultoAdicional++;
                            array_push($runAdultosAdicionales, $persona['run']);
                        }
                    }
                }

                if ($contadorMenores > 0) {
                    if (count($runAdultosAdicionales) > 0) {
                        $tiposolicitud = [
                            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE
                        ];
                        $validaSolicitudPendiente = self::validaSolicitudPendiente($runAdultosAdicionales,
                            $tiposolicitud);
                        if ($validaSolicitudPendiente) {
                            $bloqueoMenor = true;
                        }
                    } else {
                        $bloqueoMenor = true;
                    }
                }

                if ($bloqueoMenor) {
                    $menorgrupofamiliar['existe-menor'] = true;
                    $menorgrupofamiliar['error'] = Constante::MENSAJE_ERROR_MENOR_GRUPO_FAMILIAR_VIGENTE;
                }
            }

            return $menorgrupofamiliar;

        } catch (\http\Exception $ex) {
            throw $ex;
        }
    }

    public static function solicitudVigenteValidacionTercero($tipoSolicitud)
    {
        $solicitudVigente = false;
        if (!is_null(env('SOLICITUD_VALIDA_TERCERO')) && strlen(env('SOLICITUD_VALIDA_TERCERO') > 0)) {
            $tipoSolicitudValidar = explode(',', env('SOLICITUD_VALIDA_TERCERO'));
            if (in_array($tipoSolicitud, $tipoSolicitudValidar)) {
                $solicitudVigente = true;
            }
        } else {
            log::error('No existe variable SOLICITUD_VALIDA_TERCERO configurada en ENV.');
        }
        return $solicitudVigente;
    }

    public static function validaSolicitudPendiente(
        $integrantes,
        $tipoSolicitud,
        $estadoSolicitud = [
            ConstanteEstado::ESTADO_SOLICITUD_5_APROBADO,
            ConstanteEstado::ESTADO_SOLICITUD_7_RECHAZADA,
            ConstanteEstado::ESTADO_SOLICITUD_9_DESISTIDA,
            ConstanteEstado::ESTADO_SOLICITUD_11_EN_DIGITACION
        ]
    ) {
        $exiteSolicitud = false;
        $ciudadanoSolicitud = [];
        $metodo = 'POST';
        $url = '/datos-globales';
        $datosFormulario['origen-registro'] = Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA;

        if (count($integrantes) > 0) {
            foreach ($integrantes as $integrante) {
                $datosFormulario['run-ciudadano'] = $integrante;
                $consultaServicio = Utiles::consumoConGuzzle($metodo, $url, $datosFormulario);
                $datosGlobales = $consultaServicio->data;
                $listadoSolicitudes = $datosGlobales['solicitudes'] ? $datosGlobales['solicitudes'] : [];
                if (count($listadoSolicitudes) > 0) {
                    foreach ($listadoSolicitudes as $solicitud) {
                        if (in_array($solicitud['id-tipo-solicitud'],
                                $tipoSolicitud) && !in_array($solicitud['id-estado'], $estadoSolicitud)) {
                            array_push($ciudadanoSolicitud, $integrante);
                        }
                    }
                }
            }
        }

        if (count($ciudadanoSolicitud) > 0) {
            $exiteSolicitud = true;
        }

        return $exiteSolicitud;
    }
}
