<?php


namespace App\Clases;

use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\ActualizacionIngreso as IngresosLaboral;
use App\Models\ActualizacionMeses;
use App\Models\Comuna;
use App\Models\Personas;
use App\Models\FuenteExport;
use App\Sequence;
use App\Utiles;
use App\Constantes\Constante;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Http\Inputs\TramiteInput;
use App\Models\Solicitud;


abstract class ActualizacionIngreso extends TramiteArc
{
    public $periodoSolicitud;
    public $ingresos;
    public $fuentes;
    protected $periodoActual;

    public function __construct(TramiteInput $input = null)
    {
        parent::__construct($input);
        $this->fuentes = DatosDeFuentes::getListaFuentes();
        if (!empty($input)) {
            $this->periodoActual = $this->obtenerPeriodo();
        }
    }

    protected function cargarDataTramite()
    {
        $this->ingresos = IngresosLaboral::where('idsolicitud', $this->solicitud->ID_SOLICITUD)->get();
        if (count($this->ingresos) > 0 && ($this->ingresos[0] instanceof IngresosLaboral)) {
            $this->periodoSolicitud = $this->obtenerDetallePeriodo($this->ingresos[0]->PERIODO_RSH);

            for ($i = 0; $i < count($this->ingresos); $i++) {
                $this->ingresos[$i]->nombreTipoFuente = $this->obtenerNombreTipoFuente($i);
            }
        }
    }

    protected function obtenerPeriodo()
    {
        $periodo = ActualizacionMeses::orderBy('PERIODO_RSH', 'desc')
            ->first();
        return $this->formatearPeriodo($periodo);
    }

