<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteFechaEdadTiempo extends ConstanteFuente
{
    /**
     * Número mínimo en el campo defunción; campo PER_FECHADEFUNCION tabla PERSONAS
     */
    const FECHA_100000_MINIMA_DEFUNCION = 100000;

    /**
     * EDADES
     */
    const EDAD_18_MAYOR_DE_EDAD = 18; // mayor o igual a 18
    const EDAD_60_ADULTO_MAYOR = 60; // mayor o igual a 60
    const EDAD_15_PUEDE_TRABAJAR = 15; // mayor o igual a 15 // fuente: leychile.cl/Navegar?idNorma=261579 y bcn.cl/leyfacil/recurso/trabajo-infantil
    const EDAD_16_PUEDE_TENER_PAREJA = 16;       // mayor o igual a 16
    const EDAD_16_PUEDE_SER_YERNO_O_NUERA = 16;  // mayor o igual a 16
    const EDAD_17_PUEDE_SER_CONYUGE_PAREJA = 17; // viene de Controlador antiguo FamiliaController:3148 por tanto puede estar DEPRECATED
    const EDAD_15_PUEDE_COMPRAR_O_IR_MEDICO_SIN_AYUDA = 15; // mayor o igual a 15 para pregunta S2b ¿Puede hacer compras o ir al médico solo/a; sin ayuda o compañía?
    const EDAD_6_PUEDE_SALIR_BANAR_MOVERSE_SIN_AYUDA = 6; // mayor o igual a 6 para preguntas:
    // S2a ¿Puede salir solo/a a la calle sin ayuda o compañía?
    // S2c Puede bañarse; lavarse los dientes; peinarse o comer solo/a?
    // S2d Puede moverse/desplazarse solo/a dentro de la casa?
    // S2e ¿Puede controlar completamente sus esfínteres?
    const EDAD_5_MINIMA_EDUCACION_BASICA = 5;
    const EDAD_13_MINIMA_EDUCACION_MEDIA = 13;
    const EDAD_17_MINIMA_EDUCACION_CFT_INSTITUTO_UNIVERSIDAD = 17;
    const EDAD_21_MINIMA_EDUCACION_UNIVERSIDAD_COMPLETA = 21;
    const EDAD_22_MINIMA_EDUCACION_POSTGRADO_COMPLETA = 22;

    const EDAD_17_MINIMA_MATRICULA_EDUCACION_SUPERIOR = 17;   // edad minima para cursar educación superior, ToDO se debe definir cual de las dos es correcta 17 o 18
    const EDAD_18_MINIMA_MATRICULA_EDUCACION_SUPERIOR = 18;   // edad minima para cursar educación superior, ToDo se debe definir cual de las dos es correcta 17 o 18
    const EDAD_24_MAXIMA_MATRICULA_EDUCACION_SUPERIOR = 24;   // edad máximo para cursar educación superior (Para que RSH otorgue beneficio)

    const EDAD_16_HABILITA_COMPLEMENTO_COMPENSACION_ECONOMICA = 16;

    /**
     * MESES
     */
    const MES01 = 'Enero';
    const MES02 = 'Febrero';
    const MES03 = 'Marzo';
    const MES04 = 'Abril';
    const MES05 = 'Mayo';
    const MES06 = 'Junio';
    const MES07 = 'Julio';
    const MES08 = 'Agosto';
    const MES09 = 'Septiembre';
    const MES10 = 'Octubre';
    const MES11 = 'Noviembre';
    const MES12 = 'Diciembre';

    public static $MESES = [
        1 => self::MES01,
        2 => self::MES02,
        3 => self::MES03,
        4 => self::MES04,
        5 => self::MES05,
        6 => self::MES06,
        7 => self::MES07,
        8 => self::MES08,
        9 => self::MES09,
        10 => self::MES10,
        11 => self::MES11,
        12 => self::MES12
    ];

    /**
     * MESES
     */
    const MES01MIN = 'enero';
    const MES02MIN = 'febrero';
    const MES03MIN = 'marzo';
    const MES04MIN = 'abril';
    const MES05MIN = 'mayo';
    const MES06MIN = 'junio';
    const MES07MIN = 'julio';
    const MES08MIN = 'agosto';
    const MES09MIN = 'septiembre';
    const MES10MIN = 'octubre';
    const MES11MIN = 'noviembre';
    const MES12MIN = 'diciembre';

    public static $MESESMIN = [
        1 => self::MES01MIN,
        2 => self::MES02MIN,
        3 => self::MES03MIN,
        4 => self::MES04MIN,
        5 => self::MES05MIN,
        6 => self::MES06MIN,
        7 => self::MES07MIN,
        8 => self::MES08MIN,
        9 => self::MES09MIN,
        10 => self::MES10MIN,
        11 => self::MES11MIN,
        12 => self::MES12MIN
    ];

    /**
     * Minutos que está una variable api en caché
     */
    const CACHE_API_MINUTOS = 180; // 3 horas

    /**
     * Fecha nacimiento default, cuando campo viene vacía
     */
    const DEFAULT_FECHA_NACIMIENTO = '18200101';

    const ESTUDIA_TRABAJA_0 = 0;
    const ESTUDIA_TRABAJA_1 = 1;

    const ESTUDIA_0 = 0;
    const ESTUDIA_1 = 1; 

}
