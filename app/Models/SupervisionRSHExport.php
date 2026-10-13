<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Http\ResponseMappers\GrupoFamiliarAsignacion;
use App\Utiles;
use DateTime;
use DateTimeZone;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;



/**
 * App\Models\SupervisionRSHExport
 *
 * @property int $SV_PERIODO_RSH
 * @property int $SV_RUN
 * @property int $SV_FOLIO
 * @property int $SV_TIPO_HALLAZGO_ID
 * @property int $SV_DESTINATARIO_MENS
 * @property int $SV_PLATAFORMA_MENS
 * @property string|null $SV_OFICIO_INFORME
 * @property int $SV_FLAG_NOTIFICACION
 * @property int $SV_FECHA_CORREC
 * @property int $SV_IDSOLICITUD_CORREC
 * @property int $SV_VIGENTE
 * @property int $SV_FECHA_CARGA
 * @property int $SV_PLAZO_DIAS
 * @property int $SV_FEC_ULT_DIA_MENS
 * @property int $SV_FEC_CAMBIO_MENS
 * @property int $SV_TIPO_SOL_RELEVANTE_1
 * @property int $SV_TIPO_SOL_RELEVANTE_2
 * @property int $SV_TIPO_SOL_RELEVANTE_3
 * @property int $SV_ID_MENSAJE
 * @property int $SV_INVALIDA_BENEF_VIG
 * @property int $SV_FEC_INVALIDA_BEN
 * @property int $SV_FEC_NO_INVALIDA
 * @property int $SV_FCORREC_MAN
 * @property string|null $SV_DESC_CORREC
 * @property int $SV_GRAVEDAD
 * @property int $SV_MENOR
 * @property int $SV_RUN_MENOR
 * @property int $SV_ID
 * @property int $SV_FECHA_ENV_CORREO
 * @property int $SV_ESTADO_ENV_CORREO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_PERIODO_RSH
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_RUN
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FOLIO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_TIPO_HALLAZGO_ID
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_DESTINATARIO_MENS
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_PLATAFORMA_MENS
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_OFICIO_INFORME
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FLAG_NOTIFICACION
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FECHA_CORREC
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_IDSOLICITUD_CORREC
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_VIGENTE
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FECHA_CARGA
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_PLAZO_DIAS
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FEC_ULT_DIA_MENS
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FEC_CAMBIO_MENS
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_TIPO_SOL_RELEVANTE_1
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_TIPO_SOL_RELEVANTE_2
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_TIPO_SOL_RELEVANTE_3
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_ID_MENSAJE
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_INVALIDA_BENEF_VIG
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FEC_INVALIDA_BEN
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FEC_NO_INVALIDA
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FCORREC_MAN
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_DESC_CORREC
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_GRAVEDAD
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_MENOR
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_RUN_MENOR
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_ID
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_FECHA_ENV_CORREO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionRSHExport whereSV_ESTADO_ENV_CORREO
 * @mixin Eloquent
 */
class SupervisionRSHExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'supervision_rsh';
    protected $primaryKey = 'SV_ID';
    public $timestamps = false;
}
