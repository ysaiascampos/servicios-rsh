<?php

namespace App\Clases;

use App\Constantes\Constante;

class ValidadorLocalidad
{
    use ValidacionTrait;

    public function validarRespuestasLocalidad($input, $requerido = true, $requeridosAdicionales = []): bool
    {
        $campoRequeridoReferencia = array_key_exists('campoRequeridoReferencia', $requeridosAdicionales)
            ? $requeridosAdicionales['campoRequeridoReferencia']
            : false;
        $campoRequeridoComuna = array_key_exists('campoRequeridoComuna', $requeridosAdicionales)
            ? $requeridosAdicionales['campoRequeridoComuna']
            : false;
        $nombreCalleRequerido = array_key_exists('nombreCalleRequerido', $requeridosAdicionales)
            ? $requeridosAdicionales['nombreCalleRequerido']
            : false;
        $campoRequeridoAH = array_key_exists('campoRequeridoAH', $requeridosAdicionales)
            ? $requeridosAdicionales['campoRequeridoAH']
            : false;
        $campoRequeridoUV = array_key_exists('campoRequeridoUV', $requeridosAdicionales)
            ? $requeridosAdicionales['campoRequeridoUV']
            : false;
        // * Los campos que se evalua esta validacion son los siguientes:
        // * comuna
        // * localidad
        // * zona
        // * calle
        // * nombre-calle
        // * numero
        // * casa
        // * sitio
        // * block
        // * referencia
        // * departamento
        // * agrupacion-habitacional
        // * unidad-vecinal
        // * numero-cliente-luz
        // * numero-cliente-agua

        //        /**
        //         * La primera seccion evalua que solo provenga
        //         * un valor en Casa o Depto o Sitio y nulos en aquellos que no provenga
        //         * por ej. casa = 1 , depto = null, sitio = null
        //         * en caso que provenga saldra un msje de error
        //         */
        //        $camposDomicilio = ['casa', 'departamento', 'sitio'];
        //        $cont = 0;
        //        foreach ($camposDomicilio as $camposDomicilioUnicos) {
        //            $conversion = $input->get(value($camposDomicilioUnicos));
        //            if (!(empty($conversion))) {
        //                $cont = $cont + 1;
        //                $campo[] = ($camposDomicilioUnicos);
        //            }
        //        }

        // * Validacion de consistencia de valores para aquellos
        // * campos defindos en el arreglo camposCheck

        $camposCheck = ['comuna', 'localidad', 'codigo-calle', 'agrupacion-habitacional', 'unidad-vecinal', 'numero-cliente-agua', 'numero-cliente-luz', 'compania-luz', 'compania-agua'];
        foreach ($camposCheck as $camposInput) {
            $valorTemp = $input->get(value($camposInput));
            if (!is_null($valorTemp)) {
                $input->set($camposInput, (is_numeric($input->get($camposInput))) ? intval($input->get($camposInput)) : $valorTemp);
            }
        }

        //booleano campoRequerido
        $camposValidar['localidad'] = [
            'dato' => 'entero',
            'tipo' => 'between',
            'between' => [1, 99999999],
            'mensaje' => [
                'requerido' => 'El campo localidad es requerido',
                'between' => 'El valor de localidad debe poseer entre %min% y %max%',
                'dato' => 'El valor de localidad debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['codigo-calle'] = [
            'dato' => 'entero',
            'tipo' => 'between',
            'between' => Constante::$CODIGO_CALLE_1_Y_99999999999999999999_POSIBLES_RESPUESTAS_NUMERIC,
            'mensaje' => [
                'requerido' => 'El campo codigo-calle es requerido',
                'between' => 'El valor de codigo-calle debe estar entre %min% y %max% números',
                'dato' => 'El valor de codigo-calle debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];

        $this->validarArreglo($camposValidar, true, $input, null);

        $camposValidar = [];
        //booleano $nombreCalleRequerido
        $camposValidar['nombre-calle'] = [
            'dato' => 'texto',
            'tipo' => 'length.max',
            'length.max' => 100,
            'mensaje' => [
                'requerido' => 'El campo nombre-calle es requerido',
                'length.max' => 'El valor de nombre-calle debe ser maximo %max% caracteres',
                'dato' => 'El valor de nombre-calle debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $this->validarArreglo($camposValidar, $nombreCalleRequerido, $input, null);

        $camposValidar = [];
        //booleano $campoRequeridoComuna
        $camposValidar['comuna'] = [
            'dato' => 'integer',
            'tipo' => 'between',
            'between' => [1, 99999999],
            'mensaje' => [
                'requerido' => 'El campo comuna es requerido',
                'between' => 'El valor de comuna debe ser entre %min% y %max%',
                'dato' => 'El valor de comuna debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $this->validarArreglo($camposValidar, $campoRequeridoComuna, $input, null);

        $camposValidar = [];
        //booleano $campoRequeridoReferencia
        $camposValidar['referencia'] = [
            'dato' => 'texto',
            'tipo' => 'length.between',
            'length.between' => [15 | 400],
            'mensaje' => [
                'requerido' => 'El campo referencia es requerido',
                'length.between' => 'El valor de referencia debe ser entre %min% y %max% caracteres',
                'dato' => 'El valor de referencia debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $this->validarArreglo($camposValidar, $campoRequeridoReferencia, $input, null);

        $camposValidar = [];
        //booleano $campoRequeridoAH
        $camposValidar['agrupacion-habitacional'] = [
            'dato' => 'numero',
            'tipo' => 'max',
            'max' => 999999999,
            'mensaje' => [
                'requerido' => 'El campo agrupacion-habitacional es requerido',
                'max' => 'El valor de agrupacion-habitacional debe ser maximo %max%',
                'dato' => 'El valor de agrupacion-habitacional debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $this->validarArreglo($camposValidar, $campoRequeridoAH, $input, null);

        $camposValidar = [];
        //booleano $campoRequeridoUV
        $camposValidar['unidad-vecinal'] = [
            'dato' => 'numero',
            'tipo' => 'between',
            'between' => [0, 9999999999],
            'mensaje' => [
                'requerido' => 'El campo unidad-vecinal es requerido',
                'between' => 'El valor de unidad-vecinal debe entre %min% y %max%',
                'dato' => 'El valor de unidad-vecinal debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $this->validarArreglo($camposValidar, $campoRequeridoUV, $input, null);

        $camposValidar = [];
        //sin requerido
        $camposValidar['numero-domicilio'] = [
            'dato' => 'texto',
            'tipo' => 'length.between',
            'length.between' => [1, 6],
            'mensaje' => [
                'requerido' => 'El campo numero-domicilio es requerido',
                'length.between' => 'El valor de numero-domicilio debe entre %min% y %max% caracteres',
                'dato' => 'El valor de numero-domicilio debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['casa'] = [
            'dato' => 'texto',
            'tipo' => 'length.max',
            'length.max' => 10,
            'mensaje' => [
                'requerido' => 'El campo casa es requerido',
                'length.max' => 'El valor de casa debe maximo %max% caracteres',
                'dato' => 'El valor de casa debe ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['sitio'] = [
            'dato' => 'texto',
            'tipo' => 'length.max',
            'length.max' => 10,
            'mensaje' => [
                'requerido' => 'El campo sitio es requerido',
                'length.max' => 'El valor de sitio debe maximo %max% caracteres',
                'dato' => 'El valor de casa sitio ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['departamento'] = [
            'dato' => 'texto',
            'tipo' => 'length.max',
            'length.max' => 10,
            'mensaje' => [
                'requerido' => 'El campo departamento es requerido',
                'length.max' => 'El valor de departamento debe maximo %max% caracteres',
                'dato' => 'El valor de casa departamento ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['block'] = [
            'dato' => 'texto',
            'tipo' => 'length.max',
            'length.max' => 10,
            'mensaje' => [
                'requerido' => 'El campo block es requerido',
                'length.max' => 'El valor de block debe maximo %max% caracteres',
                'dato' => 'El valor de casa block ser de tipo '
            ],
            'codigo' => [
                'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                'length.max' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
            ]
        ];
        $camposValidar['numero-cliente-luz'] = [
            'dato' => 'alpha_num',
            'tipo' => 'max',
            'max' => 20,
            'mensaje' => [
                'alpha_num' => 'El valor de numero-cliente-luz debe contener solo letras y números',
                'max' => 'El valor de numero-cliente-luz no debe superar los 20 caracteres',
            ],
            'codigo' => [
                'alpha_num' => Constante::ERR_VALIDACION_FORMATO_INCORRECTO,
                'max' => Constante::ERR_VALIDACION_LONGITUD_MAXIMA,
            ]
        ];
        $camposValidar['numero-cliente-agua'] = [
            'dato' => 'alpha_num',
            'tipo' => 'max',
            'max' => 30,
            'mensaje' => [
                'alpha_num' => 'El valor de numero-cliente-agua debe contener solo letras y números',
                'max' => 'El valor de numero-cliente-agua no debe superar los 30 caracteres',
            ],
            'codigo' => [
                'alpha_num' => Constante::ERR_VALIDACION_FORMATO_INCORRECTO,
                'max' => Constante::ERR_VALIDACION_LONGITUD_MAXIMA,
            ]
        ];
        if (!empty($input->latitud)) {
            $camposValidar['latitud'] = [
                'dato' => 'numero',
                'tipo' => 'between',
                'between' => Constante::$GEO_LATITUD_73_Y_17_POSIBLES_RESPUESTAS_NUMERIC,
                'mensaje' => [
                    'requerido' => 'El campo latitud es requerido',
                    'between' => 'El valor de latitud debe entre %min% y %max%',
                    'dato' => 'El valor de latitud debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
        }
        if (!empty($input->longitud)) {
            $camposValidar['longitud'] = [
                'dato' => 'numero',
                'tipo' => 'between',
                'between' => Constante::$GEO_LONGITUD_109_Y_55_POSIBLES_RESPUESTAS_NUMERIC,
                'mensaje' => [
                    'requerido' => 'El campo longitud es requerido',
                    'between' => 'El valor de longitud debe entre %min% y %max%',
                    'dato' => 'El valor de longitud debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];
        }
        $this->validarArreglo($camposValidar, false, $input, null);

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
