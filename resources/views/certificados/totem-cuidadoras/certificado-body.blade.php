<?php use App\Utiles;?>
<div class="texto p-3">
    <div class="row">
        <div class="col-12">
            <h2 class="titulo">CERTIFICADO PERSONA CUIDADORA</h2>
            <p>El Ministerio de Desarrollo Social y Familia acredita que:</p>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <p><small class="text-black-50">Nombres</small><br>
                <strong>{{Utiles::capitalizaTexto($ciudadanoExport->PER_NOMBRES)}}</strong></p>
            <p><small class="text-black-50">Apellidos</small><br>
                <strong>{{Utiles::capitalizaTexto($ciudadanoExport->PER_APELLIDOPATERNO . " " . $ciudadanoExport->PER_APELLIDOMATERNO)}}</strong>
            </p>
            <p><small
                    class="text-black-50">RUN</small><br><strong>{{Utiles::ponePuntosAlRun($ciudadanoExport->PER_RUN, $ciudadanoExport->PER_DV)}}</strong>
            </p>
            <p>Realiza labores como persona cuidadora: <strong>@if(isset($ciudadanoExport->CUIDADOR_PRINCIPAL) && $ciudadanoExport->CUIDADOR_PRINCIPAL > 0) Principal @else Secundario @endif</strong></p>
        </div>

    </div>
    <div class="row ">
        <div class="col-12">


            <div class="col-12 text-center">
                    <div class="">
                        <table style="border-style: hidden">
                            <tr style="border-style: hidden">
                            <td style="border-style: hidden">
                        @if(isset($qrcode))
                            <div class="bi bi-qr-code-scan text-muted">
                                <img src="data:image/png;base64, {!! $qrcode !!}" width="130" height="130">
                            </div>
                        @endif
                            </td>
                            <td style="border-style: hidden">
                                <div class="col-7 p-3">
                                    <p style="text-align: justify">Este código QR es de uso exclusivo de las instituciones que otorgan atención preferente para personas cuidadoras y que cuentan con la tecnología para validarlo.</p>
                                </div>

                            </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-2 pt-3"><img src="{{asset('certificados/cuidadoras/img/bajada.png')}}"
                                                              alt="" class="img-fluid"></div>
                    <div class="col-2 pt-3"><small>Santiago, <?php echo date("d-m-Y");?></small></div>
            </div>

        </div>
    </div>
</div>
