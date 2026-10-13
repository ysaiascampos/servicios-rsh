<?php

namespace App\Models;

use Eloquent;

/**
 * Modelo para la tabla consultas_nna que almacena registros de consultas de NNA.
 *
 * @property int $id Identificador único de la consulta.
 * @property string $run_nna RUN del NNA consultado.
 * @property string $nombre_nna Nombre del NNA consultado.
 * @property string $apellido_nna Apellido del NNA consultado.
 * @property string $fecha_nacimiento_nna Fecha de nacimiento del NNA consultado.
 * @property string $documento_adjunto Nombre del archivo adjunto.
 * @property string $tipo_documento_adulto Tipo de documento del adulto consultante (RUN o DNI/Pasaporte).
 * @property string $dni_pasaporte_adulto Número de DNI o pasaporte del adulto consultante.
 * @property \Carbon\Carbon $fecha_consulta Fecha y hora en que se realizó la consulta.
 * @property int $id_funcionario Identificador del funcionario que realizó la consulta.
 *
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereRunNna($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereNombreNna($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereApellidoNna($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereFechaNacimientoNna($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereDocumentoAdjunto($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereTipoDocumentoAdulto($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereDniPasaporteAdulto($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereFechaConsulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereIdFuncionario($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereIdSeccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereUpdatedAt($value)
 */
class ConsultaNna extends Eloquent
{
    protected $table = 'consultas_nna';
    protected $primaryKey = 'id_consulta';
    public $timestamps = false;

    protected $fillable = [
        'run_nna',
        'nombre_completo_nna',
        'fecha_nacimiento_nna',
        'documento_adjunto',
        'tipo_documento_adulto',
        'dni_pasaporte_adulto',
        'fecha_consulta',
        'id_funcionario', // Nueva columna para la relación con funcionarios
        'id_seccion', // Nueva columna para la relación con la sección
    ];

    protected $dates = ['fecha_nacimiento_nna', 'fecha_consulta'];

    // Definir la relación con el modelo FuncionarioAuditoria
    public function funcionario()
    {
        return $this->hasOne(FuncionarioAuditoria::class, 'id_funcionario', 'id_funcionario');
    }

    // Definir la relación con el modelo SeccionConsultaNna
    public function seccion()
    {
        return $this->belongsTo(SeccionConsultaNna::class, 'id_seccion', 'id_seccion');
    }
}
