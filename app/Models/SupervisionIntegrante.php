<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SupervisionIntegrante
 *
 * @property int $ID_SUPERVISION_INTEGRANTE
 * @property int $ID_SUPERVISION
 * @property int $RUT_INTEGRANTE
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante whereIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante whereIDSUPERVISIONINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionIntegrante whereRUTINTEGRANTE($value)
 * @mixin Eloquent
 */
class SupervisionIntegrante extends Eloquent
{
    protected $table = 'ab_supervision_integrantes';
    protected $primaryKey = 'ID_SUPERVISION_INTEGRANTE';
    public $timestamps = false;

    public function Supervision()
    {
        return $this->belongsTo('Supervision');
    }

}
