<?php

namespace App\Http\ResponseMappers;

class NacionalidadResponseMapper
{
    public function map($data)
    {
        return self::nacionalidades($data);
    }

    private function nacionalidades($data){
        $nacionalidades = [];
        foreach($data as $campo){
            $nacionalidades[] = [
                'id' => $campo->NAC_ID,
                'pais' => $campo->PAIS,
            ];
        }
        return $nacionalidades;
    }
}
