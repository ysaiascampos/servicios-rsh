<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    SOLICITO COMPLEMENTAR INFORMACIÓN DE COMPENSACIÓN ECONÓMICA DE LA SIGUIENTE PERSONA INTEGRANTE
                    TITULAR DEL DATO:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th scope="bg-custom">Nº</th>
                            <th scope="bg-custom">RUN</th>
                            <th scope="bg-custom">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th scope="bg-custom" class="text-center">Edad</th>
                            <th scope="bg-custom">Firma mayores de 18 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->personasAsociadas as $key => $persona)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($persona->PER_RUN, $persona->PER_DV) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_NOMBRES) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOPATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOMATERNO) }}</td>
                                <td class="text-center">{{ App\Utiles::capitalizaTexto($persona->PER_EDAD) }}</td>
                                <th>
                                    @if (
                                        $tramite->solicitud->FIRMA_SIMPLE &&
                                            $portal == 'ciudadano' &&
                                            $persona->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                        Firmado Digitalmente
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
                    ADJUNTAR PODER NOTARIAL DISPONIBLE EN EL SITIO WEB DEL RSH PARA LOS CASOS DE REPRESENTACIÓN DE LA
                    PERSONA TITULAR DEL DATO:
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
                        <tr>
                            <th scope="col">Firma Representante:</th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- end Form according to request  -->
</main><!-- end Main -->
