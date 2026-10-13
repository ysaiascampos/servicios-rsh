<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Identidades
 *
 * @property int $IDENT_ID
 * @property string|null $IDENT_DESCRIPCION
 * @method static \Illuminate\Database\Eloquent\Builder|Identidades newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identidades newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identidades query()
 * @method static \Illuminate\Database\Eloquent\Builder|Identidades whereIDENTDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identidades whereIDENTID($value)
 * @mixin Eloquent
 */
class Identidades extends Eloquent
{
    protected $table = 'ab_identidad_genero';
    protected $primaryKey = 'IDENT_ID';
    public $timestamps = false;
}
