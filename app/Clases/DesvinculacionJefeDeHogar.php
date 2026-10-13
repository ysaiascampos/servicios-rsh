<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;

class DesvinculacionJefeDeHogar extends TramiteMultiplesHijos
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR;
    public $nombreSolicitud = 'Solicitud Desvinculación de la Jefatura de Hogar';

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

    public function agendado()
    {
        return null;
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
        if (in_array($this->solicitud->ID_ESTADO, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)) {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosByIdPadreSolicitud($this->solicitud->ID_PADRE_SOLICITUD,
                $this->solicitud->ID_TIPO_SOLICITUD, $this->solicitud->ID_SOLICITUD);
        } else {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdPadreSolicitud($this->solicitud->ID_PADRE_SOLICITUD,
                $this->solicitud->ID_TIPO_SOLICITUD);
        }
        $this->cargarDataGrupoFamiliarVigente();
        $this->obtenerPreguntasYRespuestasESOV($this->solicitud->ORIGEN_REGISTRO);
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
