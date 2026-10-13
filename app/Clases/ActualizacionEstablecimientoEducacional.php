<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\Comuna;
use App\Models\Personas;
use App\Models\GrupoFamiliar;
use App\Models\Colegio;
use App\Models\Ficha;
use App\Models\Educacion;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActualizacionEstablecimientoEducacional extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL;
    public $tituloFormulario = 'Solicitud de Actualización de Matrícula';
    public $nombreSolicitud = 'en Establecimiento Educacional';
    protected $colegio;
    protected $grupoFamiliar;
    protected $persona;
    protected $ficha;
    public $establecimientoEducacional;

    protected function cargarDataTramite()
    {
        $this->colegio = Colegio::where('run_alu', $this->solicitud->RUN_INTEGRANTE)
            ->where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if (!($this->colegio)) {
            throw new ExcepcionConsistencia(
                'Error administrativo, no se encontró la información de la Actualizaciónm de Educación.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $this->establecimientoEducacional['colegio'] = $this->colegio;
        $this->grupoFamiliar = GrupoFamiliar::findBySolicitud($this->solicitud, 'DATO_NUEVO');
        if (!($this->grupoFamiliar)) {
            throw new ExcepcionConsistencia(
                'Error administrativo, no se encontró la información del Grupo Familiar.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $this->establecimientoEducacional['grupoFamiliar'] = $this->grupoFamiliar;
        $this->persona = Utiles::getPersona($this->colegio->RUN_ALU);
        if (!($this->persona)) {
            throw new ExcepcionConsistencia(
                'Error administrativo, no se encontró la información de la persona.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $this->establecimientoEducacional['persona'] = $this->persona;
        $this->ficha = Ficha::findByIdPersona($this->persona->PER_ID);
        if (!($this->ficha)) {
            throw new ExcepcionConsistencia(
                'Error administrativo, no se encontró la información de la ficha.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $this->establecimientoEducacional['ficha'] = $this->ficha;
    }

    protected function consultarTitular()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $runTitular = $this->solicitud->RUN_INTEGRANTE;
        } else {
            $runTitular = $this->solicitud->RUN_CIUDADANO;
        }
        $this->titular = Personas::findByRun($runTitular);
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

    protected function obtenerPersonasAsociadas()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $this->agregarFirmante($this->titular);
        }
        $this->agregarPersonasAsociadas($this->titular);
    }

    public function editarSolicitud()
    {
        $data = $this->generaData();
        $this->validaciones($data);
        $this->verificarFirmaSimple();
        $this->guardarSolicitud($data);
        $this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->editarSolicitud();
    }

    protected function generaData()
    {
        $data = [];
        $data['subtipo'] = $this->input->get('subtipo');
        $data['run-titular'] = $this->titular->PER_RUN;
        $data['anio'] = null;
        $data['mes'] = null;

        switch ($data['subtipo']) {
            case Constante::SUB_TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL_1:
                $data['anio'] = $this->input->get('anio');
                $data['mes'] = $this->input->get('mes');
                $data['rbd-actual'] = $this->input->get('rbd-actual');
                if (!is_null($data['rbd-actual'])) {
                    $educa = Educacion::where('educacion_rbd_cod', $data['rbd-actual'])
                        ->where('educacion_rbd_vigente', 1)
                        ->first();
                    if ($educa) {
                        $data['nombre-actual'] = $educa->EDUCACION_RBD_NOM;
                    } else {
                        $data['nombre-actual'] = null;
                    }
                } else {
                    $data['nombre-actual'] = null;
                }
                break;
        }

        return $data;
    }

    private function validaciones($data)
    {
        if (!$data['subtipo']) {
            throw new ExcepcionReglaNegocio(
                'El campo subtipo es obligatorio.',
                Constante::ERR_NEGOCIO_VALOR_SUB_TIPO_REQUERIDO
            );
        }
        if (!$data['run-titular']) {
            throw new ExcepcionReglaNegocio(
                'El campo run-titular es obligatorio.',
                Constante::ERR_NEGOCIO_VALOR_RUN_TITULAR_REQUERIDO
            );
        }

        switch ($data['subtipo']) {
            case Constante::SUB_TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL_1:
                if (!$data['rbd-actual'] && $this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                    throw new ExcepcionReglaNegocio(
                        'El campo rbd actual es obligatorio.',
                        Constante::ERR_NEGOCIO_VALOR_RBD_ACTUAL_REQUERIDO
                    );
                }
                if (!$data['nombre-actual'] && $this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                    throw new ExcepcionReglaNegocio(
                        'El nombre de establecimiento educacional no fue ubicado. Verifique datos de RBD',
                        Constante::ERR_NEGOCIO_VALOR_ESTABLECIMIENTO_EDUCACIONAL_NO_ENCONTRADO
                    );
                }
                if (!$data['mes'] || !$data['anio']) {
                    throw new ExcepcionReglaNegocio(
                        'Debe indicar mes y año del cambio o retiro.',
                        Constante::ERR_NEGOCIO_VALOR_MES_Y_ANIO_REQUERIDO
                    );
                }
                break;
        }
    }

    protected function crearDataTramite($data)
    {
        $colegioOriginal = Colegio::where('run_alu', $this->titular->PER_RUN)
            ->where('vigente', 1)
            ->where('calculoactivo', 1)
            ->first();

        if (is_null($colegioOriginal)) {
            throw new ExcepcionReglaNegocio(
                'Error, no fue posible ubicar los registros admninistrativos correspondientes.',
                Constante::ERR_NEGOCIO_VALOR_COLEGIO_NO_ENCONTRADO
            );
        }

        if (!is_null($this->solicitud)) {
            try {
                $this->colegio = $colegioOriginal->replicate();
                unset($this->colegio->RN);
                $this->colegio->MATRIC_ID = Sequence::nextValue('SEQ_AB_COMPLEMENTO_EDUCACION');
                $this->colegio->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                $this->colegio->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                $this->colegio->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                $this->colegio->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
                $this->colegio->C_TACTUALIZACION_EDUCACIONAL = $data['subtipo'];

                switch ($this->input->get('subtipo')) {
                    case Constante::SUB_TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL_1:
                        $this->colegio->RBD = $data['rbd-actual'];
                        $this->colegio->NOMBRE_ESTA = $data['nombre-actual'];
                        $this->colegio->FECHA_CAMBIO = $data['anio'] . $data['mes'] . "01";
                        break;
                }

                $this->colegio->save();
            } catch (Throwable $ex) {
                Log::error('Actualización Educación : ' . $ex);
                throw new ExcepcionEjecucion(
                    'Error al grabar actualización Educación',
                    Constante::ERR_EJECUCION_ERROR_CONSULTA_SQL
                );
            }
        } else {
            throw new ExcepcionEjecucion(
                'Error al crear solicitud de actualización educación',
                Constante::ERR_EJECUCION_ERROR_CONSULTA_SQL
            );
        }
    }

    protected function actualizaDataTramite($data)
    {
        if (!is_null($this->colegio)) {
            $this->colegio->C_TACTUALIZACION_EDUCACIONAL = $data['subtipo'];

            switch ($this->input->get('subtipo')) {
                case Constante::SUB_TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL_1:
                    $this->colegio->RBD = $data['rbd-actual'];
                    $this->colegio->NOMBRE_ESTA = $data['nombre-actual'];
                    $this->colegio->FECHA_CAMBIO = $data['anio'] . $data['mes'] . "01";
                    break;
            }

            $this->colegio->save();
        }
    }



    public function pendienteDocumentos()
    {
        $this->editarSolicitud();
    }

    public function devolverGestion()
    {
        if (empty($this->observacion)) {
            throw new ExcepcionReglaNegocio(
                'El campo observacion es requerido.',
                Constante::ERR_NEGOCIO_CAMPO_DESCRIPCION_OBLIGATORIO
            );
        }
        DB::beginTransaction();
        try {
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion]['estado'];
            $this->solicitud->OBS_SOLICITUD = $this->observacion;
            $this->solicitud->save();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function pendienteResolver()
    {
        $vigenteActual = $this->solicitud->VIGENTE;
        if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
            throw new ExcepcionReglaNegocio(
                'No se han adjuntado todos los documentos requeridos.',
                Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS
            );
        }
        $this->editarSolicitud();

        DB::beginTransaction();
        try {
            if (!self::esMunicipal() && !self::esChileAtiende()) {
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
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }

        /*$vigenteNuevo = $this->solicitud->VIGENTE;
        if ($vigenteActual == Constante::VIGENTE_3_SOLICITUD_BORRADOR &&
            $vigenteNuevo == Constante::VIGENTE_1_VIGENTE
        ) {
            $metodo = 'POST';
            $url = '/enviarmail';
            $datosformulario = array('idsolicitud' => $this->solicitud->ID_SOLICITUD);
            try {
                Utiles::enviandoCorreo($metodo, $url, $datosformulario, 5);
            } catch (Throwable $ex) {
                Log::error($ex->getMessage() . ' Function->pendienteResolver(ArcTramite), Error al enviar correo de notificación para solicitud: ' .$this->solicitud->id_solicitud);
            }
        }*/
    }

    protected function guardarSolicitud(array $data)
    {
        DB::beginTransaction();
        try {
            if (is_null($this->solicitud)) {
                $grupoFamiliar = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitante->PER_RUN, false);
                if ($grupoFamiliar->count() == 0) {
                    throw new ExcepcionReglaNegocio('Error administrativo, no se encontra grupo familiar vigente y activo.', Constante::ERR_NEGOCIO_ERROR_NO_SE_ENCONTRA_GRUPO_FAMILIAR_VIGENTE_Y_ACTIVO);
                }
                $dato_nuevo = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $dato_actual = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $gf_nuevo_folio = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $this->obtenerRegistroSolicitud($dato_nuevo, $dato_actual, $gf_nuevo_folio);

                $this->crearDataTramite($data);
                //cambio de la solucitud segun caso correspondiente
                $this->solicitud->ID_SUB_TIPO_SOLICITUD = $this->input->get('subtipo'); // para solicituar adjuntar los documentos
                $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
                $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            } else {
                $this->actualizaDataTramite($data);
            }

            $this->solicitud->save();
            $this->configurarCargaInformacionDeTramite();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function actualizarRegistrosAprobacion()
    {
        $sqlUpdate1 = Colegio::where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('RUN_ALU', $this->solicitud->RUN_CIUDADANO)
            ->update(['VIGENTE' => Constante::VIGENTE_2_PENDIENTE]);

        $sqlUpdate2 = Colegio::where('IDSOLICITUD', $this->solicitud->ID_SOLICITUD)
            ->update(['VIGENTE' => Constante::VIGENTE_1_VIGENTE]);
    }

    public function agendado()
    {
        return null;
    }
}
