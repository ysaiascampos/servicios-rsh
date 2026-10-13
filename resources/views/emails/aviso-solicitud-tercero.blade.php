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
           border-radius: 5px;">
    <div>
        <!--
        <img style=" display: block;
            margin-right: auto;
            margin-left: auto;
            font-size: 16px;" src="{{$message->embed(public_path().'/assets/img/logo-rsh-mail.png')}}" width="150px">
        -->
    </div>

    <div class="container-fluid" style="padding: 30px;
            font-family: Calibri, sans-serif;
            text-align: justify !important;
            font-size: 16px;
            line-height: 1.5;">
        <p>Reciba un cordial saludo del Ministerio de Desarrollo Social y Familia.</p>
        <p>Estimado(a) Sr(a): <b>{{$persona->PER_NOMBRES}} {{$persona->PER_APELLIDOPATERNO}}</b>, tienes una solicitud de {{ $solicitud->TipoSolicitud->TIPO_SOLICITUD }} realizada por <b>{{$personaSolicitante->PER_NOMBRES}} {{$personaSolicitante->PER_APELLIDOPATERNO}}</b>, la cual deberás aprobar o rechazar en la plataforma.</p>
        <p>Para este procedimiento debes ingresar a ventanillaunicasocial.gob.cl con tu ClaveÚnica y dirigirte al Registro Social de Hogares" donde encontrarás un recuadro que te notifica del tipo de solicitud. Haz clic en el enlace y accederás a los botones que te permiten ver, aprobar o rechazar la solicitud.</p>
        <p>Si apruebas la solicitud, se verá reflejada en tu Registro Social de Hogares. Si la rechazas, no habrá cambios.</p>
        <p>Si no respondes la solicitud en 30 días, quedará en estado de desistida.</p>
        <p> Para mayor información puedes llamar sin costo al número 800 104 777 de lunes a viernes de 9:00 a 19:00 horas en días hábiles.</p>
        <p>Atentamente.</p>
        <br/>
        <table cellspacing="0" border="0">
            <tr>
                <td rowspan="3" width="150px"><img src="{{$message->embed(public_path().'/assets/img/logo.jpg')}}" width="150px" height="auto"/>
                </td>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Ministerio de Desarrollo Social y Familia
                </td>
            </tr>
            <tr>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Call Center: 800 104 777
                </td>
            </tr>
            <tr>
                <td height="40px" width="100%" align="left" style="padding-left:15px">Sitio WEB: <a href="http://www.ventanillaunicasocial.cl">www.ventanillaunicasocial.cl</a></td>
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
