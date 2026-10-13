<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;

class CambioJefeDeHogar extends TramiteActualizacion
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR;
    public $nombreSolicitud = 'Solicitud Cambio de Jefatura de Hogar';

//    public $grupoFamiliarSolicitud = null;
    public $beneficiariosSolicitud = null;
//    public $grupoFamiliarVigente = null;
    public $beneficiariosVigente = null;

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
        $solTieneMontoMensual = FichaIngresoMensual::check($this->solicitud->FECHA_CREACION,
            $this->solicitud->FECHA_CREACION_CIUDADANO);

        $this->beneficiariosSolicitud =
            BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdPadreSolicitudIncorporacionCambioJefe(
                $this->solicitud->ID_PADRE_SOLICITUD, $this->tipoSolicitud, null, $solTieneMontoMensual
            );

        $this->beneficiariosVigente = BeneficiarioGrupoFamiliar::findBeneficiariosByRun(
            $this->solicitante->PER_RUN
        );
    }

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = $this->beneficiariosSolicitud;
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }

    protected function actualizarRegistrosAprobacion()
    {
        // TODO: Implement actualizarRegistroAsociados() method.
    }
}
