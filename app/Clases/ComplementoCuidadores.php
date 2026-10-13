<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\TramiteInput;
use App\Models\ComplementoCuidador;
use App\Models\Comuna;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Sequence;
use App\Utiles;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class ComplementoCuidadores extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES;
    public $nombreSolicitud = 'Solicitud de Complemento por Cuidados';
    public $tituloFormulario = "";
    public $registroCuidador;
    public $subTipo;
    public $cuidador;
    public $cuidado;
    public $principal;
    public $tituloCuidador;
    public $tituloCuidado;

    public function editarSolicitud()
    {
        //$this->validacionGuardadoSolicitud();
        if ($this->input->cuidador == $this->input->cuidado) {
            throw new ExcepcionValidacion('Cuidador y Cuidado no pueden ser la misma persona');
        }
        $subTipoTexto = $this->input->subtipo;
        $cuidador = Utiles::getPersona($this->input->cuidador);
        $cuidado = Utiles::getPersona($this->input->cuidado);
        $principal = $this->input->principal;

        //regla que solo pueden ser cuidadores los mayores de edad
        $this->checkCiudadorMayorEdad($cuidador);

        if ($subTipoTexto == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADOR) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR;
        }
        if ($subTipoTexto == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADO) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO;
        }
        if ($subTipoTexto == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CESE) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE;
        }
        if ($subTipoTexto == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_ROL) {
            $subtipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL;
        }

        $this->checkNumeroRegistrosActivosCiudador($cuidador);
        $this->checkNumeroRegistrosActivosCiudado($cuidado);
        $this->checkV1Cuidado($cuidado);
        if ($principal == True) {
            $this->checkSoloUnPrincipalPorCuidado($cuidado);
        }

        $this->verificarFirmaSimple();

        $this->guardarSolicitud([
            'subTipo' => $subtipo,
            'cuidador' => $cuidador,
            'cuidado' => $cuidado,
            'principal' => $principal
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
            throw new ExcepcionValidacion('No se han adjuntado todos los documentos requeridos.');
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
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->cuidado->per_run, $this->tipoSolicitud);
                    $usuarioEncargado = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);//cuidado->per_run
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
        $this->registroCuidador = ComplementoCuidador::where('id_solicitud', $this->solicitud->ID_SOLICITUD)->get();

        if ( !($this->registroCuidador) ) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de complemento Cuidadores.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }

        $this->cuidador = Utiles::getPersona($this->registroCuidador[0]->RUT_CUIDADOR);
        $this->cuidador->GRUPOFAMILIAR=$this->cuidador->getPerteneceGrupoFamiliar($this->cuidador['PER_ID']);
        $this->cuidado = Utiles::getPersona($this->registroCuidador[0]->RUT_CUIDADO);
        $this->cuidado->GRUPOFAMILIAR=$this->cuidado->getPerteneceGrupoFamiliar($this->cuidado['PER_ID']);
        $this->cuidado->JEFEHOGAR = $this->cuidado->esJefeHogar($this->cuidado['PER_ID']);
        $this->subTipo = $this->registroCuidador[0]->C_TCOMPLEMENTO_CUIDADORES;
        $this->principal = $this->registroCuidador[0]->PRINCIPAL;

        $this->definirTituloCartolaCuadro($this->solicitud->ID_SUB_TIPO_SOLICITUD);

    }

    protected function definirTituloCartolaCuadro($data){
        switch ($data){
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                $this->nombreSolicitud = "Solicitud de Complemento por Cese de Cuidados";
                $this->tituloCuidador = 'DATOS DE LA PERSONA QUE DEJA DE SER CUIDADORA:';
                $this->tituloCuidado = 'DATOS DE LA PERSONA QUE DEJA DE RECIBIR CUIDADOS:';
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                $this->nombreSolicitud = "Solicitud de Complemento por Cuidados Cambio de Roles";
                $this->tituloCuidador = 'DATOS DE LA PERSONA CUIDADORA QUE CAMBIA DE ROL:';
                $this->tituloCuidado = 'DATOS DE LA PERSONA QUE RECIBE CUIDADOS:';
                break;
            default:
                $this->nombreSolicitud = 'Solicitud de Complemento por Cuidados';
                $this->tituloCuidador = 'DATOS DE LA PERSONA CUIDADORA:';
                $this->tituloCuidado = 'DATOS DE LA PERSONA QUE REQUIERE CUIDADOS:';
                break;
        }
    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;
        $input = $input->{'tipo-solicitud'}->{$this->tipoSolicitud};
        if ($input->subtipo == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADOR) {
            $titular = $input->cuidador;
        } else {
            $titular = $input->cuidado;
        }
        if (is_null($titular)) {
            throw new ExcepcionConsistencia('Error al obtener RUN Titular',
                Constante::ERR_NEGOCIO_VALOR_RUN_TITULAR_REQUERIDO);
        }
        $titular = Personas::findByRun($titular);
        if (is_null($titular)) {
            throw new ExcepcionConsistencia('El ciudadano no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
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
        $this->firmaJefeHogar = true;
    }

    protected function guardarSolicitud(array $data)
    {
        $idSubTipoSolicitud = $data['subTipo'];
        $cuidador = $data['cuidador'];
        $cuidado = $data['cuidado'];
        $principal = $data['principal'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = $nuevoFolio;
        $datoActual = $nuevoFolio;

        DB::beginTransaction();

        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);
            if ($this->accion == "resolver") {
                $comuna = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($cuidado->PER_RUN, false);
            }
            else{
            //($this->accion == "documentos") {
                $comuna = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->titular->PER_RUN, false);
            }
            //$comuna = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->titular, false);
            if ($comuna) {
                $ecComuna = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);
                $this->solicitud->COM_ID = $comuna;
                $this->solicitud->ID_USUARIO_ASIGNADO = $ecComuna;
            }

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idSubTipoSolicitud;    // para solicituar adjuntar los documentos
            $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            $registroComplementoCuidadores = $this->obtenerRegistroComplementoCuidadores();

            $registroComplementoCuidadores->ID_SOLICITUD = $this->solicitud->ID_SOLICITUD;
            $registroComplementoCuidadores->C_TCOMPLEMENTO_CUIDADORES = $idSubTipoSolicitud;
            $registroComplementoCuidadores->RUT_CUIDADOR = $cuidador->PER_RUN;
            $registroComplementoCuidadores->RUT_CUIDADO = $cuidado->PER_RUN;
            $registroComplementoCuidadores->PRINCIPAL = $principal;
            $registroComplementoCuidadores->save();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    private function obtenerRegistroComplementoCuidadores()
    {
        if (is_null($this->registroCuidador)) {
            $cuidadorNew = new ComplementoCuidador();
            unset($cuidadorNew->RN);
            $cuidadorNew->CUIDADORES_ID = Sequence::nextValue('seq_ab_complemento_cuidadores');
            $cuidadorNew->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $cuidadorNew->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $cuidadorNew->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $cuidadorNew->FECHA_CREACION = date('Y-m-d H:i:s');
        } else {
            $cuidadorNew = $this->registroCuidador;
        }
        return $cuidadorNew;
    }

    protected function actualizarRegistrosAprobacion()
    {
        $sqlUpdate2 = ComplementoCuidador::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->update([
                'VIGENTE' => Constante::VIGENTE_1_VIGENTE,
                'CALCULOACTIVO' => Constante::CALCULO_ACTIVO_1_APROBADO
            ]);
    }
/*
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
*/
    private function checkNumeroRegistrosActivosCiudador($cuidador)
    {
        $complementoCuidador = ComplementoCuidador::where("RUT_CUIDADOR", $cuidador->PER_RUN)
            ->where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->get();

        if (!$complementoCuidador) {
            throw new ExcepcionValidacion('No es posible consultar tabla de registros administrativos');
        }

        if (count($complementoCuidador) >= 3) {
            throw new ExcepcionReglaNegocio('No puede existir más de 3 registros activos como Cuidador');
        }
    }

    private function checkNumeroRegistrosActivosCiudado($cuidado)
    {
        $complementoCuidado = ComplementoCuidador::where("RUT_CUIDADOR", $cuidado->PER_RUN)
            ->where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->get();

        if (!$complementoCuidado) {
            throw new ExcepcionValidacion('No es posible consultar tabla de registros administrativos');
        }

        if (count($complementoCuidado) >= 3) {
            throw new ExcepcionReglaNegocio('No puede existir más de 3 registros activos como Cuidador');
        }
    }

/*
    public function agendado()
    {
      return null;
    }
*/
    private function checkCiudadorMayorEdad($cuidador)
    {
        if ($cuidador->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
            throw new ExcepcionValidacion('La persona cuidadora debe ser mayor de edad.');
        }
    }
    private function checkSoloUnPrincipalPorCuidado($cuidado)
    {
        $complementoCuidado = ComplementoCuidador::where("RUT_CUIDADO", $cuidado->PER_RUN)
            ->where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('PRINCIPAL', True)
            ->get();

        if (!$complementoCuidado) {
            throw new ExcepcionValidacion('No es posible consultar tabla de registros administrativos');
        }

        if (count($complementoCuidado) >= 1) {
            throw new ExcepcionReglaNegocio('No puede existir más de Cuidador principal por cada persona Cuidada');
        }
    }
    private function checkV1Cuidado($cuidado)
    {
        $gfCuidado = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($cuidado->PER_RUN,false);
        if (is_null($gfCuidado)
            || in_array($gfCuidado->GRUPFAMI_TIPOVIVIENDA, Constante::$RESPUESTA_V1_8_A_11_VIVIENDA_NO_VALIDA)) {
            throw new ExcepcionReglaNegocio('Una persona cuidada no puede tener respuesta V1 en 8, 9, 10 o 11',500);
        }
    }
}
