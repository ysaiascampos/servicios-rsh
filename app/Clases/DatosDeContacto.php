<?php

namespace App\Clases;

use App\Exceptions\ExcepcionConsistencia;
use App\Models\TipoSolicitud;
use App\Utiles;
use Exception;
use App\Constantes\Constante;
use App\Models\Solicitud;
use App\Models\GrupoFamiliar;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Personas;
use App\Models\PersonasTokenMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatosDeContacto
{
    protected $hidden = ['rn'];
    protected $fillable = [
        'PER_EMAIL',
        'PER_TELEFONO',
        'PER_CELULAR'
    ];
    protected $fillableService = [
        'PER_RUN',
        'PER_DV',
        'PER_NOMBRES',
        'PER_APELLIDOPATERNO',
        'PER_APELLIDOMATERNO',
        'PER_FECHANACIMIENTO',
        'PER_SEXO',
        'PER_ESTADOCIVIL',
        'PER_CELULAR',
        'PER_TELEFONO',
        'PER_EMAIL',
        'INGRESO_MANUAL'
    ];

    public static function obtenerDatosContacto($runCiudadano)
    {
        $ciudadano = Personas::findByRun($runCiudadano, true);
        if (is_numeric($ciudadano)) {
            if ($ciudadano < 0) {
                throw (new ExcepcionConsistencia("PERSONA NO EN BD Y RUNIFICADOR CAIDO",
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
            }
        }

        $ciudadano->validacion_email = PersonasTokenMail::findByRunCiudadano($ciudadano->PER_RUN);
        $ciudadano->situacion_unico_per_id = Personas::situacionUnicoPerID($ciudadano->PER_RUN);
        $ciudadano->situacion_unico_folio = Personas::situacionUnicoFolio($ciudadano->PER_RUN);
        if($ciudadano->PER_FECHADEFUNCION != 0){
            $ciudadano->puedeSerIncorporado = false;
        }else {
            $puedeSerIncorporado = Utiles::getPuedeSerIncorporado($ciudadano);
            $ciudadano->puedeSerIncorporado = $puedeSerIncorporado['puedeserincorporado'];
        }

        $ciudadano->institucionalizado = Institucionalizados::evaluarExistenciaInstitucionalizados($ciudadano);

        return $ciudadano;
    }

    /**
     * @param Model $entity
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function update(Model $entity, array $data)
    {
        try {
            $entity->fillable($this->fillable);
            $entity->fill($data);
            $entity->save();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            throw $ex;
        }
        return $entity;
    }

    public function actualizarEntidad(Model $entity, array $data)
    {
        try {
            $entity->fillable($this->fillableService);
            $entity->fill($data);
            $entity->save();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            throw $ex;
        }
        return $entity;
    }

    /**
     * Busca Institucionalizados de acuerdo a
     * Tabla Grupos_familiares                      GRUPFAMI_INSTITUCIONALIZADO = 1
     * Tabla Beneficiario_gruposfamiliares          BNFGRUPFAMI_INSTITUCIONALIZADO = 1
     */
    public static function analisisInstitucionalizados($idPersona)
    {
        $benefGruposFamiliares = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $idPersona)
            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();
        $checkGruposFamilares = null;
        $checkBeneficiariosGruposFamiliares = intval(trim($benefGruposFamiliares['bnfgrupfami_institucionalizado']));
        if (!empty($benefGruposFamiliares)) {
            $gruposFamiliares = GrupoFamiliar::where('grupfami_id', $benefGruposFamiliares->BNFGRUPFAMI_GRUPOFAMILIAR)
                ->where('grupfami_estado', Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
            if (!empty($gruposFamiliares)) {
                $checkGruposFamilares = intval(trim($gruposFamiliares['grupfami_institucionalizado']));
            }
        }
        if ($checkBeneficiariosGruposFamiliares == Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO &&
            $checkGruposFamilares == Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO
        ) {
            $resultadoInstitucionalizado = true;
        } else {
            $resultadoInstitucionalizado = false;
        }
        return $resultadoInstitucionalizado;
    }

    public function insert(array $data, $tipoSolicitud)
    {
        $runCiudadano = Input::get('run-ciudadano');
        $idSolicitud = null;
        $idPadreSolicitud = null;
        $idGrupoFamiliar = null;
        DB::beginTransaction();
        try {
            switch ($tipoSolicitud) {
                case Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO:
                    $telefonoNew = isset(Input::get('tipo-solicitud')[strval(Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO)]['telefono']) ?
                        Input::get('tipo-solicitud')[strval(Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO)]['telefono'] :
                        null;
                    Solicitud::where('run_ciudadano', $runCiudadano)
                        ->whereIn('id_estado', [
                            Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                            Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                        ])
                        ->update(array('fono_ciudadano' => $telefonoNew, 'fono_ciudadano2' => $telefonoNew));

                    $persona = (new DatosDeContacto())->findByRun($runCiudadano);
                    $persona->PER_TELEFONO = $telefonoNew;
                    if ($persona->save()) {
                        DB::commit();
                    }
                    break;
                case Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL:
                    $mailNew = isset(Input::get('tipo-solicitud')[strval(Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL)]['email']) ?
                        Input::get('tipo-solicitud')[strval(Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL)]['email'] :
                        null;
                    Solicitud::where('run_ciudadano', $runCiudadano)
                        ->whereIn('id_estado', [
                            Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                            Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                        ])
                        ->update(array('email_ciudadano' => $mailNew));

                    $persona = (new DatosDeContacto())->findByRun($runCiudadano);
                    $persona->PER_EMAIL = $mailNew;
                    if ($persona->save()) {
                        DB::commit();
                    }
                    break;
                case Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS:
                    $idSolicitud = DB::getSequence()->nextValue('seq_ab_solicitud');
                    $idPadreSolicitud = DB::getSequence()->nextValue('seq_ab_solicitud_padre');

                    $solicitud = new Solicitud();

                    $solicitud->ID_SOLICITUD = $idSolicitud;
                    $solicitud->ID_PADRE_SOLICITUD = $idPadreSolicitud;

                    $solicitud->SOLICITUD = TipoSolicitud::find($tipoSolicitud)->TIPO_SOLICITUD;
                    $solicitud->ID_TIPO_SOLICITUD = $tipoSolicitud;
                    if ($data['origen_registro'] == Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA) {
                        $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
                    }
                    $solicitud->fillable($this->filledSolicitud);
                    $solicitud->fill($data);

                    $solicitud->DATO_NUEVO = $data['nuevofolio'];
                    $solicitud->DATO_ACTUAL = $data['nuevofolio'];

                    if ($solicitud->save()) {
                        if (!in_array($solicitud->ID_ESTADO, [
                            Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS
                        ])
                        ) {
                            $grupoFamiliar = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($runCiudadano);
                            $grupoFamiliar->GRUPFAMI_RUT_VALIDACION_CIUD = $runCiudadano;
                            $grupoFamiliar->GRUPFAMI_FECHA_VALIDACION_CIUD = date('Y-m-d H:i:s');
                            if ($grupoFamiliar->save()) {
                                DB::commit();
                                $idGrupoFamiliar = $grupoFamiliar->GRUPFAMI_ID;
                            }
                        } else {
                            DB::commit();
                        }
                    }
                    break;
            }
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            throw $ex;
        }
        return [
            'idPadreSolicitud' => $idPadreSolicitud,
            'idSolicitud' => $idSolicitud,
            'idGrupoFamiliar' => $idGrupoFamiliar
        ];
    }

//    protected $hidden = ['rn'];
    protected $filledSolicitud = [
        'EMAIL_CIUDADANO',
        'FONO_CIUDADANO',
        'FONO_CIUDADANO2',
        'RUN_CIUDADANO',
        'COM_ID',
        'DATO_ACTUAL',
        'NUEVOFOLIO',
        'ID_GRUPO_ANTIGUO',
        'ID_USUARIO_CREACION',
        'ID_USUARIO_ASIGNADO',
        'ID_USUARIO_MODIFICACION',
        'FECHA_CREACION_CIUDADANO',
        'FECHA_CREACION',
        'FECHA_ASIGNACION',
        'FECHA_MODIFICACION',
        'ID_ESTADO',
        'ID_SUB_ESTADO',
        'FECHA_SUB_ESTADO',
        'CANAL',
        'VIGENTE',
        'ORIGEN_REGISTRO',
        'GEO_LATITUD_CREACION',
        'GEO_LONGITUD_CREACION',
        'HORA_INICIO_SOLICITUD',
        'HORA_TERMINO_SOLICITUD'
    ];

}
