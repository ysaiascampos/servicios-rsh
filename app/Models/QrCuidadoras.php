<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\QrCuidadoras
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QrCuidadoras newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QrCuidadoras newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QrCuidadoras query()
 * @mixin \Eloquent
 */
class QrCuidadoras extends Eloquent
{
    protected $table = 'qr_cuidadoras';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    public static function findByRunCiudadano($runCiudadano)
    {
        $datosDePersona = null;
        try {
            $datosDePersona = self::getModel()
                ->where('RUN_CUIDADOR', $runCiudadano)
                ->where('ESTADO', Constante::ESTADO_1_ACTIVO)
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePersona;
    }

    public static function validaEsCuidador($runCiudadano)
    {
        $esCuidador = false;
        try {
            $datosCuidador = QrCuidadoras::select('ESTADO')
                ->where('RUN_CUIDADOR', $runCiudadano)
                ->where('ESTADO', Constante::ESTADO_1_ACTIVO)
                ->first();
            if (!is_null($datosCuidador)){
                $esCuidador = (isset($datosCuidador->ESTADO) & $datosCuidador->ESTADO = true )? true:false;
            }
        } catch (Exception $ex) {
            Log::error("Hubo problemas para analizar el integrante Cuidador ".$runCiudadano);
        }

        return $esCuidador;
    }

}
