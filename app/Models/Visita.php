<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Visita
 *
 * @property int $ID_VISITA
 * @property int $ID_ORDEN_TRABAJO
 * @property string $FECHA_VISITA
 * @property int $RUN_VISITADOR
 * @property string|null $OBSERVACION_VISITA
 * @property string|null $COORDENADA_X
 * @property string|null $COORDENADA_Y
 * @property int $NRO_VISITA
 * @property int $ID_PREGUNTA
 * @property int $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visita newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visita newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Visita query()
 * @mixin \Eloquent
 */
class Visita extends Eloquent
{
    protected $table = 'visita_supervisor';
    protected $primaryKey = 'ID_VISITA';
    public $timestamps = false;

    public function Documentos()
    {
        return $this->hasOne('VisitaAdjuntos', 'doc_idvisita', 'id_visita');
    }

    public function tipoNE()
    {
        return $this->hasOne('Preguntas', 'id', 'id_pregunta');
    }

    /*public function Estado()
    {
        return $this->hasOne('EstadoSolicitud', 'id_estado', 'id_estado');
    }*/

    public function OrdenTrabajo()
    {
        return $this->belongsTo('OrdenTrabajo', 'id_orden_trabajo', 'id_orden_trabajo');
    }

}
