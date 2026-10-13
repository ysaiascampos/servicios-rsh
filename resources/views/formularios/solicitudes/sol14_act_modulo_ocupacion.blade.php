<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO LA ACTUALIZACIÓN DEL
                    MÓDULO DE OCUPACIÓN E INGRESOS EN EL REGISTRO SOCIAL DE HOGARES DE:</h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Edad</th>
                            @if ($tramite->titular->PER_EDAD >= 18)
                                <th class="bg-custom" scope="col">Firma mayor de 18 años</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ 1 }}</td>
                            <td>{{ App\Utiles::ponePuntosAlRun($tramite->titular->PER_RUN, $tramite->titular->PER_DV) }}
                            </td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->titular->PER_NOMBRES) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->titular->PER_APELLIDOPATERNO) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->titular->PER_APELLIDOMATERNO) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->titular->PER_EDAD) }}</td>
                            @if ($tramite->titular->PER_EDAD >= 18)
                                <td>
                                    @if ($tramite->solicitud->MIGRANTE != App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                        @if (
                                            $tramite->solicitud->FIRMA_SIMPLE &&
                                                $portal == 'ciudadano' &&
                                                $tramite->titular->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                            Firmado Digitalmente
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
