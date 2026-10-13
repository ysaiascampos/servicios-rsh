<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SupervisionDocExtra
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion query()
 * @mixin Eloquent
 */
class SupervisionDocExtra extends Eloquent
{
    protected $table = 'supervision_doc_extra'; /* vista */
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;
}
