<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteEnv extends ConstanteError
{
    /**
     * Variables por defecto para las variables del .env
     * La idea es que el .env funcione como los archivos config.ini de los software, si una variable no está allí el software no se cae y lee estas variables por defecto
     * se ocupa así:
     * $runificar = (isset($_ENV['CONSUMIR_WS_RUNIFICADOR'])) ? $_ENV['CONSUMIR_WS_RUNIFICADOR'] : Constante::ENV_DEFECTO_CONSUMIR_WS_RUNIFICADOR;
     */
    const ENV_DEFECTO_CONSUMIR_WS_RUNIFICADOR = true;

    const ENV_DEFECTO_CONSUMIR_REST_RUNIFICADOR = false;
    const ENV_DEFECTO_TIMEOUT_CONSUMO_ENVIAR_MAIL = 15; // segundos para intentar enviar el mail y marcarlo
    const ENV_DEFECTO_TIMEOUT_WS_CERTIFICADO_NACIMIENTO = 15;

    const ENV_DEFECTO_PERMITE_INGRESO_POR_CLAVE_UNICA = true; // permite por defecto el ingreso por ClaveÚnica

    const ENV_DEFECTO_LOGIN_RUN_TOKEN_FALSO = '000000a1824e3492298ccfbc7c933333e4';

    const WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE = -1;
    const WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE = -99;

    /**
     * Cantidad de solicitudes en la bandeja del listado de solicitudes
     */
    const ENV_DEFECTO_CANTIDAD_LISTADO_SOLICITUDES = 5000;

}
