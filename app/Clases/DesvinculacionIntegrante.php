<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\Log;
use Throwable;

class DesvinculacionIntegrante extends TramiteMultiplesHijos
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE;
    public $nombreSolicitud = 'Solicitud de Desvinculación';

    public $firmaJefeHogar=true;

    public function editarSolicitud()
    {
        // TODO: Implement editarSolicitud() method.
    }

    public function enDigitacion()
    {
        // TODO: Implement enDigitacion() method.
    }

    public function pendienteDocumentos()
    {
        // TODO: Implement pendienteDocumentos() method.
    }

    public function agendado()
    {
        return null;
    }

    public function pendienteResolver()
    {
        // TODO: Implement pendienteResolver() method.
    }

    public function devolverGestion()
    {
        // TODO: Implement devolverGestion() method.
    }

    public function aprobar()
    {
        // TODO: Implement aprobar() method.
    }

    public function desistir()
    {
        // TODO: Implement desistir() method.
    }

    public function rechazar()
    {
        // TODO: Implement rechazar() method.
    }

    protected function cargarDataTramite()
    {
        if (in_array($this->solicitud->ID_ESTADO, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)) {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosByIdPadreSolicitud($this->solicitud->ID_PADRE_SOLICITUD,
                $this->solicitud->ID_TIPO_SOLICITUD, $this->solicitud->ID_SOLICITUD);
        } else {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdPadreSolicitud($this->solicitud->ID_PADRE_SOLICITUD,
                $this->solicitud->ID_TIPO_SOLICITUD);

        }
        $runpersona=Personas::findByIdPersona($this->solicitud->DATO_NUEVO);
        /*if($this->solicitud->RUN_CIUDADANO==$runpersona->PER_RUN){
            $this->firmaJefeHogar = false;
        }*/
        $this->obtenerPreguntasYRespuestasESOV($this->solicitud->ORIGEN_REGISTRO);
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }

    protected function actualizarRegistrosAprobacion()
    {
        // TODO: Implement actualizarRegistroAsociados() method.
    }

    /**
     * DESVINCULA UN INTEGRANTE DE SU GRUPO ACTIVO DE INMEDIATO, SIN VALIDACIÓN ADICIONAL
     * VERIFICA QUE EL GRUPO DONDE SE DESVINCULA QUEDE EN UN ESTADO CONSISTENTE
     * @param $idPersona
     * @param $idSolicitud
     * @return object
     */
    public static function desvinculaIntegrante($idPersona, Solicitud $solicitud): object
    {
        $salida = function ($estado, $mensaje) {
            return (object)[
                'estado' => $estado,
                'msg' => $mensaje
            ];
        };
        try {
            //en solicitud de ingreso dato actual es el ID del grupo
            if (in_array($solicitud->ID_TIPO_SOLICITUD, [
                Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS
            ])) {
                $grupoactual = GrupoFamiliar::where('grupfami_id', $solicitud->DATO_ACTUAL)
                    ->where('grupfami_vigente', 1)
                    ->where('grupfami_calculoactivo', 1)
                    ->where('grupfami_estado', 7)
                    ->first();
                if (empty($grupoactual)) {
                    $grupoactual = GrupoFamiliar::where('grupfami_id', $solicitud->DATO_ACTUAL)
                        ->where('grupfami_vigente', 1)
                        ->where('grupfami_calculoactivo', 1)
                        ->where('grupfami_estado', 5)
                        ->first();
                }
            } else {
                //de lo contrario dato actual es el nuevofolio
                $grupoactual = GrupoFamiliar::where('grupfami_nuevofolio', $solicitud->DATO_ACTUAL)
                    ->where('grupfami_vigente', 1)
                    ->where('grupfami_calculoactivo', 1)
                    ->where('grupfami_estado', 7)
                    ->first();
                if (empty($grupoactual)) {
                    $grupoactual = GrupoFamiliar::where('grupfami_nuevofolio', $solicitud->DATO_ACTUAL)
                        ->where('grupfami_vigente', 1)
                        ->where('grupfami_calculoactivo', 1)
                        ->where('grupfami_estado', 5)
                        ->first();
                }
            }

            if (empty($grupoactual)) {
                return $salida(0, 'El grupo familiar actual no se encuentra vigente.');
            }

            //en solicitud de ingreso o solicitud de incorporacion debe desvincular de cualquier grupo donde este activo
            if (in_array($solicitud->ID_TIPO_SOLICITUD, [
                Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS,
                Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION
            ])) {
                $flag = 0;
                $existe = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $idPersona)
                    ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->first();
                if ($existe) {
                    $grupoactual = GrupoFamiliar::where('grupfami_id', $existe->BNFGRUPFAMI_GRUPOFAMILIAR)
                        ->where('grupfami_vigente', 1)
                        ->where('grupfami_calculoactivo', 1)
                        ->first();
                }
            } else {
                //de lo contrario es solicitud de desvinculacion, debe desvincular del grupoactual
                $existe = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $idPersona)
                    ->where('bnfgrupfami_grupofamiliar', $grupoactual->GRUPFAMI_ID)
                    ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->first();
                $flag = 1;
            }

            if (!empty($existe)) {
                if ($flag == 0) {
                    $nuevo = $existe->replicate();
                    unset($nuevo->RN);

                    $nuevo->BNFGRUPFAMI_ID = Sequence::nextValue('SEQ_BENEFGRUPFAMPK');
                    $nuevo->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                    $nuevo->BNFGRUPFAMI_FECHA = date("Y-m-d");
                    $nuevo->BNFGRUPFAMI_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                    $nuevo->BNFGRUPFAMI_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                    $nuevo->BNFGRUPFAMI_IDSOLICITUD = $solicitud->ID_SOLICITUD;
                    $nuevo->save();
                }

                $existe->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                $existe->save();

                if ($grupoactual) {
                    /* en el grupo donde se desvinculó, revisa si existe Jefe de Hogar,
                     * sino elige al conyuge como JH y si no hay conyuge deja al primer mayor de 18 como JH
                     * siempre debe validar que el JH no tenga discapacidad grave
                     * sino revisa si quedan integrantes (deberian ser niños) deja en revision, si no queda nadie, deja anulado
                     */
                    //ya no hay JH (se esta desvinculando)
                    if ($existe->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR) {
                        //debe buscar quien será JH provisorio
                        $grupoactual->GRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                        //$grupoactual->GRUPFAMI_ESTADO = 7; //provisorio
                        $grupoactual->save();

                        // Primero, busca conyuge mayor igual 18 años, con run
                        $conyuge = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                            $grupoactual->GRUPFAMI_ID)
                            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                            ->where('bnfgrupfami_parentesco', Constante::PARENTESCO_2_CONYUGE_O_PAREJA)
                            ->first();
                        if (!empty($conyuge)) {
                            $per_run = $conyuge->Persona()
                                ->first()->PER_RUN;
                            if (!is_null($per_run)) {
                                $per_run_ws = Utiles::getPersona($per_run);
                                if (is_numeric($per_run_ws)) {
                                    if ($per_run_ws == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                                        $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                                    } elseif ($per_run_ws == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                                        $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
                                    } else {
                                        $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                                    }
                                    return $salida(0, $mensaje);
                                }
                                // aunque sea conyuge, debe ser mayor igual de 18---- se quita discpacidad 25-05
                                if ($per_run_ws->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $conyuge->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_1_JEFE_DE_HOGAR;
                                    if ($existe->BNFGRUPFAMI_INFORMANTE == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                                        $conyuge->BNFGRUPFAMI_INFORMANTE = Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
                                    }
                                    $conyuge->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                    $conyuge->save();
                                    // los demás integrantes deben quedar en parentesco 99
                                    $integrantesSinJH = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                                        $grupoactual->GRUPFAMI_ID)
                                        ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                        ->where('bnfgrupfami_parentesco', '!=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                        ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                                        ->get();
                                    if (count($integrantesSinJH) > 0) {
                                        foreach ($integrantesSinJH as $r) {
                                            $r->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_99_POR_DEFINIR;
                                            $r->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                            $r->save();
                                        }
                                    }
                                    // DB::connection()->getPdo()->commit();
                                    return $salida(1, 'La persona ha sido desvinculada correctamente.');
                                }
                            }
                        }
                        // Segundo, buscar al hijo de ambos, con run
                        $hijoAmbos = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                            $grupoactual->GRUPFAMI_ID)
                            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                            ->where('bnfgrupfami_parentesco', Constante::PARENTESCO_3_HIJO_DE_AMBOS)
                            ->first();
                        if (!empty($hijoAmbos)) {
                            $per_run = $hijoAmbos->Persona()
                                ->first()
                                ->PER_RUN;
                            if (!is_null($per_run)) {
                                $per_run_ws = Utiles::getPersona($per_run);
                                if (is_numeric($per_run_ws)) {
                                    if ($per_run_ws == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                                        $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                                    } elseif ($per_run_ws == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                                        $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
                                    } else {
                                        $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                                    }
                                    return $salida(0, $mensaje);
                                }
                                //aunque sea HIJO, debe ser mayor igual de 18---- se quita discpacidad 25-05
                                if ($per_run_ws->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $hijoAmbos->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_1_JEFE_DE_HOGAR;
                                    if ($existe->BNFGRUPFAMI_INFORMANTE == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                                        $hijoAmbos->BNFGRUPFAMI_INFORMANTE = Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
                                    }
                                    $hijoAmbos->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                    $hijoAmbos->save();
                                    // los demás integrantes deben quedar en parentesco 99
                                    $integrantesSinJH = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                                        $grupoactual->GRUPFAMI_ID)
                                        ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                        ->where('bnfgrupfami_parentesco', '!=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                        ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                                        ->get();
                                    if (count($integrantesSinJH) > 0) {
                                        foreach ($integrantesSinJH as $r) {
                                            $r->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_99_POR_DEFINIR;
                                            $r->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                            $r->save();
                                        }
                                    }
                                    return $salida(1, 'La persona ha sido desvinculada correctamente.');
                                }
                            }
                        }

                        // Tercero, buscar al hijo de jefe de hogar, con run
                        $hijoJefeHogar = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                            $grupoactual->GRUPFAMI_ID)
                            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                            ->where('bnfgrupfami_parentesco', Constante::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR)
                            ->first();
                        if (!empty($hijoJefeHogar)) {
                            $per_run = $hijoJefeHogar->Persona()
                                ->first()
                                ->PER_RUN;
                            if (!is_null($per_run)) {
                                $per_run_ws = Utiles::getPersona($per_run);
                                if (is_numeric($per_run_ws)) {
                                    if ($per_run_ws == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                                        $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                                    } elseif ($per_run_ws == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                                        $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
                                    } else {
                                        $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                                    }
                                    return $salida(0, $mensaje);
                                }
                                //aunque sea HIJO, debe ser mayor igual de 18---- se quita discpacidad 25-05
                                if ($per_run_ws->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $hijoJefeHogar->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_1_JEFE_DE_HOGAR;
                                    if ($existe->BNFGRUPFAMI_INFORMANTE == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                                        $hijoJefeHogar->BNFGRUPFAMI_INFORMANTE = Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
                                    }
                                    $hijoJefeHogar->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                    $hijoJefeHogar->save();
                                    // los demás integrantes deben quedar en parentesco 99
                                    $integrantesSinJH = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                                        $grupoactual->GRUPFAMI_ID)
                                        ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                        ->where('bnfgrupfami_parentesco', '!=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                        ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                                        ->get();
                                    if (count($integrantesSinJH) > 0) {
                                        foreach ($integrantesSinJH as $r) {
                                            $r->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_99_POR_DEFINIR;
                                            $r->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                            $r->save();
                                        }
                                    }
                                    return $salida(1, 'La persona ha sido desvinculada correctamente.');
                                }
                            }
                        }

                        // Cuarto, buscar al hijo del conyuge, con run
                        $hijoConyuge = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                            $grupoactual->GRUPFAMI_ID)
                            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                            ->where('bnfgrupfami_parentesco', Constante::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA)
                            ->first();
                        if (!empty($hijoConyuge)) {
                            $per_run = $hijoConyuge->Persona()
                                ->first()
                                ->PER_RUN;
                            if (!is_null($per_run)) {
                                $per_run_ws = Utiles::getPersona($per_run);
                                if (is_numeric($per_run_ws)) {
                                    if ($per_run_ws == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                                        $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                                    } elseif ($per_run_ws == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                                        $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
                                    } else {
                                        $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                                    }
                                    return $salida(0, $mensaje);
                                }
                                //aunque sea HIJO, debe ser mayor igual de 18---- se quita discpacidad 25-05
                                if ($per_run_ws->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $hijoConyuge->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_1_JEFE_DE_HOGAR;
                                    if ($existe->BNFGRUPFAMI_INFORMANTE == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                                        $hijoConyuge->BNFGRUPFAMI_INFORMANTE = Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
                                    }
                                    $hijoConyuge->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                    $hijoConyuge->save();
                                    // los demás integrantes deben quedar en parentesco 99
                                    $integrantesSinJH = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                                        $grupoactual->GRUPFAMI_ID)
                                        ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                        ->where('bnfgrupfami_parentesco', '!=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                        ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                                        ->get();
                                    if (count($integrantesSinJH) > 0) {
                                        foreach ($integrantesSinJH as $r) {
                                            $r->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_99_POR_DEFINIR;
                                            $r->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                            $r->save();
                                        }
                                    }
                                    return $salida(1, 'La persona ha sido desvinculada correctamente.');
                                }
                            }
                        }

                        // Quinto, Si no hay conyuge o hijo ambos, ir al primer >= 18
                        $integrantesConRun = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                            $grupoactual->GRUPFAMI_ID)
                            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                            ->orderBy('bnfgrupfami_parentesco', 'asc')
                            ->get();
                        foreach ($integrantesConRun as $v) {
                            $per_run = $v->Persona()
                                ->first()
                                ->PER_RUN;
                            if (!is_null($per_run)) {
                                $per_run_ws = Utiles::getPersona($per_run);
                                if (is_numeric($per_run_ws)) {
                                    if ($per_run_ws == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                                        $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
                                    } elseif ($per_run_ws == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                                        $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
                                    } else {
                                        $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
                                    }
                                    return $salida(0, $mensaje);
                                }
                                //se quita discpacidad 25-05
                                if ($per_run_ws->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $v->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_1_JEFE_DE_HOGAR;
                                    if ($existe->BNFGRUPFAMI_INFORMANTE == Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE) {
                                        $v->BNFGRUPFAMI_INFORMANTE = Constante::RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE;
                                    }
                                    $v->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                    $v->save();

                                    $integrantesSinJH = BeneficiarioGrupoFamiliar::where('bnfgrupfami_grupofamiliar',
                                        $grupoactual->GRUPFAMI_ID)
                                        ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                        ->where('bnfgrupfami_parentesco', '!=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                        ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                                        ->get();
                                    if (count($integrantesSinJH) > 0) {
                                        foreach ($integrantesSinJH as $r) {
                                            $r->BNFGRUPFAMI_PARENTESCO = Constante::PARENTESCO_99_POR_DEFINIR;
                                            $r->BNFGRUPFAMI_FECHA_MODIFICACION = date('Ymd');
                                            $r->save();
                                        }
                                    }
                                    return $salida(1, 'La persona ha sido desvinculada correctamente.');
                                }
                            }
                        }
                        // En este punto quiere decir que no se pudo encontrar JH por lo tanto
                        // el nuevo grupo queda anulado (estado = 6), no vigente y calculo no activo
                        $grupoactual->GRUPFAMI_ESTADO = 6; //anulado
                        $grupoactual->GRUPFAMI_VIGENTE = 2;
                        $grupoactual->GRUPFAMI_CALCULOACTIVO = 2;
                        $grupoactual->save();

                        return $salida(1, 'La persona ha sido desvinculada correctamente.');
                    } else {
                        return $salida(1, 'La persona ha sido desvinculada correctamente.');
                    }
                }
            } else {
                return $salida(1, 'La persona ha sido desvinculada correctamente.');
            }

            return $salida(-1, 'La persona no existe en el grupo familiar.');
        } catch (Throwable $ex) {
            Log::error('Error desvinculación automática: ' . $ex);
            return $salida(0, 'Error inesperado #1114 ' . $ex->getMessage());
        }
    }
}
