<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Colegio
 *
 * @property int $MATRIC_ID
 * @property int $AGNO
 * @property int $RBD
 * @property int $RUN_ALU
 * @property string|null $NOMBRE_ESTA
 * @property int|null $TIPO_DEPENDENCIA
 * @property string|null $TIPO_FINANCIAMENTO
 * @property int|null $COBRO_MENSUAL_PROM_POR_ALU
 * @property int|null $VIGENTE
 * @property string|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int $FECHAFUENTE
 * @property int|null $C_TACTUALIZACION_EDUCACIONAL CAMPO PARA SUBTIPO, 1 - CAMBIO, 2 - RETIRO
 * @property int|null $FECHA_CAMBIO
 * @property int|null $PERIODO_READ
 * @property int|null $ID_REGION
 * @property int|null $ID_COMUNA
 * @property string|null $CURSO
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereAGNO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereCOBROMENSUALPROMPORALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereCTACTUALIZACIONEDUCACIONAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereCURSO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereFECHACAMBIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereIDCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereIDREGION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereMATRICID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereNOMBREESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereRBD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereRUNALU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereTIPODEPENDENCIA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereTIPOFINANCIAMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Colegio whereVIGENTE($value)
 * @mixin Eloquent
 */
class Colegio extends Eloquent
{
    protected $table = 'tbl_matricula_cobro';
    protected $primaryKey = 'MATRIC_ID';
    public $timestamps = false;

    public function comuna()
    {
        return $this->hasOne('comunas_ine', 'com_id', 'id_comuna');
    }

    public function region()
    {
        return $this->hasOne('regiones', 'reg_id', 'id_region');
    }

}
