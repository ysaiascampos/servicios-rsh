<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\MatriculaEducacionSuperior
 *
 * @property int $MATRICULA_ED_SUP_ID
 * @property int|null $PERIODO_RSH
 * @property int $CAT_PERIODO
 * @property int|null $RUN_ALU
 * @property string|null $CODIGO_UNICO
 * @property int|null $CODIGO_RC
 * @property int|null $FEC_NAC_ALU
 * @property int|null $EDAD_ALU
 * @property int|null $FEC_MATR_ALU
 * @property int|null $ANIO_MAT_PRI_ANIO
 * @property int|null $SEM_MAT_PRI_ANIO
 * @property string|null $ANIO_ING_CARRERA
 * @property int|null $SEM_ING_CARRERA
 * @property int|null $COD_INST
 * @property string|null $NOMB_INST
 * @property string|null $NOMB_CARRERA
 * @property string|null $JORNADA
 * @property string|null $COMUNA_SEDE
 * @property int|null $MOTIVO
 * @property int|null $FEC_ING_EGR_RET
 * @property int|null $CODIGO_DE_INSTITUCION
 * @property int|null $VIGENTE
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $PERIODO_READ
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereANIOINGCARRERA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereANIOMATPRIANIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCATPERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCODIGODEINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCODIGORC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCODIGOUNICO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCODINST($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereCOMUNASEDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereEDADALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereFECINGEGRRET($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereFECMATRALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereFECNACALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereJORNADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereMATRICULAEDSUPID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereMOTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereNOMBCARRERA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereNOMBINST($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereRUNALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereSEMINGCARRERA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereSEMMATPRIANIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatriculaEducacionSuperior whereVIGENTE($value)
 * @mixin Eloquent
 */
class MatriculaEducacionSuperior extends Eloquent
{
    protected $table = 'matricula_ed_sup';
    protected $primaryKey = 'MATRICULA_ED_SUP_ID';
    public $timestamps = false;
}
