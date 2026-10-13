<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card">
            <div class="card-body pb-1">
                <h5 class="card-title mb-4">
                    Solicitud de complemento para sociedades sin fines de lucro:
                </h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Nº</th>
                        <th scope="col">RUN</th>
                        <th scope="col">Nombres</th>
                        <th scope="col">Apellido Paterno</th>
                        <th scope="col">Apellido Materno</th>
                        <th scope="col">Firma mayores de 18 años</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tramite->personasAsociadas as $key => $persona)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{App\Utiles::ponePuntosAlRun($persona->PER_RUN, $persona->PER_DV)}}</td>
                            <td>{{App\Utiles::capitalizaTexto($persona->PER_NOMBRES)}}</td>
                            <td>{{App\Utiles::capitalizaTexto($persona->PER_APELLIDOPATERNO)}}</td>
                            <td>{{App\Utiles::capitalizaTexto($persona->PER_APELLIDOMATERNO)}}</td>
                            <th>@if($tramite->solicitud->FIRMA_SIMPLE && $portal == 'ciudadano' && $persona->PER_EDAD >=\App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD) Firmado Digitalmente @endif</th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="p-0">
                        <h6 class="card-title mb-4">
                            Para los casos de Representación del Titular del Dato:
                        </h6>
                        <h6>Nombre Titular del Dato:</h6>
                        <hr class="mb-3">
                        <h6>Representante:</h6>
                        <hr class="mb-3">
                        <h6>Firma Representante:</h6>
                        <hr class="mb-4">
                    </div>
                    <div class="p-2 rounded bg-light mb-2">
                        <h6>
                            Nota: Adjuntar Poder Notarial disponible en el Sitio Web del Registro Social de Hogares
                        </h6>
                    </div>
                    <div class="p-2 rounded text-justify bg-light">
                        En mi condición de integrante del Hogar Nº {{$tramite->solicitud->DATO_ACTUAL}} del Registro Social de Hogares, solicito el complemento de la información de la persona anteriormente individualizada
                        de acuerdo a la información completada en el Sitio Web del Registro Social de Hogares, mediante la solicitud Nº {{$tramite->solicitud->ID_PADRE_SOLICITUD}} realizada
                        con fecha {{date("d", strtotime($tramite->solicitud->FECHA_CREACION))}} de {{App\Utiles::obtenerMes(date("m", strtotime($tramite->solicitud->FECHA_CREACION)))}} de {{date("Y", strtotime($tramite->solicitud->FECHA_CREACION)) }} a las {{date("H:i:s", strtotime($tramite->solicitud->FECHA_CREACION))}}.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- end Form according to request  -->
</main><!-- end Main -->
