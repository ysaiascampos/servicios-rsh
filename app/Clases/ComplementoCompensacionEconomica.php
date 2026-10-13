<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\ComplementoIngresos;
use App\Models\Comuna;
use App\Models\Personas;
use App\Sequence;
use App\Utiles;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ComplementoCompensacionEconomica extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA;
    public $nombreSolicitud = 'Compensación Económica';
    public $tituloFormulario = "Solicitud de Complemento por";
    public $ingreso;
    public $beneficiario;
    public $representante;
    public $pagador;

    public function editarSolicitud()
    {
        //$this->validacionGuardadoSolicitud();

        $fechaInicioPago = DateTime::createFromFormat('Y-m-d', $this->input->{'fecha-inicio'});
        $monto = $this->input->monto;
        $numeroCuotas = $this->input->cuotas;
        $subtipoTexto = $this->input->subtipo;

        $this->checkFechaInicio($fechaInicioPago);

        if ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING_PAGO) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_1_PAGO;
            $this->pagador = Utiles::getPersona( $this->input->get('pagador') );
            $this->representante = Utiles::getPersona( $this->input->get('representante') );
            $this->beneficiario = Utiles::getPersona($this->input->get('beneficiario') );
            $this->checkRequisitosPago();
        }
        if ($subtipoTexto == Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING_RECEPCION) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_2_RECEPCION;
            $this->representante = Utiles::getPersona( $this->input->get('representante') );
            $this->pagador = Utiles::getPersona( $this->input->get('pagador') );
            $this->beneficiario = Utiles::getPersona($this->input->get('beneficiario') );
            $this->checkRequisitosRecepcion();
        }

        $this->verificarFirmaSimple();

        $this->guardarSolicitud([
            'subTipo' => $subtipo,
            'monto' => $monto,
            'fechaInicio' => $fechaInicioPago->format('Y-m-d H:i:s'),
            'cuotas' => $numeroCuotas
        ]);

        $this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->requiereCamposObligatorios = false;
        $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        $this->requiereCamposObligatorios = false;
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
        // TODO: Implement cargarDataTramite() method.
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
        if ($input->subtipo == Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING_PAGO) {
            $titular = $input->pagador;
        } else {
            $titular = $input->beneficiario;
        }
        return $titular;
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
        $idSubTipoSolicitud = $data['subTipo'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = $nuevoFolio;
        $datoActual = $nuevoFolio;

        DB::beginTransaction();

        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idSubTipoSolicitud;    // para solicituar adjuntar los documentos
            $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            $registroComplementoIngreso = $this->obtenerRegistroComplementoIngresos();

            $registroComplementoIngreso->ID_SOLICITUD = $this->solicitud->ID_SOLICITUD;
            $registroComplementoIngreso->C_TCOMPLEMENTO_INGRESO = $idSubTipoSolicitud;
            $registroComplementoIngreso->MONTO = $data['monto'];
            $registroComplementoIngreso->FECHA_INICIO_PAGO = $data['fechaInicio'];
            $registroComplementoIngreso->NUMERO_CUOTAS = $data['cuotas'];
            $registroComplementoIngreso->RUT_CAUSANTE = $this->beneficiario->PER_RUN;
            $registroComplementoIngreso->DV_CAUSANTE = $this->beneficiario->PER_DV;
            $registroComplementoIngreso->RUT_RECEPTOR = $this->representante->PER_RUN;
            $registroComplementoIngreso->DV_RECEPTOR = $this->representante->PER_DV;
            $registroComplementoIngreso->RUT_PAGADOR = $this->pagador->PER_RUN; //$rut_causante; //$rut_pagador;
            $registroComplementoIngreso->DV_PAGADOR = $this->pagador->PER_DV;   //$dv_causante; //$dv_pagador;
            $registroComplementoIngreso->save();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    private function obtenerRegistroComplementoIngresos()
    {
        if (is_null($this->ingreso)) {
            $ingresoNew = new ComplementoIngresos();
            unset($ingresoNew->RN);
            $ingresoNew->COMPLEMENTO_ID = Sequence::nextValue('seq_ab_complemento_ingresos');
            $ingresoNew->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $ingresoNew->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $ingresoNew->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $ingresoNew->FECHA_CREACION = date('Y-m-d H:i:s');
        } else {
            $ingresoNew = $this->ingreso;
        }
        return $ingresoNew;
    }

    protected function actualizarRegistrosAprobacion()
    {
        $sqlUpdate2 = ComplementoIngresos::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

    private function checkPersonas()
    {
        $personas = [
            'beneficiario' => $this->beneficiario,
            'pagador' => $this->pagador,
            'representante' => $this->representante
        ];
        $errorMessages = [];
        foreach ($personas as $rol => $persona) {
            if ($persona instanceof Personas !== true) {
                $errorMessages[$rol] = $rol . ' no se encuentra en los registros de la plataforma';
            }
        }
        if (count($errorMessages) > 0) {
            throw new ExcepcionReglaNegocio($errorMessages);
        }
    }

    private function checkRequisitosPago()
    {
        $this->checkPersonas();

        if ($this->representante->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
            throw new ExcepcionReglaNegocio('El representante debe ser mayor de edad.',constante::ERR_NEGOCIO_REPRESENTANTE_DEBE_SER_MAYOR_DE_EDAD);
        }

        $folioPagador = Utiles::verificaRunTieneGrupoFamiliar($this->pagador->PER_RUN);
        if ($folioPagador->ESTADO == '0') { // estado 0, No posee grupo Grupo Familiar
            throw new ExcepcionReglaNegocio('El pagador no posee un registro de Hogar vigente.',constante::ERR_NEGOCIO_PAGADOR_NO_POSEE_UN_REGISTRO_DE_HOGAR_VIGENTE);
        }

        $runActualPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->solicitante->PER_RUN,
            $this->pagador->PER_RUN
        );
        if ($runActualPagadorMismoHogar->ESTADO == '0') { // estado 0, No pertenecen al mismo grupo familiar
            throw new ExcepcionReglaNegocio('El pagador debe ser parte del Hogar actual.',constante::ERR_NEGOCIO_PAGADOR_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
        }

        // 1.3 Si el alimentante tiene RSH en el mismo hogar que el representante o el alimentario,
        // no debe permitir realizar la solicitud.
        $beneficiarioPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->beneficiario->PER_RUN,
            $this->pagador->PER_RUN
        );
        if ($beneficiarioPagadorMismoHogar->ESTADO == '1') { // estado 1, Pertenecen al mismo Grupo Familiar
            throw new ExcepcionReglaNegocio('El beneficiario y pagador no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_BENEFICIARIO_Y_PAGADOR_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
        }

        // 1.3 Si el alimentante tiene RSH en el mismo hogar que el representante o el beneficiario,
        // no debe permitir realizar la solicitud.
        $representantePagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->representante->PER_RUN,
            $this->pagador->PER_RUN
        );
        if ($representantePagadorMismoHogar->ESTADO == '1') { // estado 1, Pertenecen al mismo Grupo Familiar
            throw new ExcepcionReglaNegocio('El representante y pagador no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_REPRESENTANTE_Y_PAGADOR_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
        }

        // 1.1 Si el representante o el beneficiario (solo 1 de ellos) tiene RSH debe mostrar mensaje y no permitir realizar la solicitud.
        // 1.2 Si el representante y el beneficiario (ambos) tienen RSH pero en distintos hogares debe mostrar mensaje y no permitir realizar la solicitud.
        $representanteBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->representante->PER_RUN,
            $this->beneficiario->PER_RUN
        );
        if ($representanteBeneficiarioMismoHogar->ESTADO == '0') { // estado 0, No pertenecen al mismo grupo familiar
            throw new ExcepcionReglaNegocio('El representante y beneficiario deben ser parte del mismo Hogar.',constante::ERR_NEGOCIO_REPRESENTANTE_Y_BENEFICIARIO_DEBEN_SER_PARTE_DEL_MISMO_HOGAR);
        }
    }

    private function checkRequisitosRecepcion()
    {
        $this->checkPersonas();

        $folioBeneficiario = Utiles::verificaRunTieneGrupoFamiliar($this->beneficiario->PER_RUN);
        if ($folioBeneficiario->ESTADO == '0') { // estado 0, No posee grupo Grupo Familiar
            throw new ExcepcionReglaNegocio('El beneficiario no posee un registro de Hogar vigente.',constante::ERR_NEGOCIO_BENEFICIARIO_NO_POSEE_UN_REGISTRO_DE_HOGAR_VIGENTE);
        }

        $runActualBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->solicitante->PER_RUN,
            $this->beneficiario->PER_RUN
        );
        if ($runActualBeneficiarioMismoHogar->ESTADO == '0') { // estado 0, No pertenecen al mismo grupo familiar
            throw new ExcepcionReglaNegocio('El beneficiario debe ser parte del Hogar actual.',constante::ERR_NEGOCIO_BENEFICIARIO_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
        } else {
            $runActualReceptorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
                $this->solicitante->PER_RUN,
                $this->representante->PER_RUN
            );
            if ($runActualReceptorMismoHogar->ESTADO == '0') { // estado 0, No pertenecen al mismo grupo familiar
                throw new ExcepcionReglaNegocio('El representante debe ser parte del Hogar actual.',constante::ERR_NEGOCIO_REPRESENTANTE_DEBE_SER_PARTE_DEL_HOGAR_ACTUAL);
            }
        }

        // 2.3 Si el alimentante tiene RSH en el mismo hogar que el representante o el beneficiario no debe permitir realizar la solicitud.
        $beneficiarioPagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->beneficiario->PER_RUN,
            $this->pagador->PER_RUN
        );
        if ($beneficiarioPagadorMismoHogar->ESTADO == '1') { // estado 1, Pertenecen al mismo Grupo Familiar
            throw new ExcepcionReglaNegocio('El beneficiario y pagador no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_BENEFICIARIO_Y_PAGADOR_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
        }

        // 2.3 Si el alimentante tiene RSH en el mismo hogar que el representante o el beneficiario no debe permitir realizar la solicitud.
        $representantePagadorMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->representante->PER_RUN,
            $this->pagador->PER_RUN
        );
        if ($representantePagadorMismoHogar->ESTADO == '1') { // estado 1, Pertenecen al mismo Grupo Familiar
            throw new ExcepcionReglaNegocio('El representante y pagador no pueden pertenecer al mismo Hogar.',constante::ERR_NEGOCIO_REPRESENTANTE_Y_PAGADOR_NO_PUEDEN_PERTENECER_AL_MISMO_HOGAR);
        }

        // 2.1 Si el representante o el beneficiario (solo 1 de ellos) tiene RSH debe mostrar mensaje y no permitir realizar la solicitud.
        // 2.2 Si el representante y el beneficiario (ambos) tienen RSH pero en distintos hogares debe mostrar mensaje y no permitir realizar la solicitud.
        $representanteBeneficiarioMismoHogar = Utiles::verificaRunesTienenMismoGrupoFamiliar(
            $this->representante->PER_RUN,
            $this->beneficiario->PER_RUN
        );
        if ($representanteBeneficiarioMismoHogar->ESTADO == '0') { // estado 0, No pertenecen al mismo grupo familiar
            throw new ExcepcionReglaNegocio('El representante y beneficiario deben ser parte del mismo Hogar.',constante::ERR_NEGOCIO_REPRESENTANTE_Y_BENEFICIARIO_DEBEN_SER_PARTE_DEL_MISMO_HOGAR);
        }
    }

    private function checkFechaInicio(DateTime $fechaInicioPago)
    {
        $dias = (Constante::COMPENSACION_ECONOMICA_MAXIMO_CUOTAS / 12) * 365;
        $intervalo = new DateInterval('P' . $dias . 'D');
        $minFechaInicio = (new DateTime())->sub($intervalo);
        $maxFechaInicio = (new DateTime())->add($intervalo);
        if ($fechaInicioPago < $minFechaInicio) {
            throw new ExcepcionReglaNegocio('Fecha de inicio excede el número máximo de cuotas',constante:: ERR_NEGOCIO_FECHA_DE_INICIO_EXCEDE_EL_NUMERO_MAXIMO_DE_CUOTAS);
        }
        if ($fechaInicioPago > $maxFechaInicio) {
            throw new ExcepcionReglaNegocio('Fecha de inicio excede el número máximo de cuotas',constante::ERR_NEGOCIO_FECHA_DE_INICIO_EXCEDE_EL_NUMERO_MAXIMO_DE_CUOTAS);
        }
    }

    public function agendado()
    {
      return null;
    }

}
