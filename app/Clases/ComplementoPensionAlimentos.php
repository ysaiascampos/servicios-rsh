<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Constantes\ConstanteExcepcion;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\ComplementoIngresos;
use App\Models\Comuna;
use App\Models\Personas;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;

class ComplementoPensionAlimentos extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS;
    public $nombreSolicitud = 'Solicitud de Complemento por Pensión de Alimentos';
    public $tituloFormulario = "";
    public $ingresos;
    public $beneficiario;
    public $receptor;
    public $pagador;
    protected $subTipoSolicitud_Interno;

    public function editarSolicitud()
    {
        // INICIO VALIDACIONES //
        //$this->validacionGuardadoSolicitud();
        //---------------------//
        $runSolicitante = $this->solicitante->PER_RUN;    // MUNICIPAL y CHILE-ATIENDE: run-ciudadano solicitante / CIUDADANA: run-ciudadano
        //$runTitular = $this->titular->PER_RUN;            // MUNICIPAL Y CIUDADANA: run a quien se le hace la solicitud (mismo ciudadano o algún integrante de su grupo)
        //$runUsuario = $this->usuario->RUN;          // MUNICIPAL: run-usuario-municipal / CIUDADANA y CHILE-ATIENDE: run-usuario-municipal calculado // tb llamado $usuarioEncargado

        $subtipoTexto = $this->input->subtipo;
        $opcionSubtipo = null;
        $opcionCeseVariacion = null;

        //$borrador = $this->accion == 'borrador';

        $monto = null;
        $fechaInicioPago = null;
        $numeroCuotas = null;
        $runBeneficiario = null;
        $dvBeneficiario = null;
        $runReceptor = null;
        $dvReceptor = null;
        $runPagador = null;
        $dvPagador = null;

        $tramiteIndividual = $this->input->detalle;
        $idRegistroComplemento = $tramiteIndividual->id;
        $runBeneficiario = $tramiteIndividual->beneficiario;    // Alimentario - Causante (Persona por la cual se paga la pensión)
        $runReceptor = $tramiteIndividual->receptor;            // Receptor de la pensión (Representante del alimentario)
        $runPagador = $tramiteIndividual->pagador;              // Alimentante (Persona que paga la pensión de alimentos)
        //$nuevoFolioTitular = null;

        if ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_PAGO)
        {
            //Si el canal es ciudadanoUX el pagador es sobre quien se hace el tramite.
//            if (strtolower($this->canal) == Constante::CANAL_2_STRING_MINUS_CLAVE_UNICA) {
//                $runTitular = $runPagador;
//                $this->runTitular = $runTitular;
//            }
            $opcionSubtipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_1_PAGO;
            $this->subTipoSolicitud_Interno = $opcionSubtipo;

            $folioPagador = Utiles::verificaRunTieneGrupoFamiliar($runPagador);
            if ($folioPagador->ESTADO == '0') {
                // estado 0, No posee grupo Grupo Familiar
                throw new ExcepcionReglaNegocio('El alimentante no posee un registro de Hogar vigente.', ConstanteExcepcion::ERR_NEGOCIO_ALIMENTANTE_NO_POSEE_UN_REGISTRO_DE_HOGAR_VIGENTE);
            }

            $titularPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runSolicitante, $runPagador);
            if ($titularPagadorMismoHogar->ESTADO == '0') {
                // estado 0, No pertenecen al mismo grupo familiar
                throw new ExcepcionReglaNegocio('El alimentante debe ser parte del Hogar actual.',ConstanteExcepcion::ERR_NEGOCIO_ALIMENTANTE_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
            }

            // 1.3 Si el alimentante tiene RSH en el mismo hogar que el receptor o el alimentario, no debe permitir realizar la solicitud.
            $beneficiarioPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runBeneficiario, $runPagador);
            if ($beneficiarioPagadorMismoHogar->ESTADO == '1') {
                // estado 1, Pertenecen al mismo Grupo Familiar
                throw new ExcepcionReglaNegocio('El alimentario y alimentante no pueden pertenecer al mismo Hogar.',ConstanteExcepcion::ERR_NEGOCIO_ALIMENTARIO_Y_ALIMENTANTE_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
            }

            // 1.3 Si el alimentante tiene RSH en el mismo hogar que el receptor o el alimentario no debe permitir realizar la solicitud.
            $receptorPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runReceptor, $runPagador);
            if ($receptorPagadorMismoHogar->ESTADO == '1') {
                // estado 1, Pertenecen al mismo Grupo Familiar
                throw new ExcepcionReglaNegocio('El receptor y alimentante no pueden pertenecer al mismo Hogar.',ConstanteExcepcion::ERR_NEGOCIO_RECEPTOR_Y_ALIMENTANTE_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
            }

            // 1.1 Si el receptor o el alimentario (solo 1 de ellos) tiene RSH debe mostrar mensaje y no permitir realizar la solicitud.
            // 1.2 Si el receptor y el alimentario (ambos) tienen RSH pero en distintos hogares debe mostrar mensaje y no permitir realizar la solicitud.
            $receptorBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runReceptor, $runBeneficiario);
            if ($receptorBeneficiarioMismoHogar->ESTADO == '0') {
                // estado 0, No pertenecen al mismo grupo familiar
                throw new ExcepcionReglaNegocio('El receptor y alimentario deben ser parte del mismo Hogar.',ConstanteExcepcion::ERR_NEGOCIO_RECEPTOR_Y_ALIMENTARIO_DEBEN_SER_PARTE_DEL_MISMO_HOGAR);
            }
            //}
        } elseif ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_RECEPCION)
        {
            // Recepción de pensión de alimentos
            //Si el canal es ciudadanoUX el receptor es sobre quien se hace el tramite.
//            if (strtolower($this->canal) == Constante::CANAL_2_STRING_MINUS_CLAVE_UNICA) {
//                $runTitular = $runBeneficiario;
//                $this->runTitular = $runTitular;
//            }
            $opcionSubtipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_2_RECEPCION;
            $this->subTipoSolicitud_Interno = $opcionSubtipo;

            $folioBeneficiario = Utiles::verificaRunTieneGrupoFamiliar($runBeneficiario);
            if ($folioBeneficiario->ESTADO == '0') {
                // estado 0, No posee grupo Grupo Familiar
                throw new ExcepcionReglaNegocio('El alimentario no posee un registro de Hogar vigente.',constante::ERR_NEGOCIO_ALIMENTARIO_NO_POSEE_UN_REGISTRO_DE_HOGAR_VIGENTE);
            }

            $titularBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runSolicitante, $runBeneficiario);
            if ($titularBeneficiarioMismoHogar->ESTADO == '0') {
                // estado 0, No pertenecen al mismo grupo familiar
                throw new ExcepcionReglaNegocio('El alimentario debe ser parte del Hogar actual.',constante::ERR_NEGOCIO_ALIMENTARIO_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
            } else {
                $titularReceptorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runBeneficiario, $runReceptor);
                if ($titularReceptorMismoHogar->ESTADO == '0') {
                    // estado 0, No pertenecen al mismo grupo familiar
                    throw new ExcepcionReglaNegocio('El receptor debe ser parte del Hogar actual.',constante::ERR_NEGOCIO_RECEPTOR_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
                }
            }

            // 2.3 Si el alimentante tiene RSH en el mismo hogar que el receptor o el alimentario no debe permitir realizar la solicitud.
            $beneficiarioPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runBeneficiario, $runPagador);
            //                if ($beneficiarioPagadorMismoHogar->estado == "1") {
            if ($beneficiarioPagadorMismoHogar->ESTADO == '1') {
                // estado 1, Pertenecen al mismo Grupo Familiar
                throw new ExcepcionReglaNegocio('El alimentario y alimentante no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_ALIMENTARIO_Y_ALIMENTANTE_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
            }

            // 2.3 Si el alimentante tiene RSH en el mismo hogar que el receptor o el alimentario no debe permitir realizar la solicitud.
            $receptorPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runReceptor, $runPagador);
            if ($receptorPagadorMismoHogar->ESTADO == '1') {
                // estado 1, Pertenecen al mismo Grupo Familiar
                throw new ExcepcionReglaNegocio('El receptor y alimentante no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_RECEPTOR_Y_ALIMENTANTE_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
            }
            // 2.1 Si el receptor o el alimentario (solo 1 de ellos) tiene RSH debe mostrar mensaje y no permitir realizar la solicitud.
            // 2.2 Si el receptor y el alimentario (ambos) tienen RSH pero en distintos hogares debe mostrar mensaje y no permitir realizar la solicitud.
            $receptorBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar($runReceptor, $runBeneficiario);
            if ($receptorBeneficiarioMismoHogar->ESTADO == '0') {
                // estado 0, No pertenecen al mismo grupo familiar
                throw new ExcepcionReglaNegocio('El receptor y alimentario deben ser parte del mismo Hogar.',constante::ERR_NEGOCIO_RECEPTOR_Y_ALIMENTARIO_DEBEN_SER_PARTE_DEL_MISMO_HOGAR);
            }
        } elseif ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_CESE)
        {
            // Recepción de pensión de alimentos
            $opcionSubtipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_3_CESE;

            $registroComplementoBase = null;
            if (count($this->ingresos) > 0 && $this->ingresos[0]->complemento_id == $idRegistroComplemento) {
                $registroComplementoBase = $this->ingresos[0];
            } else {
                $registroComplementoBase = $this->recuperarRegistroComplementoPorId($idRegistroComplemento);
            }
            $opcionCeseVariacion = $registroComplementoBase->C_TCOMPLEMENTO_INGRESO;
            //Si el canal es ciudadanoUX sobre quien se hace el tramite es el pagador o receptor según el tipo de cese
            if ( $this->canal == Constante::CANAL_2_STRING_MINUS_CLAVE_UNICA ) {
                $runTitular = $registroComplementoBase->RUT_CAUSANTE;
                $this->titular = Utiles::getPersona($runTitular);
            }
            else{
                $this->titular = $this->solicitante;
            }
            $this->beneficiario = Utiles::getPersona($registroComplementoBase->RUT_CAUSANTE);
            $this->receptor = Utiles::getPersona($registroComplementoBase->RUT_RECEPTOR);
            $this->pagador = Utiles::getPersona($registroComplementoBase->RUT_PAGADOR);
            $this->subTipoSolicitud_Interno = $opcionCeseVariacion;
        }elseif ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_VARIACION)
        {
            // Recepción de pensión de alimentos
            $opcionSubtipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_4_VARIACION;

            $registroComplementoBase = null;
            if (count($this->ingresos) > 0 && $this->ingresos[0]->complemento_id == $idRegistroComplemento) {
                $registroComplementoBase = $this->ingresos[0];
            } else {
                $registroComplementoBase = $this->recuperarRegistroComplementoPorId($idRegistroComplemento);
            }
            $opcionCeseVariacion = $registroComplementoBase->C_TCOMPLEMENTO_INGRESO;
            //Si el canal es ciudadanoUX sobre quien se hace el tramite es el pagador o receptor según el tipo de cese
            if ( $this->canal == Constante::CANAL_2_STRING_MINUS_CLAVE_UNICA ) {
                $runTitular = $registroComplementoBase->RUT_CAUSANTE;
                $this->titular = Utiles::getPersona($runTitular);
            }
            else{
                $this->titular = $this->solicitante;
            }
            $this->beneficiario = Utiles::getPersona($registroComplementoBase->RUT_CAUSANTE);
            $this->receptor = Utiles::getPersona($registroComplementoBase->RUT_RECEPTOR);
            $this->pagador = Utiles::getPersona($registroComplementoBase->RUT_PAGADOR);
            $this->subTipoSolicitud_Interno = $opcionCeseVariacion;
        } else {
            throw new ExcepcionConsistencia('Subtipo no válido.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }
        $this->verificarFirmaSimple();

        $this->guardarSolicitud([
            'opcionSubtipo' => $opcionSubtipo,
            'opcionCeseVariacion' => $opcionCeseVariacion
        ]);

        //$this->generarPdfFirmaSimple();
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
            throw new ValidacionException('No se han adjuntado todos los documentos requeridos.');
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

        /*$vigenteNuevo = $this->solicitud->vigente;
        if ($vigenteActual == Constante::VIGENTE_3_SOLICITUD_BORRADOR &&
            $vigenteNuevo == Constante::VIGENTE_1_VIGENTE
        ) {
            $metodo = 'POST';
            $url = '/enviarmail';
            $datosformulario = array('idsolicitud' => $this->solicitud->id_solicitud);
            try {
                Utiles::enviandoCorreo($metodo, $url, $datosformulario, 5);
            } catch (Exception $ex) {
                Log::error($ex->getMessage() . ' Function->pendienteResolver(ArcTramite), idsol: ' .$this->solicitud->id_solicitud);
            }
        }*/
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
        $this->ingresos = ComplementoIngresos::where('id_solicitud', $this->solicitud->ID_SOLICITUD)->get();

        if ( !($this->ingresos) ) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de complemento Ingresos.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }

        $this->beneficiario = Utiles::getPersona($this->ingresos[0]->RUT_CAUSANTE);
        $this->receptor = Utiles::getPersona($this->ingresos[0]->RUT_RECEPTOR);
        $this->pagador = Utiles::getPersona($this->ingresos[0]->RUT_PAGADOR);

    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;
        $input = $input->{'tipo-solicitud'}->{$this->tipoSolicitud};
        if ($input->subtipo == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_1_PAGO) {
            $titular = $input->pagador;
        } else {
            $titular = $input->beneficiario;
        }
        return $titular;
    }

    protected function consultarTitular()
    {
        if (isset($this->solicitud->RUN_INTEGRANTE) and ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO)) {
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
        $idSubTipoSolicitud = $data['opcionSubtipo'];
        DB::beginTransaction();
        try {

            $nuevoFolio = $this->obtenerNuevoFolio();
            $datoNuevo = $nuevoFolio;
            $datoActual = $nuevoFolio;

            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idSubTipoSolicitud; // para solicituar adjuntar los documentos

            $this->solicitud->ID_FUENTE_ACTUAL = null;
            $this->solicitud->RUN_INTEGRANTE = $this->runTitular;
            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;

            if ($idSubTipoSolicitud == Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_2_RECEPCION) {
                $this->solicitud->id_doc_complemento = null; // $input['tipodoc'];
            }

            $this->solicitud->save();

            $registroComplementoIngreso = $this->obtenerRegistroComplementoIngresos([
                'opcionSubtipo' => $data['opcionSubtipo'],
                'opcionCeseVariacion' => $data['opcionCeseVariacion']
            ]);
            $registroComplementoIngreso->save();

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    protected function obtenerRegistroComplementoIngresos($data)
    {
        $idSubTipoSolicitud = $data['opcionSubtipo'];
        $opcionCeseVariacion = $data['opcionCeseVariacion'];

        $monto = null;
        $fechaInicioPago = null;
        $numeroCuotas = null;
        $runBeneficiario = null;
        $dvBeneficiario = null;
        $runReceptor = null;
        $dvReceptor = null;
        $runPagador = null;
        $dvPagador = null;

        $tramiteIndividual = $this->input->detalle;
        $runBeneficiario = $tramiteIndividual->beneficiario;    // Alimentario - Causante (Persona por la cual se paga la pensión)
        $dvBeneficiario = ($runBeneficiario == '') ? null : Utiles::dv($runBeneficiario);
        $runReceptor = $tramiteIndividual->receptor;            // Receptor de la pensión (Representante del alimentario)
        $dvReceptor = ($runReceptor == '') ? null : Utiles::dv($runReceptor);
        $runPagador = $tramiteIndividual->pagador;              // Alimentante (Persona que paga la pensión de alimentos)
        $dvPagador = ($runPagador) == '' ? null : Utiles::dv($runPagador);
        $monto = $tramiteIndividual->monto;
        //$nuevoFolioTitular = null;

        if (count($this->ingresos) > 0) { // PUT
            $nuevaFilaComplementoIngresos = $this->ingresos->first();
        } else { // POST
            $nuevaFilaComplementoIngresos = new ComplementoIngresos();
            $nuevaFilaComplementoIngresos->COMPLEMENTO_ID = Sequence::nextValue('seq_ab_complemento_ingresos');
            $nuevaFilaComplementoIngresos->ID_SOLICITUD = $this->solicitud->ID_SOLICITUD;
        }
        $nuevaFilaComplementoIngresos->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
        $nuevaFilaComplementoIngresos->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
        $nuevaFilaComplementoIngresos->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
        $nuevaFilaComplementoIngresos->FECHA_CREACION = date('Y-m-d H:i:s');

        if ($idSubTipoSolicitud == 3) {
            $nuevaFilaComplementoIngresos->C_TCOMPLEMENTO_INGRESO = $opcionCeseVariacion; //opcion seleccionada
        } else {
            $nuevaFilaComplementoIngresos->C_TCOMPLEMENTO_INGRESO = $idSubTipoSolicitud;
        }

        $nuevaFilaComplementoIngresos->MONTO = $monto;
        $nuevaFilaComplementoIngresos->FECHA_INICIO_PAGO = $fechaInicioPago;
        $nuevaFilaComplementoIngresos->RUT_CAUSANTE = $runBeneficiario;
        $nuevaFilaComplementoIngresos->DV_CAUSANTE = $dvBeneficiario;
        $nuevaFilaComplementoIngresos->RUT_RECEPTOR = $runReceptor;
        $nuevaFilaComplementoIngresos->DV_RECEPTOR = $dvReceptor;
        $nuevaFilaComplementoIngresos->RUT_PAGADOR = $runPagador; //$rut_causante; //$rut_pagador;
        $nuevaFilaComplementoIngresos->DV_PAGADOR = $dvPagador;   //$dv_causante; //$dv_pagador;

        return $nuevaFilaComplementoIngresos;
    }

    protected function actualizarRegistrosAprobacion()
    {
        $sqlUpdate2 = ComplementoIngresos::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

    private function recuperarRegistroComplementoPorId($idComplemento)
    {
        $regIComplemento = ComplementoIngresos::where('ab_complemento_ingresos.complemento_id', $idComplemento)
            ->first();
        if (!$regIComplemento) {
            throw new ExcepcionConsistencia('El registro de complemento especificado no es válido');
        }
        return $regIComplemento;
    }

    private function validarCamposRequeridos()
    {
        if ($this->requiereCamposObligatorios) {
            $this->input->validate([
                'subtipo' => 'required',
                'detalle' => 'required'
            ]);

            if ($this->input->subtipo != Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_CESE) {
                $this->input->detalle->validate([
                    'pagador' => 'required',
                    'receptor' => 'required',
                    'beneficiario' => 'required',
                    'monto' => 'required'
                ]);
            } else {
                $this->input->detalle->validate([
                    'id' => 'required',
                    'monto' => 'required'
                ]);
            }
        }
        return true;
    }

    public function agendado()
    {
        return null;
    }

}
