<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Respuestas
 *
 * @property int $ID
 * @property string|null $NOMBRE
 * @property int|null $ESTADO estado del registro 1= activo ; 0=inactivo
 * @property int|null $ID_CATEGORIA
 * @property int|null $VALOR
 * @property string|null $SKIP_TO_PREG
 * @property string|null $SKIP_LOGIC1
 * @property string|null $SKIP_LOGIC2
 * @property string|null $NOMBRE_CIUDADANO
 * @property string|null $INDICACION
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereIDCATEGORIA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereINDICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereNOMBRECIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereSKIPLOGIC1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereSKIPLOGIC2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereSKIPTOPREG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuestas whereVALOR($value)
 * @mixin Eloquent
 */
class Respuestas extends Eloquent
{
    protected $table = 'ab_respuesta';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $hidden = ['rn'];

    public static function findAllVivienda()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = DB::table('ab_respuesta_pregunta')
                ->where('id_preg_padre', Constante::CATEGORIA_PREGUNTA_VIVIENDA)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->orderBy('codigo', 'ASC')
                ->get();

            $datosDeRespuesta = json_decode(json_encode($datosDeRespuesta), true);

            for ($indice = 0; $indice < count($datosDeRespuesta); $indice++) {
                $datosDeRespuesta[$indice]['respuesta'] = Respuestas::where('id_categoria',
                    $datosDeRespuesta[$indice]['id'])
                    ->where('estado', Constante::ESTADO_1_ACTIVO)
                    ->orderBy('valor', 'ASC')
                    ->get();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    /**
     * Esta funcion debería ser DEPRECATED ya que apunta a id especificos
     * debe ser reemplazada por findAllActivePorCategoriaESOV
     */
    public static function findAllViviendaActive()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = [
                'V1' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_1_V1_TIPO_DE_VIVIENDA),
                'V2' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_3_V2_SU_VIVIENDA_BAJO_QUE_SITUACION_OCUPA_EL_SITIO),
                'V3' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_4_V3_SU_HOGAR_BAJO_QUE_SITUACION_OCUPA_LA_VIVIENDA),
                'V4' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_5_V4_FUENTE_DE_AGUA),
                'V5' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_6_V5_DISTRIBUCION_DE_AGUA),
                'V6' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_7_V6_SISTEMA_DE_BANO),
                'V7' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_100_V7_CUANTOS_DORMITORIOS_OCUPA_SU_HOGAR),
                'V8' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_8_V8_SU_HOGAR_ES_EL_PRINCIPAL_OCUPANTE_DE_LA_VIVIENDA_O_SITIO),
                'V9' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_10_V9_MATERIALIDAD_DE_LOS_MUROS),
                'V9EC' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_123_V9EC_ESTADO_DE_LOS_MUROS_DE_LA_VIVIENDA),
                'V10' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_12_V10_MATERIALIDAD_DE_PISOS),
                'V10EC' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_124_V10EC_ESTADO_DEL_PISO_DE_LA_VIVIENDA),
                'V11' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_13_V11_MATERIALIDAD_DE_TECHOS),
                'V11EC' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_125_V11EC_ESTADO_DEL_TECHO_DE_LA_VIVIENDA),
