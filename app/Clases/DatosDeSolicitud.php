<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\GrupoFamiliarExport;
use App\Models\Personas;
use App\Models\PersonasTokenMail;
use App\Models\PersonasExport;
use App\Models\RJuridicos;
use App\Models\Solicitud;
use App\Models\TipoSolicitud;
use App\Models\VW_DIRECTEMAR;
use App\Models\VWDetalleTasacionVehiculos;
use App\Models\VWPropiedad;
use App\Utiles;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Clases\Tramite;

class DatosDeSolicitud
{
    protected $hidden = ['rn'];
    protected $filled = [];

    public function getModel()
    {
        return new Solicitud();
    }

    public function findEnDigitacionRunCiudadanoComunaIneByIdSolicitud($idSolicitud)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select(
                    [
                        'id_solicitud',
                        'id_padre_solicitud',
                        'run_ciudadano as runCiudadano',
                        'com_id as comunaIne',
                        'dato_nuevo',
                        'id_estado',
                    ]
                )
                ->where('id_solicitud', $idSolicitud)
                ->where('id_estado', Constante::ESTADO_SOLICITUD_11_EN_DIGITACION)
                ->first();
            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function findByIdSolicitud($idSolicitud, $tipoSolicitud = null)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->where('id_solicitud', $idSolicitud)
                ->whereIn('vigente', [
                    Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                    Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR,
                ]);
            if (!is_null($tipoSolicitud)) {
                $datosDeSolicitud->where('id_tipo_solicitud', $tipoSolicitud);
            }
            $datosDeSolicitud = $datosDeSolicitud->first();
            if (is_null($datosDeSolicitud)) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function findByIdPadreSolicitud($idPadreSolicitud, $tipoSolicitud = null)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->where('id_padre_solicitud', $idPadreSolicitud)
                ->whereIn('vigente', [
                    Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                    Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR,
                ]);
            if (!is_null($tipoSolicitud)) {
                if (is_array($tipoSolicitud)) {
                    $datosDeSolicitud
                        ->whereIn('id_tipo_solicitud', $tipoSolicitud);
                } else {
                    $datosDeSolicitud
                        ->where('id_tipo_solicitud', $tipoSolicitud);
                }
            }
            $datosDeSolicitud = $datosDeSolicitud
                ->get();

            if (is_null($datosDeSolicitud)) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudIncorporacionByIdPadreSolicitudIdPersona($idPadreSolicitud, $idPersona)
    {
        return $datosDeSolicitud = $this->getModel()
            ->where('id_padre_solicitud', $idPadreSolicitud)
            ->where('dato_nuevo', $idPersona)
            ->whereIn('vigente', [
                Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR,
            ])
            ->first();
    }

