<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionEjecucion;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\NNARepresentanteMigrante;

class TramiteMapper extends ResponseMapper
{
    public function consulta(Tramite $tramite)
    {
        $this->campos = [
            'persona' => new PersonaMapper($tramite->solicitante),
            'solicitud' => new SolicitudMapper($tramite->solicitud),
            'personas-asociadas' => new PersonasAsociadasMapper($tramite),
            'datos-vigente' => null,
            'datos-solicitud' => null,
            'datos-anteriores-solicitud' => null,
            'datos-grupo-familiar' => null,
            'archivos-adjuntos' => AdjuntosMapper::new()->show($tramite->adjuntos)
        ];

        if($this->campos["solicitud"]->campos["migrante"] == 1){
            $this->campos = array_merge($this->campos,
                array("adulto-responsable"=>$this->adultoResponsable($tramite->titular->PER_ID))
            );
        }

        return $this;
    }

    public function adultoResponsable($id_nna){
        $bnf_nna = BeneficiarioGrupoFamiliar::join('grupos_familiares', 'grupos_familiares.grupfami_id', '=', 'beneficiarios_gruposfamiliares.bnfgrupfami_grupofamiliar')
            ->where("bnfgrupfami_persona", $id_nna)
            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();

        $mapp_representante = null;
        if ($bnf_nna){
            $migrante = NNARepresentanteMigrante::where("MIGRAN_GRUPOFAMILIAR", $bnf_nna->GRUPFAMI_ID)
                ->where("MIGRAN_NUEVOFOLIO", $bnf_nna->GRUPFAMI_NUEVOFOLIO)
                ->where('MIGRAN_VIGENTE', Constante::VIGENTE_1_VIGENTE)
                ->where('MIGRAN_CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();

            $mapp_representante = $migrante ? (new RshEspecialAdultoMapper)->map($migrante) : null;
        }
        return $mapp_representante;
    }


    public function resultado(Tramite $tramite)
    {
        $this->campos = [
            'id-solicitud' => intval($tramite->solicitud->ID_SOLICITUD),
            'id-padre-solicitud' => intval($tramite->solicitud->ID_PADRE_SOLICITUD),
            'id-tipo-solicitud' => intval($tramite->solicitud->ID_TIPO_SOLICITUD),
            'id-sub-tipo-solicitud' => $tramite->solicitud->ID_SUB_TIPO_SOLICITUD,
            'id-estado-solicitud' => $tramite->solicitud->ID_ESTADO,
        ];
        return $this;
    }

    public static function new($tipoSolicitud): TramiteMapper
    {
        $mappers = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO =>
                IngresoRegistroMapper::class,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO =>
                '',
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE =>
                IncorporacionDesvinculacionMapper::class,
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE =>
                IncorporacionDesvinculacionMapper::class,
            Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD =>
                ActualizacionModuloESOMapper::class,
            Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION =>
                ActualizacionModuloESOMapper::class,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA =>
                ActualizacionModuloViviendaMapper::class,
            Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS =>
                ActualizacionModuloESOMapper::class,
            Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO =>
                CambioRelacionParentescoMapper::class,
            Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS =>
                IngresoRegistroInstitucionalizadosMapper::class,
            Constante::TIPO_SOLICITUD_31_A_35_LOCACION_GENERICO =>
                '',
            Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS =>
                ComplementoPensionAlimentoMapper::class,
            Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD =>
                ComplementoCotizacionSaludMapper::class,
            Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL =>
                ComplementoEstablecimientoEducacionalMapper::class,
            Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ =>
                ComplementoBienRaizMapper::class,
            Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA =>
                ComplementoCompensacionEconomicaMapper::class,
            Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES =>
                ComplementoCuidadoresMapper::class,
            Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE =>
                ActualizacionIngresoMapper::class,
            Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE =>
                ActualizacionIngresoMapper::class,
            Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE =>
                ActualizacionIngresoMapper::class,
            Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION =>
                ActualizacionIngresoMapper::class,
            Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL =>
                ActualizacionIngresoMapper::class,
            Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD =>
                ActualizacionCotizacionSaludMapper::class,
            Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL =>
                ActualizacionEstablecimientoEducacionalMapper::class,
            Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ =>
                ActualizacionBienRaizMapper::class,
            Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL =>
                ActualizacionPropiedadAutomovilMapper::class,
            Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR =>
                ActualizacionEducacionSuperiorMapper::class,
            Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR =>
                CambioJefeHogarMapper::class,
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR =>
                IncorporacionDesvinculacionMapper::class,
            Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION =>
                IngresoRegistroMapper::class,
            Constante::TIPO_SOLICITUD_201_CAMBIO_DATOS_DE_CONTACTO =>
                '',
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA =>
                DomicilioViviendaMapper::class,
            Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO =>
                '',
            Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL =>
                '',
            Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS =>
                ConfirmarDatosMapper::class,
            Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA =>
                IngresoRegistroMapper::class,
        ];

        if (!array_key_exists($tipoSolicitud, $mappers)) {
            throw ExcepcionEjecucion::new('No se ha definido un Mapper para este tipo de solicitud. ' .
                '[tipo solicitud:' . $tipoSolicitud . ']', Constante::ERR_EJECUCION_NO_CONTROLADO);
        }
        $claseMapper = $mappers[$tipoSolicitud];
        return new $claseMapper();
    }
}
