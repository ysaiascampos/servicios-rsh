<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\TramiteInput;
use App\Models\Ficha;
use App\Models\Personas;
use App\Models\GrupoFamiliar;
use App\Sequence;
use App\Models\NNARepresentanteMigrante;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\BeneficiarioGrupoFamiliar;
use Illuminate\Support\Facades\DB;
use Throwable;

class ModuloEducacion extends TramiteActualizacion
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION;
    public $nombreSolicitud = 'Solicitud de Actualización del Módulo Educación';

    public $fichaVigente;
    public $fichaSolicitudActual;

    private $requiereCamposObligatorios;
    protected $requiereDocumentosAdjuntos;

    public function enDigitacion()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereDocumentosAdjuntos = false;

        $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereDocumentosAdjuntos = false;

        $this->editarSolicitud();
    }

    public function agendado()
    {
        return null;    // no aplica
    }

    public function pendienteResolver()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereDocumentosAdjuntos = true;

        $this->editarSolicitud();
    }

    public function devolverGestion()
    {
        if (empty($this->observacion)) {
            throw ExcepcionValidacion::new('El campo observacion es requerido.')
                ->setOrigen('observacion');
        }
        $this->requiereCamposObligatorios = true;
        $this->requiereDocumentosAdjuntos = true;

        $this->editarSolicitud();
    }

    public function editarSolicitud()
    {
        $this->validacionGuardadoSolicitud();
        $this->validarCamposRequeridos();
        $this->validarRespuestasEducacion();
        $this->validarAdjuntosRequeridos();

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        $this->generarPdfFirmaSimple();
    }

    protected function guardarSolicitud(array $data)
    {
        DB::beginTransaction();
        try {
            $nuevoFolio = $this->obtenerNuevoFolio();
            $idPersona = $this->input->id;

            $this->obtenerFicha();

            $this->fichaSolicitudActual->FICHA_PERSONA = $idPersona;
            $this->fichaSolicitudActual->FICHA_VIGENTE = Constante::VIGENTE_2_PENDIENTE;

            $this->fichaSolicitudActual->FICHA_ASISTEESTABEDUCACIONAL = $this->input->e1;
//            $this->fichaSolicitudActual->FICHA_NOASISTEMOTIVO = $this->input->e2;
            $this->fichaSolicitudActual->FICHA_CURSO = $this->input->e3;
            $this->fichaSolicitudActual->FICHA_TIPOEDUCACION = $this->input->e4;

            $datoActual = $idPersona;
            $datoNuevo = $this->fichaSolicitudActual->FICHA_ID;

            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $this->fichaSolicitudActual->FICHA_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;

            $this->fichaSolicitudActual->save();
            $this->solicitud->save();

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    private function obtenerFicha()
    {
        if (empty($this->fichaSolicitudActual)) {

            $fichaActual = Ficha::findByIdPersona($this->input->id);
            $this->fichaSolicitudActual = $fichaActual->replicate();

            unset($this->fichaSolicitudActual->RN);
            $this->fichaSolicitudActual->FICHA_ID = Sequence::nextValue('SEQ_FICHAPK');
            $this->fichaSolicitudActual->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $this->fichaSolicitudActual->FICHA_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
        }
    }

    protected function cargarDataTramite()
    {
        $this->fichaSolicitudActual = Ficha::findDetallePersonaByIdSolicitud($this->solicitud->ID_SOLICITUD);
        $this->fichaVigente = Ficha::findDetallePersonaByIdPersona($this->fichaSolicitudActual->FICHA_PERSONA);
        if (isset($this->solicitud->MIGRANTE) && $this->solicitud->MIGRANTE == 1) {

            $this->nombreSolicitud = 'Solicitud de Actualización de Módulo Educación NNA';
        }
        $this->obtenerPreguntasYRespuestasESOV($this->solicitud->ORIGEN_REGISTRO);
    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;
        $localInput = $input->tipoSolicitud->{$this->tipoSolicitud};
        if ($this->solicitante->PER_RUN == $localInput->runTitular) {
            $titular = $this->solicitante;
        } else {
            $titular = Personas::findByRun($localInput->runTitular);
        }
        return $titular;
    }

    protected function consultarTitular()
    {
        $this->titular = Personas::find($this->solicitud->DATO_ACTUAL);
    }

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = [$this->titular];
    }

    private function validarCamposRequeridos()
    {
        if ($this->requiereCamposObligatorios) {
            $this->input->validate(null, [
                'e1' => 'required',
//                'e2' => 'required_if:e1,2',
                'e3' => 'required_if:e1,2',
                'e4' => 'required_if:e1,2',
            ]);
        }
        return true;
    }

    private function validarRespuestasEducacion()
    {
        $validador = new ValidadorEducacion();
        if (!$validador->validarRespuestasEducacion($this->input)) {
            $this->addErrorWrapper($validador->errors);
        }
        if (!empty($this->errors)) {
            throw new ExcepcionReglaNegocio($this->errors);
        }
    }

    public function validarAdjuntosRequeridos()
    {
        if ($this->requiereDocumentosAdjuntos) {
            if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
                throw new ExcepcionReglaNegocio(
                    'No se han adjuntado todos los documentos requeridos.',
                    Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS
                );
            }
        }
    }

    protected function actualizarRegistrosAprobacion()
    {
        $this->fichaSolicitudActual->FICHA_VIGENTE = Constante::VIGENTE_1_VIGENTE;
        $this->fichaSolicitudActual->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
        $this->fichaSolicitudActual->FICHA_DIFICVISUAL = $this->fichaVigente->FICHA_DIFICVISUAL;
        $this->fichaSolicitudActual->FICHA_DIFICAUDITIVA = $this->fichaVigente->FICHA_DIFICAUDITIVA;
        $this->fichaSolicitudActual->FICHA_DIFICHABLA = $this->fichaVigente->FICHA_DIFICHABLA;
        $this->fichaSolicitudActual->FICHA_DIFICFISICA = $this->fichaVigente->FICHA_DIFICFISICA;
        $this->fichaSolicitudActual->FICHA_DIFICMENTAL = $this->fichaVigente->FICHA_DIFICMENTAL;
        $this->fichaSolicitudActual->FICHA_DIFICPSIQUICA = $this->fichaVigente->FICHA_DIFICPSIQUICA;

        $this->fichaSolicitudActual->FICHA_CALLESINAYUDA = $this->fichaVigente->FICHA_CALLESINAYUDA;
        $this->fichaSolicitudActual->FICHA_MEDICOSINAYUDA = $this->fichaVigente->FICHA_MEDICOSINAYUDA;
        $this->fichaSolicitudActual->FICHA_ASEOSINAYUDA = $this->fichaVigente->FICHA_ASEOSINAYUDA;
        $this->fichaSolicitudActual->FICHA_DESPLAZAMIENTOENCASA = $this->fichaVigente->FICHA_DESPLAZAMIENTOENCASA;
        $this->fichaSolicitudActual->FICHA_CTRLESFINTERES = $this->fichaVigente->FICHA_CTRLESFINTERES;

        $this->fichaSolicitudActual->FICHA_CONDICIONDISCAPACIDAD = $this->fichaVigente->FICHA_CONDICIONDISCAPACIDAD;

        $this->fichaSolicitudActual->FICHA_TRABAJA = $this->fichaVigente->FICHA_TRABAJA;
        $this->fichaSolicitudActual->FICHA_OCUPACIONACTUAL = $this->fichaVigente->FICHA_OCUPACIONACTUAL;
        $this->fichaSolicitudActual->FICHA_CODIGOSRAMA = $this->fichaVigente->FICHA_CODIGOSRAMA;
        $this->fichaSolicitudActual->FICHA_TEMPORALIDADPERMANENCIA = $this->fichaVigente->FICHA_TEMPORALIDADPERMANENCIA;
        $this->fichaSolicitudActual->FICHA_CODIGOCONTRATO = $this->fichaVigente->FICHA_CODIGOCONTRATO;
        $this->fichaSolicitudActual->FICHA_RELACIONCONTRACTUAL = $this->fichaVigente->FICHA_RELACIONCONTRACTUAL;
        $this->fichaSolicitudActual->FICHA_HORASTRABAJO = $this->fichaVigente->FICHA_HORASTRABAJO;
        $this->fichaSolicitudActual->FICHA_BUSCOTRABAJO = $this->fichaVigente->FICHA_BUSCOTRABAJO;
        $this->fichaSolicitudActual->FICHA_NOBUSCOTRABAJOMOTIVO = $this->fichaVigente->FICHA_NOBUSCOTRABAJOMOTIVO;
        $this->fichaSolicitudActual->FICHA_INGRESOANUAL = $this->fichaVigente->FICHA_INGRESOANUAL;
        $this->fichaSolicitudActual->FICHA_INGRESOOTROS = $this->fichaVigente->FICHA_INGRESOOTROS;
        $this->fichaSolicitudActual->FICHA_INGRESOJUBILACION = $this->fichaVigente->ficha_ingresojubilacion;

        $this->fichaSolicitudActual->save();

        $this->fichaVigente->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
        $this->fichaVigente->save();
    }
}
