<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SupervisionResponsable
 *
 * @property int $ID_SUPERVISION_RESPONSABLE
 * @property string $SUPERVISION_RESPONSABLE
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionResponsable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionResponsable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionResponsable query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionResponsable whereIDSUPERVISIONRESPONSABLE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionResponsable whereSUPERVISIONRESPONSABLE($value)
 * @mixin Eloquent
 */
class SupervisionResponsable extends Eloquent
{
    protected $table = 'ab_supervision_responsables';
    protected $primaryKey = 'id_supervision_responsable';
    public $timestamps = false;
}
