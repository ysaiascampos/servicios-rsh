<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;

class SolicitudMapper extends ResponseMapper
{
    public function __construct($solicitud)
    {
        $mensajeEstado = Constante::MENSAJE_EN_REVISION;
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
            case Constante::ESTADO_SOLICITUD_18_EN_ESPERA_APROBACION_CIUDADANO:
                if ($solicitud->ID_SUB_ESTADO != Constante::ESTADO_SOLICITUD_14_INACTIVO_POR_DEFUNCION) {
                    if ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION){
                        $mensajeEstado = Constante::MENSAJE_PENDIENTE_COMPLETAR;
                    } else {
                        $mensajeEstado = Constante::MENSAJE_BORRADOR;
                    }
                }
                break;
        }

        $this->campos = [
            "id-padre-solicitud" => self::entero($solicitud->ID_PADRE_SOLICITUD),
            "id-solicitud" => self::entero($solicitud->ID_SOLICITUD),
            "id-estado" => self::entero($solicitud->ID_ESTADO),
            "estado" => $mensajeEstado,
            "sub-estado" => self::entero($solicitud->ID_SUB_ESTADO),
            "id-tipo-solicitud" => self::entero($solicitud->ID_TIPO_SOLICITUD),
            "id-sub-tipo-solicitud" => self::entero($solicitud->ID_SUB_TIPO_SOLICITUD),
            "solicitud" => $solicitud->SOLICITUD,
            "aviso-correo" => self::entero($solicitud->AVISO_CORREO),
            "codigo-comuna" => self::entero($solicitud->COM_ID),
            //la comuna se desgloza como region comuna con su respectiva descripcion
            "comuna" => $solicitud->COM_DESCRIPCION,
            "codigo-region" => self::entero($solicitud->REG_ID),
            "region" => $solicitud->REG_DESCRIPCION,
            "fecha-creacion" => $solicitud->FECHA_CREACION,
            "fecha-modificacion" => $solicitud->FECHA_MODIFICACION,
            "canal" => self::entero(self::mapValorCanal($solicitud->CANAL)),
            "nombre-canal" => self::mapNombreCanal($solicitud->CANAL),
            "origen-registro" => self::entero($solicitud->ORIGEN_REGISTRO),
            "nombre-origen-registro" => self::mapNombreOrigenRegistro($solicitud->ORIGEN_REGISTRO),
            "observaciones" => $solicitud->OBSERVACION,
            "dato-actual" => self::entero($solicitud->DATO_ACTUAL),
            "dato-nuevo" => self::entero($solicitud->DATO_NUEVO),
            "identidad-acreditada" => $solicitud->IDENTIDAD_ACREDITADA,
            "vigente" => self::entero($solicitud->VIGENTE),
            "id-usuario-asignado" => self::entero($solicitud->ID_USUARIO_ASIGNADO),
            "fecha-asignacion" => $solicitud->FECHA_ASIGNACION,
            "fecha-creacion-ciudadano" => $solicitud->FECHA_CREACION_CIUDADANO,
            "ciudadano-asociado" => $solicitud->CIUDADANO_ASOCIADO,
            "comentario" => $solicitud->COMENTARIO,
            "numero-documentos" => self::entero(isset($solicitud->numero_documentos) ? $solicitud->numero_documentos : 0),
            "id-fuente-actual" => self::entero($solicitud->ID_FUENTE_ACTUAL),
            "observacion-solicitud" => $solicitud->OBS_SOLICITUD,
            "observacion-solicitud-nivel-central" => $solicitud->OBSERVACION_NIVEL_CENTRAL,
            "run-titular" => self::entero($solicitud->RUN_TITULAR),
            "run-beneficiario" => isset($solicitud->RUN_BENEFICIARIO) ? self::entero($solicitud->RUN_BENEFICIARIO) : null,
            "migrante" => self::entero($solicitud->MIGRANTE)
        ];
    }

    private static function mapValorCanal($canal)
    {
        if (array_key_exists($canal, Constante::$canalSaveSegunCanalMinuscula)) {
            $valorCanal = Constante::$canalSaveSegunCanalMinuscula[$canal];
        } else {
            $valorCanal = $canal;
        }
        return $valorCanal;
    }

    private static function mapNombreCanal($canal)
    {
        if (array_key_exists($canal, Constante::$canalTextoCapitalizado)) {
            $nombreCanal = Constante::$canalTextoCapitalizado[$canal];
        } else {
            $nombreCanal = $canal;
        }
        return $nombreCanal;
    }

    private static function mapNombreOrigenRegistro($origenRegistro)
    {
        if (array_key_exists($origenRegistro, Constante::$ORIGEN_REGISTRO_TEXTO)) {
            $nombreOrigenRegistro = Constante::$ORIGEN_REGISTRO_TEXTO[$origenRegistro];
        } else {
            $nombreOrigenRegistro = $origenRegistro;
        }
        return $nombreOrigenRegistro;
    }
}
