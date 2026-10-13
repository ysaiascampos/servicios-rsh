<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteUrl extends ConstanteVigenteCalculoactivo
{
    const URL_MINISTERIAL_PROD = 'http://www.desarrollosocialyfamilia.gob.cl';

    /**
     * URLs de cada ambiente
     */
    const URL_PROD = 'rshmunicipal.ministeriodesarrollosocial.gob.cl';
    const URL_QA = 'rsh.mds.cl';
    const URL_CAP = 'rshmunicipalcap.ministeriodesarrollosocial.gob.cl';
    const URL_DESA = 'rshmunicipal.mideplan.cl';
    const URL_DESA2 = 'localhost';
    const URL_DESA3 = '192.168.31.27';
    const URL_DESA4 = '127.0.0.1';

    // URL BANDEJA
    // Producción http://rshmunicipal.ministeriodesarrollosocial.gob.cl/bandeja
    // QA https://rshmunicipal.mds.cl/bandeja
    // Desarrollo http://rshmunicipal.mideplan.cl/bandeja
    const BANDEJA_URL_PROD = 'rshmunicipal.ministeriodesarrollosocial.gob.cl';
    const BANDEJA_URL_QA = 'rshmunicipal.mds.cl';
    const BANDEJA_URL_DESA = 'rshmunicipal.mideplan.cl';
    const BANDEJA_URL_DESA2 = 'localhost';
    const BANDEJA_URL_DESA3 = '192.168.31.27';

    // URL REGISTRO CALLE
    const CALLE_URL_PROD = 'https://registrocalle.ministeriodesarrollosocial.gob.cl/';
    const CALLE_URL_QA = 'http://registrocalle.mds.cl/';
    const CALLE_URL_DESA = 'http://registrocalle.mideplan.cl/';

    // URL GUIA DE ATENCION CIUDADANA
    const GUIA_APOYO_URL_PROD = 'http://guiarsh.ministeriodesarrollosocial.gob.cl/guia';
    const GUIA_APOYO_URL_QA = 'http://guiarsh.mds.cl/guia';
    const GUIA_APOYO_URL_DESA = 'http://guiarsh.mideplan.cl/guia';

    // URL MESA DE AYUDA
    const MESA_AYUDA_URL_PROD1 = 'http://mesadesoportersh.ministeriodesarrollosocial.gob.cl/USDKV8RSH/?u=';
    const MESA_AYUDA_URL_PROD2 = '&token';
    const MESA_AYUDA_URL_PROD_OLD = '/mesadeayuda/index.php';

    const RIS_URL_PROD = 'http://ris.ministeriodesarrollosocial.gob.cl/rest?ClaveSistema=';
    const RIS_URL_QA = 'http://risqa.mds.cl/rest?ClaveSistema=';
    const RIS_URL_DESA = 'http://ris.mideplan.cl/rest?ClaveSistema=';

    /**
     * WS de SBIF UF
     */
    const UF_URL_1 = 'https://api.sbif.cl/api-sbifv3/recursos_api/uf/';
    const UF_URL_2 = '/dias/';
    const UF_VALOR_AL_NO_CONSUMIR = '0';

}
