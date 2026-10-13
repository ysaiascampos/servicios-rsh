<?php


namespace App\Constantes;


class ConstanteAdjuntos extends ConstanteCampos
{
    const ADJUNTOS_MAX_SIZE_KB = 6144;
    const ADJUNTOS_MIME = 'doc,docx,xls,xlsx,ppt,pptx,pdf,zip,rar,jpg,png,bmp,jpeg,gif';
    const ADJUNTOS_DIR_ANTECEDENTES = 'antecedentes';
    const ADJUNTOS_INTENTOS_MAXIMO = 3;
    const ADJUNTOS_DESCARGAR_SOLO_PRIMER_ARCHIVO_POR_TIPO = false;

    public static $tipoSolicitudesConVisita = [
        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
        Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
    ];
    public static $tiposSolcitudesConDocumentosCompartidos = [
        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE
    ];
    public static $categoriasDocumentosCompartidos = [
        Constante::CLASIFICACION_DOCUMENTO_1_FORMULARIO_SOLICITUD,
        Constante::CLASIFICACION_DOCUMENTO_2_CEDULA_IDENTIDAD,
        Constante::CLASIFICACION_DOCUMENTO_5_MENORES_EDAD
    ];
}
