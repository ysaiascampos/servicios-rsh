<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clases\RegistroConsultaNna;
use App\Http\ResponseMappers\RegistroConsultaNnaMapper;
use Illuminate\Support\Facades\Validator;
use App\Http\ResponseMappers\JsonMapper;
use Exception;
use App\Models\ConsultaNna;

class RegistroConsultaNnaController extends Controller
{
    private $registroConsultaNnaService;
    private $registroConsultaNnaMapper;

    public function __construct(RegistroConsultaNna $registroConsultaNnaService, RegistroConsultaNnaMapper $registroConsultaNnaMapper)
    {
        $this->registroConsultaNnaService = $registroConsultaNnaService;
        $this->registroConsultaNnaMapper = $registroConsultaNnaMapper;
    }

    public function store(Request $request)
    {

        // Define la regla personalizada para 'tipo_documento_adulto'
        Validator::extend('documento_tipo', function ($attribute, $value, $parameters, $validator) {
            // Verifica si el valor es igual a "RUN" o "DNI/Pasaporte" (insensible a mayúsculas y minúsculas)
            return strtoupper($value) === 'RUN' || strtoupper($value) === 'DNI/PASAPORTE';
        });

        // Mensaje de error personalizado para la regla
        Validator::replacer('documento_tipo', function ($message, $attribute, $rule, $parameters) {
            return 'El campo ' . $attribute . ' debe ser igual a "RUN" o "DNI/Pasaporte".';
        });

        $validator = Validator::make($request->all(), [
            'run_nna' => 'required|string',
            'nombre_completo_nna' => 'nullable|string',
            'fecha_nacimiento_nna' => 'nullable|date_format:d/m/Y',
            'documento_adjunto' => 'nullable|string',
            'tipo_documento_adulto' => 'nullable|documento_tipo',
            'dni_pasaporte_adulto' => 'nullable|string', // El campo es opcional
            'nombreFuncionario' => 'required|string',
            'rutFuncionario' => 'required|string',
            'perfilFuncionario' => 'required|string',
            'comunaFuncionario' => 'required|string',
            'ipCliente' => 'required|string',
            'id_seccion' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Obtener los datos del funcionario y de la consulta
            $datosFuncionario = $request->only([
                'nombreFuncionario',
                'rutFuncionario',
                'perfilFuncionario',
                'comunaFuncionario',
                'ipCliente',
                'fecha'
            ]);

            $datosConsulta = $request->only([
                'run_nna',
                'nombre_completo_nna',
                'fecha_nacimiento_nna',
                'documento_adjunto',
                'tipo_documento_adulto',
                'dni_pasaporte_adulto',
                'fecha_consulta',
                'id_funcionario', // Clave foránea
                'id_seccion', // Clave foránea
            ]);

            $registroConsultaNna = $this->registroConsultaNnaService->guardarConsulta($datosConsulta, $datosFuncionario);

            $consulta = $registroConsultaNna['consulta'];
            $funcionario = $registroConsultaNna['funcionario'];

            $registroConsultaNnaMapped = $this->registroConsultaNnaMapper->map($consulta, $funcionario);

            return new JsonMapper($registroConsultaNnaMapped);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
