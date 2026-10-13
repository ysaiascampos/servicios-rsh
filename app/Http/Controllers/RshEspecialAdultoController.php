<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clases\RshEspecialAdulto;
use App\Http\ResponseMappers\RshEspecialAdultoMapper;
use App\Models\NNARepresentanteMigrante;
use Illuminate\Support\Facades\Validator;
use App\Http\ResponseMappers\JsonMapper;
use Exception;

class RshEspecialAdultoController extends Controller
{
    private $rshEspecialAdultoService;
    private $rshEspecialAdultoMapper;

    public function __construct(RshEspecialAdulto $rshEspecialAdultoService, RshEspecialAdultoMapper $rshEspecialAdultoMapper)
    {
        $this->rshEspecialAdultoService = $rshEspecialAdultoService;
        $this->rshEspecialAdultoMapper = $rshEspecialAdultoMapper;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string', // DNI debe ser requerido y una cadena de caracteres
            'nacionalidad' => 'required', // Nacionalidad debe ser requerida
            'nacionalidad_manual' => 'nullable|string', // Nacionalidad manual es opcional y una cadena de caracteres
            'fecha_nacimiento' => 'required|date_format:d/m/Y', // Fecha de nacimiento debe ser requerida y tener formato dd/mm/yyyy
            'nombre' => 'required|string', // Nombres debe ser requerido y una cadena de caracteres
            'apellido1' => 'required|string', // Apellido 1 debe ser requerido y una cadena de caracteres
            'apellido2' => 'nullable|string', // Apellido 2 es opcional y una cadena de caracteres
            'parentesco' => 'required', // Parentesco debe ser requerido
            'telefono_movil' => 'required|numeric', // Teléfono móvil debe ser requerido y numérico
            'email' => 'required|email', // Email debe ser requerido y tener formato de email válido
            'adjuntarDocumento' => 'required|string', // Cambie la validación a "string" para recibir la ruta del archivo
            'tipo_pasaporte' => 'required|numeric',
            'msg_pasaporte' => 'required|string',
            'sexo' => 'required|numeric',
            'msg_sexo' => 'required|string',
        ]);

        if ($validator->fails()) {
            //
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Guardar los datos utilizando el servicio
            $rshEspecialAdultoData = $request->only([
                'dni',
                'dniConfirmacion',
                'nacionalidad',
                'nacionalidad_manual',
                'fecha_nacimiento',
                'nombre',
                'apellido1',
                'apellido2',
                'parentesco',
                'telefono_movil',
                'telefono_fijo',
                'email',
                'id_solicitud',
                'id_grupo_familiar',
                'nuevofolio',
                'adjuntarDocumento',
                'tipo_pasaporte',
                'msg_pasaporte',
                'sexo',
                'msg_sexo',
            ]);
            $rshEspecialAdulto = $this->rshEspecialAdultoService->guardarDatos($rshEspecialAdultoData);

            // Mapear la respuesta utilizando el mapper
            $rshEspecialAdultoMapped = $this->rshEspecialAdultoMapper->map($rshEspecialAdulto);

            // Retornar la respuesta
            return new JsonMapper($rshEspecialAdultoMapped);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->rshEspecialAdultoService->actualizarDatos($id, $request->all());
            return response()->json(['message' => 'Los campos se actualizaron con éxito'], 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
