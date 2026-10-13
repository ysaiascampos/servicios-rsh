<?php

namespace App\Models;

use Eloquent;
use Exception;

/**
 * App\Models\MotivoEducacionSuperior
 *
 * @property int $ID
 * @property string $MOTIVO
 * @property string|null $DETALLE
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior whereDETALLE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoEducacionSuperior whereMOTIVO($value)
 * @mixin Eloquent
 */
class MotivoEducacionSuperior extends Eloquent
{
    protected $table = 'ab_motivo_educacion_superior';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    public function getAllSuperiorMotivo()
    {
        $motivoEducacionSuperior = null;
        try {
            $motivoEducacionSuperior = self::select('ID as KEY', 'MOTIVO as VALUE')
                ->orderBy('KEY', 'ASC')
                ->pluck('VALUE', 'KEY');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $motivoEducacionSuperior;
    }
}
