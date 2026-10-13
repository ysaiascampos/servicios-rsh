<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;

/**
 * App\Models\TipoSolicitud
 *
 * @property int $ID_TIPO_SOLICITUD
 * @property string|null $TIPO_SOLICITUD
 * @property string|null $MODULO_FORMULARIO
 * @property int|null $ID_PERFIL_RESUELVE
 * @property int|null $SLA
 * @property int|null $ID_PRIORIDAD
 * @property string|null $URL_FORMULARIO
 * @property int|null $ADJUNTOS
 * @property int|null $VIGENTE
 * @property string|null $PDF
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereADJUNTOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereIDPERFILRESUELVE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereIDPRIORIDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereIDTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereMODULOFORMULARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud wherePDF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereSLA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereURLFORMULARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSolicitud whereVIGENTE($value)
 * @mixin Eloquent
 */
class TipoSolicitud extends Eloquent
{
    protected $table = 'ab_tipo_solicitud';
    protected $primaryKey = 'ID_TIPO_SOLICITUD';
    public $timestamps = false;

    public static function solicitudesVigentes()
    {
        $datosTipoDeSolicitud = null;
        try {
            $datosTipoDeSolicitud = self::getModel()
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->orderBy('id_tipo_solicitud', 'asc')
                ->pluck('TIPO_SOLICITUD', 'ID_TIPO_SOLICITUD')
                ->toArray();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosTipoDeSolicitud;
    }
}
