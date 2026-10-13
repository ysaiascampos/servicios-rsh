<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\NNARepresentanteMigrante
 *
 * @property int $MIGRAN_ID
 * @property int $MIGRAN_GRUPOFAMILIAR
 * @property int $MIGRAN_NUEVOFOLIO
 * @property int $MIGRAN_PARENTESCO
 * @property string $MIGRAN_NOMBRES
 * @property string $MIGRAN_APELLIDOPATERNO
 * @property string $MIGRAN_APELLIDOMATERNO
 * @property int $MIGRAN_FECHANACIMIENTO
 * @property int $MIGRAN_EDAD
 * @property int $MIGRAN_SEXO
 * @property int $MIGRAN_NACIONALIDAD
 * @property string $MIGRAN_NACIONALIDAD_MANUAL
 * @property string $MIGRAN_DIRECCION
 * @property string $MIGRAN_EMAIL
 * @property string $MIGRAN_TELEFONO
 * @property string $MIGRAN_CELULAR
 * @property string $MIGRAN_NROIDENTIFICADORNAC
 * @property string $MIGRAN_NROIDENTIFICADORNAC_CONFIRM
 * @property string $MIGRAN_ARCHIVO_SCAN
 * @property int $MIGRAN_RUN
 * @property int $MIGRAN_RUN_FONASA
 * @property int $MIGRAN_VIGENTE
 * @property int $MIGRAN_IDSOLICITUD
 * @property int $MIGRAN_CALCULOACTIVO
 * @property int $MIGRAN_FUENTE
 * @property string $MIGRAN_FECHA_INGRESO
 * @property string $MIGRAN_FECHA_MODIFICACION
 * @property int $MIGRAN_FCORREC_MAN
 * @property string $MIGRAN_DESC_CORREC
 * @property int $MIGRAN_TIPO_DOCUMENTO
 * @property string $MIGRAN_MSGSEXO
 * @property string $MIGRAN_MSGTIPO_DOCUMENTO
 *
 * @method static Builder|NNARepresentanteMigrante newModelQuery()
 * @method static Builder|NNARepresentanteMigrante newQuery()
 * @method static Builder|NNARepresentanteMigrante query()
 *
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_ID($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_GRUPOFAMILIAR($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_NUEVOFOLIO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_PARENTESCO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_NOMBRES($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_APELLIDOPATERNO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_APELLIDOMATERNO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_FECHANACIMIENTO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_EDAD($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_SEXO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_NACIONALIDAD($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_DIRECCION($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_EMAIL($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_TELEFONO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_CELULAR($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_NROIDENTIFICADORNAC($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_ARCHIVO_SCAN($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_RUN($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_RUN_FONASA($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_VIGENTE($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_IDSOLICITUD($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_CALCULOACTIVO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_FUENTE($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_FECHA_INGRESO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_FECHA_MODIFICACION($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_FCORREC_MAN($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_DESC_CORREC($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_NACIONALIDAD_MANUAL($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_TIPO_DOCUMENTO($value)
 * @method static Builder|NNARepresentanteMigrante whereMIGRAN_MSGTIPO_DOCUMENTO($value)
 *
 * @mixin Eloquent
 */
class NNARepresentanteMigrante extends Eloquent
{
    protected $table = 'MIGRANTES';
    protected $primaryKey = 'MIGRAN_ID';
    public $timestamps = false;

    protected $fillable = [
        'MIGRAN_TIPO_DOCUMENTO',
        'MIGRAN_MSGSEXO',
        'MIGRAN_MSGTIPO_DOCUMENTO',
        'MIGRAN_ID',
        'MIGRAN_GRUPOFAMILIAR',
        'MIGRAN_NUEVOFOLIO',
        'MIGRAN_PARENTESCO',
        'MIGRAN_NOMBRES',
        'MIGRAN_APELLIDOPATERNO',
        'MIGRAN_APELLIDOMATERNO',
        'MIGRAN_FECHANACIMIENTO',
        'MIGRAN_EDAD',
        'MIGRAN_SEXO',
        'MIGRAN_NACIONALIDAD',
        'MIGRAN_DIRECCION',
        'MIGRAN_EMAIL',
        'MIGRAN_TELEFONO',
        'MIGRAN_CELULAR',
        'MIGRAN_NROIDENTIFICADORNAC',
        'MIGRAN_ARCHIVO_SCAN',
        'MIGRAN_RUN',
        'MIGRAN_RUN_FONASA',
        'MIGRAN_VIGENTE',
        'MIGRAN_IDSOLICITUD',
        'MIGRAN_CALCULOACTIVO',
        'MIGRAN_FUENTE',
        'MIGRAN_FECHA_INGRESO',
        'MIGRAN_FECHA_MODIFICACION',
        'MIGRAN_FCORREC_MAN',
        'MIGRAN_DESC_CORREC',
        'MIGRAN_NACIONALIDAD_MANUAL'
    ];
}


