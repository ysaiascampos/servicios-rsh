<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteFuente extends ConstanteMinimosMaximos
{
    /**
     * TIPOS DE FUENTES (Tabla ASIGNACION_EXPORT.FUENTES)
     * SDP - Superintendencia de Pensiones, organismo que vela el funcionar de Administradoras de Fondos de Pensiones (AFP), Instituto de Previsión Social (IPS) y Administradora de Fondos de Cesantía (AFC).
     * SDS - Superintendencia de Salud, organismo que supervigilar las ISAPREs y Fondo Nacional de Salud (FONASA).
     * SII - Servicio de Impuestos Internos, servicio público que fiscaliza los impuestos internos de Chile.
     */
    const FUENTE_0_SIN_INGRESOS = 0;         //
    const FUENTE_1_AFP_INDEPENDIENTE = 1;    // Indep.(SdP) Superintendencia de Pensiones (AFP)
    const FUENTE_5_AFP_DEPENDIENTE = 5;      // Dep.(SdP)   Superintendencia de Pensiones (AFP)
    const FUENTE_2_ISAPRE_INDEPENDIENTE = 2; // Indep.(SdS) Superintendencia de Salud (ISAPREs o FONASA)
    const FUENTE_6_ISAPRE_DEPENDIENTE = 6;   // Dep.(SdS)   Superintendencia de Salud (ISAPREs o FONASA)
    const FUENTE_3_SII_INDEPENDIENTE = 3;    // Indep.(SII) Servicio de Impuestos Internos
    const FUENTE_7_SII_DEPENDIENTE = 7;      // Dep.(SII)   Servicio de Impuestos Internos
    const FUENTE_4_AFC_DEPENDIENTE = 4;      // Dep.(AFC)   Administradora de Fondos de Cesantía
    const FUENTE_20_OTRAS_ISAPRE = 20;       // Otras (SdS) Superintendencia de Salud
    const CAPITAL = 20;

    const FUENTE_8_AFC_DEPENDIENTE_Y_AFP_INDEPENDIENTE = 8;         // Dep.(AFC) y Indep.(SdP)
    const FUENTE_9_AFC_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE = 9;      // Dep.(AFC) y Indep.(SdS)
    const FUENTE_10_AFC_DEPENDIENTE_Y_SII_INDEPENDIENTE = 10;       // Dep.(AFC) y Indep.(SII)
    const FUENTE_11_AFP_DEPENDIENTE_Y_AFP_INDEPENDIENTE = 11;       // Dep.(SdP) y Indep.(SdP)
    const FUENTE_12_AFP_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE = 12;    // Dep.(SdP) y Indep.(SdS)
    const FUENTE_13_AFP_DEPENDIENTE_Y_SII_INDEPENDIENTE = 13;       // Dep.(SdP) y Indep.(SII)
    const FUENTE_14_ISAPRE_DEPENDIENTE_Y_AFP_INDEPENDIENTE = 14;    // Dep.(SdS) y Indep.(SdP)
    const FUENTE_15_ISAPRE_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE = 15; // Dep.(SdS) y Indep.(SdS)
    const FUENTE_16_ISAPRE_DEPENDIENTE_Y_SII_INDEPENDIENTE = 16;    // Dep.(SdS) y Indep.(SII)
    const FUENTE_17_SII_DEPENDIENTE_Y_AFP_INDEPENDIENTE = 17;       // Dep.(SII) y Indep.(SdP)
    const FUENTE_18_SII_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE = 18;    // Dep.(SII) y Indep.(SdS)
    const FUENTE_19_SII_DEPENDIENTE_Y_SII_INDEPENDIENTE = 19;       // Dep.(SII) y Indep.(SII)
    const FUENTE_21_OTRAS_ALTOS_INGRESOS_SII = 21;      // Otras: Altos ingresos SII
    const FUENTE_22_AUTOREPORTE = 22;                   // Autoreporte
    const FUENTE_23_IND_SDP_ACT_Y_O_RECT = 23;          // Ind.(SdP) actualizados
    const FUENTE_24_IND_SDS_ACT_Y_O_RECT = 24;          // Ind.(SdS) actualizados
    const FUENTE_25_IND_SII_ACT_Y_O_RECT = 25;          // Ind.(SII) actualizados
    const FUENTE_26_DEP_AFC_ACT_Y_O_RECT = 26;          // Dep.(AFC) actualizados
    const FUENTE_27_DEP_SDP_ACT_Y_O_RECT = 27;          // Dep.(SdP) actualizados
    const FUENTE_28_DEP_SDS_ACT_Y_O_RECT = 28;          // Dep.(SdS) actualizados
    const FUENTE_29_DEP_SII_ACT_Y_O_RECT = 29;          // Dep.(SII) actualizados
    const FUENTE_30_DEP_AFC_E_IND_SDP_ACT_Y_O_RECT = 30;    // Dep.(AFC) e Ind.(SdP) actualizados
    const FUENTE_31_DEP_AFC_E_IND_SDS_ACT_Y_O_RECT = 31;    // Dep.(AFC) e Ind.(SdS) actualizados
    const FUENTE_32_DEP_AFC_E_IND_SII_ACT_Y_O_RECT = 32;    // Dep.(AFC) e Ind.(SII) actualizados
    const FUENTE_33_DEP_SDP_E_IND_SDP_ACT_Y_O_RECT = 33;    // Dep.(SdP) e Ind.(SdP) actualizados
    const FUENTE_34_DEP_SDP_E_IND_SDS_ACT_Y_O_RECT = 34;    // Dep.(SdP) e Ind.(SdS) actualizados
    const FUENTE_35_DEP_SDP_E_IND_SII_ACT_Y_O_RECT = 35;    // Dep.(SdP) e Ind.(SII) actualizados
    const FUENTE_36_DEP_SDS_E_IND_SDP_ACT_Y_O_RECT = 36;    // Dep.(SdS) e Ind.(SdP) actualizados
    const FUENTE_37_DEP_SDS_E_IND_SDS_ACT_Y_O_RECT = 37;    // Dep.(SdS) e Ind.(SdS) actualizados
    const FUENTE_38_DEP_SDS_E_IND_SII_ACT_Y_O_RECT = 38;    // Dep.(SdS) e Ind.(SII) actualizados
    const FUENTE_39_DEP_SII_E_IND_SDP_ACT_Y_O_RECT = 39;    // Dep.(SII) e Ind.(SdP) actualizados
    const FUENTE_40_DEP_SII_E_IND_SDS_ACT_Y_O_RECT = 40;    // Dep.(SII) e Ind.(SdS) actualizados
    const FUENTE_41_DEP_SII_E_IND_SII_ACT_Y_O_RECT = 41;    // Dep.(SII) e Ind.(SII) actualizados
    const FUENTE_42_OTROS_SDS_ACT_Y_O_RECT = 42;            // Otros SdS actualizados
    const FUENTE_43_OTROS_SII_AI_ACT_Y_O_RECT = 43;         // Otros SII (AI) actualizados
    const FUENTE_44_SIN_INGRESOS_ACT_Y_O_RECT = 44;         // Sin ingresos  actualizados

    const FUENTE_100_INGRESO_DE_PENSIONES_IPS = 100;                                    // Ingreso de pensiones (IPS)
    const FUENTE_101_INGRESO_DE_PENSIONES_AFP = 101;                                    // Ingreso de pensiones (SdP)
    const FUENTE_102_INGRESO_DE_PENSIONES_SII = 102;                                    // Ingreso de pensiones SII (Otras pensiones)
    const FUENTE_103_INGRESO_DE_PENSIONES_RSH = 103;                                    // Ingreso de pensiones (RSH)
    const FUENTE_104_INGRESO_DE_PENSIONES_FFAA_Y_ORDEN = 104;                           // Ingreso de pensiones (FFAA y Orden)
    const FUENTE_105_INGRESO_DE_PENSIONES_IPS_Y_SII_FFAA_Y_ORDEN = 105;                 // Ingreso de pensiones IPS y SII (FFAA y Orden)
    const FUENTE_106_INGRESO_DE_PENSIONES_SDP_Y_SII_FFAA_Y_ORDEN = 106;                 // Ingreso de pensiones SdP y SII (FFAA y Orden)
    const FUENTE_107_INGRESO_DE_PENSIONES_SII_OTRAS_PENSIONES_Y_SII_FFAA_Y_ORDEN = 107; // Ingreso de pensiones SII (Otras pensiones) y SII (FFAA y Orden)
    const FUENTE_108_INGRESO_DE_PENSIONES_SIN_PENSION = 108;                            // Sin Pensión
    const FUENTE_109_INGRESO_DE_PENSIONES_PENSION_OBSOLETO = 109;                       // Ingreso de pensiones OBSOLETO

    const FUENTE_110_SDP_ACT_Y_O_RECT = 110;                        // SdP actualizados
    const FUENTE_111_SII_OTRAS_PENSIONES_ACT_Y_O_RECT = 111;        // SII (Otras pensiones) actualizados
    const FUENTE_112_AUTOREPORTADA_ACT_Y_O_RECT = 112;              // Autoreportada actualizados
    const FUENTE_113_SII_FFAA_Y_ORDEN_ACT_Y_O_RECT = 113;           // SII (FFAA y Orden) actualizados
    const FUENTE_114_IPS_Y_SII_FFAA_Y_ORDEN_ACT_Y_O_RECT = 114;     // IPS y SII (FFAA y Orden) actualizados

    const FUENTE_115_SDP_Y_SII_FFAA_Y_ORDEN_ACT_Y_O_RECT = 115;                 // SdP y SII (FFAA y Orden) actualizados
    const FUENTE_116_SII_OTRAS_PENSIONES_Y_SII_FFAA_Y_ORDEN_ACT_Y_O_RECT = 116; // SII (Otras pensiones) y SII (FFAA y Orden) actualizados
    const FUENTE_119_IPS_ACT_Y_O_RECT = 119;                                    // IPS actualizados
    const FUENTE_200_INGRESO_DE_CAPITAL = 200;                                  // Ingreso de capital
    const FUENTE_301_VEHICULO_S = 301;                                          // Vehículo(s)
    const FUENTE_302_PROPIEDAD_ES_BIENES_RAICES = 302;                          // Propiedad(es) (Bienes Raíces)
    const FUENTE_303_POSEE_COTIZACIONES_DE_ALTO_VALOR = 303;                    // Posee cotizaciones de alto valor 
    const FUENTE_304_ESTUDIA_EN_UN_COLEGIO_CON_MENSUALIDAD_DE_ALTO_COSTO = 304;     // Estudia en un colegio con mensualidad de alto costo
    const FUENTE_400_DESCUENTO_MONETARIO_POR_PENSION_DE_ALIMENTO = 400;             // Descuento monetario por pensión de alimento
    const FUENTE_401_APORTE_MONETARIO_POR_PENSION_DE_ALIMENTO = 401;                // Aporte monetario por pensión de alimento
    const FUENTE_402_APORTE_Y_DESCUENTO_MONETARIO_POR_PENSION_DE_ALIMENTO = 402;    // Aporte y Descuento monetario por pensión de alimento
    const FUENTE_406_MEDIO_PADRE_NO_PRESENTE = 406;                                 // Medio Padre No Presente
    const FUENTE_998_MENSAJE_AGRADECIMIENTO_ACT_INFO_RSH = 998;                     // Mensaje agradecimiento Act. Info RSH
    const FUENTE_999_MENSAJE_CARTOLA_CIERRE_MENSUAL = 999;                          // Mensaje Cartola Cierre Mensual

    public static $HABILITA_INGRESO_TRABAJADOR_DEPENDIENTE = [
        self::FUENTE_4_AFC_DEPENDIENTE,
        self::FUENTE_5_AFP_DEPENDIENTE,
        self::FUENTE_6_ISAPRE_DEPENDIENTE,
        self::FUENTE_7_SII_DEPENDIENTE,
        self::FUENTE_26_DEP_AFC_ACT_Y_O_RECT,
        self::FUENTE_27_DEP_SDP_ACT_Y_O_RECT,
        self::FUENTE_28_DEP_SDS_ACT_Y_O_RECT,
        self::FUENTE_29_DEP_SII_ACT_Y_O_RECT
    ];

    public static $HABILITA_INGRESO_TRABAJADOR_INDEPENDIENTE = [
        self::FUENTE_1_AFP_INDEPENDIENTE,
        self::FUENTE_2_ISAPRE_INDEPENDIENTE,
        self::FUENTE_3_SII_INDEPENDIENTE,
        self::FUENTE_23_IND_SDP_ACT_Y_O_RECT,
        self::FUENTE_24_IND_SDS_ACT_Y_O_RECT,
        self::FUENTE_25_IND_SII_ACT_Y_O_RECT
    ];

    public static $HABILITA_INGRESO_TRABAJADOR_DEP_E_IND = [
        self::FUENTE_8_AFC_DEPENDIENTE_Y_AFP_INDEPENDIENTE,
        self::FUENTE_9_AFC_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE,
        self::FUENTE_10_AFC_DEPENDIENTE_Y_SII_INDEPENDIENTE,
        self::FUENTE_11_AFP_DEPENDIENTE_Y_AFP_INDEPENDIENTE,
        self::FUENTE_12_AFP_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE,
        self::FUENTE_13_AFP_DEPENDIENTE_Y_SII_INDEPENDIENTE,
        self::FUENTE_14_ISAPRE_DEPENDIENTE_Y_AFP_INDEPENDIENTE,
        self::FUENTE_15_ISAPRE_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE,
        self::FUENTE_16_ISAPRE_DEPENDIENTE_Y_SII_INDEPENDIENTE,
        self::FUENTE_17_SII_DEPENDIENTE_Y_AFP_INDEPENDIENTE,
        self::FUENTE_18_SII_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE,
        self::FUENTE_19_SII_DEPENDIENTE_Y_SII_INDEPENDIENTE,
        self::FUENTE_20_OTRAS_ISAPRE,
        self::FUENTE_21_OTRAS_ALTOS_INGRESOS_SII,
        self::FUENTE_30_DEP_AFC_E_IND_SDP_ACT_Y_O_RECT,
        self::FUENTE_31_DEP_AFC_E_IND_SDS_ACT_Y_O_RECT,
        self::FUENTE_32_DEP_AFC_E_IND_SII_ACT_Y_O_RECT,
        self::FUENTE_33_DEP_SDP_E_IND_SDP_ACT_Y_O_RECT,
        self::FUENTE_34_DEP_SDP_E_IND_SDS_ACT_Y_O_RECT,
        self::FUENTE_35_DEP_SDP_E_IND_SII_ACT_Y_O_RECT,
        self::FUENTE_36_DEP_SDS_E_IND_SDP_ACT_Y_O_RECT,
        self::FUENTE_37_DEP_SDS_E_IND_SDS_ACT_Y_O_RECT,
        self::FUENTE_38_DEP_SDS_E_IND_SII_ACT_Y_O_RECT,
        self::FUENTE_39_DEP_SII_E_IND_SDP_ACT_Y_O_RECT,
        self::FUENTE_40_DEP_SII_E_IND_SDS_ACT_Y_O_RECT,
        self::FUENTE_41_DEP_SII_E_IND_SII_ACT_Y_O_RECT,
        self::FUENTE_42_OTROS_SDS_ACT_Y_O_RECT,
        self::FUENTE_43_OTROS_SII_AI_ACT_Y_O_RECT,
        self::FUENTE_44_SIN_INGRESOS_ACT_Y_O_RECT
    ];

    public static $HABILITA_INGRESO_PENSION = [
        self::FUENTE_100_INGRESO_DE_PENSIONES_IPS,
        self::FUENTE_101_INGRESO_DE_PENSIONES_AFP,
        self::FUENTE_102_INGRESO_DE_PENSIONES_SII,
        self::FUENTE_104_INGRESO_DE_PENSIONES_FFAA_Y_ORDEN,
        self::FUENTE_107_INGRESO_DE_PENSIONES_SII_OTRAS_PENSIONES_Y_SII_FFAA_Y_ORDEN,
        self::FUENTE_105_INGRESO_DE_PENSIONES_IPS_Y_SII_FFAA_Y_ORDEN,
        self::FUENTE_106_INGRESO_DE_PENSIONES_SDP_Y_SII_FFAA_Y_ORDEN,
        self::FUENTE_114_IPS_Y_SII_FFAA_Y_ORDEN_ACT_Y_O_RECT,
        self::FUENTE_119_IPS_ACT_Y_O_RECT
    ];

    public static $HABILITA_INGRESO_CAPITAL = [
        self::FUENTE_200_INGRESO_DE_CAPITAL
    ];

    /**
     * Glosas de fuentes
     */
    const AFP_GLOSA = 'Administradora De fondos de Pensiones (AFP)';
    const ISAPRE_GLOSA = 'ISAPRE';
    const SII_GLOSA = 'Servicio de Impuestos Internos (SII)';
    const AFC_GLOSA = 'Administradora de Fondos de Cesantía (AFC)';
    const IPS_GLOSA = 'Instituto de Prevision Social (IPS)';

    /**
     * Números de solicitud
     */
    const INGRESO_SOL_DEP = 1;
    const INGRESO_SOL_INDEP = 2;
    const INGRESO_SOL_MIXTO = 3;
    const INGRESO_SOL_PENSION = 4;
    const INGRESO_SOL_CAPITAL = 5;

    /**
     * Tabla ASIGNACION.FUENTES
     * Ocupado en campo FUENTE de tablas ASIGNACION.GRUPOS_FAMILIARES, ASIGNACION.BENEFICIARIOS_GRUPOSFAMILIARES, ASIGNACION.FICHAS
     */
    const FUENTE_INGRESO_1_FPS = 1;                     // Ficha de Protección Social
    const FUENTE_INGRESO_2_FS = 2;                      // Ficha Social
    const FUENTE_INGRESO_3_AFC_RENTA_IMPONIBLE = 3;
    const FUENTE_INGRESO_4_SII_FORMULARIO_22 = 4;
    const FUENTE_INGRESO_5_SII_DECLARACION_JURADA_F22 = 5;
    const FUENTE_INGRESO_6_SII_PROPIEDADES = 6;
    const FUENTE_INGRESO_7_SUPERSALUD_COTIZACIONES = 7; // Cotizaciones de la Superintendencia de Salud
    const FUENTE_INGRESO_8_MINEDUC_MATRICULAS = 8;
    const FUENTE_INGRESO_9_SRCEI_VEHICULOS = 9;         // Servicio de Registro Civil e Identificación
    const FUENTE_INGRESO_10_SUPERSALUD_CARGAS = 10;     // Superintendencia de Salud Cotizaciones
    const FUENTE_INGRESO_11_BENEFICIOS_MUNICIPALES = 11;
    const FUENTE_INGRESO_12_BENEFICIOS_SOCIALES = 12;
    const FUENTE_INGRESO_13_AFP = 13;
    const FUENTE_INGRESO_14_ISAPRE = 14;
    const FUENTE_INGRESO_15_IPS = 15;                   // Instituto de Previsión Social
    const FUENTE_INGRESO_16_SRCEI_HIJOS = 16;           // Servicio de Registro Civil e Identificación
    const FUENTE_INGRESO_22_CAMBIO_EXTARORDINARIO_MARZO_2016 = 22; // Ficha de Proteccion Social
    const FUENTE_INGRESO_23_CAMBIO_20160825_SOL_1_CAMBIO_VIGENCIA = 23;
    const FUENTE_INGRESO_99_PROCESO_BATCH_FALLECIDOS = 99;
    const FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB = 100; // Desde plataforma web

    public static $TIPO_SOLICITUD_INGRESO = [
        self::INGRESO_SOL_DEP => 60,
        self::INGRESO_SOL_INDEP => 61,
        self::INGRESO_SOL_MIXTO => 62,
        self::INGRESO_SOL_PENSION => 63,
        self::INGRESO_SOL_CAPITAL => 64
    ];

}
