<?php

namespace App\Models;

use App\Utiles;
use DB;
use Eloquent;
use Exception;

/**
 * App\Models\LocacionRegion
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionRegion query()
 * @mixin Eloquent
 */
class LocacionRegion extends Eloquent
{
    protected $table = 'regiones';
    public $timestamps = false;

    public static function getModel()
    {
        return new LocacionRegion;
    }

    public static function getRegion()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = LocacionRegion::getModel()
                ->select(['reg_id as objeto_codigo', 'reg_descripcion as objeto_nomb'])
                ->orderBy('objeto_codigo', 'asc')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    public static function getRegionSelect()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = LocacionRegion::getModel()
                ->select(['reg_id', 'reg_descripcion'])
                ->orderBy('reg_id', 'asc')
                ->pluck('REG_DESCRIPCION', 'REG_ID');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    public static function getRegionFull()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = LocacionRegion::getModel()
                ->orderBy('reg_id', 'asc')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    public static function findByComunaIne($comunaIne)
    {
        $idRegion = Utiles::getRegionDesdeCodigoComuna($comunaIne);
        $datosDeRegion = null;
        try {
            $datosDeRegion = LocacionRegion::getModel()
                ->where('reg_id', (int)$idRegion)
                ->orderBy('reg_id', 'asc')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeRegion;
    }

    public static function getRegionPorComuna($idComuna)
    {
        $idRegion = Utiles::getRegionDesdeCodigoComuna($idComuna);
        $locacionRegion = null;
        try {
            $locacionRegion = LocacionRegion::getModel()
                ->where('reg_id', (int)$idRegion)
                ->orderBy('reg_id', 'asc')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    /**
     * regiones: 16
     * comunas: 346
     * localidades: 10417
     */
    public static function getRegionComunaLocalidad()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = DB::table('regiones r')
                ->join('comunas_ine c', 'c.com_region', '=', 'r.reg_id')
                ->join('vas_localidad l', 'l.comuna_ine', '=', 'c.com_id')
                ->orderBy('r.reg_id, c.com_descripcion, l.nombre')
                ->select(
                    'r.reg_id as "region-id"',
                    'r.reg_descripcion as "region-nombre"',
                    'c.com_id as "comuna-ine"',
                    'c.com_descripcion as "comuna-nombre"',
                    'l.id_localidad as "loc-id"',
                    'l.nombre as "loc-nombre"',
                    'l.zona as "loc-tipo-zona"'
                )
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    /**
     * UV por comuna
     * unidad vecinal: 6866
     */
    public static function getComunaUV()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = DB::table('comunas_ine c')
                ->join('vas_unidad_vecinal uv', 'uv.comuna_ine', '=', 'c.com_id')
                ->orderBy('uv.nombre')
                ->select(
                    'c.com_id',
                    'uv.uv_carto',
                    'uv.nombre'
                )
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }

    /**
     * AH por comuna
     * agrupación habitacional: 55801
     */
    public static function getComunaAH()
    {
        $locacionRegion = null;
        try {
            $locacionRegion = DB::table('comunas_ine c')
                ->join('vas_ah ah', 'ah.comuna_ine', '=', 'c.com_id')
                ->orderBy('ah.nombre')
                ->select(
                    'c.com_id',
                    'ah.id_ah',
                    'ah.nombre'
                )
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionRegion;
    }
}
