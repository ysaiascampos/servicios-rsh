<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\Calle;
use App\Models\GrupoFamiliar;
use App\Models\NNARepresentanteMigrante;
use App\Models\Personas;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\BeneficiarioGrupoFamiliar;

class DomicilioVivienda extends TramiteConVisita
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA;
    public $nombreSolicitud = 'Solicitud de Cambio de Domicilio y Vivienda';

    public $grupoFamiliarAnterior;
    public $beneficiariosAnterior;
    public $grupoFamiliarVigente;
    public $beneficiariosVigente;
    public $grupoFamiliarSolicitudActual;
    public $beneficiariosSolicitudActual;

    public $firmaJefeHogar = true;

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
        $this->grupoFamiliarSolicitudActual = GrupoFamiliar::findByIdGrupoFamiliar($this->solicitud->DATO_NUEVO);
        $calle = Calle::findByCodigoCalleIdLocalidad(
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_C_CALLE,
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_LOCALIDAD
        );
        if (!is_null($calle)) {
            $this->grupoFamiliarSolicitudActual->CALLE = $calle->NOMBRE;
        } else {
            $this->grupoFamiliarSolicitudActual->CALLE = $this->grupoFamiliarSolicitudActual->GRUPFAMI_N_CALLE;
        }

        //$this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdSolicitud($this->solicitud->ID_SOLICITUD,
        //    $solTieneMontoMensual);

        $this->grupoFamiliarVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano(
            $this->solicitud->RUN_CIUDADANO,
            false
        );

        if ($this->solicitud->ID_ESTADO == Constante::ESTADO_SOLICITUD_5_APROBADO) {
            $this->grupoFamiliarAnterior = GrupoFamiliar::findByIdGrupoFamiliar($this->solicitud->ID_GRUPO_ANTIGUO);
            $calle = Calle::findByCodigoCalleIdLocalidad(
                $this->grupoFamiliarAnterior->GRUPFAMI_C_CALLE,
                $this->grupoFamiliarAnterior->GRUPFAMI_LOCALIDAD
            );
            if (!is_null($calle)) {
                $this->grupoFamiliarAnterior->CALLE = $calle->NOMBRE;
            } else {
                $this->grupoFamiliarAnterior->CALLE = $this->grupoFamiliarAnterior->GRUPFAMI_N_CALLE;
            }
        } else {
            $this->grupoFamiliarAnterior = $this->grupoFamiliarVigente;
        }
        if (isset($this->solicitud->MIGRANTE) && $this->solicitud->MIGRANTE == 1) {

            $this->nombreSolicitud = 'Solicitud de Cambio de Domicilio y Vivienda de NNA';
        }
        $this->grupoFamiliarAnterior->NOMBRE_COMPANIA_LUZ;
        $this->grupoFamiliarAnterior->NOMBRE_COMPANIA_AGUA;
        $this->grupoFamiliarAnterior->GRUPFAMI_NUMCLIENTELUZ;
        $this->grupoFamiliarAnterior->GRUPFAMI_NUMCLIENTEAGUA;
    }

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = $this->firmantes;
    }

    protected function guardarSolicitud(array $data)
    {
    }

    public function actualizarRegistrosAprobacion()
    {
    }

    public function agendado()
    {
        return null;
    }
}
