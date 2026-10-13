<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;

class AdjuntosMapper extends ResponseMapper
{

    public static function new()
    {
        return new self;
    }

    public function requeridos($documentos)
    {
        $this->campos = [
            'obligatorios' => [],
            'opcionales' => []
        ];
        if ($documentos) {
            foreach ($documentos['obligatorios'] as $documento) {
                $this->campos['obligatorios'][] = (new DocumentoMapper)->tipoDocumento($documento);
            }
            foreach ($documentos['opcionales'] as $documento) {
                $this->campos['opcionales'][] = (new DocumentoMapper)->tipoDocumento($documento);
            }
        }
        return $this;
    }

    public function show($adjuntos)
    {
        $this->campos = [];
        if ($adjuntos && count($adjuntos) <> 0) {
            foreach ($adjuntos as $key => $documento) {
                foreach ($documento as $valor) {
                    $this->campos[$key][] = (new DocumentoMapper)->detalleDocumento($valor);
                }
            }
        }
        return $this;
    }

    public function store($adjunto)
    {
        $this->campos = [
            'mensaje' => 'El documento fue adjuntado exitosamente',
            'nombre-archivo' => $adjunto->DOC_NOMBREORIGINAL,
            'id' => $adjunto->DOC_ID,
        ];
        return $this;
    }

    public function delete()
    {
        $this->campos = [
            'mensaje' => 'El documento fue eliminado exitosamente de la solicitud.',
            'confirmacion' => Constante::SOLICITUD_ADJUNTOS_ELIMINADO_1_DOCUMENTO_ELIMINADO
        ];
        return $this;
    }

    public function listaRequeridosExtras($adjuntos)
    {
        $this->campos = [];
        if ($adjuntos && count($adjuntos) <> 0) {
            foreach ($adjuntos as $key => $documento) {
                $documentoExtra['id-documento'] = $documento->ID_DOCUMENTO;
                $documentoExtra['observaciones'] = $documento->OBSERVACIONES;
                $this->campos[] = $documentoExtra;
            }
        }
        return $this;
    }
}
