<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TComplementoBienRaiz
 *
 * @property int $C_TCOMPLEMENTO_BIENRAIZ
 * @property string|null $N_TCOMPLEMENTO_BIENRAIZ
 * @method static \Illuminate\Database\Eloquent\Builder|TComplementoBienRaiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TComplementoBienRaiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TComplementoBienRaiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|TComplementoBienRaiz whereCTCOMPLEMENTOBIENRAIZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TComplementoBienRaiz whereNTCOMPLEMENTOBIENRAIZ($value)
 * @mixin Eloquent
 */
class TComplementoBienRaiz extends Eloquent
{
    protected $table = 'tcomplemento_bienraiz'; /* vista */
    protected $primaryKey = 'C_TCOMPLEMENTO_BIENRAIZ';
    public $timestamps = false;
}
