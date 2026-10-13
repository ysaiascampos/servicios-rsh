<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ValorUf
 *
 * @property int $ID
 * @property string $FECHA
 * @property int $VALOR_UF_DIA
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ValorUf newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ValorUf newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ValorUf query()
 * @mixin \Eloquent
 */
class ValorUf extends Eloquent
{
    protected $table = 'valor_uf';
    protected $primaryKey = 'ID';
    public $timestamps = false;

}
