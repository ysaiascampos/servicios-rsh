<?php

namespace App\Clases;

use App\Constantes\Constante;

class ValidadorSalud
{
    use ValidacionTrait;

    public function validarRespuestasSalud($integrante, $requerido = true): bool
    {
        $titular = $integrante->runTitular;

        if ($integrante->get('s1') == Constante::RESPUESTA_S1_1_SI) {
            if (($integrante->get('s1a') != Constante::RESPUESTA_S1A_1_CON_CEGUERA_O_DIFICULTAD_VISUAL_AUN_USANDO_LENTES &&
                $integrante->get('s1b') != Constante::RESPUESTA_S1B_1_CON_SORDERA_O_DIFICULTAD_AUDITIVA_AUN_USANDO_AUDIFONOS &&
                $integrante->get('s1c') != Constante::RESPUESTA_S1C_1_CON_MUDEZ_O_DIFICULTAD_EN_EL_HABLA &&
                $integrante->get('s1d') != Constante::RESPUESTA_S1D_1_CON_DIFICULTAD_FISICA &&
                $integrante->get('s1e') != Constante::RESPUESTA_S1E_1_CON_PROBLEMAS_MENTALES &&
                $integrante->get('s1f') != Constante::RESPUESTA_S1F_1_CON_PROBLEMAS_PSIQUIATRICOS) &&
                $requerido
            ) {
                $this->collectError('Debe tener al menos una condición de salud (s1a, s1b, s1c, s1d, s1e, s1f)',
                    Constante::ERR_NEGOCIO_S1X_DEBE_TENER_CONDICION_SALUD,
                    's1a',
                    $titular);
            }
        }

        if (($integrante->get('s2a') != null ||
                $integrante->get('s2c') != null ||
                $integrante->get('s2d') != null ||
                $integrante->get('s2e') != null) &&
            $integrante->get('edad') < Constante::EDAD_6_PUEDE_SALIR_BANAR_MOVERSE_SIN_AYUDA
        ) {
            $this->collectError('El integrante debe ser mayor a 6 años para contestar s2a, s2c, s2d, s2e',
                Constante::ERR_NEGOCIO_S2ACDE_DEBE_SER_MAYOR_6,
                's2a',
                $titular);
        }

        if ($integrante->get('s2b') != null &&
            $integrante->get('edad') < Constante::EDAD_15_PUEDE_COMPRAR_O_IR_MEDICO_SIN_AYUDA
        ) {
            $this->collectError('El integrante debe tener al menos de 15 años para contestar s2b',
                Constante::ERR_NEGOCIO_S2B_DEBE_TENER_AL_MENOS_15,
                's2b',
                $titular);
        }

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
}
