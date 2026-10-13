<?php

namespace App\Clases;

use App\Constantes\Constante;

class ValidadorEducacion
{
    use ValidacionTrait;

    public function validarRespuestasEducacion($integrante): bool
    {
        $titular = $integrante->runTitular;
        if ($integrante->get('e1') == Constante::RESPUESTA_E1_2_NO) {

            $e3 = $integrante->get('e3');
            $e4 = $integrante->get('e4');
            $edad = $integrante->edad;

            /* validaciones */
            //if ($e4 == 0 && $e3 > 0) {
            //    $this->collectError('Si e4 es 0 e3 no puede ser mayor a cero.', 'integrante ' . $integrante->get('run'). '-' . Utiles::dv($integrante->get('run')));
            //}

            if (in_array($e4, [Constante::RESPUESTA_E4_0_NINGUNO,]) && $e3 != Constante::RESPUESTA_E3_0_NO_APLICA) {
                $this->collectError('Si respuesta e4 es ' . $e4 . ' e3 debe ser 0.',
                    Constante::ERR_NEGOCIO_E3_NO_APLICA, 'e3', $titular);
            }
            /*
            $e3min = 1;
            $e3max = 120;
            $edadmin = 1;
            */
            $e3min = null;
            $e3max = null;
            $edadmin = null;
            $codigoErrorEdad = null;
            $codigoErrorOpcion = null;

            if ($e4 == Constante::RESPUESTA_E4_2_EDUCACION_BASICA) {
                $e3min = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED;
                $e3max = Constante::RESPUESTA_E3_26_OCTAVO_ANO_ED;
                $edadmin = Constante::EDAD_5_MINIMA_EDUCACION_BASICA;
                $codigoErrorEdad = Constante::ERR_NEGOCIO_E4_EDAD_EDUCACION_BASICA;
                $codigoErrorOpcion = Constante::ERR_NEGOCIO_E3_BASICA;
            }
            if ($e4 == Constante::RESPUESTA_E4_3_PREPARATORIA_SISTEMA_ANTIGUO) {
                $e3min = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED;
                $e3max = Constante::RESPUESTA_E3_24_SEXTO_ANO_ED;
                $edadmin = Constante::EDAD_60_ADULTO_MAYOR;
                $codigoErrorEdad = Constante::ERR_NEGOCIO_E4_EDAD_PREPARATORIA;
                $codigoErrorOpcion = Constante::ERR_NEGOCIO_E3_PREPARATORIA;
            }
            /*if ($e4 == Constante::RESPUESTA_E4_4_EDUCACION_MEDIA_CIENTIFICA_HUMANISTA) {
                    $e3min = Constante::RESPUESTA_E3_9_PRIMERO_ED_MEDIA;
                    $e3max = Constante::RESPUESTA_E3_12_CUARTO_ED_MEDIA;
                    $edadmin = Constante::EDAD_13_MINIMA_EDUCACION_MEDIA;
                }
            if ($e4 == Constante::RESPUESTA_E4_5_EDUCACION_MEDIA_TECNICO_PROFESIONAL) {
                $e3min = Constante::RESPUESTA_E3_9_PRIMERO_ED_MEDIA;
                $e3max = Constante::RESPUESTA_E3_12_CUARTO_ED_MEDIA;
                $edadmin = Constante::EDAD_13_MINIMA_EDUCACION_MEDIA;
            }*/
            if ($e4 == Constante::RESPUESTA_E4_6_HUMANIDADES_SISTEMA_ANTIGUO) {
                $e3min = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED;
                $e3max = Constante::RESPUESTA_E3_24_SEXTO_ANO_ED;
                $edadmin = Constante::EDAD_60_ADULTO_MAYOR;
                $codigoErrorEdad = Constante::ERR_NEGOCIO_E4_EDAD_HUMANIDADES;
                $codigoErrorOpcion = Constante::ERR_NEGOCIO_E3_HUMANIDADES;
            }
            if ($e4 == Constante::RESPUESTA_E4_7_TECNICA_COMERCIAL_INDUSTRIAL_O_NORMALISTA_SISTEMA_ANTIGUO) {
                $e3min = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED;
                $e3max = Constante::RESPUESTA_E3_24_SEXTO_ANO_ED;
                $edadmin = Constante::EDAD_60_ADULTO_MAYOR;
                $codigoErrorEdad = Constante::ERR_NEGOCIO_E4_EDAD_TECNICO_NORMALISTA;
                $codigoErrorOpcion = Constante::ERR_NEGOCIO_E3_TECNICO_NORMALISTA;
            }

            if ($e4 == Constante::RESPUESTA_E4_1_EDUCACION_PARVULARIA) {
                if (!in_array($e3, Constante::$RESPUESTA_E3_38_A_41_POSIBLES_RESPUESTAS_ED_PARVULARIA)) {
                    $this->collectError('Si respuesta e4 es 1, e3 debe estar entre 38 y 41.',
                        Constante::ERR_NEGOCIO_E3_PARVULO, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_4_EDUCACION_MEDIA_CIENTIFICA_HUMANISTA) {
                if ($integrante->get('edad') < Constante::EDAD_13_MINIMA_EDUCACION_MEDIA) {
                    $this->collectError('Si respuesta e4 es 4 entonces debe ser mayor o igual a 13 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_MEDIA_CIENTIFICA, 'e4', $titular);
                } elseif (!in_array($e3, Constante::$RESPUESTAS_E3_ED_MEDIA_CIENTIFICA)) {
                    $this->collectError('Si respuesta e4 es 4 entonces e3 debe tener estos valores ' .
                        implode(", ", Constante::$RESPUESTAS_E3_ED_MEDIA_CIENTIFICA),
                        Constante::ERR_NEGOCIO_E3_MEDIA_CIENTIFICA, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_5_EDUCACION_MEDIA_TECNICO_PROFESIONAL) {
                if ($integrante->get('edad') < Constante::EDAD_13_MINIMA_EDUCACION_MEDIA) {
                    $this->collectError('Si respuesta e4 es 5 entonces debe ser mayor o igual a 13 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_MEDIA_TECNICO, 'e4', $titular);
                } elseif (!in_array($e3, Constante::$RESPUESTAS_E3_ED_MEDIA_TECNICA_PROFECIONAL)) {
                    $this->collectError('Si respuesta e4 es 5 entonces e3 debe tener estos valores ' .
                        implode(", ", Constante::$RESPUESTAS_E3_ED_MEDIA_TECNICA_PROFECIONAL),
                        Constante::ERR_NEGOCIO_E3_MEDIA_TECNICO, 'e3', $titular);
                }
            }

            if (in_array($e4, Constante::$RESPUESTA_E4_8_A_9_EDUCACION_CFT)) {
                if ($integrante->get('edad') < Constante::EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD) {
                    $this->collectError('Si respuesta e4 está entre 8 y 9, debe ser mayor o igual a 17 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_CFT, 'e4', $titular);
                } elseif (!in_array($e3,
                    Constante::$RESPUESTA_E3_19_A_21_POSIBLES_RESPUESTAS_ED_CFT)) { //($e3 != Constante::RESPUESTA_E3_0_NO_APLICA)
                    $this->collectError('Si respuesta e4 está entre 8 y 9, e3 debe estar entre 19 y 21.',
                        Constante::ERR_NEGOCIO_E3_CFT, 'e4', $titular);
                }
            }

            if (in_array($e4, Constante::$RESPUESTA_E4_10_A_11_EDUCACION_IP)) {
                if ($integrante->get('edad') < Constante::EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD) {
                    $this->collectError('Si respuesta e4 está entre 10 y 11, debe ser mayor o igual a 17 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_IP, 'e4', $titular);
                } elseif (!in_array($e3,
                    Constante::$RESPUESTA_E3_19_A_23_POSIBLES_RESPUESTAS_ED_IP)) { //($e3 != Constante::RESPUESTA_E3_0_NO_APLICA)
                    $this->collectError('Si respuesta e4 está entre 10 y 11, e3 debe estar entre 19 y 23.',
                        Constante::ERR_NEGOCIO_E3_IP, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_12_EDUCACION_UNIVERSITARIA_INCOMPLETA) {
                if ($integrante->get('edad') < Constante::EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD) {
                    $this->collectError('Si respuesta e4 está entre 12, debe ser mayor o igual a 17 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_UNIVERSIDAD_INCOMPLETA, 'e4', $titular);
                } elseif (!in_array($e3,
                    Constante::$RESPUESTA_E3_19_A_27_POSIBLES_RESPUESTAS_ED_UNIVERSITARIA_INCOMPLETA)) { //($e3 != Constante::RESPUESTA_E3_0_NO_APLICA)
                    $this->collectError('Si respuesta e4 es 12, e3 debe estar entre 19 y 27.',
                        Constante::ERR_NEGOCIO_E3_UNIVERSITARIA_INCOMPLETA, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_13_EDUCACION_UNIVERSITARIA_COMPLETA) {
                if ($integrante->get('edad') < Constante::EDAD_21_MINIMA_EDUCACION_UNIVERSIDAD_COMPLETA) {
                    $this->collectError('Si respuesta e4 es 13, debe ser mayor de 21 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_UNIVERSIDAD_COMPLETA, 'e4', $titular);
                } elseif (!in_array($e3, Constante::$RESPUESTA_E3_19_A_28_POSIBLES_RESPUESTAS_ED_UNIVERSITARIA)) {
                    $this->collectError('Si respuesta e4 es 13, e3 debe estar entre ' .
                        Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED . ' y ' . Constante::RESPUESTA_E3_28_DECIMO_ANO_ED,
                        Constante::ERR_NEGOCIO_E3_UNIVERSITARIA_COMPLETA, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_14_POSTGRADO) {
                if ($integrante->get('edad') < Constante::EDAD_22_MINIMA_EDUCACION_POSTGRADO_COMPLETA) {
                    $this->collectError('Si respuesta e4 es 14, debe ser mayor de 22 años.',
                        Constante::ERR_NEGOCIO_E4_EDAD_POSTGRADO, 'e4', $titular);
                } elseif (!in_array($e3, Constante::$RESPUESTA_E3_19_A_20_POSIBLES_RESPUESTAS_POSTGRADO)) {
                    $this->collectError('Si respuesta e4 es 14, e3 debe estar entre 19 y 20.',
                        Constante::ERR_NEGOCIO_E3_POSTGRADO, 'e3', $titular);
                }
            }

            if ($e4 == Constante::RESPUESTA_E4_15_EDUCACION_ESPECIAL_DIFERENCIAL) {
                if (!in_array($e3, Constante::$RESPUESTA_E3_19_A_27_POSIBLES_RESPUESTAS_ED_DIFERENCIAL)) {
                    $this->collectError('Si respuesta e4 es 15, e3 debe estar entre 29 y 41.',
                        Constante::ERR_NEGOCIO_E3_DIFERENCIAL, 'e3', $titular);
                }
            }

            /*if ($e4 == Constante::RESPUESTA_E4_15_EDUCACION_ESPECIAL_DIFERENCIAL &&
                $e3 != Constante::RESPUESTA_E3_0_NO_APLICA
            ) {
                $this->collectError('Si respuesta e4 es Educación Especial Diferencial, e3 debe ser 0.',
                    'integrante ' . $integrante->get('run') . '-' . Utiles::dv($integrante->get('run')));
            }*/
            if ($e3min != null &&
                $e3max != null &&
                $edadmin != null
            ) {
                if ($e3 < $e3min ||
                    $e3 > $e3max ||
                    $edad < $edadmin
                ) {
                    if ($edad < $edadmin) {
                        $this->collectError("Si respuesta e4 es $e4, la edad debe ser al menos $edadmin",
                            $codigoErrorEdad, 'e4', $titular);
                    } elseif ($e3 < $e3min || $e3 > $e3max) {
                        $this->collectError("Si respuesta e4 es $e4, e3 debe estar entre $e3min y $e3max",
                            $codigoErrorOpcion, 'e3', $titular);
                    }
                    //$mensaje = "Si E4 es $e4 E3 debe estar entre $e3min y $e3max y adicionalmente la persona debe tener al menos $edadmin años. (Edad del integrante: $edad años)";
                    //return Response::json(array('estado' => '0', 'msg' => $mensaje));
                }
            }
        } else {
            foreach (['e3', 'e4'] as $campo) {
                if ($integrante->$campo != null) {
                    $this->collectError('Si respuesta e1 es 1 no se requiere el atributo ' . $campo,
                        Constante::ERR_NEGOCIO_NO_SE_REQUIERE_ATRIBUTO, $campo, $titular);
                }
            }
        }
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
