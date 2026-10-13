<?php

namespace App\Http\ResponseMappers;

use Illuminate\Support\Facades\Response;

class DescargaArchivo
{
    public static function desdeRuta($rutaArchivo, $nombreArchivo, $borrarDespuesDeEnviar = false)
    {
        $cabeceras = [
            'Content-Disposition' => 'attachment; filename=' . $nombreArchivo,
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
        return Response::download($rutaArchivo, $nombreArchivo, $cabeceras)
            ->deleteFileAfterSend($borrarDespuesDeEnviar);
    }
}
