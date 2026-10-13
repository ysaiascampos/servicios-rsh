<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Utiles;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;


/**
 * App\Models\GrupoFamiliarExport
 *
 * @property int $GRUPFAMI_ID
 * @property int|null $GRUPFAMI_FOLIO
 * @property int|null $GRUPFAMI_NUEVOFOLIO
 * @property int|null $GRUPFAMI_COMUNAINE
 * @property int|null $GRUPFAMI_LOCALIDAD
 * @property string|null $GRUPFAMI_UNIDADVECINAL
 * @property int|null $GRUPFAMI_CODIGOAH
 * @property string|null $GRUPFAMI_NUMDOMICILIO
 * @property string|null $GRUPFAMI_SITIO
 * @property string|null $GRUPFAMI_BLOCK
 * @property string|null $GRUPFAMI_DPTO
 * @property string|null $GRUPFAMI_CASA
 * @property string|null $GRUPFAMI_REFERENCIA
 * @property string|null $GRUPFAMI_NUMCLIENTELUZ
 * @property string|null $GRUPFAMI_NUMCLIENTEAGUA
 * @property int|null $GRUPFAMI_TIPOVIVIENDA
 * @property int|null $GRUPFAMI_SITUACIONSITIO
 * @property int|null $GRUPFAMI_SITUACIONVIVIENDA
 * @property int|null $GRUPFAMI_FUENTEAGUA
 * @property int|null $GRUPFAMI_DISTRIBUCIONAGUA
 * @property int|null $GRUPFAMI_SISTEMABANO
 * @property int|null $GRUPFAMI_DORMITORIOSOCUPADOS
 * @property int|null $GRUPFAMI_PRINCIPALOCUPANTE
 * @property string|null $GRUPFAMI_FECHAINGRESO
 * @property int|null $NUM_CON_DEPENDENCIA
 * @property string|null $CALC_INDICE_NECESIDAD
 * @property int|null $CALC_INGRESO_CAPITAL
 * @property int|null $CALC_INGRESO_LABORAL
 * @property int|null $CALC_INGRESO_PENSION
 * @property int|null $CALC_INGRESO_HONORARIO
 * @property int|null $CALC_INGRESO_DEFINITIVO
 * @property int|null $CALC_INGRESO_EQUIVALENTE
 * @property int|null $CALC_QUINTIL_SINVALIDACION
 * @property int|null $CALC_QUINTIL_VALIDACION1
 * @property int|null $CALC_QUINTIL_VALIDACION2
 * @property string|null $GRUPFAMI_FECHAINGRESOCALCULO
 * @property bool|null $CALC_ISAPRECARGA_COSTOSO
 * @property bool|null $CALC_VEHICULOS_COSTOSOS
 * @property bool|null $CALC_PROPIEDADES_COSTOSAS
 * @property int|null $GRUPFAMI_FECHA_ENCUESTA
 * @property int|null $GRUPFAMI_FECHA_MODIFICACION
 * @property int|null $GRUPFAMI_MATERIALMURO
 * @property int|null $GRUPFAMI_MATERIALMUROESTADO
 * @property int|null $GRUPFAMI_MATERIALPISO
 * @property int|null $GRUPFAMI_MATERIALPISOESTADO
 * @property int|null $GRUPFAMI_MATERIALTECHO
 * @property int|null $GRUPFAMI_MATERIALTECHOESTADO
 * @property bool|null $GRUPFAMI_C_ZONA
 * @property string|null $GRUPFAMI_C_CALLE
 * @property string|null $GRUPFAMI_N_CALLE
 * @property int|null $CALC_TRAMO_LABORAL_MIN
 * @property int|null $CALC_TRAMO_LABORAL_MAX
 * @property int|null $CALC_TRAMO_CAPITAL_MIN
 * @property int|null $CALC_TRAMO_CAPITAL_MAX
 * @property int|null $CALC_TRAMO_PENSION_MIN
 * @property int|null $CALC_TRAMO_PENSION_MAX
 * @property int|null $CALC_TRAMO_DEF_MIN
 * @property int|null $CALC_TRAMO_DEFL_MAX
 * @property int|null $FECHA_CALIFICACION
 * @property int|null $NUM_INTEGRANTES_GRUP
 * @property int|null $GRUPFAMI_VIGENTE
 * @property int|null $GRUPFAMI_CORREL_ING
 * @property int|null $GRUPFAMI_MARCA20
 * @property int|null $INGRESO_ESCALA_MINVU
 * @property int|null $CORRELATIVO_Y_SAP
 * @property int|null $MARCA_10
 * @property int|null $CSE_PROCESO_CALC
 * @property int|null $GRUPFAMI_IDSOLICITUD
 * @property int|null $CALC_ISAPRECARGA_MCOSTOSO
 * @property int|null $CALC_VEHICULOS_MCOSTOSOS
 * @property int|null $CALC_PROPIEDADES_MCOSTOSAS
 * @property int|null $MARCA_30
 * @property int|null $CALC_COLEGIO_COSTOSO
 * @property int|null $PENSION_ALIMENTOS
 * @property int|null $MEDIO_PADRE_NO_PRESENT
 * @property string|null $MANZENT MANZENT CODIGO INE DE MANZANA
 * @property string|null $SIST_REFERENCIA_COORD_X_Y SISTEMA DE REFERENCIA DE COORDENADAS GEOGRAFICAS
 * @property string|null $SIST_ORIGEN_X_Y SISTEMA O AREA MDS DE DONDE PROVIENE X e Y COORDENADAS GEOGRAFICAS
 * @property string|null $COORDENADA_X COORDENADA_X
 * @property string|null $COORDENADA_Y COORDENADA_Y
 * @property string|null $DIREC_SIN_SEPARAR_GEO DIREC_SIN_SEPARAR_GEO CADENA COMPLETA DEVUELTA POR GEOREFERENCIA   POR EJEMPLO DE GOOGLE
 * @property string|null $DIREC_CALLE_GEO DIREC_CALLE_GEO DEVUELTA POR GEOREFERENCIA   POR EJEMPLO DE GOOGLE
 * @property string|null $DIREC_NUMERO_GEO DIREC_NUMERO_GEO DEVUELTA POR GEOREFERENCIA   POR EJEMPLO DE GOOGLE
 * @property string|null $UV_GEO UV_GEO DEVUELTA POR GEOREFERENCIA   POR EJEMPLO DE GOOGLE
 * @property int|null $FECHA_GEO FECHA_GEO DE GEOREFERENCIA
 * @property string|null $LOCALIDAD_GEO LOCALIDAD_GEO DEVUELTA POR GEOREFERENCIA   POR EJEMPLO DE GOOGLE
 * @property string|null $GRUPFAMI_FECHA_VALIDACION_CIUD
 * @property string|null $UV_OLD
 * @property int|null $GRUPFAMI_IDCOMPLUZ
 * @property int|null $GRUPFAMI_IDCOMPAGUA
 * @method static Builder|GrupoFamiliarExport newModelQuery()
 * @method static Builder|GrupoFamiliarExport newQuery()
 * @method static Builder|GrupoFamiliarExport query()
 * @mixin Eloquent
 */
class GrupoFamiliarExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'grupos_familiares';
    protected $primaryKey = 'GRUPFAMI_ID';
    public $timestamps = false;
    protected $hidden = ['rn'];
    protected $filled = [];

    public function getNombreUnidadVecinalAttribute()
    {
        if (empty($this->attributes['NOMBRE_UNIDAD_VECINAL'])) {
            $this->attributes['NOMBRE_UNIDAD_VECINAL'] = $this->obtenerNombreUnidadVecinal();
        }
        return $this->attributes['NOMBRE_UNIDAD_VECINAL'];
    }

    public function getNombreAgrupacionHabitacionalAttribute()
    {
        if (empty($this->attributes['NOMBRE_AGRUPACION_HABITACIONAL'])) {
            $this->attributes['NOMBRE_AGRUPACION_HABITACIONAL'] = $this->obtenerNombreAgrupacionHabitacional();
        }
        return $this->attributes['NOMBRE_AGRUPACION_HABITACIONAL'];
    }

    public function getComunaAttribute()
    {
        if (empty($this->attributes['COMUNA'])) {
            $this->attributes['COMUNA'] = LocacionComuna::findByComunaIne($this->attributes['GRUPFAMI_COMUNAINE'])[0];
        }
        return $this->attributes['COMUNA'];
    }

    public function getRegionAttribute()
    {
        if (empty($this->attributes['REGION'])) {
            $comuna = $this->COMUNA;
            $this->attributes['REGION'] = ($comuna) ? LocacionRegion::getRegionPorComuna($comuna->CODIGO) : null;
        }
        return $this->attributes['REGION'];
    }

    public function getLocalidadAttribute()
    {
        if (empty($this->attributes['LOCALIDAD'])) {
            $comuna = $this->COMUNA;
            $this->attributes['LOCALIDAD'] = ($comuna) ?
                LocacionLocalidad::getLocalidad($comuna->CODIGO, $this->GRUPFAMI_LOCALIDAD) : null;
        }
        return $this->attributes['LOCALIDAD'];
    }

    public function getCalleAttribute()
    {
        if (empty($this->attributes['CALLE'])) {
            $comuna = $this->COMUNA;
            $this->attributes['CALLE'] = ($comuna) ? $this->obtenerNombreCalle() : null;
        }
        return $this->attributes['CALLE'];
    }

    public static function getModel()
    {
        return new GrupoFamiliarExport();
    }

    public static function findByIdGrupoFamiliar($idGrupoFamiliar)
    {
        $datosDeGrupoFamliar = null;
        try {
            $datosDeGrupoFamiliar = self::getModel()
                ->where('grupfami_id', $idGrupoFamiliar)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->orderBy('grupfami_id','desc')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeGrupoFamiliar;
    }

    public static function getGrupo($perFicGrupofamiliarId)
    {
        $grupo = self::getModel()
            ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('grupfami_id', $perFicGrupofamiliarId)
            ->orderBy('grupfami_id','desc');

        $comunaGrupo = null;
        $calleGrupo = null;
        if ($grupo) {
            //$comunaGrupo = Comuna::find($grupo[0]->GRUPFAMI_COMUNAINE);
            if (count($grupo[0]->GRUPFAMI_C_CALLE)) {
                $calleGrupo = Calle::where('id_calle', $grupo[0]->GRUPFAMI_C_CALLE)
                    ->where('comuna_ine', $grupo[0]->GRUPFAMI_COMUNAINE)
                    ->first();
            }
        }
    }

    public function obtenerNombreCalle()
    {
        if ($this->GRUPFAMI_C_CALLE >= Constante::ID_CALLE_11011_CORTE_GRUPO_FAMILIAR) {
            $calle_aux = Calle::where('id_localidad', $this->GRUPFAMI_LOCALIDAD)
                ->where('id_calle', $this->GRUPFAMI_C_CALLE)
                ->where('comuna_ine', $this->GRUPFAMI_COMUNAINE)
                ->get();
            if ($calle_aux->count()) {
                $calle = $calle_aux->first()['NOMBRE'];
            } else {
                $calle_aux = Calle::where('id_calle', $this->GRUPFAMI_C_CALLE)
                    ->where('comuna_ine', $this->GRUPFAMI_COMUNAINE)
                    ->get();
                $calle = $calle_aux->first()['NOMBRE'];
            }
            if ($calle_aux->count() < 1) {
                $calle = $this->GRUPFAMI_N_CALLE;
            }
        } elseif ($this->GRUPFAMI_N_CALLE) {
            $calle = $this->GRUPFAMI_N_CALLE;
        }
        return $calle??"Calle no encontrada";
    }
    public function obtenerNombreAgrupacionHabitacional()
    {
        try {
            $sqlAH = "select FN_NOMBRE_AGRUP_HAB_EXPORT(?) as nombreah from dual";
            $nombreAH = DB::connection('oracle_export')
                ->select($sqlAH, [$this->GRUPFAMI_NUEVOFOLIO])[0];
        } catch (Exception $exception) {
            Log::error('Excepcion al usar la funcion FN_NOMBRE_AGRUP_HAB_EXPORT con el folio ' . $this->GRUPFAMI_NUEVOFOLIO . '. Excepcion: ' . $exception);
            $nombreAH = new stdClass;
            $nombreAH->NOMBREAH = 'Error:';
        }
        return Utiles::limpiaErrorFuncionDB($nombreAH->NOMBREAH);
    }

    public function obtenerNombreUnidadVecinal()
    {
        try {
            $sqlUV = "select FN_NOMBRE_U_VECINAL_EXPORT(?) as nombreuv from dual";
            $nombreUV = DB::connection('oracle_export')
                ->select($sqlUV, [$this->GRUPFAMI_NUEVOFOLIO])[0];
        } catch (Exception $exception) {
            Log::error('Excepcion al usar la funcion FN_NOMBRE_U_VECINAL_EXPORT con el folio ' . $this->GRUPFAMI_NUEVOFOLIO . '. Excepcion: ' . $exception);
            $nombreUV = new stdClass;
            $nombreUV->NOMBREUV = 'Error:';
        }
        return Utiles::limpiaErrorFuncionDB($nombreUV->NOMBREUV);
    }

    public function obtenerNombreLocalidad()
    {
        try {
            $sqlNomLocalidad = "select FN_NOM_LOCALIDAD_C_POB_EXPORT(?) as nomlocalidad from dual";
            $nombreLocalidad = DB::select($sqlNomLocalidad, [$this->GRUPFAMI_NUEVOFOLIO])[0];

        } catch (Exception $exception) {
            Log::error('Excepcion al usar la funcion FN_NOM_LOCALIDAD_C_POB_EXPORT con el folio' . $this->GRUPFAMI_NUEVOFOLIO . '. Excepcion: ' . $exception);
            $nombreLocalidad = new stdClass;
            $nombreLocalidad->NOMLOCALIDAD = 'Error:';
        }
        return Utiles::limpiaErrorFuncionDB($nombreLocalidad->NOMLOCALIDAD);
    }

    public function obtenerEstadoGrupoFamiliar()
    {
        //ToDO: Verificar estados en export
        $estadosGrupoFamiliar = [
            1 => Constante::ESTADO_FOLIO_SOLICITADO,
            2 => Constante::ESTADO_FOLIO_DIGITACION,
            3 => Constante::ESTADO_FOLIO_ESPERANDO_PUNTAJE,
            4 => Constante::ESTADO_FOLIO_SUSPENDIDO,
            5 => Constante::ESTADO_FOLIO_PROVISORIO,
            6 => Constante::ESTADO_FOLIO_ANULADO,
            7 => Constante::ESTADO_FOLIO_APROBADO,
            14 => Constante::ESTADO_FOLIO_INACTIVO_X_DEFUNCION
        ];
        if (in_array($this->GRUPFAMI_ESTADO, $estadosGrupoFamiliar)) {
            $estado = $estadosGrupoFamiliar[$this->GRUPFAMI_ESTADO];
        } else {
            $estado = Constante::ESTADO_FOLIO_SIN_INFORMACION;
        }
        return $estado;
    }
}
