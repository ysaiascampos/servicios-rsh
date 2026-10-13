<?php
namespace App\Clases;
use App\Constantes\Constante;

/*
 *  [ <codigo pregunta> ] = [
 *      <valor respuesta N> | 'default' => [
 *          'next' => <codigo siguiente pregunta segun <valor respuesta N> >,
 *          'auto' => [ <codigo pregunta con valor automatico en caso de <valor respuesta N> > => <valor a asignar> ]
 *      ]
 *  ];
 */

class DependenciaModulo
{
    public static function educacion($edad)
    {
        $dependencias = [];
        $dependencias['e1'] = [
            Constante::RESPUESTA_E1_1_SI => ['next' => 's1'],
            Constante::RESPUESTA_E1_2_NO => ['next' => 'e3']
        ];
/*        $dependencias['e2'] = [
            'default' => ['next' => 'e4']
        ];*/

        $defaultE4 = ['next' => 'e3'];
        $nextS1_AutoE3igual0 = [
            'next' => 's1',
            'auto' => ['e3' => '0']
        ];

        $dependencias['e4'][Constante::RESPUESTA_E4_0_NINGUNO] = $nextS1_AutoE3igual0;
        $dependencias['e4'][Constante::RESPUESTA_E4_1_EDUCACION_PARVULARIA] = $defaultE4;//$nextS1_AutoE3igual0;
        $dependencias['e4'][Constante::RESPUESTA_E4_15_EDUCACION_ESPECIAL_DIFERENCIAL] = $defaultE4;//$nextS1_AutoE3igual0;

        if ($edad >= Constante::EDAD_5_MINIMA_EDUCACION_BASICA) {
            $dependencias['e4'][Constante::RESPUESTA_E4_2_EDUCACION_BASICA] = $defaultE4;
        }
        if ($edad >= Constante::EDAD_13_MINIMA_EDUCACION_MEDIA) {
            $dependencias['e4'][Constante::RESPUESTA_E4_4_EDUCACION_MEDIA_CIENTIFICA_HUMANISTA] = $defaultE4;
            $dependencias['e4'][Constante::RESPUESTA_E4_5_EDUCACION_MEDIA_TECNICO_PROFESIONAL] = $defaultE4;
        }
        if ($edad >= Constante::EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD) {
            $dependencias['e4'][Constante::RESPUESTA_E4_8_CENTRO_DE_FORMACION_TECNICA_INCOMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
            $dependencias['e4'][Constante::RESPUESTA_E4_9_CENTRO_DE_FORMACION_TECNICA_COMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
            $dependencias['e4'][Constante::RESPUESTA_E4_10_INSTITUTO_PROFESIONAL_INCOMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
            $dependencias['e4'][Constante::RESPUESTA_E4_11_INSTITUTO_PROFESIONAL_COMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
            $dependencias['e4'][Constante::RESPUESTA_E4_12_EDUCACION_UNIVERSITARIA_INCOMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
        }
        if ($edad >= Constante::EDAD_21_MINIMA_EDUCACION_UNIVERSIDAD_COMPLETA) {
            $dependencias['e4'][Constante::RESPUESTA_E4_13_EDUCACION_UNIVERSITARIA_COMPLETA] = $defaultE4;//$nextS1_AutoE3igual0;
        }
        if ($edad >= Constante::EDAD_22_MINIMA_EDUCACION_POSTGRADO_COMPLETA) {
            $dependencias['e4'][Constante::RESPUESTA_E4_14_POSTGRADO] = $defaultE4;//$nextS1_AutoE3igual0;
        }
        if ($edad >= Constante::EDAD_60_ADULTO_MAYOR) {
            $dependencias['e4'][Constante::RESPUESTA_E4_3_PREPARATORIA_SISTEMA_ANTIGUO] = $defaultE4;
            $dependencias['e4'][Constante::RESPUESTA_E4_6_HUMANIDADES_SISTEMA_ANTIGUO] = $defaultE4;
            $dependencias['e4'][Constante::RESPUESTA_E4_7_TECNICA_COMERCIAL_INDUSTRIAL_O_NORMALISTA_SISTEMA_ANTIGUO] = $defaultE4;
        }


        //Construir respuestas para e3
        $defaultE3 = ['next' => 's1'];
        $dependencias['e3'][Constante::RESPUESTA_E3_0_NO_APLICA] = $defaultE3;

        if ($edad >= 0) {
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_41_KINDER; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }
        }

        if ($edad >= Constante::EDAD_5_MINIMA_EDUCACION_BASICA) {
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_26_OCTAVO_ANO_ED; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }
        }

        if ($edad >= Constante::EDAD_13_MINIMA_EDUCACION_MEDIA) {
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_22_CUARTO_ANO_ED; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }

            $dependencias['e3'][Constante::RESPUESTA_E3_23_QUINTO_ANO_ED] = $defaultE3;
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_28_DECIMO_ANO_ED; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }
        }

        if ($edad >= Constante::EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD) {
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_27_NOVENO_ANO_ED; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }
        }

        if ($edad >= Constante::EDAD_21_MINIMA_EDUCACION_UNIVERSIDAD_COMPLETA) {
            $dependencias['e3'][Constante::RESPUESTA_E3_28_DECIMO_ANO_ED] = $defaultE3;
        }

        if ($edad >= Constante::EDAD_22_MINIMA_EDUCACION_POSTGRADO_COMPLETA) {
            //$dependencias['e3'][Constante::RESPUESTA_E3_29] = $defaultE3;
        }

        if ($edad >= Constante::EDAD_60_ADULTO_MAYOR) {
            for ($i = Constante::RESPUESTA_E3_19_PRIMERO_ANO_ED; $i <= Constante::RESPUESTA_E3_24_SEXTO_ANO_ED; $i++) {
                $dependencias['e3'][$i] = $defaultE3;
            }
        }
        return $dependencias;
    }

