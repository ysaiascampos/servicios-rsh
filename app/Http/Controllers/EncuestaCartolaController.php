<?php

namespace App\Http\Controllers;

use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\JsonMapper;
use App\Models\EncuestaCartola;
use App\Clases\ValidacionEncuestaCartola;
use App\Http\Inputs\EncuestaCartolaInput;

use App\Utiles;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class EncuestaCartolaController extends Controller
{

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'ENC_RUN' => 'required|numeric',
                'ENC_EDAD' => 'nullable|numeric',
                'ENC_FONO' => 'nullable|numeric',
                'ENC_MAIL' => 'nullable|email',
                'ENC_SEXO' => 'nullable|string|in:M,F',
                'ENC_COMUNA_ID' => 'nullable|numeric',
                'ENC_COMUNA_DESC' => 'nullable|string',
                'ENC_TRAMO' => 'nullable|numeric',
                'ENC_FRECUENCIA_CIUD' => 'nullable|numeric',
                'ENC_FRECUENCIA_MUN' => 'nullable|numeric',
                'ENC_TIEMPO_INICIO' => 'nullable|date',
                'ENC_TIEMPO_FIN' => 'nullable|date',
                'ENC_TIEMPO_DEMORA' => 'nullable|numeric',
                'ENC_CANAL' => 'nullable|numeric',
                'ENC_TIPO_CARTOLA' => 'nullable|string',
                'ENC_FECHA_CONSENT' => 'nullable|date',
                'ENC_ACEPTA_CONVENIO' => 'nullable|numeric',
                'ENC_ENVIADA' => 'nullable|numeric',
                'ENC_P1' => 'nullable|string|in:SI,NO',
                'ENC_P2' => 'nullable|string|in:T40,T50,T60,T70,T80,T90,T100',
                'ENC_P3' => 'nullable|string|in:p6,p12,p24,p0',
                'ENC_P4' => 'nullable|string|in:a,b,c,d',
                'ENC_P5' => 'nullable|string|in:a,b,c',
                'ENC_P6' => 'nullable|numeric|between:1,7',
                'ENC_P7' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $response = ValidacionEncuestaCartola::guardarEncuesta($input);
            return new JsonMapper($response);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function show($runSolicitante)
    {
        try {
            $response = ValidacionEncuestaCartola::obtenerEncuestaPorRun($runSolicitante);
            return new JsonMapper($response);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function update(Request $request,$run)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'ENC_RUN' => 'required|numeric',
                'ENC_EDAD' => 'nullable|numeric',
                'ENC_FONO' => 'nullable|numeric',
                'ENC_MAIL' => 'nullable|email',
                'ENC_SEXO' => 'nullable|string|in:M,F',
                'ENC_COMUNA_ID' => 'nullable|numeric',
                'ENC_COMUNA_DESC' => 'nullable|string',
                'ENC_TRAMO' => 'nullable|numeric',
                'ENC_FRECUENCIA_CIUD' => 'nullable|numeric',
                'ENC_FRECUENCIA_MUN' => 'nullable|numeric',
                'ENC_TIEMPO_INICIO' => 'nullable|date',
                'ENC_TIEMPO_FIN' => 'nullable|date',
                'ENC_TIEMPO_DEMORA' => 'nullable|numeric',
                'ENC_CANAL' => 'nullable|numeric',
                'ENC_TIPO_CARTOLA' => 'nullable|string',
                'ENC_FECHA_CONSENT' => 'nullable|date',
                'ENC_ACEPTA_CONVENIO' => 'nullable|numeric',
                'ENC_ENVIADA' => 'nullable|numeric',
                'ENC_P1' => 'nullable|string|in:SI,NO',
                'ENC_P2' => 'nullable|string|in:T40,T50,T60,T70,T80,T90,T100',
                'ENC_P3' => 'nullable|string|in:p6,p12,p24,p0',
                'ENC_P4' => 'nullable|string|in:a,b,c,d',
                'ENC_P5' => 'nullable|string|in:a,b,c',
                'ENC_P6' => 'nullable|numeric|between:1,7',
                'ENC_P7' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $response = ValidacionEncuestaCartola::actualizarEncuesta($run, $input);
            return new JsonMapper($response);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
