<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Sii
 *
 * @property int $SII_F22_ID_I
 * @property int|null $RUT
 * @property int|null $ANNO_COMERCIAL
 * @property int|null $ANNIO_TRIBUTARIO
 * @property bool|null $ING_ALTOS_FLAG
 * @property bool|null $KAPITAL_FLAG
 * @property bool|null $DJ_1887_FLAG
 * @property bool|null $F22_161_FLAG
 * @property bool|null $F22_110_FLAG
 * @property bool|null $DJ_1879_FLAG
 * @property bool|null $DJ_1812_PENS_FLAG
 * @property int|null $KAPITAL_SUM
 * @property int|null $PENSION_DJ1812_SUM
 * @property int|null $ING_INDEPENDIENTE_SUM
 * @property int|null $ING_DEPENDIENTE_SUM
 * @property int|null $KAPITAL_SUM_COMPL
 * @property int|null $MES_1_COMPL_DJ1812
 * @property int|null $MES_2_COMPL_DJ1812
 * @property int|null $MES_3_COMPL_DJ1812
 * @property int|null $MES_4_COMPL_DJ1812
 * @property int|null $MES_5_COMPL_DJ1812
 * @property int|null $MES_6_COMPL_DJ1812
 * @property int|null $MES_7_COMPL_DJ1812
 * @property int|null $MES_8_COMPL_DJ1812
 * @property int|null $MES_9_COMPL_DJ1812
 * @property int|null $MES_10_COMPL_DJ1812
 * @property int|null $MES_11_COMPL_DJ1812
 * @property int|null $MES_12_COMPL_DJ1812
 * @property int|null $MES_1_COMPL_INDEP
 * @property int|null $MES_2_COMPL_INDEP
 * @property int|null $MES_3_COMPL_INDEP
 * @property int|null $MES_4_COMPL_INDEP
 * @property int|null $MES_5_COMPL_INDEP
 * @property int|null $MES_6_COMPL_INDEP
 * @property int|null $MES_7_COMPL_INDEP
 * @property int|null $MES_8_COMPL_INDEP
 * @property int|null $MES_9_COMPL_INDEP
 * @property int|null $MES_10_COMPL_INDEP
 * @property int|null $MES_11_COMPL_INDEP
 * @property int|null $MES_12_COMPL_INDEP
 * @property int|null $MES_1_COMPL_DEP
 * @property int|null $MES_2_COMPL_DEP
 * @property int|null $MES_3_COMPL_DEP
 * @property int|null $MES_4_COMPL_DEP
 * @property int|null $MES_5_COMPL_DEP
 * @property int|null $MES_6_COMPL_DEP
 * @property int|null $MES_7_COMPL_DEP
 * @property int|null $MES_8_COMPL_DEP
 * @property int|null $MES_9_COMPL_DEP
 * @property int|null $MES_10_COMPL_DEP
 * @property int|null $MES_11_COMPL_DEP
 * @property int|null $MES_12_COMPL_DEP
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $PERIODO_READ
 * @method static \Illuminate\Database\Eloquent\Builder|Sii newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sii newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sii query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereANNIOTRIBUTARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereANNOCOMERCIAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereDJ1812PENSFLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereDJ1879FLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereDJ1887FLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereF22110FLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereF22161FLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereINGALTOSFLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereINGDEPENDIENTESUM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereINGINDEPENDIENTESUM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereKAPITALFLAG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereKAPITALSUM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereKAPITALSUMCOMPL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES10COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES10COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES10COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES11COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES11COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES11COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES12COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES12COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES12COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES1COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES1COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES1COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES2COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES2COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES2COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES3COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES3COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES3COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES4COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES4COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES4COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES5COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES5COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES5COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES6COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES6COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES6COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES7COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES7COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES7COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES8COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES8COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES8COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES9COMPLDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES9COMPLDJ1812($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereMES9COMPLINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii wherePENSIONDJ1812SUM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereSIIF22IDI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sii whereVIGENTE($value)
 * @mixin Eloquent
 */
class Sii extends Eloquent
{
    protected $table = 'ever_sii';
    protected $primaryKey = 'SII_F22_ID_I';
    public $timestamps = false;
}
