<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\NNARepresentanteMigrante;
use App\Models\Ficha;
use App\Models\FichaMontoMensual;
use App\Models\Personas;
use Illuminate\Support\Facades\Log;
use Throwable;

class IncorporacionIntegrante extends TramiteMultiplesHijos
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE;
    public $nombreSolicitud = 'Solicitud de Incorporación';

    public $firmaJefeHogar = true;

    public function editarSolicitud()
    {
        // TODO: Implement editarSolicitud() method.
    }

    public function enDigitacion()
    {
        // TODO: Implement enDigitacion() method.
    }

    public function agendado()
    {
        return null;
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

    protected function cargarDataTramite()
    {
        $solTieneMontoMensual = FichaIngresoMensual::check(
            $this->solicitud->FECHA_CREACION,
            $this->solicitud->FECHA_CREACION_CIUDADANO
        );

        $tramitable = !in_array($this->solicitud->ID_ESTADO, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);

        $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdPadreSolicitudIncorporacionNuevoIntegrante(
            $this->solicitud->ID_PADRE_SOLICITUD,
            $this->solicitud->ID_TIPO_SOLICITUD,
            $tramitable,
            $solTieneMontoMensual
        );

        if(!is_null($this->beneficiariosSolicitudActual)){
            $this->grupoFamiliarVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitud->RUN_CIUDADANO);
            $this->parentesco=BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliar($this->grupoFamiliarVigente,false,true);
        }

        if (isset($this->solicitud->MIGRANTE) && $this->solicitud->MIGRANTE == 1) {

            $this->nombreSolicitud = 'Solicitud de Incorporación de Niño, Niña o Adolescente ';
        }

        $this->obtenerPreguntasYRespuestasESOV($this->solicitud->ORIGEN_REGISTRO);
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }

    public function aprobar()
    {
        parent::aprobar();
    }

    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionReglaNegocio
     * @throws ExcepcionValidacion
     * @throws Throwable
     */
    protected function actualizarRegistrosAprobacion()
    {
        $integrante = BeneficiarioGrupoFamiliar::findByIdPersonaIdSolicitud(
            $this->solicitud->DATO_NUEVO,
            $this->solicitud->ID_SOLICITUD
        );
        if (empty($integrante)) {
            throw new ExcepcionConsistencia(
                'No se ha podido encontrar el integrante asociado a la solicitud.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $integrante->BNFGRUPFAMI_GRUPOFAMILIAR = $this->grupoFamiliarVigente->GRUPFAMI_ID;
        $integrante->save();

        $per = Personas::findByIdPersona($integrante->BNFGRUPFAMI_PERSONA);
        if (empty($per)) {
            throw new ExcepcionConsistencia(
                'No existe la persona en los registros.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        if ($integrante->BNFGRUPFAMI_PARENTESCO < Constante::PARENTESCO_1_JEFE_DE_HOGAR) {
            throw new ExcepcionValidacion('El integrante no tiene tiene definido el parentesco.');
        }

        $fichaSolicitudEnCurso = Ficha::findByIdSolicitud($this->solicitud->ID_SOLICITUD);
        $solTieneMontoMensual = FichaIngresoMensual::check(
            $this->solicitud->FECHA_CREACION,
            $this->solicitud->FECHA_CREACION_CIUDADANO
        );

        $this->validarFicha($fichaSolicitudEnCurso, $per, $solTieneMontoMensual);

        $fichaMontoMensualSolicitudEnCurso = null;
        if ($solTieneMontoMensual) {
            $fichaMontoMensualSolicitudEnCurso = FichaMontoMensual::findByIdSolicitud($this->solicitud->ID_SOLICITUD);
            if (empty($fichaMontoMensualSolicitudEnCurso)) {
                Log::warning("FichaID: " . $this->solicitud->DATO_NUEVO . " - FichaPersona: " . $this->solicitud->DATO_ACTUAL . " - idSolicitud: " . $this->solicitud->ID_SOLICITUD);
                throw new ExcepcionConsistencia(
                    'No se han podido encontrar los datos del formulario del integrante asociado a la solicitud. Datos Mensuales',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
                );
            }
        }

        $this->validarSoloUnJefeHogar($integrante);

        $resultadoDesvinculacion = DesvinculacionIntegrante::desvinculaIntegrante(
            $integrante->BNFGRUPFAMI_PERSONA,
            $this->solicitud
        );
        if ($resultadoDesvinculacion->estado === 0) {
            throw new ExcepcionReglaNegocio(
                'No se ha podido desvincular al integrante',
                Constante::ERR_NEGOCIO_NO_SE_PUDO_DESVINCULAR_INTEGRANTE
            );
        }
        if ($resultadoDesvinculacion->estado === -1) {
            Log::warning($resultadoDesvinculacion->msg . " FichaID: " . $this->solicitud->DATO_NUEVO . " - FichaPersona: " . $this->solicitud->DATO_ACTUAL . " - idSolicitud: " . $this->solicitud->ID_SOLICITUD);
        }

        $fichaVigente = Ficha::findByIdPersona($fichaSolicitudEnCurso->FICHA_PERSONA);
        if ($fichaVigente != null) {
            $fichaVigente->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $fichaVigente->save();
        }

        $fichaSolicitudEnCurso->FICHA_VIGENTE = Constante::VIGENTE_1_VIGENTE;
        $fichaSolicitudEnCurso->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
        $fichaSolicitudEnCurso->save();

        if ($solTieneMontoMensual) {
            $fichaMontoMensualVigente = FichaMontoMensual::findVigenteByIdPersona($fichaMontoMensualSolicitudEnCurso->MENSUAL_ID_PERSONA);
            if ($fichaMontoMensualVigente != null) {
                $fichaMontoMensualVigente->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                $fichaMontoMensualVigente->save();
            }
            $fichaMontoMensualSolicitudEnCurso->MENSUAL_VIGENTE = Constante::VIGENTE_1_VIGENTE;
            $fichaMontoMensualSolicitudEnCurso->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
            $fichaMontoMensualSolicitudEnCurso->save();
        }

        $integranteDesvinculado = BeneficiarioGrupoFamiliar::findPendienteByIdPersonaIdSolicitud(
            $this->solicitud->DATO_NUEVO,
            $this->solicitud->ID_SOLICITUD
        );
        if ($integranteDesvinculado) {
            $integranteDesvinculado->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $integranteDesvinculado->save();
        }

        $integrante->BNFGRUPFAMI_VIGENTE = Constante::VIGENTE_1_VIGENTE;
        $integrante->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
        $integrante->save();
    }

    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionReglaNegocio
     * @throws ExcepcionValidacion
     */
    public function validacionAprobarSolicitud()
    {
        $this->validarPerfilAprobar();

        if (empty($this->grupoFamiliarVigente)) {
            throw new ExcepcionConsistencia(
                'El grupo familiar actual no se encuentra vigente.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_VIGENTE
            );
        }

        $this->validarIntegranteDuplicado();
        $this->validarAdjuntosRequeridos();
    }

    /**
     * @throws ExcepcionValidacion
     */
    protected function validarIntegranteDuplicado()
    {
        $duplicado = BeneficiarioGrupoFamiliar::findByIdPersonaIdGrupoFamiliar(
            $this->solicitud->DATO_NUEVO,
            $this->grupoFamiliarVigente->GRUPFAMI_ID
        );
        if (!empty($duplicado)) {
            throw new ExcepcionValidacion('El integrante ya se encuentra en el grupo familiar');
        }
    }


    public function validarAdjuntosRequeridos()
    {
        if ($this->requiereDocumentosAdjuntos) {
            $parametros = [
                'integrantes' => [],
                'grupo_familiar_institucionalizado' => $this->grupoFamiliarVigente->GRUPFAMI_INSTITUCIONALIZADO
            ];
            //necesario para chequear documentacion de menores sin padres en grupo familiar
            foreach ($this->beneficiariosSolicitudActual as $integrante) {
                $parametros['integrantes'][] = $integrante->PER_RUN;
            }
            if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud, $parametros)) {
                throw new ExcepcionReglaNegocio(
                    'No se han adjuntado todos los documentos requeridos.',
                    Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS
                );
            }
        }
    }

    /**
     * Valida que sólo exista un jefe de hogar
     * @throws ExcepcionReglaNegocio
     */
    protected function validarSoloUnJefeHogar(BeneficiarioGrupoFamiliar $integrante)
    {
        $jefeHogar = BeneficiarioGrupoFamiliar::where(
            'bnfgrupfami_grupofamiliar',
            $integrante->BNFGRUPFAMI_GRUPOFAMILIAR
        )
            ->where('bnfgrupfami_parentesco', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->get();
        $sum = count($jefeHogar);
        if ($integrante->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR) {
            $sum = $sum + 1;
        }
        if ($sum != 1) {
            throw new ExcepcionReglaNegocio('Debe seleccionar un Jefe de Hogar. Actualmente hay: ' . $sum);
        }
    }

    /**
     * @param Ficha $ficha
     * @param Personas $per
     * @param $solTieneMontoMensual
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     */
    protected function validarFicha(Ficha $ficha, Personas $per, bool $solTieneMontoMensual)
    {
        if ($solTieneMontoMensual) {
            $fichaMontoMensual = FichaMontoMensual::where('mensual_id_persona', $ficha->FICHA_PERSONA)
                ->where('mensual_idsolicitud', $this->solicitud->ID_SOLICITUD)
                ->first();
            if ($fichaMontoMensual) {
                if ($per->PER_EDAD <= Constante::EDAD_15_PUEDE_TRABAJAR) {
                    for ($i = 1; $i <= 12; $i++) {
                        if (!is_numeric($fichaMontoMensual['MENSUAL_Y2_M' . $i])) {
                            throw new ExcepcionValidacion('El integrante debe responder el módulo de ingresos mensuales.');
                        }
                    }
                } else {
                    for ($i = 1; $i <= 12; $i++) {
                        if (
                            !is_numeric($fichaMontoMensual['MENSUAL_Y1_M' . $i]) ||
                            !is_numeric($fichaMontoMensual['MENSUAL_Y2_M' . $i])
                        ) {
                            throw new ExcepcionValidacion('El integrante debe responder el módulo de ingresos mensuales.');
                        }
                    }
                }
            } else {
                throw new ExcepcionValidacion('El integrante debe responder el módulo de ingresos mensuales.');
            }
        }

        if (empty($ficha)) {
            throw new ExcepcionConsistencia(
                'El integrante no tiene asociado datos del cuestionario.',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        if (!in_array($ficha->FICHA_ASISTEESTABEDUCACIONAL, Constante::$RESPUESTA_E1_1_A_2_POSIBLES_RESPUESTAS)) {
            throw new ExcepcionValidacion('El integrante debe responder el módulo de Educación.');
        }

        if ($ficha->FICHA_ASISTEESTABEDUCACIONAL == Constante::RESPUESTA_E1_2_NO) {
            if (!in_array($ficha->FICHA_TIPOEDUCACION, Constante::$RESPUESTA_E4_0_A_15_POSIBLES_RESPUESTAS)) {
                throw new ExcepcionValidacion('El integrante debe responder el módulo de Educación.');
            }

//            if (!in_array($ficha->FICHA_NOASISTEMOTIVO, Constante::$RESPUESTA_E2_1_A_12_POSIBLES_RESPUESTAS)) {
//                throw new ExcepcionValidacion('El integrante debe responder el módulo de Educación.');
//            }
        }

        if (!is_numeric($ficha->FICHA_CONDICIONDISCAPACIDAD)) {
            throw new ExcepcionValidacion('El integrante debe responder el módulo de Salud.');
        }

        if (!in_array($ficha->FICHA_CONDICIONDISCAPACIDAD, Constante::$RESPUESTA_S1_1_A_2_POSIBLES_RESPUESTAS)) {
            throw new ExcepcionValidacion('El integrante debe responder el módulo de Salud.');
        }

        if (
            !is_numeric($ficha->FICHA_TRABAJA) &&
            $per->PER_EDAD >= Constante::EDAD_15_PUEDE_TRABAJAR
        ) {
            throw new ExcepcionValidacion('El integrante debe responder el módulo de Ocupación e Ingresos.');
        }

        if ($per->PER_EDAD >= Constante::EDAD_15_PUEDE_TRABAJAR) {
            if (!in_array($ficha->FICHA_TRABAJA, Constante::$RESPUESTA_O1_1_A_2_POSIBLES_RESPUESTAS)) {
                throw new ExcepcionValidacion('El integrante debe responder el módulo de Ocupación e Ingresos.');
            }

            if (
                $ficha->FICHA_TRABAJA == Constante::RESPUESTA_O1_2_NO &&
                !in_array($ficha->FICHA_BUSCOTRABAJO, Constante::$RESPUESTA_O8_1_A_2_POSIBLES_RESPUESTAS)
            ) {
                throw new ExcepcionValidacion('El integrante debe responder el módulo de Ocupación e Ingresos (O4).');
            }
        }
    }

}
