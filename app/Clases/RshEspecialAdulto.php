<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\NNARepresentanteMigrante;
use Illuminate\Support\Facades\DB;
use App\Sequence;

class RshEspecialAdulto
{
    public function guardarDatos($data)
    {
        DB::beginTransaction();
        try {

            // Validar y convertir la fecha de nacimiento al formato adecuado
            if (isset($data['fecha_nacimiento'])) {
                $fechaNacimiento = date_create_from_format('d/m/Y', $data['fecha_nacimiento']);
                $data['fecha_nacimiento'] = $fechaNacimiento ? $fechaNacimiento->format('Ymd') : null;
            }


            $nnaRepresentanteMigrante = new NNARepresentanteMigrante();
            $nnaRepresentanteMigrante->MIGRAN_ID = Sequence::nextValue('SEQ_MIGRANTES');
            $nnaRepresentanteMigrante->MIGRAN_NOMBRES = $data['nombre'];
            $nnaRepresentanteMigrante->MIGRAN_APELLIDOPATERNO = $data['apellido1'];
            // Verificar si el segundo apellido está vacío y establecerlo como cadena vacía si es así
            $nnaRepresentanteMigrante->MIGRAN_APELLIDOMATERNO = isset($data['apellido2']) ? $data['apellido2'] : '';
            $nnaRepresentanteMigrante->MIGRAN_FECHANACIMIENTO = $data['fecha_nacimiento'];
            $nnaRepresentanteMigrante->MIGRAN_PARENTESCO = $data['parentesco'];
            $nnaRepresentanteMigrante->MIGRAN_CELULAR = $data['telefono_movil'];
            $nnaRepresentanteMigrante->MIGRAN_TELEFONO = $data['telefono_fijo'];
            $nnaRepresentanteMigrante->MIGRAN_EMAIL = $data['email'];
            $nnaRepresentanteMigrante->MIGRAN_NROIDENTIFICADORNAC = $data['dni'];
            $nnaRepresentanteMigrante->MIGRAN_NACIONALIDAD = $data['nacionalidad'];
            $nnaRepresentanteMigrante->MIGRAN_NACIONALIDAD_MANUAL = $data['nacionalidad_manual'];

            $nnaRepresentanteMigrante->MIGRAN_IDSOLICITUD = $data['id_solicitud'];
            $nnaRepresentanteMigrante->MIGRAN_GRUPOFAMILIAR = $data['id_grupo_familiar'];
            //campos nuevos
            $nnaRepresentanteMigrante->MIGRAN_TIPO_DOCUMENTO = $data['tipo_pasaporte'];
            $nnaRepresentanteMigrante->MIGRAN_MSGTIPO_DOCUMENTO = $data['msg_pasaporte'];
            $nnaRepresentanteMigrante->MIGRAN_SEXO = $data['sexo'];
            $nnaRepresentanteMigrante->MIGRAN_MSGSEXO = $data['msg_sexo'];

            $nnaRepresentanteMigrante->MIGRAN_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $nnaRepresentanteMigrante->MIGRAN_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;

            // Verificar si se ha adjuntado un documento
            if (isset($data['adjuntarDocumento'])) {
                $nnaRepresentanteMigrante->MIGRAN_ARCHIVO_SCAN = $data['adjuntarDocumento'];
            }


            $nnaRepresentanteMigrante->save();

            DB::commit();

            return $nnaRepresentanteMigrante;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function actualizarDatos($id, $data)
    {
        DB::beginTransaction();
        try {
            // Obtener el registro existente por ID
            $nnaRepresentanteMigrante = NNARepresentanteMigrante::find($id);

            if (!$nnaRepresentanteMigrante) {
                throw new \Exception('Registro no encontrado');
            }

            // Verificar que MIGRAN_VIGENTE y MIGRAN_CALCULOACTIVO sean igual a 1 antes de actualizar
            if ($nnaRepresentanteMigrante->MIGRAN_VIGENTE == 1 && $nnaRepresentanteMigrante->MIGRAN_CALCULOACTIVO == 1) {
                // Actualizar los campos de teléfono y correo electrónico
                $nnaRepresentanteMigrante->MIGRAN_CELULAR = $data['telefono_movil'];
                $nnaRepresentanteMigrante->MIGRAN_TELEFONO = $data['telefono_fijo'];
                $nnaRepresentanteMigrante->MIGRAN_EMAIL = $data['email'];

                $nnaRepresentanteMigrante->save();

                DB::commit();

                return $nnaRepresentanteMigrante;
            } else {
                throw new \Exception('No se pueden actualizar los campos.');
            }
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}
