<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Programas
 *
 * @property int|null $ID
 * @property string|null $EJECUTOR
 * @property string|null $NOMBRE
 * @property int|null $ID_TIPO_PRESTACION
 * @property int|null $NIVEL_RUN
 * @property int|null $ID_VALORIZACION
 * @property int|null $ID_FRECUENCIA
 * @property int|null $PARTIDA
 * @property int|null $CAPITULO
 * @property int|null $PROGRAMA
 * @property int|null $SUBTITULO
 * @property int|null $ITEM
 * @property int|null $SUBITEM
 * @property string|null $DESCRIPCION
 * @property int|null $EXIGE_CHS
 * @property int|null $ID_RES
 * @property int|null $ID_CONVENIO
 * @property int|null $ESTADO
 * @property int|null $MES_CARGA
 * @property int|null $ID_ANT
 * @property string|null $DF_FECHA_ACTUALIZACION
 * @property int|null $TIPO_PROGRAMA
 * @property int|null $VISIBLE
 * @method static \Illuminate\Database\Eloquent\Builder|Programas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Programas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Programas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereCAPITULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereDFFECHAACTUALIZACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereEJECUTOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereEXIGECHS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDANT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDCONVENIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDFRECUENCIA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDRES($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDTIPOPRESTACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereIDVALORIZACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereITEM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereMESCARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereNIVELRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas wherePARTIDA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas wherePROGRAMA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereSUBITEM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereSUBTITULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereTIPOPROGRAMA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programas whereVISIBLE($value)
 * @mixin Eloquent
 */
class Programas extends Eloquent
{
    protected $table = 'r_programas';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
