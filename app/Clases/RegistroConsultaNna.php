<?php

namespace App\Clases;

use App\Models\ConsultaNna;
use App\Models\FuncionarioAuditoria;
use Illuminate\Support\Facades\DB;
use App\Sequence;
use Carbon\Carbon;


// Clase para registrar una consulta de NNA
class RegistroConsultaNna
{
    /**
     * Guarda una consulta de NNA en la base de datos.
     *
     * @param array $datosConsulta Los datos de la consulta de NNA.
     * @param array $datosFuncionario Los datos del funcionario que realiza la consulta.
     * @return array El arreglo que contiene tanto los datos de la consulta como los del funcionario.
     * @throws \Exception Si ocurre un error al guardar la consulta.
     */
    public function guardarConsulta($datosConsulta, $datosFuncionario)
    {
        // Iniciar una transacción de la base de datos
        DB::beginTransaction();

        try {

            $fechaIngresada = $datosConsulta['fecha_nacimiento_nna'];

            // Verifica si la fecha ingresada es válida
            if ($fechaIngresada !== null) {
                list($dia, $mes, $anio) = explode('/', $fechaIngresada);

                // Verifica si la fecha ingresada es válida
                if (checkdate($mes, $dia, $anio)) {
                    $fechaCarbon = Carbon::createFromFormat('d/m/Y', $fechaIngresada); // Convierte la fecha ingresada en un objeto Carbon
                    $datosConsulta['fecha_nacimiento_nna'] = $fechaCarbon->format('Y-m-d'); // Asigna la fecha en formato Y-m-d
                }
            }

            // Crear un nuevo registro de funcionario
            $funcionario = new FuncionarioAuditoria(); // Crear una nueva instancia del modelo FuncionarioAuditoria
            $funcionario->id_funcionario = Sequence::nextValue('SEQ_FUNCIONARIOS_AUDITORIA'); // Obtener el siguiente valor de la secuencia
            $funcionario->nombre = $datosFuncionario['nombreFuncionario']; // Obtener el nombre del funcionario
            $funcionario->rut = $datosFuncionario['rutFuncionario']; // Obtener el RUT del funcionario
            $funcionario->perfil = $datosFuncionario['perfilFuncionario']; // Obtener el perfil del funcionario
            $funcionario->comuna = $datosFuncionario['comunaFuncionario']; // Obtener la comuna del funcionario
            $funcionario->ip = $datosFuncionario['ipCliente']; // Obtener la IP del cliente
            $funcionario->save(); // Guardar el registro del funcionario

            // Crear una nueva consulta
            $consulta = new ConsultaNna(); // Crear una nueva instancia del modelo ConsultaNna
            $consulta->id_consulta = Sequence::nextValue('SEQ_CONSULTAS_NNA'); // Obtener el siguiente valor de la secuencia
            $consulta->run_nna = $datosConsulta['run_nna']; // Obtener el RUN del NNA
            $consulta->nombre_completo_nna = $datosConsulta['nombre_completo_nna']; // Obtener el nombre completo del NNA
            $consulta->fecha_nacimiento_nna = $datosConsulta['fecha_nacimiento_nna']; // Obtener la fecha de nacimiento del NNA
            $consulta->documento_adjunto = $datosConsulta['documento_adjunto']; // Obtener el nombre del archivo adjunto
            $consulta->tipo_documento_adulto = $datosConsulta['tipo_documento_adulto']; // Obtener el tipo de documento del adulto consultante
            $consulta->dni_pasaporte_adulto = $datosConsulta['dni_pasaporte_adulto']; // Obtener el número de documento del adulto consultante
            $consulta->id_seccion = $datosConsulta['id_seccion']; // Obtener el ID de la sección
            // Establecer la fecha de consulta como la fecha y hora actuales
            $fechaConsulta = Carbon::now();
            $datosConsulta['fecha_consulta'] = $fechaConsulta;

            // Asignar la fecha de consulta
            $consulta->fecha_consulta = $datosConsulta['fecha_consulta'];

            // Verificar si se ha adjuntado un documento
            if (isset($datosConsulta['adjuntarDocumento'])) {
                $consulta->documento_adjunto = $datosConsulta['adjuntarDocumento'];
            }

            // Asignar el ID del funcionario a la consulta
            $consulta->id_funcionario = $funcionario->id_funcionario; // Donde $funcionario es el funcionario que se creó anteriormente

            $consulta->save(); // Guardar la consulta
            // Confirmar la transacción
            DB::commit();

            // Crear un arreglo que contenga tanto los datos de la consulta como los del funcionario
            $consultaYFuncionario = [
                'consulta' => $consulta,
                'funcionario' => $funcionario,
            ];

            // Retornar el arreglo
            return $consultaYFuncionario;

            // Si ocurre un error, hacer rollback de la transacción
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}
