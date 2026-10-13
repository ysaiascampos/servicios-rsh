<?php

namespace App\Models;

use Eloquent;

/**
 * Modelo para la tabla funcionarios_auditoria que almacena registros de funcionarios y su actividad.
 *
 * @property int $id_funcionario Identificador único del funcionario.
 * @property string $nombre Nombre del funcionario.
 * @property string $rut RUT del funcionario.
 * @property string $perfil Perfil del funcionario.
 * @property string $comuna Comuna del funcionario.
 * @property string $ip Dirección IP del funcionario.
 * @property \Carbon\Carbon $fecha Fecha de registro del funcionario.
 *
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder whereIdFuncionario($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereRut($value)
 * @method static \Illuminate\Database\Eloquent\Builder wherePerfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereComuna($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereFecha($value)
 */
class FuncionarioAuditoria extends Eloquent
{
    protected $table = 'funcionarios_auditoria';
    protected $primaryKey = 'id_funcionario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'rut',
        'perfil',
        'comuna',
        'ip',
        'fecha',
    ];

    protected $dates = ['fecha'];

    // Establecer la relación con ConsultaNna
    public function consultas()
    {
        return $this->belongsTo(ConsultaNna::class, 'id_funcionario', 'id_funcionario');
    }
}
