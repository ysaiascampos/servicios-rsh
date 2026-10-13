<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\ActualizacionMeses;
use App\Models\Comuna;
use App\Models\DetalleTasacionVehiculos;
use App\Models\Objeto;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;
use Throwable;

class ActualizacionPropiedadVehiculo extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL;
    public $tituloFormulario = 'Solicitud de Actualización de Propiedad';
    public $nombreSolicitud = 'de Vehículo Terreste/Marítimo';

    public $vehiculo;
    protected $periodoActual;

    public function __construct(TramiteInput $input = null)
    {
        parent::__construct($input);
        if (!empty($input)) {
            $this->periodoActual = $this->obtenerPeriodo();
        }
    }

    protected function obtenerPeriodo()
    {
        $periodo = ActualizacionMeses::orderBy('PERIODO_RSH', 'desc')
            ->first();
        return $this->formatearPeriodo($periodo);
    }

    protected function formatearPeriodo(ActualizacionMeses $periodo)
    {
        $out = (object)[
            'periodo' => $periodo->PERIODO_RSH,
            'meses' => []
        ];
        for ($i = 1; $i <= 12; $i++) {
            $out->meses[$i] = (object)[
                'anio' => substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 0, -2),
                'nMes' => intval(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4)),
                'mes' => Utiles::obtenerMes(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4))
            ];
        }
        return $out;
    }

    protected function cargarDataTramite()
    {
        $this->vehiculo = DetalleTasacionVehiculos::where('veh_det_idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if ($this->vehiculo) {
            $this->vehiculo->MOTIVO = Constante::$SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_STRING[$this->vehiculo->C_TACTUALIZACION_VEHICULO];
            $this->vehiculo->PERIODO_VENTA_ANIO = substr($this->vehiculo->VEH_VENTA_PERIODO, 0, -2);
            $this->vehiculo->PERIODO_VENTA_MES = intval(substr($this->vehiculo->VEH_VENTA_PERIODO, 4));
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
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $this->agregarFirmante($this->titular);
        }
        $this->agregarPersonasAsociadas($this->titular);
    }
    protected function guardarSolicitud(array $data)
    {
        $vehiculo = $data['vehiculo'];
        $runIntegrante = $data['runIntegrante'];
        $idMotivo = $data['idMotivo'];
        $periodo = $data['periodo'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = null;
        $datoActual = $vehiculo->VEH_ID;

        DB::beginTransaction();
        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $vehiculo_new = null;
            if (empty($this->vehiculo)) {
                $vehiculo_new = $vehiculo->replicate();
                unset($vehiculo_new->RN);
                $vehiculo_new->VEH_ID = Sequence::nextValue('seq_detalle_vehiculos_pk');
            } else {
                $vehiculo_new = $this->vehiculo;
                unset($vehiculo_new->MOTIVO);
                unset($vehiculo_new->PERIODO_VENTA_ANIO);
                unset($vehiculo_new->PERIODO_VENTA_MES);
            }

            $this->solicitud->RUN_INTEGRANTE = $runIntegrante;
            $this->solicitud->DATO_NUEVO = $vehiculo_new->VEH_ID;
            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idMotivo; // para solicitar adjuntar los documentos

            $vehiculo_new->C_TACTUALIZACION_VEHICULO = $idMotivo;
            $vehiculo_new->VEH_DET_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $vehiculo_new->VEH_DET_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $vehiculo_new->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $vehiculo_new->VEH_VENTA_PERIODO = $periodo;
            $vehiculo_new->VEH_DET_PERIODO = $this->periodoActual->periodo;
            $vehiculo_new->VEH_DET_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $vehiculo_new->C_TRECTIFICACION_VEHICULO = null;
            $vehiculo_new->save();

            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();
            $this->configurarCargaInformacionDeTramite();
            DB::commit();

        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function editarSolicitud()
    {
        $this->validarPendienteDocumentos();

        $runIntegrante = $this->titular->PER_RUN;
        $idVehiculo = $this->input->get('id-vehiculo');
        $periodo = $this->input->anio . str_pad($this->input->mes, 2, '0', STR_PAD_LEFT);
        $motivo = $this->input->motivo;
        $idMotivo = array_search($motivo,
            Constante::$SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_STRING);

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([
            'runIntegrante' => $runIntegrante,
            'periodo' => $periodo,
            'idMotivo' => $idMotivo,
            'vehiculo' => $this->obtenerVehiculo($idVehiculo)
        ]);
        $this->generarPdfFirmaSimple();
    }
    protected function validarPendienteDocumentos()
    {
        $errors = [];
        $mes = $this->input->mes;
        $anio = $this->input->anio;
        $anioEnCurso = intval(substr($this->periodoActual->periodo, 0, -2));
        $mesEnCurso = intval(substr($this->periodoActual->periodo, 4));

        if ($anio > $anioEnCurso) {
            $this->collectError('Año no debe ser superior al del periodo actual: ' . $mesEnCurso . '-' . $anioEnCurso . '.', Constante::ERR_NEGOCIO_VALOR_ANIO_REQUERIDO);
        }

        if ($anioEnCurso == $anio) {
            if ($mesEnCurso < $mes) {
                $this->collectError('Mes no debe ser superior al del periodo actual: ' . $mesEnCurso . '-' . $anioEnCurso . '.', Constante::ERR_NEGOCIO_VALOR_MES_REQUERIDO);
            }
        }

        $solicitudVehiculo = solicitud::where('run_integrante', $this->titular->PER_RUN)
            ->where('dato_actual', $this->input->get('id-vehiculo'))
            ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL)
            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->get();
        if(isset($this->solicitud->ID_SOLICITUD)){
            $solicitudVehiculo = $solicitudVehiculo->where('ID_SOLICITUD','<>',$this->solicitud->ID_SOLICITUD);
        }

        if ($solicitudVehiculo->count() > 0) {
            $this->collectError('Ya hay una solicitud de actualización en curso para el vehículo seleccionado', Constante::ERR_NEGOCIO_VALOR_SOLICITUD_EN_CURSO_REQUERIDO);
        }

        if ($this->errors) {
            throw new ExcepcionReglaNegocio($this->errors);
        }
    }

    protected function obtenerVehiculo($idVehiculo)
    {
        $vehiculo = DetalleTasacionVehiculos::where('veh_id', $idVehiculo)
            ->where('veh_det_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('veh_det_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('fuente', Constante::FUENTE_9_AFC_DEPENDIENTE_Y_ISAPRE_INDEPENDIENTE)
            ->first();
        if (empty($vehiculo)) {
            throw new ExcepcionReglaNegocio('No se encuentra el vehículo para realizar la solicitud',Constante::ERR_NEGOCIO_VALOR_VEHICULO_NO_ENCONTRADO);
        }
        return $vehiculo;
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
    public function actualizarRegistrosAprobacion(){
        $veh_id = Solicitud::where('id_solicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        $sqlUpdate1 = DetalleTasacionVehiculos::where('veh_id', $veh_id->DATO_ACTUAL)
            ->update([
                'veh_det_vigente' => Constante::VIGENTE_2_PENDIENTE,
                'veh_det_calculoactivo' => Constante::CALCULO_ACTIVO_2_PENDIENTE
            ]);

        $sqlUpdate2 = DetalleTasacionVehiculos::where('veh_id', $veh_id->DATO_ACTUAL)
            ->update([
                'veh_det_vigente' => Constante::VIGENTE_1_VIGENTE,
                'veh_det_calculoactivo' => Constante::CALCULO_ACTIVO_1_APROBADO
            ]);
    }

    public function agendado(){
        return null;
    }
}
