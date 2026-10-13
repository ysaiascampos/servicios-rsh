<?php

namespace App\Http\ResponseMappers;

use App\Clases\Respuestas;
use App\Models\FichaMontoMensual;

class FichaMapper extends ResponseMapper
{
    public function __construct($ficha, $preguntasRespuestas)
    {
        $monto = FichaMontoMensual::where('MENSUAL_ID_FICHA', $ficha->FICHA_ID)->first();

        $this->campos = [
            'id-ficha' => $ficha->FICHA_ID,
            'pareja' => self::entero($ficha->FICHA_NUMPAREJA),
            'parentesco' => self::mapParentesco($ficha),
            'informante' => self::entero($ficha->INFORMANTE),
            'pueblo-originario' => $ficha->FICHA_PUEBLOINDIGENA,

            'e1' => $e1 = self::mapRespuesta($ficha, 'FICHA_ASISTEESTABEDUCACIONAL', 'PER_FIC_ASISTEESTABEDUCACIONAL'),
            'e1-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'e1', $e1),
//            'e2' => $e2 = self::mapRespuesta($ficha, 'FICHA_NOASISTEMOTIVO', 'PER_FIC_NOASISTEMOTIVO'),
//            'e2-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'e2', $e2),
            'e3' => $e3 = self::mapRespuesta($ficha, 'FICHA_CURSO', 'PER_FIC_CURSO'),
            'e3-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'e3', $e3),
            'e4' => $e4 = self::mapRespuesta($ficha, 'FICHA_TIPOEDUCACION', 'PER_FIC_TIPOEDUCACION'),
            'e4-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'e4', $e4),

            's1' => $s1 = self::mapRespuesta($ficha, 'FICHA_CONDICIONDISCAPACIDAD'),
            's1-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1', $s1),
            's1a' => $s1a = self::mapRespuesta($ficha, 'FICHA_DIFICVISUAL', 'PER_FIC_DIFICVISUAL'),
            's1a-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1a', $s1a),
            's1b' => $s1b = self::mapRespuesta($ficha, 'FICHA_DIFICAUDITIVA', 'PER_FIC_DIFICAUDITIVA'),
            's1b-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1b', $s1b),
            's1c' => $s1c = self::mapRespuesta($ficha, 'FICHA_DIFICHABLA', 'PER_FIC_DIFICHABLA'),
            's1c-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1c', $s1c),
            's1d' => $s1d = self::mapRespuesta($ficha, 'FICHA_DIFICFISICA', 'PER_FIC_DIFICFISICA'),
            's1d-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1d', $s1d),
            's1e' => $s1e = self::mapRespuesta($ficha, 'FICHA_DIFICMENTAL', 'PER_FIC_DIFICMENTAL'),
            's1e-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1e', $s1e),
            's1f' => $s1f = self::mapRespuesta($ficha, 'FICHA_DIFICPSIQUICA', 'PER_FIC_DIFICPSIQUICA'),
            's1f-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's1f', $s1f),
            's2a' => $s2a = self::mapRespuesta($ficha, 'FICHA_CALLESINAYUDA', 'PER_FIC_CALLESINAYUDA'),
            's2a-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's2a', $s2a),
            's2b' => $s2b = self::mapRespuesta($ficha, 'FICHA_MEDICOSINAYUDA', 'PER_FIC_MEDICOSINAYUDA'),
            's2b-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's2b', $s2b),
            's2c' => $s2c = self::mapRespuesta($ficha, 'FICHA_ASEOSINAYUDA', 'PER_FIC_ASEOSINAYUDA'),
            's2c-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's2c', $s2c),
            's2d' => $s2d = self::mapRespuesta($ficha, 'FICHA_DESPLAZAMIENTOENCASA', 'PER_FIC_DESPLAZAMIENTOENCASA'),
            's2d-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's2d', $s2d),
            's2e' => $s2e = self::mapRespuesta($ficha, 'FICHA_CTRLESFINTERES', 'PER_FIC_CTRLESFINTERES'),
            's2e-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 's2e', $s2e),

            'o1' => $o1 = self::mapRespuesta($ficha, 'FICHA_TRABAJA', 'PER_FIC_TRABAJA'),
            'o1-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o1', $o1),
            'o2' => $o2 = self::mapRespuesta($ficha, 'FICHA_OCUPACIONACTUAL', 'PER_FIC_OCUPACIONACTUAL'),
            'o2-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o2', $o2),
            'o3' => $o3 = self::mapRespuesta($ficha, 'FICHA_CODIGOSRAMA', 'PER_FIC_CODIGOSRAMA'),
            'o3-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o3', $o3),
            'o4' => $o4 = self::mapRespuesta($ficha, 'FICHA_TEMPORALIDADPERMANENCIA'),
            'o4-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o4', $o4),
            'o5' => $o5 = self::mapRespuesta($ficha, 'FICHA_CODIGOCONTRATO', 'PER_FIC_CODIGOCONTRATO'),
            'o5-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o5', $o5),
            'o6' => $o6 = self::mapRespuesta($ficha, 'FICHA_RELACIONCONTRACTUAL', 'PER_FIC_RELACIONCONTRACTUAL'),
            'o6-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o6', $o6),
            'o7' => $o7 = self::mapRespuesta($ficha, 'FICHA_HORASTRABAJO', 'PER_FIC_HORASTRABAJO'),
            'o7-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o7', $o7),
            'o8' => $o8 = self::mapRespuesta($ficha, 'FICHA_BUSCOTRABAJO', 'PER_FIC_HORASTRABAJO'),
            'o8-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o8', $o8),
            'o9' => $o9 = self::mapRespuesta($ficha, 'FICHA_NOBUSCOTRABAJOMOTIVO', 'PER_FIC_NOBUSCOTRABAJOMOTIVO'),
            'o9-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'o9', $o9),

            'y1' => $y1 = self::mapRespuesta($ficha, 'FICHA_INGRESOANUAL', 'PER_FIC_INGRESOANUAL'),
            'y1-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'y1', $y1),
            'y2' => $y2 = self::mapRespuesta($ficha, 'FICHA_INGRESOJUBILACION', 'PER_FIC_INGRESOJUBILACION'),
            'y2-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'y2', $y2),
            'y3' => $y3 = self::mapRespuesta($ficha, 'FICHA_INGRESOOTROS', 'PER_FIC_INGRESOOTROS'),
            'y3-texto' => Respuestas::obtenerTextoRespuestas($preguntasRespuestas, 'y3', $y3),

            'y1-m1' => $monto ? $monto->MENSUAL_Y1_M1 : null,
            'y1-m2' => $monto ? $monto->MENSUAL_Y1_M2 : null,
            'y1-m3' => $monto ? $monto->MENSUAL_Y1_M3 : null,
            'y1-m4' => $monto ? $monto->MENSUAL_Y1_M4 : null,
            'y1-m5' => $monto ? $monto->MENSUAL_Y1_M5 : null,
            'y1-m6' => $monto ? $monto->MENSUAL_Y1_M6 : null,
            'y1-m7' => $monto ? $monto->MENSUAL_Y1_M7 : null,
            'y1-m8' => $monto ? $monto->MENSUAL_Y1_M8 : null,
            'y1-m9' => $monto ? $monto->MENSUAL_Y1_M9 : null,
            'y1-m10' => $monto ? $monto->MENSUAL_Y1_M10 : null,
            'y1-m11' => $monto ? $monto->MENSUAL_Y1_M11 : null,
            'y1-m12' => $monto ? $monto->MENSUAL_Y1_M12 : null,

            'y2-m1' => $monto ? $monto->MENSUAL_Y2_M1 : null,
            'y2-m2' => $monto ? $monto->MENSUAL_Y2_M2 : null,
            'y2-m3' => $monto ? $monto->MENSUAL_Y2_M3 : null,
            'y2-m4' => $monto ? $monto->MENSUAL_Y2_M4 : null,
            'y2-m5' => $monto ? $monto->MENSUAL_Y2_M5 : null,
            'y2-m6' => $monto ? $monto->MENSUAL_Y2_M6 : null,
            'y2-m7' => $monto ? $monto->MENSUAL_Y2_M7 : null,
            'y2-m8' => $monto ? $monto->MENSUAL_Y2_M8 : null,
            'y2-m9' => $monto ? $monto->MENSUAL_Y2_M9 : null,
            'y2-m10' => $monto ? $monto->MENSUAL_Y2_M10 : null,
            'y2-m11' => $monto ? $monto->MENSUAL_Y2_M11 : null,
            'y2-m12' => $monto ? $monto->MENSUAL_Y2_M12 : null,
            'y-mes-inicio' => self::entero($ficha->MENSUAL_FECHA_INICIO),
        ];
    }

    private function mapParentesco($integrante)
    {
        return [
            'valor' => self::entero($integrante->ID_PARENTESCO),
            'descripcion' => $integrante->NOMBRE_PARENTESCO ?: null,
        ];
    }

    private function mapRespuesta($ficha, $campoAsignacion, $campoExport = null)
    {
        return self::entero($ficha->$campoAsignacion ?? $ficha->$campoExport);
    }
}
