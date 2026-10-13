<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TipoLocalizacion
 *
 * @property int $TIPO_LOCALIZACION_ID
 * @property int|null $TIPO_SOL_LOCALIZACION_ID
 * @property string|null $TIPO_LOCALIZACION_DESC
 * @property int|null $TIPO_LOCALIZACION_VIGENTE
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion whereTIPOLOCALIZACIONDESC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion whereTIPOLOCALIZACIONID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion whereTIPOLOCALIZACIONVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLocalizacion whereTIPOSOLLOCALIZACIONID($value)
 * @mixin Eloquent
 */
class TipoLocalizacion extends Eloquent
{
    protected $table = 'ab_tipo_localizacion'; /* tipo de localizacion */
    protected $primaryKey = 'TIPO_LOCALIZACION_ID';
    public $timestamps = false;
}
