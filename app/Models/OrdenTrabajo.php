<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\OrdenTrabajo
 *
 * @property int $ID_ORDEN_TRABAJO
 * @property int $FOLIO_GRUPO_FAMILIAR
 * @property int $RUN_SUPERVISOR_ASIGNADO
 * @property int $RUN_USUARIO_ASIGNADOR
 * @property string $FECHA_CREACION
 * @property int|null $RUN_USUARIO_MODIFICACION
 * @property string|null $FECHA_MODIFICACION
 * @property int $ESTADO
 * @property string|null $COMENTARIO
 * @property int|null $ID_SOLICITUD ID DE SOLICITUD CUANDO LA SUPERVISION ES EFECTIVA
 * @property int|null $CODIGO_RESULTADO CODIGO INDICATIVO DEL RESULTADO DE LA SUPERVISION A ESE FOLIO, SEGUN TABLA RESULTADO_ORDEN_TRABAJO
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereCODIGORESULTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereCOMENTARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereFECHAMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereFOLIOGRUPOFAMILIAR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereIDORDENTRABAJO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereRUNSUPERVISORASIGNADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereRUNUSUARIOASIGNADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrdenTrabajo whereRUNUSUARIOMODIFICACION($value)
 * @mixin Eloquent
 */
class OrdenTrabajo extends Eloquent
{
    protected $table = 'orden_trabajo_supervisor';
    protected $primaryKey = 'ID_ORDEN_TRABAJO';
    public $timestamps = false;

    public function Visitas()
    {
        return $this->hasMany('Visita', 'id_orden_trabajo', 'id_orden_trabajo');
    }

}
