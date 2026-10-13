<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    SOLICITO COMPLEMENTAR INFORMACIÓN DE PENSIÓN DE ALIMENTOS DE LA SIGUIENTE PERSONA INTEGRANTE TITULAR DEL DATO:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                    <tr>
                        <th class="bg-custom" scope="col">Nº</th>
                        <th class="bg-custom" scope="col">RUN</th>
                        <th class="bg-custom" scope="col">Nombres</th>
                        <th class="bg-custom" scope="col">Primer Apellido</th>
                        <th class="bg-custom" scope="col">Segundo Apellido</th>
                        <th class="bg-custom" scope="col">Edad</th>
                        <th class="bg-custom" scope="col">Firma mayores de 18 años</th>

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
                            <td class="text-center">{{App\Utiles::capitalizaTexto($persona->PER_EDAD)}}</td>
                                <th>
                                    @if($portal == 'ciudadano' && $persona->PER_EDAD >=\App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD
                                        && $tramite->solicitud->RUN_CIUDADANO == $persona->PER_RUN)
                                        Firmado Digitalmente
                                        @elseif($persona->PER_EDAD < \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                        No Aplica
                                    @endif
                                </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    ADJUNTAR PODER NOTARIAL DISPONIBLE EN EL SITIO WEB DEL RSH PARA LOS CASOS DE REPRESENTACIÓN DE LA PERSONA TITULAR DEL DATO:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                    <tr>
                        <th scope="col" width="35%"> Nombre Completo Persona Titular del Dato:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="col">Nombre Representante:</th>
                        <td></td>
                    </tr>
                    @if($tramite->solicitud->ID_SUB_TIPO_SOLICITUD !=\App\Constantes\Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_3_CESE)
                        <tr>
                            <th scope="col">Firma Representante:</th>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @if($tramite->solicitud->ID_TIPO_SOLICITUD==\App\Constantes\Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS)
        @if($tramite->solicitud->ID_SUB_TIPO_SOLICITUD==\App\Constantes\Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_3_CESE)
            <section>
                <div class="p-1 m-1">
                    <p class="mb-0">**El cese se realiza solo si previamente realizó un complemento por pago o recepción de pensión de alimentos.</p>
                </div>
            </section>
        @elseif($tramite->solicitud->ID_SUB_TIPO_SOLICITUD==\App\Constantes\Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_4_VARIACION)
            <section>
                <div class="p-1 m-1">
                    <p class="mb-0">**La variación se realiza solo si previamente realizó un complemento por pago o recepción de pensión de alimentos.</p>
                </div>
            </section>
        @endif
    @endif

    <!-- end Form according to request  -->
</main><!-- end Main -->
