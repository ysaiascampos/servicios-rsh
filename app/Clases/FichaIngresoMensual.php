<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Http\Inputs\IntegranteGrupoFamiliarInput;
use App\Models\FichaMontoMensual;
use App\Sequence;
use Illuminate\Support\Facades\Validator;

/**
 * Borrar esta clase y sus llamadas luego del COVID19
 */
class FichaIngresoMensual
{
    public static function mappingInDatosFichaIngresoMensual(IntegranteGrupoFamiliarInput $input): array
    {
        $perId = $input->get('id');

        $data = [];
        $data['mensual_id_persona'] = $perId;
        $data['mensual_vigente'] = Constante::VIGENTE_2_PENDIENTE;

        $data['mensual_y1_m1'] = $input->get('y1-m1');
        $data['mensual_y1_m2'] = $input->get('y1-m2');
        $data['mensual_y1_m3'] = $input->get('y1-m3');
        $data['mensual_y1_m4'] = $input->get('y1-m4');
        $data['mensual_y1_m5'] = $input->get('y1-m5');
        $data['mensual_y1_m6'] = $input->get('y1-m6');
        $data['mensual_y1_m7'] = $input->get('y1-m7');
        $data['mensual_y1_m8'] = $input->get('y1-m8');
        $data['mensual_y1_m9'] = $input->get('y1-m9');
        $data['mensual_y1_m10'] = $input->get('y1-m10');
        $data['mensual_y1_m11'] = $input->get('y1-m11');
        $data['mensual_y1_m12'] = $input->get('y1-m12');

        $data['mensual_y2_m1'] = $input->get('y2-m1');
        $data['mensual_y2_m2'] = $input->get('y2-m2');
        $data['mensual_y2_m3'] = $input->get('y2-m3');
        $data['mensual_y2_m4'] = $input->get('y2-m4');
        $data['mensual_y2_m5'] = $input->get('y2-m5');
        $data['mensual_y2_m6'] = $input->get('y2-m6');
        $data['mensual_y2_m7'] = $input->get('y2-m7');
        $data['mensual_y2_m8'] = $input->get('y2-m8');
        $data['mensual_y2_m9'] = $input->get('y2-m9');
        $data['mensual_y2_m10'] = $input->get('y2-m10');
        $data['mensual_y2_m11'] = $input->get('y2-m11');
        $data['mensual_y2_m12'] = $input->get('y2-m12');

        return $data;
    }

    public static function guardarFichaMontoMensual($integrante, $fichaId, $idSolicitud, $personaId = null)
    {
        if (self::check(date('Ymd'))) {
            $fichaMontoMensual = new FichaMontoMensual();

            $fichaMontoId = Sequence::nextValue('SEQ_FICHA_MENSUALPK');
            $fichaMontoMensual->MENSUAL_ID = $fichaMontoId;
            $fichaMontoMensual->MENSUAL_ID_FICHA = $fichaId;
            $fichaMontoMensual->MENSUAL_IDSOLICITUD = $idSolicitud;
            $fichaMontoMensual->MENSUAL_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $fichaMontoMensual->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $fichaMontoMensual->MENSUAL_FECHA_INICIO = date('Ymd');

            $fichaMontoMensual->fillable([
                'mensual_id_persona',
                'mensual_y1_m1',
                'mensual_y1_m2',
                'mensual_y1_m3',
                'mensual_y1_m4',
                'mensual_y1_m5',
                'mensual_y1_m6',
                'mensual_y1_m7',
                'mensual_y1_m8',
                'mensual_y1_m9',
                'mensual_y1_m10',
                'mensual_y1_m11',
                'mensual_y1_m12',
                'mensual_y2_m1',
                'mensual_y2_m2',
                'mensual_y2_m3',
                'mensual_y2_m4',
                'mensual_y2_m5',
                'mensual_y2_m6',
                'mensual_y2_m7',
                'mensual_y2_m8',
                'mensual_y2_m9',
                'mensual_y2_m10',
                'mensual_y2_m11',
                'mensual_y2_m12'
            ]);
            array_key_exists('mensual_id_persona', $integrante) ? : $integrante['mensual_id_persona'] = $personaId;
            $integrante = self::formatearMontosMensuales($integrante);
            $fichaMontoMensual->fill($integrante); // llena con los campos especificados anteriormente, tomados del array $integrantes
            $fichaMontoMensual->save();
        }
    }