//                'SN' => self::findActivosByIdCategoria(8),
                'EC' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_11_EC_ESTADO)
            ];
        } catch (Exception $ex) {
            Log::error('Probelmas al recueperar Valores de Vivienda');
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    public static function findAllRespuetasJoinPreguntas()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = DB::table('ab_respuesta_pregunta')
                ->join('ab_respuesta', 'ab_respuesta_pregunta.id', '=', 'ab_respuesta.id_categoria')
                ->where('ab_respuesta_pregunta.estado', Constante::ESTADO_1_ACTIVO)
                ->where('ab_respuesta.estado', Constante::ESTADO_1_ACTIVO)
                ->orderby('ab_respuesta_pregunta.id_preg_padre')
                ->orderBy('ab_respuesta_pregunta.codigo')
                ->select(
                    'ab_respuesta_pregunta.CODIGO',
                    'ab_respuesta_pregunta.id_preg_padre',
                    'ab_respuesta_pregunta.tipo_input', // 1=select; 2=radio button; 3=checkbox; 4=input
                    'ab_respuesta_pregunta.nombre_ciudadano as pregunta_ciudadano',
                    'ab_respuesta_pregunta.nombre as pregunta_municipal',
                    'ab_respuesta.valor',
                    'ab_respuesta.nombre_ciudadano as respuesta_ciudadano',
                    'ab_respuesta.nombre as respuesta_municipal'
                )
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    /**
     * Esta funcion debería ser DEPRECATED ya que apunta a id especificos
     * debe ser reemplazada por findAllActivePorCategoriaESOV
     */
    public static function findAllEducacionActive()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = [
                'E1' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_E1_ASISTE_ACTUALMENTE_A_ALGUN_ESTABLECIMIENTO_EDUCACIONAL),
//                'E2' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_E2_POR_QUE_NO_ESTUDIA),
                'E3' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_E3_HASTA_QUE_CURSO_LLEGO),
                'E4' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_E4_EN_QUE_TIPO_DE_EDUCACION_ESTUDIO)
            ];
        } catch (Exception $exception) {
            throw $exception;
        }
        return $datosDeRespuesta;
    }

    /**
     * Esta funcion debería ser DEPRECATED ya que apunta a id especificos
     * debe ser reemplazada por findAllActivePorCategoriaESOV
     */
    public static function findAllSaludActive()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = [
                'S1' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1_TIENE_ALGUNA_CONDICION_DE_SALUD_DE_LARGA_DURACION),
                'S1a' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1A_CEGUERA_O_DIFICULTAD_VISUAL_AUN_USANDO_LENTES),
                'S1b' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1B_SORDERA_O_DIFICULTAD_AUDITIVA_AUN_USANDO_AUDIFONOS),
                'S1c' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1C_MUDEZ_O_DIFICULTAD_EN_EL_HABLA),
                'S1d' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1D_DIFICULTAD_FISICA),
                'S1e' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1E_PROBLEMAS_MENTALES),
                'S1f' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S1F_PROBLEMAS_PSIQUIATRICOS),
                'S2a' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S2A_PUEDE_SALIR_SOLOA_A_LA_CALLE_SIN_AYUDA_O_COMPANIA),
                'S2b' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S2B_PUEDE_HACER_COMPRAS_O_IR_AL_MEDICO_SOLOA_SIN_AYUDA_O_COMPANIA),
                'S2c' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S2C_PUEDE_BANARSE_LAVARSE_LOS_DIENTES_PEINARSE_O_COMER_SOLOA),
                'S2d' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S2D_PUEDE_MOVERSEDESPLAZARSE_SOLOA_DENTRO_DE_LA_CASA),
                'S2e' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_S2E_PUEDE_CONTROLAR_COMPLETAMENTE_SUS_ESFINTERES),
            ];
        } catch (Exception $exception) {
            throw $exception;
        }
        return $datosDeRespuesta;
    }

    /**
     * Esta funcion debería ser DEPRECATED ya que apunta a id especificos
     * debe ser reemplazada por findAllActivePorCategoriaESOV
     */
    public static function findAllOcupacionIngresoActive()
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = [
                'O1' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O1_TIENE_TRABAJO_ACTUALMENTE),
                'O2' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O2_CUAL_ES_SU_OCUPACION_ACTUAL),
                'O3' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O3_QUE_ACTIVIDAD_ECONOMICA_TIENE_LA_EMPRESA_PARA_LA_CUAL_TRABAJAS),
                'O4' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O4_DE_QUE_TIPO_ES_TU_TRABAJO),
                'O5' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O5_TIENES_CONTRATO_DE_TRABAJO),
                'O6' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O6_QUE_TIPO_DE_CONTRATO_TIENE),
                'O7' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O7_CUANTAS_HORAS_TRABAJAS_A_LA_SEMANA),
                'O8' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O8_BUSCO_TRABAJO_REMUNERADO_EN_LOS_ULTIMOS_2_MESES),
                'O9' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O9_POR_QUE_NO_BUSCO_TRABAJO),
                'Y1' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_Y1_CUAL_FUE_EL_INGRESO_ANUAL_QUE_RECIBISTE_POR_TRABAJOS_U_OCUPACIONES),
                'Y2' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_Y2_CUAL_FUE_EL_INGRESO_ANUAL_QUE_RECIBISTE_POR_JUBILACIONES_O_PENSIONES),
                'Y3' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_Y3_SI_TIENES_OTROS_INGRESOS_ECONOMICOS_INDICAR_MONTO_ANUAL)
            ];
        } catch (Exception $exception) {
            throw $exception;
        }
        return $datosDeRespuesta;
    }

    /**
     * Esta funcion debería ser DEPRECATED ya que apunta a id especificos
     * debe ser reemplazada por findAllActivePorCategoriaESOV
     */
    public static function findOcupacionIngresoActiveByDependencia($valor)
    {
        $datosDeRespuesta = [];
        try {
            if ($valor == 1) {
                $datosDeRespuesta = [
                    'O2' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O2_CUAL_ES_SU_OCUPACION_ACTUAL),
                    'O3' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O3_QUE_ACTIVIDAD_ECONOMICA_TIENE_LA_EMPRESA_PARA_LA_CUAL_TRABAJAS),
                    'O4' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O4_DE_QUE_TIPO_ES_TU_TRABAJO),
                    'O5' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O5_TIENES_CONTRATO_DE_TRABAJO),
                    'O6' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O6_QUE_TIPO_DE_CONTRATO_TIENE),
                    'O7' => 'Valor numérico entre 1 y 60 según legislación laboral'
                ];
            } elseif ($valor == 2) {
                $datosDeRespuesta = [
                    'O8' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O8_BUSCO_TRABAJO_REMUNERADO_EN_LOS_ULTIMOS_2_MESES),
                    'O9' => self::findActivosByIdCategoria(Constante::CATEGORIA_PREGUNTA_O9_POR_QUE_NO_BUSCO_TRABAJO),
                ];
            }
            $datosDeRespuesta = array_merge($datosDeRespuesta, [
                'Y1. Ingresos anuales del trabajo' => 'numérico, requerido',
                'Y2. Jubilaciones o pensiones anuales.' => 'numérico, requerido',
                'Y3. Otros ingresos anuales' => 'numérico, requerido'
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
        return $datosDeRespuesta;
    }

    public static function findActivosByIdCategoria($idCategoria)
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = Respuestas::where('id_categoria', $idCategoria)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->orderBy('valor', 'ASC')
                ->get(['nombre', 'valor', 'skip_to_preg', 'skip_logic1', 'nombre_ciudadano', 'indicacion']);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    public static function findActivosByIdCategoriaOrden($idCategoria)
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = Respuestas::where('id_categoria', $idCategoria)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->get()
                ->pluck('nombre_ciudadano', 'valor')
                ->toArray();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    public static function findActivosByIdCategoriaAndId($idCategoria, $id)
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = Respuestas::select('nombre', 'nombre_ciudadano', 'valor')
                ->where('id_categoria', $idCategoria)
                ->where('valor', $id)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->orderBy('valor', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }

    public static function findActivosByIdCategoriaAndIdCiudadano($idCategoria, $id)
    {
        $datosDeRespuesta = null;
        try {
            $datosDeRespuesta = Respuestas::select('nombre_ciudadano', 'valor')
                ->where('id_categoria', $idCategoria)
                ->where('valor', $id)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->orderBy('valor', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRespuesta;
    }
}