    protected function obtenerDetallePeriodo($periodoRsh)
    {
        $periodo = ActualizacionMeses::where('PERIODO_RSH', $periodoRsh)
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

    protected function obtenerMesesRequeridosPorFuente($indice)
    {
        $fuenteSolicitud = $this->fuentes['tipos-fuentes'][$this->solicitud->ID_FUENTE_ACTUAL];
        $mesesRequeridos = null;
        if ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_2_INDEPENDIENTE) {
            $mesesRequeridos = $fuenteSolicitud['sub-fuentes']['independiente']['nro-meses'];
        } elseif ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_1_DEPENDIENTE) {
            $mesesRequeridos = $fuenteSolicitud['sub-fuentes']['dependiente']['nro-meses'];
        } elseif ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_0_OTRO) {

            if (count($fuenteSolicitud['sub-fuentes']) == 1) {
                $mesesRequeridos = current($fuenteSolicitud['sub-fuentes'])['nro-meses'];
            } else {
                if (($indice + 1) == 1) {
                    $mesesRequeridos = $fuenteSolicitud['sub-fuentes']['pension']['nro-meses'];
                } else {
                    $mesesRequeridos = $fuenteSolicitud['sub-fuentes']['sii']['nro-meses'];
                }
            }
        }
        return $mesesRequeridos;
    }

    protected function obtenerNombreTipoFuente($indice)
    {
        $tipoFuente = null;
        if ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_2_INDEPENDIENTE) {
            $tipoFuente = 'independiente';
        } elseif ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_1_DEPENDIENTE) {
            $tipoFuente = 'dependiente';
        } elseif ($this->ingresos[$indice]->DEP_O_INDEP == Constante::DEP_O_INDEP_0_OTRO) {
            if (in_array($this->solicitud->ID_FUENTE_ACTUAL,
                $this->fuentes['solicitudes'][Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]['fuentes'])) {
                $fuenteSolicitud = $this->fuentes['tipos-fuentes'][$this->solicitud->ID_FUENTE_ACTUAL];
                if (count($fuenteSolicitud['sub-fuentes']) > 1) {
                    // cuando hay 2 registros, primero siempre va AFP/IPS y segundo SII
                    $subFuentePension = ['pension', 'sii'];
                    $tipoFuente = $subFuentePension[$indice];
                } else {
                    $tipoFuente = isset($fuenteSolicitud['sub-fuentes']['pension']) ? 'pension' : 'sii';
                }
            } else {
                // cuando hay 2 registros, primero siempre va AFP/IPS y segundo SII
                $subFuentePension = ['pension', 'sii'];
                $tipoFuente = $subFuentePension[$indice];
            }
        }
        return $tipoFuente;
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

    protected function obtenerPersonasAsociadas()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $this->agregarFirmante($this->titular);
        }
        $this->agregarPersonasAsociadas($this->titular);
    }

    public function configurarCargaInformacionDeTramite()
    {
        $this->consultarTitular();
        $this->cargarDataTramite();
        $this->obtenerPersonasAsociadas();
    }

    protected function guardarSolicitud(array $data)
    {
        $fuenteActual = $data['fuente'];
        $idSubTipoSolicitud = $data['subTipoSolicitud'];
        $registrosIngreso = $data['ingresos'];

        DB::beginTransaction();
        try {
            $nuevoFolio = $this->obtenerNuevoFolio();
            $datoNuevo = $nuevoFolio;
            $datoActual = $nuevoFolio;
            if (is_null($this->solicitud)) {
                if (!in_array($fuenteActual, $this->fuentes['solicitudes'][$this->tipoSolicitud]['fuentes'])) {
                    throw new ExcepcionReglaNegocio('No se encontro fuente de ingreso valida para este tramite',
                        Constante::ERR_NEGOCIO_FUENTE_NO_VALIDA);
                }
            }

            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idSubTipoSolicitud;
            if (is_null($this->solicitud->ID_FUENTE_ACTUAL)) {
                $this->solicitud->ID_FUENTE_ACTUAL = $fuenteActual;
                $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
            }
            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            foreach ($registrosIngreso as $ingreso) {

                if (is_null($ingreso)) {
                    continue;
                }
                unset($ingreso->nombreTipoFuente);
                $ingreso->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
                $ingreso->save();
            }
            $this->configurarCargaInformacionDeTramite();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function editarSolicitud()
    {
        $this->validacionGuardadoSolicitud();
        $this->validarCamposRequeridos();
        if (is_null($this->solicitud)) {
            $fuente = $this->obtenerFuente();
            $fuenteIngreso = $fuente->id;
        } else {
            $fuenteIngreso = $this->solicitud->ID_FUENTE_ACTUAL;
        }
        $subTipoSolicitud = $this->obtenerSubTipoSolicitud();

        $ingresos = [
            $this->obtenerRegistroIngreso(
                $this->input->independiente,
                [
                    'dependiente-independiente' => Constante::DEP_O_INDEP_2_INDEPENDIENTE,
                    'nro-meses' => $this->fuentes['tipos-fuentes'][$fuenteIngreso]['sub-fuentes']['independiente']['nro-meses']
                ]
            ),
            $this->obtenerRegistroIngreso(
                $this->input->dependiente,
                [
                    'dependiente-independiente' => Constante::DEP_O_INDEP_1_DEPENDIENTE,
                    'nro-meses' => $this->fuentes['tipos-fuentes'][$fuenteIngreso]['sub-fuentes']['dependiente']['nro-meses']
                ]
            )
        ];
        $this->verificarFirmaSimple();
        $this->guardarSolicitud([
            'fuente' => $fuenteIngreso,
            'subTipoSolicitud' => $subTipoSolicitud,
            'ingresos' => $ingresos
        ]);
    }

    private function obtenerSubTipoSolicitud()
    {
        $subTipo = Constante::SUB_TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_1;
        if (in_array($this->tipoSolicitud, [
            Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE,
            Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE
        ])) {
            $subTipo = Constante::SUB_TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_4;
        }
        return $subTipo;
    }

    protected function obtenerRegistroIngreso($arrayIngresosMensuales, array $parametros)
    {
        $parametros = array_replace([
            'dependiente-independiente' => null,
            'posicion' => null,
            'nro-meses' => null
        ], $parametros);

        if (is_null($arrayIngresosMensuales)) {
            return null;
        }

        $registroIngreso = null;
        if (!is_null($this->ingresos)) {
            if (isset($parametros['posicion'])) {
                $registroIngreso = $this->ingresos[$parametros['posicion']];
            } else {
                foreach ($this->ingresos as $registro) {
                    if ($registro->DEP_O_INDEP == $parametros['dependiente-independiente']) {
                        $registroIngreso = $registro;
                    }
                }
            }
        }

        if (is_null($registroIngreso)) {
            $registroIngreso = new IngresosLaboral();
            $registroIngreso->ACTUALIZA_ID = Sequence::nextValue('seq_rsh_ingreso');
            $registroIngreso->PERIODO_RSH = $this->periodoActual->periodo;
            $registroIngreso->RUT = $this->titular->PER_RUN;
            $registroIngreso->DEP_O_INDEP = $parametros['dependiente-independiente'];
            $registroIngreso->FECHAFUENTE = 0;
            $registroIngreso->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $registroIngreso->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $registroIngreso->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $registroIngreso->N_MESES = $parametros['nro-meses'];
        }

        $mesesPeriodo = array_map(function ($m) {
            return $m->nMes;
        }, $this->periodoActual->meses);
        for ($i = 1; $i <= 12; $i++) {
            $registroIngreso->{'MES_' . $i} = null;
        }
        for ($i = 0; $i < count($arrayIngresosMensuales); $i++) {
            $indiceMes = array_search($arrayIngresosMensuales[$i]->mes, $mesesPeriodo);
            $registroIngreso->{'MES_' . $indiceMes} = $arrayIngresosMensuales[$i]->valor;
        }
        return $registroIngreso;
    }

    protected function obtenerFuente()
    {
        $fuente = FuenteExport::join('personas', 'personas.calc_fuente_ingresolaboral', '=', 'fuentes.fue_id')
            ->where('personas.per_run', $this->titular->PER_RUN)
            ->first(['fue_id', 'fue_dependiente', 'fue_independiente']);
        return $this->formatearFuente($fuente);
    }

    protected function formatearFuente($fuente)
    {
        if (is_null($fuente)) {
            throw new ExcepcionConsistencia('La solicitud no registra la fuente.',
                Constante::ERR_CONSISTENCIA_FUENTE_NO_EXISTE);
        }
        return (object)[
            'id' => $fuente->FUE_ID,
            'dependiente' => $fuente->FUE_DEPENDIENTE,
            'independiente' => $fuente->FUE_INDEPENDIENTE
        ];
    }

    private $requiereCamposObligatorios = true;

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
        $this->requiereCamposObligatorios = true;
        $vigenteActual = $this->solicitud->VIGENTE;
        if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
            throw new ExcepcionReglaNegocio('No se han adjuntado todos los documentos requeridos.',
                Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS);
        }
        $this->editarSolicitud();

        DB::beginTransaction();
        try {
            if (!self::esMunicipal() && !self::esChileAtiende()) { // ToDo revisar vigente para no municipal
                $this->solicitud->VIGENTE = Constante::$vigenteSegunAccionNoMunicipal[$this->accion];
            }
            if (self::ingresaAMunicipal()) {
                $this->solicitud->FECHA_CREACION = $this->fechaActual;

                if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE) {
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->titular->PER_RUN,
                        $this->tipoSolicitud);
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
                Log::error($ex->getMessage() .
                    ' Function->pendienteResolver(ArcTramite), Error al enviar correo de notificación para solicitud: '
                    .$this->solicitud->ID_SOLICITUD);
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
            //falta validar
            $this->solicitud->OBS_SOLICITUD = $this->observacion;
            $this->solicitud->save();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function agendado()
    {
        return null;
    }

    public function actualizarRegistrosAprobacion()
    {
        $maxId = Solicitud::where('id_fuente_actual', $this->solicitud->ID_FUENTE_ACTUAL)
            ->where('run_ciudadano', $this->solicitud->RUN_CIUDADANO)
            ->whereNotIn('id_solicitud', [$this->solicitud->ID_SOLICITUD])
            ->max('id_solicitud');

        $sqlUpdate1 = IngresosLaboral::where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('idsolicitud', $maxId)
            ->update(['vigente' => Constante::VIGENTE_2_PENDIENTE]);

        $sqlUpdate2 = IngresosLaboral::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;

        $runTitular = (!is_null($input->{$this->tipoSolicitud})) ?
            $input->{$this->tipoSolicitud}->{'run-titular'} :
            $input->{'run-ciudadano'};

        if ($this->solicitante->PER_RUN == $runTitular) {
            $titular = $this->solicitante;
        } else {
            $titular = Personas::findByRun($runTitular);
        }
        return $titular;
    }

}
