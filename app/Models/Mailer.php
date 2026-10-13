<?php

require_once 'classDb.php';

class Mailer
{
    public $db;
    public $error;
    public $email;
    public $para;
    public $asunto;
    public $mensaje;
    public $cabeceras;
    public $cc;

    function __construct()
    {
        $this->db = DB::getInstance();
    }

    function enviarMail()
    {
        if (mail($this->para, $this->asunto, $this->mensaje, $this->cabeceras)) {
            return true;
        } else {
            return false;
        }
    }

    function enviarMail2($id)
    {
        /*if (mail($this->para, $this->asunto, utf8_decode($this->mensaje), $this->cabeceras)){
            return true;
        }else{
            return false;
        }
        */

        require("class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->Host = "localhost";

        $mail->From = "sociedadcivil@desarrollosocial.cl";
        $mail->FromName = "Sociedad Civil";
        $mail->Subject = "Concurso Chile de Todas y Todos 2015";
        $mail->AddAddress($this->para, $this->nombre);
        $mail->AddCC($this->cc, 'Sociedad Civil');


        $body = $this->mensaje;

        $mail->Body = $body;

        $ruta = '../views/org_com/certificados/' . $id . '.pdf';
        $nombre = $id . '.pdf';
        $mail->AddAttachment($ruta, $nombre);
        $mail->Send();

    }

}

?>
