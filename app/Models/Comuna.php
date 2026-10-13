<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Comuna
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Comuna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comuna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comuna query()
 * @mixin Eloquent
 */
class Comuna extends Eloquent
{
    protected $table = 'comunas_ine';
    protected $primaryKey = 'COM_ID';
    public $timestamps = false;

    public static function obtenerNombreComuna($com_id = null)
    {
        if ($com_id > 0) {
            $oComuna = Comuna::where('com_id', $com_id)
                ->first();
            if ($oComuna) {
                return $oComuna->COM_DESCRIPCION;
            } else {
                return 'ERROR - No corresponde';
            }
        } else {
            return 'No hay comuna asociada';
        }
    }

    public function obtenerEncargadoComunal($com_id = null)
    {
        return self::obtenerEncargadoComunalParaCiudadano($com_id);

    }

    /**
     * Esta funcion entrega el Encargado Comunal de la comuna,
     * y si no existe extrega Apoyo Encargado Comunal o Atención de Caso de la misma comuna
     * Se conserva el mismo criterio anterior entrega el run mayor si hay varios, es decir la persona más joven
     */
    public static function obtenerEncargadoComunalParaCiudadano($idComuna = null)
    {
        $usuarioAsignadoCiudadano = null;
        if ($idComuna > 0) {
            $perfilesAConsultar = [
                Constante::PERFIL_8_ATENCION_DE_CASO,
                Constante::PERFIL_12_ENCARGADO_COMUNAL,
                Constante::PERFIL_17_APOYO_ENCARGADO_COMUNAL
            ];

            $resultSet = DB::table('personas PER')
                ->join('ab_usuario USU', 'PER.per_run', '=', 'USU.rut_usuario')
                ->join('ab_usuario_comuna COM', 'USU.rut_usuario', '=', 'COM.rut_usuario')
                ->join('comunas_ine COMU', 'COMU.com_id', '=', 'COM.com_id')
                ->join('ab_perfil P', 'USU.id_perfil', '=', 'P.id_perfil')
                ->where('COMU.com_id', $idComuna)
                ->where('USU.id_estado', Constante::ESTADO_1_ACTIVO)
                ->whereIn('USU.id_perfil', $perfilesAConsultar)
                ->groupBy('USU.id_perfil')
                ->select(DB::raw('max(per_run) usuario_asignado, USU.id_perfil'))
                ->pluck('USUARIO_ASIGNADO', 'ID_PERFIL');

            if (isset($resultSet[Constante::PERFIL_12_ENCARGADO_COMUNAL])) {
                $usuarioAsignadoCiudadano = $resultSet[Constante::PERFIL_12_ENCARGADO_COMUNAL];
            } else {
                if (isset($resultSet[Constante::PERFIL_17_APOYO_ENCARGADO_COMUNAL])) {
                    $usuarioAsignadoCiudadano = $resultSet[Constante::PERFIL_17_APOYO_ENCARGADO_COMUNAL];
                } else {
                    if (isset($resultSet[Constante::PERFIL_8_ATENCION_DE_CASO])) {
                        $usuarioAsignadoCiudadano = $resultSet[Constante::PERFIL_8_ATENCION_DE_CASO];
                    }
                }
            }
            return $usuarioAsignadoCiudadano;
        } else {
            return $usuarioAsignadoCiudadano;
        }

    }

    /**
     * UV por comuna
     * unidad vecinal: 6866
     */
    public static function getComunaUV()
    {
        return Comuna::join('vas_unidad_vecinal uv', 'uv.comuna_ine', '=', 'com_id')
            ->orderBy('uv.nombre')
            ->select(
                'comunas_ine.com_id',
                'uv.uv_carto',
                'uv.nombre'
            )
            ->get();
    }

    /**
     * AH por comuna
     * agrupación habitacional: 55801
     */
    public static function getComunaAH()
    {
        return Comuna::join('vas_ah ah', 'ah.comuna_ine', '=', 'com_id')
            ->orderBy('ah.nombre')
            ->select(
                'comunas_ine.com_id',
                'ah.id_ah',
                'ah.nombre'
            )
            ->get();
    }
}

