<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EncuestaCartola
 *
 * @property int|null $ENC_RUN
 * @property int|null $ENC_EDAD
 * @property int|null $ENC_FONO
 * @property string|null $ENC_MAIL
 * @property string|null $ENC_SEXO
 * @property int|null $ENC_COMUNA_ID
 * @property string|null $ENC_COMUNA_DESC
 * @property int|null $ENC_TRAMO
 * @property int|null $ENC_FRECUENCIA_CIUD
 * @property int|null $ENC_FRECUENCIA_MUN
 * @property string|null $ENC_TIEMPO_INICIO
 * @property string|null $ENC_TIEMPO_FIN
 * @property int|null $ENC_TIEMPO_DEMORA
 * @property int|null $ENC_CANAL
 * @property string|null $ENC_TIPO_CARTOLA
 * @property string|null $ENC_FECHA_CONSENT
 * @property int|null $ENC_ACEPTA_CONVENIO
 * @property int|null $ENC_ENVIADA
 * @property string|null $ENC_P1
 * @property string|null $ENC_P2
 * @property string|null $ENC_P3
 * @property string|null $ENC_P4
 * @property string|null $ENC_P5
 * @property int|null $ENC_P6
 * @property string|null $ENC_P7
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola query()
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCACEPTACONVENIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCCANAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCCOMUNADESC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCCOMUNAID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCEDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCENVIADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCFECHACONSENT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCFONOCIONO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCP7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCTRAMO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCTIEMPODEMORA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCTIEMPOFIN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCTIEMPOINICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EncuestaCartola whereENCTIPOCARTOLA($value)
 * @mixin \Eloquent
 */
class EncuestaCartola extends Eloquent
{
    protected $table = 'ENCUESTA_CARTOLA';
    protected $primaryKey = 'ENC_RUN';
    public $timestamps = false;

    protected $fillable = [
        'ENC_RUN',
        'ENC_EDAD',
        'ENC_FONO',
        'ENC_MAIL',
        'ENC_SEXO',
        'ENC_COMUNA_ID',
        'ENC_COMUNA_DESC',
        'ENC_TRAMO',
        'ENC_FRECUENCIA_CIUD',
        'ENC_FRECUENCIA_MUN',
        'ENC_TIEMPO_INICIO',
        'ENC_TIEMPO_FIN',
        'ENC_TIEMPO_DEMORA',
        'ENC_CANAL',
        'ENC_TIPO_CARTOLA',
        'ENC_FECHA_CONSENT',
        'ENC_ACEPTA_CONVENIO',
        'ENC_ENVIADA',
        'ENC_P1',
        'ENC_P2',
        'ENC_P3',
        'ENC_P4',
        'ENC_P5',
        'ENC_P6',
        'ENC_P7',
    ];
}
