<?php


namespace App;


use Illuminate\Support\Facades\DB;

/**
 * Compatibiliza el uso de campos en mayuscula de ide-helper para secuencias
 * @package App
 */
class Sequence
{
    public static function nextValue($name)
    {
        return DB::select("SELECT $name.NEXTVAL as ID FROM DUAL")[0]->ID;
    }
}
