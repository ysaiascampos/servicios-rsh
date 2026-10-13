<?php

namespace App\Http\ResponseMappers;


class DocumentoMapper extends ResponseMapper
{
    public function detalleDocumento($documento)
    {
        $this->campos = [
            "id" => self::entero($documento->DOC_ID),
            "fecha-carga" => $documento->DOC_FECHACARGA,
            "archivo-carga" => $documento->DOC_NOMBREORIGINAL,
            "firma-simple" => $documento->firma_simple,
            "tipo-documento" => $documento->tipo_documento,
            "usuario-carga" => $documento->usuario_carga ? (new PersonaCortaMapper($documento->usuario_carga)) : null,
            "propietario-dato" => $documento->propietario_dato ? (new PersonaCortaMapper($documento->propietario_dato)) : null
        ];
        return $this;
    }

    public function tipoDocumento($documento)
    {
        if ($documento) {
            $this->campos = [
                "id-documento" => self::entero($documento->ID_DOCUMENTO),
                "id-tipo-solicitud" => self::entero($documento->ID_TIPO_SOLICITUD),
                "id-sub-tipo-solicitud" => self::entero($documento->ID_SUB_TIPO_SOLICITUD),
                "nombre" => $documento->NOMBRE,
                "nombre-ciudadano" => $documento->NOMBRE_CIUDADANO,
                "obligatorio" => self::booleano($documento->OBLIGATORIO),
                "orden" => self::entero($documento->ORDEN),
                "estado" => self::entero($documento->ESTADO),
                "perfil-ciudadano" => self::entero($documento->PERFIL_CIUDADANO),
                "clasificacion" => self::categoriaMapper($documento)
            ];
        }
        return $this;
    }

    private function categoriaMapper($documento)
    {
        $map = [
            "id" => self::entero($documento->CLASIFICACION_DOCUMENTO),
            "descripcion" => $documento->DESCRIPCION_CLASIFICACION
        ];
        return $map;
    }

}
