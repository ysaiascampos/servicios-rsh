<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">

</head>
<body style="
        padding: 30px;
        background: linear-gradient(90deg, rgba(233, 74, 80, 1) 0%, rgba(67, 47, 136, 1) 100%) !important;">
<div class="center-div container" style="margin: 0 auto;
           max-width: 90%;
           height: auto;
           background-color: #ffffff;
           border-radius: 5px;">.
    <div>
        <img style=" display: block;
            margin-right: auto;
            margin-left: auto;
            font-size: 16px;" src="{{$message->embed(public_path().'/assets/img/logo-rsh-mail.png')}}" width="150px">
    </div>

    <div class="container-fluid" style="padding: 30px;
            font-family: Calibri, sans-serif;
            text-align: justify !important;
            font-size: 16px;
            line-height: 1.5;">
        <p>Reciba un cordial saludo del Ministerio de Desarrollo Social y Familia.</p>

        <p>Estimado(a) Sr(a): <b>{{$personaSolicitante->PER_NOMBRES}} {{$personaSolicitante->PER_APELLIDOPATERNO}}</b>,
            por medio de este correo te informamos que <b>{{$persona->PER_NOMBRES}} {{$persona->PER_APELLIDOPATERNO}}</b>
            ha {{$textAccion}} la solicitud de {{$nombreSolicitud}} a tu Registro Social de Hogares.
        </p>
        <br/>
        <p> Para mayor información puede llamar al número 800 104 777, gratuito, de lunes a viernes de 9:00 a 19:00
            horas en
            días hábiles.
        </p>
        <p>Atentamente.</p>
        <br/>

        <table cellspacing="0" border="0">
            <tr>
                <td rowspan="3" width="150px"><img src="{{$message->embed(public_path().'/assets/img/logo.jpg')}}" width="150px" height="auto"/>
                </td>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Ministerio
                    de Desarrollo Social y Familia
                </td>
            </tr>
            <tr>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Call
                    Center: 800 104 777
                </td>
            </tr>
            <tr>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Sitio
                    WEB: <a href="http://www.registrosocial.cl">www.registrosocial.cl</a></td>
            </tr>
        </table>
        <br/><br/>
        <small style=" display: block;
            margin-right: auto;
            margin-left: auto;
            font-size: 16px;
            text-align: center">
            NOTA: Le pedimos no responder este correo que es solo de
            carácter informativo.</small>
    </div>
</div>

</body>
</html>
