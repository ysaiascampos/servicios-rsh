<?php

namespace App\Models;

use App\Clases\DatosDeFuentes;
use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;


/**
 * App\Models\PersonasExport
 *
 * @property int $PER_RUN
 * @property string|null $PER_DV
 * @property string|null $PER_NOMBRES
 * @property string|null $PER_APELLIDOPATERNO
 * @property string|null $PER_APELLIDOMATERNO
 * @property int|null $PER_FECHANACIMIENTO
 * @property int|null $PER_EDAD
 * @property int|null $PER_SEXO_ID
 * @property string|null $PER_SEXO
 * @property int|null $PER_NACIONALIDAD_ID
 * @property string|null $PER_NACIONALIDAD
 * @property string|null $PER_DIRECCION
 * @property string|null $PER_EMAIL
 * @property string|null $PER_TELEFONO
 * @property string|null $PER_CELULAR
 * @property int|null $PER_FECHADEFUNCION
 * @property string|null $PER_FIC_TUTORLEGAL
 * @property string|null $PER_FIC_PUEBLOORIGINARIO
 * @property int|null $PER_FIC_NUMPAREJA
 * @property int|null $PER_FIC_INGRESOANUAL
 * @property int|null $PER_FIC_INGRESOJUBILACION
 * @property int|null $PER_FIC_INGRESOOTROS
 * @property int|null $PER_FIC_FECHAAPLICACION
 * @property int|null $PER_FIC_FECHAREVISION
 * @property int|null $PER_FIC_PENSIONADMIN
 * @property int|null $PER_FIC_DIFICVISUAL
 * @property int|null $PER_FIC_DIFICAUDITIVA
 * @property int|null $PER_FIC_DIFICHABLA
 * @property int|null $PER_FIC_DIFICFISICA
 * @property int|null $PER_FIC_DIFICMENTAL
 * @property int|null $PER_FIC_DIFICPSIQUICA
 * @property int|null $PER_FIC_CALLESINAYUDA
 * @property int|null $PER_FIC_MEDICOSINAYUDA
 * @property int|null $PER_FIC_ASEOSINAYUDA
 * @property int|null $PER_FIC_DESPLAZAMIENTOENCASA
 * @property int|null $PER_FIC_CTRLESFINTERES
 * @property int|null $PER_FIC_ASISTEESTABEDUCACIONAL
 * @property int|null $PER_FIC_CURSO
 * @property int|null $PER_FIC_TIPOEDUCACION_ID
 * @property string|null $PER_FIC_TIPOEDUCACION
 * @property int|null $PER_MAT_MENSUALIDAD
 * @property int|null $PER_MAT_CODIGODEPENDENCIA
 * @property int|null $PER_FIC_TRABAJA
 * @property string|null $PER_FIC_OCUPACIONACTUAL
 * @property int|null $PER_FIC_PARENTESCOID
 * @property string|null $PER_FIC_PARENTESCO
 * @property int|null $PER_FIC_GRUPOFAMILIAR_ID
 * @property int|null $PER_COT_MESESCOTIZADOS
 * @property int|null $PER_COT_TOTALRENTAIMPONIBLE
 * @property string|null $PER_COT_TIPOTRABAJADOR
 * @property int|null $PER_F22_CALCULADO
 * @property int|null $PER_DJF22_HON_1879
 * @property int|null $PER_DJF22_HON_1812
 * @property int|null $PER_DJF22_HON_1887
 * @property int|null $PER_AFC_MESESCOTIZADOS
 * @property int|null $PER_AFC_TOTALRENTAIMPONIBLE
 * @property string|null $PER_AFC_TIPOCONTRATO
 * @property int|null $PER_VEH_TOTALVEHICULOS
 * @property int|null $PER_VEH_TOTALAVALUO
 * @property int|null $PER_PRO_TOTALPROPIEDADES
 * @property int|null $PER_PRO_TOTALAVALUO
 * @property string|null $PER_FECHAINGRESO
 * @property int|null $CALC_COLEGIO_COSTOSO
 * @property int|null $CALC_ISAPRECARGA_COSTOSO
 * @property int|null $CALC_VEHICULOS_COSTOSOS
 * @property int|null $CALC_PROPIEDADES_COSTOSAS
 * @property int|null $CALC_INGRESO_PENSION
 * @property int|null $CALC_INGRESO_LABORAL
 * @property int|null $CALC_FUENTE_INGRESOLABORAL
 * @property int|null $CALC_INGRESO_CAPITAL
 * @property int|null $CALC_INGRESO_HONORARIO
 * @property int|null $CALC_INGRESO_DEFINITIVO
 * @property int|null $CONDICION_DEPENDENCIA
 * @property string|null $CALC_PESO
 * @property int|null $PER_PEN_MESESCOTIZADOS
 * @property string|null $PER_PEN_CALCULADO
 * @property int|null $PER_FIC_INFORMANTE
 * @property int|null $PER_FIC_NOASISTEMOTIVO
 * @property int|null $PER_FIC_HORASTRABAJO
 * @property int|null $PER_FIC_BUSCOTRABAJO
 * @property int|null $PER_FIC_NOBUSCOTRABAJOMOTIVO
 * @property string|null $PER_F22_COD_110
 * @property string|null $PER_F22_COD_161
 * @property int|null $PER_AFP_RENTA_DEPENDIENTE
 * @property int|null $PER_AFP_RENTA_INDEPENDIENTE
 * @property int|null $PER_AFP_MESES_DEPENDIENTE
 * @property int|null $PER_AFP_MESES_INDEPENDIENTE
 * @property int|null $PER_PEN_COD_INSTIT
 * @property int|null $PER_PEN_COD_BENEF
 * @property int|null $PER_PEN_COD_TIPOBE
 * @property int|null $PER_COT_COTIZACIONPACTADA
 * @property int|null $PER_SII_INFORME
 * @property int $PER_ID
 * @property int|null $CALC_TRAMO_LABORAL_MIN
 * @property int|null $CALC_TRAMO_LABORAL_MAX
 * @property int|null $CALC_TRAMO_CAPITAL_MIN
 * @property int|null $CALC_TRAMO_CAPITAL_MAX
 * @property int|null $CALC_TRAMO_PENSION_MIN
 * @property int|null $CALC_TRAMO_PENSION_MAX
 * @property int|null $CALC_TRAMO_DEF_MIN
 * @property int|null $CALC_TRAMO_DEFL_MAX
 * @property int|null $CALC_FUENTE_PENSION
 * @property int|null $DISCAP_POSTRADO
 * @property int|null $DISCAP_MODERADO
 * @property int|null $DISCAP_AUTOVALENTE
 * @property int|null $DISCAP_LEVE
 * @property int|null $PER_FIC_CODIGOSRAMA
 * @property int|null $PER_FIC_CODIGOCONTRATO
 * @property int|null $PER_FIC_RELACIONCONTRACTUAL
 * @property int|null $PER_VIGENTE
 * @property int|null $PER_EMBARAZO
 * @property int|null $PER_INGRESO_EQ_CORREG
 * @property int|null $PER_FIC_TEMPPERMANEN
 * @property int|null $FICHA_ID
 * @property int|null $FICHA_IDSOLICITUD
 * @property int|null $BNFGRUPFAMI_ID
 * @property int|null $BNFGRUPFAMI_IDSOLICITUD
 * @property int|null $CALC_ISAPRECARGA_MCOSTOSO
 * @property int|null $CALC_VEHICULOS_MCOSTOSOS
 * @property int|null $CALC_PROPIEDADES_MCOSTOSAS
 * @property int|null $PENSION_APORTE
 * @property int|null $PENSION_DESCUENTO
 * @property int|null $MEDIO_PADRE_NO_PRESEN_AMB
 * @property int|null $RND_CSE
 * @property int|null $ESCOLARIDAD
 * @property string|null $FUENTE_ESCOLARIDAD
 * @property int|null $FUENTE_K
 * @property int|null $COTIZANTE_CARGA
 * @property int|null $ED_SUPERIOR
 * @property int|null $ESTUDIA_TRABAJA
 * @property int|null $ESTUDIA
 * @property int|null $P_JURIDICA
 * @property int|null $DEPEN_SEVERA
 * @property int|null $MARCA_NEE
 * @property int|null $FUENTE_DEPENDENCIA
 * @property int|null $CUIDADOR
 * @property int|null $DEPENDIENTE
 * @property int|null $CUIDADOR_PRINCIPAL
 * @property string|null $ID_RUN_FECHA_ORIGEN_ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonasExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonasExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonasExport query()
 * @mixin \Eloquent
 */
class PersonasExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'personas';
    protected $primaryKey = 'PER_ID';
    public $timestamps = false;
    protected $hidden = ['rn'];
    protected $appends = [
        'INFORMANTE',
        'ID_PARENTESCO',
        'NOMBRE_PARENTESCO',
        'TIENE_RSH'
    ];
    protected $filled = [];

    public function getInformanteAttribute()
    {
        return $this->attributes['PER_FIC_INFORMANTE'];
    }

    public function getIDParentescoAttribute()
    {
        return $this->attributes['PER_FIC_PARENTESCOID'];
    }

    public function getNombreParentescoAttribute()
    {
        return $this->attributes['PER_FIC_PARENTESCO'];
    }

    public function getTieneRshAttribute()
    {
        if (!isset($this->attributes['TIENE_RSH'])) {
            $this->attributes['TIENE_RSH'] = Personas::tieneRSH($this->attributes['PER_ID']);
        }
        return $this->attributes['TIENE_RSH'];
    }

    public static function getModel()
    {
        return new PersonasExport();
    }

    public static function findByRunCiudadano($runCiudadano)
    {
        $datosDePersona = null;
        try {
            $datosDePersona = self::getModel()
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_run', $runCiudadano)
                ->orderBy('per_id', 'desc')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePersona;
    }

    public static function findByIdGrupoFamiliarExport($idGrupoFamiliar)
    {
        $datosDePersona = null;
        try {
            $datosDePersona = self::getModel()
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_fic_grupofamiliar_id', $idGrupoFamiliar)
                ->orderBy('per_fic_parentescoid')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePersona;
    }

    public static function situacionUnicoPerID($runPersona)
    {
        try {
            $registros = self::where('per_run', $runPersona)
                ->select(['PER_ID'])
                ->get()
                ->pluck('PER_ID')
                ->toArray();
            $situacionUnicoPerID = [
                'situacion' => (count($registros) <= 1),
                'per-id' => $registros
            ];
        } catch (Exception $e) {
            $situacionUnicoPerID = [
                'situacion' => true,
                'per-id' => []
            ];
            Log::error("Hubo problemas para analizar el integrante RUN (EXPORT)" . $runPersona);
        }
        return $situacionUnicoPerID;
    }

    public static function situacionUnicoFolio($runPersona)
    {
        try {
            $gruposFamiliares = self::join('grupos_familiares', 'grupos_familiares.grupfami_id', '=',
                'personas.per_fic_grupofamiliar_id')
                ->where('personas.per_run', $runPersona)
//                ->where('personas.per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('personas.per_vigente', Constante::VIGENTE_1_VIGENTE)
//                ->where('grupos_familiares.grupfami_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('grupos_familiares.grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->select(['grupos_familiares.grupfami_nuevofolio'])
                ->get();
            $nuevosfolios = $gruposFamiliares->pluck('GRUPFAMI_NUEVOFOLIO')
                ->toArray();
            $multiplesFolios = [
                'situacion' => (count($nuevosfolios) <= 1),
                'folios' => $nuevosfolios
            ];
        } catch (Exception $e) {
            $multiplesFolios = [
                'situacion' => false,
                'folios' => []
            ];
            Log::error("Hubo problemas para analizar el integrante RUN Export" . $runPersona);
        }
        return $multiplesFolios;
    }

    public static function detalleIngresoExport($integrante)
    {
        $detalleIngreso = [
            'ingreso-trabajo' => [
                'monto' => null,
                'fuente-nombre' => null,
                'fuente-descripcion' => null,
                'fuente-codigo' => null
            ],
            'ingreso-pension' => [
                'monto' => null,
                'fuente-nombre' => null,
                'fuente-descripcion' => null,
                'fuente-codigo' => null
            ],
            'ingreso-capital' => [
                'monto' => null,
                'fuente-nombre' => null,
                'fuente-descripcion' => null,
                'fuente-codigo' => null
            ]
        ];
        try {
            if (is_object($integrante)) {
                try {
                    $listadoFuentes = DatosDeFuentes::getListaFuentes();
                } catch (Exception $exception) {
                    log::error('Problema al obtener las fuentes para los tramites ARC de ingreso');
                    $listadoFuentes = null;
                }

                $fuenteTrabajoNombre = !(is_null($integrante->CALC_FUENTE_INGRESOLABORAL)) ?
                    $listadoFuentes['tipos-fuentes'][$integrante->CALC_FUENTE_INGRESOLABORAL]['descripcion'] : null;
                $fuenteTrabajoDescripcion = !(is_null($fuenteTrabajoNombre)) ?
                    $listadoFuentes['tipos-fuentes'][$integrante->CALC_FUENTE_INGRESOLABORAL]['descripcion-larga'] : null;
                $fuenteTrabajoCodigo = !(is_null($fuenteTrabajoNombre)) ?
                    $integrante->CALC_FUENTE_INGRESOLABORAL : null;

                $fuentePensionNombre = !(is_null($integrante->CALC_FUENTE_PENSION)) ?
                    $listadoFuentes['tipos-fuentes'][$integrante->CALC_FUENTE_PENSION]['descripcion'] : null;
                $fuentePensionDescripcion = !(is_null($fuentePensionNombre)) ?
                    $listadoFuentes['tipos-fuentes'][$integrante->CALC_FUENTE_PENSION]['descripcion-larga'] : null;
                $fuentePensionCodigo = !(is_null($fuentePensionNombre)) ?
                    $integrante->CALC_FUENTE_PENSION : null;

                $fuenteCapitalNombre = !(is_null($integrante->CALC_INGRESO_CAPITAL)) && (int)$integrante->CALC_INGRESO_CAPITAL > 0 ?
                    $listadoFuentes['tipos-fuentes'][Constante::FUENTE_200_INGRESO_DE_CAPITAL]['descripcion'] : null;
                $fuenteCapitalDescripcion = !(is_null($integrante->CALC_INGRESO_CAPITAL)) && (int)$integrante->CALC_INGRESO_CAPITAL > 0 ?
                    $listadoFuentes['tipos-fuentes'][Constante::FUENTE_200_INGRESO_DE_CAPITAL]['descripcion-larga'] : null;
                $fuenteCapitalCodigo = !(is_null($integrante->CALC_INGRESO_CAPITAL)) && (int)$integrante->CALC_INGRESO_CAPITAL > 0 ?
                    Constante::FUENTE_200_INGRESO_DE_CAPITAL : null;


                $detalleIngreso = [
                    'ingreso-trabajo' => [
                        'monto' => $integrante->CALC_INGRESO_LABORAL,
                        'fuente-nombre' => $fuenteTrabajoNombre,
                        'fuente-descripcion' => $fuenteTrabajoDescripcion,
                        'fuente-codigo' => $fuenteTrabajoCodigo
                    ],
                    'ingreso-pension' => [
                        'monto' => $integrante->CALC_INGRESO_PENSION,
                        'fuente-nombre' => $fuentePensionNombre,
                        'fuente-descripcion' => $fuentePensionDescripcion,
                        'fuente-codigo' => $fuentePensionCodigo
                    ],
                    'ingreso-capital' => [
                        'monto' => $integrante->CALC_INGRESO_CAPITAL,
                        'fuente-nombre' => $fuenteCapitalNombre,
                        'fuente-descripcion' => $fuenteCapitalDescripcion,
                        'fuente-codigo' => $fuenteCapitalCodigo
                    ]
                ];
            }
        } catch (Exception $e) {
            Log::error("Problema al armar el detalle de Ingreso: " . $e);
        }
        return ($detalleIngreso);
    }
    public static function findByRunCiudadanoCuidadora($runCiudadano)
    {
        $datosDePersona = null;
        try {
            $datosDePersona = self::getModel()
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_run', $runCiudadano)
                ->select('PER_RUN','PER_DV','PER_NOMBRES','PER_APELLIDOPATERNO','PER_APELLIDOMATERNO',
                    'PER_FECHANACIMIENTO','PER_EDAD','PER_SEXO','CUIDADOR','DEPENDIENTE','CUIDADOR_PRINCIPAL',
                    'ID_RUN_FECHA_ORIGEN_ESTADO')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePersona;
    }

    public static function findByIdGrupoFamiliarExportJefeFamiliar($idGrupoFamiliar)
    {
        $datosDePersona = null;
        try {
            $datosDePersona = self::getModel()
                ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_fic_grupofamiliar_id', $idGrupoFamiliar)
                ->where('per_fic_parentescoid', Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                ->orderBy('per_fic_parentescoid')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePersona;
    }
}
