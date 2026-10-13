<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\GrupoFamiliar;
use App\Models\NNARepresentanteMigrante;
use App\Models\Personas;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Sequence;
use App\Utiles;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ModuloVivienda extends TramiteConVisita
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA;
    public $nombreSolicitud = 'Solicitud de Actualización del Módulo Vivienda';

    public $grupoFamiliarVigente;
    public $grupoFamiliarSolicitudActual;
    public $grupoFamiliarSolicitudAnterior;

    public $firmaJefeHogar = true;

    private $requiereCamposObligatorios;
    private $requiereV1Obligatorio;
    private $requiereVivienda;
    protected $requiereDocumentosAdjuntos;

    public function editarSolicitud()
    {
        $this->validacionGuardadoSolicitud();
        $this->validarCamposRequeridos();
        $this->validarRespuestasVivienda();
        $this->validarAdjuntosRequeridos();

        // Si hay errores detectados anteriormente los retorna
        if (!empty($this->errors)) {
            throw new ExcepcionReglaNegocio($this->errors);
        }

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        $this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->requiereV1Obligatorio = true;
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

    public function pendienteResolver()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereV1Obligatorio = true;
        $this->requiereVivienda = true;
        $this->requiereDocumentosAdjuntos = true;

        $this->editarSolicitud();
    }

    public function devolverGestion()
    {
        $estadoActual = $this->solicitud->ID_ESTADO;
        if (
            $estadoActual == Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL
            && in_array($this->accion, [
                Constante::ACCION_GESTION_VA_ESTADO_3
            ])
        ) {
            $this->requiereObservacion = true;
        }
        $this->requiereCamposObligatorios = true;
        $this->requiereV1Obligatorio = true;
        $this->requiereVivienda = true;
        $this->requiereDocumentosAdjuntos = true;

        $this->editarSolicitud();
    }

    protected function cargarDataTramite()
    {
        /*$aux = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitud->RUN_CIUDADANO);
        $this->grupoFamiliarVigente = GrupoFamiliar::findByIdGrupoFamiliar($aux->GRUPFAMI_ID);*/
        $this->grupoFamiliarVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitud->RUN_CIUDADANO);
        $this->grupoFamiliarSolicitudActual =  GrupoFamiliar::findBySolicitud($this->solicitud);
        $this->grupoFamiliarSolicitudAnterior =  GrupoFamiliar::findByIdGrupoFamiliar($this->solicitud->ID_GRUPO_ANTIGUO);
        if(!is_null($this->grupoFamiliarVigente)){
            $this->parentesco=BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliar($this->grupoFamiliarVigente,false,true);
        }
        if (isset($this->solicitud->MIGRANTE) && $this->solicitud->MIGRANTE == 1) {

            $this->nombreSolicitud = 'Solicitud de Actualización del Módulo de Vivienda de NNA';
        }
    }

    /*  protected function consultarTitular()
    {
        $this->titular = Personas::find($this->solicitud->DATO_ACTUAL);
    }*/

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = [$this->titular];
    }

    public function agendado()
    {
        // TODO: Implement agendado() method.
    }

    protected function guardarSolicitud(array $data)
    {
        $idGrupoFamiliar = null;

        DB::beginTransaction();

        try {
            if (empty($this->solicitud)) {
                $idGrupoFamiliar = Sequence::nextValue('seq_grupofamiliapk');
                $this->obtenerRegistroSolicitud($idGrupoFamiliar, $idGrupoFamiliar, null);
                $this->solicitud->ID_GRUPO_ANTIGUO = $this->grupoFamiliarVigente->GRUPFAMI_ID;
                if ($this->solicitud->save()) {
                    self::fillGrupoFamiliar($idGrupoFamiliar);
                    $this->grupoFamiliarSolicitudActual->save();
                }
            } else {
                $this->obtenerRegistroSolicitud($this->solicitud->DATO_ACTUAL, $this->solicitud->DATO_NUEVO, $this->grupoFamiliarVigente->NUEVO_FOLIO);
                if ($this->solicitud->save()) {
                    self::fillGrupoFamiliar(null);
                    $this->grupoFamiliarSolicitudActual->save();
                }
            }
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            Log::error($ex);
            throw $ex;
        }
    }

    protected function actualizarRegistrosAprobacion()
    {
        // TODO: Implement actualizarRegistroAsociados() method.
    }

    private function validarCamposRequeridos()
    {
        if ($this->requiereCamposObligatorios) {
            if ($this->requiereV1Obligatorio) {
                $inputValidator = $this->input->all();
                $rules['v1'] = 'required|integer|in:' . implode(',', Constante::$RESPUESTA_V1_1_A_11_POSIBLES_RESPUESTAS);
                $mensaje['v1'] = 'Valor de V1 incorrecto, requiere valores entre ' . Utiles::InicioYFin(Constante::$RESPUESTA_V1_1_A_11_POSIBLES_RESPUESTAS);
                $validator = Validator::make($inputValidator, $rules, $mensaje);
                if ($validator->fails()) {
                    $this->collectError($validator->messages(), Constante::ERR_NEGOCIO_V1_INVALIDO);
                }
            }
        }
    }

    private function validarRespuestasVivienda()
    {
        if ($this->requiereVivienda) {
            $validador = new ValidadorVivienda();
            if (!$validador->validarRespuestasVivienda($this->input)) {
                $this->addErrorWrapper($validador->errors);
            }
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

    private function fillGrupoFamiliar($idGrupoFamiliar)
    {
        $zonaHorariaChileContinental = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime("now", $zonaHorariaChileContinental);
        $fechaValidacionDir = null;
        $fechaValidacionAct = null;
        if ($this->canal == Constante::CANAL_1_STRING_MINUS_MUNICIPAL) {
            $fechaValidacionDir = $fechaActual;
            $fechaValidacionAct = $fechaActual;
        }
        if (empty($this->grupoFamiliarSolicitudActual)) {
            $this->grupoFamiliarSolicitudActual = $this->grupoFamiliarVigente->replicate();
            unset($this->grupoFamiliarSolicitudActual->RN);
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_ID = $idGrupoFamiliar;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_ESTADO = Constante::ESTADO_GRUPO_FAMILIAR_1_SOLICITADO;
        }

        $this->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA = $this->input->get('v1');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SITUACIONSITIO = $this->input->get('v2');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SITUACIONVIVIENDA = $this->input->get('v3');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FUENTEAGUA = $this->input->get('v4');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_DISTRIBUCIONAGUA = $this->input->get('v5');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SISTEMABANO = $this->input->get('v6');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_DORMITORIOSOCUPADOS = $this->input->get('v7');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_PRINCIPALOCUPANTE = $this->input->get('v8');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALMURO = $this->input->get('v9');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALMUROESTADO = $this->input->get('v9-estado');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALPISO = $this->input->get('v10');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALPISOESTADO = $this->input->get('v10-estado');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALTECHO = $this->input->get('v11');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALTECHOESTADO = $this->input->get('v11-estado');

        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTELUZ = $this->input->get('numero-cliente-luz');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTEAGUA = $this->input->get('numero-cliente-agua');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDCOMPLUZ = $this->input->get('compania-luz');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDCOMPAGUA = $this->input->get('compania-agua');

        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_VALIDACION_DIR = $fechaValidacionDir;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_VALIDACION_ACT = $fechaValidacionAct;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_RUT_VALIDACION_ACT = ($this->usuario) ? $this->usuario->RUT_USUARIO : $this->solicitante->PER_RUN;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_MODIFICACION = date("Ymd");
    }

    public function configurarCargaInformacionInicial()
    {
        $aux = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitante->PER_RUN);
        $this->grupoFamiliarVigente = GrupoFamiliar::findByIdGrupoFamiliar($aux->GRUPFAMI_ID);
    }

}
