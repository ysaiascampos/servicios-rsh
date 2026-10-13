<?php

namespace App\Models;
use Eloquent;

/**
 * App\Models\LogCartolaHistorica
 *
 * @property int $LOG_ID
 * @property string $USUARIO_RUN
 * @property string $USUARIO_NOMBRE
 * @property string $USUARIO_PERFIL
 * @property int $COMUNA_ID
 * @property string $CIUDADANO_RUN
 * @property string $MODALIDAD
 * @property int|null $ANIO_INICIO
 * @property int|null $ANIO_TERMINO
 * @property string $FECHA_EMISION
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereLOGID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereUSUARIORUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereUSUARIONOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereUSUARIOPERFIL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereCOMUNAID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereCIUDADANORUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereMODALIDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereANIOINICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereANIOTERMINO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogCartolaHistorica whereFECHAEMISION($value)
 *
 * @mixin Eloquent
 */
class LogCartolaHistorica extends Eloquent
{
    protected $table = 'LOG_CARTOLA_HISTORICA';
    protected $primaryKey = 'LOG_ID';
    public $timestamps = false;

    protected $fillable = [
        'USUARIO_RUN',
        'USUARIO_NOMBRE',
        'USUARIO_PERFIL',
        'COMUNA_ID',
        'CIUDADANO_RUN',
        'MODALIDAD',
        'ANIO_INICIO',
        'ANIO_TERMINO',
        'FECHA_EMISION'
    ];
}
