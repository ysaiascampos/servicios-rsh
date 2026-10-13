<?php


namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ActualizacionModuloViviendaMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new ViviendaMapper($tramite->grupoFamiliarSolicitudActual),
            'datos-vigente' => new ViviendaMapper($tramite->grupoFamiliarVigente),
            'datos-anteriores-solicitud' => new ViviendaMapper($tramite->grupoFamiliarSolicitudAnterior)
        ]);
        return $this;
    }
}
