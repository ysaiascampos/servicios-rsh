<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\CompaniasLuz
 *
 * @property int $ID_COMPANIA_AGUA
 * @property string|null $NOMBRE_COMPANIA_AGUA
 * @property int|null $ID_REGION
 * @property int|null $RUN_COMPANIA
 * @property string|null $DV
 * @property int|null $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereIDREGION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereCODCOMPANIA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompaniasLuz whereESTADO($value)
 * @mixin Eloquent
 */
class CompaniasAgua extends Eloquent
{
    protected $table = 'COMPANIAS_AGUA';
    protected $primaryKey = 'ID_COMPANIA_AGUA';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_COMPANIA_AGUA',
        'ID_REGION',
        'COD_COMPANIA',
        'ESTADO'
    ];

    public static function getCompaniasAgua()
    {
        return self::orderBy('ID_COMPANIA_AGUA')
            ->where('ESTADO', 1)
            ->get();
    }
}
