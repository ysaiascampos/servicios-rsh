<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\VisitaAdjuntos
 *
 * @property int $DOC_ID
 * @property string|null $DOC_ARCHIVO
 * @property int|null $DOC_IDVISITA
 * @property string|null $DOC_NOMBREORIGINAL
 * @property string|null $DOC_FECHACARGA
 * @property int|null $DOC_IDUSUARIOCARGA
 * @property int|null $DOC_TIPO_DOCUMENTO
 * @property bool|null $DOC_ELIMINADO
 * @property string|null $SUBTIPO
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitaAdjuntos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitaAdjuntos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitaAdjuntos query()
 * @mixin \Eloquent
 */
class VisitaAdjuntos extends Eloquent
{
    protected $table = 'visita_supervision_adjuntos';
    protected $primaryKey = 'doc_id';
    public $timestamps = false;
}
