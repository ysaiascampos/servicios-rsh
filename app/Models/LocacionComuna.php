<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\LocacionComuna
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionComuna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionComuna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionComuna query()
 * @mixin Eloquent
 */
class LocacionComuna extends Eloquent
{
    protected $table = 'comunas_ine';
    public $timestamps = false;

    public static function getModel()
    {
        return new LocacionComuna;
    }

    public static function findAll()
    {
        $datosDeComuna = null;
        try {
            $datosDeComuna = LocacionComuna::getModel()
                ->select(['com_id as codigo', 'com_descripcion as descripcion'])
                ->orderBy('descripcion', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeComuna;
    }

    public static function findByComunaIne($comunaIne)
    {
        $datosDeComuna = null;
        try {
            $datosDeComuna = LocacionComuna::getModel()
                ->select(['com_id as codigo', 'com_descripcion as descripcion'])
                ->where('com_id', $comunaIne)
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeComuna;
    }

    public static function getAllPorComuna($comunaIne)
    {
        $locacionComuna = null;
        try {
            $locacionComuna = LocacionComuna::getModel()
                ->where('com_id', $comunaIne)
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionComuna;
    }

    public static function getComunaPorRegion($regionCodigo)
    {
        $locacionComuna = null;
        try {
            $locacionComuna = LocacionComuna::getModel()
                ->select(['com_id as objeto_codigo', 'com_descripcion as objeto_nomb'])
                ->where('com_region', $regionCodigo)
                ->orderBy('com_descripcion', 'asc')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionComuna;
    }

    public static function getComunaPorRegionSelect($regionCodigo)
    {
        $locacionComuna = null;
        try {
            $locacionComuna = LocacionComuna::getModel()
                ->where('com_region', $regionCodigo)
                ->orderBy('com_descripcion', 'asc')
                ->pluck('COM_DESCRIPCION', 'COM_ID');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionComuna;
    }

    public static function getComunaPorRegionFull($regionCodigo)
    {
        $locacionComuna = null;
        try {
            $locacionComuna = LocacionComuna::getModel()
                ->where('com_region', $regionCodigo)
                ->orderBy('com_descripcion', 'asc')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionComuna;
    }

    public static function getNombreComuna($comunaIne)
    {
        if (empty($comunaIne)) {
            return null;
        }

        $sql = "SELECT ASIGNACION_EXPORT.FN_NOMBRE_COMUNA(".$comunaIne.") AS nombre FROM DUAL";
        $row = DB::connection('oracle_export')->selectOne($sql);

        $nombre = $row->nombre ?? $row->NOMBRE ?? '';
        $nombre = trim((string)$nombre);

        if ($nombre === '' || stripos($nombre, 'ERROR') === 0) {
            return null;
        }

        return $nombre;
    }
}
