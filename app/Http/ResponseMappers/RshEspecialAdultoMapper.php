<?php

namespace App\Http\ResponseMappers;

class RshEspecialAdultoMapper
{
    public function map($rshEspecialAdulto)
    {
        return self::mapRshEspecialAdulto($rshEspecialAdulto);
    }

    private function mapRshEspecialAdulto($rshEspecialAdulto)
    {
        return [
            'id' => $rshEspecialAdulto->MIGRAN_ID,
            'dni' => $rshEspecialAdulto->MIGRAN_NROIDENTIFICADORNAC,
            'nacionalidad' => $rshEspecialAdulto->MIGRAN_NACIONALIDAD,
            'nacionalidad_manual' => $rshEspecialAdulto->MIGRAN_NACIONALIDAD_MANUAL,
            'fecha_nacimiento' => $rshEspecialAdulto->MIGRAN_FECHANACIMIENTO,
            'nombre' => $rshEspecialAdulto->MIGRAN_NOMBRES,
            'apellido1' => $rshEspecialAdulto->MIGRAN_APELLIDOPATERNO,
            'apellido2' => $rshEspecialAdulto->MIGRAN_APELLIDOMATERNO,
            'parentesco' => $rshEspecialAdulto->MIGRAN_PARENTESCO,
            'telefono_movil' => $rshEspecialAdulto->MIGRAN_CELULAR,
            'telefono_fijo' => $rshEspecialAdulto->MIGRAN_TELEFONO,
            'email' => $rshEspecialAdulto->MIGRAN_EMAIL,
            'documento' => $rshEspecialAdulto->MIGRAN_ARCHIVO_SCAN,
            'tipo_pasaporte' => $rshEspecialAdulto->MIGRAN_TIPO_DOCUMENTO,
            'msg_pasaporte' => $rshEspecialAdulto->MIGRAN_MSGTIPO_DOCUMENTO,
            'sexo' => $rshEspecialAdulto->MIGRAN_SEXO,
            'msg_sexo' => $rshEspecialAdulto->MIGRAN_MSGSEXO,
        ];
    }
}

