<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;

/**
 * App\Models\Educacion
 *
 * @property int $EDUCACION_RBD_ID
 * @property int|null $EDUCACION_RBD_AGNO
 * @property int|null $EDUCACION_RBD_COD
 * @property string|null $EDUCACION_RBD_NOM
 * @property int|null $EDUCACION_RBD_COD_REG
 * @property int|null $EDUCACION_RBD_COD_PROV
 * @property int|null $EDUCACION_RBD_COD_COM
 * @property string|null $EDUCACION_RBD_NOM_COM
 * @property int|null $EDUCACION_RBD_VIGENTE
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDAGNO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDCOD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDCODCOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDCODPROV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDCODREG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDNOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDNOMCOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educacion whereEDUCACIONRBDVIGENTE($value)
 * @mixin Eloquent
 */
class Educacion extends Eloquent
{
    protected $table = 'tbl_educacion_rbd';
    protected $primaryKey = 'EDUCACION_RBD_ID';
    public $timestamps = false;

    public function getAllColegios()
    {
        $institucionEducacionColegio = null;
        try {
            $institucionEducacionColegio = self::select('EDUCACION_RBD_COD as RBD', 'EDUCACION_RBD_NOM as NOMBRE', 'EDUCACION_RBD_COD_COM as COMUNA-INE')
                ->where('educacion_rbd_vigente', Constante::VIGENTE_1_VIGENTE)
                ->orderBy('EDUCACION_RBD_COD', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $institucionEducacionColegio;
    }
}
