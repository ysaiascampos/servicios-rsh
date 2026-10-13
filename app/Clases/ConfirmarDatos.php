<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;

class ConfirmarDatos extends Tramite
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS;
    public $nombreSolicitud = 'Confirmar mis datos';

    public $grupoVigente = null;
    public $grupoSolicitud = null;
    public $beneficiariosSolicitud = null;
    public $beneficiariosGrupoVigente = null;

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
        $this->grupoVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitante->PER_RUN, false);

        $this->grupoSolicitud = GrupoFamiliar::findByNuevoFolio($this->solicitud->DATO_ACTUAL);
        if (!empty($this->grupoSolicitud)) {
            $this->beneficiariosSolicitud = BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliar($this->grupoSolicitud);
        }
        if (!empty($this->grupoVigente)) {
            $this->beneficiariosGrupoVigente = BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliar($this->grupoVigente);
        }
    }
    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = $this->beneficiariosSolicitud;
    }

    public function agendado()
    {
        // TODO: Implement agendado() method.
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }
}
