<?php

namespace App\Http\Controllers;

use App\Clases\Token;
use App\Http\ResponseMappers\TokenMapper;

class AuthApiController extends Controller
{
    function getToken()
    {
        $token = new Token();
        return new TokenMapper($token);
    }
}
