<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\Comuna;
use App\Models\Personas;
use App\Models\Propiedad;
use App\Models\RJuridicos;
use App\Sequence;
use Illuminate\Support\Facades\DB;
use Throwable;

class ActualizacionBienRaiz extends BienRaiz
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ;
    public $tituloFormulario = '';
    public $nombreSolicitud = 'Solicitud de Actualización de Propiedad de Bien Raíz';

    public $glosaDatosBeneficiario = "Solicito actualizar Información de Bien Raíz de: ";
    public $glosaFooterFormulario = 'Con los antecedentes aportados solicito la Actualización de Información del Registro Social de Hogares';



    protected function cargarDataTramite()
    {
        parent::cargarDataTramite();

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
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $this->agregarFirmante($this->titular);
        }
        $this->agregarPersonasAsociadas($this->titular);
    }
    protected function guardarSolicitud(array $data)
    {
        $bienRaiz = $data['bien-raiz'];
        $runIntegrante = $data['run-integrante'];
        $runJuridico = $data['run-juridico'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = $nuevoFolio;
        $datoActual = $nuevoFolio;

        DB::beginTransaction();
        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $bienraiz_new = null;
            if (empty($this->propiedad)) {
                $bienraiz_new = $bienRaiz->replicate();
                unset($bienraiz_new->RN);
                $id_propiedad = Sequence::nextValue('seq_propiedadpk');
                $bienraiz_new->SII_PROP_DET_ID = $id_propiedad;
            } else {
                $bienraiz_new = $this->propiedad->first();
            }

            $bienraiz_new->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $bienraiz_new->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $bienraiz_new->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $bienraiz_new->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $bienraiz_new->RUT = $runIntegrante;
            $bienraiz_new->R_JURIDICO = $runJuridico;
            $bienraiz_new->save();

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = 1; // para solicituar adjuntar los documentos
            $this->solicitud->RUN_INTEGRANTE = $runIntegrante;

            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            $this->configurarCargaInformacionDeTramite();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }

        /*try {
            SolicitudController::enviaMailRecepcionSolicitud($this->solicitud);
        } catch (Exception $e) {
            Log::warning($e);
        }*/

    }
    public function editarSolicitud()
    {
        $rol = $this->input->rol;
        $runIntegrante = $this->titular->PER_RUN;
        $runJuridico = $this->input->{'run-juridico'};

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([
            'run-integrante' => $runIntegrante,
            'bien-raiz' => $this->obtenerBienRaiz($rol,$runIntegrante),
            'run-juridico' => $runJuridico
        ]);
        $this->generarPdfFirmaSimple();
    }

    protected function obtenerBienRaiz($rol, $runIntegrante)
    {
        $RJuridicos= RJuridicos::where('run_socio', $runIntegrante)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->select('run_sociedad')
            ->get();
        $runes[] = $runIntegrante;
        foreach ($RJuridicos as $run){
            $runes[] = $run->run_sociedad;
        }
        $bienraiz = Propiedad::where('rol', $rol)
            ->whereIn('rut', $runes)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();

        if (is_null($bienraiz)) {
            throw new ExcepcionReglaNegocio('Error administrativo, No se encuentra el bien raíz.',Constante::ERR_NEGOCIO_VALOR_BIEN_RAIZ_NO_ENCONTRADO);
        }
        return $bienraiz;
    }

    public function enDigitacion()
    {
        return $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        return $this->editarSolicitud();
    }

    public function pendienteResolver()
    {
        $vigenteActual = $this->solicitud->VIGENTE;
        if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
            throw new ExcepcionReglaNegocio('No se han adjuntado todos los documentos requeridos.',
                Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS);
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

    public function devolverGestion()
    {
        if (empty($this->observacion)) {
            throw new ExcepcionReglaNegocio('El campo observacion es requerido.',
                Constante::ERR_NEGOCIO_CAMPO_DESCRIPCION_OBLIGATORIO);
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

    public function agendado(){
        return null;
    }

    public function actualizarRegistrosAprobacion(){
        $rol = Propiedad::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->select('rol')
            ->first();

        $sqlUpdate1 = Propiedad::where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('rol', $rol->ROL)
            ->update(['vigente' => Constante::VIGENTE_2_PENDIENTE]);

        $sqlUpdate2 = Propiedad::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

}
