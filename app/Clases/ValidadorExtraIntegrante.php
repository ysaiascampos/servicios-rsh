<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\Parentesco;
use App\Models\Personas;
use App\Models\PuebloIndigena;
use App\Utiles;

class ValidadorExtraIntegrante
{
    use ValidacionTrait;

    public function personaFallecida($input)
    {
        foreach ($input as $integrante) {
            $fallecido = Personas::where('per_run',$integrante->get('run-titular'))
                ->first()
                ->PER_FECHADEFUNCION;
            if ($fallecido) {
                $this->collectError(
                    'No se puede incorporar personas Fallecidas',
                    Constante::ERR_VALIDACION_INTEGRANTE_FALLECIDO,
                    'fallecido',
                    $integrante->get('run-titular'));
            }
        }
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }

    public function validacionParejaParentesco($input, $validaParejas = true, $validaJH = true, $requerido = true)
    {
        $listaParejaParentesco = array();
        $cantJefeHogar = 0;
        $idParejaJefeHogar = null;
        $contConyuge = 0;
        $parejas = [];
        $camposValidar = [];

        foreach ($input as $integrante) {
            if (!is_null($integrante->get('pareja'))) {
                $camposValidar['pareja'] = [
                    'dato' => 'entero',
                    'tipo' => 'between',
                    'between' => Constante::$RESPUESTA_PAREJA_1_Y_99_POSIBLES_RESPUESTAS_NUMERIC,
                    'mensaje' => [
                        'requerido' => 'El campo pareja es requerido',
                        'between' => 'El valor de pareja debe poseer entre %min% y %max% o ser vacío',
                        'dato' => 'El valor de pareja debe ser de tipo '
                    ],
                    'codigo' => [
                        'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                        'between' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                        'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                    ]
                ];
            }
            $camposValidar['parentesco'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => Constante::$RESPUESTA_PARENTESCO_1_A_99_POSIBLES_RESPUESTAS,
                'mensaje' => [
                    'requerido' => 'El campo parentesco es requerido',
                    'in' => 'El valor de parentesco debe estar entre %valores%',
                    'dato' => 'El valor de parentesco debe ser de tipo '
                ],
                'codigo' => [
                    'requerido' => Constante::ERR_VALIDACION_CAMPO_REQUERIDO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                ]
            ];

            $this->validarArreglo($camposValidar, $requerido, $integrante, $integrante->runTitular);

//            $validator = Validator::make($integrante->toArray(), $rules, $messages);
//            if ($validator->fails()) {
//                $this->collectError($validator->messages(),
//                    'integrante ' . $integrante->get('run') . '-' . Utiles::dv($integrante->get('run')));
//            }

//            if ((strlen($integrante->get('pareja'))) > 0 &&
//                (intval($integrante->get('pareja')) <= 0)
//            ) {
//                $this->collectError(
//                    'Número de pareja(s) debe ser vacío o mayor que 0',
//                    Constante::ERR_VALIDACION_NUMERO_PAREJA_VACIO_O_MAYOR_CERO,
//                    'pareja',
//                    $integrante->get('run-titular'));
//            }
            if ($validaJH) {
                if ($integrante->get('parentesco') == Constante::PARENTESCO_1_JEFE_DE_HOGAR) {
                    $idParejaJefeHogar = $integrante->get('pareja');
                    if (++$cantJefeHogar > 1) {
                        $this->collectError(
                            'Sólo debe haber un Jefe de Hogar',
                            Constante::ERR_VALIDACION_UNICO_JEFE_HOGAR,
                            'parentesco',
                            $integrante->get('run-titular'));
                    }
                }
            }
            if ($integrante->get('parentesco') == Constante::PARENTESCO_2_CONYUGE_O_PAREJA) {
                if (++$contConyuge > 1) {
                    $this->collectError('Sólo debe haber un cónyuge o pareja',
                        'integrante ' . $integrante->get('run-titular') . '-' . Utiles::dv($integrante->get('run-titular')));
                    $this->collectError(
                        'Sólo debe haber un cónyuge o pareja',
                        Constante::ERR_VALIDACION_UNICO_CONYUGUE_JEFE_HOGAR,
                        'parentesco',
                        $integrante->get('run-titular'));
                }
            }
            if ($integrante->get('parentesco') == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                $integrante->get('edad') < Constante::EDAD_18_MAYOR_DE_EDAD
            ) {
                // Jefe de hogar deberá ser igual o mayor a 18 años, a excepción de niños institucionalizados
                $analisisInstitucionalizados = DatosDeContacto::analisisInstitucionalizados($integrante->get('id'));
                if (!$analisisInstitucionalizados) {
                    $this->collectError(
                        'El Jefe de hogar ' . $integrante->get('run-titular') . '-' . Utiles::dv($integrante->get('run-titular')) .
                        ' debe tener al menos ' . Constante::EDAD_18_MAYOR_DE_EDAD . ' años',
                        Constante::ERR_VALIDACION_JEFE_HOGAR_MAYOR,
                        'parentesco',
                        $integrante->get('run-titular'));
                }
            }

            // Obliga a cualquier integrante que manifieste que este en situacion de pareja siendo este YERNO / NUERA (tipo 8)
            // y esta sea menor de edad (18 anos),   debe estar en estado civil como casado, de lo contrario no puede ser pareja
            // regla de validacion de en base requerimiento de Negocio 4/30/2019
//            $consultaPerID = (new DatosDeContacto)->findByIdPersona($integrante->get('id'));
            $estadoCivil = Utiles::getPersona($integrante->get('run-titular'));
            if (is_numeric($estadoCivil)) {
                if ($estadoCivil == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                    $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                } elseif ($estadoCivil == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                    $mensaje = "El RUN ingresado no se encuentra en el Registro Civil.";
                } else {
                    $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                }
                $this->collectError(
                    $mensaje . ": " . $integrante->get('run-titular'),
                    Constante::ERR_VALIDACION_ESTADO_CIVIL_REGISTRO,
                    'parentesco',
                    $integrante->get('run-titular'));
            }

            if (
                $integrante->get('parentesco') == Constante::PARENTESCO_8_YERNO_O_NUERA &&
                $integrante->get('edad') < Constante::EDAD_18_MAYOR_DE_EDAD &&
                !in_array($estadoCivil['PER_ESTADOCIVIL'], Constante::$ESTADO_CIVIL_CASADO_EQUIVALENTE)
            ) {
                $this->collectError(
                    'El integrante ' . $integrante->get('run-titular') . '-' . Utiles::dv($integrante->get('run-titular')) .
                    ' debe tener al menos ' . Constante::EDAD_16_PUEDE_TENER_PAREJA .
                    ' años y encontrarse con un estado civil de ' . Constante::ESTADO_CIVIL_CASADO .
                    ' o ' . Constante::ESTADO_CIVIL_CONVIVIENTE_CIVIL,
                    Constante::ERR_VALIDACION_ESTADO_CIVIL_REGISTRO,
                    'pareja',
                    $integrante->get('run-titular'));
            }

            if (trim($integrante->get('pareja')) != null &&
                intval($integrante->get('pareja')) >= 1
            ) {
                $nroPareja = (integer)$integrante->get('pareja');
            } else {
                $nroPareja = null;
            }
            if (isset($parejas[$nroPareja])) {
                $parejas[$nroPareja]++;
                array_push($listaParejaParentesco[$nroPareja], [
                    'id_integrante' => $integrante->get('id'),
                    'parentesco' => $integrante->get('parentesco'),
                    'run-titular' => $integrante->get('run-titular')
                ]);
            } else {
                $parejas[$nroPareja] = 1;
                $listaParejaParentesco[$nroPareja] = array([
                    'id_integrante' => $integrante->get('id'),
                    'parentesco' => $integrante->get('parentesco'),
                    'run-titular' => $integrante->get('run-titular')
                ]);
            }
        }

        if ($validaJH && $cantJefeHogar == 0) {
            $this->collectError('El grupo familiar debe poseer Jefe de Hogar',
                Constante::ERR_VALIDACION_GRUPOFAMILIAR_DEBE_TENER_JH,
                'parentesco',
                null);
        }

        foreach ($input as $integrante) {
            if ($integrante->get('parentesco') == Constante::PARENTESCO_6_PADRE_O_MADRE &&
                $integrante->get('edad') < Constante::EDAD_18_MAYOR_DE_EDAD
            ) {
                $error = true;
                $this->collectError(
                    'El integrante ' . $integrante->get('run-titular') . '-' . Utiles::dv($integrante->get('run-titular')) .
                    ' tiene un parentesco de padre/madre del jefe de hogar, debe tener al menos ' . Constante::EDAD_18_MAYOR_DE_EDAD .
                    ' años de edad',
                    Constante::ERR_VALIDACION_PARENTESCO_MADRE_PADRE,
                    'parentesco',
                    $integrante->get('run-titular'));
            }

            if (
                $integrante->get('parentesco') == Constante::PARENTESCO_2_CONYUGE_O_PAREJA &&
                $integrante->get('edad') < Constante::EDAD_18_MAYOR_DE_EDAD &&
                !in_array($integrante->get('estadocivil'), Constante::$ESTADO_CIVIL_CASADO_EQUIVALENTE)
            ) {
                $this->collectError(
                    'El integrante no puede ser cónyuge/pareja debido a que es menor de ' .
                    Constante::EDAD_18_MAYOR_DE_EDAD . ' años y su estado civil no es ' .
                    Constante::ESTADO_CIVIL_CASADO . ' o ' . Constante::ESTADO_CIVIL_CONVIVIENTE_CIVIL,
                    Constante::ERR_VALIDACION_PARENTESCO_CONYUGUE_MENOR,
                    'parentesco',
                    $integrante->get('run-titular'));
            }

            // El jefe de hogar no puede tener como pareja a un hijo(a)
            if (in_array($integrante->get('parentesco'), [
                    Constante::PARENTESCO_3_HIJO_DE_AMBOS,
                    Constante::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR,
                    Constante::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA]) &&
                (integer)trim($integrante->get('pareja')) == $idParejaJefeHogar &&
                (integer)trim($integrante->get('pareja')) != null &&
                (integer)trim($integrante->get('pareja')) != 0
            ) {
                $this->collectError(
                    'El jefe de hogar no puede tener como pareja a su hijo(a)',
                    Constante::ERR_VALIDACION_JH_PAREJA_HIJO,
                    'pareja',
                    $integrante->get('run-titular'));
            }

//            La pareja del jefe de hogar debe tener el mismo codigo de pareja del jefe de hogar
            if ($validaParejas) {
                if ($idParejaJefeHogar != null &&
                    $integrante->get('parentesco') == Constante::PARENTESCO_2_CONYUGE_O_PAREJA &&
                    ((integer)trim($integrante->get('pareja')) != $idParejaJefeHogar ||
                        (integer)trim($integrante->get('pareja')) == null ||
                        (integer)trim($integrante->get('pareja')) == 0)
                ) {
                    $this->collectError(
                        'Cónyuge/pareja debe tener el mismo código de pareja que el Jefe de hogar',
                        Constante::ERR_VALIDACION_CONYUGUE_MISMA_PAREJA_JH,
                        'pareja',
                        $integrante->get('run-titular'));
                }
            }
        }

        $parejasCorrectas = true;

        foreach ($parejas as $idPareja => $nroIntegrantePareja) {
            if ($idPareja >= 1) {
                if ($idPareja > count($parejas)) {
                    $this->collectError(
                        'El número de pareja ' . $idPareja .
                        ' es incorrecto. El número de potenciales parejas es ' . count($parejas),
                        Constante::ERR_VALIDACION_NUMERO_PAREJAS_POTENCIALES,
                        'pareja',
                        null);
                    $parejasCorrectas = false;
                } else {
                    if ($nroIntegrantePareja != 2) {
                        $this->collectError(
                            'Deben haber dos integrantes con el mismo número de pareja ' . $idPareja,
                            Constante::ERR_VALIDACION_PAREJAS_CON_MAS_DE_2_INTEGRANTES,
                            'pareja',
                            null);
                        $parejasCorrectas = false;
                    }
                }
            }
        }

        $parentescoSangre = [
            Constante::PARENTESCO_1_JEFE_DE_HOGAR,
            Constante::PARENTESCO_2_CONYUGE_O_PAREJA,
            Constante::PARENTESCO_3_HIJO_DE_AMBOS,
            Constante::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR,
            Constante::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA,
            Constante::PARENTESCO_6_PADRE_O_MADRE,
            Constante::PARENTESCO_9_NIETO,
            Constante::PARENTESCO_10_HERMANO
        ];
        $listaParentescos = Parentesco::listParentescos();
        if ($parejasCorrectas) {
            foreach ($listaParejaParentesco as $idPareja => $nroIntegrantePareja) {
                if (is_numeric($idPareja)) {
                    $inte1 = $nroIntegrantePareja[0];
                    $inte2 = $nroIntegrantePareja[1];
                    if (
                        in_array($inte1['parentesco'], $parentescoSangre) &&
                        in_array($inte2['parentesco'], $parentescoSangre)
                    ) {
                        if (
                            !(
                                $inte1['parentesco'] == Constante::PARENTESCO_1_JEFE_DE_HOGAR && $inte2['parentesco'] == Constante::PARENTESCO_2_CONYUGE_O_PAREJA ||
                                $inte2['parentesco'] == Constante::PARENTESCO_1_JEFE_DE_HOGAR && $inte1['parentesco'] == Constante::PARENTESCO_2_CONYUGE_O_PAREJA
                            ) && !(
                                $inte1['parentesco'] == Constante::PARENTESCO_6_PADRE_O_MADRE && $inte2['parentesco'] == Constante::PARENTESCO_6_PADRE_O_MADRE
//                            ) && !(
//                                $inte1['parentesco'] == Constante::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR && $inte2['parentesco'] == Constante::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA ||
//                                $inte2['parentesco'] == Constante::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR && $inte1['parentesco'] == Constante::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA
                            )
                        ) {
                            $this->collectError(
                                'Emparejamiento inválido entre integrante: ' .
                                $inte1['id_integrante'] . ' (parentesco: ' . $listaParentescos[$inte1['parentesco']] . ') e ' .
                                'integrante: ' .
                                $inte2['id_integrante'] . ' (parentesco: ' . $listaParentescos[$inte2['parentesco']] . ')',
                                Constante::ERR_VALIDACION_PAREJA_INVALIDA,
                                'pareja',
                                $inte1['run-titular']);
                        }
                    }
                }
            }
        }
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }

