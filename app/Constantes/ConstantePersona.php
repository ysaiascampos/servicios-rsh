<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstantePersona extends ConstantePreguntaRespuestaESOV
{
    /**
     * NACIONALIDADES (TABLA NACIONALIDADES)
     */
    const NACIONALIDAD_1_CHILENA = 1;
    const NACIONALIDAD_2_EXTRANJERO = 2;

    /**
     * PARENTESCOS (TABLA PARENTESCOS)
     */
    const PARENTESCO_1_JEFE_DE_HOGAR = 1;
    const PARENTESCO_2_CONYUGE_O_PAREJA = 2;
    const PARENTESCO_3_HIJO_DE_AMBOS = 3;
    const PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR = 4;
    const PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA = 5;
    const PARENTESCO_6_PADRE_O_MADRE = 6;
    const PARENTESCO_7_SUEGRO_O_SUEGRA = 7;
    const PARENTESCO_8_YERNO_O_NUERA = 8;
    const PARENTESCO_9_NIETO = 9;
    const PARENTESCO_10_HERMANO = 10;
    const PARENTESCO_11_CUNADO = 11;
    const PARENTESCO_12_OTRO_FAMILIAR = 12;
    const PARENTESCO_13_NO_FAMILIAR = 13;
    const PARENTESCO_14_NO_PARIENTE = 14;    // Con estado 0 en tabla AB_PARENTESCO
    const PARENTESCO_15_NO_PARIENTE = 15;    // Con estado 0 en tabla AB_PARENTESCO
    const PARENTESCO_99_POR_DEFINIR = 99;    // También llamado 'NO PARIENTE'
    public static $RESPUESTA_PARENTESCO_1_A_99_POSIBLES_RESPUESTAS = [
        self::PARENTESCO_1_JEFE_DE_HOGAR,
        self::PARENTESCO_2_CONYUGE_O_PAREJA,
        self::PARENTESCO_3_HIJO_DE_AMBOS,
        self::PARENTESCO_4_HIJO_SOLO_DEL_JEFE_DE_HOGAR,
        self::PARENTESCO_5_HIJO_SOLO_DEL_CONYUGE_O_PAREJA,
        self::PARENTESCO_6_PADRE_O_MADRE,
        self::PARENTESCO_7_SUEGRO_O_SUEGRA,
        self::PARENTESCO_8_YERNO_O_NUERA,
        self::PARENTESCO_9_NIETO,
        self::PARENTESCO_10_HERMANO,
        self::PARENTESCO_11_CUNADO,
        self::PARENTESCO_12_OTRO_FAMILIAR,
        self::PARENTESCO_13_NO_FAMILIAR,
        self::PARENTESCO_14_NO_PARIENTE,
        self::PARENTESCO_15_NO_PARIENTE,
        self::PARENTESCO_99_POR_DEFINIR
    ];

    /**
     * PUEBLOS INDIGENAS (Table AB_PUEBLO_INDIGENA)
     */
    const PUEBLOS_INDIGENA_0_NO_PERTENECE_A_NINGUNO_DE_ELLOS = 0;
    const PUEBLOS_INDIGENA_1_AYMARA = 1;
    const PUEBLOS_INDIGENA_2_RAPA_NUI = 2;
    const PUEBLOS_INDIGENA_3_QUECHUA = 3;
    const PUEBLOS_INDIGENA_4_MAPUCHE = 4;
    const PUEBLOS_INDIGENA_5_ATACAMENO = 5;
    const PUEBLOS_INDIGENA_6_COYAS = 6;
    const PUEBLOS_INDIGENA_7_KAWESQAR = 7;
    const PUEBLOS_INDIGENA_8_YAGAN = 8;
    const PUEBLOS_INDIGENA_9_DIAGUITA = 9;

    /**
     * SEXOS (TABLA SEXOS)
     */
    const SEXO_1_MASCULINO = 1;
    const SEXO_2_FEMENINO = 2;

    const SEXO_STRING_MASCULINO = 'Hombre';
    const SEXO_STRING_FEMENINO = 'Mujer';

    /**
     * Campo PER_ESTADOCIVIL de PERSONAS
     */
    const ESTADO_CIVIL_CASADO = 'CASADO';
    const ESTADO_CIVIL_CONVIVIENTE_CIVIL = 'CONVIVIENTE CIVIL';
    const ESTADO_CIVIL_DIVORCIADO = 'DIVORCIADO';
    const ESTADO_CIVIL_SOLTERO = 'SOLTERO';
    const ESTADO_CIVIL_VIUDO = 'VIUDO';
    const ESTADO_CIVIL_ANULADO = 'ANULADO';                                     // guardado en tabla ASIGNACION.PERSONAS
    const ESTADO_CIVIL_ANULADO_EQUIVALENTE_A_SOL = 'ANULADO-EQUIVALENTE A SOL'; // guardado en tabla ASIGNACION.PERSONAS
    const ESTADO_CIVIL_SEPARADO = 'SEPARADO';                                   // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale
    const ESTADO_CIVIL_SEPARADO_JUDICIALMENTE = 'SEPARADO JUDICIALMENTE';       // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale
    const ESTADO_CIVIL_SEPARADO_JUDICIALMENTE_EQ = 'SEPARADO JUDICIALMENTE-EQ'; // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale
    const ESTADO_CIVIL_DESCONOCIDO = 'DESCONOCIDO';                             // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale
    const ESTADO_CIVIL_ERROR = 'ERROR';                                         // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale
    const ESTADO_CIVIL_SPACE = ' ';                                             // guardado en tabla ASIGNACION.PERSONAS, no se sabe que equivale

    // todos los equivalentes a CASADO
    public static $ESTADO_CIVIL_CASADO_EQUIVALENTE = [
        self::ESTADO_CIVIL_CASADO,
        self::ESTADO_CIVIL_CONVIVIENTE_CIVIL
    ];
    public static $ESTADO_CIVIL_ANULADO = [
        self::ESTADO_CIVIL_ANULADO,
        self::ESTADO_CIVIL_ANULADO_EQUIVALENTE_A_SOL
    ];
    public static $ESTADO_CIVIL_SEPARADO = [
        self::ESTADO_CIVIL_SEPARADO,
        self::ESTADO_CIVIL_SEPARADO_JUDICIALMENTE,
        self::ESTADO_CIVIL_SEPARADO_JUDICIALMENTE_EQ
    ];
    public static $ESTADO_CIVIL_SOLTERO_EQUIVALENTE = [
        self::ESTADO_CIVIL_SOLTERO,
        self::ESTADO_CIVIL_ANULADO,
        self::ESTADO_CIVIL_ANULADO_EQUIVALENTE_A_SOL
    ];
    public static $RESPUESTA_ESTADO_CIVIL_POSIBLES_RESPUESTAS = [
        self::ESTADO_CIVIL_SOLTERO,
        self::ESTADO_CIVIL_CASADO,
        self::ESTADO_CIVIL_DIVORCIADO,
        self::ESTADO_CIVIL_VIUDO,
        self::ESTADO_CIVIL_CONVIVIENTE_CIVIL
    ];
    public static $RESPUESTA_ESTADO_CIVIL_POSIBLES_RESPUESTAS_DUAL = [
        self::ESTADO_CIVIL_SOLTERO => self::ESTADO_CIVIL_SOLTERO,
        self::ESTADO_CIVIL_CASADO => self::ESTADO_CIVIL_CASADO,
        self::ESTADO_CIVIL_DIVORCIADO => self::ESTADO_CIVIL_DIVORCIADO,
        self::ESTADO_CIVIL_VIUDO => self::ESTADO_CIVIL_VIUDO,
        self::ESTADO_CIVIL_CONVIVIENTE_CIVIL => self::ESTADO_CIVIL_CONVIVIENTE_CIVIL
    ];

    /**
     * INFORMANTE
     */
    const RESPUESTA_INFORMANTE_1_PERSONA_INFORMANTE = 1;

    /**
     * Constante para no encontrado en BD para ingreso manual
     */
    const PERSONA_NO_EN_BD = -99;

    /**
     * Constante para Persona Juridica o natural
     */
    const PERSONA_JURIDICA = 420;
    const PERSONA_NATURAL = 421;

}
