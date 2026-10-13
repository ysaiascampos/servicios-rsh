<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;
use DateTime;

class ValidaTerceroMapper extends ResponseMapper
{
    public function __construct($solicitud)
    {
        $mensajeEstado = Constante::MENSAJE_PENDIENTE_FIRMA_DIGITAL;
        $mensajeEstadoValidacion = Constante::MENSAJE_PENDIENTE_FIRMA_DIGITAL;
        $fechaValidacion = (strlen($solicitud->FECHA_VALIDACION) == 8) ?
            DateTime::createFromFormat('Ymd', $solicitud->FECHA_VALIDACION)->format('Y-m-d') :
            $solicitud->FECHA_VALIDACION;

        switch ($solicitud->ESTADO_VALIDACION) {
            case  Constante::VALIDACION_TERCERO_ESTADO_0_PENDIENTE:
                $mensajeEstadoValidacion = Constante::MENSAJE_PENDIENTE_FIRMA_DIGITAL;
                break;
            case Constante::VALIDACION_TERCERO_ESTADO_1_APROBADO:
                $mensajeEstadoValidacion = Constante::MENSAJE_APROBADO;
                break;
            case Constante::VALIDACION_TERCERO_ESTADO_2_RECHAZADO:
                $mensajeEstadoValidacion = Constante::MENSAJE_RECHAZADA;
                break;
            case Constante::VALIDACION_TERCERO_ESTADO_3_DESISTIDO:
                $mensajeEstadoValidacion = Constante::MENSAJE_DESISTIDA;
                break;
            default:
                $mensajeEstadoValidacion = Constante::MENSAJE_PENDIENTE_FIRMA_DIGITAL;
                break;
        }

        switch ($solicitud->ID_ESTADO) {
            case Constante::ESTADO_SOLICITUD_5_APROBADO:
                $mensajeEstado = Constante::MENSAJE_APROBADO;
                break;
            case Constante::ESTADO_SOLICITUD_7_RECHAZADA:
                $mensajeEstado = Constante::MENSAJE_RECHAZADA;
                break;
            case Constante::ESTADO_SOLICITUD_9_DESISTIDA:
                $mensajeEstado = Constante::MENSAJE_DESISTIDA;
                break;
            case Constante::ESTADO_SOLICITUD_17_CONFIRMADO:
                $mensajeEstado = Constante::MENSAJE_CONFIRMADO;
                break;
            case Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL:
                $mensajeEstado = Constante::MENSAJE_PENDIENTE_FIRMA_DIGITAL;
                break;
            case Constante::ESTADO_SOLICITUD_11_EN_DIGITACION:
                if ($solicitud->ID_SUB_ESTADO != Constante::ESTADO_SOLICITUD_14_INACTIVO_POR_DEFUNCION) {
                    $mensajeEstado = Constante::MENSAJE_BORRADOR;
                }
                break;
        }

        $this->campos = [
            //DATOS DE TABLA VALIDACIONSOLICITUDTERCERO
            "id-validacion" => self::entero($solicitud->ID),
            "run-ciudadano-asociado" => self::entero($solicitud->PER_RUN),
            "nombre-ciudadano-asociado" => self::entero($solicitud->NOMBRE_ASOCIADO_SOLICITUD),
            "id-estado-validacion" => self::entero($solicitud->ESTADO_VALIDACION),
            "estado-validacion" => $mensajeEstadoValidacion,
            "correo" => $solicitud->EMAIL,
            "fecha-validacion" => $fechaValidacion,
            "aviso-correo-solicitud" => self::entero($solicitud->ENVIO_EMAIL_AVISO_SOLICITUD),
            "aviso-correo-validacion" => self::entero($solicitud->ENVIO_EMAIL_VALIDACION),

            //DATOS TITULAR SOLICITUD
            'run-titular-solicitud' => self::entero($solicitud->RUN_CIUDADANO),
            "nombre-titular-solicitud" => $solicitud->NOMBRE_TITULAR_SOLICITUD,

            //DATOS SOLICITUD
            "id-padre-solicitud" => self::entero($solicitud->ID_PADRE_SOLICITUD),
            "id-solicitud" => self::entero($solicitud->IDSOLICITUD),
            "vigente-solicitud" => self::entero($solicitud->VIGENTE),
            "id-estado-solicitud" => self::entero($solicitud->ID_ESTADO),
            "estado-solicitud" => $mensajeEstado,
            "id-sub-estado-solicitud" => self::entero($solicitud->ID_SUB_ESTADO_SOLICITUD),
            "sub-estado-solicitud" => $solicitud->SUB_ESTADO_SOLICITUD,
            "id-tipo-solicitud" => self::entero($solicitud->ID_TIPO_SOLICITUD),
            "tipo-solicitud" => $solicitud->TIPO_SOLICITUD,
            "id-sub-tipo-solicitud" => self::entero($solicitud->ID_SUB_TIPO_SOLICITUD),
            "sub-tipo-solicitud" => $solicitud->SUB_TIPO_SOLICITUD,
            "codigo-comuna-solicitud" => self::entero($solicitud->CODIGO_COMUNA),
            "comuna-solicitud" => $solicitud->COMUNA_DESCRIPCION,
            "codigo-region-solicitud" => self::entero($solicitud->CODIGO_REGION),
            "region-solicitud" => $solicitud->REGION_DESCRIPCION,
            "fecha-creacion-solicitud" => $solicitud->FECHA_CREACION,
            "fecha-modificacion-solicitud" => $solicitud->FECHA_MODIFICACION,
            "fecha-creacion-ciudadano-solicitud" => $solicitud->FECHA_CREACION_CIUDADANO,
            "comentario-solicitud" => $solicitud->COMENTARIO
        ];
    }
}
