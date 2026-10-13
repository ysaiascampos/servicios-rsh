<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Region
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @mixin Eloquent
 */
class Region extends Eloquent
{
    protected $table = 'regiones';
    protected $primaryKey = 'REG_ID';
    public $timestamps = false;

    public static function obtenerNombreRegion($reg_id = null)
    {
        if ($reg_id > 0) {
            $oRegion = Region::where('reg_id', $reg_id)
                ->first();
            if ($oRegion) {
                return $oRegion->REG_DESCRIPCION;
            } else {
                return 'ERROR - No corresponde';
            }
        } else {
            return 'No hay región asociada';
        }
    }

    /**
     * regiones: 16
     * comunas: 346
     * localidades: 10417
     */
    public static function getRegionComunaLocalidad()
    {
        $locacionRegion = Region::join('comunas_ine c', 'c.com_region', '=', 'reg_id')
            ->join('vas_localidad l', 'l.comuna_ine', '=', 'c.com_id')
            ->orderBy('reg_id')
            ->orderBy('c.com_descripcion')
            ->orderBy('l.nombre')
            ->select(
                'regiones.reg_id',
                'regiones.reg_descripcion',
                'c.com_id',
                'c.com_descripcion',
                'l.id_localidad',
                'l.nombre',
                'l.zona'
            )
            ->get();
        return $locacionRegion;
    }
}
