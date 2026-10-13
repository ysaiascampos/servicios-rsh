<?php

namespace App\Http\ResponseMappers;

use App\Clases\Token;

class TokenMapper extends ResponseMapper
{
    public function __construct(Token $token)
    {
        $this->campos = [
            'token' => $token->contenido
        ];
    }
}
