<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\Personas;
use App\Models\Propiedad;
use App\Sequence;
use Illuminate\Support\Facades\DB;
use Throwable;

class ComplementoBienRaiz extends BienRaiz
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ;
    public $nombreSolicitud = 'en Comunidad / Bien Raíz';
    public $tituloFormulario = "Solicitud de Complemento de Propiedad";
    public $propietarios;

    public function editarSolicitud()
    {
        $this->validarEditarSolicitud();

        $rol = $this->input->rol;
        $runIntegrante = $this->input->get('run-titular');

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([
            'run-integrante' => $runIntegrante,
            'bien-raiz' => $this->obtenerBienRaiz($rol)
        ]);
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
        // TODO: Implement agendado() method.
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
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    protected function cargarDataTramite()
    {
        $this->propietarios = Personas::join('ever_sii_propiedades_det', 'PERSONAS.PER_RUN', '=',
            'ever_sii_propiedades_det.RUT')
            ->join(
                'BENEFICIARIOS_GRUPOSFAMILIARES',
                'BENEFICIARIOS_GRUPOSFAMILIARES.BNFGRUPFAMI_PERSONA',
                '=',
                'PERSONAS.PER_ID'
            )
            ->join('AB_PARENTESCO', 'AB_PARENTESCO.ID', '=', 'BENEFICIARIOS_GRUPOSFAMILIARES.BNFGRUPFAMI_PARENTESCO')
            ->where('BENEFICIARIOS_GRUPOSFAMILIARES.BNFGRUPFAMI_VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->where('BENEFICIARIOS_GRUPOSFAMILIARES.BNFGRUPFAMI_CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('ever_sii_propiedades_det.IDSOLICITUD', $this->solicitud->ID_SOLICITUD)
            ->select(['PERSONAS.PER_RUN as rut', 'PERSONAS.PER_DV as dv','PERSONAS.PER_NOMBRES as nombres',
                'PERSONAS.PER_APELLIDOPATERNO as apellido_paterno','PERSONAS.PER_APELLIDOMATERNO as apellido_materno',
                'AB_PARENTESCO.NOMBRE as parentesco', 'EVER_SII_PROPIEDADES_DET.PORCENTAJE as porcentaje',
                'EVER_SII_PROPIEDADES_DET.ROL as rol','EVER_SII_PROPIEDADES_DET.ROL_OFUSCADO as rol_ofuscado',
                'EVER_SII_PROPIEDADES_DET.DIRECCION as direccion',
                'EVER_SII_PROPIEDADES_DET.DIRECCION_OFUSCADA as dir_ofucada'])
            ->get();

        if ( !($this->propietarios) ) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de complemento propiedad compartida.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
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
        $bienRaiz = $data['bien-raiz'];
        $runIntegrante = $data['run-integrante'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = $nuevoFolio;
        $datoActual = $nuevoFolio;

        DB::beginTransaction();
        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $propietarios = $this->input->propietarios;
            foreach ($propietarios as $propietario) {
                $bienraizNew = null;
                if (empty($this->propiedad)) {
                    $bienraizNew = $bienRaiz->replicate();
                    unset($bienraizNew->RN);
                    $id_propiedad = Sequence::nextValue('seq_propiedadpk');
                    $bienraizNew->sii_prop_det_id = $id_propiedad;
                } else {
                    foreach ($this->propiedad as $prop) {
                        if ($prop->rut == $propietario->run) {
                            $bienraizNew = $prop;
                            break;
                        }
                    }
                }
                $bienraizNew->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
                $bienraizNew->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                $bienraizNew->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                $bienraizNew->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;;
                $bienraizNew->RUT = $propietario->RUN;
                $bienraizNew->PORCENTAJE = $propietario->PARTICIPACION;
                $bienraizNew->save();
            }

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = 1; // para solicituar adjuntar los documentos
            $this->solicitud->RUN_INTEGRANTE = $runIntegrante;

            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    protected function actualizarRegistrosAprobacion()
    {
        $rol = Propiedad::where('idsolicitud', $this->solicitud->id_solicitud)
            ->get();

        $sqlUpdate1 = Propiedad::where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('rol', $rol[0]->rol)
            ->update(['vigente' => Constante::VIGENTE_2_PENDIENTE]);

        $sqlUpdate2 = Propiedad::where('idsolicitud', $this->solicitud->id_solicitud)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }

    protected function validarEditarSolicitud()
    {
        $errores = [];
        if (!empty($this->propietarios)) {
            foreach ($this->propietarios as $propietarioSolicitud) {
                $valido = false;
                foreach ($this->input->propietarios as $propietarioInput) {
                    if ($propietarioSolicitud->rut == $propietarioInput->run) {
                        $valido = true;
                    }
                }
                if (!$valido) {
                    $errores[] = 'El propietario ' . $propietarioInput->run . ' no es valido para esta solicitud';
                }
            }
        }

        $suma = 0;
        foreach ($this->input->propietarios as $propietario) {
            $suma += $propietario->participacion;
        }
        if ($suma > 100) {
            $errores[] = 'La suma de los porcentajes de participación no debe ser mayor que el 100%';
        }
        if (count($errores) > 0) {
            throw new ExcepcionReglaNegocio($errores);
        }
    }

    protected function obtenerBienRaiz($rol)
    {
        $bienraiz = Propiedad::where('rol', $rol)
            ->where('vigente', 1)
            ->where('calculoactivo', 1)
            ->first();

        if (empty($bienraiz)) {
            throw new ExcepcionReglaNegocio('No se encuentra el bien raíz.',Constante::ERR_NEGOCIO_NO_SE_ENCUENTRA_EL_BIEN_RAIZ);
        }
        return $bienraiz;
    }

    public function agendado()
    {
        // TODO: Implement agendado() method.
        return null;
    }
}
