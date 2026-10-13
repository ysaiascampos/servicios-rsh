<?php

namespace App\Http\ResponseMappers;

class ParentescoResponseMapper
{
    public function map($data)
    {
        return self::parentescos($data);
    }

    private function parentescos($data)
    {
        $parentescos = [];
        foreach ($data as $campo) {
            $parentescos[] = [
                'id' => $campo->PAREN_ID,
                'nombre' => $campo->PAREN_DESCN,
            ];
        }
        return $parentescos;
    }
}
