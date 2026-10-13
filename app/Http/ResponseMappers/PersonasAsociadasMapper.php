<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class PersonasAsociadasMapper extends ResponseMapper
{
    public function __construct(Tramite $tramite)
    {
        $this->campos = [
            'solicitante' => new PersonaCortaMapper($tramite->solicitante),
            'titular' => ($tramite->titular) ? new PersonaCortaMapper($tramite->titular): null,
            'encargado' => ($tramite->encargado) ? new UsuarioMapper($tramite->encargado) : null,
            'firmantes' => $this->mapPersonas($tramite->firmantes),
            'personas' => $this->mapPersonas($tramite->personasAsociadas)
        ];
        return $this;
    }

    public function mapPersonas($personas)
    {
        $map = [];
        if (!empty($personas)) {
            foreach ($personas as $persona) {
                if (!is_numeric($persona)) {
                    $map[] = new PersonaCortaMapper($persona);
                }
            }
        }
        return $map;
    }
}
