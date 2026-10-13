<?php

namespace App\Http\ResponseMappers;

class ValidacionSolicitudTerceroMapper extends ResponseMapper
{
    private $tipoValidacionTerceros;

    public function __construct($validaSolicitudTercero)
    {
        $lista = [];

        if (array_key_exists('existe-menor', $validaSolicitudTercero)) {
            foreach ($validaSolicitudTercero['solicitudes-integrantes'] as $idSolicitud => $dataSolicitud) {
                $lista[$idSolicitud] = self::mapValidaSolicitudesTerceros($dataSolicitud);
            }

            $validaSolicitudTercero['solicitudes-integrantes'] = $lista;

            $this->campos = $validaSolicitudTercero;
        } else {
            foreach ($validaSolicitudTercero as $idSolicitud => $dataSolicitud) {
                $lista[$idSolicitud] = self::mapValidaSolicitudesTerceros($dataSolicitud);
            }
            $this->campos = [
                'tipo-validacion-tercero' => self::booleano($this->tipoValidacionTerceros),
                'solicitudes-integrantes' => $lista,
            ];
        }
    }

    public function mapValidaSolicitudesTerceros($dataSolicitud)
    {
        $lista = [];
        foreach ($dataSolicitud as $validaSolicitudTercero) {
            $this->tipoValidacionTerceros = $validaSolicitudTercero->TIPO_VALIDACION_TERCEROS;
            $lista[$validaSolicitudTercero->PER_RUN] = self::mapValidaSolicitudTercero($validaSolicitudTercero);
        }
        return $lista;
    }

    public function mapValidaSolicitudTercero($validaSolicitudTercero)
    {
        return [
            'id-solicitud' => self::entero($validaSolicitudTercero->IDSOLICITUD),
            'run' => self::entero($validaSolicitudTercero->PER_RUN),
            'email' => $validaSolicitudTercero->EMAIL,
        ];
    }
}
