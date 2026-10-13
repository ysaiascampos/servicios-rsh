<?php


namespace App\Servicios\ReglasCuidadores;


use App\Clases\DatosDeContacto;
use App\Http\ResponseMappers\PersonaCuidadoresMapper;
use App\Models\Personas;
use Illuminate\Support\Facades\Validator;

class IngresoDatosCuidadores
{
    private $datosCuidador;
    private $datosDependiente;
    private $input;


    public function ValidarDatos($input)
    {
        $this->input = $input;

        $validator = Validator::make($this->input, [
            'dependiente' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!ctype_digit($value)) {
                        $fail('El campo dependiente solo debe contener números.');
                    } else {
                        if (!Personas::where('PER_RUN', $value)->exists()) {
                            $fail('El ' . $value . ' no existe en la en nuestros registros.');
                        } else {
                            $dependiente = DatosDeContacto::obtenerDatosContacto($value);
                            if ($dependiente['PER_FECHADEFUNCION'] != 0) {
                                $fail('El ' . $value . ' que ingreso que requiere cuidado, es una persona fallecida');
                            }
                            $edad = $dependiente->PER_EDAD;
                            $dependiente['MENOR'] = false;
                            if ($edad < 18) {
                                $dependiente['MENOR'] = true;
                            }

                            $mapear = new PersonaCuidadoresMapper();
                            $resultado = $mapear->map($dependiente);
                            $this->datosDependiente = $resultado;
                        }
                    }
                },
            ],
            'cuidador' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!ctype_digit($value)) {
                        $fail('El campo cuidador solo debe contener números.');
                    } else {
                        $persona = Personas::where('PER_RUN', $value)->first();
                        if (!$persona) {
                            $fail('El ' . $value . ' no existe  en nuestros registros.');
                        } elseif ($persona) {
                            $cuidado = DatosDeContacto::obtenerDatosContacto($value);
                            if ($cuidado['PER_FECHADEFUNCION'] != 0) {
                                $fail('El ' . $value . ' que ingreso como cuidador, es una persona fallecida');
                            }
                            $edad = $cuidado->PER_EDAD;

                            if ($edad < 18) {
                                $fail('El ' . $value . ' es menor de edad.');
                            }
                            $cuidado['MENOR'] = false;
                        }
                        if (isset($this->input['dependiente']) && $this->input['dependiente'] === $value) {
                            $fail('Los RUT no pueden ser iguales.');
                        }

                        $mapear2 = new PersonaCuidadoresMapper();
                        $resultado = $mapear2->map($cuidado);
                        $this->datosCuidador = $resultado;
                    }
                },
            ],
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            $errores = [];
            $errores = array_merge($errores, $validator->errors()->all());
            if (empty($input['dependiente']) && empty($input['cuidador'])) {
                $errores[] = 'Ambos RUTs están vacíos. Se requiere al menos un RUT.';
            }
            $datos = [
                "estatus" => true,
                'errores' => $errores,
            ];
            return $datos;
        }

        /*origen-registro si viene de municipal o ciudadano
        tipo-registro si viene cuidado o cuidador para hacer los filtros especificos
        */
        $datos = [
            "origen-registro" => $this->input['origen-registro'],
            "tipo-registro" => $this->input['tipo-registro'],
            "cuidador" => $this->datosCuidador,
            "dependiente" => $this->datosDependiente
        ];

        return $datos;
    }
}
