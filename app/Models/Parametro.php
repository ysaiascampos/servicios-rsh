<?php

namespace App\Models;

use DB;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Model;


class Parametro extends Eloquent
{
    protected $table = 'AB_PARAMETRO';
    protected $primaryKey = 'ID_PARAMETRO';
    public $timestamps = false;

    protected $fillable = [
        'ID_PARAMETRO',
        'NOMBRE_PARAMETRO',
        'DESCRIPCION_PARAMETRO',
        'VALOR'
    ];

    //Se modifica esta funcion para futuras referencias acerca de la busqueda de elementos con el modelo
    public static function findByIdParametro($idParametro)
    {
        $parametro = null;
        $resultado = null;

        try {
            $parametro = self::getModel()
                ->select("ID_PARAMETRO","NOMBRE_PARAMETRO","DESCRIPCION_PARAMETRO")
                ->where('ID_PARAMETRO', $idParametro)
                ->first();

            if (!is_null($idParametro)) {
                $resultado = [
                    'nombre_parametro'=>$parametro->NOMBRE_PARAMETRO,
                    'descripcion_parametro'=>$parametro->DESCRIPCION_PARAMETRO
                ];
            }else{
                $resultado = false;
            }

        } catch (Exception $ex) {
            throw $ex;
        }

        return $resultado;
    }

}



