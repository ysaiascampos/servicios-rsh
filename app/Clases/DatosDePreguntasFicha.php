<?php

namespace App\Clases;

use Exception;


class DatosDePreguntasFicha
{
    const MODULO_EDUCACION = 'educacion';
    const MODULO_SALUD = 'salud';
    const MODULO_INGRESO = 'ingreso';
    const MODULO_PUEBLO_ORIGINARIO = 'pueblo_originario';

    private $tipo_solicitud = array(
        self::MODULO_EDUCACION => "6",
        self::MODULO_SALUD => "5",
        self::MODULO_INGRESO => "14"
    );

    private $diccionario_modulos = array(
        self::MODULO_EDUCACION => array(
            'nombre_modulo' => self::MODULO_EDUCACION,
            'preguntas' => 'preguntasEducacion'
        ),
        self::MODULO_SALUD => array(
            'nombre_modulo' => self::MODULO_SALUD,
            'preguntas' => 'preguntasSalud'
        ),

        self::MODULO_INGRESO => array(
            'nombre_modulo' => self::MODULO_INGRESO,
            'preguntas' => 'preguntasIngreso'
        ),
    );
    private $datosDeRespuestas;

    public function __construct()
    {
        $this->datosDeRespuestas =new Preguntas();
    }

    public function showModulo($tipoModulo, $runCiudadano = null)
    {
        $respuesta = "";
        try {
            $data = [];
            if ($tipoModulo == 'todos') {
                foreach ($this->diccionario_modulos as $nombre_modulo) {
                    $data[$nombre_modulo['nombre_modulo']] = $this->datosDeRespuestas
                        ->findPreguntasbyModuloAndDependency($runCiudadano, $nombre_modulo['nombre_modulo'], $respuesta);
                }
            } else {
                $data = [
                    'datos-respuestas' => $this->datosDeRespuestas
                        ->findPreguntasbyModuloAndDependency($runCiudadano, $tipoModulo, $respuesta)
                ];
            }

        } catch (Exception $ex) {
            throw $ex;
        }
        return $data;
    }

    public function showModuloEdad($tipoModulo, $edad )
    {
        $respuesta = "";
        try {
            $data = [];
            if ($tipoModulo == 'todos') {
                foreach ($this->diccionario_modulos as $nombre_modulo) {
                    $data[$nombre_modulo['nombre_modulo']] = $this->datosDeRespuestas
                        ->findPreguntasbyModuloAndDependencyEdad($nombre_modulo['nombre_modulo'], $edad);
                }
            } else {
                $data = [
                    'datos-respuestas' => $this->datosDeRespuestas
                        ->findPreguntasbyModuloAndDependencyEdad($tipoModulo, $edad)
                ];
            }

        } catch (Exception $ex) {
            throw $ex;
        }
        return $data;
    }

    public function showModuloSinRun($tipoModulo)
    {
        try {
            $data = [];
            //respuesta cuando O1 vale:= { nombre='Sí', valor=1 }
            if ($tipoModulo == 'todos') {
                foreach ($this->diccionario_modulos as $nombre_modulo) {
                    $data[$nombre_modulo['nombre_modulo']] = [
                        'datos-respuestas' => $this->datosDeRespuestas->findPreguntasbyModulo($nombre_modulo['nombre_modulo'])
                    ];
                }
            } else {
                $data = [
                    'datos-respuestas' => $this->datosDeRespuestas->findPreguntasbyModulo($tipoModulo)
                ];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $data;
    }

}
