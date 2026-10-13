<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionConsistencia;
use App\Utiles;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * App\Models\Personas
 *
 * @property int $PER_ID
 * @property int|null $PER_RUN
 * @property string|null $PER_DV
 * @property string|null $PER_NOMBRES
 * @property string|null $PER_APELLIDOPATERNO
 * @property string|null $PER_APELLIDOMATERNO
 * @property int|null $PER_FECHANACIMIENTO
 * @property int|null $PER_EDAD
 * @property int|null $PER_SEXO
 * @property int|null $PER_NACIONALIDAD
 * @property string|null $PER_DIRECCION
 * @property string|null $PER_EMAIL
 * @property string|null $PER_TELEFONO
 * @property string|null $PER_CELULAR
 * @property int|null $PER_FECHADEFUNCION
 * @property int|null $PER_FECHAFUENTE
 * @property string|null $PER_FECHAACTUALIZACION
 * @property string|null $PER_CARACTERIZACION
 * @property string|null $PER_FECHACALCULO
 * @property int|null $PER_VIGENTE
 * @property string|null $PER_FECHAEXPIRACION
 * @property int|null $PER_IDSOLICITUD
 * @property int|null $PER_CALCULOACTIVO
 * @property int|null $PER_FUENTE
 * @property int|null $NO_REGISTRADO_SRCEI
 * @property string|null $PER_SITUACIONVISA
 * @property int|null $PER_IDENTIDADGENERO
 * @property string|null $PER_MD5
 * @property string|null $PER_NROIDENTIFICADORNAC
 * @property string|null $PER_ESTADOCIVIL
 * @property int|null $PER_FCORREC_MAN
 * @property string|null $PER_DESC_CORREC
 * @property string|null $PER_FECHA_CONSULTA
 * @property bool|null $INGRESO_MANUAL
 * @property int|null $PER_RUN_PADRE
 * @property int|null $PER_RUN_MADRE
 * @property string|null $PER_FECHA_ACTUALIZACION_PADRES
 * @property-read mixed $per_dv_madre
 * @property-read mixed $per_dv_padre
 * @method static Builder|Personas newModelQuery()
 * @method static Builder|Personas newQuery()
 * @method static Builder|Personas query()
 * @method static Builder|Personas whereINGRESOMANUAL($value)
 * @method static Builder|Personas whereNOREGISTRADOSRCEI($value)
 * @method static Builder|Personas wherePERAPELLIDOMATERNO($value)
 * @method static Builder|Personas wherePERAPELLIDOPATERNO($value)
 * @method static Builder|Personas wherePERCALCULOACTIVO($value)
 * @method static Builder|Personas wherePERCARACTERIZACION($value)
 * @method static Builder|Personas wherePERCELULAR($value)
 * @method static Builder|Personas wherePERDESCCORREC($value)
 * @method static Builder|Personas wherePERDIRECCION($value)
 * @method static Builder|Personas wherePERDV($value)
 * @method static Builder|Personas wherePEREDAD($value)
 * @method static Builder|Personas wherePEREMAIL($value)
 * @method static Builder|Personas wherePERESTADOCIVIL($value)
 * @method static Builder|Personas wherePERFCORRECMAN($value)
 * @method static Builder|Personas wherePERFECHAACTUALIZACION($value)
 * @method static Builder|Personas wherePERFECHAACTUALIZACIONPADRES($value)
 * @method static Builder|Personas wherePERFECHACALCULO($value)
 * @method static Builder|Personas wherePERFECHACONSULTA($value)
 * @method static Builder|Personas wherePERFECHADEFUNCION($value)
 * @method static Builder|Personas wherePERFECHAEXPIRACION($value)
 * @method static Builder|Personas wherePERFECHAFUENTE($value)
 * @method static Builder|Personas wherePERFECHANACIMIENTO($value)
 * @method static Builder|Personas wherePERFUENTE($value)
 * @method static Builder|Personas wherePERID($value)
 * @method static Builder|Personas wherePERIDENTIDADGENERO($value)
 * @method static Builder|Personas wherePERIDSOLICITUD($value)
 * @method static Builder|Personas wherePERMD5($value)
 * @method static Builder|Personas wherePERNACIONALIDAD($value)
 * @method static Builder|Personas wherePERNOMBRES($value)
 * @method static Builder|Personas wherePERNROIDENTIFICADORNAC($value)
 * @method static Builder|Personas wherePERRUN($value)
 * @method static Builder|Personas wherePERRUNMADRE($value)
 * @method static Builder|Personas wherePERRUNPADRE($value)
 * @method static Builder|Personas wherePERSEXO($value)
 * @method static Builder|Personas wherePERSITUACIONVISA($value)
 * @method static Builder|Personas wherePERTELEFONO($value)
 * @method static Builder|Personas wherePERVIGENTE($value)
 * @mixin Eloquent
 */
class Personas extends Eloquent
{
    protected $table = 'personas';
    protected $primaryKey = 'PER_ID';
    public $timestamps = false;