    public static function actualizarFichaMontoMensual(
        $integrante,
        $fichaId,
        $idSolicitud,
        $fichaVigente,
        $fechaCreacion,
        $fechaCreacionCiudadano = null,
        $personaId = null
    ) {
        if (self::check($fechaCreacion, $fechaCreacionCiudadano)) {
            array_key_exists('mensual_id_persona', $integrante) ? : $integrante['mensual_id_persona'] = $personaId;
            $fichaMontoMensual = self::getFichaMontoMensualFromModel(
                $integrante['mensual_id_persona'],
                $idSolicitud,
                $fichaId,
                date("Ymd", strtotime($fechaCreacion))
            );
            $filledFicha = [
                'mensual_id_ficha',
                'mensual_id_persona',
                'mensual_y1_m1',
                'mensual_y1_m2',
                'mensual_y1_m3',
                'mensual_y1_m4',
                'mensual_y1_m5',
                'mensual_y1_m6',
                'mensual_y1_m7',
                'mensual_y1_m8',
                'mensual_y1_m9',
                'mensual_y1_m10',
                'mensual_y1_m11',
                'mensual_y1_m12',
                'mensual_y2_m1',
                'mensual_y2_m2',
                'mensual_y2_m3',
                'mensual_y2_m4',
                'mensual_y2_m5',
                'mensual_y2_m6',
                'mensual_y2_m7',
                'mensual_y2_m8',
                'mensual_y2_m9',
                'mensual_y2_m10',
                'mensual_y2_m11',
                'mensual_y2_m12'
            ];

            $integrante = self::formatearMontosMensuales($integrante);
            $fichaMontoMensual->mensual_vigente = $fichaVigente;
            $fichaMontoMensual->fillable($filledFicha); //especifica cuales campos se van a llenar
            $fichaMontoMensual->fill($integrante); //llena con los campos especificados anteriormente, tomados del array $integrantes
            $fichaMontoMensual->save();
        }
    }

    public static function getFichaMontoMensualFromModel(
        $perIdBeneficiario,
        $idSolicictud,
        $secuenciaFicha,
        $fechaCracion
    ) {
        $fichaMontoMensual = FichaMontoMensual::where('mensual_id_persona', $perIdBeneficiario)
            ->where('mensual_idsolicitud', $idSolicictud)
            ->first();
        if (is_null($fichaMontoMensual)) {
            $fichaMontoMensual = new FichaMontoMensual();
            $fichaMontoMensual->MENSUAL_ID = Sequence::nextValue('SEQ_FICHA_MENSUALPK');
            $fichaMontoMensual->MENSUAL_ID_FICHA = $secuenciaFicha;
            $fichaMontoMensual->MENSUAL_ID_PERSONA = $perIdBeneficiario;
            $fichaMontoMensual->MENSUAL_IDSOLICITUD = $idSolicictud;
            $fichaMontoMensual->MENSUAL_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $fichaMontoMensual->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $fichaMontoMensual->MENSUAL_FECHA_INICIO = $fechaCracion;
        }
        return $fichaMontoMensual;
    }

    public static function validadorFichaMontoMensual(IntegranteGrupoFamiliarInput $input, $requerido = false)
    {
        $required = 'required|';
        if ($requerido === false) {
            $required = '';
        }
        $campos = $input->all();
        $reglas = [
            'y1-m1' => $required . 'integer|min:0',
            'y1-m2' => $required . 'integer|min:0',
            'y1-m3' => $required . 'integer|min:0',
            'y1-m4' => $required . 'integer|min:0',
            'y1-m5' => $required . 'integer|min:0',
            'y1-m6' => $required . 'integer|min:0',
            'y1-m7' => $required . 'integer|min:0',
            'y1-m8' => $required . 'integer|min:0',
            'y1-m9' => $required . 'integer|min:0',
            'y1-m10' => $required . 'integer|min:0',
            'y1-m11' => $required . 'integer|min:0',
            'y1-m12' => $required . 'integer|min:0',
            'y2-m1' => $required . 'integer|min:0',
            'y2-m2' => $required . 'integer|min:0',
            'y2-m3' => $required . 'integer|min:0',
            'y2-m4' => $required . 'integer|min:0',
            'y2-m5' => $required . 'integer|min:0',
            'y2-m6' => $required . 'integer|min:0',
            'y2-m7' => $required . 'integer|min:0',
            'y2-m8' => $required . 'integer|min:0',
            'y2-m9' => $required . 'integer|min:0',
            'y2-m10' => $required . 'integer|min:0',
            'y2-m11' => $required . 'integer|min:0',
            'y2-m12' => $required . 'integer|min:0'
        ];
        return Validator::make($campos, $reglas);
    }

    public static function formatearMontosMensuales($data)
    {
        $y1keyString = array_key_exists('mensual_y1_m1', $data) ? 'mensual_y1_m' : 'y1-m';
        $y2keyString = array_key_exists('mensual_y2_m1', $data) ? 'mensual_y2_m' : 'y2-m';
        for ($mes = 1; $mes <= 12; $mes++) {
            $data['mensual_y1_m' . $mes] = str_replace('.', '', $data[$y1keyString . $mes]);
            $data['mensual_y2_m' . $mes] = str_replace('.', '', $data[$y2keyString . $mes]);
        }
        return $data;
    }

    public static function check($fecha = 'now', $fechaCreacionCiudadano = null): bool
    {
        $salida = false;
        if (env('FECHA_APLICACION_MONTO_MENSUAL')) {
            $salida = strtotime($fecha) >= strtotime(env('FECHA_APLICACION_MONTO_MENSUAL'));
        }
        if ($salida && isset($fechaCreacionCiudadano)) {
            $salida = strtotime($fechaCreacionCiudadano) >= strtotime(env('FECHA_APLICACION_MONTO_MENSUAL'));
        }
        return ($salida);
    }
}
