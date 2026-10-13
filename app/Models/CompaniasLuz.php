<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\CompaniasLuz
 *
 * @property int $ID_COMPANIA_LUZ
 * @property string|null $NOMBRE_COMPANIA_LUZ
 * @property int|null $ID_REGION
 * @property int|null $COD_COMPANIA
 * @property int|null $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereIDCOMPANIALUZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereIDREGION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereCODCOMPANIA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereESTADO($value)
 * @mixin Eloquent
 */
class CompaniasLuz extends Eloquent
{
    protected $table = 'companias_luz';
    protected $primaryKey = 'ID_COMPANIA_LUZ';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_COMPANIA_LUZ',
        'ID_REGION',
        'COD_COMPANIA',
        'ESTADO'
    ];

    public static function getCompaniasLuz()
    {
        return self::orderBy('ID_COMPANIA_LUZ')
            ->where('ESTADO', 1)
            ->get();
    }
}
