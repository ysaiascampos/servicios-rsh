<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\ActualizacionMeses;
use App\Models\Afc;
use App\Models\Afp;
use App\Models\Colegio;
use App\Models\RJuridicos;
use App\Models\VWDetalleTasacionVehiculos;
use App\Models\VW_DIRECTEMAR;
use App\Models\VWPropiedad;
use App\Models\FuenteExport;
use App\Models\Isapre;
use App\Models\MatriculaEducacionSuperior;
use App\Models\PersonasExport;
use App\Utiles;
use Illuminate\Support\Collection;

class DetalleGrupoFamiliar
{
    public $bienRaiz;
    public $educacion;
    public $ingresos;
    public $pensionAlimentos;
    public $saludIsapre;
    public $vehiculo;
    public $juridico;
    public $mensaje;

    private $persona;
    private $integrantes;

    public function __construct($runCiudadano)
    {
        $this->persona = PersonasExport::findByRunCiudadano($runCiudadano);
        if (empty($this->persona)) {
            $this->mensaje = 'Persona no existe en export';
        } else {
            $this->integrantes = PersonasExport::findByIdGrupoFamiliarExport($this->persona->PER_FIC_GRUPOFAMILIAR_ID);
            if (empty($this->integrantes)) {
                $this->mensaje = 'No existe grupo familiar en export';
            } else {
                $this->bienRaiz = self::consultaBienRaiz();
                $this->educacion = self::consultaEducacion();
                $this->ingresos = self::consultaIngreso();
                $this->pensionAlimentos = self::consultaPension();
                $this->saludIsapre = self::consultaSaludIsapre();
                $this->vehiculo = self::consultaVehiculos();
                $this->juridico = self::consultaJuridicos();
            }
        }
    }

    public function consultaBienRaiz()
    {
        $response = [];

        foreach ($this->integrantes as $integrante) {
            $medioAltoCosto = false;
            $BienRaizPorRUN = [];

            if (isset($integrante->CALC_PROPIEDADES_COSTOSAS)
                && $integrante->CALC_PROPIEDADES_COSTOSAS == 1) {
                $busquedaSolicitudesJuridicos = null;
                $sociedadesLista= array();

                if ($integrante->P_JURIDICA == Constante::PERSONA_JURIDICA) {
                    $busquedaSolicitudesJuridicos = DatosDeSolicitud::busquedaSolicitudexRunJuridicoConBorrador($integrante->PER_RUN);
                    $sociedadesLista = ActualizacionPropiedadJuridico::obtenerSociedades($integrante->PER_RUN, true);
                }

                $busquedaSolicitudes = DatosDeSolicitud::busquedaSolicitudexRunIntegranteConBorradorBienes($integrante->PER_RUN,
                    [
                        Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ,
                        Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ,
                        Constante::TIPO_SOLICITUD_75_RECTIFICACION_DE_DOMINIO_DE_BIEN_RAIZ,
                        Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO
                    ]);

                $dataPropiedadesCiudadanoEloq = VWPropiedad::where('rut', $integrante->PER_RUN)
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->join('COMUNA_SII b', 'b.CODIGO_COMUNA', '=', 'vw_ever_sii_prop_det.ROL_COMUNA')
                    ->get();


                foreach ($dataPropiedadesCiudadanoEloq as $element) {
                    if (count($busquedaSolicitudes) > 0) {
                        $busqueda = false;
                        foreach ($busquedaSolicitudes as $busquedaSolicitude) {
                            if (in_array($busquedaSolicitude->ID_TIPO_SOLICITUD,
                                    [
                                        Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ,
                                        Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ,
                                        Constante::TIPO_SOLICITUD_75_RECTIFICACION_DE_DOMINIO_DE_BIEN_RAIZ,
                                        Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO
                                    ])
                                && $busquedaSolicitude->ROL == $element->ROL) {
                                $busqueda = true;
                                break;
                            }
                        }
                        if ($busqueda) {
                            continue;
                        }
                    }

                    if (count($sociedadesLista) > 0 && $element->R_JURIDICO == '1' && !in_array($element->RUN_SOCIEDAD,$sociedadesLista)  ){
                        continue;
                    }

                    if (isset($busquedaSolicitudesJuridicos)){
                        if (count($busquedaSolicitudesJuridicos) > 0 && $element->R_JURIDICO == "1") {
                            $continue = false;
                            foreach ($busquedaSolicitudesJuridicos as $busquedaSolicitudesJuridico) {
                                if ($busquedaSolicitudesJuridico->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA_1_PARTICIPACION) {
                                    if ($busquedaSolicitudesJuridico->RUN_CIUDADANO == $integrante->PER_RUN
                                        && $busquedaSolicitudesJuridico->RUN_SOCIEDAD == $element->RUN_SOCIEDAD) {
                                        $continue = true;
                                        break;
                                    }
                                } else {
                                    if ($busquedaSolicitudesJuridico->RUN_SOCIEDAD == $element->RUN_SOCIEDAD) {
                                        $continue = true;
                                        break;
                                    }
                                }
                            }
                            if ($continue) {
                                continue;
                            }
                        }
                    }
                    $elementFiltroPropiedad['nombre-comuna'] = $element->NOMBRE_COMUNA;
                    $elementFiltroPropiedad['rol-comuna'] = $element->ROL_COMUNA;
                    $elementFiltroPropiedad['rol-manzana'] = $element->ROL_MANZANA;
                    $elementFiltroPropiedad['rol-predio'] = $element->ROL_PREDIO;
                    $elementFiltroPropiedad['direccion-ofuscada'] = $element->DIRECCION_OFUSCADA;
                    $elementFiltroPropiedad['rol'] = $element->ROL;
                    $elementFiltroPropiedad['tasacion'] = $element->AVALUO_TOTAL;
                    $elementFiltroPropiedad['fecha-fuente'] = $element->FECHAFUENTE;
                    $elementFiltroPropiedad['run-juridico'] = $element->R_JURIDICO;
                    $elementFiltroPropiedad['run-sociedad'] = $element->RUN_SOCIEDAD;
                    $elementFiltroPropiedad['run-sociedad-ofuscado'] = $element->RUN_SOCIEDAD_OFUSCADO;
                    $BienRaizPorRUN[$element->ROL] = $elementFiltroPropiedad;
                }


                if (isset($integrante->CALC_PROPIEDADES_COSTOSAS) && count($BienRaizPorRUN) > 0) {
                    $medioAltoCosto = $integrante->CALC_PROPIEDADES_COSTOSAS == 1;
//                if ($medioAltoCosto) {
//                    $busqueda1 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
//                        $integrante->PER_RUN,
//                        Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ
//                    );
//                    $busqueda2 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
//                        $integrante->PER_RUN,
//                        Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ
//                    );
//                    if (count($busqueda1) > 0 or count($busqueda2) > 0) {
//                        $medioAltoCosto = false;
//                    }
//                }
                }
            }
            $response[$integrante->PER_RUN]['puede-realizar-solicitud-53'] = $medioAltoCosto;
            $response[$integrante->PER_RUN]['puede-realizar-solicitud-67'] = $medioAltoCosto;
            $response[$integrante->PER_RUN]['puede-realizar-solicitud-81'] = $medioAltoCosto;
            $response[$integrante->PER_RUN]['propiedad-bien-raiz'] = $BienRaizPorRUN;
        }
        return ["grupo-familiar" => $response];
    }

