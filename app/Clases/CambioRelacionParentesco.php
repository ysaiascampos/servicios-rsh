<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;

class CambioRelacionParentesco extends TramiteActualizacion
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO;
    public $nombreSolicitud = 'Cambio en relación de parentesco';

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
    }
    protected function actualizarRegistrosAprobacion()
    {
    }
}
