<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ModuloPerfil
 *
 * @property int $ID
 * @property int $ID_MODULO
 * @property int $ID_PERFIL
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil whereIDMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuloPerfil whereIDPERFIL($value)
 * @mixin Eloquent
 */
class ModuloPerfil extends Eloquent
{

    protected $table = 'MODULO_PERFIL';
    protected $primaryKey = 'ID';
    public $timestamps = false;

//    public function Perfil()
//    {
//        return $this->hasMany('Perfil', 'id_perfil', 'id_perfil');
//    }
//
//    public function Modulo()
//    {
//        return $this->hasMany('Modulo_Perfil', 'id_modulo', 'id_modulo');
//    }

}


