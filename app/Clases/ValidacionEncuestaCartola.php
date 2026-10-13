<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Models\EncuestaCartola;
use App\Models\Personas;
use App\Models\SupervisionRSHExport;
use http\Exception;
use App\Http\Inputs\EncuestaCartolaInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\Log;
use Mockery\Container;
use Throwable;

class ValidacionEncuestaCartola
{

    public static function guardarEncuesta(array $datos)
    {
        // Iniciar una transacción de la base de datos
        DB::beginTransaction();

        try {
            $encuestaExistente = EncuestaCartola::where('ENC_RUN', $datos['ENC_RUN'])->first();

            if ($encuestaExistente) {
                throw new ExcepcionValidacion('Ya existe una encuesta con el mismo rut.', 400);
            }

            $encuesta = new EncuestaCartola();

            // Asignar valores al modelo EncuestaCartola
            $encuesta->ENC_RUN = $datos['ENC_RUN'];
            $encuesta->ENC_EDAD = $datos['ENC_EDAD'];
            $encuesta->ENC_FONO = $datos['ENC_FONO'];
            $encuesta->ENC_MAIL = $datos['ENC_MAIL'];
            $encuesta->ENC_SEXO = $datos['ENC_SEXO'];
            $encuesta->ENC_COMUNA_ID = $datos['ENC_COMUNA_ID'];
            $encuesta->ENC_COMUNA_DESC = $datos['ENC_COMUNA_DESC'];
            $encuesta->ENC_TRAMO = $datos['ENC_TRAMO'];
            $encuesta->ENC_FRECUENCIA_CIUD = $datos['ENC_FRECUENCIA_CIUD'];
            $encuesta->ENC_FRECUENCIA_MUN = $datos['ENC_FRECUENCIA_MUN'];
            $encuesta->ENC_TIEMPO_INICIO = $datos['ENC_TIEMPO_INICIO'];
            $encuesta->ENC_TIEMPO_FIN = $datos['ENC_TIEMPO_FIN'];
            $encuesta->ENC_TIEMPO_DEMORA = $datos['ENC_TIEMPO_DEMORA'];
            $encuesta->ENC_CANAL = $datos['ENC_CANAL'];
            $encuesta->ENC_TIPO_CARTOLA = $datos['ENC_TIPO_CARTOLA'];
            $encuesta->ENC_FECHA_CONSENT = $datos['ENC_FECHA_CONSENT'];
            $encuesta->ENC_ACEPTA_CONVENIO = $datos['ENC_ACEPTA_CONVENIO'];
            $encuesta->ENC_ENVIADA = $datos['ENC_ENVIADA'];
            $encuesta->ENC_P1 = $datos['ENC_P1'];
            $encuesta->ENC_P2 = $datos['ENC_P2'];
            $encuesta->ENC_P3 = $datos['ENC_P3'];
            $encuesta->ENC_P4 = $datos['ENC_P4'];
            $encuesta->ENC_P5 = $datos['ENC_P5'];
            $encuesta->ENC_P6 = $datos['ENC_P6'];
            $encuesta->ENC_P7 = $datos['ENC_P7'];

            $encuesta->save(); // Guardar el registro de la encuesta

            if($datos['ENC_ACEPTA_CONVENIO'] == 0 || $datos['ENC_ENVIADA'] == 1) {
                SupervisionRSHExport::where('sv_run', $datos['ENC_RUN'])
                    ->where('sv_tipo_hallazgo_id', 900)
                    ->update(array('sv_vigente' => 0));
            }

            // Confirmar la transacción
            DB::commit();
            return $encuesta;
        } catch (\Exception $ex) {
            // Si ocurre un error, hacer rollback de la transacción
            DB::rollback();
            throw $ex;
        }
    }

    public static function obtenerEncuestaPorRun($runCiudadano)
    {
        try {
            $encuesta = EncuestaCartola::where('ENC_RUN', $runCiudadano)->first();
            return $encuesta;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public static function actualizarEncuesta($run, array $datos)
    {
        // Iniciar una transacción de la base de datos
        DB::beginTransaction();

        try {
            // Buscar la encuesta por el RUN proporcionado
            $encuesta = EncuestaCartola::where('ENC_RUN', $run)->first();

            if (!$encuesta) {
                throw new ExcepcionValidacion('No se encontró ninguna encuesta con el RUN proporcionado.', 404);
            }

            // Actualizar los campos de la encuesta con los datos proporcionados
            $encuesta->ENC_RUN = $datos['ENC_RUN'];
            $encuesta->ENC_EDAD = $datos['ENC_EDAD'];
            $encuesta->ENC_FONO = $datos['ENC_FONO'];
            $encuesta->ENC_MAIL = $datos['ENC_MAIL'];
            $encuesta->ENC_SEXO = $datos['ENC_SEXO'];
            $encuesta->ENC_COMUNA_ID = $datos['ENC_COMUNA_ID'];
            $encuesta->ENC_COMUNA_DESC = $datos['ENC_COMUNA_DESC'];
            $encuesta->ENC_TRAMO = $datos['ENC_TRAMO'];
            $encuesta->ENC_FRECUENCIA_CIUD = $datos['ENC_FRECUENCIA_CIUD'];
            $encuesta->ENC_FRECUENCIA_MUN = $datos['ENC_FRECUENCIA_MUN'];
            $encuesta->ENC_TIEMPO_INICIO = $datos['ENC_TIEMPO_INICIO'];
            $encuesta->ENC_TIEMPO_FIN = $datos['ENC_TIEMPO_FIN'];
            $encuesta->ENC_TIEMPO_DEMORA = $datos['ENC_TIEMPO_DEMORA'];
            $encuesta->ENC_CANAL = $datos['ENC_CANAL'];
            $encuesta->ENC_TIPO_CARTOLA = $datos['ENC_TIPO_CARTOLA'];
            $encuesta->ENC_FECHA_CONSENT = $datos['ENC_FECHA_CONSENT'];
            $encuesta->ENC_ACEPTA_CONVENIO = $datos['ENC_ACEPTA_CONVENIO'];
            $encuesta->ENC_ENVIADA = $datos['ENC_ENVIADA'];
            $encuesta->ENC_P1 = $datos['ENC_P1'];
            $encuesta->ENC_P2 = $datos['ENC_P2'];
            $encuesta->ENC_P3 = $datos['ENC_P3'];
            $encuesta->ENC_P4 = $datos['ENC_P4'];
            $encuesta->ENC_P5 = $datos['ENC_P5'];
            $encuesta->ENC_P6 = $datos['ENC_P6'];
            $encuesta->ENC_P7 = $datos['ENC_P7'];

            $encuesta->save();

            if($datos['ENC_ACEPTA_CONVENIO'] == 0 || $datos['ENC_ENVIADA'] == 1) {
                SupervisionRSHExport::where('sv_run', $datos['ENC_RUN'])
                    ->where('sv_tipo_hallazgo_id', 900)
                    ->update(array('sv_vigente' => 0));
            }

            DB::commit();

            return $encuesta;
        } catch (\Exception $ex) {
            // Si ocurre un error, hacer rollback de la transacción
            DB::rollback();
            throw $ex;
        }
    }



}
