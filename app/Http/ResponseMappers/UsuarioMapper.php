<?php


namespace App\Http\ResponseMappers;


use App\Models\Usuario;
use App\Utiles;

class UsuarioMapper extends ResponseMapper
{
    public function __construct(Usuario $usuario)
    {
        $this->campos = [
            'run' => self::entero($usuario->RUT_USUARIO),
            'dv' => Utiles::dv($usuario->RUT_USUARIO),
            'nombres' => $usuario->NOMBRES_USUARIO,
            'apellido-paterno' => $usuario->APAT_USUARIO,
            'apellido-materno' => $usuario->AMAT_USUARIO,
            'perfil' => $this->mapPerfil($usuario),
        ];
    }

    public function mapPerfil(Usuario $usuario)
    {
        return [
            'id'=> self::entero($usuario->ID_PERFIL),
            'descripcion' =>$usuario->PERFIL->NOMBRE_PERFIL
        ];
    }
}
