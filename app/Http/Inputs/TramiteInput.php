<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

/**
 * @property TipoSolicitudInput tipoSolicitud
 * @property int runCiudadano
 * @property int runUsuario
 * @property string accion
 * @property string canal
 * @property int origenRegistro
 * @property string|null observacion
 * @property MetricaInput metricas
 */
class TramiteInput extends ServiceInput
{
    protected function define()
    {
        $this->campos = [
            'run-ciudadano' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'run-usuario' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'origen-registro' => 'integer|in:' .
                implode(',', Constante::$ORIGEN_REGISTRO_VALIDO_PARA_INPUT),
            'canal' => 'string|lowercase|in:' .
                implode(',', Constante::$CANAL_VALIDO_PARA_INPUT),
            'accion' => 'string|lowercase|in:' .
                implode(',', Constante::$ACCION_VALIDA_PARA_INPUT),
            'observacion' => 'string|between:' .
                implode(',', Constante::$COMENTARIO_1_Y_3000_POSIBLES_RESPUESTAS_STRING),
            'tipo-solicitud' => 'required|object:' . TipoSolicitudInput::class,
            'metricas' => 'nullable|object:' . MetricaInput::class
        ];
    }
}
