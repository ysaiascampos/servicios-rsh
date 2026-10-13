<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteCanalOrigen extends ConstanteDocumentoSolicitud
{
    /**
     * CANALES (Tabla ASIGNACION.CANAL)
     * Son los tipos de privilegios con que se loguea un usuario en cada plataforma
     */
    const CANAL_1_MUNICIPAL = 1;
    const CANAL_2_CLAVE_UNICA = 2;
    const CANAL_3_RUN_CIUDADANO = 3;
    const CANAL_4_CHILE_ATIENDE_EJECUTOR = 4;
    const CANAL_5_SUPERVISION = 5;
    const CANAL_6_FUNFA = 6;
    const CANAL_7_TOTEM = 7;

//    /*
//    * definicion de portal para log de cartola
//    */
//    const CANAL_1_LOG_CARTOLA_TOTEM = 1;
//    const CANAL_2_LOG_CARTOLA_MUNICIPAL = 2;
//    const CANAL_3_LOG_CARTOLA_CIUDADANO = 3;

    // Texto como se manda el canal en los cuerpos de los APIs
    const CANAL_1_STRING_MINUS_MUNICIPAL = 'municipal';
    const CANAL_2_STRING_MINUS_CLAVE_UNICA = 'clave unica';
    const CANAL_3_STRING_MINUS_RUN_CIUDADANO = 'run ciudadano';
    const CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR = 'chile atiende ejecutor';
    const CANAL_5_STRING_MINUS_SUPERVISION = 'supervision';
    const CANAL_6_STRING_MINUS_FUNFA = 'funfa';
    const CANAL_7_STRING_MINUS_TOTEM = 'totem';

    const CANAL_4_STRING_MAYUS_CHILE_ATIENDE_EJECUTOR = 'Chile Atiende Ejecutor';
    const CANAL_6_STRING_MAYUS_FUNFA = 'FUNFA';
    const CANAL_7_STRING_MAYUS_TOTEM = 'TOTEM';


    // Texto como se guarda el campo canal en tabla ab_solicitud
    const CANAL_1_SAVE_MUNICIPAL = 'Municipal';
    const CANAL_2_SAVE_CLAVE_UNICA = 'Clave Unica';
    const CANAL_3_SAVE_RUN_CIUDADANO = 'Run Ciudadano';
    const CANAL_4_SAVE_CHILE_ATIENDE_EJECUTOR = 4;
    const CANAL_5_SAVE_SUPERVISION = 5;
    const CANAL_6_SAVE_FUNFA = 6;

    // Texto como se guarda el campo canal en tabla ab_solicitud
    const CANAL_1_NOMBRE_MUNICIPAL = 'Municipal';
    const CANAL_2_NOMBRE_CLAVE_UNICA = 'Clave Unica';
    const CANAL_3_NOMBRE_RUN_CIUDADANO = 'Run Ciudadano';
    const CANAL_4_NOMBRE_CHILE_ATIENDE_EJECUTOR = 4;
    const CANAL_5_NOMBRE_SUPERVISION = 5;
    const CANAL_6_NOMBRE_FUNFA = 6;

    public static $canalSaveSegunCanalMinuscula = [
        self::CANAL_1_MUNICIPAL => self::CANAL_1_MUNICIPAL,
        self::CANAL_2_CLAVE_UNICA => self::CANAL_2_CLAVE_UNICA,
        self::CANAL_3_RUN_CIUDADANO => self::CANAL_3_RUN_CIUDADANO,
        self::CANAL_4_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_SUPERVISION => self::CANAL_5_SUPERVISION,
        self::CANAL_6_FUNFA => self::CANAL_6_SAVE_FUNFA,
        self::CANAL_1_STRING_MINUS_MUNICIPAL => self::CANAL_1_MUNICIPAL,
        self::CANAL_2_STRING_MINUS_CLAVE_UNICA => self::CANAL_2_CLAVE_UNICA,
        self::CANAL_3_STRING_MINUS_RUN_CIUDADANO => self::CANAL_3_RUN_CIUDADANO,
        self::CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_STRING_MINUS_SUPERVISION => self::CANAL_5_SUPERVISION,
        self::CANAL_6_STRING_MINUS_FUNFA => self::CANAL_6_SAVE_FUNFA,
        self::CANAL_1_SAVE_MUNICIPAL => self::CANAL_1_MUNICIPAL,
        self::CANAL_2_SAVE_CLAVE_UNICA => self::CANAL_2_CLAVE_UNICA,
        self::CANAL_3_SAVE_RUN_CIUDADANO => self::CANAL_3_RUN_CIUDADANO,
    ];

    public static $canalTextoCapitalizado = [
        self::CANAL_1_MUNICIPAL => self::CANAL_1_NOMBRE_MUNICIPAL,
        self::CANAL_2_CLAVE_UNICA => self::CANAL_2_NOMBRE_CLAVE_UNICA,
        self::CANAL_3_RUN_CIUDADANO => self::CANAL_3_NOMBRE_RUN_CIUDADANO,
        self::CANAL_4_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_NOMBRE_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_SUPERVISION => self::CANAL_5_NOMBRE_SUPERVISION,
        self::CANAL_6_FUNFA => self::CANAL_6_NOMBRE_FUNFA,
        self::CANAL_1_STRING_MINUS_MUNICIPAL => self::CANAL_1_NOMBRE_MUNICIPAL,
        self::CANAL_2_STRING_MINUS_CLAVE_UNICA => self::CANAL_2_NOMBRE_CLAVE_UNICA,
        self::CANAL_3_STRING_MINUS_RUN_CIUDADANO => self::CANAL_3_NOMBRE_RUN_CIUDADANO,
        self::CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_NOMBRE_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_STRING_MINUS_SUPERVISION => self::CANAL_5_NOMBRE_SUPERVISION,
        self::CANAL_6_STRING_MINUS_FUNFA => self::CANAL_6_NOMBRE_FUNFA,
        self::CANAL_1_SAVE_MUNICIPAL => self::CANAL_1_NOMBRE_MUNICIPAL,
        self::CANAL_2_SAVE_CLAVE_UNICA => self::CANAL_2_NOMBRE_CLAVE_UNICA,
        self::CANAL_3_SAVE_RUN_CIUDADANO => self::CANAL_3_NOMBRE_RUN_CIUDADANO,
    ];
    public static $CANAL_1_MUNICIPAL_VALIDOS = [
        self::CANAL_1_MUNICIPAL,
        self::CANAL_1_STRING_MINUS_MUNICIPAL,
    ];
    public static $CANAL_2_CLAVE_UNICA_VALIDOS = [
        self::CANAL_2_CLAVE_UNICA,
        self::CANAL_2_STRING_MINUS_CLAVE_UNICA,
    ];
    public static $CANAL_3_RUN_CIUDADANO_VALIDOS = [
        self::CANAL_3_RUN_CIUDADANO,
        self::CANAL_3_STRING_MINUS_RUN_CIUDADANO,
    ];
    public static $CANAL_4_CHILE_ATIENDE_EJECUTOR_VALIDOS = [
        self::CANAL_4_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR,
    ];
    public static $CANAL_5_SUPERVISION_VALIDOS = [
        self::CANAL_5_SUPERVISION,
        self::CANAL_5_STRING_MINUS_SUPERVISION,
    ];

    public static $CANAL_6_FUNFA_VALIDOS = [
        self::CANAL_6_FUNFA,
        self::CANAL_6_STRING_MINUS_FUNFA,
    ];

    /*
     * Devuelve el canal en formato numero para ser evaluado en array de flujo
     * @var int[]
     */
    public static $CANAL_VALIDO_PARA_ARRAY_FLUJO = [
        self::CANAL_1_MUNICIPAL => self::CANAL_1_MUNICIPAL,
        self::CANAL_2_CLAVE_UNICA => self::CANAL_2_CLAVE_UNICA,
        self::CANAL_3_RUN_CIUDADANO => self::CANAL_3_RUN_CIUDADANO,
        self::CANAL_4_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_SUPERVISION => self::CANAL_5_SUPERVISION,
        self::CANAL_6_FUNFA => self::CANAL_6_FUNFA,
        self::CANAL_1_STRING_MINUS_MUNICIPAL => self::CANAL_1_MUNICIPAL,
        self::CANAL_2_STRING_MINUS_CLAVE_UNICA => self::CANAL_2_CLAVE_UNICA,
        self::CANAL_3_STRING_MINUS_RUN_CIUDADANO => self::CANAL_3_RUN_CIUDADANO,
        self::CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR => self::CANAL_4_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_STRING_MINUS_SUPERVISION => self::CANAL_5_SUPERVISION,
        self::CANAL_6_STRING_MINUS_FUNFA => self::CANAL_6_FUNFA,
    ];

    public static $CANAL_VALIDO_PARA_INPUT = [
        self::CANAL_1_STRING_MINUS_MUNICIPAL,
        self::CANAL_2_STRING_MINUS_CLAVE_UNICA,
        self::CANAL_3_STRING_MINUS_RUN_CIUDADANO,
        self::CANAL_4_STRING_MINUS_CHILE_ATIENDE_EJECUTOR,
        self::CANAL_5_STRING_MINUS_SUPERVISION,
        self::CANAL_6_STRING_MINUS_FUNFA,
    ];

    /**
     * Son cada una de las plataformas que consumen una API
     * Tabla ASIGNACION.AB_ORIGEN_REGISTRO
     * Ocupado en campo ORIGEN_REGISTRO de tabla AB_SOLICITUD
     */

    // Texto como se guarda el origen en la tabla AB_SOLICITUD
    // y también texto como puede ser enviado desde una API
    const ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL = 1;
    const ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA = 2;
    const ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA = 3;
    const ORIGEN_REGISTRO_4_PLATAFORMA_SUPERVISION = 4;

    // Texto como puede ser enviado el origen desde una API
    const ORIGEN_REGISTRO_1_STRING_MINUS_PLATAFORMA_MUNICIPAL = 'plataforma municipal';
    const ORIGEN_REGISTRO_2_STRING_MINUS_PLATAFORMA_CIUDADANA = 'plataforma ciudadana';
    const ORIGEN_REGISTRO_3_STRING_MINUS_PLATAFORMA_CLASE_MEDIA_PROTEGIDA = 'plataforma red clase media';

    // Para comparar por origen de registro
    // Utiles::inArrayMinuscula($origenRegistro, Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)
    public static $ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS = [
        self::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL,
        self::ORIGEN_REGISTRO_1_STRING_MINUS_PLATAFORMA_MUNICIPAL,
    ];
    public static $ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS = [
        self::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA,
        self::ORIGEN_REGISTRO_2_STRING_MINUS_PLATAFORMA_CIUDADANA,
    ];
    public static $ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS = [
        self::ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA,
        self::ORIGEN_REGISTRO_3_STRING_MINUS_PLATAFORMA_CLASE_MEDIA_PROTEGIDA,
    ];

    public static $ORIGEN_REGISTRO_VALIDO_PARA_INPUT = [
        self::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL,
        self::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA,
        self::ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA,
    ];

    public static $ORIGEN_REGISTRO_TEXTO = [
        self::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL => 'Municipal',
        self::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA => 'Ciudadano',
        self::ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA => 'Red Clase Media',
        self::ORIGEN_REGISTRO_4_PLATAFORMA_SUPERVISION => 'Supervisión',
        self::ORIGEN_REGISTRO_1_STRING_MINUS_PLATAFORMA_MUNICIPAL => 'Municipal',
        self::ORIGEN_REGISTRO_2_STRING_MINUS_PLATAFORMA_CIUDADANA => 'Ciudadano',
        self::ORIGEN_REGISTRO_3_STRING_MINUS_PLATAFORMA_CLASE_MEDIA_PROTEGIDA => 'Red Clase Media',
    ];

    /*
     * definicion de portal para descarga de formularios
    */
    public const PORTAL_MUNICIPAL = 'municipal';
    public const PORTAL_CIUDADANO = 'ciudadano';
    public static $PORTAL_VALIDOS = [
        self::PORTAL_MUNICIPAL,
        self::PORTAL_CIUDADANO,
    ];



}
