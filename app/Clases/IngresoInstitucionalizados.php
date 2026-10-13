<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\InstitucionEver;

class IngresoInstitucionalizados extends TramiteMultiplesHijos
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS;
    public $nombreSolicitud = 'de Personas Institucionalizadas';
    public $tituloFormulario = "Solicitud de Ingreso al Registro Social de Hogares";

    public $firmaJefeHogar = false;
    public $otrasFirmas = ['Firma Director del Centro'];

    public $institucion;

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

    protected function cargarDataTramite()
    {
        $solTieneMontoMensual = FichaIngresoMensual::check(
            $this->solicitud->FECHA_CREACION,
            $this->solicitud->FECHA_CREACION_CIUDADANO
        );

        /*$tramitable = (in_array($this->solicitud->ID_ESTADO, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)) ? false : true;

        $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdPadreSolicitudIncorporacionNuevoIntegrante(
            $this->solicitud->ID_PADRE_SOLICITUD,
            $this->solicitud->ID_TIPO_SOLICITUD,
            $tramitable,
            $solTieneMontoMensual
        );*/

        $this->grupoFamiliarSolicitudActual = GrupoFamiliar::findByIdGrupoFamiliar($this->solicitud->DATO_NUEVO);
        if (!empty($this->grupoFamiliarSolicitudActual)) {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdSolicitud(
                $this->solicitud->ID_SOLICITUD,
                $solTieneMontoMensual
            );
        }

        $this->institucion = InstitucionEver::getByIdSolicitud($this->solicitud->ID_SOLICITUD);

        //$this->cargarDataGrupoFamiliarVigente();
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }

    protected function actualizarRegistrosAprobacion()
    {
        // TODO: Implement actualizarRegistroAsociados() method.
    }

    protected function consultarTitular()
    {
        $this->titular = $this->solicitante;
    }
}
