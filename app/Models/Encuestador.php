<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Encuestador
 *
 * @property int $ENC_ID
 * @property int $ENC_RUN
 * @property string $ENC_DV
 * @property bool $ENC_ACTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador query()
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador whereENCACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador whereENCDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador whereENCID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Encuestador whereENCRUN($value)
 * @mixin Eloquent
 */
class Encuestador extends Eloquent
{
    protected $table = 'ab_encuestador';
    protected $primaryKey = 'enc_id';
    public $timestamps = false;
}
