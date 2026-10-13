<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class DomicilioViviendaMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-anteriores-solicitud' => new GrupoFamiliarMapper($tramite->grupoFamiliarAnterior, null),
            'datos-vigente' => new GrupoFamiliarMapper($tramite->grupoFamiliarVigente, null),
            'datos-solicitud' => new GrupoFamiliarMapper($tramite->grupoFamiliarSolicitudActual, null),
        ]);
        return $this;
    }
}
