<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use Eloquent;

/**
 * App\Models\ModalAviso
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModalAviso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModalAviso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModalAviso query()
 * @mixin \Eloquent
 */
class ModalAviso extends Eloquent
{
    protected $table = 'modal_aviso';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public static function findAllModalAviso()
    {
        $datosModalAviso = self::where('estado', Constante::ESTADO_1_ACTIVO)
        ->Select('id', 'titulo','mensaje')
        ->orderBy('orden')
        ->get();
        return $datosModalAviso;
    }
}