    protected $appends = ['PER_DV_PADRE', 'PER_DV_MADRE', 'TIENE_RSH', 'P_JURIDICA', 'FUENTE_DEPENDENCIA'];

    public function getTieneRshAttribute()
    {
        if (!isset($this->attributes['TIENE_RSH'])) {
            $this->attributes['TIENE_RSH'] = $this->tieneRSH($this->attributes['PER_ID']);
        }

        return $this->attributes['TIENE_RSH'];
    }

    public function getPJuridicaAttribute()
    {
        if (!isset($this->attributes['P_JURIDICA'])) {
            $this->attributes['P_JURIDICA'] = $this->personaJuridica($this->attributes['PER_RUN']);
        }

        return $this->attributes['P_JURIDICA'];
    }

    public function getFuenteDependenciaAttribute()
    {
        if (!isset($this->attributes['FUENTE_DEPENDENCIA'])) {
            $this->attributes['FUENTE_DEPENDENCIA'] = $this->fuenteDependencia($this->attributes['PER_RUN']);
        }

        return $this->attributes['FUENTE_DEPENDENCIA'];
    }

    public function getPerDvPadreAttribute()
    {
        return isset($this->attributes['PER_RUN_PADRE']) ? Utiles::dv($this->attributes['PER_RUN_PADRE']) : null;
    }

    public function getPerDvMadreAttribute()
    {
        return isset($this->attributes['PER_RUN_MADRE']) ? Utiles::dv($this->attributes['PER_RUN_MADRE']) : null;
    }

    public static function getModel()
    {
        return new Personas();
    }

