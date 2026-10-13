<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstantePerfil extends ConstantePersona
{
    /**
     * PERFILES (tabla AB_PERFIL)
     */
    const PERFIL_1_CIUDADANO = 1;
    const PERFIL_2_ADMINISTRADOR_NACIONAL = 2;
    const PERFIL_3_CONSULTA_NACIONAL = 3;
    const PERFIL_4_REPORTES = 4;
    const PERFIL_5_SIT = 5;                             // _SISTEMA_DE_INFORMACION_TERRITORIAL // SISTEMA DE INFORMACIÓN TERRITORIAL
    const PERFIL_6_COORDINADOR_NACIONAL = 6;
    const PERFIL_7_SUPERVISION_REGIONAL = 7;
    const PERFIL_8_ATENCION_DE_CASO = 8;                // Llamado también DIGITADOR en el código
    const PERFIL_9_CONSULTA_COMUNAL = 9;
    const PERFIL_10_ATENCION_DE_CASO_WEB = 10;
    const PERFIL_11_EMISOR_DE_CARTOLA = 11;
    const PERFIL_12_ENCARGADO_COMUNAL = 12;
    const PERFIL_13_RECEPCION = 13;
    const PERFIL_14_CHILE_ATIENDE = 14;
    const PERFIL_15_CALL_CENTER = 15;
    const PERFIL_16_ENCARGADO_REGIONAL = 16;
    const PERFIL_17_APOYO_ENCARGADO_COMUNAL = 17;
    const PERFIL_18_NIVEL_CENTRAL = 18;
    const PERFIL_19_ENCUESTADOR = 19;
    const PERFIL_20_ADMINISTRADOR_BENEFICIOS = 20;
    const PERFIL_21_UGIT = 21;                          // _UNIDAD_GESTION_INFORMACION_TERRITORIAL //  UNIDAD DE GESTIÓN DE INFORMACIÓN TERRITORIAL (Nivel central)
    const PERFIL_22_NC = 22;                            // NIVEL CENTRAL
    const PERFIL_23_CONSULTA_NACIONAL_SUPERVISION = 23;
    const PERFIL_24_GOBERNACIONES = 24;
    const PERFIL_25_CHILE_ATIENDE_EJECUTOR = 25;
    const PERFIL_26_SUPERVISION = 26;
    const PERFIL_27_ADMINISTRADOR_DE_SUPERVISION = 27;
    const PERFIL_28_APOYO_NIVEL_CENTRAL = 28;
    const PERFIL_29_FUNFA = 29;                         // FUNDACION DE LA FAMILIA
    const PERFIL_30_NIVEL_CENTRAL_ARC = 30;

    /**
     * SUPERVISION
     */
    const SUPERVISION_TIPO_HOGAR = 1;
    const SUPERVISION_TIPO_INTEGRANTE = 2;

    const SUPERVISION_CATEGORIA_LOCALIZACION_TERRITORIAL = 1;
    const SUPERVISION_CATEGORIA_COMPOSICION_HOGAR = 2;
    const SUPERVISION_CATEGORIA_EDUCACION = 3;
    const SUPERVISION_CATEGORIA_SALUD = 4;
    const SUPERVISION_CATEGORIA_OCUPACION = 5;
    const SUPERVISION_CATEGORIA_INGRESOS = 6;
    const SUPERVISION_CATEGORIA_OTRO = 7;

    /**
     * PERFILES NIVEL CENTRAL
     */
    const COORDINADOR_DF = 100;  // División Focalización
    const REVISOR_DF = 101;      // División Focalización
    const COORDINADOR_DIS = 102; // Dirección de Información Social
    const REVISOR_DIS = 103;     // Dirección de Información Social
    const COORDINADOR_DPS = 104; // División Política Social
    const REVISOR_DPS = 105;     // División Política Social

}
