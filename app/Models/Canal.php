<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Canal
 *
 * @property int $ID
 * @property string $NOMBRE informacion del canal de donde proviene la informacion, es decir que aplicativo es la fuente de origen de la información
 * @method static \Illuminate\Database\Eloquent\Builder|Canal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Canal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Canal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Canal whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canal whereNOMBRE($value)
 * @mixin Eloquent
 */
class Canal extends Eloquent
{
    protected $table = 'CANAL';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
