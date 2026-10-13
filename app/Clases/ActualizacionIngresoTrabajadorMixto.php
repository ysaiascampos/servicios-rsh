<?php

namespace App\Clases;

use App\Constantes\Constante;

class ActualizacionIngresoTrabajadorMixto extends ActualizacionIngreso
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE;
    public $nombreSolicitud = 'Solicitud de Actualización ingreso dependiente e independiente';

    public function validarCamposRequeridos()
    {
        $this->input->validate(null, [
            'run-titular' => 'required',
            'dependiente' => 'required',
            'independiente' => 'required',
        ]);
        return true;
    }
}
