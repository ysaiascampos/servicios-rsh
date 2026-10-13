<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;

/**
 * App\Models\InstitucionEducacionSuperior
 *
 * @property int $ED_SUP_ID
 * @property int $CODIGO_DE_INSTITUCION
 * @property string $TIPO_DE_INSTITUCION
 * @property string $NOMBRE_INSTITUCION
 * @property string|null $PROCESO
 * @property string $ACREDITACION
 * @property string|null $VIGENCIA_DE_LA_ACREDITACION
 * @property int|null $ANNIOS_DE_ACREDITACION
 * @property string|null $ESTADO
 * @property string|null $DIRECCION
 * @property string|null $PAGINA_WEB
 * @property int|null $PERIODO
 * @property int|null $VIGENTE
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereACREDITACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereANNIOSDEACREDITACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereCODIGODEINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereDIRECCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereEDSUPID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereNOMBREINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior wherePAGINAWEB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior wherePERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior wherePROCESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereTIPODEINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereVIGENCIADELAACREDITACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitucionEducacionSuperior whereVIGENTE($value)
 * @mixin Eloquent
 */
class InstitucionEducacionSuperior extends Eloquent
{
    protected $table = 'instituciones_ed_sup';
    protected $primaryKey = 'ED_SUP_ID';
    public $timestamps = false;

    public function getAllSuperiorTipoInstitucion()
    {
        $tipoInstitucionEducacionSuperior = null;
        try {
            $tipoInstitucionEducacionSuperior = self::select('TIPO_DE_INSTITUCION as KEY', 'TIPO_DE_INSTITUCION as VALUE')
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->distinct()
                ->orderBy('KEY', 'ASC')
                ->pluck('VALUE', 'KEY');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $tipoInstitucionEducacionSuperior;
    }

    public function getSuperiorInstitucion($tipoInstitucion)
    {
        $institucionEducacionSuperior = null;
        try {
            $institucionEducacionSuperior = self::select('CODIGO_DE_INSTITUCION as KEY', 'NOMBRE_INSTITUCION as VALUE')
                ->where('tipo_de_institucion', $tipoInstitucion)
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->orderBy('KEY', 'ASC')
                ->pluck('VALUE', 'KEY');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $institucionEducacionSuperior;
    }

    public function findAllInstitucion()
    {
        $institucionEducacionSuperior = null;
        try {
            $institucionEducacionSuperior = self::select('CODIGO_DE_INSTITUCION as ID', 'NOMBRE_INSTITUCION as NOMBRE', 'TIPO_DE_INSTITUCION as TIPO')
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->orderBy('ID', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $institucionEducacionSuperior;
    }

    public function getAllSuperiorInstitucion()
    {
        $institucionEducacionSuperiores = self::findAllInstitucion();
        $salida = [];
        foreach ($institucionEducacionSuperiores as $institucionEducacionSuperior) {
            $salida[$institucionEducacionSuperior['TIPO']][$institucionEducacionSuperior['ID']] = [
                'ID' => $institucionEducacionSuperior['ID'],
                'NOMBRE' => $institucionEducacionSuperior['NOMBRE']
            ];
        }
        return $salida;
    }
}
