<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
</head>
<body style="padding: 30px;background: linear-gradient(90deg, rgba(233, 74, 80, 1) 0%, rgba(67, 47, 136, 1) 100%) !important;">
    <div class="center-div container" style="margin: 0 auto;max-width: 90%;height: auto;background-color: #ffffff;border-radius: 5px;">.
        <div>
            <center>
                <img style=" display: block;margin-right: auto;margin-left: auto;font-size: 16px;" src="{{$message->embed(public_path().'/assets/img/logo-rsh-mail.png')}}" width="100px">
            </center>
        </div>
        <div class="container-fluid" style="padding: 30px;font-family: Calibri, sans-serif;text-align: justify !important;font-size: 16px;line-height: 1.5;">
            <p>Estimado(a): {{$primernombrepersona}} {{$persona->PER_APELLIDOPATERNO}} {{$persona->PER_APELLIDOMATERNO}}</p>
            <p>
                Junto con saludar, le informamos que su solicitud N°{{ $id_solicitud_padre }} aprobada ha sido revisada y <b>validada</b> por el Ministerio de Desarrollo Social y Familia.  Lo anterior, le permite obtener la credencial digital de persona cuidadora, la que adjuntamos a este correo y que le permitirá acceder a la atención preferente en las siguientes instituciones: FONASA, ChileAtiende, BancoEstado, MINVU (Oficinas Serviu), Registro Civil, SENAMA, SENADIS, Correos de Chile, Oficinas del Registro Social de Hogares.
            </p>
            <p>
                Gradualmente se irán sumando nuevas instituciones a la entrega de este servicio, las que estaremos informando por medio de nuestros canales de comunicación.
            </p>
            <p>
                Para mayor información, puedes acceder a <a href="http://wwww.chilecuida.cl">www.chilecuida.cl</a> o llamar de manera gratuita al Call Center 800 104 777.
                <br />
                Saludos Cordiales,
            </p>
            <p>Atentamente.</p>
            <br/>
            <table cellspacing="0" border="0">
                <tr>
                    <td rowspan="3" width="150px"><img src="{{$message->embed(public_path().'/assets/img/logo.jpg')}}" width="150px" height="auto"/></td>
                    <td height="40px" width="100%" align="left" style="padding-left:15px">Ministerio de Desarrollo Social y Familia</td>
                </tr>
                <tr>
                    <td height="40px" width="100%" align="left" style="padding-left:15px">Call Center: 800 104 777</td>
                </tr>
                <tr>
                    <td height="40px" width="100%" align="left" style="padding-left:15px">Sitio WEB: <a href="http://www.registrosocial.cl">www.registrosocial.cl</a></td>
                </tr>
            </table>
            <br/><br/>
            <small style=" display: block;margin-right: auto;margin-left: auto;font-size: 16px;text-align: center">
                NOTA: Le pedimos no responder este correo que es solo de carácter informativo.
            </small>
        </div>
    </div>
</body>
</html>

