<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ActualizacionModuloESOMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new IntegranteGrupoFamiliarMapper($tramite->fichaSolicitudActual, $tramite->preguntasYRespuestas),
            'datos-vigente' => new IntegranteGrupoFamiliarMapper($tramite->fichaVigente, $tramite->preguntasYRespuestas)
        ]);
        return $this;
    }
}