    //-----------------------------------------------------------
    public static function salud($edad)
    {
        $edad6BanarseSinAyuda = ($edad > Constante::EDAD_6_PUEDE_SALIR_BANAR_MOVERSE_SIN_AYUDA) ? ['next' => 's2a'] : ['next' => 'y2'];

        $dependencias = [];
        $dependencias['s1'] = [
            Constante::RESPUESTA_S1_1_SI => [
                'next' => 's1a'
            ],
            Constante::RESPUESTA_S1_2_NO => $edad6BanarseSinAyuda
        ];
        $dependencias['s1a'] = [
            'default' => ['next' => 's1b']
        ];
        $dependencias['s1b'] = [
            'default' => ['next' => 's1c']
        ];
        $dependencias['s1c'] = [
            'default' => ['next' => 's1d']
        ];
        $dependencias['s1d'] = [
            'default' => ['next' => 's1e']
        ];
        $dependencias['s1e'] = [
            'default' => ['next' => 's1f']
        ];
        $dependencias['s1f'] = ['default' => $edad6BanarseSinAyuda];

        if ($edad >= Constante::EDAD_6_PUEDE_SALIR_BANAR_MOVERSE_SIN_AYUDA) {
            $dependencias['s2a'] = [
                'default' => ($edad > Constante::EDAD_15_PUEDE_COMPRAR_O_IR_MEDICO_SIN_AYUDA) ?
                    ['next' => 's2b'] : ['next' => 's2c']
            ];
            if ($edad >= Constante::EDAD_15_PUEDE_COMPRAR_O_IR_MEDICO_SIN_AYUDA) {
                $dependencias['s2b'] = [
                    'default' => ['next' => 's2c']
                ];
            }
            $dependencias['s2c'] = [
                'default' => ['next' => 's2d']
            ];
            $dependencias['s2d'] = [
                'default' => ['next' => 's2e']
            ];
            $dependencias['s2e'] = [
                'default' => ($edad > Constante::EDAD_15_PUEDE_COMPRAR_O_IR_MEDICO_SIN_AYUDA) ?
                    ['next' => 'o1'] :
                    ['next' => 'y2']
            ];
        }
        return $dependencias;
    }

