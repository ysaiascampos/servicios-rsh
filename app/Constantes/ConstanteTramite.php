<?php

namespace App\Constantes;

use App\Clases\ActualizacionBienRaiz;
use App\Clases\ActualizacionCotizacionSalud;
use App\Clases\ActualizacionEducacionSuperior;
use App\Clases\ActualizacionEstablecimientoEducacional;
use App\Clases\ActualizacionIngresoCapital;
use App\Clases\ActualizacionIngresoPension;
use App\Clases\ActualizacionIngresoTrabajadorDependiente;
use App\Clases\ActualizacionIngresoTrabajadorIndependiente;
use App\Clases\ActualizacionIngresoTrabajadorMixto;
use App\Clases\ActualizacionMail;
use App\Clases\ActualizacionPropiedadJuridico;
use App\Clases\ActualizacionPropiedadVehiculo;
use App\Clases\ActualizacionTelefono;
use App\Clases\CambioDatosDeContacto;
use App\Clases\CambioJefeDeHogar;
use App\Clases\CambioRelacionParentesco;
use App\Clases\ComplementoBienRaiz;
use App\Clases\ComplementoCompensacionEconomica;
use App\Clases\ComplementoBienRaizSinLucro;
use App\Clases\ComplementoCotizacionSalud;
use App\Clases\ComplementoCuidadores;
use App\Clases\ComplementoEstablecimientoEducacional;
use App\Clases\ComplementoPensionAlimentos;
use App\Clases\ConfirmarDatos;
use App\Clases\DesvinculacionIntegrante;
use App\Clases\DesvinculacionJefeDeHogar;
use App\Clases\DomicilioVivienda;
use App\Clases\IncorporacionIntegrante;
use App\Clases\IngresoInstitucionalizados;
use App\Clases\IngresoRegistro;
use App\Clases\IngresoRegistroNNA;
use App\Clases\ModuloEducacion;
use App\Clases\ModuloOcupacionIngresos;
use App\Clases\ModuloSalud;
use App\Clases\ModuloVivienda;
use App\Clases\Supervision;

class ConstanteTramite extends ConstanteUrl
{
    public static $clasesTramitePorTipoSolicitud = [

        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO =>
            IngresoRegistro::class,
        Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO =>
            DomicilioVivienda::class,
        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE =>
            IncorporacionIntegrante::class,
        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE =>
            DesvinculacionIntegrante::class,
        Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD =>
            ModuloSalud::class,
        Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION =>
            ModuloEducacion::class,
        Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA =>
            ModuloVivienda::class,
        Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS =>
            ModuloOcupacionIngresos::class,
        Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO =>
            CambioRelacionParentesco::class,
        Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS =>
            IngresoInstitucionalizados::class,
        Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS =>
            ComplementoPensionAlimentos::class,
        Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD =>
            ComplementoCotizacionSalud::class,
        Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL =>
            ComplementoEstablecimientoEducacional::class,
        Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ =>
            ComplementoBienRaiz::class,
        Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA =>
            ComplementoCompensacionEconomica::class,
        Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES =>
            ComplementoCuidadores::class,
        Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO =>
            ComplementoBienRaizSinLucro::class,
        Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE =>
            ActualizacionIngresoTrabajadorDependiente::class,
        Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE =>
            ActualizacionIngresoTrabajadorIndependiente::class,
        Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE =>
            ActualizacionIngresoTrabajadorMixto::class,
        Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION =>
            ActualizacionIngresoPension::class,
        Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL =>
            ActualizacionIngresoCapital::class,
        Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD =>
            ActualizacionCotizacionSalud::class,
        Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL =>
            ActualizacionEstablecimientoEducacional::class,
        Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ =>
            ActualizacionBienRaiz::class,
        Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL =>
            ActualizacionPropiedadVehiculo::class,
        Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR =>
            ActualizacionEducacionSuperior::class,
        Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA=>
            ActualizacionPropiedadJuridico::class,
        Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR =>
            CambioJefeDeHogar::class,
        Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR =>
            DesvinculacionJefeDeHogar::class,
        Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION =>
            Supervision::class,
        Constante::TIPO_SOLICITUD_201_CAMBIO_DATOS_DE_CONTACTO =>
            CambioDatosDeContacto::class,
        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA =>
            DomicilioVivienda::class,
        Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO =>
            ActualizacionTelefono::class,
        Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL =>
            ActualizacionMail::class,
        Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS =>
            ConfirmarDatos::class,
        Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA =>
            IngresoRegistroNNA::class
    ];
}
