<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla seccion_consulta_nna.
 *
 * @property int $id_seccion Identificador único de la sección.
 * @property string $nombre_seccion Nombre de la sección.
 * @property string $descripcion_seccion Descripción de la sección.
 * @property \Carbon\Carbon $created_at Fecha y hora de creación del registro.
 * @property \Carbon\Carbon $updated_at Fecha y hora de última actualización del registro.
 *
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder whereIdSeccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereNombreSeccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereDescripcionSeccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereUpdatedAt($value)
 */
class SeccionConsultaNna extends Model
{
    protected $table = 'seccion_consulta_nna';
    protected $primaryKey = 'id_seccion';
    public $timestamps = true;

    protected $fillable = [
        'nombre_seccion',
        'descripcion_seccion',
    ];
}
