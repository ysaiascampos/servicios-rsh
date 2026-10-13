<?php

namespace App\Clases;

use App\Models\Respuestas as ModeloRespuestas;
use App\Utiles;

class Respuestas
{
    const TIPO_INPUT_RESPUESTA_1_SELECT = 1;
    const TIPO_INPUT_RESPUESTA_2_RADIO_BUTTON = 2;
    const TIPO_INPUT_RESPUESTA_3_CHECKBOX = 3;
    const TIPO_INPUT_RESPUESTA_4_TEXT = 4;

    public static function findAllActivePorCategoriaESOV($ciudadanoMunicipal = 'municipal')
    {
        $campoPregunta = 'PREGUNTA_' . strtoupper($ciudadanoMunicipal);
        $campoRespuesta = 'RESPUESTA_' . strtoupper($ciudadanoMunicipal);
        $datosDePreguntasRespuestas = null;
        $datosDeRespuestasMapeo = null;
        $datosDePreguntasRespuestas = ModeloRespuestas::findAllRespuetasJoinPreguntas();
        foreach ($datosDePreguntasRespuestas as $datosDePreguntaRespuesta) {
            $datosDePreguntaRespuesta = get_object_vars($datosDePreguntaRespuesta);
            if ($datosDePreguntaRespuesta['ID_PREG_PADRE'] == 'Y') {
                $datosDeRespuestasMapeo['preguntas']['o'][strtolower($datosDePreguntaRespuesta['CODIGO'])] = $datosDePreguntaRespuesta[$campoPregunta];
                $datosDeRespuestasMapeo['respuestas']['o'][strtolower($datosDePreguntaRespuesta['CODIGO'])][$datosDePreguntaRespuesta['VALOR']] = $datosDePreguntaRespuesta[$campoRespuesta];
                $datosDeRespuestasMapeo['tipo-respuestas']['o'][strtolower($datosDePreguntaRespuesta['CODIGO'])] = $datosDePreguntaRespuesta['TIPO_INPUT']; // 1=select; 2=radio button; 3=checkbox; 4=input text
            } else {
                $datosDeRespuestasMapeo['preguntas'][strtolower($datosDePreguntaRespuesta['ID_PREG_PADRE'])][strtolower($datosDePreguntaRespuesta['CODIGO'])] = $datosDePreguntaRespuesta[$campoPregunta];
                $datosDeRespuestasMapeo['respuestas'][strtolower($datosDePreguntaRespuesta['ID_PREG_PADRE'])][strtolower($datosDePreguntaRespuesta['CODIGO'])][$datosDePreguntaRespuesta['VALOR']] = $datosDePreguntaRespuesta[$campoRespuesta];
                $datosDeRespuestasMapeo['tipo-respuestas'][strtolower($datosDePreguntaRespuesta['ID_PREG_PADRE'])][strtolower($datosDePreguntaRespuesta['CODIGO'])] = $datosDePreguntaRespuesta['TIPO_INPUT']; // 1=select; 2=radio button; 3=checkbox; 4=input text
            }
        }

        return $datosDeRespuestasMapeo;
    }

    public static function obtenerTextoRespuestas($definicionPreguntas, $pregunta, $respuesta)
    {
        $salida = null;
        $esPreguntaIngreso = false;
        $identificadorModulo = substr($pregunta, 0, 1);
        if ($identificadorModulo == 'y') {
            $esPreguntaIngreso = true;
            $identificadorModulo = 'o';
        }

        if (isset($definicionPreguntas['tipo-respuestas'][$identificadorModulo][$pregunta])) {
            switch ($definicionPreguntas['tipo-respuestas'][$identificadorModulo][$pregunta]) {
                case self::TIPO_INPUT_RESPUESTA_1_SELECT:
                    if (is_null($respuesta)) {
                        $salida = 'Sin información'; // null
                    } elseif (isset($definicionPreguntas['respuestas'][$identificadorModulo][$pregunta][$respuesta])) {
                        $salida = $definicionPreguntas['respuestas'][$identificadorModulo][$pregunta][$respuesta];
                    } else {
                        $salida = 'Sin información.'; // 'Información inconsistente';
                    }
                    break;
                case self::TIPO_INPUT_RESPUESTA_2_RADIO_BUTTON:
                    if (is_null($respuesta)) {
                        $salida = 'Sin información'; // null
                    } elseif (isset($definicionPreguntas['respuestas'][$identificadorModulo][$pregunta][$respuesta])) {
                        $salida = $definicionPreguntas['respuestas'][$identificadorModulo][$pregunta][$respuesta];
                    } else {
                        $salida = 'Información inconsistente';
                    }
                    break;
                case self::TIPO_INPUT_RESPUESTA_3_CHECKBOX:
                    if (is_null($respuesta)) {
                        $salida = 'No'; // 'Sin información';
                    } elseif ($respuesta == 2) {
                        $salida = 'No';
                    } elseif ($respuesta == 1) {
                        $salida = 'Sí';
                    } else {
                        $salida = 'Información inconsistente';
                    }
                    break;
                case self::TIPO_INPUT_RESPUESTA_4_TEXT:
                    if (is_null($respuesta)) {
                        $salida = 'Sin información';
                    } else {
                        if ($esPreguntaIngreso) {
                            $salida = '$&nbsp;' . Utiles::ponePuntosNumero($respuesta);
                        } else {
                            $salida = $respuesta;
                        }
                    }
                    break;
                default:
                    $salida = $respuesta; // no pertenece a ningún tipo de input
            }
        } else { // key no definida
            $salida = $respuesta; // no pertenece a ningún tipo de input
        }
        return $salida;
    }

}
