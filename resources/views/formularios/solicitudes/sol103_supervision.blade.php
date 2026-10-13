<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1>
                <h5 class="card-title">Información de su domicilio</h5>
                <table class="table card-table">
                    <tbody>
                        <tr>
                            <td>Calle</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_N_CALLE }}</th>
                            <td>Número</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMDOMICILIO }}</th>
                        </tr>
                        <tr>
                            <td>Depto</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_DPTO }}</th>
                            <td>Block</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_BLOCK }}</th>
                        </tr>
                        <tr>
                            <td>Casa</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_CASA }}</th>
                            <td>Comuna</td>
                            <th>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->COMUNA))
                                    {{ $tramite->grupoFamiliarSolicitudActual->COMUNA->DESCRIPCION }}
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <td>Agrupación habitacional</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_AGRUPACION_HABITACIONAL }}</th>
                            <td>Unidad vecinal</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_UNIDAD_VECINAL }}</th>
                        </tr>
                        <tr>
                            <td>Km / Sitio</td>
                            <th>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_SITIO }}</th>
                            <td>Ciudad / Localidad</td>
                            <th>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->LOCALIDAD))
                                    {{ $tramite->grupoFamiliarSolicitudActual->LOCALIDAD->NOMBRE }}
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <td>Referencia</td>
                            <th colspan="3">
                                {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_REFERENCIA }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section>
        <div class="card bg-light">
            {{--            <h5 class="card-title">En mi condición de Informante del Registro Social de Hogares, declaro:</h5> --}}
            <div class="card-body p-1 m-1">
                <div class="text-justify">
                    Que producto de entrevista con Supervisor(a) del Ministerio de Desarrollo Social y Familia,
                    se ha detectado la desactualización de los datos de las siguientes personas, en los módulos
                    identificados con una X, por lo que se solicita su actualización de acuerdo a la
                    información entregada:
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h5 class="card-title">Datos de personas del hogar
                    {{ $this->grupoFamiliarSolicitudActual->MODULO_VIVIENDA_MODIFICADO ?? '(Existen cambios en el Módulo de Vivienda)' }}
                </h5>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombre Completo</th>
                            <th class="bg-custom" scope="col">Educación</th>
                            <th class="bg-custom" scope="col">Salud</th>
                            <th class="bg-custom" scope="col">Ocupación e Ingresos</th>
                            <th class="bg-custom" scope="col">Edad</th>
                            <th class="bg-custom" scope="col">Firma mayores de 18 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->beneficiariosSolicitudActual as $key => $beneficiario)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($beneficiario->PER_RUN, $beneficiario->PER_DV) }}
                                </td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_NOMBRES) }}
                                    {{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOPATERNO) }}
                                    {{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ $beneficiario->MODULOS_MODIFICADOS['educacion'] ? 'X' : '' }}</td>
                                <td>{{ $beneficiario->MODULOS_MODIFICADOS['salud'] ? 'X' : '' }}</td>
                                <td>{{ $beneficiario->MODULOS_MODIFICADOS['ocupacion_ingreso'] ? 'X' : '' }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_EDAD) }}</td>
                                <th>
                                    @if (
                                        $tramite->solicitud->FIRMA_SIMPLE &&
                                            $portal == 'ciudadano' &&
                                            $beneficiario->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
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
                <h5 class="card-title">Solicito y autorizo:</h5>
                <h6 class="card-title">La incorporación de los siguientes integrantes a mi Registro Social de
                    Hogares:</h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombre Completo</th>
                            <th class="bg-custom" scope="col">Edad</th>
                            <th class="bg-custom" scope="col">Parentesco</th>
                            <th class="bg-custom" scope="col">Firma mayores de 18 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->personasAsociadas as $key => $persona)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($persona->PER_RUN, $persona->PER_DV) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_NOMBRES) }}
                                    {{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOPATERNO) }}
                                    {{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_EDAD) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->NOMBRE_PARENTESCO) }}</td>
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
    <!-- end Form according to request  -->
</main><!-- end Main -->
