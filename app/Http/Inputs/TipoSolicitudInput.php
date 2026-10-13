<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class TipoSolicitudInput extends ServiceInput
{
    protected function define()
    {
        $this->removerCamposNull = true;

        $this->campos = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO =>
                'object:' . IngresoRegistroInput::class,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO =>
                'object:' . DomicilioViviendaInput::class,
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE =>
                'array|object:' . IntegranteGrupoFamiliarInput::class,
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE =>
                'object:' . IntegranteGrupoFamiliarInput::class,
            Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD =>
                'object:' . ModuloSaludInput::class,
            Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION =>
                'object:' . ModuloEducacionInput::class,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA =>
                'object:' . ModuloVivientaInput::class,
            Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS =>
                'object:' . ModuloOcupacionIngresoInput::class,
            Constante::TIPO_SOLICITUD_31_A_35_LOCACION_GENERICO =>
                'object:' . LocacionInput::class,
            Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS =>
                'object:' . PensionAlimentosInput::class,
            Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD =>
                'object:' . ComplementoCotizacionSaludInput::class,
            Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL =>
                'object:' . ComplementoEstablecimientoEducacionalInput::class,
            Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ =>
                'object:' . ComplementoBienRaizInput::class,
            Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA =>
                'object:' . CompensacionEconomicaInput::class,
            Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES =>
                'object:' . CompensacionCuidadoresInput::class,
            Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE =>
                'object:' . IngresoLaboralInput::class,
            Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE =>
                'object:' . IngresoLaboralInput::class,
            Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE =>
                'object:' . IngresoLaboralInput::class,
            Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION =>
                'object:' . IngresoPensionInput::class,
            Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL =>
                'object:' . IngresoCapitalInput::class,
            Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD =>
                'object:' . ActualizacionCotizacionSaludInput::class,
            Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL =>
                'object:' . ActualizacionEstablecimientoEducacionalInput::class,
            Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ =>
                'object:' . ActualizacionBienRaizInput::class,
            Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL =>
                'object:' . PropiedadVehiculoInput::class,
            Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR =>
                'object:' . InformacionEducacionSuperiorInput::class,
            Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR =>
                'object:' . IntegranteGrupoFamiliarInput::class,
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR =>
                'object:' . IntegranteGrupoFamiliarInput::class,
            Constante::TIPO_SOLICITUD_201_CAMBIO_DATOS_DE_CONTACTO =>
                'object:' . PersonaContactoInput::class,
            Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO =>
                'object:' . PersonaContactoInput::class,
            Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL =>
                'object:' . PersonaContactoInput::class,
        ];
    }

    public function getTipo()
    {
        // considera solo una solicitud por consumo
        return key(array_filter($this->attributes));
    }
}