    //-----------------------------------------------------------
    public static function ingreso($edad)
    {
        $dependencia = [];
        if ($edad >= Constante::EDAD_15_PUEDE_TRABAJAR) {
            $dependencia['o1'] = [
                Constante::RESPUESTA_O1_1_SI => ['next' => 'o2'],
                Constante::RESPUESTA_O1_2_NO => ['next' => 'o8']
            ];
            $dependencia['o2'] = [
                Constante::RESPUESTA_O2_6_FUERZAS_ARMADAS_Y_DE_ORDEN => [
                    'next' => 'o4',
                    'auto' => ['o3' => Constante::RESPUESTA_O3_9_SERVICIOS_COMUNALES_SOCIALES]
                ],
                'default' => ['next' => 'o3']
            ];
            $dependencia['o3'] = ['default' => ['next' => 'o4']];
            $dependencia['o4'] = ['default' => ['next' => 'o5']];
            $dependencia['o5'] = [
                Constante::RESPUESTA_O5_1_SI => ['next' => 'o6'],
                Constante::RESPUESTA_O5_2_NO => ['next' => 'o7'],
            ];
            $dependencia['o6'] = ['default' => ['next' => 'o7']];
            $dependencia['o7'] = ['default' => ['next' => 'y1']];
            $dependencia['o8'] = [
                Constante::RESPUESTA_O8_1_SI => ['next' => 'y1'],
                Constante::RESPUESTA_O8_2_NO => ['next' => 'o9'],
            ];
            $dependencia['o9'] = ['default' => ['next' => 'y1']];
            $dependencia['y1'] = ['default' => ['next' => 'y2']];
        }
        $dependencia['y2'] = [];
        $dependencia['y2']['default'] = ($edad >= Constante::EDAD_15_PUEDE_TRABAJAR) ?
            ['next' => 'y3'] : ['next' => null];

        if ($edad >= Constante::EDAD_15_PUEDE_TRABAJAR) {
            $dependencia['y3'] = ['default' => ['next' => null]];
        }

        return $dependencia;
    }

    public static function vivienda()
    {
        $dependencias = [];
        $dependencias['v1'] = [
            'default' => ['next' => 'v2'],
        ];

        $dependencias['v2'] = [
            'default' => ['next' => 'v3'],
            /*'auto'=>['V6'=>[6],
                     'V9'=>[1,2,3,4],
                     'V10'=>[1,2,3,4,5],
                     'V11'=>[1,2,3]
            ]*/
        ];

        $dependencias['v3'] = [
            'default' => ['next' => 'v4']
        ];

        $dependencias['v4'] = [
            'default' => ['next' => 'v5']
        ];

        $dependencias['v5'] = [
            'default' => ['next' => 'v6']
        ];

        $dependencias['v6'] = [
            'default' => ['next' => 'v7']
        ];

        $dependencias['v7'] = [
            'default' => ['next' => 'v8']
        ];

        $dependencias['v8'] = [
            'default' => ['next' => 'v9'],
            /*'auto'=>[
                    'V2'=>0,
                    'V3'=>0,
                    'V4'=>0,
                    'V5'=>0,
                    'V6'=>0,
                    'V6'=>0,
                    'V7'=>0,
                    'V8'=>0,
                    'V9'=>0,
                    'V10'=>0,
                    'V11'=>0
            ]*/
        ];

        $dependencias['v9'] = [
            'default' => ['next' => 'v9EC']
        ];

        $dependencias['v9EC'] = [
            'default' => ['next' => 'v10']
        ];

        $dependencias['v10'] = [
            'default' => ['next' => 'v10EC']
        ];

        $dependencias['v10EC'] = [
            'default' => ['next' => 'v11']
        ];

        $dependencias['v11'] = [
            'default' => ['next' => 'v11EC']
        ];

        $dependencias['v11EC'] = ['default' => ['next' => null]];
        return $dependencias;
    }

}
