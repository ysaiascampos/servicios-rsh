<?php


namespace App\Servicios\ReglasCuidadores;


use Exception;
use Illuminate\Pipeline\Pipeline;


class ClasePipelineCuidadores
{
    public function procesarDatos($data, $lista)
    {
        try {
            $result = app(Pipeline::class)
                ->send($data)
                ->through($lista)
                ->then(function ($resultado) {
                    return response()->json([
                        'status' => 'success',
                        'data' => $resultado
                    ], 200);
                });
        } catch (Exception $e) {
            // Manejar la excepción y retornar el error en formato JSON
            return response()->json([
                'status' => "error",
                'message' => $e->getMessage(),
            ], 400);
        }

        return $result;
    }
}