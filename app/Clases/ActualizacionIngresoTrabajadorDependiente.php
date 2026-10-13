<?php

namespace App\Clases;

use App\Constantes\Constante;

class ActualizacionIngresoTrabajadorDependiente extends ActualizacionIngreso
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE;
    public $nombreSolicitud = 'Solicitud de Actualización de ingreso trabajador dependiente';

    public function validarCamposRequeridos()
    {
        $this->input->validate(null, [
            'run-titular' => 'required',
            'dependiente' => 'required',
        ]);
        return true;
    }
}
