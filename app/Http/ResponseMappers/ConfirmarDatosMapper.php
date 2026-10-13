<?php

namespace App\Http\ResponseMappers;

class ConfirmarDatosMapper extends TramiteMapper
{
    public function consulta($tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-vigente' => new GrupoFamiliarMapper($tramite->grupoVigente,$tramite->beneficiariosGrupoVigente, $tramite->preguntasYRespuestas),
            'datos-grupo-familiar' => new GrupoFamiliarMapper($tramite->grupoSolicitud, $tramite->beneficiariosSolicitud, $tramite->preguntasYRespuestas),
        ]);

        return $this;
    }
}
