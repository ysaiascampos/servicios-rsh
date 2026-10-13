<?php

namespace App\Models;

use Eloquent;
use Exception;

/**
 * App\Models\TramoQuintil
 *
 * @property int $ID
 * @property string|null $RANGO
 * @property string|null $NOMBRE
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TramoQuintil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TramoQuintil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TramoQuintil query()
 * @mixin \Eloquent
 */
class TramoQuintil extends Eloquent
{
    protected $table = 'ab_tramo_quintil';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $hidden = ['rn'];

    public static function getModel()
    {
        return new TramoQuintil();
    }

    public static function findAll()
    {
        $datosDeTramoQuintil = null;
        try {
            $datosDeTramoQuintil = TramoQuintil::getModel()
                ->orderBy('ID', 'ASC')
                ->pluck('NOMBRE', 'ID');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeTramoQuintil;
    }
}
