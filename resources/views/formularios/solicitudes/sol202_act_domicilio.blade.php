<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO ACTUALIZAR LA
                    INFORMACIÓN
                    DE MI HOGAR
                    POR CAMBIO DE DOMICILIO:</h6>

                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <th id="" class="text-left mb-1">Domicilio anterior:</th>
                            <th id="" colspan="4"></th>
                        </tr>
                        <tr>
                            <td class="bg-custom">Calle:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->CALLE }}</td>
                            <td class="bg-custom">Número:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->GRUPFAMI_NUMDOMICILIO }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Depto:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->GRUPFAMI_DPTO }}</td>
                            <td class="bg-custom">Block:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->GRUPFAMI_BLOCK }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Casa:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->GRUPFAMI_CASA }}</td>
                            <td class="bg-custom">Comuna:</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarAnterior->COMUNA))
                                    {{ $tramite->grupoFamiliarAnterior->COMUNA->DESCRIPCION }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Agrupación habitacional:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->NOMBRE_AGRUPACION_HABITACIONAL }}</td>
                            <td class="bg-custom">Unidad vecinal:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->NOMBRE_UNIDAD_VECINAL }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Km / Sitio:</td>
                            <td>{{ $tramite->grupoFamiliarAnterior->GRUPFAMI_SITIO }}</td>
                            <td class="bg-custom">Ciudad / Localidad:</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarAnterior->LOCALIDAD))
                                    {{ $tramite->grupoFamiliarAnterior->LOCALIDAD->NOMBRE }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Empresa/Cooperativa eléctrica:</td>

                            <td>
                                @if($tramite->grupoFamiliarAnterior->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarAnterior->NOMBRE_COMPANIA_LUZ }}
                                @endif
                            </td>
                            <td class="bg-custom">Empresa/Cooperativa de agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarAnterior->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarAnterior->NOMBRE_COMPANIA_AGUA }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">N° Cliente de Luz:</td>
                            <td>
                                @if($tramite->grupoFamiliarAnterior->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarAnterior->GRUPFAMI_NUMCLIENTELUZ }}
                                @endif
                            </td>
                            <td class="bg-custom">N° Cliente de Agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarAnterior->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarAnterior->GRUPFAMI_NUMCLIENTEAGUA }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Referencia</td>
                            <td colspan="3">
                                {{ $tramite->grupoFamiliarAnterior->GRUPFAMI_REFERENCIA }}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="card-body p-1 m-1">

                <table class="table table-bordered table-sm mt-0 mb-0">
                    <tbody>
                        <tr>
                            <th id="" class="text-left font-weight-bold mb-1">Nuevo domicilio:
                            </th>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <td class="bg-custom">Calle:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->CALLE }}</td>
                            <td class="bg-custom">Número:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMDOMICILIO }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Depto:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_DPTO }}</td>
                            <td class="bg-custom">Block:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_BLOCK }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Casa:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_CASA }}</td>
                            <td class="bg-custom">Comuna:</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->COMUNA))
                                    {{ $tramite->grupoFamiliarSolicitudActual->COMUNA->DESCRIPCION }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Agrupación habitacional:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_AGRUPACION_HABITACIONAL }}</td>
                            <td class="bg-custom">Unidad vecinal:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_UNIDAD_VECINAL }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Km / Sitio:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_SITIO }}</td>
                            <td class="bg-custom">Ciudad / Localidad:</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->LOCALIDAD))
                                    {{ $tramite->grupoFamiliarSolicitudActual->LOCALIDAD->NOMBRE }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Empresa/Cooperativa eléctrica:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_LUZ }}
                                @endif
                            </td>
                            <td class="bg-custom">Empresa/Cooperativa de agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_AGUA }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">N° Cliente de Luz:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTELUZ }}
                                @endif
                            </td>
                            <td class="bg-custom">N° Cliente de Agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTEAGUA }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Referencia</td>
                            <td colspan="3">
                                {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_REFERENCIA }}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
