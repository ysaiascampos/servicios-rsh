<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\MigranteNacionalidad
 *
 * @property int $NAC_ID
 * @property string $NAC_DESCRIPCION
 * @property string $PAIS
 * @property string $CODIGO_ISO
 * @property string $ALPHA_3
 * @property int $PRIORIDAD_VISUAL
 * @property int $ACTIVO
 *
 * @method static Builder|MigranteNacionalidad newModelQuery()
 * @method static Builder|MigranteNacionalidad newQuery()
 * @method static Builder|MigranteNacionalidad query()
 *
 * @method static Builder|MigranteNacionalidad whereNAC_ID($value)
 * @method static Builder|MigranteNacionalidad whereNAC_DESCRIPCION($value)
 * @method static Builder|MigranteNacionalidad wherePAIS($value)
 * @method static Builder|MigranteNacionalidad whereCODIGO_ISO($value)
 * @method static Builder|MigranteNacionalidad whereALPHA_3($value)
 * @method static Builder|MigranteNacionalidad wherePRIORIDAD_VISUAL($value)
 * @method static Builder|MigranteNacionalidad whereACTIVO($value)
 *
 * @mixin Eloquent
 */
class MigranteNacionalidad extends Eloquent
{
    protected $table = 'MIGRANTE_PAIS_NACIO';
    protected $primaryKey = 'NAC_ID';
    public $timestamps = false;

    protected $fillable = [
        'NAC_DESCRIPCION',
        'PAIS',
        'CODIGO_ISO',
        'ALPHA_3',
        'PRIORIDAD_VISUAL',
        'ACTIVO',
    ];
}

