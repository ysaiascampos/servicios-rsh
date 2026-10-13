<?php

namespace App\Models;

use Eloquent;

/*
 * Contiene las relaciones entre comunas, ciudades, calles en la BD de SIT
 */

/**
 * App\Models\ObjetoPadre
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ObjetoPadre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjetoPadre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjetoPadre query()
 * @mixin Eloquent
 */
class ObjetoPadre extends Eloquent
{
    protected $table = 'objeto_padre';
    protected $primaryKey = 'objeto_padre_id';
    public $timestamps = false;
    //protected $connection = 'pgsql';
}
