<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\ComplementoEducacion;
use App\Models\Comuna;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Sequence;
use Illuminate\Support\Facades\DB;

class ComplementoEstablecimientoEducacional extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL;
    public $nombreSolicitud = 'Arancel de Establecimiento Educacional';
    public $tituloFormulario = "Solicitud de Complemento por Pago de";
    public $requiereCamposObligatorios;

    public $compEducacion;

    public function editarSolicitud()
    {
        //$this->validacionGuardadoSolicitud();
        $this->validarCamposRequeridos();

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        //$this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->requiereCamposObligatorios = false;
        $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        $this->requiereCamposObligatorios = true;
        $this->editarSolicitud();
    }

    public function pendienteResolver()
    {
        $vigenteActual = $this->solicitud->VIGENTE;
        $adjuntarDocumentoService = new Adjuntos();
        if (!$adjuntarDocumentoService->validarDocumentosRequeridos($this->solicitud)) {
            throw new ExcepcionReglaNegocio('No se han adjuntado todos los documentos requeridos.',Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS);
        }
        $this->editarSolicitud();

        DB::beginTransaction();
        try {
            if (!self::esMunicipal() && !self::esChileAtiende()) {
                // ToDo revisar vigente para no municipal
                $this->solicitud->VIGENTE = Constante::$vigenteSegunAccionNoMunicipal[$this->accion];
            }
            if (self::ingresaAMunicipal()) {
                $this->solicitud->FECHA_CREACION = $this->fechaActual;

                if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE) {
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->titular->PER_RUN, $this->tipoSolicitud);
                    $usuarioEncargado = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);
                    $this->solicitud->ID_USUARIO_ASIGNADO = $usuarioEncargado;
                    $this->solicitud->FECHA_ASIGNACION = $this->fechaActual;
                }
            }
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
            $this->solicitud->update();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function devolverGestion()
    {
        DB::beginTransaction();
        try {
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_USUARIO_MODIFICACION = $this->usuario->RUT_USUARIO;
            //falta validar
            $this->solicitud->OBS_SOLICITUD = $this->observacion;
            $this->solicitud->save();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    protected function cargarDataTramite()
    {
        $this->compEducacion = ComplementoEducacion::where('run_dato', $this->solicitud->RUN_INTEGRANTE)
            ->where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if ( !($this->compEducacion) ) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de complemento Educación.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }

    }
    protected function consultarTitular()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $runTitular = $this->solicitud->RUN_INTEGRANTE;
        }else{
            $runTitular = $this->solicitud->RUN_CIUDADANO;
        }
        $this->titular = Personas::findByRun($runTitular);
    }

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = [$this->titular];
    }

    protected function guardarSolicitud(array $data)
    {
        DB::beginTransaction();
        try {
                if (is_null($this->solicitud)) {
                    $grupoFamiliar = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitante->PER_RUN, false);
                    if ($grupoFamiliar->count() == 0) {
                        throw new ExcepcionReglaNegocio('Error administrativo, no se encontra grupo familiar vigente y activo.',Constante::ERR_NEGOCIO_ERROR_NO_SE_ENCONTRA_GRUPO_FAMILIAR_VIGENTE_Y_ACTIVO);
                    }

                    $dato_nuevo = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                    $dato_actual = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                    $gf_nuevo_folio = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;

                    $this->obtenerRegistroSolicitud($dato_nuevo, $dato_actual, $gf_nuevo_folio);

                    $this->crearDataTramite();

                    //cambio de la solucitud segun caso correspondiente
                    $this->solicitud->ID_SUB_TIPO_SOLICITUD = $this->input->get('subtipo'); // para solicituar adjuntar los documentos
                    $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
                    $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;

                } else {

                    $this->actualizaDataTramite($data);
                    //$this->solicitud->ID_USUARIO_MODIFICACION = $this->usuario->RUN;
                    $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;

                }

                $this->solicitud->save();

            DB::commit();
            }
            catch (Throwable $ex) {
                DB::rollback();
                throw $ex;
            }
    }

    protected function crearDataTramite()
    {
        $this->compEducacion = new ComplementoEducacion();

        if ($this->solicitud->ID_SOLICITUD != 0 && $this->solicitud->ID_SOLICITUD != null) {
            try {

                $this->compEducacion->COMPLEMENTO_ID = Sequence::nextValue('SEQ_AB_COMPLEMENTO_EDUCACION');
                $this->compEducacion->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                $this->compEducacion->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                $this->compEducacion->CALCULO_ACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                $this->compEducacion->ID_SOLICITUD = $this->solicitud->ID_SOLICITUD;
                $this->compEducacion->FECHA_CREACION = $this->fechaActual;
                $this->compEducacion->C_TCOMPLEMENTO_EDUCACION = $this->input->get('subtipo');
                $this->compEducacion->MONTO = $this->input->get('monto-hogar');
                $this->compEducacion->MONTO_BECA = $this->input->get('monto-beca');
                $this->compEducacion->MONTO_EXTERNO = $this->input->get('monto-externo');

                $titular = Personas::where('per_run', $this->input->get('run-titular'))->first();
                $this->titular = $titular;

                $this->compEducacion->DATO_COMPLEMENTO = $titular->PER_NOMBRES. ' ' .$titular->PER_APELLIDOPATERNO. ' ' .$titular->PER_APELLIDOMATERNO;
                $this->compEducacion->RUN_DATO = $titular->PER_RUN;
                $this->compEducacion->DV_DATO = $titular->PER_DV;

                $this->compEducacion->save();

            } catch (Exception $ex) {
                Log::error('Complemento Educación: ' . $ex);
                throw new ExcepcionConsistencia('Error al grabar Complemento Educación');
            }
        } else {
            throw new ExcepcionConsistencia('Error al crear solicitud de complemento de educacion');
        }
    }

    protected function actualizaDataTramite($data)
    {
        if (($this->compEducacion->count())) {
            $this->compEducacion->C_TCOMPLEMENTO_EDUCACION = $this->input->get('subtipo');
            $this->compEducacion->MONTO = $this->input->get('monto-hogar');

            switch ($this->input->get('subtipo')) {
                case Constante::SUB_TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL_1:
                    $this->compEducacion->MONTO_BECA = $this->input->get('monto-beca');
                    $this->compEducacion->MONTO_EXTERNO = $this->input->get('monto-externo');
                    break;
                case Constante::SUB_TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL_2:
                    $this->compEducacion->MONTO_BECA = $this->input->get('monto-beca');
                    break;
                case Constante::SUB_TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL_3:
                    $this->compEducacion->MONTO_EXTERNO = $this->input->get('monto-externo');
                    break;
            }
            $this->compEducacion->save();
        }
    }

    protected function actualizarRegistrosAprobacion()
    {
        $sqlUpdate2 = ComplementoEducacion::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['VIGENTE' => Constante::VIGENTE_1_VIGENTE]);
    }

    private function validarCamposRequeridos()
    {
        if ($this->requiereCamposObligatorios) {
            $this->input->validate(null, [
                'subtipo' => 'required',
                'run-titular' => 'required',
                'monto-hogar' => 'required',
            ]);
        }
        return true;
    }

    public function agendado()
    {
        return null;
    }
}
