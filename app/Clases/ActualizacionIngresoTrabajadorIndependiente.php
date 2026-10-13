<?php
namespace App\Clases;

use App\Constantes\Constante;

class ActualizacionIngresoTrabajadorIndependiente extends ActualizacionIngreso
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE;
    public $nombreSolicitud = 'Solicitud de Actualización de ingreso trabajador independiente';
    public function validarCamposRequeridos()
    {
        $this->input->validate(null, [
            'run-titular' => 'required',
            'independiente' => 'required',
        ]);
        return true;
    }
}
