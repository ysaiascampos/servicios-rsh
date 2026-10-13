<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\AprobadasAutomaticamente
 *
 * @property int $APROB_ID
 * @property int|null $APROB_ID_SOLICITUD
 * @property int|null $APROB_ID_SOLICITUD_ESTADO
 * @property int|null $APROB_SOLICITUD_DOC
 * @property int|null $APROB_ID_FICHA_SOLICITUD
 * @property int|null $APROB_ID_FICHA_VIGENTE
 * @property int|null $APROB_ID_FICHA_MENSUAL_SOL
 * @property int|null $APROB_ID_FICHA_MENSUAL_VIGENTE
 * @property int|null $APROB_ESTADO_EJECUCION
 * @property string|null $APROB_FECHA_EJECUCION
 * @property int|null $APROB_TIPO_SOLICITUD
 * @property int|null $APROB_FICHA_PERSONA
 * @property string|null $APROB_RESULTADO_TEXT
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente query()
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBESTADOEJECUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBFECHAEJECUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBFICHAPERSONA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDFICHAMENSUALSOL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDFICHAMENSUALVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDFICHASOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDFICHAVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBIDSOLICITUDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBRESULTADOTEXT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBSOLICITUDDOC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AprobadasAutomaticamente whereAPROBTIPOSOLICITUD($value)
 * @mixin Eloquent
 */
class AprobadasAutomaticamente extends Eloquent
{
    protected $table = 'ab_solicitud_aprob_auto';
    protected $primaryKey = 'APROB_ID';
    public $timestamps = false;
}
