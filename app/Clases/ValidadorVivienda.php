<?php

namespace App\Clases;

use App\Constantes\Constante;

class ValidadorVivienda
{
    use ValidacionTrait;

    public function validarRespuestasVivienda($input, $requerido = true): bool
    {
//        $requerido = true;
        $valorV1 = ($input->get('v1'));
        $camposValidar = [];
        if (in_array($valorV1, Constante::$RESPUESTA_V1_1_A_7_VIVIENDAS_ACEPTADAS)) {
            $camposValidar['v2'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V2_1_A_6_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo bajo que situación ocupa el sitio es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de bajo que situación ocupa el sitio debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v3'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V3_1_A_5_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo bajo que situación ocupa la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de bajo que situación ocupa la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v4'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V4_1_A_6_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo de dónde obtienen el agua es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de dónde obtienen el agua debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v5'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V5_0_A_2_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo cómo llega el agua a su vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de cómo llega el agua a su vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v9-estado'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V9EC_1_A_3_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo estado de los muros de la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de estado de los muros de la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v10-estado'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V10EC_1_A_3_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo estado del piso de la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor estado del piso de la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v11-estado'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V11EC_1_A_3_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo estado del techo de la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor estado del techo de la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];

            $camposValidar['v6'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'mensaje' => [
                    'requerido' => 'El campo sistema de baño es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor sistema de baño debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v9'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'mensaje' => [
                    'requerido' => 'El campo material muros de su vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de material muros de su vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v10'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'mensaje' => [
                    'requerido' => 'El campo material piso de su vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de material piso de su vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v11'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'mensaje' => [
                    'requerido' => 'El campo material techo de su vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de material techo de su vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];

            if ($valorV1 == Constante::RESPUESTA_V1_2_DEPARTAMENTO) {
                $camposValidar['v6']['in'] = Constante::RESPUESTA_V6_1_WC_CONECTADO_A_ALCANTARILLADO;
                $camposValidar['v9']['in'] = Constante::$RESPUESTA_V9_1_A_4_DEPARTAMENTO;
                $camposValidar['v10']['in'] = Constante::$RESPUESTA_V10_1_A_5_DEPARTAMENTO;
                $camposValidar['v11']['in'] = Constante::$RESPUESTA_V11_1_A_3_DEPARTAMENTO;
            } else {
                $camposValidar['v6']['in'] = Constante::$RESPUESTA_V6_0_A_3_POSIBLES_RESPUESTAS;
                $camposValidar['v9']['in'] = Constante::$RESPUESTA_V9_1_A_6_POSIBLES_RESPUESTAS;
                $camposValidar['v10']['in'] = Constante::$RESPUESTA_V10_1_A_7_POSIBLES_RESPUESTAS;
                $camposValidar['v11']['in'] = Constante::$RESPUESTA_V11_1_A_7_POSIBLES_RESPUESTAS;
            }

            // * Cuando V1 es 3 (Pieza dentro de la vivienda) no requiere valor
            // * de lo contrario si es otro valor, solo puede ser en entre 1 y 2 (SI/NO)
            $camposValidar['v8'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'mensaje' => [
                    'requerido' => 'El campo es el principal ocupante es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor es el principal ocupante debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            if ($valorV1 == Constante::RESPUESTA_V1_3_PIEZA_DENTRO_DE_LA_VIVIENDA) {
                $camposValidar['v8']['in'] = Constante::RESPUESTA_V8_2_NO;
            } else {
                $camposValidar['v8']['in'] = Constante::$RESPUESTA_V8_1_A_2_POSIBLES_RESPUESTAS;
            }
            $camposValidar['v7'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_V7_0_Y_99_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo dormitorios que ocupa es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor dormitorios que ocupa debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
        }

        if (in_array($valorV1, Constante::$RESPUESTA_V1_8_A_11_VIVIENDA_NO_VALIDA)) {
            $requerido = false;
            $camposValidar['v2'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo bajo que situación ocupa el sitio es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de bajo que situación ocupa el sitio debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v3'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo bajo que situación ocupa la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de bajo que situación ocupa la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v4'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo de dónde obtiene el agua es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de dónde obtiene el agua debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v5'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo como llega el agua es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de como llega el agua debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v6'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo sistema de baño es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de sistema de baño debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v7'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo cuántos dormitorios ocupa es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de cuántos dormitorios ocupa debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v8'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo su hogar es el principal ocupantede la vivienda es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de su hogar es el principal ocupantede la vivienda debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v9-estado'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo estado de los muros es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de estado de los muros debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v10-estado'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo estado del piso es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de estado del piso debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
            $camposValidar['v1-estado'] = [
                'dato' => 'null',
                'tipo' => 'in',
                'in' => [null],
                'mensaje' => [
                    'requerido' => 'El campo estado del techo es requerido',
                    'in' => 'El valor de respuesta debe estar entre %valores%',
                    'dato' => 'El valor de estado del techo debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
        }

        $this->validarArreglo($camposValidar, $requerido, $input, null);
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
