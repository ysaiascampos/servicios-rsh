<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionValidacion;
use App\Models\Preguntas as ModeloPreguntas;
use App\Models\Respuestas;
use App\Models\Personas;
use App\Utiles;

class Preguntas
{

    public function findPreguntasViviendaDependency()
    {
        $dependencia = DependenciaModulo::vivienda();
        $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['V']);
        $respuestas = Respuestas::findAllViviendaActive();
        return $this->setDataToResponse([
            'dependencias' => $dependencia,
            'preguntas' => $preguntas,
            'respuestas' => $respuestas
        ]);
    }

    public function findPreguntasbyModuloAndDependency($runCiudadano, $modulo, $paramRespuestaValue)
    {
        $ciudadano = Utiles::getPersona(trim($runCiudadano));
        if (!($ciudadano instanceof Personas)) {
            throw new ExcepcionConsistencia('El ciudadano no posee registro en la plataforma', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }
        $edad = Utiles::getEdad($ciudadano->PER_FECHANACIMIENTO);
        $data = $this->findPreguntasbyModuloAndDependencyEdad($modulo, $edad);
        return $data;
    }

    public function findPreguntasbyModuloAndDependencyEdad($modulo, $edad){
        $data = null;
        switch ($modulo) {
            case 'educacion':
                $data = $this->setDataToResponse([
                    'dependencias' => DependenciaModulo::educacion($edad),
                    'preguntas' => ModeloPreguntas::findPreguntasByPregPadre(['E']),
                    'respuestas' => Respuestas::findAllEducacionActive()
                ]);
                break;
            case 'salud':
                $data = $this->setDataToResponse([
                    'dependencias' => DependenciaModulo::salud($edad),
                    'preguntas' => ModeloPreguntas::findPreguntasByPregPadre(['S']),
                    'respuestas' => Respuestas::findAllSaludActive()
                ]);
                break;
            case 'ingreso':
                $data = $this->setDataToResponse([
                    'dependencias' => DependenciaModulo::ingreso($edad),
                    'preguntas' => ModeloPreguntas::findPreguntasByPregPadre(['O', 'Y']),
                    'respuestas' => Respuestas::findAllOcupacionIngresoActive()
                ]);
                break;
            case 'vivienda':
                $dependencia = DependenciaModulo::vivienda();
                $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['V']);
                $respuestas = Respuestas::findAllViviendaActive();
                $data = $this->setDataToResponse([
                    'dependencias' => $dependencia,
                    'preguntas' => $preguntas,
                    'respuestas' => $respuestas
                ]);
                break;
            default:
                throw new ExcepcionValidacion('El módulo ' . $modulo . ' no existe en la plataforma', Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO);
                break;
        }
        return $data;
    }

    /**
     * En caso que una pregunta sea del tipo 4, es decir del tipo entrada por parte del usuario INPUT
     * esta debera ir obligatoriamente con una respuesta por defecto
     * caso contrario la secuencia de dependencia de las preguntas no se lograra de completaar de buena forma
     * ejemplo caso pregunta Y1 -> Tipo 4 -> posee en la tabla respuesta "default y1"
     * con valor 0 id categoria 107 (ID Pregunta)
     */
    private function setDataToResponse(array $data)
    {

        $salida = [];
        $arrayCodigosDePreguntas = array_column($data['preguntas']->toArray(), 'CODIGO');

        foreach ($data['dependencias'] as $codigoPregunta => $dependencia) {

            $indicePregunta = array_search(ucfirst($codigoPregunta), $arrayCodigosDePreguntas);
            $pregunta = $data['preguntas'][$indicePregunta];
            $listaRepuestas = $data['respuestas'][ucfirst($codigoPregunta)];

            $detalle = [
                'nombre-municipal' => $pregunta->NOMBRE,
                'nombre-ciudadano' => $pregunta->NOMBRE_CIUDADANO,
                'hint' => $pregunta->INDICACION,
                'tipo-input' => $pregunta->TIPO_INPUT,
                'opciones-respuesta' => []
            ];

            foreach ($listaRepuestas as $respuesta) {
                $siguiente = null;
                $autorespuesta = null;
                if (array_key_exists($respuesta->VALOR, $dependencia)) {
                    $siguiente = $dependencia[$respuesta->VALOR];
                } elseif (array_key_exists('default', $dependencia)) {
                    $siguiente = $dependencia['default'];
                } else {
                    continue;
                }
                if (array_key_exists('auto', $siguiente)) {
                    $autorespuesta = [
                        'pregunta' => key($siguiente['auto']),
                        'respuesta-pregunta' => current($siguiente['auto'])
                    ];
                }
                $detalle['opciones-respuesta'][] = [
                    'respuesta-municipal' => $respuesta->NOMBRE,
                    'respuesta-ciudadano' => $respuesta->NOMBRE_CIUDADANO,
                    'hint' => $respuesta->INDICACION,
                    'valor' => $respuesta->VALOR,
                    'siguiente-pregunta' => $siguiente['next'],
                    'auto-respuesta' => $autorespuesta
                ];
            }
            $salida[strtolower($codigoPregunta)] = $detalle;
        }
        return $salida;
    }

    public function findPreguntasbyModulo($valorModulo)
    {
        $data = [];
        $datosRespuestas = null;
        $preguntas = null;
        switch ($valorModulo) {
            case 'educacion':
                $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['E']);
                $datosRespuestas = Respuestas::findAllEducacionActive();
                break;
            case 'salud':
                $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['S']);
                $datosRespuestas = Respuestas::findAllSaludActive();
                break;
            case 'ingreso':
                $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['O', 'Y']);
                $datosRespuestas = Respuestas::findAllOcupacionIngresoActive();
                break;
            case 'vivienda':
                $preguntas = ModeloPreguntas::findPreguntasByPregPadre(['V']);
                $datosRespuestas = Respuestas::findAllViviendaActive();
                break;
            default:
                throw new ExcepcionValidacion('El módulo ' . $valorModulo . ' no existe en la plataforma', Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO);
                break;
        }
        $datosRespuestas = array_map(function ($elemento) {
            foreach ($elemento as $key => $value) {
                $nuevo['respuesta-municipal'] = $value['NOMBRE'];
                $nuevo['respuesta-ciudadano'] = $value['NOMBRE_CIUDADANO'];
                $nuevo['hint'] = $value['INDICACION'];
                $nuevo['valor'] = $value['VALOR'];
                $nuevo['siguiente-pregunta'] = null;
                $nuevo['auto-respuesta'] = null;
                $elemento[$key] = $nuevo;
            }
            return $elemento;
        },$datosRespuestas);
        foreach ($preguntas as $pregunta) {
            $data[strtolower($pregunta->CODIGO)] = [
                'nombre-municipal' => $pregunta->NOMBRE,
                'nombre-ciudadano' => $pregunta->NOMBRE_CIUDADANO,
                'tipo-input' => $pregunta->TIPO_INPUT,
                'hint' => $pregunta->INDICACION,
                'opciones-respuesta' => $datosRespuestas[$pregunta->CODIGO]
            ];
        }
        ksort($data,SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getPreguntasOrdenCiudadanoEducacion()
    {
        $arrayCodigoPreguntas = [
            'E1', 'E3', 'E4'
        ];
        return self::getPreguntasOrdenCiudadano($arrayCodigoPreguntas);
    }

    public function getPreguntasOrdenCiudadanoSalud()
    {
        $arrayCodigoPreguntas = [
            'S1', 'S1a', 'S1b', 'S1c', 'S1d', 'S1e', 'S1f', 'S2a', 'S2b', 'S2c', 'S2d', 'S2e'
        ];
        return self::getPreguntasOrdenCiudadano($arrayCodigoPreguntas);
    }

    public function getPreguntasOrdenCiudadanoOcupacionIngreso()
    {
        $arrayCodigoPreguntas = [
            'O1', 'O2', 'O3', 'O4', 'O5', 'O6', 'O7', 'O8', 'O9', 'Y1', 'Y2', 'Y3'
        ];
        return self::getPreguntasOrdenCiudadano($arrayCodigoPreguntas);
    }

    public function getPreguntasOrdenCiudadanoVivienda()
    {
        $arrayCodigoPreguntas = [
            'V1', 'V2', 'V3', 'V4', 'V5', 'V6', 'V7', 'V8', 'V9', 'V9EC', 'V10', 'V10EC', 'V11', 'V11EC', 'EC'
        ];
        return self::getPreguntasOrdenCiudadano($arrayCodigoPreguntas);
    }

    public function getPreguntasOrdenCiudadano($arrayCodigoPreguntas)
    {
        $datosDePreguntas = [];
        foreach ($arrayCodigoPreguntas as $codigoPregunta) {
            $datosDePreguntas[$codigoPregunta] = ModeloPreguntas::findPreguntasByCodigo($codigoPregunta)['nombre_ciudadano'];
        }
        return $datosDePreguntas;
    }

}