    public function validacionInformante($input, $validaExisteUnInformante = false)
    {
        $contInformante = 0;

        foreach ($input as $integrante) {
//            $rules = array();
//            $messages = array();
            if (!is_null($integrante->get('informante'))) {
                $camposValidar['informante'] = [
                    'dato' => 'entero',
                    'mensaje' => [
                        'dato' => 'El valor de informante debe ser de tipo '
                    ],
                    'codigo' => [
                        'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO
                    ]
                ];
                $this->validarArreglo($camposValidar, false, $integrante, $integrante->runTitular);
//                $rules['informante'] = 'integer|in:' . Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
//                $messages = [
//                    'informante.integer' => 'valor inválido en campo informante',
//                    'informante.in' => 'valor inválido en campo informante'
//                ];
            }
//            $validator = Validator::make($integrante->toArray(), $rules, $messages);
//            if ($validator->fails()) {
//                $this->collectError($validator->messages(),
//                    'integrante ' . $integrante->get('run') . '-' . Utiles::dv($integrante->get('run')));
//            }

            if ($validaExisteUnInformante) {
                if ($integrante->get('informante') == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                    $contInformante++;
                    if ($contInformante > 1) {
                        $this->collectError('Sólo debe haber un informante',
                            Constante::ERR_VALIDACION_GRUPOFAMILIAR_SOLO_UN_INFORMANTE,
                            'informante',
                            $integrante->get('run-titular'));
                    }
                }
            }
        }

        if ($contInformante == 0 && $validaExisteUnInformante) {
            $this->collectError('El grupo familiar debe poseer informante',
                Constante::ERR_VALIDACION_GRUPOFAMILIAR_DEBE_TENER_INFORMANTE,
                'informante',
                null);
        }
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }

    public function validacionPuebloOriginario($input, $requerido = true)
    {
        $pueblosOriginarios = PuebloIndigena::listarPueblosOriginarios();

        foreach ($input as $integrante) {
            $camposValidar['pueblo-originario'] = [
                'dato' => 'entero',
                'tipo' => 'in',
                'in' => $pueblosOriginarios->pluck('ID')->toArray(),
                'mensaje' => [
                    'dato' => 'El valor de pueblo originario debe ser de tipo ',
                    'in' => 'El valor de pueblo originario debe estar entre %valores%',
                ],
                'codigo' => [
                    'dato' => Constante::ERR_VALIDACION_CAMPO_TIPO_DATO,
                    'in' => Constante::ERR_VALIDACION_VALOR_FUERA_RANGO,
                ]
            ];
            $this->validarArreglo($camposValidar, $requerido, $integrante, $integrante->runTitular);
        }

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