    public function getSolicitudesPorRun(
        $runCiudadano,
        $arrayTipoSolicitudes = null,
        $estadosNoFinalizados = null,
        $vigente = null,
        $arrayTipoIgnorados = null
    ) {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select([
                    'id_padre_solicitud',
                    'id_solicitud',
                    'id_estado',
                    'id_sub_estado',
                    'id_tipo_solicitud',
                    'solicitud',
                    'aviso_correo',
                    'com_id',
                    'fn_Obtener_Id_Region (com_id) reg_id',
                    'fecha_creacion',
                    'fecha_modificacion',
                    'canal',
                    'identidad_acreditada',
                ])
                ->where('id_tipo_solicitud', '<>', Constante::TIPO_SOLICITUD_28_SOLICITUD_CALLE_PENDIENTE)
                ->where('run_ciudadano', $runCiudadano);
            if (!is_null($arrayTipoSolicitudes)) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->whereIn('id_tipo_solicitud', $arrayTipoSolicitudes);
            }
            if (!is_null($arrayTipoIgnorados)) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->whereNotIn('id_tipo_solicitud', $arrayTipoIgnorados);
            }
            if ($estadosNoFinalizados) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);
            }
            if ($vigente) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE);
            }
            $datosDeSolicitud = $datosDeSolicitud
                ->orderBy('id_solicitud', 'desc')
                ->get();
            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesPorRunYOrigenRegistroServicio(
        $runCiudadano,
        $origenRegistro,
        $muestraVigente3 = false
    ) {
        $estadosDeVigencia = [Constante::VIGENTE_SOLICITUD_1_VIGENTE];
        if ($muestraVigente3) {
            $estadosDeVigencia = [
                Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR,
            ];
        }
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select(
                    [
                        'id_padre_solicitud as "id-padre-solicitud"',
                        'id_solicitud as "id-solicitud"',
                        'id_estado as "id-estado"',
                        'null as "estado"',
                        //'CASE id_estado WHEN 5 THEN \''.self::MENSAJE_APROBADO.'\' WHEN 7 THEN \''.self::MENSAJE_RECHAZADA.'\' WHEN 9 THEN \''.self::MENSAJE_DESISTIDA.'\' ELSE \''.self::MENSAJE_EN_REVISION.'\' END',
                        'id_sub_estado as "sub-estado"',
                        'id_tipo_solicitud as "id-tipo-solicitud"',
                        'solicitud',
                        'aviso_correo as "aviso-correo"',
                        'com_id as "comuna"',
                        'fecha_creacion as "fecha-creacion"',
                        'fecha_modificacion as "fecha-modificacion"',
                        'canal',
                        'origen_registro as "origen-registro"',
                        'obs_solicitud as "observaciones"',
                        'identidad_acreditada as "identidad-acreditada"',
                        'vigente',
                        'id_usuario_asignado as "id-usuario-asignado"',
                        'fecha_asignacion as "fecha-asignacion"',
                    ]
                )
                ->where('id_tipo_solicitud', '<>', Constante::TIPO_SOLICITUD_28_SOLICITUD_CALLE_PENDIENTE)
                ->where('run_ciudadano', $runCiudadano)
                ->where('origen_registro', $origenRegistro)
                ->whereIn('vigente', $estadosDeVigencia)
                ->orderBy('id_solicitud', 'desc')
                ->get();

            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesTramitablesPorRunExcluyendoOrigenRegistroServicio(
        $runCiudadano,
        $origenRegistroExcluido
    ) {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->where('id_tipo_solicitud', '<>', Constante::TIPO_SOLICITUD_28_SOLICITUD_CALLE_PENDIENTE)
                ->where('run_ciudadano', $runCiudadano)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->where(function ($query) use ($origenRegistroExcluido) {
                    $query
                        ->where('origen_registro', '<>', $origenRegistroExcluido)
                        ->orWhereNull('origen_registro');
                })
                ->orderBy('id_solicitud', 'desc')
                ->selectRaw(
                    'id_padre_solicitud as "id-padre-solicitud",'.
                    'id_solicitud as "id-solicitud",'.
                    'id_estado as "id-estado",'.
                    'null as "estado",'.
                    'id_sub_estado as "sub-estado",'.
                    'id_tipo_solicitud as "id-tipo-solicitud",'.
                    'solicitud,'.
                    'aviso_correo as "aviso-correo",'.
                    'com_id as "comuna",'.
                    'fecha_creacion as "fecha-creacion",'.
                    'fecha_modificacion as "fecha-modificacion",'.
                    'canal,'.
                    'origen_registro as "origen-registro",'.
                    'obs_solicitud as "observaciones",'.
                    'dato_actual as "dato-actual",'.
                    'dato_nuevo as "dato-nuevo",'.
                    'identidad_acreditada as "identidad-acreditada",'.
                    'vigente'.
                    'id_usuario_asignado as "id-usuario-asignado",'.
                    'fecha_asignacion as "fecha-asignacion",'.
                    'fecha_creacion_ciudadano as "fecha-creacion-ciudadano"'
                );

            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesDetalladasPorRunServicio(
        $runCiudadano,
        $soloTramitables = false,
        $origenRegistro = null
    ) {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select([
                    'id_padre_solicitud as "id-padre-solicitud"',
                    'id_solicitud as "id-solicitud"',
                    'ab_solicitud.id_estado as "id-estado"',
                    'nombre_estado as "estado"',
                    'ab_solicitud.id_sub_estado as "sub-estado"',
                    'id_tipo_solicitud as "id-tipo-solicitud"',
                    'solicitud',
                    'aviso_correo as "aviso-correo"',
                    'com_descripcion as "comuna"',
                    'reg_descripcion as "region"',
                    'fecha_creacion as "fecha-creacion"',
                    'fecha_modificacion as "fecha-modificacion"',
                    'canal',
                    'nombre_origen_registro as "origen-registro"',
                    'obs_solicitud as "observaciones"',
                    'dato_actual as "dato-actual"',
                    'dato_nuevo as "dato-nuevo"',
                    'identidad_acreditada as "identidad-acreditada"',
                ])
                ->join('ab_estado_solicitud', 'ab_solicitud.id_estado', '=', 'ab_estado_solicitud.id_estado')
                ->leftJoin('ab_sub_estado_solicitud', 'ab_solicitud.id_sub_estado', '=',
                    'ab_sub_estado_solicitud.id_sub_estado')
                ->join('comunas_ine', 'ab_solicitud.com_id', '=', 'comunas_ine.com_id')
                ->join('regiones', 'comunas_ine.com_region', '=', 'regiones.reg_id')
                ->leftJoin('ab_origen_registro', 'ab_solicitud.origen_registro', '=',
                    'ab_origen_registro.id_origen_registro')
                ->where('id_tipo_solicitud', '<>', Constante::TIPO_SOLICITUD_28_SOLICITUD_CALLE_PENDIENTE)
                ->where('run_ciudadano', $runCiudadano)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->orderBy('id_solicitud', 'desc');
            if ($soloTramitables) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->whereNotIn('ab_solicitud.id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);
            }
            $datosDeSolicitud = $datosDeSolicitud
                ->get();
            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesDetalladasPorIdSolicitud($idSolicitud)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select([
                    'id_padre_solicitud',
                    'id_solicitud',
                    'nombre_estado',
                    'nombre_sub_estado',
                    'id_tipo_solicitud',
                    'solicitud',
                    'aviso_correo',
                    'com_descripcion',
                    'reg_descripcion',
                    'fecha_creacion',
                    'fecha_modificacion',
                    'canal',
                    'nombre_origen_registro',
                    'obs_solicitud',
                    'identidad_acreditada',
                ])
                ->join('ab_estado_solicitud', 'ab_solicitud.id_estado', '=', 'ab_estado_solicitud.id_estado')
                ->leftJoin('ab_sub_estado_solicitud', 'ab_solicitud.id_sub_estado', '=',
                    'ab_sub_estado_solicitud.id_sub_estado')
                ->join('comunas_ine', 'ab_solicitud.com_id', '=', 'comunas_ine.com_id')
                ->join('regiones', 'comunas_ine.com_region', '=', 'regiones.reg_id')
                ->leftJoin('ab_origen_registro', 'ab_solicitud.origen_registro', '=',
                    'ab_origen_registro.id_origen_registro')
                ->where('id_tipo_solicitud', '<>', Constante::TIPO_SOLICITUD_28_SOLICITUD_CALLE_PENDIENTE)
                ->where('id_solicitud', $idSolicitud)
                ->orderBy('id_solicitud', 'desc')
                ->get();
            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    /**
     * Consulta solicitudes pendientes para cualquier miembro del grupo familiar (esquema asignacion)
     */
    public function getSolicitudesExcluyentesPorRun(
        $runCiudadano,
        $otrasSolicitudes = null,
        $verificaIncorporacionDesvinculacion = null,
        $todasSolicitudes = null
    ) {
        $datosDeSolicitudPorRunCiudadano = null;
        $arraySolicitudesExcluyentesPorRunCiudadano = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
            Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
            Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
        ];
        $arraySolicitudesExcluyentesPorDatoNuevoPerRun = [
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
        ];

        if ($todasSolicitudes == "VIA RUN") {
            /*
            * Se utiliza en la incoporacion y desvinculacion en caso que la persona tenga una solicitud excluyente
            * que haya sido gestionada para esta persona, a diferencia de las anteriores que son para el grupo familiar
            */
            $datosDeSolicitudPorRunCiudadano = $this->getModel()
                ->select([
                    'id_solicitud',
                    'id_tipo_solicitud',
                    'solicitud',
                    'run_ciudadano',
                    'id_estado',
                    'dato_nuevo',
                    'dato_actual',
                ])
                ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                    [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->where('run_ciudadano', $runCiudadano)
                ->get();

            $datosDeSolicitudPorRunCiudadano1 = $this->getModel()
                ->select([
                    'id_solicitud',
                    'id_tipo_solicitud',
                    'solicitud',
                    'run_ciudadano',
                    'id_estado',
                    'dato_nuevo',
                    'dato_actual',
                ])
                ->where('id_estado', Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR)
                ->where('run_ciudadano', $runCiudadano)
                ->get();
            $a = $datosDeSolicitudPorRunCiudadano->toArray();
            $b = $datosDeSolicitudPorRunCiudadano1->toArray();
            $datosDeSolicitudPorRunCiudadano = array_merge($a, $b);
        } elseif ($todasSolicitudes == "VIA DATO_NUEVO") {
            /*
             * Se utiliza para Query basadas en DATO_NUEVO --> Solicitudes de incoporacion y desvinculacion
             * en caso que la persona tenga una solicitud excluyente, que haya sido gestionada para esta persona
             * Tambien se incluye Query para solicituds especiales de actualizacion 5,6,14 basads en el DATO_ACTUAL
             */
            $perId = null;
            $personaIncorporarDesvincular = Personas::findByRun($runCiudadano);
            if ($personaIncorporarDesvincular !== null && $personaIncorporarDesvincular !== -1) {
                $perId = $personaIncorporarDesvincular->PER_ID;
            }
            $datosDeSolicitudPorRunCiudadano = [];
            if (!(is_null($perId))) {
                $datosDeSolicitudPorRunCiudadano1 = $this->getModel()
                    ->select([
                        'id_solicitud',
                        'id_tipo_solicitud',
                        'solicitud',
                        'run_ciudadano',
                        'id_estado',
                        'dato_nuevo',
                        'dato_actual',
                    ])
                    ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                        [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                    ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                    ->whereIn('id_tipo_solicitud', Constante::$solicitudBusquedaDatoNuevo)// parentezco
                    ->where('dato_nuevo', $perId)
                    ->get();

                // solo para solicitudes del tipo 5,6,14 (actualizacion)
                $datosDeSolicitudPorRunCiudadano2 = $this->getModel()
                    ->select([
                        'id_solicitud',
                        'id_tipo_solicitud',
                        'solicitud',
                        'run_ciudadano',
                        'id_estado',
                        'dato_nuevo',
                        'dato_actual',
                    ])
                    ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                        [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                    ->whereIn('id_tipo_solicitud', Constante::$solicitudesActualizacionESO)
                    ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                    ->where('dato_actual', $perId)
                    ->get();

                $datosDeSolicitudPorRunCiudadano3 = $this->getModel()
                    ->select([
                        'id_solicitud',
                        'id_tipo_solicitud',
                        'solicitud',
                        'run_ciudadano',
                        'id_estado',
                        'dato_nuevo',
                        'dato_actual',
                    ])
                    ->where('id_estado', Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL)
                    ->where('vigente', Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR)
                    ->whereIn('id_tipo_solicitud', Constante::$solicitudBusquedaDatoNuevo)// parentezco
                    ->where('dato_nuevo', $perId)
                    ->get();

                $a = $datosDeSolicitudPorRunCiudadano1->toArray();
                $b = $datosDeSolicitudPorRunCiudadano2->toArray();
                $c = $datosDeSolicitudPorRunCiudadano3->toArray();
                $datosDeSolicitudPorRunCiudadano = array_merge($a, $b, $c);
            }

        } elseif ($todasSolicitudes == "VIA GRUPO_FAMILIAR") {
            /*
             * Se utiliza para obtener las Solicitudes del grupo familiar, funcion de la cual se utiliza para
             * analizar y generar solicitudes-tramitables, al igual que las busquedas por RUN y DATO-NUEVO/ACTUAL
             */
            $datosDeSolicitudPorRunCiudadano1 = [];
            $datosDeSolicitudPorRunCiudadano2 = [];
            $datosDeSolicitudPorRunCiudadano3 = [];
            $datosDeSolicitudPorRunCiudadano4 = [];
            $beneficiariosDelGrupoFamiliar = BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
            $personaIncorporarDesvincular = DatosDeContacto::obtenerDatosContacto($runCiudadano);
            $runsPorIdGrupoFamiliar = array_column($beneficiariosDelGrupoFamiliar->toArray(), 'PER_RUN');
            $perIdporGrupoFamiliar = array_column($beneficiariosDelGrupoFamiliar->toArray(), 'PER_ID');
            // Elimina el RUN del usuario del grupo Familiar.
            if (in_array($runCiudadano, $runsPorIdGrupoFamiliar)) {
                $key = array_search($runCiudadano, $runsPorIdGrupoFamiliar);
                unset($runsPorIdGrupoFamiliar[$key]);
            }

            if ($personaIncorporarDesvincular) {
                if (in_array($personaIncorporarDesvincular['PER_ID'], $perIdporGrupoFamiliar)) {
                    $key2 = array_search($personaIncorporarDesvincular['PER_ID'], $perIdporGrupoFamiliar);
                    unset($perIdporGrupoFamiliar[$key2]);
                }

                if (count($runsPorIdGrupoFamiliar) > 0) {
                    $datosDeSolicitudPorRunCiudadano1 = $this->getModel()
                        ->select([
                            'id_solicitud',
                            'id_tipo_solicitud',
                            'solicitud',
                            'run_ciudadano',
                            'id_estado',
                            'dato_nuevo',
                            'dato_actual',
                        ])
                        ->whereNotIn('id_tipo_solicitud',
                            array_merge(Constante::$solicitudEspecialesCONST, Constante::$solicitudesActualizacionESO))
                        ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                            [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                        ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                        ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                        ->get();
                }
                if (count($perIdporGrupoFamiliar) > 0) {
                    $datosDeSolicitudPorRunCiudadano2 = $this->getModel()
                        ->select([
                            'id_solicitud',
                            'id_tipo_solicitud',
                            'solicitud',
                            'run_ciudadano',
                            'id_estado',
                            'dato_nuevo',
                            'dato_actual',
                        ])
                        ->whereIn('id_tipo_solicitud', Constante::$solicitudEspecialesCONST)
                        ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                            [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                        ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                        ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                        ->get();
                    // ->whereIn('dato_nuevo', $perIdporGrupoFamiliar)

                    $datosDeSolicitudPorRunCiudadano3 = $this->getModel()
                        ->select([
                            'id_solicitud',
                            'id_tipo_solicitud',
                            'solicitud',
                            'run_ciudadano',
                            'id_estado',
                            'dato_nuevo',
                            'dato_actual',
                        ])
                        ->whereIn('id_tipo_solicitud', Constante::$solicitudesActualizacionESO)
                        ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                            [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                        ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                        ->whereIn('dato_actual', $perIdporGrupoFamiliar)
                        ->get();

                    $datosDeSolicitudPorRunCiudadano4 = $this->getModel()
                        ->select([
                            'id_solicitud',
                            'id_tipo_solicitud',
                            'solicitud',
                            'run_ciudadano',
                            'id_estado',
                            'dato_nuevo',
                            'dato_actual',
                        ])
                        ->where('id_estado', Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL)
                        ->where('vigente', Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR)
                        ->whereIn('id_tipo_solicitud', Constante::$solicitudEspecialesCONST)// parentezco
                        ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                        ->get();
                }
                $a = (count($datosDeSolicitudPorRunCiudadano1)) > 0 ? $datosDeSolicitudPorRunCiudadano1->toArray() : [];
                $b = (count($datosDeSolicitudPorRunCiudadano2)) > 0 ? $datosDeSolicitudPorRunCiudadano2->toArray() : [];
                $c = (count($datosDeSolicitudPorRunCiudadano3)) > 0 ? $datosDeSolicitudPorRunCiudadano3->toArray() : [];
                $d = (count($datosDeSolicitudPorRunCiudadano4)) > 0 ? $datosDeSolicitudPorRunCiudadano4->toArray() : [];
                $datosDeSolicitudPorRunCiudadano = array_merge($a, $b, $c, $d);
            }
        } else {
            $beneficiariosDelGrupoFamiliar = BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
            $runsPorIdGrupoFamiliar = array_column($beneficiariosDelGrupoFamiliar->toArray(), 'PER_RUN');
            $datosDeSolicitudPorRunCiudadano = $this->getModel()
                ->select([
                    'id_solicitud',
                    'id_tipo_solicitud',
                    'solicitud',
                    'run_ciudadano',
                    'id_estado',
                    'dato_nuevo',
                    'dato_actual',
                ])
                ->whereIn('id_tipo_solicitud', array_merge($arraySolicitudesExcluyentesPorRunCiudadano,
                    $arraySolicitudesExcluyentesPorDatoNuevoPerRun))
                ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                    [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                ->get();

            if ($otrasSolicitudes != null) {
                // utilizado para todas aquellas "OTRAS" solicitudes distintasa de las declaradas en el array_merge
                $datosDeSolicitudPorRunCiudadano = $this->getModel()
                    ->select([
                        'id_solicitud',
                        'id_tipo_solicitud',
                        'solicitud',
                        'run_ciudadano',
                        'id_estado',
                        'dato_nuevo',
                        'dato_actual',
                    ])
                    ->whereNotIn('id_tipo_solicitud', array_merge($arraySolicitudesExcluyentesPorRunCiudadano,
                        $arraySolicitudesExcluyentesPorDatoNuevoPerRun))
                    ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                        [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                    ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                    ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                    ->get();
            }
            if ($verificaIncorporacionDesvinculacion != null) {
                // Se utiliza en la incoporacion y desvinculacion en caso que la persona tenga una solicitud excluyente
                // que haya sido gestionada para esta persona, a diferencia de las anteriores que son para el grupo familiar
                $datosDeSolicitudPorRunCiudadano = $this->getModel()
                    ->select([
                        'id_solicitud',
                        'id_tipo_solicitud',
                        'solicitud',
                        'run_ciudadano',
                        'id_estado',
                        'dato_nuevo',
                        'dato_actual',
                    ])
                    ->whereIn('id_tipo_solicitud', $arraySolicitudesExcluyentesPorRunCiudadano)
                    ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                        [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                    ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                    ->where('run_ciudadano', $runCiudadano)
                    ->get();
            }
        }
        if (is_array($datosDeSolicitudPorRunCiudadano)) {
            return $datosDeSolicitudPorRunCiudadano;
        } else {
            return ($datosDeSolicitudPorRunCiudadano->toArray());
        }
    }

    public function getSolicitudesExcluyentesPorRunIncorporarDesvincular($runIncorporarDesvincular)
    {
        $arraySolicitudesExcluyentesPorDatoNuevoPerRun = [
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
        ];

        $personaIncorporarDesvincular = Personas::findByRun($runIncorporarDesvincular);
        $perIdIncorporarDesvincular = $personaIncorporarDesvincular['PER_ID'];
        $datosDeSolicitudPorDatoNuevoPerRun = $this->getModel()
            ->select(['id_tipo_solicitud', 'solicitud', 'run_ciudadano', 'id_estado'])
            ->whereIn('id_tipo_solicitud', $arraySolicitudesExcluyentesPorDatoNuevoPerRun)
            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
            ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
            ->where('dato_nuevo', $perIdIncorporarDesvincular)
            ->get();

        return $datosDeSolicitudPorDatoNuevoPerRun->toArray();
    }

    /**
     * Verifica si ya existe solictud en trámite del mismo tipo para el ciudadano
     * $idSolicitud es para identificar si exite esa solicitud dentro de la transacción de la sessión actual
     */
    public function existeSolicitudDeTipo($tipoSolicitud, $runCiudadano, $idSolicitud = null)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->where('run_ciudadano', $runCiudadano)
                ->where('id_tipo_solicitud', $tipoSolicitud)
                ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE);
            if (isset($idSolicitud)) {
                $datosDeSolicitud = $datosDeSolicitud
                    ->where('id_solicitud', '!=', $idSolicitud);
            }
            $datosDeSolicitud = $datosDeSolicitud
                ->count();
        } catch (Exception $ex) {
            throw $ex;
        }

        return ($datosDeSolicitud > 0);
    }

    /**
     * Trae la última solicitud 2 o 202 que corresponda al $nuevoFolio, ya sea en tramitación o aprobada
     */
    public function getComunaDesdeSolicitudDomicilioVigentePorNuevoFolio($nuevoFolio)
    {
        $comuna = null;
        $solicitud = $this->getModel()
            ->where('dato_actual', $nuevoFolio)
            ->whereIn('id_tipo_solicitud', [
                Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
            ])
            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_7_9_RECHAZADOS_DESISTIDOS)
            ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
            ->orderBy('id_solicitud', 'desc')
            ->first(['COM_ID']);
        if ($solicitud) {
            $comuna = $solicitud->COM_ID;
        }

        return $comuna;
    }

    public function updateSolicitudesByListaIdSolicitudes(array $listaIdSolicitudes, array $atributos)
    {
        return $this->getModel()
            ->whereIn('id_solicitud', $listaIdSolicitudes)
            ->update($atributos);
    }

    public function getSolicitudDatosGlobales($runCiudadano)
    {
        /*
         * Funcion utilizada para el servicio de datos-globales seccion solicitudes en tramites
         * la cual permite indicar si una determinada persona tiene solicitudes en tramite (Blade rsh.tramitesRshCiudadano.dashIntegrantesHogar)
         * falta incorporar la ARC y que se proporcione la informacion que campos utiliza en la tabla AB_SOLICITUD.
         */
//        $todasSolicitudes = "VIA RUN";
//        $consultaA = self::getSolicitudesExcluyentesPorRun($runCiudadano, null, null, $todasSolicitudes);
        $todasSolicitudes = "VIA DATO_NUEVO";
        $consultaB = self::getSolicitudesExcluyentesPorRun($runCiudadano, null, null, $todasSolicitudes);
        $resultado ['solicitud-en-tramite'] = false;
        $resultado ['solicitud-de-salud'] = false;
        $resultado ['solicitud-de-ocupacioningreso'] = false;
        $resultado ['solicitud-de-educacion'] = false;
        if (count($consultaB) > 0) { // || count($consultaA) > 0
            //existen solicitude en tramite.
            $resultado ['solicitud-en-tramite'] = true;
//            if (count($consultaA) > 0) {
//                for ($i = 0; $i < count($consultaA); $i++) {
//                    if ($consultaA[$i]['id_tipo_solicitud'] == Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD) {
//                        $resultado ['solicitud-de-salud'] = true;
//                    }
//                    if ($consultaA[$i]['id_tipo_solicitud'] == Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION) {
//                        $resultado ['solicitud-de-educacion'] = true;
//                    }
//                    if ($consultaA[$i]['id_tipo_solicitud'] == Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS) {
//                        $resultado ['solicitud-de-ocupacioningreso'] = true;
//                    }
//                }
//            }
            if (count($consultaB) > 0) {
                for ($i = 0; $i < count($consultaB); $i++) {
                    if ($consultaB[$i]['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD) {
                        $resultado ['solicitud-de-salud'] = true;
                    }
                    if ($consultaB[$i]['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION) {
                        $resultado ['solicitud-de-educacion'] = true;
                    }
                    if ($consultaB[$i]['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS) {
                        $resultado ['solicitud-de-ocupacioningreso'] = true;
                    }
                }
            }
        }

        return $resultado;
    }

    public function getSolicitudDatosGlobales2($persona, $runsPorIdGrupoFamiliar)
    {
        /*
         * Se utiliza para Query basadas
         * Solicitudes en estado de borrador
         */
//        $perId = null;
        $datosDeSolicitud = null;
//        $conjuntoFINAL = [];

//        $persona = (new DatosDeContacto())->findByRun($runCiudadano);
//        $beneficiariosDelGrupoFamiliar = (new DatosBeneficiarios())->findBeneficiariosByRun($runCiudadano);
//        $runsPorIdGrupoFamiliar = array_column($beneficiariosDelGrupoFamiliar->toArray(), 'per_run');
        if ($persona) {
//            $perId = $persona['per_id'];
            $perId = $persona->PER_ID;
            $runCiudadano = $persona->PER_RUN;
        }
        if (!(is_null($perId))) {
            $consultaSegunOrigenRegistro = $this->getModel()
//                ->select('id_solicitud', 'id_padre_solicitud', 'id_tipo_solicitud')
                ->select(['id_solicitud', 'id_tipo_solicitud'])
                //    ->whereIn('id_estado', Constante::ESTADO_SOLICITUD_11_EN_DIGITACION)
                //    ->where('vigente', Constante::VIGENTE_SOLICITUD_3_BORRADOR)
                //    ->where('origen_registro', $origenRegistro)
                ->where('run_ciudadano', $runCiudadano)
                ->get();

            $datosDeSolicitudPorDatoNuevo = $this->getModel()
//                ->select('id_solicitud', 'id_padre_solicitud', 'id_tipo_solicitud')
                ->select(['id_solicitud', 'id_tipo_solicitud'])
                ->whereIn('id_tipo_solicitud', Constante::$solicitudEspecialesCONST)
                ->whereNotIN('id_estado', [Constante::ESTADO_SOLICITUD_11_EN_DIGITACION])
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->where('dato_nuevo', $perId)
                ->get();

            $datosDeSolicitudPorDatoActual = $this->getModel()
                //                ->select('id_solicitud', 'id_padre_solicitud', 'id_tipo_solicitud')
                ->select(['id_solicitud', 'id_tipo_solicitud'])
                ->whereIn('id_tipo_solicitud', Constante::$solicitudesActualizacionESO)
                ->whereNotIN('id_estado', [Constante::ESTADO_SOLICITUD_11_EN_DIGITACION])
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->where('dato_actual', $perId)
                ->get();

            $datosDeSolicitudPorRunGrupoFamiliar = $this->getModel()
                //                ->select('id_solicitud', 'id_padre_solicitud', 'id_tipo_solicitud')
                ->select(['id_solicitud', 'id_tipo_solicitud'])
                ->whereIn('id_tipo_solicitud', [Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                    Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA])
                ->whereNotIN('id_estado', [Constante::ESTADO_SOLICITUD_11_EN_DIGITACION])
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->whereIn('run_ciudadano', $runsPorIdGrupoFamiliar)
                ->get();

            $a = (count($datosDeSolicitudPorDatoNuevo)) > 0 ? $datosDeSolicitudPorDatoNuevo->toArray() : [];
            $b = (count($datosDeSolicitudPorDatoActual)) > 0 ? $datosDeSolicitudPorDatoActual->toArray() : [];
            $c = (count($datosDeSolicitudPorRunGrupoFamiliar)) > 0 ? $datosDeSolicitudPorRunGrupoFamiliar->toArray() : [];
            $d = (count($consultaSegunOrigenRegistro)) > 0 ? $consultaSegunOrigenRegistro->toArray() : [];

            //-----------------------------------------------------------------//

            // Limpieza de solicitudes Individuales v/s otros por mi

            $solicitudesTEMP = $a;
            if (count($a) && (count($b))) {
                $largoSolicitudA = count($a);
                $largoSolicitudB = count($b);
                if ($largoSolicitudA > 0 && $largoSolicitudB > 0) {
                    for ($i = 0; $i < $largoSolicitudA; $i++) {
                        for ($ii = 0; $ii < $largoSolicitudB; $ii++) {
                            if ($a[$i] == $b[$ii]) {
                                unset($solicitudesTEMP[$i]);
                            }
                        }
                    }
                }
            }
            $conjuntoA = array_merge($solicitudesTEMP, $b);

            $solicitudesTEMP2 = $c;
            if (count($c) && (count($d))) {
                $largoSolicitudC = count($c);
                $largoSolicitudD = count($d);
                if ($largoSolicitudC > 0 && $largoSolicitudD > 0) {
                    for ($i = 0; $i < $largoSolicitudC; $i++) {
                        for ($ii = 0; $ii < $largoSolicitudD; $ii++) {
                            if ($c[$i] == $d[$ii]) {
                                unset($solicitudesTEMP2[$i]);
                            }
                        }
                    }
                }
            }
            $conjuntoB = array_merge($solicitudesTEMP2, $d);

            $solicitudesSemiFinal = $conjuntoA;
            if (count($conjuntoA) && (count($conjuntoB))) {
                $largoSolicitudConjuntoA = count($conjuntoA);
                $largoSolicitudConjuntoB = count($conjuntoB);
                if ($largoSolicitudConjuntoA > 0 && $largoSolicitudConjuntoB > 0) {
                    for ($i = 0; $i < $largoSolicitudConjuntoA; $i++) {
                        for ($ii = 0; $ii < $largoSolicitudConjuntoB; $ii++) {
                            if ($conjuntoA[$i] == $conjuntoB[$ii]) {
                                unset($solicitudesSemiFinal[$ii]);
                            }
                        }
                    }
                }
            }
            $conjuntoFINAL = array_merge($solicitudesSemiFinal, $conjuntoB);
            if (count($conjuntoFINAL) > 0) {
                for ($i = 0; $i < count($conjuntoFINAL); $i++) {
                    $idSolicitudes [] = $conjuntoFINAL[$i]['ID_SOLICITUD'];
                }
                $datosDeSolicitud = self::getSolicitudesDetalladasParaCiudadano($idSolicitudes);
            }
            /* "presuntamente" esto hace lo mismo que el codigo de arriba
            ‌‌$idSolicitudes = array_unique(array_column(array_merge($a, $b, $c, $d), 'id_solicitud'));
            $datosDeSolicitud = self::getSolicitudesDetalladasParaCiudadano($idSolicitudes);
            */
            if ($datosDeSolicitud) {
                $datosDeSolicitud->map(function ($item) {
                    $item->{'CIUDADANO_ASOCIADO'} = null;
                    if (in_array($item['ID_TIPO_SOLICITUD'], [
                        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                    ])) {
                        $datoPersona = Personas::find($item['DATO_NUEVO']);
                        $run = $datoPersona['PER_RUN']."-".$datoPersona['PER_DV'];
                        $nombre = ucwords(strtolower(
                            Utiles::cambiaTildeASuLetra($datoPersona['PER_NOMBRES']).' '.
                            Utiles::cambiaTildeASuLetra($datoPersona['PER_APELLIDOPATERNO'])));
                        $item->{'CIUDADANO_ASOCIADO'} = $run." ".$nombre;
                    }
                    /*$tramite = Tramite::instanciarSubClase(null, $item['ID_SOLICITUD']);
                    if(isset($tramite->titular)){
                        $item->{'RUN_TITULAR'} =  $tramite->titular->PER_RUN;
                    }
                    if(isset($tramite->beneficiario)){
                        $item->{'RUN_BENEFICIARIO'} =  $tramite->beneficiario->PER_RUN;
                    }*/
                });
            }
        }

        if ($datosDeSolicitud == null) {
            $datosDeSolicitud = $consultaSegunOrigenRegistro;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesDetalladasParaCiudadano($idSolicitudes)
    {
        $datosDeSolicitud = null;
        try {
            $datosDeSolicitud = $this->getModel()
                ->select(
                    DB::RAW('AB_SOLICITUD.*'),
                    DB::RAW('AB_ESTADO_SOLICITUD.*'),
                    DB::RAW('AB_SUB_ESTADO_SOLICITUD.*'),
                    DB::RAW('COMUNAS_INE.*'),
                    DB::RAW('REGIONES.*'),
                    DB::RAW('AB_ORIGEN_REGISTRO.*'),
                    DB::RAW('(SELECT count(1) from AB_SOLICITUD_ADJUNTOS WHERE AB_SOLICITUD_ADJUNTOS.DOC_IDSOLICITUD=AB_SOLICITUD.id_solicitud) as "numero_documentos"'))
                ->join('ab_estado_solicitud', 'ab_solicitud.id_estado', '=', 'ab_estado_solicitud.id_estado')
                ->leftJoin('ab_sub_estado_solicitud', 'ab_solicitud.id_sub_estado', '=',
                    'ab_sub_estado_solicitud.id_sub_estado')
                ->join('comunas_ine', 'ab_solicitud.com_id', '=', 'comunas_ine.com_id')
                ->join('regiones', 'comunas_ine.com_region', '=', 'regiones.reg_id')
                ->leftJoin('ab_origen_registro', 'ab_solicitud.origen_registro', '=',
                    'ab_origen_registro.id_origen_registro')
                //    ->where('id_tipo_solicitud', '<>', 28)// calle pendiente
                ->whereIn('id_solicitud', $idSolicitudes)
                //    ->whereIn('run_ciudadano', $runCiudadano)
                //    ->whereIn('vigente', array(Constante::VIGENTE_SOLICITUD_1_VIGENTE, self::VIGENTE_SOLICITUD_3_BORRADOR))
                ->orderBy('id_solicitud', 'desc');
            $datosDeSolicitud = $datosDeSolicitud->get();
            if (!$datosDeSolicitud) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return $datosDeSolicitud;
    }

    public function getSolicitudesPorFiltro($filtro = [])
    {
        $tipoSolicitud = isset($filtro['tipoSolicitud']) ? $filtro['tipoSolicitud'] : null;
        $runCiudadano = isset($filtro['runCiudadano']) ? $filtro['runCiudadano'] : null;
        $datoActual = isset($filtro['datoActual']) ? $filtro['datoActual'] : null;
        $usuarioCreacion = isset($filtro['usuarioCreacion']) ? $filtro['usuarioCreacion'] : null;

        $datosDeSolicitud = $this->getModel()
            ->select('ab_solicitud.*,personas.*,comunas_ine.*,ab_estado_solicitud.*')
            ->addSelect(DB::raw("(SELECT per_dv FROM personas WHERE per_run = ab_solicitud.run_ciudadano) as dv_ciudadano"))
            ->join('personas', 'ab_solicitud.id_usuario_asignado', '=', 'personas.per_run')
            ->join('comunas_ine', 'comunas_ine.com_id', '=', 'ab_solicitud.com_id')
            ->join('ab_estado_solicitud', 'ab_estado_solicitud.id_estado', '=', 'ab_solicitud.id_estado')
            ->whereNotIn('ab_solicitud.id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);

        if (!is_null($usuarioCreacion)) {
            $datosDeSolicitud = $datosDeSolicitud->where('id_usuario_creacion', $usuarioCreacion);
        }
        if (!is_null($tipoSolicitud)) {
            $datosDeSolicitud = $datosDeSolicitud->where('id_tipo_solicitud', $tipoSolicitud);
        }
        if (!is_null($runCiudadano)) {
            $datosDeSolicitud = $datosDeSolicitud->where('run_ciudadano', $runCiudadano);
        }
        if (!is_null($datoActual)) {
            $datosDeSolicitud = $datosDeSolicitud->where('dato_actual', $datoActual);
        }

        return $datosDeSolicitud->get();
    }

    public function getSolicitudBorrador($runCiudadano, $idSolicitud)
    {
        $valSolIngReg = Solicitud::where('id_solicitud', $idSolicitud)
            ->where('run_ciudadano', $runCiudadano)
            ->where('id_estado', Constante::ESTADO_SOLICITUD_11_EN_DIGITACION)
            ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO)
            ->where('vigente', Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR)
            ->get();

        return $valSolIngReg;
    }

    public function getPersona($colum, $atrr)
    {
        $persona = Personas::where($colum, $atrr)
            ->where('per_vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();

        return $persona;
    }

    public static function busquedaSolicitudexRunIntegrante($runCiudadano, $idtiposolicitud, $idsubtiposolicitud = null)
    {
        $busqueda = Solicitud::where('id_tipo_solicitud', $idtiposolicitud)
            ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
            ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
            ->where('run_integrante', $runCiudadano);
        if(isset($idsubtiposolicitud)){
            $busqueda->where('id_sub_tipo_solicitud', $idsubtiposolicitud);
        }
        $busqueda = $busqueda->get();

        return $busqueda;
    }

    public static function getSolicitudesExcluyentesLogicaNegocioPorRunAsociadoMensajes($runIncorporarDesvincular)
    {
        $arraySolicitudesPorRun = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
        ];
        $dataSolicitudesRunIncorporarDesvincularEnTramite = self::formatearSolicitudesRunIncorporarDesvincularEnTramite();
        if (is_null($runIncorporarDesvincular)) {
            return $dataSolicitudesRunIncorporarDesvincularEnTramite;
        }
        $solicitudesExcluyentesPorRun = (new DatosDeSolicitud())->getSolicitudesExcluyentesPorRun($runIncorporarDesvincular);
        $mensajeSolicitudesExcluyentesLogicaNegocioPorRunConRsh = "El ciudadano RUN "
            .$runIncorporarDesvincular."-".Utiles::dv($runIncorporarDesvincular)." no puede realizar esta solicitud, ya que tiene solicitud/es: "
            .implode(', ', array_column($solicitudesExcluyentesPorRun, 'solicitud'));

        foreach ($solicitudesExcluyentesPorRun as $solicitudExcluyentesPorRun) {
            if (in_array($solicitudExcluyentesPorRun['id_tipo_solicitud'], $arraySolicitudesPorRun)) {
                $dataSolicitudesRunIncorporarDesvincularEnTramite = self::agregaObjetoNoPuedeRealizarSolicitud(
                    $dataSolicitudesRunIncorporarDesvincularEnTramite,
                    [
                        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                    ],
                    $mensajeSolicitudesExcluyentesLogicaNegocioPorRunConRsh
                );

                return $dataSolicitudesRunIncorporarDesvincularEnTramite;
            }
        }

        $solicitudesExcluyentesPorRun = (new DatosDeSolicitud())->getSolicitudesExcluyentesPorRunIncorporarDesvincular($runIncorporarDesvincular);
        foreach ($solicitudesExcluyentesPorRun as $solicitudExcluyentePorRun) {
            $dataSolicitudesRunIncorporarDesvincularEnTramite[$solicitudExcluyentePorRun['id_tipo_solicitud']] = [
                "puede-realizar-solicitud" => false,
                "mensaje" => "El ciudadano RUN ".$runIncorporarDesvincular."-".Utiles::dv($runIncorporarDesvincular).
                    " ya posee una solicitud '".$solicitudExcluyentePorRun['solicitud']."' en trámite.",
            ];
        }

        return $dataSolicitudesRunIncorporarDesvincularEnTramite;
    }

    static function formatearSolicitudesRunIncorporarDesvincularEnTramite()
    {
        return [
            "3" => [
                "puede-realizar-solicitud" => true,
                "mensaje" => '',
            ],
            "4" => [
                "puede-realizar-solicitud" => true,
                "mensaje" => '',
            ],
        ];
    }

    private static function agregaObjetoNoPuedeRealizarSolicitud(
        $dataSolicitudesRunCiudadanoEnTramite,
        $arrayTipoDeSolicitud,
        $mensajeSolicitudesExcluyentesPorRunLogicaNegocioSinRshConIngresoEnTramite
    ) {
        foreach ($arrayTipoDeSolicitud as $tipoDeSolicitud) {
            $dataSolicitudesRunCiudadanoEnTramite[$tipoDeSolicitud] = [
                "puede-realizar-solicitud" => false,
                "mensaje" => $mensajeSolicitudesExcluyentesPorRunLogicaNegocioSinRshConIngresoEnTramite,
            ];
        }

        return $dataSolicitudesRunCiudadanoEnTramite;
    }

    public static function getTodasSolicitudes(
        $runCiudadano,
        $edad = null,
        $origenRegistro = null,
        $parametrosAdicionales = null
    ) {
        $listaSolicitudes = TipoSolicitud::solicitudesVigentes();

        if ($parametrosAdicionales) {
            $personaConsultada = $parametrosAdicionales['persona'];
            $personaExportConsultada = $parametrosAdicionales['personaExport'];
            $gAsignacion = $parametrosAdicionales['gAsignacion'];
            $gExport = $parametrosAdicionales['gExport'];
        } else {
            $personaConsultada = DatosDeContacto::obtenerDatosContacto($runCiudadano);
            $personaExportConsultada = null;
            $gAsignacion = null;
            $gExport = null;
        }

        // se recibe parametro $mismoFolio para indicar si debe o no procesar para retornar en espera de tramo
        // para todas las solicitudes, se deja acá para no realizar otra consulta externa, simplemente si viene la bandeja cortamos
        // el flujo normal para retornar a todas las solicitudes que no se pueden realizar.

        if ($gAsignacion && $gExport) {
            $folioAsignacion = $gAsignacion->GRUPFAMI_NUEVOFOLIO ?: null;
            $folioExport = $gExport->GRUPFAMI_NUEVOFOLIO ?: null;

            if ($folioAsignacion != $folioExport) {
                $arregloRespuestaNo = [];
                foreach ($listaSolicitudes as $key => $value) {
                    $arregloRespuestaNo[$key] = [
                        "puede-realizar-solicitud" => false,
                        "mensaje" => "Ciudadano en espera de tramo debido a que fue incorporado o cuenta con solicitud de ingreso aprobada recientemente",
                        "mensaje-ciudadano" => "Ciudadano en espera de tramo debido a que fue incorporado o cuenta con solicitud de ingreso aprobada recientemente",
                    ];
                }

                return $arregloRespuestaNo;
            }
        }
        $solicitudesTramitables = $listaSolicitudes;
        $solicitudSemiBloqueante = Constante::$solicitudSemiBloqueantexRun;
        $solicitudEspeciales = Constante::$solicitudEspecialesCONST;
        /*
         * con la finalidad de evitar ciclo de unset del array
         * ya correspoden a casos que se tratan de caracteristicas distintas del resto
         * que es una mezcla entre Especiales(3,4,101) y actualizacion (+ 5 + 6 + 14)
         */
        //**************************************
        $casoBloqueanteTotalExtremo = 0;
        $casoBloqueanteTotal = 0;
        $casoTotalBloqueanteGrupal = 0;
        $casoSemiBloqueante = 0;
        $casoEspeciales = 0;
        $casoSemiBloqueanteGrupal = 0;
        $casoEspecialBloqueanteGrupal = 0;
        $reglaEspecialConfirmar = false;
        //**************************************
        $nombreSolicitudes = [];
        $nombreSolicitudesOtrasPorMi = [];
        $solicitudesNoTramitables = [];
        $msjCiudadano = [];
        $msjGrupoFamiliar = [];
        $msjCiudadano0 = [];
        $concatenarSinRSH = null;
        $arregloRespuestaSi = [];
        $arregloRespuestaNo = [];
        $solicitudesRCM[1] = Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO;
        $solicitudesRCM[2] = Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO;
        $solicitudesRCM[3] = Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE;
        $solicitudesRCM[4] = Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE;
        $variable = 0;
        $solicitudesEnMiNombre = false;
        $mensajeCiudadanoUX = null;
        $solicitudesSemiBloqueanteRCM = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
        ];
        if (Utiles::inArrayMinuscula($origenRegistro,
            Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
            // debe quedar antes ya que se elimina con el unset mas abajo
            $solicitudesTramitables = $solicitudesRCM;
        }
        if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD ||
            $edad == null
        ) {
            $noCumpleRequisitosEdad = false;
            //$personaIncorporarDesvincular = DatosDeContacto::obtenerDatosContacto($runCiudadano);
            $personaIncorporarDesvincular = $personaConsultada ?: DatosDeContacto::obtenerDatosContacto($runCiudadano);
            if ($personaIncorporarDesvincular == null || is_numeric($personaIncorporarDesvincular)) {
                return $personaIncorporarDesvincular;
            }

            if ($personaIncorporarDesvincular) {
                $perId = $personaIncorporarDesvincular['PER_ID'];
            }
            $contadorParaSiMismo = 0;
            $contadorParaOtraPersona = 0;
            $contadorGrupoFamiliar = 0;
            $solicitudParaGrupoFamiliar = [];
            $solicitudParaSiMismo = [];
            $solicitudParaOtraPersona = [];
            $viaRun = "VIA RUN";
            $solicitudesActuales = (new DatosDeSolicitud())->getSolicitudesExcluyentesPorRun($runCiudadano, null, null,
                $viaRun);
            $viaDatoNuevo = "VIA DATO_NUEVO";
            $solicitudesRealizadaPorOtrosEnMiNombre = (new DatosDeSolicitud())->getSolicitudesExcluyentesPorRun($runCiudadano,
                null, null, $viaDatoNuevo);
            $viaDatoNuevo = "VIA GRUPO_FAMILIAR";
            $solicitudesRealizadaPorGrupoFamiliar = (new DatosDeSolicitud())->getSolicitudesExcluyentesPorRun($runCiudadano,
                null, null, $viaDatoNuevo);
            //-----------------------------------------------------------------//
            /**
             * Limpieza de solicitudes Individuales v/s otros por mi
             */
            $solicitudesRealizadaPorOtrosTEMP = $solicitudesRealizadaPorOtrosEnMiNombre;
            if ($solicitudesRealizadaPorOtrosEnMiNombre) {
                $largoSolicitudesOtros = count($solicitudesRealizadaPorOtrosEnMiNombre);
                $largoSolicitudesActuales = count($solicitudesActuales);
                if ($largoSolicitudesOtros > 0 && $largoSolicitudesActuales > 0) {
                    for ($i = 0; $i < $largoSolicitudesActuales; $i++) {
                        for ($ii = 0; $ii < $largoSolicitudesOtros; $ii++) {
                            if ($solicitudesRealizadaPorOtrosEnMiNombre[$ii] == $solicitudesActuales[$i]) {
                                unset($solicitudesRealizadaPorOtrosTEMP[$ii]);
                            }
                        }
                    }
                }
            }
            $solicitudesRealizadaPorOtrosEnMiNombre = $solicitudesRealizadaPorOtrosTEMP;
            /**
             * Limpieza de solicitudes Grupales v/s individuales/otros por mi
             */
            $solicitudesRealizadaPorGrupoFamiliarTEMP = $solicitudesRealizadaPorGrupoFamiliar;
            $array_mergeTemporal = array_merge($solicitudesActuales, $solicitudesRealizadaPorOtrosEnMiNombre);
            if (count($solicitudesRealizadaPorGrupoFamiliar)) {
                $largoSolicitudesGrupo = count($solicitudesRealizadaPorGrupoFamiliar);
                $largoSolicitudesActuales = count($array_mergeTemporal);
                if ($largoSolicitudesGrupo > 0 && $largoSolicitudesActuales > 0) {
                    for ($i = 0; $i < $largoSolicitudesActuales; $i++) {
                        for ($ii = 0; $ii < $largoSolicitudesGrupo; $ii++) {
                            if ($solicitudesRealizadaPorGrupoFamiliar[$ii] == $array_mergeTemporal[$i]) {
                                unset($solicitudesRealizadaPorGrupoFamiliarTEMP[$ii]);
                            }
                        }
                    }
                }
            }
            $solicitudesRealizadaPorGrupoFamiliar = $solicitudesRealizadaPorGrupoFamiliarTEMP;

            /**
             * CASO A --- CUANDO UNA PERSONA REALIZA UNA SOLICITUD PARA SI MISMO.
             * Query sobre RUN_CIUDADANO
             */
            if (count($solicitudesActuales)) {
                foreach ($solicitudesActuales as $idTipoSolicitudActual) {
                    if (in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'], Constante::$solicitudBloqueanteCONST)) {
                        $casoBloqueanteTotalExtremo++;
                    }
                    if (in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'],
                        Constante::$solicitudSemiBloqueanteCONST)) {
                        /**
                         * se elimina la solicitud que es del tipo SEMIBLOQUEANTE (incluyendo aquellos realizada
                         * por otros que son del mismo grupo familiar.
                         */
                        if ($idTipoSolicitudActual['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA) {
                            $reglaEspecialConfirmar = true;
                        }

                        $casoSemiBloqueante++;
                        $solicitudParaGrupoFamiliar[$runCiudadano] = $contadorGrupoFamiliar++;
                        unset($solicitudesTramitables[($idTipoSolicitudActual['ID_TIPO_SOLICITUD'])]);
                        $nombreSolicitudes [] = $idTipoSolicitudActual['SOLICITUD'];
                    }
                    if (in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'], Constante::$solicitudEspecialesCONST)) {
                        //$nombreSolicitudes [] = $idTipoSolicitudActual['SOLICITUD'];
                        $nombreSolicitudes [] = $idTipoSolicitudActual['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR ?
                            "Desvincular Jefe de Hogar" : $idTipoSolicitudActual['SOLICITUD'];
                        if ($perId == $idTipoSolicitudActual['DATO_NUEVO']) {
                            // La solicitud de (ingreso/desvinculacion/desvinculacionJH) es para la misma persona
                            $solicitudParaSiMismo[$runCiudadano] = $contadorParaSiMismo++;
                            $casoBloqueanteTotal++;
                            unset($solicitudesTramitables[($idTipoSolicitudActual['ID_TIPO_SOLICITUD'])]);
                        } elseif ($idTipoSolicitudActual['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR) {
                            // Creo una solicitud de desvinculacion de JH, pero fue a nombre de otra persona.
                            $casoSemiBloqueante++;
                            $solicitudParaOtraPersona[$runCiudadano] = $contadorParaOtraPersona++;
                            unset($solicitudesTramitables[($idTipoSolicitudActual['ID_TIPO_SOLICITUD'])]);
                        } else {
                            // Creo una solicitud de incorporacion(3)/desvinculacion(4) a nombre de otra persona.
                            $casoEspeciales++;
                            $solicitudParaOtraPersona[$runCiudadano] = $contadorParaOtraPersona++;
                        }
                    }
                    if (in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'],
                        Constante::$solicitudesActualizacionESO)) {
                        $nombreSolicitudes [] = $idTipoSolicitudActual['SOLICITUD'];
                        if ($perId == $idTipoSolicitudActual['DATO_ACTUAL']) {
                            $solicitudParaSiMismo[$runCiudadano] = $contadorParaSiMismo++;
                            // La persona solicito un tramite para si misma.
                            //SE AGREGA ESTA VALDIACION SI TIENE UNA ESO EN TRAMITE NO PUEDE REALIZAR UNA SOLICITUD 100 Y 7 y 202
                            // SEGUN LA INCIDENCIA RSH-1599 (30-01-2025)
                            unset($solicitudesTramitables[($idTipoSolicitudActual['ID_TIPO_SOLICITUD'])]);
                        } else {
                            $solicitudParaOtraPersona[$runCiudadano] = $contadorParaOtraPersona++;
                        }
                    }
                    // se elimna la solicitud que tiene el ciudadano en curso excepto para solicitudEspeciales
                    // ya que se elimino mas arriba
                    if (!(in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'], Constante::$omiteEliminarSolicitud))) {
                        $solicitudParaSiMismo[$runCiudadano] = $contadorParaSiMismo++;
                        if (!(in_array($idTipoSolicitudActual['ID_TIPO_SOLICITUD'], Constante::$omiteEliminarSolicitud)))
                        {
                            unset($solicitudesTramitables[($idTipoSolicitudActual['ID_TIPO_SOLICITUD'])]);
                        }
                        $nombreSolicitudes [] = $idTipoSolicitudActual['SOLICITUD'];
                    }
                }
            }
            $concatenarxRun = null;
            $contarSolicitudesxRun = self::contarSolicitudes($nombreSolicitudes);
            // $concatenarxRun0 = "Total Solicitudes Creadas por el Ciudadano ".count($solicitudesActuales).". Para si mísmo :".$contadorParaSiMismo.", para otros: "
            //    .$contadorParaOtraPersona.", Con afectación al grupo Familiar ".$contadorGrupoFamiliar .". Detalle: ".implode(" | ",$contarSolicitudesxRun);
            $concatenarxRun0 = "Total solicitudes creadas por el Ciudadano ".count($solicitudesActuales).", Detalle: ".implode(" | ",
                    $contarSolicitudesxRun);
            $concatenarxRun = count($contarSolicitudesxRun) > 0 ? $concatenarxRun0 : null;

            /**
             * CASO B --- CUANDO OTRA PERSONA REALIZA UNA SOLICITUD EN MI NOMBRE.
             * Query sobre DATO_NUEVO y DATO_ACTUAL
             * es decir El Tramite fue solicitado para esta persona
             */

            if (count($solicitudesRealizadaPorOtrosEnMiNombre)) {
                foreach ($solicitudesRealizadaPorOtrosEnMiNombre as $otrasEnMiNombre) {
                    if (in_array($otrasEnMiNombre['ID_TIPO_SOLICITUD'], Constante::$solicitudBloqueanteCONST)) {
                        if ($perId == $otrasEnMiNombre['DATO_NUEVO']) {
                            $casoBloqueanteTotalExtremo++;
                        }
                    }
                    if (in_array($otrasEnMiNombre['ID_TIPO_SOLICITUD'], Constante::$solicitudSemiBloqueanteCONST)) {
                        $casoSemiBloqueante++;
                        $nombreSolicitudesOtrasPorMi [] = $otrasEnMiNombre['SOLICITUD'];
                        unset($solicitudesTramitables[($otrasEnMiNombre['ID_TIPO_SOLICITUD'])]);
                        if ($idTipoSolicitudActual['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA) {
                            $reglaEspecialConfirmar = true;
                        }
                    }
                    if (in_array($otrasEnMiNombre['ID_TIPO_SOLICITUD'], Constante::$solicitudEspecialesCONST)) {
                        // La solicitud de incorporacion/desvinculacion/desvinculacion JH, a nombre de la persona.
                        $nombreSolicitudesOtrasPorMi [] = $otrasEnMiNombre['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR ?
                            "Desvincular Jefe de Hogar" : $otrasEnMiNombre['SOLICITUD'];
                        unset($solicitudesTramitables[($otrasEnMiNombre['ID_TIPO_SOLICITUD'])]);
                        $casoBloqueanteTotal++;
                    }
                    if (in_array($otrasEnMiNombre['ID_TIPO_SOLICITUD'], Constante::$solicitudesActualizacionESO)) {
                        $nombreSolicitudesOtrasPorMi [] = $otrasEnMiNombre['SOLICITUD'];
                        unset($solicitudesTramitables[($otrasEnMiNombre['ID_TIPO_SOLICITUD'])]);
                    }
                    // se elimna la solicitud que tiene el ciudadano en curso excepto para solicitudEspeciales
                    if (!(in_array($otrasEnMiNombre['ID_TIPO_SOLICITUD'], Constante::$omiteEliminarSolicitud))) {
                        unset($solicitudesTramitables[($otrasEnMiNombre['ID_TIPO_SOLICITUD'])]);
                        $nombreSolicitudesOtrasPorMi [] = $otrasEnMiNombre['SOLICITUD'];
                    }
                }
            }
            $concatenarxPerId = null;
            $contarSolicitudesxPerId = self::contarSolicitudes($nombreSolicitudesOtrasPorMi);
            $concatenarxPerId0 = "Solicitudes realizadas por otros ".count($nombreSolicitudesOtrasPorMi)." . Detalle: ".implode(" | ",
                    $contarSolicitudesxPerId);
            $concatenarxPerId = count($contarSolicitudesxPerId) > 0 ? $concatenarxPerId0 : null;

            /**
             * CASO C --- CUANDO EL GRUPO FAMILIAR TIENE SOLICITUDES.
             * solo verifica si no hay solicitudes mayores.
             */

            $tramiteGrupoFamiliar = null;
            $concatenarRutCantidad = [];
            $solicitudSemiBloqueanteGrupal = [
                Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
            ];

            if (count($solicitudesRealizadaPorGrupoFamiliar)) {
                foreach ($solicitudesRealizadaPorGrupoFamiliar as $otrasGrupoFamiliar) {
                    if (in_array($otrasGrupoFamiliar['ID_TIPO_SOLICITUD'], Constante::$solicitudBloqueanteCONST)) {
                        $casoSemiBloqueanteGrupal++;
                        $analizaRun[] = $otrasGrupoFamiliar['RUN_CIUDADANO'];
                        $tramiteGrupoFamiliar[] = [
                            $otrasGrupoFamiliar['RUN_CIUDADANO'],
                            $otrasGrupoFamiliar['SOLICITUD'],
                            $otrasGrupoFamiliar['DATO_NUEVO'],
                        ];
                    }
                    if (in_array($otrasGrupoFamiliar['ID_TIPO_SOLICITUD'], Constante::$solicitudEspecialesCONST)) {
                        $casoEspecialBloqueanteGrupal++;
                        $analizaRun [] = $otrasGrupoFamiliar['RUN_CIUDADANO'];
                        $otrasGrupoFamiliarSolicitudes = ($otrasGrupoFamiliar['ID_TIPO_SOLICITUD']) == Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR ?
                            "Desvincular Jefe de Hogar" : $otrasGrupoFamiliar['SOLICITUD'];
                        $tramiteGrupoFamiliar[] = [
                            $otrasGrupoFamiliar['RUN_CIUDADANO'],
                            $otrasGrupoFamiliarSolicitudes,
                            $otrasGrupoFamiliar['DATO_NUEVO'],
                        ];
                    }
                    if (in_array($otrasGrupoFamiliar['ID_TIPO_SOLICITUD'],
                        Constante::$solicitudBloqueanteTotalGrupal)) {
                        $casoTotalBloqueanteGrupal++;
                        $analizaRun [] = $otrasGrupoFamiliar['RUN_CIUDADANO'];
                        $tramiteGrupoFamiliar [] = [
                            $otrasGrupoFamiliar['RUN_CIUDADANO'],
                            $otrasGrupoFamiliar['SOLICITUD'],
                            $otrasGrupoFamiliar['DATO_NUEVO'],
                        ];
                    }
                }
            }
            if ($tramiteGrupoFamiliar) {
                $tramitesPorLaMismaPersona = array_count_values($analizaRun);
                foreach ($tramitesPorLaMismaPersona as $rut => $vecesRepetidas) {
                    for ($i = 0; $i < (count($tramiteGrupoFamiliar)); $i++) {
                        if ($rut == $tramiteGrupoFamiliar[$i][0]) {
                            $mismoTramite[] = $tramiteGrupoFamiliar[$i][1];
                        }
                    }
                    $abc = array_count_values($mismoTramite);
                    foreach ($abc as $nombre => $valor) {
                        $concatenarTramitesCantidad [] = $nombre.' ('.$valor.')';
                    }
                    $tramite = array_count_values($mismoTramite);
                    $concatenarRutCantidad [] = " RUN ".$rut."-".Utiles::dv($runCiudadano).
                        ' realizo '.$vecesRepetidas.' tramites de : '.implode(", ", $concatenarTramitesCantidad);
                }
            }

            $concatenarxGrupo = 'El grupo familiar cuenta con solicitudes vigentes, '.
                'han sido realizados por las siguientes personas.'.implode(" | ", $concatenarRutCantidad);
            $concatenarxGrupo = count($concatenarRutCantidad) > 0 ? $concatenarxGrupo : null;

            if (Utiles::inArrayMinuscula($origenRegistro,
                Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
                $solicitudSemiBloqueante = $solicitudesSemiBloqueanteRCM;
                $listaSolicitudes = $solicitudesRCM;
            }

            /**
             * Tabla Prioridad
             */
            $consultaExistenciaPersonaenExport = $personaExportConsultada ?: PersonasExport::findByRunCiudadano($runCiudadano);
            if ($casoBloqueanteTotalExtremo > 0) {
                $variable = 6; // Caso Bloqueante Total Extremo
            } elseif ($casoBloqueanteTotal > 0) {
                $variable = 3; // Caso Bloqueante Total
            } elseif ($casoSemiBloqueante > 0) {
                $variable = 1; // Caso Semi Bloqueante
            } elseif ($casoTotalBloqueanteGrupal > 0) {
                $variable = 5; // Caso Total Bloqueante Grupal
            } elseif ($casoEspeciales > 0) {
                $variable = 2; // Caso Especiales
            } elseif ($casoSemiBloqueanteGrupal > 0) {
                $variable = 7; // Caso Semi Bloqueante Grupal
            } elseif ($casoEspecialBloqueanteGrupal > 0) {
                $variable = 4; // Caso Especial Bloqueante Grupal
            } else {
                $buscaIngresoRegistro = Solicitud::where('run_ciudadano', $runCiudadano)
                    ->where('id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO)
                    ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO)
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->first();
                $variable = 0; // Todos los otros casos donde no existe un bloqueo, si no verificacion de forma individual
                if (is_null($consultaExistenciaPersonaenExport) || ($personaConsultada->TIENE_RSH == false)) {
                    if(isset($consultaExistenciaPersonaenExport) && $personaConsultada->TIENE_RSH == false){
                        $concatenarSinRSH = "Ciudadano no posee registro RSH o fue desvinculado recientemente, puede realizar Ingreso al Registro Social de Hogares o descargar la cartola en el siguiente enlace <a href='http://localhost/rsh-municipal/public/printcartola'>Aquí</a> ";
                    }
                    elseif(is_null($consultaExistenciaPersonaenExport) && $personaConsultada->TIENE_RSH == false){
                        $concatenarSinRSH = "Ciudadano no posee registro RSH, debe realizar Ingreso al Registro Social de Hogares o Incorporación a grupo familiar.";
                    }
                    $variable = 3; // Caso Bloqueante Total
                    $mensajeCiudadanoUX = "Usted no cuenta con un registro activo, lo invitamos a realizar una solicitud de Ingreso al Registro Social de Hogares";
                    $incorporado = false;
                    if (!$buscaIngresoRegistro) {
                        $perId = $personaConsultada->PER_ID;
                        $buscaIngresoRegistro = Solicitud::where('dato_nuevo', $perId)
                            ->where('id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO)
                            ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE)
                            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                            ->first();
                        if(!empty($buscaIngresoRegistro)){
                            $incorporado = true;
                        }
                        else{
                            $beneficiario=BeneficiarioGrupoFamiliar::findBeneficiariosByRunAnalisis($runCiudadano);
                            if(!empty($beneficiario['BNFGRUPFAMI_IDSOLICITUD'])){
                                $buscaIngresoRegistro = Solicitud::where('id_solicitud', $beneficiario['BNFGRUPFAMI_IDSOLICITUD'])
                                    ->where('id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO)
                                    ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO)
                                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                                    ->first();
                            }
                        }
                    }
                    $diferenciaDias = 31;
                    if ($buscaIngresoRegistro) {
                        $fecha_aprobacion = strtotime(substr($buscaIngresoRegistro->FECHA_MODIFICACION, 0, -9));
                        $fecha_actual = strtotime(date("Y") . "/" . date("m") . "/" . date("d"));
                        $diferenciaDias = round(( $fecha_actual - $fecha_aprobacion) / 86400);
                    }

                    if ($buscaIngresoRegistro && ($diferenciaDias <= 30)) {
                        if($incorporado){
                            $concatenarSinRSH = 'Ciudadano en espera de tramo. Tiene solicitud de Incorporación aprobada.';
                            $mensajeCiudadanoUX = 'Usted se encuentra en espera de tramo, ya posee una solicitud de Incorporación aprobada.';
                        } else {
                            $concatenarSinRSH = 'Ciudadano en espera de tramo. Tiene solicitud de Ingreso al registro aprobada.';
                            $mensajeCiudadanoUX = 'Usted se encuentra en espera de tramo, ya posee una solicitud de Ingreso al registro aprobada.';
                        }
                        $variable = 6;
                    } else {
                        $personaConsultada->TIENE_RSH = false;
                    }
                }
                if(($consultaExistenciaPersonaenExport) &&
                    ($buscaIngresoRegistro) &&
                    isset($consultaExistenciaPersonaenExport->PER_FIC_GRUPOFAMILIAR_ID)
                    && $variable != 6){
                    $esperaTramo = false;
                    $grupoFamiliar = GrupoFamiliarExport::findByIdGrupoFamiliar($consultaExistenciaPersonaenExport->PER_FIC_GRUPOFAMILIAR_ID);
                    if ($grupoFamiliar && ($grupoFamiliar->FECHA_CALIFICACION < $buscaIngresoRegistro->FECHA_MODIFICACION)) {
                        $esperaTramo = true;
                    }
                    if ($esperaTramo) {
                        $concatenarSinRSH = 'Ciudadano en espera de tramo. Tiene solicitud de Ingreso al registro aprobada.';
                        $mensajeCiudadanoUX = 'Usted se encuentra en espera de tramo, ya posee una solicitud de Ingreso al registro aprobada.';
                        $variable = 6;
                    }
                }
            }

            $msjCiudadano[] = $concatenarSinRSH." ".$concatenarxRun." ".$concatenarxPerId." ".$concatenarxGrupo;

            switch ($variable) {
                case 0:
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
                case 1:
                    // construye un solo array de acuerdo al tipo de regla.
                    array_push($solicitudSemiBloqueante, Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO);
                    if (!Utiles::inArrayMinuscula($origenRegistro,
                        Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
                        array_push($solicitudSemiBloqueante, Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION);
                        if ($reglaEspecialConfirmar) {
                            array_push($solicitudSemiBloqueante, Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS);
                        }
                    }
                    for ($cont2 = 0; ($cont2 < count($solicitudEspeciales));) {
                        array_push($solicitudSemiBloqueante, $solicitudEspeciales[$cont2]);
                        $cont2++;
                    }
                    // Se eliminan todas las solicitudes a excepcion de las definidas como OTRAS.
                    for ($cont = 0; ($cont < count($solicitudSemiBloqueante));) {
                        unset($solicitudesTramitables[($solicitudSemiBloqueante[$cont])]);
                        $cont++;
                    }
                    // $solicitudesTramitables !!
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
                case 2:
                    $solicitudesTemp = Constante::$solicitudSemiBloqueantexRun;
                    array_push($solicitudesTemp, Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO);
                    array_push($solicitudesTemp, Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION);
                    // Solo solicitudes especiales, es decir OTRAS + aquellas del tipo 3 y 4.
                    for ($cont = 0; ($cont < count($solicitudesTemp));) {
                        unset($solicitudesTramitables[($solicitudesTemp[$cont])]);
                        $cont++;
                    }
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    // $solicitudesTramitables !!
                    break;
                case 3:
                    // Solamente solicitudes bloqueantes (Ingreso al Registro), No puede hacer ninguna otra solicitud.
                    $solicitudesTemp = $listaSolicitudes;
                    unset($solicitudesTemp[1]);
                    $solicitudesTramitables2 = $solicitudesTramitables;
                    $solicitudesTramitables = array_diff($solicitudesTramitables2, $solicitudesTemp);
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
                case 4:
                    // *** CASOS GRUPALES ***
                    // para el caso de la solicitud numero 1..
                    array_push($solicitudSemiBloqueanteGrupal, Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO);
                    array_push($solicitudSemiBloqueanteGrupal, Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION);
                    $temporal = Constante::$solicitudBloqueanteTotalGrupal;
                    $otrasSolicitudes = $solicitudesTramitables;
                    foreach ($temporal as $indiceArray2) {
                        unset($otrasSolicitudes[$indiceArray2]);
                    }
                    $solicitudesTramitables = $otrasSolicitudes;
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
                case 5:
                    // CASOS GRUPALES
                    // Solamente solicitudes bloqueantes (Ingreso al Registro), No puede hacer ninguna otra solicitud.
                    $temporal = [
                        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                        Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                        Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
                        Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
                        Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
                        Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION,
                        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
                    ];
                    $otrasSolicitudes = $solicitudesTramitables;
                    foreach ($temporal as $indiceArray2) {
                        unset($otrasSolicitudes[$indiceArray2]);
                    }
                    $solicitudesTramitables = $otrasSolicitudes;
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
                case 6:
                    // No puede hacer ninguna otra solicitud ya que tiene una bloqueante TOTAL.
                    $solicitudesTramitables = [];
                    $solicitudesNoTramitables = $listaSolicitudes;
                    break;
                case 7:
                    // CASOS GRUPALES
                    // para el caso de la solicitud numero 3, 4 ,101..
                    array_push($solicitudSemiBloqueanteGrupal, Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO);
                    $temporal = [
                        Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                        Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                        Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
                        Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
                        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
                    ];
                    $otrasSolicitudes = $solicitudesTramitables;
                    foreach ($temporal as $indiceArray2) {
                        unset($otrasSolicitudes[$indiceArray2]);
                    }
                    $solicitudesTramitables = $otrasSolicitudes;
                    $solicitudesNoTramitables = array_diff_key($listaSolicitudes, $solicitudesTramitables);
                    break;
            }
        } else {
            // personas menores de 18 anos no pueden hacer tramites.
            $solicitudesTramitables = [];
            if (Utiles::inArrayMinuscula($origenRegistro,
                Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
                $listaSolicitudes = $solicitudesRCM;
            }
            $sql = "SELECT *
                    FROM PERSONAS
                    WHERE PER_VIGENTE = 1
                      AND PER_RUN=" . $runCiudadano . "
                      AND (calc_ingreso_pension > 0 )";
            $oPersonaExport = DB::connection('oracle_export')
                ->select($sql);
            if($oPersonaExport){
                $solicitudesTramitables[Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]=$listaSolicitudes[Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION];
                unset($listaSolicitudes[Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]);
            }
            //else{
                $solicitudesNoTramitables = $listaSolicitudes;
                $msjCiudadano[] = "El ciudadano RUN " . $runCiudadano . "-" . Utiles::dv($runCiudadano)
                    . " es menor de edad (" . $edad . ") lamentablemente no puede realizar esta solicitud, no cumple con los requisitos mínimos.";
                $noCumpleRequisitosEdad = true;
            //}

        }

        /**
         * ARRAY --- MENSAJES
         */
        $consultaExistenciaPersonaenExport = $personaExportConsultada ?: PersonasExport::findByRunCiudadano($runCiudadano);
        $solicitudARCBloqueada = array();

        //try {
        $listadoFuentes = DatosDeFuentes::getListaFuentes();
//            if ($listadoFuentes->getStatusCode() == Constante::STATUS_200_SUCCESS_OK){
//                $listadoFuentes = $listadoFuentes->getData('solicitudes');
//            } else {
//                $listadoFuentes = null;
//            }
//        } catch (Exception $exception) {
//            log::error('Problema al obtener las fuentes para los tramites ARC de ingreso');
//            $listadoFuentes = null;
//        }

        $grupoFamiliarCiudadano = $gAsignacion ?: GrupoFamiliar::findVigenteAprobadoByRunCiudadano($runCiudadano,
            false);
        $grupoFamiliarCiudadanoNuevoFolio = isset($grupoFamiliarCiudadano) ? $grupoFamiliarCiudadano->grupfami_nuevofolio : null;
        foreach ($solicitudesNoTramitables as $tipoSolicitud => $value) {
            if (in_array($tipoSolicitud, Constante::$solicitudesComplementoARC)) {
                unset($solicitudesNoTramitables[($tipoSolicitud)]);
                $solicitudesTramitables[$tipoSolicitud]= $listaSolicitudes[$tipoSolicitud];
            }
        }

        foreach ($solicitudesTramitables as $key => $value) {

            //aplicacion de regla para solicitudes Actualizacion RA
            //Falta agregar un mensaje acorde ya que actualmente solo posee mensajes genericos de no poder ya que hay solicitudes
            //por tanto se deja como no posible tramitar pero sin mensaje.
            //Issue #37 Proyecto municipal gitlab.mds.cl


            //no puede hacer 202 si es mayor de 18 y es institucionalizado
            if ($key == Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA) {
                $personaInstitucionalizado = $personaConsultada ?: DatosDeContacto::obtenerDatosContacto($runCiudadano);
                if ($personaInstitucionalizado->institucionalizado->ES_INSTITUCIONALIZADO == true) {
                    unset($solicitudesTramitables[Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA]);
                    $arregloRespuestaNo[$key] = [
                        "puede-realizar-solicitud" => false,
                        "mensaje" => 'Usuario cuenta con registro como persona institucionalizada, debe realizar un nuevo ingreso al registro',
                        "mensaje-ciudadano" => 'Usuario cuenta con registro como persona institucionalizada, debe realizar un nuevo ingreso al registro',
                    ];
                } else {

                }
            }

            if ($key == Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO) {
                if (!is_null($grupoFamiliarCiudadanoNuevoFolio)) {
                    $solAux19 = Solicitud::where('id_tipo_solicitud',
                        Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO)
                        ->where('dato_actual', $grupoFamiliarCiudadanoNuevoFolio)
                        ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                        ->where('vigente', 1)
                        ->first();
                    if ($solAux19) {
                        unset($solicitudesTramitables[$key]);
                        $solicitudesNoTramitables[$key] = $value;
                    }
                }
            }
            if ($key == Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA) {
                $valSol79 = false;
                if (!empty($consultaExistenciaPersonaenExport)){
                    if ($consultaExistenciaPersonaenExport->P_JURIDICA == 420) {
                        $valSol79 = true;
                    }
                }
            }

            $bloqueoSolicitud = false;
            if ((!empty($consultaExistenciaPersonaenExport)) && (!(is_null($listadoFuentes)))) {
                switch ($key) {
                    Case Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE:
                        $listadoFuentes60 = $listadoFuentes['solicitudes'][60]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes60)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE:
                        $listadoFuentes61 = $listadoFuentes['solicitudes'][61]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes61)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE:
                        $listadoFuentes62 = $listadoFuentes['solicitudes'][62]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes62)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS:
                        if ($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL == Constante::FUENTE_0_SIN_INGRESOS &&
                            $consultaExistenciaPersonaenExport->CALC_INGRESO_CAPITAL == Constante::FUENTE_0_SIN_INGRESOS &&
                            $consultaExistenciaPersonaenExport->CALC_FUENTE_PENSION == Constante::FUENTE_0_SIN_INGRESOS
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION:
                        $listadoFuentes63 = $listadoFuentes['solicitudes'][63]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_PENSION, $listadoFuentes63)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL:
                        $listadoFuentes64 = $listadoFuentes['solicitudes'][64]['fuentes'];
                        $fuenteCapitalCodigo = (!(is_null($consultaExistenciaPersonaenExport->CALC_INGRESO_CAPITAL)) &&
                            (int)$consultaExistenciaPersonaenExport->CALC_INGRESO_CAPITAL > 0) ?
                            Constante::FUENTE_200_INGRESO_DE_CAPITAL : null;
                        if (!in_array($fuenteCapitalCodigo, $listadoFuentes64)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ:
                        if ($consultaExistenciaPersonaenExport->CALC_PROPIEDADES_COSTOSAS == 0) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ:
                        if ($consultaExistenciaPersonaenExport->CALC_PROPIEDADES_COSTOSAS == 0) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL:
                        if ($consultaExistenciaPersonaenExport->CALC_VEHICULOS_COSTOSOS == 0
                        && $consultaExistenciaPersonaenExport->DNAVES_IND_MUY_ALTO_VALOR == 0) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL:
                        if ($consultaExistenciaPersonaenExport->CALC_COLEGIO_COSTOSO == 0) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL:
                        if ($consultaExistenciaPersonaenExport->CALC_COLEGIO_COSTOSO == 0) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_70_RECTIFICACION_INGRESO_TRABAJADOR_DEPENDIENTE:
                        $listadoFuentes70 = $listadoFuentes['solicitudes'][70]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes70)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_71_RECTIFICACION_INGRESO_TRABAJADOR_INDEPENDIENTE:
                        $listadoFuentes71 = $listadoFuentes['solicitudes'][71]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes71)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_72_RECTIFICACION_DE_INGRESOS_DE_CAPITAL:
                        $listadoFuentes72 = $listadoFuentes['solicitudes'][72]['fuentes'];
                        $fuenteCapitalCodigo = (!(is_null($consultaExistenciaPersonaenExport->CALC_INGRESO_CAPITAL)) &&
                            (int)$consultaExistenciaPersonaenExport->CALC_INGRESO_CAPITAL > 0) ?
                            Constante::FUENTE_200_INGRESO_DE_CAPITAL : null;
                        if (!in_array($fuenteCapitalCodigo, $listadoFuentes72)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_77_RECTIFICACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION:
                        $listadoFuentes77 = $listadoFuentes['solicitudes'][77]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_PENSION, $listadoFuentes77)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_78_RECTIFICACION_INGRESO_DEPENDIENTE_E_INDEPENDIENTE:
                        $listadoFuentes78 = $listadoFuentes['solicitudes'][78]['fuentes'];
                        if (!in_array($consultaExistenciaPersonaenExport->CALC_FUENTE_INGRESOLABORAL,
                            $listadoFuentes78)
                        ) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                    Case Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA:
                        if ($valSol79 == false) {
                            $bloqueoSolicitud = true;
                        }
                        break;
                }
                if ($bloqueoSolicitud == true) {
                    unset($solicitudesTramitables[$key]);
                    $solicitudesNoTramitables[$key] = $value;
                    //No cumple con los reglas de negocio
                    $solicitudARCBloqueada[$key] = $key;
                }
            }
        }

        //Bloqueo de tramites por validación de email (para todos los usuarios por defecto)
        if($origenRegistro == Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA){

            $resValidacionPersona = PersonasTokenMail::findByRunCiudadano($runCiudadano);

            //Realiza el bloqueo por defecto a todas las personas sin validar.
            if ($resValidacionPersona['estado-validacion'] != Constante::ESTADO_VALIDADO_VALIDA_EMAIL ||
                is_null($resValidacionPersona['email-temporal'])) {

                # solo bloquea cuando no existe una validacion de email
                foreach ($solicitudesTramitables as $key => $value) {
                    $arregloRespuestaSi[$key] = [
                        "puede-realizar-solicitud" => false,
                        "mensaje" => Constante::MSJ_BLOQUEO_VALIDA_EMAIL,
                        "mensaje-ciudadano" => Constante::MSJ_BLOQUEO_VALIDA_EMAIL
                    ];
                }

            } else {

                foreach ($solicitudesTramitables as $key => $value) {
                    $arregloRespuestaSi[$key] = [
                        "puede-realizar-solicitud" => true,
                        "mensaje" => "",
                        "mensaje-ciudadano" => ""
                    ];
                }

            }
        } else {
            foreach ($solicitudesTramitables as $key => $value) {
                $arregloRespuestaSi[$key] = [
                    "puede-realizar-solicitud" => true,
                    "mensaje" => "",
                    "mensaje-ciudadano" => ""
                ];
            }
        }
        //::

        if (count($solicitudesNoTramitables)) {
            if (!isset($mensajeCiudadanoUX)) {
                $mensajeCiudadanoUX = $noCumpleRequisitosEdad == true ? implode(" - ", $msjCiudadano) :
                    "No puede realizar esta solicitud, debido a que tiene una solicitud suya o de su grupo familiar en curso.";
            }
            foreach ($solicitudesNoTramitables as $key => $value) {
                if (in_array($key, $solicitudARCBloqueada)) {
                    $mensajeCiudadano = Constante::MENSAJE_NO_CUMPLE_REGLA_CIUDADANO;
                    $msjMunicipal = Constante::MENSAJE_NO_CUMPLE_REGLA_MUNICIPAL;
                } else {
                    $msjMunicipal = implode(" - ", $msjCiudadano);
                    $mensajeCiudadano = $mensajeCiudadanoUX;
                }
                $arregloRespuestaNo[$key] = [
                    "puede-realizar-solicitud" => false,
                    "mensaje" => $msjMunicipal,
                    "mensaje-ciudadano" => $mensajeCiudadano,
                ];
            }
        }

        return ($arregloRespuestaSi + $arregloRespuestaNo);
    }

    public static function contarSolicitudes($nombreSolicitudes)
    {
        $concatenarNombreCantidad = [];
        if (count($nombreSolicitudes) > 0) {
            //$solicitudesEnMiNombre = True;
            foreach ($nombreSolicitudes as $nombre1 => $valor1) {
                if ($valor1 == null) {
                    $nombreSolicitudes[$nombre1] = 'Sol. SIN NOMBRE';
                }
            }

            $abc = array_count_values($nombreSolicitudes);
            foreach ($abc as $nombre => $valor) {
                $concatenarNombreCantidad[] = $nombre.' ('.$valor.')';
            }
        }

        return ($concatenarNombreCantidad);
    }

    public static function validarSolicitudTramitableDomicilioViviendaTramo($solicitudes = null)
    {

        return null;
    }

    public static function busquedaSolicitudexRunIntegranteDatoNuevo($runCiudadano, $idtiposolicitud, $idsubtiposolicitud = [], $datoActual)
    {
        $busqueda = Solicitud::where('id_tipo_solicitud', $idtiposolicitud)
            ->whereIn('id_sub_tipo_solicitud', $idsubtiposolicitud)
            ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
            ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
            ->where('run_integrante', $runCiudadano)
            ->where('dato_actual', $datoActual)
            ->get();

        return $busqueda;
    }
    public static function busquedaSolicitudexRunIntegranteConBorrador($runCiudadano, $idtiposolicitudes)
    {
        $busqueda = Solicitud::whereIn('id_tipo_solicitud', $idtiposolicitudes)
            ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
            ->where('run_integrante', $runCiudadano)
            ->get();

        return $busqueda;
    }

    public static function busquedaSolicitudexRunJuridicoConBorrador($runSocio)
    {
        $rJuridicos = RJuridicos::where('RUN_SOCIO', $runSocio)
            ->where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('R_JUR_CALCULOACTIVO',Constante::VIGENTE_1_VIGENTE)
            ->select('RUN_SOCIEDAD')
            ->get();
        $runJuridicos = array();
        if($rJuridicos){
            foreach ($rJuridicos as $rJuridico) {
                $runJuridicos[] = $rJuridico->RUN_SOCIEDAD;
            }
        }
        $rJuridicos = RJuridicos::whereIn('RUN_SOCIEDAD', $runJuridicos)
            ->where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('R_JUR_CALCULOACTIVO',Constante::VIGENTE_1_VIGENTE)
            ->select('R_JUR_ID')
            ->get();

        $idJuridicos = array();
        if($rJuridicos){
            foreach ($rJuridicos as $rJuridico) {
                $idJuridicos[] = $rJuridico->R_JUR_ID;
            }
        }

        $busqueda = Solicitud::where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA)
            ->join('r_juridicos', 'ab_solicitud.dato_actual', '=',
                'r_juridicos.r_jur_id')
            ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
            ->whereIn('dato_actual', $idJuridicos)
            ->select('ID_TIPO_SOLICITUD','ID_SUB_TIPO_SOLICITUD','DATO_ACTUAL',
                'DATO_NUEVO','RUN_CIUDADANO','RUN_SOCIEDAD')
            ->get();

        return $busqueda;
    }

    public static function busquedaSolicitudexRunIntegranteConBorradorBienes($runCiudadano, $idtiposolicitudes)
    {
        $busqueda = Solicitud::whereIn('id_tipo_solicitud', $idtiposolicitudes)
            ->join('ever_sii_propiedades_det', 'ab_solicitud.id_solicitud', '=',
                'ever_sii_propiedades_det.idsolicitud')
            ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                [Constante::ESTADO_SOLICITUD_17_CONFIRMADO],[Constante::ESTADO_SOLICITUD_11_EN_DIGITACION]))
            ->where('run_integrante', $runCiudadano)
            ->select('ID_TIPO_SOLICITUD','ID_SUB_TIPO_SOLICITUD','DATO_ACTUAL',
                'DATO_NUEVO','RUN_CIUDADANO','ROL')
            ->get();

        return $busqueda;
    }
}