    public function consultaEducacion()
    {
        $int = [];
        foreach ($this->integrantes as $integrante) {
            $colegiosIntegrante = Colegio::where('run_alu', $integrante->PER_RUN)
//                ->where('vigente', 1)
//                ->where('calculoactivo', 1)
//                ->orderBy('agno', 'desc')
                ->get();
            $colegios = [];
            $colegios['educacion-alto-costo'] = false;
            foreach ($colegiosIntegrante as $colegioIntegrante) {
                $colegio = [];
                $colegio['anio-fuente'] = $colegioIntegrante->AGNO;
                $colegio['rbd'] = $colegioIntegrante->RBD;
                $colegio['colegio-actual'] = $colegioIntegrante->NOMBRE_ESTA;
                if ($colegioIntegrante->VIGENTE == Constante::VIGENTE_1_VIGENTE &&
                    $colegioIntegrante->CALCULOACTIVO == Constante::CALCULO_ACTIVO_1_APROBADO
                ) {
                    $colegio['educacion-alto-costo'] = $integrante->CALC_COLEGIO_COSTOSO == 1;
                    $colegios = $colegio;
                } else {
                    $colegios['historico'][] = $colegio;
                }

            }

            $estudiosIntegrante = MatriculaEducacionSuperior::where('run_alu', $integrante->PER_RUN)
                //->where('fuente', 8)
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->orderBy('cod_inst', 'asc')
                ->orderBy('codigo_unico', 'asc')
                ->orderBy('periodo_rsh', 'desc')
                ->get();
            $institucionesEdSuperior = [];
            $msjCiudadano = '';
            foreach ($estudiosIntegrante as $estudioIntegrante) {
                if (!array_key_exists($estudioIntegrante->COD_INST, $institucionesEdSuperior)) {
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST] = [];
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST]['nombre'] = $estudioIntegrante->NOMB_INST;
                }
                if (!array_key_exists($estudioIntegrante->CODIGO_UNICO,
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST])) {
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO] = [];
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['id-matricula'] = $estudioIntegrante->MATRICULA_ED_SUP_ID;
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['nombre-carrera'] = !(is_null($estudioIntegrante->NOMB_CARRERA)) ? $estudioIntegrante->NOMB_CARRERA : 'Sin Información';
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['anio-ingreso'] = !(is_null($estudioIntegrante->ANIO_ING_CARRERA)) ? $estudioIntegrante->ANIO_ING_CARRERA : 'Sin Información';
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['comuna'] = !(is_null($estudioIntegrante->COMUNA_SEDE)) ? $estudioIntegrante->COMUNA_SEDE : 'Sin Información';
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['jornada'] = !(is_null($estudioIntegrante->JORNADA)) ? $estudioIntegrante->JORNADA : 'Sin Información';
                    switch ($estudioIntegrante->CODIGO_RC) {
                        case 1:
                            $estadoDetalle = "INGRESO";
                            break;
                        case 2:
                            $estadoDetalle = "EGRESO/RETIRO";
                            break;
                        default:
                            $estadoDetalle = $estudioIntegrante->CODIGO_RC;
                            break;
                    }
                    $estudioActivo [$estudioIntegrante->MATRICULA_ED_SUP_ID] = false;
                    if ($estudioIntegrante->CALCULOACTIVO == 1 && $estudioIntegrante->VIGENTE == 1) {
                        $estudioActivo [$estudioIntegrante->MATRICULA_ED_SUP_ID] = true;
                    }
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['estado'] = (isset($estadoDetalle)) ? $estadoDetalle : null;
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['calculo-activo'] = $estudioIntegrante->CALCULOACTIVO;
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['vigente'] = $estudioIntegrante->VIGENTE;
                    $institucionesEdSuperior[$estudioIntegrante->COD_INST][$estudioIntegrante->CODIGO_UNICO]['historico'] = self::getHistoricoEducacion(
                        $estudioIntegrante->RUN_ALU,
                        $estudioIntegrante->COD_INST, $estudioIntegrante->CODIGO_UNICO
                    );
                }
            }
            $solicitudTramitable = false;
            $solicitudSubTipo = false;
            $edad = Utiles::getEdad($integrante->PER_FECHANACIMIENTO);
            if (($edad >= Constante::EDAD_18_MINIMA_MATRICULA_EDUCACION_SUPERIOR &&
                $edad <= Constante::EDAD_24_MAXIMA_MATRICULA_EDUCACION_SUPERIOR)
            ) {
                $padreNoPresente = $integrante->MEDIO_PADRE_NO_PRESEN_AMB ?? 0;
                if(($padreNoPresente == 0 && $integrante->CALC_INGRESO_LABORAL > 0 && $integrante->ESTUDIA_TRABAJA == Constante::ESTUDIA_TRABAJA_0) ||
                    ($padreNoPresente > 0 && $integrante->CALC_INGRESO_LABORAL > 0 && $integrante->ESTUDIA == Constante::ESTUDIA_0)
                ){
                    $busqueda = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
                        $integrante->PER_RUN,
                        Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR,
                        Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_1_INGRESO
                    );
                    if ($busqueda->count() == 0) {
                        $solicitudTramitable = true;
                        $solicitudSubTipo = 'INGRESO';
                    } else if ($busqueda->count() > 0) {
                        $msjCiudadano = 'El ciudadano ' . $integrante->PER_RUN . ' Posee una solicitud en curso de INGRESO';
                    }
                }else if(
                    ($padreNoPresente == 0 && $integrante->CALC_INGRESO_LABORAL == 0 && $integrante->ED_SUPERIOR == Constante::ESTUDIA_TRABAJA_1) ||
                    ($padreNoPresente == 0 && $integrante->calc_ingreso_laboral > 0 &&  $integrante->ED_SUPERIOR == Constante::ESTUDIA_TRABAJA_1)||
                    ($padreNoPresente > 0  && $integrante->ED_SUPERIOR == Constante::ESTUDIA_1)
                )  {
                    $busqueda = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
                        $integrante->PER_RUN,
                        Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR,
                        Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_2_RETIRO_O_EGRESO
                    );
                    if ($busqueda->count() == 0) {
                        $solicitudTramitable = true;
                        $solicitudSubTipo = 'EGRESO/RETIRO';
                    } else if ($busqueda->count() > 0) {
                        $msjCiudadano = 'El ciudadano ' . $integrante->PER_RUN . ' Posee una solicitud en curso de EGRESO/RETIRO';
                    }
                }
            }
            $medioAltoCosto = false;
            if (isset($integrante->CALC_COLEGIO_COSTOSO)) {
                $medioAltoCosto = $integrante->CALC_COLEGIO_COSTOSO == 1;
                if ($medioAltoCosto) {
                    $busqueda1 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
                        $integrante->PER_RUN,
                        Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL
                    );
                    $busqueda2 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante(
                        $integrante->PER_RUN,
                        Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL
                    );
                    if (count($busqueda1) > 0 or count($busqueda2) > 0) {
                        $medioAltoCosto = false;
                    }
                }
            }
            $estudioActivo = array();
            $int[$integrante->PER_RUN] = array(
                'puede-realizar-solicitud-69' => $solicitudTramitable,
                'puede-realizar-solicitud-52' => $medioAltoCosto,
                'puede-realizar-solicitud-66' => $medioAltoCosto,
                'sub-tipo-solicitud' => $solicitudSubTipo,
                'msj-ciudadano' => $msjCiudadano,
                'educacion-superior' => $institucionesEdSuperior,
                'educacion-basica-media' => $colegios
            );
        }
        return (['grupo-familiar' => $int]);
    }

    public function consultaIngreso()
    {
        $grupoTrabajo = [];
        $grupoPension = [];
        $grupoCapital = [];
        $individuoTrabajo = [];
        $individuoPension = [];
        $individuoCapital = [];
        $integrantes = [];
        $totalGrupoTrabajo = 0;
        $fuentes = DatosDeFuentes::getListaFuentes();
        $tramosIngresos = Utiles::obtenerTramosIngresos();
        foreach ($this->integrantes as $integrante) {
            $bandMismo = ($this->persona->PER_RUN == $integrante->PER_RUN);
            $aporta = false;

            if (isset($fuentes['solicitudes']) && in_array($integrante->CALC_FUENTE_INGRESOLABORAL,
                    array_merge(
                        $fuentes['solicitudes'][Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE]['fuentes'],
                        $fuentes['solicitudes'][Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE]['fuentes'],
                        $fuentes['solicitudes'][Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE]['fuentes']
                    )
                )) {
                $aporta = true;
                $fuenteIngresoLaboral = FuenteExport::leftJoin('fuentes ftabla_ind', 'ftabla_ind.fue_id', '=',
                    'fuentes.fue_independiente')
                    ->leftJoin('fuentes ftabla_dep', 'ftabla_dep.fue_id', '=', 'fuentes.fue_dependiente')
                    ->where('fuentes.fue_id', $integrante->CALC_FUENTE_INGRESOLABORAL)
                    ->select('fuentes.fue_id',
                        'fuentes.fue_descripcion',
                        'fuentes.fue_independiente',
                        'fuentes.fue_dependiente',
                        'ftabla_ind.tabla as tabla_ind',
                        'ftabla_dep.tabla as tabla_dep')
                    ->first();
                $periodo1 = null;
                $periodo2 = null;
                $periodoTBL = ActualizacionMeses::max('periodo_rsh');

                $mesesDep = self::getIngresoMesesTipo($fuenteIngresoLaboral->FUE_DEPENDIENTE,
                    $fuenteIngresoLaboral->TABLA_DEP, $integrante->PER_RUN, $periodo1, $tramosIngresos);
                $mesesIndep = self::getIngresoMesesTipo($fuenteIngresoLaboral->FUE_INDEPENDIENTE,
                    $fuenteIngresoLaboral->TABLA_IND, $integrante->PER_RUN, $periodo2, $tramosIngresos);
                $totalDependiente = isset($mesesDep['monto-total']) ? $mesesDep['monto-total'] : 0;
                $totalIndependiente = isset($mesesIndep['monto-total']) ? $mesesIndep['monto-total'] : 0;
                $totalIndividualTrabajo = $totalDependiente + $totalIndependiente;

                $totalGrupoTrabajo += array_key_exists('monto-total', $mesesDep) ? $mesesDep['monto-total'] : 0;
                $totalGrupoTrabajo += array_key_exists('monto-total', $mesesIndep) ? $mesesIndep['monto-total'] : 0;

                if ($bandMismo) {
                    $individuoTrabajo['codigo-fuente'] = $integrante->CALC_FUENTE_INGRESOLABORAL;
                    $individuoTrabajo['fuente'] = $fuenteIngresoLaboral->FUE_DESCRIPCION;
                    if (isset($fuenteIngresoLaboral->FUE_DEPENDIENTE)) {
                        $individuoTrabajo['sub-codigo-fuente-dep'] = $fuenteIngresoLaboral->FUE_DEPENDIENTE;
                    }
                    if (isset($fuenteIngresoLaboral->FUE_INDEPENDIENTE)) {
                        $individuoTrabajo['sub-codigo-fuente-indep'] = $fuenteIngresoLaboral->FUE_INDEPENDIENTE;
                    }
                    $individuoTrabajo['mensaje'] = "Sus ingresos laborales...";
                    $individuoTrabajo['periodo'] = (max($periodo1, $periodo2)) ? max($periodo1, $periodo2) : 0;
                    $individuoTrabajo['periodo-tbl'] = $periodoTBL;
                    $individuoTrabajo['dependiente'] = $mesesDep;
                    $individuoTrabajo['independiente'] = $mesesIndep;
                    $individuoTrabajo['tramo-individual'] = round($totalIndividualTrabajo / 12, 2);
                    $individuoTrabajo['tramo-mensaje-individual'] = Utiles::mensajeTramoIngreso($individuoTrabajo['tramo-individual'],
                        $tramosIngresos);
                }
            }
            if ($integrante->CALC_INGRESO_CAPITAL > 0
            ) {
                $aporta = true;
                $fuenteIngresoLaboral = FuenteExport::where('fuentes.fue_id', Constante::FUENTE_200_INGRESO_DE_CAPITAL)
                    ->select('fuentes.fue_id',
                        'fuentes.fue_descripcion')
                    ->first();
                $periodo1 = null;
                $periodo2 = null;
                $periodoTBL = ActualizacionMeses::max('periodo_rsh');

                if ($bandMismo) {
                    $individuoCapital['codigo-fuente'] = Constante::FUENTE_200_INGRESO_DE_CAPITAL;
                    $individuoCapital['fuente'] = $fuenteIngresoLaboral->FUE_DESCRIPCION;
                    $individuoCapital['mensaje'] = "Sus ingresos laborales...";
                    $individuoCapital['periodo'] = (max($periodo1, $periodo2)) ? max($periodo1, $periodo2) : 0;
                    $individuoCapital['periodo-tbl'] = $periodoTBL;
                }
            }
            if (isset($fuentes['solicitudes']) &&
                in_array($integrante->CALC_FUENTE_PENSION,
                    $fuentes['solicitudes'][Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]['fuentes'])
            ) {
                $aporta = true;
                $fuenteIngresoLaboral = FuenteExport::leftJoin('fuentes ftabla_1', 'ftabla_1.fue_id', '=',
                    'fuentes.fue_pens_1')
                    ->leftJoin('fuentes ftabla_2', 'ftabla_2.fue_id', '=', 'fuentes.fue_pens_2')
                    ->where('fuentes.fue_id', $integrante->CALC_FUENTE_PENSION)
                    ->select('fuentes.fue_id',
                        'fuentes.fue_descripcion',
                        'fuentes.fue_pens_1',
                        'fuentes.fue_pens_2',
                        'ftabla_1.tabla as tabla_1',
                        'ftabla_2.tabla as tabla_2')
                    ->first();
                $periodo1 = null;
                $periodo2 = null;
                $periodoTBL = ActualizacionMeses::max('periodo_rsh');

                if ($bandMismo) {
                    $individuoPension['codigo-fuente'] = $integrante->CALC_FUENTE_PENSION;
                    $individuoPension['fuente'] = $fuenteIngresoLaboral->FUE_DESCRIPCION;
                    if (isset($fuenteIngresoLaboral->FUE_PENS_1)) {
                        $individuoPension['sub-codigo-fuente-1'] = $fuenteIngresoLaboral->FUE_PENS_1;
                    }
                    if (isset($fuenteIngresoLaboral->FUE_PENS_2)) {
                        $individuoPension['sub-codigo-fuente-2'] = $fuenteIngresoLaboral->FUE_PENS_2;
                    }
                    $individuoPension['mensaje'] = "Sus ingresos laborales...";
                    $individuoPension['periodo'] = (max($periodo1, $periodo2)) ? max($periodo1, $periodo2) : 0;
                    $individuoPension['periodo-tbl'] = $periodoTBL;

                }
            }
            if ($aporta) {
                $integrantes[$integrante->PER_RUN] = [
                    'nombre' => $integrante->PER_NOMBRES . " " . $integrante->PER_APELLIDOPATERNO . " " . $integrante->PER_APELLIDOMATERNO,
                    'edad' => $integrante->PER_EDAD
                ];

            }
        }
        $grupoTrabajo['individuos'] = $integrantes;
        $grupoTrabajo['monto-total'] = $totalGrupoTrabajo;
        $grupoTrabajo['promedio'] = round($totalGrupoTrabajo / 12, 2);
        $ingresoGrupal = ['trabajo' => $grupoTrabajo, 'pension' => $grupoPension, 'capital' => $grupoCapital];
        $ingresoIndividual = [
            'trabajo' => $individuoTrabajo,
            'pension' => $individuoPension,
            'capital' => $individuoCapital
        ];
        $respuesta = [
            'ingreso-grupal' => $ingresoGrupal,
            $this->persona->PER_RUN => $ingresoIndividual
        ];

        return $respuesta;
    }

    public function consultaPension()
    {
        $grupoFamiliar = [];
        foreach ($this->integrantes as $integrante) {
            $grupoFamiliar[$integrante->PER_RUN] = Utiles::getPensionPorRUN($integrante->PER_RUN);
        }
        return ["grupo-familiar" => $grupoFamiliar];
    }

    public function consultaSaludIsapre()
    {
        $response = [];
        $periodoCargado = ActualizacionMeses::max("periodo_rsh");
        $buscaPadreMadre = Utiles::busquedaPadres($this->persona->PER_RUN);
        $cuentaPadres = null;
        foreach ($this->integrantes as $integrante) {
            $ciudadanoConsultado = false;
            if ($integrante->PER_RUN == $this->persona->PER_RUN) {
                $ciudadanoConsultado = true;
            }
            $response[$integrante->PER_RUN] = Utiles::getIsaprePorRUN($integrante->PER_RUN,
                $periodoCargado, $ciudadanoConsultado);
            if ($integrante->PER_RUN == $buscaPadreMadre ['padre'] || $integrante->PER_RUN == $buscaPadreMadre ['madre']) {
                $cuentaPadres = $cuentaPadres + 1;
            }
        }
        foreach ($this->integrantes as $integrante) {
            if ($response[$integrante->PER_RUN]['sub-tipo-solicitud-51'] === "1 y revisar si aplica 2") { // llevar el texto "1 y revisar si aplica 2" a array
                $response[$integrante->PER_RUN]['sub-tipo-solicitud-51'] = [1];
                if ($cuentaPadres != 2) {
                    $response[$integrante->PER_RUN]['sub-tipo-solicitud-51'] = [1, 2];
                }
            }
        }
        /**
         * valor UF
         */
        $valorUF = ValoresSBIF::devuelveUf();
        return [
            "valor-uf" => $valorUF,
            "grupo-familiar" => $response
        ];
    }

    public function consultaVehiculos()
    {
        $int = [];
        foreach ($this->integrantes as $integrante) {

            $vehiculos = [];
            $vehiculosMaritimo = [];
            $medioAltoCosto = false;
            $medioAltoCostoTerrestre = false;
            $medioAltoCostoMaritimo = false;

            if ((isset($integrante->CALC_VEHICULOS_COSTOSOS) && $integrante->CALC_VEHICULOS_COSTOSOS == 1) ||
                (isset($integrante->DNAVES_IND_MUY_ALTO_VALOR) && $integrante->DNAVES_IND_MUY_ALTO_VALOR == 1)) {

                $busquedaSolicitudesJuridicos = null;
                $sociedadesLista= array();

                if ($integrante->P_JURIDICA == Constante::PERSONA_JURIDICA) {
                    $busquedaSolicitudesJuridicos = DatosDeSolicitud::busquedaSolicitudexRunJuridicoConBorrador($integrante->PER_RUN);
                    $sociedadesLista = ActualizacionPropiedadJuridico::obtenerSociedades($integrante->PER_RUN, true);
                }

                $busquedaSolicitudes = DatosDeSolicitud::busquedaSolicitudexRunIntegranteConBorrador($integrante->PER_RUN,
                    [
                        Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL,
                        Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                    ]);

                if ((isset($integrante->CALC_VEHICULOS_COSTOSOS) && $integrante->CALC_VEHICULOS_COSTOSOS == 1)) {

                    $vehiculosIntegrante = VWDetalleTasacionVehiculos::where('veh_det_vigente',
                        Constante::VIGENTE_1_VIGENTE)
                        ->where('veh_det_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->where('veh_det_run', $integrante->PER_RUN)
                        ->whereNotNull('veh_det_patente')
                        ->get();



                    foreach ($vehiculosIntegrante as $vehiculo) {
                        if (count($busquedaSolicitudes) > 0) {
                            $busqueda = false;
                            foreach ($busquedaSolicitudes as $busquedaSolicitude) {
                                if ($busquedaSolicitude->ID_TIPO_SOLICITUD
                                    == Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL
                                    && (in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                        [
                                            Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_1_VENTA,
                                            Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_2_CANCELACION,
                                            Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_5_NO_PROPIETARIO
                                        ]))
                                    && $busquedaSolicitude->DATO_ACTUAL == $vehiculo->VEH_ID) {
                                    $busqueda = true;
                                    break;
                                }

                                if ($busquedaSolicitude->ID_TIPO_SOLICITUD
                                    == Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                                    && (in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                        [
                                            Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_1,
                                            Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_2_ACREDITAR_EL_ERROR_EN_EL_MODELO_MARCA,
                                            Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_3_ACREDITAR_EL_ERROR_DEL_ANNO_DEL_VEHICULO
                                        ]))
                                    && $busquedaSolicitude->DATO_ACTUAL == $vehiculo->VEH_ID) {
                                    $busqueda = true;
                                    break;
                                }
                            }
                            if ($busqueda) {
                                continue;
                            }
                        }

                        if (count($sociedadesLista) > 0 && $vehiculo->R_JURIDICO == '1' && !in_array($vehiculo->RUN_SOCIEDAD,$sociedadesLista)  ){
                            continue;
                        }

                        if (isset($busquedaSolicitudesJuridicos)) {
                            if (count($busquedaSolicitudesJuridicos) > 0 && $vehiculo->R_JURIDICO == 1) {
                                $continue = false;
                                foreach ($busquedaSolicitudesJuridicos as $busquedaSolicitudesJuridico) {
                                    if ($busquedaSolicitudesJuridico->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA_1_PARTICIPACION) {
                                        if ($busquedaSolicitudesJuridico->RUN_CIUDADANO == $integrante->PER_RUN
                                            && $busquedaSolicitudesJuridico->RUN_SOCIEDAD == $vehiculo->RUN_SOCIEDAD) {
                                            $continue = true;
                                            break;
                                        }
                                    } else {
                                        if ($busquedaSolicitudesJuridico->RUN_SOCIEDAD == $vehiculo->RUN_SOCIEDAD) {
                                            $continue = true;
                                            break;
                                        }
                                    }
                                }
                                if ($continue) {
                                    continue;
                                }
                            }
                        }

                        $vehiculosAut = array();
                        $vehiculosAut['id'] = $vehiculo->VEH_ID;
                        $vehiculosAut['marca'] = $vehiculo->VEH_DET_MARCA;
                        $vehiculosAut['modelo'] = $vehiculo->VEH_DET_MODELO;
                        $vehiculosAut['patente'] = $vehiculo->VEH_DET_PATENTE;
                        $vehiculosAut['anio'] = $vehiculo->VEH_DET_ANNIO_FABRICACION;
                        $vehiculosAut['tasacion'] = $vehiculo->VEH_DET_TASACION;
                        $vehiculosAut['fecha-fuente'] = $vehiculo->FECHAFUENTE;
                        $vehiculosAut['run-juridico'] = $vehiculo->R_JURIDICO;
                        $vehiculosAut['run-sociedad'] = $vehiculo->RUN_SOCIEDAD;
                        $vehiculosAut['run-sociedad-ofuscado'] = $vehiculo->RUN_SOCIEDAD_OFUSCADO;
                        $vehiculos[$vehiculo->VEH_ID] = $vehiculosAut;
                    }
                }


                if ((isset($integrante->DNAVES_IND_MUY_ALTO_VALOR) && $integrante->DNAVES_IND_MUY_ALTO_VALOR == 1)) {

                    $vehiculosMaritimoIntegrante = VW_DIRECTEMAR::where('run', $integrante->PER_RUN)
                        ->where('CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->get();

                    foreach ($vehiculosMaritimoIntegrante as $vehiculoMaritimo) {
                        if (count($busquedaSolicitudes) > 0) {
                            $busqueda = false;
                            foreach ($busquedaSolicitudes as $busquedaSolicitude) {
                                if ($busquedaSolicitude->ID_TIPO_SOLICITUD
                                    == Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL
                                    && (in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                        [Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_MARITIMO_3_VENTA])
                                        || in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                            [Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_MARITIMO_4_PERDIDA_TOTAL])
                                        || in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                            [Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_MARITIMO_6_CANCELACION])
                                    )
                                    && $busquedaSolicitude->DATO_ACTUAL == $vehiculoMaritimo->ID_DIRECTEMAR) {
                                    $busqueda = true;
                                    break;
                                }
                                if ($busquedaSolicitude->ID_TIPO_SOLICITUD
                                    == Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                                    && (in_array($busquedaSolicitude->ID_SUB_TIPO_SOLICITUD,
                                        [Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_MARITIMO_4_VENTA]))
                                    && $busquedaSolicitude->DATO_ACTUAL == $vehiculoMaritimo->ID_DIRECTEMAR) {
                                    $busqueda = true;
                                    break;
                                }
                            }
                            if ($busqueda) {
                                continue;
                            }
                        }
                        if (count($sociedadesLista) > 0 && $vehiculoMaritimo->R_JURIDICO == '1' && !in_array($vehiculoMaritimo->RUN_SOCIEDAD,$sociedadesLista)  ){
                            continue;
                        }

                        if (isset($busquedaSolicitudesJuridicos)) {
                            if (count($busquedaSolicitudesJuridicos) > 0 && $vehiculoMaritimo->R_JURIDICO == 1) {
                                $continue = false;
                                foreach ($busquedaSolicitudesJuridicos as $busquedaSolicitudesJuridico) {
                                    if ($busquedaSolicitudesJuridico->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA_1_PARTICIPACION) {
                                        if ($busquedaSolicitudesJuridico->RUN_CIUDADANO == $integrante->PER_RUN
                                            && $busquedaSolicitudesJuridico->RUN_SOCIEDAD == $vehiculoMaritimo->RUN_SOCIEDAD) {
                                            $continue = true;
                                            break;
                                        }
                                    } else {
                                        if ($busquedaSolicitudesJuridico->RUN_SOCIEDAD == $vehiculoMaritimo->RUN_SOCIEDAD) {
                                            $continue = true;
                                            break;
                                        }
                                    }
                                }
                                if ($continue) {
                                    continue;
                                }
                            }
                        }

                        $vehiculoMar = array();
                        $vehiculoMar['id-vehiculo'] = $vehiculoMaritimo->ID_DIRECTEMAR;
                        $vehiculoMar['id-movil'] = $vehiculoMaritimo->ID_MOVIL;
                        $vehiculoMar['run'] = $vehiculoMaritimo->RUN;
                        $vehiculoMar['nm-movil'] = $vehiculoMaritimo->NMMOVIL;
                        $vehiculoMar['cdtp-actividad'] = $vehiculoMaritimo->CDTPACTIVIDAD;
                        $vehiculoMar['gltp-actividad'] = $vehiculoMaritimo->GLTPACTIVIDAD;
                        $vehiculoMar['cdpto-matricula'] = $vehiculoMaritimo->CDPTOMATRICULA;
                        $vehiculoMar['puerto-matricula'] = $vehiculoMaritimo->PUERTOMATRICULA;
                        $vehiculoMar['nr-matricula'] = $vehiculoMaritimo->NRMATRICULA;
                        $vehiculoMar['run-juridico'] = $vehiculoMaritimo->R_JURIDICO;
                        $vehiculoMar['run-sociedad'] = $vehiculoMaritimo->RUN_SOCIEDAD;
                        $vehiculoMar['run-sociedad-ofuscado'] = $vehiculoMaritimo->RUN_SOCIEDAD_OFUSCADO;
                        $vehiculosMaritimo[$vehiculoMaritimo->ID_DIRECTEMAR] = $vehiculoMar;
                    }
                }


                if ((isset($integrante->CALC_VEHICULOS_COSTOSOS) && count($vehiculos) > 0) ||
                    (isset($integrante->DNAVES_IND_MUY_ALTO_VALOR) && count($vehiculosMaritimo) > 0)) {
                    $medioAltoCosto = ($integrante->CALC_VEHICULOS_COSTOSOS == 1 && count($vehiculos) > 0) ||
                        ($integrante->DNAVES_IND_MUY_ALTO_VALOR == 1 && count($vehiculosMaritimo) > 0);
                    $medioAltoCostoTerrestre = ($integrante->CALC_VEHICULOS_COSTOSOS == 1 && count($vehiculos) > 0);
                    $medioAltoCostoMaritimo = ($integrante->DNAVES_IND_MUY_ALTO_VALOR == 1 && count($vehiculosMaritimo) > 0);
                }
            }
            $int[$integrante->PER_RUN]['puede-realizar-solicitud-68'] = $medioAltoCosto;
            $int[$integrante->PER_RUN]['propiedad-calc-vehiculos-terrestre'] = $medioAltoCostoTerrestre;
            $int[$integrante->PER_RUN]['propiedad-calc-vehiculos-maritimo'] = $medioAltoCostoMaritimo;
            $int[$integrante->PER_RUN]['propiedad-vehiculos-terrestre'] = $vehiculos;
            $int[$integrante->PER_RUN]['propiedad-vehiculos-maritimo'] = $vehiculosMaritimo;

        }
        return (['grupo-familiar' => $int]);
    }

    public function consultaJuridicos()
    {
        $personaJuridica = array();
        $listadoTerrestre = array();
        $listadoMaritimo = array();
        $listadoPropiedad = array();

        foreach ($this->integrantes as $integrante) {
            if (isset($integrante)){
                $pJuridica= ActualizacionPropiedadJuridico::bloqueoSolicitudRectificacionExistente($integrante);
                if ($pJuridica){
                    $sociedades = ActualizacionPropiedadJuridico::obtenerSociedades($integrante->PER_RUN);
                    if (isset($sociedades)){
                        foreach ($sociedades as $rutJuridico) {
                            $listadoSocios = ActualizacionPropiedadJuridico::obtenerSocios($rutJuridico['datos-sociedad']['run-sociedad']);

                            if ((isset($integrante->CALC_VEHICULOS_COSTOSOS) && $integrante->CALC_VEHICULOS_COSTOSOS == 1)) {
                                $listadoTerrestre = ActualizacionPropiedadJuridico::obtenerPropiedadesTerrestres($rutJuridico['datos-sociedad']['run-sociedad'],
                                    $integrante->PER_RUN);
                            }

                            if ((isset($integrante->DNAVES_IND_MUY_ALTO_VALOR) && $integrante->DNAVES_IND_MUY_ALTO_VALOR == 1)) {
                                $listadoMaritimo = ActualizacionPropiedadJuridico::obtenerPropiedadesMaritimas($rutJuridico['datos-sociedad']['run-sociedad'],
                                    $integrante->PER_RUN);
                            }

                            if (isset($integrante->CALC_PROPIEDADES_COSTOSAS) && $integrante->CALC_PROPIEDADES_COSTOSAS == 1) {
                                $listadoPropiedad = ActualizacionPropiedadJuridico::obtenerPropiedades($rutJuridico['datos-sociedad']['run-sociedad'],
                                    $integrante->PER_RUN);
                            }

                            $recuentoBienes = ActualizacionPropiedadJuridico::contarBienes($listadoTerrestre, $listadoMaritimo,
                                $listadoPropiedad);

                            if ($recuentoBienes['total-bienes'] > 0) {
                                $bloqueoSolicitud = ActualizacionPropiedadJuridico::bloqueoSolicitud($integrante->PER_RUN,
                                    $listadoTerrestre, $listadoMaritimo, $listadoPropiedad, $rutJuridico, $listadoSocios);

                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']] = $rutJuridico;
                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']]['datos-sociedad']['puede-realizar'] = $bloqueoSolicitud;
                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']]['cantidad-propiedades'] = $recuentoBienes;
                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']]['bienes-raices'] = $listadoPropiedad;
                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']]['vehiculos-terrestres'] = $listadoTerrestre;
                                $personaJuridica[$integrante->PER_RUN][$rutJuridico['datos-sociedad']['run-ofuscado']]['vehiculos-maritimos'] = $listadoMaritimo;
                            }
                        }
                    }
                }
            }
        }
        return ($personaJuridica);
    }

    public function getIngresoMesesTipo($fuente, $tabla, $run, &$periodo, $tramosIngresos)
    {
        $mesesRetorno = [];
        if (!is_null($fuente)) {
            switch ($fuente) {
                case 1: // independiente - EVER_AFP_12_MESES
                    $maxPeriodo = Afp::where('run', $run)
                        ->max('periodo_rsh');
                    $periodo = $maxPeriodo;
                    $datosIngreso = Afp::join('afp_meses_cargados amc', 'amc.periodo_rsh', '=',
                        'ever_afp_12_meses.periodo_rsh')
                        ->where('ever_afp_12_meses.run', $run)
                        ->where('ever_afp_12_meses.periodo_rsh', $maxPeriodo)
                        ->where('ever_afp_12_meses.vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    $totalIndependiente = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $totalIndependiente += $datosIngreso['MONTOPESOS_IND_' . $i];
                        $mesesRetorno['detalle'][$datosIngreso['MES_' . ($i + 1)]] = $datosIngreso['MONTOPESOS_IND_' . $i];
                    }
                    $mesesRetorno['monto-total'] = $totalIndependiente;
                    $mesesRetorno['promedio'] = round($totalIndependiente / 12, 2);
                    $mesesRetorno['tramo-texto'] = Utiles::mensajeTramoIngreso($mesesRetorno['promedio'],
                        $tramosIngresos);
                    break;
                case 2: // independiente - EVER_SUPER_SALUD_COTIZANTE
                    $maxPeriodo = Isapre::where('run', $run)
                        ->max('periodo_rsh');
                    $periodo = $maxPeriodo;
                    $datosIngreso = Isapre::join('isapres_cotizan_meses_cargados icmc', 'icmc.periodo_rsh', '=',
                        'ever_super_salud_cotizantes.periodo_rsh')
                        ->where('ever_super_salud_cotizantes.run', $run)
                        ->where('ever_super_salud_cotizantes.periodo_rsh', $maxPeriodo)
                        //->where('ever_super_salud_cotizantes.vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    $totalIndependiente = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $campo = ($i < 10) ? 'RENTA_IND_0' . $i : 'RENTA_IND_' . $i;
                        $totalIndependiente += $datosIngreso[$campo];
                        $mesesRetorno['detalle'][$datosIngreso['MES_' . ($i + 1)]] = $datosIngreso[$campo];
                    }
                    $mesesRetorno['monto-total'] = $totalIndependiente;
                    $mesesRetorno['promedio'] = round($totalIndependiente / 12, 2);
                    $mesesRetorno['tramo-texto'] = Utiles::mensajeTramoIngreso($mesesRetorno['promedio'],
                        $tramosIngresos);
                    break;
                case 3: // independiente - SII
                    break;
                case 4: // dependiente - EVER_AFC_INGRESO
                    $maxPeriodo = Afc::where('run', $run)
                        ->max('periodo_rsh');
                    $periodo = $maxPeriodo;
                    $datosIngreso = Afc::join('afc_meses_cargados amc', 'amc.periodo_rsh', '=',
                        'ever_afc_ingreso.periodo_rsh')
                        ->where('ever_afc_ingreso.run', $run)
                        ->where('ever_afc_ingreso.periodo_rsh', $maxPeriodo)
                        ->where('ever_afc_ingreso.vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    $totalDependiente = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $campo = ($i < 10) ? 'RENTA_0' . $i : 'RENTA_' . $i;
                        $totalDependiente += $datosIngreso[$campo];
                        $mesesRetorno['detalle'][$datosIngreso['MES_' . $i]] = $datosIngreso[$campo];
                    }
                    $mesesRetorno['monto-total'] = $totalDependiente;
                    $mesesRetorno['promedio'] = round($totalDependiente / 12, 2);
                    $mesesRetorno['tramo-texto'] = Utiles::mensajeTramoIngreso($mesesRetorno['promedio'],
                        $tramosIngresos);
                    break;
                case 5: // dependiente - EVER_AFP_12_MESES
                    $maxPeriodo = Afp::where('run', $run)
                        ->max('periodo_rsh');
                    $periodo = $maxPeriodo;
                    $datosIngreso = Afp::join('afp_meses_cargados amc', 'amc.periodo_rsh', '=',
                        'ever_afp_12_meses.periodo_rsh')
                        ->where('ever_afp_12_meses.run', $run)
                        ->where('ever_afp_12_meses.periodo_rsh', $maxPeriodo)
                        ->where('ever_afp_12_meses.vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    $totalDependiente = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $totalDependiente += $datosIngreso['MONTOPESOS_DEP_' . $i];
                        $mesesRetorno['detalle'][$datosIngreso['MES_' . ($i + 1)]] = $datosIngreso['MONTOPESOS_DEP_' . $i];
                    }
                    $mesesRetorno['monto-total'] = $totalDependiente;
                    $mesesRetorno['promedio'] = round($totalDependiente / 12, 2);
                    $mesesRetorno['tramo-texto'] = Utiles::mensajeTramoIngreso($mesesRetorno['promedio'],
                        $tramosIngresos);
                    break;
                case 6: // dependiente - EVER_SUPER_SALUD
                    $maxPeriodo = Isapre::where('run', $run)
                        ->max('periodo_rsh');
                    $periodo = $maxPeriodo;
                    $datosIngreso = Isapre::join('isapres_cotizan_meses_cargados icmc', 'icmc.periodo_rsh', '=',
                        'ever_super_salud_cotizantes.periodo_rsh')
                        ->where('ever_super_salud_cotizantes.run', $run)
                        ->where('ever_super_salud_cotizantes.periodo_rsh', $maxPeriodo)
                        //->where('ever_super_salud_cotizantes.vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    $totalIndependiente = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $campo = ($i < 10) ? 'RENTA_DEP_0' . $i : 'RENTA_DEP_' . $i;
                        $totalIndependiente += $datosIngreso[$campo];
                        $mesesRetorno['detalle'][$datosIngreso['MES_' . ($i + 1)]] = $datosIngreso[$campo];
                    }
                    $mesesRetorno['monto-total'] = $totalIndependiente;
                    $mesesRetorno['promedio'] = round($totalIndependiente / 12, 2);
                    $mesesRetorno['tramo-texto'] = Utiles::mensajeTramoIngreso($mesesRetorno['promedio'],
                        $tramosIngresos);
                    break;
                case 7: // dependiente - SII
                    break;
            }
        }
        return $mesesRetorno;
    }

    public function getHistoricoEducacion($runAlu, $codigoInstitucion, $codigoUnico)
    {
        $retorno = [];
        $historial = MatriculaEducacionSuperior::where('run_alu', $runAlu)
            ->where('cod_inst', $codigoInstitucion)
            ->where('codigo_unico', $codigoUnico)
            ->orderBy('cat_periodo', 'desc')
            ->select('cat_periodo')
            ->get();
        foreach ($historial as $item) {
            if (strlen($item->CAT_PERIODO) == 8) {
                $retorno = array_merge($retorno, [substr($item->CAT_PERIODO, 0, 4)]);
            } else {
                $retorno = array_merge($retorno, [$item->CAT_PERIODO]);
            }
        }
        return $retorno;
    }
}
