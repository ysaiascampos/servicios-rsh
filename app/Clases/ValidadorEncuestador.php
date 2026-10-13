<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\Encuestador;
use App\Utiles;

class ValidadorEncuestador
{
    use ValidacionTrait;

    public function validarEncuestador($input, $fechaSolicitud): bool
    {
        if (!is_null($input->get('run-encuestador'))) {
            $camposValidar['run-encuestador'] = [
                'dato' => 'numero',
                'tipo' => 'between',
                'between' => Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC,
                'mensaje' => [
                    'requerido' => 'El campo RUN del encuestador es requerido',
                    'between' => 'El valor de RUN del encuestador debe estar entre %min% y %max%',
                    'dato' => 'El valor de RUN del encuestador debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['dv-encuestador'] = [
                'dato' => 'texto',
                'tipo' => 'in',
                'in' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'K'],
                'mensaje' => [
                    'requerido' => 'El campo DV del encuestador es requerido',
                    'in' => 'El valor de DV del encuestador debe estar entre %valores%',
                    'dato' => 'El valor de DV del encuestador debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['fecha-encuesta'] = [
                'tipo' => 'date_format',
                'date_format' => 'd/m/Y',
                'mensaje' => [
                    'requerido' => 'El campo fecha encuesta es requerido',
                    'date_format' => 'La fecha de encuesta no es una fecha con formato válido "d/m/Y"'
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'date_format' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];

            $this->validarArreglo($camposValidar, true, $input, null);
        }
        $encuestador = $input->get('run-encuestador');
        if (!empty($encuestador)) {
            if ($encuestador != env('RUTAPROBADOR','11111111')) {
                $tblEncuestador = Encuestador::where('enc_run', $encuestador)
                    ->first();
                $tblEncuestadorVigente = Encuestador::where('enc_run', $encuestador)
                    ->where('enc_activo', Constante::ESTADO_1_ACTIVO)
                    ->first();
                if (!$tblEncuestador) {
                    $this->collectError(
                        'No se ha encontrado al encuestador',
                        Constante::ERR_VALIDACION_ENCUESTADOR_NO_ENCONTRADO,
                        'encuestador',
                        null);
                } elseif (!$tblEncuestadorVigente) {
                    $this->collectError(
                        'Encuestador, no esta vigente en el sistema',
                        Constante::ERR_VALIDACION_ENCUESTADOR_NO_VIGENTE,
                        'encuestador',
                        null);
                }
            }
            $runEncuestador = $input->get('run-encuestador') . '-' . $input->get('dv-encuestador');

            /**
             * Regla: Fecha de encuesta no puede ser mayor a fecha actual, ni menor a fecha de creación de solicitud
             */
            $fechaEncuesta = $input->get('fecha-encuesta');

//            $solicitud = Solicitud::find($idSolicitud);
            $fechaCreacionSolicitud = date('d/m/Y', strtotime($fechaSolicitud));

            $tempFecha = explode('/', $fechaEncuesta); // dia/mes/año
            if (!isset($tempFecha[2])) {
                $this->collectError(
                    'La fecha de encuesta no es una fecha con formato válido',
                    Constante::ERR_VALIDACION_ENCUESTADOR_FORMATO_FECHA_NO_VALIDO,
                    'encuestador',
                    null);
            } elseif (!checkdate($tempFecha[1], $tempFecha[0], $tempFecha[2])) {
                $this->collectError(
                    'La fecha de encuesta no es una fecha con formato válido',
                    Constante::ERR_VALIDACION_ENCUESTADOR_FORMATO_FECHA_NO_VALIDO,
                    'encuestador',
                    null);
            }

            if(!empty($fechaEncuesta)) {
                $fechaEncuestaNum = Utiles::fechaDateToNumber($fechaEncuesta);
                $fechaCreacionSolicitudNum = Utiles::fechaDateToNumber($fechaCreacionSolicitud);
                $fechaActualNum = Utiles::fechaDateToNumber(date('d/m/Y'));

                if ($fechaEncuestaNum > $fechaActualNum) {
                    $this->collectError(
                        'La fecha de encuesta (' . $fechaEncuesta . ') debe ser menor o igual a la fecha actual.',
                        Constante::ERR_VALIDACION_ENCUESTADOR_FECHA_ENCUESTA_MENOR_ACTUAL,
                        'encuestador',
                        null);
                }
                if ($fechaEncuestaNum < $fechaCreacionSolicitudNum) {
                    $this->collectError(
                        'La fecha de encuesta (' . $fechaEncuesta . ') debe ser mayor o igual a la fecha de creación de la solicitud (' . $fechaCreacionSolicitud . ').',
                        Constante::ERR_VALIDACION_ENCUESTADOR_FECHA_ENCUESTA_DEBE_SER_MAYOR_CREACION,
                        'encuestador',
                        null);
                }
            }
        }
//        $g->grupfami_run_encuestador = Input::get('run_encuestador');
//        $g->grupfami_fecha_modificacion = date('Ymd');
//        $g->save();

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
