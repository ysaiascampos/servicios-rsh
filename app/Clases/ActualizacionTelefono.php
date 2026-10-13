<?php


namespace App\Clases;


use App\Constantes\Constante;

class ActualizacionTelefono extends Tramite
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO;
    protected $nombreSolicitud = 'Actualización de datos de teléfono';

    public function editarSolicitud()
    {
        // TODO: Implement editarSolicitud() method.
    }

    public function enDigitacion()
    {
        // TODO: Implement enDigitacion() method.
    }

    public function pendienteDocumentos()
    {
        // TODO: Implement pendienteDocumentos() method.
    }

    public function pendienteResolver()
    {
        // TODO: Implement pendienteResolver() method.
    }

    public function devolverGestion()
    {
        // TODO: Implement devolverGestion() method.
    }

    public function aprobar()
    {
        // TODO: Implement aprobar() method.
    }

    public function desistir()
    {
        // TODO: Implement desistir() method.
    }

    public function rechazar()
    {
        // TODO: Implement rechazar() method.
    }

    protected function cargarDataTramite()
    {
        // TODO: Implement cargarDataTramite() method.
    }
}
