<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */

/**
 * Constante con Y en medio: son los extremos y todos los del medio deben estar en la comparación. Ejemplo:
 *  'between:' . implode(',', Constante::$CODIGO_COMUNA_1_Y_99999999_POSIBLES_RESPUESTAS),                          // desde Validator
 *  Utiles::condicionEntreDosNumeros($runCiudadano, Constante::$RUN_1000000_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC) // desde if
 *  ->whereBetween('per_run', Constante::$RUN_1000000_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC)                       // desde Eloquent
 *
 * Constante con A en medio: son valores fijos que deben estar en la comparación ningún otro. Ejemplo:
 *  'in:' . implode(',', Constante::$RESPUESTA_V2_1_A_6_POSIBLES_RESPUESTAS);                                       // desde Validator
 *  in_array($canal, Constante::$CANAL_MUNICIPAL_VALORES_PERMITIDOS)                                                // desde if
 *  ->whereIn('grupfami_estado', Constante::$ESTADO_GRUPO_FAMILIAR_3_5_7)                                           // desde Eloquent
 */
abstract class ConstanteMinimosMaximos extends ConstantePerfil
{
    /**
     * RUN
     */
    const RUN_1_MINIMO_NUMERIC = 1;
    const RUN_99999999_MAXIMO_NUMERIC = 99999999;
    public static $RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::RUN_1_MINIMO_NUMERIC,
        self::RUN_99999999_MAXIMO_NUMERIC
    ];

    /**
     * MONTO
     */
    const MONTO_0_MINIMO_NUMERIC = 0;
    const MONTO_2147483647_MAXIMO_NUMERIC = 2147483647;
    public static $MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC = [
        self::MONTO_0_MINIMO_NUMERIC,
        self::MONTO_2147483647_MAXIMO_NUMERIC
    ];

    /**
     * NUMERO DE SERIE Cedula Identidad
     */
    const NUMERACION_CI_MIN = 8;
    const NUMERACION_CI_MAX = 9;
    public static $NROSERIE_CI = [
        self::NUMERACION_CI_MIN,
        self::NUMERACION_CI_MAX
    ];

    /**
     * PAREJAS
     */
    const RESPUESTA_PAREJA_1_MINIMO_NUMERIC = 1;
    const RESPUESTA_PAREJA_99_MAXIMO_NUMERIC = 99;
    public static $RESPUESTA_PAREJA_1_Y_99_POSIBLES_RESPUESTAS_NUMERIC = [
        self::RESPUESTA_PAREJA_1_MINIMO_NUMERIC,
        self::RESPUESTA_PAREJA_99_MAXIMO_NUMERIC
    ];

    /**
     * COMUNA
     */
    const CODIGO_COMUNA_1_MINIMO_NUMERIC = 1;
    const CODIGO_COMUNA_99999999_MAXIMO_NUMERIC = 99999999;
    public static $CODIGO_COMUNA_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::CODIGO_COMUNA_1_MINIMO_NUMERIC,
        self::CODIGO_COMUNA_99999999_MAXIMO_NUMERIC
    ];

    /**
     * LOCALIDAD / CIUDAD
     */
    const CODIGO_LOCALIDAD_1_MINIMO_NUMERIC = 1;
    const CODIGO_LOCALIDAD_99999999_MAXIMO_NUMERIC = 99999999;
    public static $CODIGO_LOCALIDAD_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::CODIGO_LOCALIDAD_1_MINIMO_NUMERIC,
        self::CODIGO_LOCALIDAD_99999999_MAXIMO_NUMERIC
    ];

    /**
     * AH AGRUPACIÓN HABITACIONAL
     */
    const CODIGO_AH_1_MINIMO_NUMERIC = 1;
    const CODIGO_AH_99999999_MAXIMO_NUMERIC = 99999999;
    public static $CODIGO_AH_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::CODIGO_AH_1_MINIMO_NUMERIC,
        self::CODIGO_AH_99999999_MAXIMO_NUMERIC
    ];

    /**
     * UV UNIDAD VECINAL
     */
    const CODIGO_UV_1_MINIMO_STRING = 1;
    const CODIGO_UV_99999999_MAXIMO_STRING = 99999999;
    public static $CODIGO_UV_1_Y_99999999_POSIBLES_RESPUESTAS_STRING = [
        self::CODIGO_UV_1_MINIMO_STRING,
        self::CODIGO_UV_99999999_MAXIMO_STRING
    ];

    /**
     * CÓDIGO CALLE
     */
    const CODIGO_CALLE_1_MINIMO_NUMERIC = 1;
    const CODIGO_CALLE_99999999999999999999_MAXIMO_NUMERIC = 99999999999999999999;
    public static $CODIGO_CALLE_1_Y_99999999999999999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::CODIGO_CALLE_1_MINIMO_NUMERIC,
        self::CODIGO_CALLE_99999999999999999999_MAXIMO_NUMERIC
    ];

    /**
     * NUMERACION DEL DOMICILIO
     */
    const NUMERACION_1_MINIMO_NUMERIC = 1;
    const NUMERACION_999999_MAXIMO_NUMERIC = 999999;
    public static $NUMERACION_1_Y_999999_POSIBLES_RESPUESTAS_NUMERIC = [
        self::NUMERACION_1_MINIMO_NUMERIC,
        self::NUMERACION_999999_MAXIMO_NUMERIC
    ];

    /**
     * DEPTO DEL DOMICILIO
     */
    const DEPTO_10_MAXIMO_STRING = 10;

    /**
     * BLOCK DEL DOMICILIO
     */
    const BLOCK_10_MAXIMO_STRING = 10;

    /**
     * SITIO O KM DEL DOMICILIO
     */
    const SITIO_10_MAXIMO_STRING = 10;

    /**
     * CASA DEL DOMICILIO
     */
    const CASA_10_MAXIMO_STRING = 10;

    /**
     * COMENTARIO (OBSERVACION para pasar de estado 4 a 3)
     */
    const COMENTARIO_10_MINIMO_STRING = 10;
    const COMENTARIO_3000_MAXIMO_STRING = 3000;
    public static $COMENTARIO_1_Y_3000_POSIBLES_RESPUESTAS_STRING = [
        self::COMENTARIO_10_MINIMO_STRING,
        self::COMENTARIO_3000_MAXIMO_STRING
    ];

    /**
     * REFERENCIA
     */
    const REFERENCIA_1_MINIMO_STRING = 1;
    const REFERENCIA_400_MAXIMO_STRING = 400;
    public static $REFERENCIA_1_Y_400_POSIBLES_RESPUESTAS_STRING = [
        self::REFERENCIA_1_MINIMO_STRING,
        self::REFERENCIA_400_MAXIMO_STRING
    ];

    /**
     * NRO CLIENTE AGUA
     */
    const NRO_CLIENTE_AGUA_999999999999999_MAXIMO_STRING = 999999999999999;

    /**
     * NRO CLIENTE ENERGIA
     */
    const NRO_CLIENTE_LUZ_999999999999999_MAXIMO_STRING = 999999999999999;

    /**
     * OBSERVACIONES
     */
    const OBSERVACIONES_10_MINIMO_STRING = 10;
    const OBSERVACIONES_500_MAXIMO_STRING = 500;
    public static $OBSERVACIONES_10_Y_500_POSIBLES_RESPUESTAS_STRING = [
        self::OBSERVACIONES_10_MINIMO_STRING,
        self::OBSERVACIONES_500_MAXIMO_STRING
    ];

    /**
     * Campo 'respuesta' para servicio tramites - Locacion 31-35
     */
    const LOCACION_RESPUESTA_PENDIENTE = 'pendiente';
    const LOCACION_RESPUESTA_AGREGADA = 'agregada';
    const LOCACION_RESPUESTA_MODIFICADA = 'modificada';
    const LOCACION_RESPUESTA_EXISTENTE = 'existente';
    public static $LOCACION_RESPUESTA_VALIDO = [
        self::LOCACION_RESPUESTA_AGREGADA,
        self::LOCACION_RESPUESTA_MODIFICADA,
        self::LOCACION_RESPUESTA_EXISTENTE,
        self::LOCACION_RESPUESTA_PENDIENTE
    ];

    /**
     * Compensacion economica
     */
    const COMPENSACION_ECONOMICA_MAXIMO_CUOTAS = 36;
    const COMPENSACION_ECONOMICA_MONTO_MINIMO = 10000;

    /**
     * Propiedad de vehíciulo
     */
    const PROPIEDAD_VEHICULO_ANIO_MINIMO = 1990;

    /**
     * Campo GEO_LONGITUD_CREACION de tabla ASIGNACION.AB_SOLICITUD y
     * Campo GEO_LATITUD_CREACION de tabla ASIGNACION.AB_SOLICITUD
     */
    const GEO_LATITUD_73_MINIMO = -73.000000;       // Latitud desde Visviri
    const GEO_LATITUD_17_MAXIMO = -17.000000;       // Latitud hasta la Antártica de Chile.
    const GEO_LONGITUD_109_MINIMO = -109.000000;    // Longitud desde la punta de Hang-Roa
    const GEO_LONGITUD_55_MAXIMO = -55.000000;      // Longitud hasta la Antártica de Chile.
    public static $GEO_LATITUD_73_Y_17_POSIBLES_RESPUESTAS_NUMERIC = [
        self::GEO_LATITUD_73_MINIMO,
        self::GEO_LATITUD_17_MAXIMO
    ];
    public static $GEO_LONGITUD_109_Y_55_POSIBLES_RESPUESTAS_NUMERIC = [
        self::GEO_LONGITUD_109_MINIMO,
        self::GEO_LONGITUD_55_MAXIMO
    ];

}
