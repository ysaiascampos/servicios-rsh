<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\Comuna;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Models\ComplementoCotizaSalud;
use App\Utiles;
use Illuminate\Support\Facades\DB;

class ComplementoCotizacionSalud extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD;
    public $nombreSolicitud = "de Salud por Preexistencias";
    public $tituloFormulario = "Solicitud de Complemento por Cotización";
    public $complementoCotizaSalud = null;
    public $pagador = null;
    public $receptor = null;
    public $beneficiario = null;
    public $integrante = null;

    public function editarSolicitud()
    {
        $this->validacionGuardadoSolicitud();
        $this->validarCamposRequeridos();
        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        $this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->requiereCamposObligatorios = true;
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
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->runTitular, $this->tipoSolicitud);
                    $usuarioEncargado = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);
                    $this->solicitud->ID_USUARIO_ASIGNADO = $usuarioEncargado;
                    $this->solicitud->FECHA_ASIGNACION = $this->fechaActual;
                }
            }
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->id_estado][$this->accion][self::ESTADO];
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
        $this->complementoCotizaSalud = ComplementoCotizaSalud::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
                ->where('c_tcomplemento_cotizasalud', $this->solicitud->ID_SUB_TIPO_SOLICITUD)
                ->first();
        if ( !($this->complementoCotizaSalud) ) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de complemento Cotización Salud.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }
        $this->beneficiario = Utiles::getPersona($this->complementoCotizaSalud->RUT_CAUSANTE);
        $this->receptor = Utiles::getPersona($this->complementoCotizaSalud->RUT_RECEPTOR);
        $this->pagador = Utiles::getPersona($this->complementoCotizaSalud->RUT_PAGADOR);

        if ($this->solicitud->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_1_PAGO) {
            $this->integrante = Utiles::getPersona($this->complementoCotizaSalud->RUT_INTEGRANTE);
        }
        if($this->solicitud->ID_SUB_TIPO_SOLICITUD== Constante::SUB_TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD_2_COTIZANTE_NO_RESIDE_MISMO_HOGAR){
            $this->nombreSolicitud = 'Aporte monetario efectivo';
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

                //cambio de la solucitud segun caso correspondiente
                $this->solicitud->ID_SUB_TIPO_SOLICITUD = $this->input->get('subtipo'); // para solicituar adjuntar los documentos
                $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
                $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;

            } else {
                $this->actualizaDataTramite();
                $this->solicitud->ID_USUARIO_MODIFICACION = $this->usuario->RUN;
                $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            }

            $this->solicitud->save();

            DB::commit();

            } catch (Throwable $ex) {
        DB::rollback();
        throw $ex;
        }
    }

    protected function actualizaDataTramite()
    {
        DB::beginTransaction();
        try {
            if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD_2_COTIZANTE_NO_RESIDE_MISMO_HOGAR) { // Aporte monetario efectivo del cotizante al hogar de la(s) carga(s) de cotización de salud
                $monto = $this->input->get('monto');
                $monto = str_replace('.', '', $monto);
                $this->complementoCotizaSalud->MONTO = $monto;
                $this->solicitud->ID_DOC_COMPLEMENTO = $this->input->get('id-documento');       //$input['tipodoc']; //Se agrega ID de documento

                $this->complementoCotizaSalud->RUT_CAUSANTE = $this->beneficiario->PER_RUN;     //$input['rut_causante1'];
                $this->complementoCotizaSalud->DV_CAUSANTE = $this->beneficiario->PER_DV;       //$input['dv_causante1'];
                $this->complementoCotizaSalud->RUT_RECEPTOR = $this->receptor->PER_RUN;         //$input['rut_receptor1'];
                $this->complementoCotizaSalud->DV_RECEPTOR = $this->receptor->PER_DV;           //$input['dv_receptor1'];
                $this->complementoCotizaSalud->RUT_PAGADOR = $this->pagador->PER_RUN;           //['rut_pagadorpension1'];
                $this->complementoCotizaSalud->DV_PAGADOR = $this->pagador->PER_DV;             //$input['dv_pagadorpension1'];

                $this->complementoCotizaSalud->save();
            }
            DB::commit();

        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    private function validarCamposRequeridos()
    {
        $this->input->validate([
            'subtipo' => 'required',
            'run-titular' => 'required'
        ]);

        if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD_2_COTIZANTE_NO_RESIDE_MISMO_HOGAR) {
            $documentoService = new Adjuntos();
            if (!$this->input->get('monto') && $this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                $docsAdjuntos = $documentoService->recuperarAdjuntos($this->solicitud->ID_SOLICITUD);
                if (!is_null($docsAdjuntos) &&
                    !array_key_exists(Constante::DOCUMENTO_SOLICITUD_1830_RESOLUCION_QUE_FIJA_ALIMENTOS_PROVISORIOS_EN_EJERCICIO_DE_LA_POTESTAD_CAUTELAR_DEL_TRIBUNAL_EN_EL_MARCO_DE_UNA_DEMANDA_O_DENUNCIA_POR_VIOLENCIA_INTRAFAMILIAR, $docsAdjuntos)
                ) {
                    throw new ExcepcionReglaNegocio('El campo monto es obligatorio.',Constante::ERR_NEGOCIO_EL_CAMPO_MONTO_ES_OBLIGATORIO);
                }
            }
            if ($this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                if (!$this->input->get('pagador')) {
                    throw new ExcepcionReglaNegocio('El campo pagador es obligatorio.',Constante::ERR_NEGOCIO_EL_CAMPO_PAGADOR_ES_OBLIGATORIO);
                }
            }
            if ($this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                if (!$this->input->get('receptor')) {
                    throw new ExcepcionReglaNegocio('El campo receptor es obligatorio.',Constante::ERR_NEGOCIO_EL_CAMPO_RECEPTOR_ES_OBLIGATORIO);
                }
            }
            if (!$this->input->get('beneficiario') && $this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                if (!$this->input->get('beneficiario')) {
                    throw new ExcepcionReglaNegocio('El campo beneficiario es obligatorio.',Constante::ERR_NEGOCIO_EL_CAMPO_BENEFICIARIO_ES_OBLIGATORIO);
                }
            }
            if ($this->verificaMismoHogar($this->input->get('receptor')->PER_RUN, $this->input->get('pagador')->PER_RUN)) {
                throw new ExcepcionReglaNegocio('El receptor y alimentante no pueden pertenecer al mismo Hogar.',Constante::ERR_NEGOCIO_EL_RECEPTOR_Y_ALIMENTANTE_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
            }
            if (!$this->verificaMismoHogar($this->input->get('receptor')->PER_RUN, $this->input->get('beneficiario')->PER_RUN)) {
                throw new ExcepcionReglaNegocio('El receptor y alimentario deben ser parte del mismo Hogar.',Constante::ERR_NEGOCIO_EL_RECEPTOR_Y_ALIMENTARIO_DEBEN_SER_PARTE_DEL_MISMO_HOGAR);
            }
        }
        return true;
    }

    protected function actualizarRegistrosAprobacion()
    {
        $sqlUpdate2 = ComplementoCotizaSalud::where('id_solicitud', $this->solicitud->id_solicitud)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

    private function verificaMismoHogar($runIntegrante1, $runIntegrante2)
    {
        $persona1 = Personas::where('per_run', $runIntegrante1)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        $persona2 = Personas::where('per_run', $runIntegrante2)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        if (count($persona1) && count($persona2)) {
            $beneficiario1 = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona1->per_id)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
            $beneficiario2 = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona2->per_id)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
            if (count($beneficiario1) && count($beneficiario2)) {
                if ($beneficiario1->bnfgrupfami_grupofamiliar == $beneficiario2->bnfgrupfami_grupofamiliar) {
                    return true;
                }
            } elseif (!(count($beneficiario1) || count($beneficiario2))) {
                return false;
            }
        }
        return false;
    }

    public function agendado()
    {
        return null;
    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;
        $runTitular = (!is_null($input->tipoSolicitud->{$this->tipoSolicitud}->runTitular)) ?
            $input->tipoSolicitud->{$this->tipoSolicitud}->runTitular :
            $input->runCiudadano;

        if ($this->solicitante->PER_RUN == $runTitular) {
            $titular = $this->solicitante;
        } else {
            $titular = Personas::findByRun($runTitular);
        }
        return $titular;
    }
}
