<?php

namespace App\Models;

use Eloquent;

/*
 * contiene comunas, provincias, calles en la BD de SIT
 */

/**
 * App\Models\Objeto
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Objeto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Objeto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Objeto query()
 * @mixin Eloquent
 */
class Objeto extends Eloquent
{
    protected $table = 'objeto';
    protected $primaryKey = 'OBJETO_ID';
    public $timestamps = false;
    //protected $connection = 'pgsql';

    public function Hijos()
    {
        return $this->belongsToMany('Objeto', 'objeto_padre', 'objeto_padre_id', 'objeto_hijo_id');
        //return $this->hasMany('Objeto','objeto_id', 'objeto_hijo_id');
    }

}
