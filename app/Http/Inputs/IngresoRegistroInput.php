<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class IngresoRegistroInput extends DomicilioViviendaInput
{

    protected function define()
    {
        parent::define();
        $this->encuestadorInput();
        $this->campos = array_merge($this->campos,[
            'borrador' => 'string|in:' . implode(',',[
                Constante::STEP_1_STRING_DATOS_CONTACTO,
                Constante::STEP_1_2_STRING_VIVIENDA,
                Constante::STEP_2_STRING_INTEGRATES_FAMILIA,
                Constante::STEP_3_STRING_JEFE_HOGAR,
                Constante::STEP_4_STRING_PARENTESCO,
                Constante::STEP_5_STRING_PAREJAS,
                Constante::STEP_STRING_CONTINUAR_MAS_TARDE
            ]),
            'run-titular' => 'numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'integrantes' => 'array|object:'.IntegranteGrupoFamiliarInput::class
        ]);
    }

    private function encuestadorInput()
    {
        $this->campos = array_merge($this->campos,
            [
                'run-encuestador' => 'integer|min:1|max:99999999',
                'dv-encuestador' => 'string|min:1|max:1',
                'fecha-encuesta' => 'date_format:"d/m/Y"'
            ]);
    }
}
