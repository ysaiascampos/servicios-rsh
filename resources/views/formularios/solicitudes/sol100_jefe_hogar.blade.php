<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    SOLICITO Y AUTORIZO QUE LA SIGUIENTE PERSONA INTEGRANTE INDIVIDUALIZADA SEA LA NUEVA JEFATURA DE
                    HOGAR DE MI REGISTRO SOCIAL
                    DE HOGARES, QUIEN AUTORIZA ESTA SOLICITUD:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th scope="bg-custom">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Firma nueva Jefatura de Hogar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php($dato = 0)
                            @foreach ($tramite->beneficiariosSolicitud as $key => $beneficiario)
                                @if ($beneficiario->BNFGRUPFAMI_PARENTESCO == \App\Constantes\Constante::PARENTESCO_1_JEFE_DE_HOGAR)
                                    <td>{{ $dato + 1 }}</td>
                                    <td>{{ \App\Utiles::ponePuntosAlRun($beneficiario->PER_RUN, $beneficiario->PER_DV) }}
                                    </td>
                                    <td>{{ $beneficiario->PER_NOMBRES }}</td>
                                    <td>{{ $beneficiario->PER_APELLIDOPATERNO }}</td>
                                    <td>{{ $beneficiario->PER_APELLIDOMATERNO }}</td>
                                    <th></th>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section>
        <div class="p-1 m-1">
            <p class="mb-0"> Este formulario se utiliza exclusivamente cuando la Jefatura de Hogar asigna a otra
                persona integrante como nueva Jefatura de Hogar.</p>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
