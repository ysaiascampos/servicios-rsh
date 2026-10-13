<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SupervisionAdjuntos
 *
 * @property int $DOC_ID
 * @property string|null $DOC_ARCHIVO
 * @property int|null $DOC_IDSUPERVISION
 * @property string|null $DOC_NOMBREORIGINAL
 * @property string|null $DOC_FECHACARGA
 * @property int|null $DOC_IDUSUARIOCARGA
 * @property int|null $DOC_TIPO_DOCUMENTO
 * @property bool|null $DOC_ELIMINADO 1=ELIMINADO; 0=VIGENTE
 * @property string|null $SUBTIPO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCARCHIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCELIMINADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCIDUSUARIOCARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCNOMBREORIGINAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereDOCTIPODOCUMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionAdjuntos whereSUBTIPO($value)
 * @mixin Eloquent
 */
class SupervisionAdjuntos extends Eloquent
{
    protected $table = 'ab_supervision_adjuntos';
    protected $primaryKey = 'DOC_ID';
    public $timestamps = false;
}