    public static function findByRun($runCiudadano, $retornarException = true)
    {
        try {
            $datosDeContacto = Utiles::getPersona($runCiudadano); // valida si run no existe en tabla Personas

            if ($retornarException && $datosDeContacto == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_NO_DATO_CONTACTO,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            if ($retornarException && $datosDeContacto == Constante::DATOS_DE_CONTACTO_2_ERROR_INESPERADO) {
                throw new Excepcion("Utiles::getPersona no se puede reconocer el error inesperado");
            }
            if ($retornarException && $datosDeContacto == Constante::DATOS_DE_CONTACTO_6_ERROR_REGISTRO_CIVIL) {
                throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_FALLA_REGISTRO_CIVIL,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            if (!($datosDeContacto instanceof Personas)) {
                throw (new ExcepcionConsistencia("PERSONA NO EN BD Y RUNIFICADOR CAIDO",
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
            }
        } catch (Throwable $ex) {
            Log::info('error en Personas -> findByRun');
            Log::info(print_r($ex->getMessage(),true));
            throw $ex;
        }

        return $datosDeContacto;
    }

    public static function findByIdPersona($idPersona): Personas
    {
        try {
            $datosDeContacto = self::getModel()
                ->where('per_id', $idPersona)
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
        } catch (Throwable $ex) {
            throw $ex;
        }

        return $datosDeContacto;
    }

    public function findByIdPersona_calc2($idPersona, $sid, $tipo)
    {
        try {
            if ($tipo == 'I') {
                $datosDeContacto = $this->getModel()
                    ->join('beneficiarios_gruposfamiliares', 'beneficiarios_gruposfamiliares.bnfgrupfami_persona', '=',
                        'per_id')
                    ->join('ab_parentesco', 'ab_parentesco.id', '=',
                        'beneficiarios_gruposfamiliares.bnfgrupfami_parentesco')
                    ->where('per_id', $idPersona)
                    ->where('beneficiarios_gruposfamiliares.bnfgrupfami_idsolicitud', $sid)
                    ->first();
            } else {
                $datosDeContacto = $this->getModel()
                    ->join('beneficiarios_gruposfamiliares', 'beneficiarios_gruposfamiliares.bnfgrupfami_persona', '=',
                        'per_id')
                    ->join('ab_parentesco', 'ab_parentesco.id', '=',
                        'beneficiarios_gruposfamiliares.bnfgrupfami_parentesco')
                    ->where('per_id', $idPersona)
                    ->where('beneficiarios_gruposfamiliares.bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('beneficiarios_gruposfamiliares.bnfgrupfami_calculoactivo',
                        Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->first();
            }
        } catch (Throwable $ex) {
            throw $ex;
        }

        return $datosDeContacto;
    }

    /**
     * Para extranjeros sin run
     */
    public function findByMd5($personaMd5): Personas
    {
        try {
            $datosDeContacto = $this->getModel()
                ->where('per_md5', $personaMd5)
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
        } catch (Throwable $ex) {
            throw $ex;
        }

        return $datosDeContacto;
    }

    /* Busca si la persona se encuentra vigente en otro grupo familiar y genera un mensaje y guarda una alerta */
    public function getPerteneceGrupoFamiliar($idPersona)
    {
        $beneficiarios = BeneficiarioGrupoFamiliar::select([
            'bnfgrupfami_grupofamiliar',
            'bnfgrupfami_institucionalizado',
        ])
            ->where('bnfgrupfami_persona', $idPersona)
            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();
        if (!empty($beneficiarios)) {
            $grupoFamiliar = GrupoFamiliar::select('grupfami_nuevofolio')
                ->where('grupfami_id', $beneficiarios->BNFGRUPFAMI_GRUPOFAMILIAR)
                ->where('grupfami_estado', Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
            if (!empty($grupoFamiliar)) {
                if ($beneficiarios->bnfgrupfami_institucionalizado != Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO) {
                    return $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                }
            }
        }

        return null;
    }

    public static function tieneRSH($idPersona)
    {
        $tieneRSH = false;
        try {
            $idGrupoFamiliar = BeneficiarioGrupoFamiliar::findIdGrupoFamiliarVigenteByIdPersona($idPersona, false);
            $idGrupoFamiliar = ($idGrupoFamiliar) ? $idGrupoFamiliar->BNFGRUPFAMI_GRUPOFAMILIAR : null;
            $grupoFamiliar = ($idGrupoFamiliar) ? GrupoFamiliar::where('GRUPFAMI_ID', $idGrupoFamiliar)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first() : null;
            $estadoGrupo = isset($grupoFamiliar->GRUPFAMI_ESTADO) ? intval($grupoFamiliar->GRUPFAMI_ESTADO) : 0;

            if ($estadoGrupo == Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO) {
                $tieneRSH = true;
            }
        } catch (Exception $e) {
            Log::error("Hubo problemas para analizar el integrante Per_ID ".$idPersona);
        }

        return ($tieneRSH);
    }

    public static function situacionUnicoPerID($runPersona): array
    {
        try {
            $registros = self::where('per_run', $runPersona)
                ->where('per_vigente', '=', 1)
                ->where('per_calculoactivo', '=', 1)
                ->pluck('PER_ID')
                ->toArray();
            $situacionUnicoPerID = [
                'situacion' => (count($registros) <= 1),
                'per-id' => $registros,
            ];
        } catch (Exception $e) {
            $situacionUnicoPerID = [
                'situacion' => true,
                'per-id' => [],
            ];
            Log::error("Hubo problemas para analizar el integrante RUN ".$runPersona);
        }

        return $situacionUnicoPerID;
    }

    public static function situacionUnicoFolio($runPersona): array
    {
        try {
            $gruposFamiliares = BeneficiarioGrupoFamiliar::join('grupos_familiares', 'grupos_familiares.grupfami_id',
                '=', 'beneficiarios_gruposfamiliares.bnfgrupfami_grupofamiliar')
                ->join('personas', 'personas.per_id', '=', 'beneficiarios_gruposfamiliares.bnfgrupfami_persona')
                ->where('personas.per_run', $runPersona)
                ->where('beneficiarios_gruposfamiliares.bnfgrupfami_calculoactivo',
                    Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('beneficiarios_gruposfamiliares.bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupos_familiares.grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('grupos_familiares.grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->select(['grupos_familiares.grupfami_nuevofolio'])
                ->get();
            $nuevosfolios = $gruposFamiliares->pluck('GRUPFAMI_NUEVOFOLIO')
                ->toArray();
            $multiplesFolios = [
                'situacion' => $nuevosfolios ? (count($nuevosfolios) <= 1) : false,
                'folios' => $nuevosfolios,
            ];
        } catch (Exception $e) {
            $multiplesFolios = [
                'situacion' => false,
                'folios' => [],
            ];
            Log::error("Hubo problemas para analizar el integrante RUN ".$runPersona);
        }

        return $multiplesFolios;
    }


    public static function personaJuridica($runPersona)
    {
        $estadoJuridico = false;
        try {
            $personaJuridica = PersonasExport::findByRunCiudadano($runPersona);
            if (isset($personaJuridica) && $personaJuridica->P_JURIDICA == Constante::PERSONA_JURIDICA) {
                $estadoJuridico = true;
            }
        } catch (Exception $e) {
            Log::error("Hubo problemas para analizar el estado juridico del integrante run ".$runPersona);
        }
        return $estadoJuridico;
    }

    public static function fuenteDependencia($runPersona)
    {
        $fuenteDependencia = null;
        try {
            $personaFuente = PersonasExport::findByRunCiudadano($runPersona);
            if (isset($personaFuente->FUENTE_DEPENDENCIA)) {
                $idFuenteDependencia = ($personaFuente->FUENTE_DEPENDENCIA);
                $fuenteDependencia = FuenteExport::findByFuenteDependencia($idFuenteDependencia);
            }
        } catch (Exception $e) {
            Log::error("Hubo problemas para analizar la FUENTE DE DEPENDENCIA del integrante run ".$runPersona);
        }

        return $fuenteDependencia;
    }

    public function esJefeHogar($persona_id){

        $idgrupofamiliar=BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona',
            $persona_id)
            ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('bnfgrupfami_parentesco', '=', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
            ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->get();
        if (count($idgrupofamiliar) > 0){
            return true;
        }
        else{
            return false;
        }
    }

}
