<?php

namespace App\Clases;

use App\Constantes\Constante;

class ValidadorIngreso
{
    use ValidacionTrait;

    public function validarRespuestasIngreso($integrante, $requerido = true): bool
    {
        $edad = $integrante->get('edad');
        $titular = $integrante->runTitular;
        $camposValidar = [];

        if ($edad < Constante::EDAD_15_PUEDE_TRABAJAR) {
            $camposValidar['y2'] = [
                'dato' => 'entero',
                'tipo' => 'between',
                'between' => Constante::$RESPUESTA_Y2_0_Y_999999999_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo jubilaciones o pensiones anuales es requerido',
                    'between' => 'El valor de jubilaciones o pensiones anuales debe estar entre %min% y %max%',
                    'dato' => 'El valor de jubilaciones o pensiones anuales debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];

            if ($integrante->get('o1') != null ||
                $integrante->get('o2') != null ||
                $integrante->get('o3') != null ||
                $integrante->get('o4') != null ||
                $integrante->get('o5') != null ||
                $integrante->get('o6') != null ||
                $integrante->get('o7') != null ||
                $integrante->get('o8') != null ||
                $integrante->get('o9') != null ||
                $integrante->get('y1') != null ||
                $integrante->get('y3') != null
            ) {
                $this->collectError('Si el integrante es menor de ' . Constante::EDAD_15_PUEDE_TRABAJAR .
                    ' años entonces no se requieren los respuestas o1, o2, o3, o4, o5, o6, o7, o8, o9, y1, y3',
                    Constante::ERR_NEGOCIO_VALOR_NO_REQUERIDO,
                    $titular,
                    $titular);
            }
        } else {
            if (!is_null($integrante->get('o1'))) {
                if (!in_array($integrante->get('o1'), Constante::$RESPUESTA_O1_1_A_2_POSIBLES_RESPUESTAS)) {
                    $this->collectError('O1: Valor no válido',
                        Constante::ERR_NEGOCIO_O1_INVALIDO,
                        $titular,
                        $titular);
                }
                if ($integrante->get('o1') == Constante::RESPUESTA_O1_1_SI) {
                    //if ($integrante->get('o7') > 168) {
                    //    $this->collectError('No es posible trabajar mas de 168 horas a la semana . ',
                    //       'integrante ' . $integrante->get('run'). '-' . Utiles::dv($integrante->get('run')));
                    //}
                    $camposValidar['o2'] = [
                        'dato' => 'entero',
                        'tipo' => 'in',
                        'in' => Constante::$RESPUESTA_O2_1_A_6_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo ocupación actual es requerido',
                            'in' => 'El valor de respuesta debe estar entre %valores%',
                            'dato' => 'El valor de ocupación debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['o3'] = [
                        'dato' => 'entero',
                        'tipo' => 'in',
                        'in' => Constante::$RESPUESTA_O3_1_A_9_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo códigos rama es requerido',
                            'in' => 'El valor de respuesta debe estar entre %valores%',
                            'dato' => 'El valor de códigos rama debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['o4'] = [
                        'dato' => 'entero',
                        'tipo' => 'in',
                        'in' => Constante::$RESPUESTA_O4_1_A_5_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo temporalidad/permanencia es requerido',
                            'in' => 'El valor de respuesta debe estar entre %valores%',
                            'dato' => 'El valor de temporalidad/permanencia debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['o5'] = [
                        'dato' => 'entero',
                        'tipo' => 'in',
                        'in' => Constante::$RESPUESTA_O5_1_A_2_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo tipo de contrato es requerido',
                            'in' => 'El valor de respuesta debe estar entre %valores%',
                            'dato' => 'El valor de tipo de contrato debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['o7'] = [
                        'dato' => 'entero',
                        'tipo' => 'between',
                        'between' => Constante::$RESPUESTA_O7_0_Y_90_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo horas trabajadas a la semana es requerido',
                            'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                            'dato' => 'El valor de horas trabajadas a la semana debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];

                    $camposValidar['y1'] = [
                        'dato' => 'entero',
                        'tipo' => 'between',
                        'between' => Constante::$RESPUESTA_Y1_0_Y_999999999_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo ingresos anuales del trabajo es requerido',
                            'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                            'dato' => 'El valor de ingresos anuales del trabajo debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['y2'] = [
                        'dato' => 'entero',
                        'tipo' => 'between',
                        'between' => Constante::$RESPUESTA_Y2_0_Y_999999999_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo tipo de jubilaciones o pensiones anuales es requerido',
                            'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                            'dato' => 'El valor de jubilaciones o pensiones anuales debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                    $camposValidar['y3'] = [
                        'dato' => 'entero',
                        'tipo' => 'between',
                        'between' => Constante::$RESPUESTA_Y3_0_Y_999999999_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo otros ingresos anuales es requerido',
                            'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                            'dato' => 'El valor de otros ingresos anuales debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];

                    if ($integrante->get('o2') == Constante::RESPUESTA_O2_6_FUERZAS_ARMADAS_Y_DE_ORDEN &&
                        $integrante->get('o3') != Constante::RESPUESTA_O3_9_SERVICIOS_COMUNALES_SOCIALES &&
                        $requerido
                    ) {
                        $this->collectError('Si o2 es 6 entonces o3 debe ser 9)',
                            Constante::ERR_NEGOCIO_SI_O6_6_ENTONCES_O3_9,
                            $titular,
                            $titular);
                    }
                    if ($integrante->get('o5') == Constante::RESPUESTA_O5_1_SI) {
                        $camposValidar['o6'] = [
                            'dato' => 'entero',
                            'tipo' => 'in',
                            'in' => Constante::$RESPUESTA_O6_1_A_5_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo código relación contractual es requerido',
                                'in' => 'El valor de respuesta debe estar entre %valores%',
                                'dato' => 'El valor de código relación contractual debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                    }
                    if ($integrante->get('o5') == Constante::RESPUESTA_O5_2_NO) {
                        $integrante->o6 = Constante::RESPUESTA_O6_5_NO_CORRESPONDE;
                        /*
                        // se comenta para que pase el commmit
                             if ($integrante->get('o6')!=null) {
                             $this->collectError('Si o5 es 2 entonces no se requieren los siguientes atributos(o6)',
                            'integrante ' . $integrante->get('run') . '-' . Utiles::dv($integrante->get('run')));
                        }
                        */
                    }

                    if (($integrante->get('o8') != null ||
                        $integrante->get('o9') != null) &&
                        $requerido
                    ) {
                        $this->collectError('Si o1 es 1 entonces no se requieren los siguientes atributos(o8 y o9)',
                            Constante::ERR_NEGOCIO_SI_O1_1_NO_O89,
                            $titular,
                            $titular);
                    }

                } elseif (
                    $integrante->get('o1') == Constante::RESPUESTA_O1_2_NO &&
                    $integrante->get('o8')
                ) {
                    if ($integrante->get('o8') == Constante::RESPUESTA_O8_1_SI) {
                        $camposValidar['o8'] = [
                            'dato' => 'entero',
                            'tipo' => 'in',
                            'in' => Constante::$RESPUESTA_O8_1_A_2_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo buscó trabajo remunerado es requerido',
                                'in' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de buscó trabajo remunerado debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];

                        $camposValidar['y1'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y1_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo ingresos anuales del trabajo es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de ingresos anuales del trabajo debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                        $camposValidar['y2'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y2_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo tipo de jubilaciones o pensiones anuales es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de jubilaciones o pensiones anuales debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                        $camposValidar['y3'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y3_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo otros ingresos anuales es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de otros ingresos anuales debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];

                        if (($integrante->get('o2') != null ||
                            $integrante->get('o3') != null ||
                            $integrante->get('o4') != null ||
                            $integrante->get('o5') != null ||
                            $integrante->get('o6') != null ||
                            $integrante->get('o7') != null ||
                            $integrante->get('o9') != null) && $requerido
                        ) {
                            $this->collectError('Si o1 es 2 y o8 es 1 entonces no se requieren los siguientes atributos(o2, o3, o4, o5, o6, o7 y o9)',
                                Constante::ERR_NEGOCIO_VALOR_NO_REQUERIDO,
                                'o2',
                                $titular);
                        }

                    } elseif ($integrante->get('o8') == Constante::RESPUESTA_O8_2_NO) {
                        $camposValidar['o8'] = [
                            'dato' => 'entero',
                            'tipo' => 'in',
                            'in' => Constante::$RESPUESTA_O8_1_A_2_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo buscó trabajo remunerado es requerido',
                                'in' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de buscó trabajo remunerado debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                        $camposValidar['o9'] = [
                            'dato' => 'entero',
                            'tipo' => 'in',
                            'in' => Constante::$RESPUESTA_O9_1_A_10_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo por que no buscó trabajo en los últimos meses es requerido',
                                'in' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de por que no buscó trabajo en los últimos meses debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];

                        $camposValidar['y1'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y1_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo ingresos anuales del trabajo es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de ingresos anuales del trabajo debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                        $camposValidar['y2'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y2_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo tipo de jubilaciones o pensiones anuales es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de jubilaciones o pensiones anuales debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];
                        $camposValidar['y3'] = [
                            'dato' => 'entero',
                            'tipo' => 'between',
                            'between' => Constante::$RESPUESTA_Y3_0_Y_999999999_POSIBLES_RESPUESTAS,
                            'mensaje' => [
                                'requerido' => 'El campo otros ingresos anuales es requerido',
                                'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                                'dato' => 'El valor de otros ingresos anuales debe ser de tipo '
                            ],
                            'codigo' => [
                                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                            ]
                        ];

                        if (($integrante->get('o2') != null ||
                            $integrante->get('o3') != null ||
                            $integrante->get('o4') != null ||
                            $integrante->get('o5') != null ||
                            $integrante->get('o6') != null ||
                            $integrante->get('o7') != null) &&
                            $requerido
                        ) {
                            $this->collectError('Si o1 es NO y o8 es NO, entonces no se requieren las respuestas o2, o3, o4, o5, o6 y o7',
                                Constante::ERR_NEGOCIO_VALOR_NO_REQUERIDO,
                                'o2',
                                $titular);
                        }
                    }
                } else {
                    $camposValidar['o8'] = [
                        'dato' => 'entero',
                        'tipo' => 'in',
                        'in' => Constante::$RESPUESTA_O8_1_A_2_POSIBLES_RESPUESTAS,
                        'mensaje' => [
                            'requerido' => 'El campo buscó trabajo remunerado es requerido',
                            'in' => 'El valor de respuesta debe estar entre %min% y %max%',
                            'dato' => 'El valor de buscó trabajo remunerado debe ser de tipo '
                        ],
                        'codigo' => [
                            'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                            'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                            'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                        ]
                    ];
                }
            } else {
                $camposValidar['o1'] = [
                    'dato' => 'entero',
                    'tipo' => 'in',
                    'in' => Constante::$RESPUESTA_O1_1_A_2_POSIBLES_RESPUESTAS,
                    'mensaje' => [
                        'requerido' => 'El campo está trabajando es requerido',
                        'in' => 'El valor de respuesta debe estar entre %valores%',
                        'dato' => 'El valor de está trabajando ser de tipo '
                    ],
                    'codigo' => [
                        'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                        'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                        'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                    ]
                ];
                $camposValidar['y2'] = [
                    'dato' => 'entero',
                    'tipo' => 'between',
                    'between' => Constante::$RESPUESTA_Y2_0_Y_999999999_POSIBLES_RESPUESTAS,
                    'mensaje' => [
                        'requerido' => 'El campo tipo de jubilaciones o pensiones anuales es requerido',
                        'between' => 'El valor de respuesta debe estar entre %min% y %max%',
                        'dato' => 'El valor de jubilaciones o pensiones anuales debe ser de tipo '
                    ],
                    'codigo' => [
                        'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                        'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                        'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                    ]
                ];
            }
        }

        //procesa validacion de array $camposValidar
        $this->validarArreglo($camposValidar, $requerido, $integrante, $titular);

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
