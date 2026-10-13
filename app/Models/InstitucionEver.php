<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\InstitucionEver
 *
 * @property int $ID_INSTITUCION
 * @property int $ID_SOLICITUD
 * @property int|null $ID_GRUPOFAMILIAR
 * @property int|null $GRUPFAMI_NUEVOFOLIO
 * @property string $NOMBRE
 * @property string|null $TELEFONO
 * @property int|null $CALCULO_ACTIVO
 * @property int|null $VIGENTE
 * @property int|null $RUN_DIRECTOR_CENTRO
 * @property string|null $DV_DIRECTOR_CENTRO
 * @property string|null $NOMBRE_DIRECTOR_CENTRO
 * @property string|null $PATERNO_DIRECTOR_CENTRO
 * @property string|null $MATERNO_DIRECTOR_CENTRO
 * @property string|null $EMAIL_DIRECTOR_CENTRO
 * @property string|null $FECHA_DESDE_VIGENCIA_REPLEGAL
 * @property string|null $FECHA_HASTA_VIGENCIA_REPLEGAL
 * @property string|null $FECHA_CREACION
 * @property string|null $FECHA_RESOLUCION
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereDVDIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereEMAILDIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereFECHADESDEVIGENCIAREPLEGAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereFECHAHASTAVIGENCIAREPLEGAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereFECHARESOLUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereGRUPFAMINUEVOFOLIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereIDGRUPOFAMILIAR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereIDINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereMATERNODIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereNOMBREDIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver wherePATERNODIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereRUNDIRECTORCENTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereTELEFONO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEver whereVIGENTE($value)
 * @mixin Eloquent
 */
class InstitucionEver extends Eloquent
{
    protected $table = 'ab_institucion_ever';
    protected $primaryKey = 'ID_INSTITUCION';
    public $timestamps = false;

    public static function getByIdSolicitud($idSolicitud)
    {
        return InstitucionEver::where('id_solicitud', $idSolicitud)
            ->first();
    }
}
