<?php

namespace App\Servicios;

use App\Http\ResponseMappers\DescargaArchivo;
use Illuminate\Support\Facades\Storage;


class DatosDucumentosDj
{
    private $documento;
    private $lista = [
        1 => 'DJ_N7_Complemento_por_Cuidados',
        2 => 'DJ_N8_Complemento_por_Cuidados',
        3 => 'DJ_N9_Complemento_por_Cuidados',
        4 => 'DJ_N10_Complemento_por_Cuidados'];
    public function __construct($documento){
        $this->documento = $documento;
    }
    public function show()
    {
        $nombre = $this->lista[$this->documento].'.pdf';
        $archivo = storage_path().'/antecedentes/documentodj/'.$nombre;

        if(file_exists($archivo) && array_key_exists($this->documento,$this->lista)){
            return DescargaArchivo::desdeRuta($archivo, $nombre);
        }
    }
}
