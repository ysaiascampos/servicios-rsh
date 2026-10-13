<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SupervisionCategoria
 *
 * @property int $ID_SUPERVISION
 * @property int|null $LOCALIZACION_TERRITORIAL
 * @property int|null $COMPOSICION_HOGAR
 * @property int|null $EDUCACION
 * @property int|null $SALUD
 * @property int|null $OCUPACION
 * @property int|null $INGRESOS
 * @property int|null $OTRO
 * @property string|null $DETALLE_OTRO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereCOMPOSICIONHOGAR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereDETALLEOTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereEDUCACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereINGRESOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereLOCALIZACIONTERRITORIAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereOCUPACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereOTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionCategoria whereSALUD($value)
 * @mixin Eloquent
 */
class SupervisionCategoria extends Eloquent
{
    protected $table = 'ab_supervision_categorias';
    protected $primaryKey = 'ID_SUPERVISION';
    public $timestamps = false;

    /*public function Supervision() {
        return $this->belongsTo('Supervision', 'local_key', 'parent_key');
    }*/

}
