<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class IngresoRegistroMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-vigente' => new GrupoFamiliarMapper($tramite->grupoFamiliarVigente, $tramite->beneficiariosVigente, $tramite->preguntasYRespuestas),
            'datos-solicitud' => new GrupoFamiliarMapper($tramite->grupoFamiliarSolicitudActual,
                $tramite->beneficiariosSolicitudActual,$tramite->preguntasYRespuestas),
        ]);
        return $this;
    }
}
