<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\Personas;
use App\Models\MatriculaEducacionSuperior;
use App\Models\InstitucionEducacionSuperior;

class ActualizacionEducacionSuperior extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR;
    public $tituloFormulario = '';
    public $nombreSolicitud = 'Solicitud de Actualización de Educación Superior';

    protected $matriculaEducacionSuperior;
    protected $institucionEducacionSuperior;
    public $educacionSuperior;

    public function editarSolicitud()
    {
        // TODO: Implement editarSolicitud() method.
    }

    public function enDigitacion()
    {
        // TODO: Implement enDigitacion() method.
    }

    public function pendienteDocumentos()
    {
        // TODO: Implement pendienteDocumentos() method.
    }

    public function pendienteResolver()
    {
        // TODO: Implement pendienteResolver() method.
    }

    public function devolverGestion()
    {
        // TODO: Implement devolverGestion() method.
    }

    public function aprobar()
    {
        // TODO: Implement aprobar() method.
    }

    public function desistir()
    {
        // TODO: Implement desistir() method.
    }

    public function rechazar()
    {
        // TODO: Implement rechazar() method.
    }

    protected function cargarDataTramite()
    {

        $idSubTipoSolicitud = $this->solicitud->ID_SUB_TIPO_SOLICITUD;

        $motivoMatriculaEducacionSuperior = '';
        if ($idSubTipoSolicitud == Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_1_INGRESO) {
            $motivoMatriculaEducacionSuperior =
                Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_STRING_INGRESO;
        } else {
            if ($idSubTipoSolicitud == Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_2_RETIRO_O_EGRESO) {
                $motivoMatriculaEducacionSuperior = Constante::SUB_TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR_STRING_RETIRO_O_EGRESO;
            }
        }
        $this->matriculaEducacionSuperior =
            MatriculaEducacionSuperior::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->where('motivo', $this->solicitud->ID_SUB_TIPO_SOLICITUD)
            ->first();
        if (!($this->matriculaEducacionSuperior)) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de la Matricula de Educacion Superior.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }
        $this->institucionEducacionSuperior =
            InstitucionEducacionSuperior::where('codigo_de_institucion', $this->matriculaEducacionSuperior->COD_INST)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        if (!($this->institucionEducacionSuperior)) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de las Instituciones de Educacion Superior.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }

        $this->educacionSuperior = [
            'run-titular' => $this->matriculaEducacionSuperior->RUN_ALU,
            'mes' => $this->matriculaEducacionSuperior->FEC_ING_EGR_RET,
            'motivo' => [
                'id' => $idSubTipoSolicitud,
                'tipo' => $motivoMatriculaEducacionSuperior
            ],
            'matricula' => [
                'id' => $this->matriculaEducacionSuperior->MATRICULA_ED_SUP_ID,
                'cod-institucion' => $this->institucionEducacionSuperior->CODIGO_DE_INSTITUCION,
                'nombre-institucion' => $this->institucionEducacionSuperior->NOMBRE_INSTITUCION,
                'tipo-institucion' => $this->institucionEducacionSuperior->TIPO_DE_INSTITUCION,
                'carrera' => $this->matriculaEducacionSuperior->NOMB_CARRERA,
                'anio-ingreso' => $this->matriculaEducacionSuperior->ANIO_ING_CARRERA
            ]
        ];
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
    protected function guardarSolicitud(array $data)
    {
    }
    public function actualizarRegistrosAprobacion()
    {
    }
    public function agendado()
    {
        return null;
    }
}
