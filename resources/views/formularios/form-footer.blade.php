<!-- Footer -->
<footer>
    <div class="card sign-form p-1 m-1">
        <div class="card-body p-1 m-1">
            @if ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA)
                <h5 class="card-title small text-justify">En mi condición de adulto responsable y con los antecedentes
                    aportados
                    solicito el
                    Ingreso al Registro Social de Hogares, según corresponda.</h5>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación requerida de todos los integrantes que corresponda,
                    los que serán cargados digitalmente en la plataforma al momento de digitar la solicitud.
                </p>
                <p class="card-text text-justify small">Asimismo declaro que los/las NNA incorporados/as se encuentran
                    bajo
                    mi cuidado personal, lo que se acredita con los documentos requeridos según protocolo vigente.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA)
                <h5 class="card-title small text-justify"><strong>En mi condición
                        de @if ($tramite->solicitud->MIGRANTE == 1)
                            adulto responsable
                        @else
                            integrante del Hogar
                        @endif N° {{ $tramite->numeroRegistro }} del Registro Social de
                        Hogares, solicito actualizar la información de cambio de domiclio y vivienda de acuerdo a la
                        información consignada en el sitio web del Registro
                        Social de Hogares.</strong></h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según
                        corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE)
                <h5 class="card-title small text-justify"><strong>En mi condición
                        de @if ($tramite->solicitud->MIGRANTE == 1)
                            adulto responsable
                        @else
                            integrante del Hogar
                        @endif N° {{ $tramite->numeroRegistro }} del Registro Social de
                        Hogares, solicito actualizar la información de composición
                        familiar de acuerdo a la información consignada en el
                        sitio web del Registro Social de Hogares.</strong></h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según
                        corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Asimismo declaro que los NNA incorporados se encuentran bajo el cuidado personal de uno o más
                    integrantes del hogar lo que se acredita con los
                    documentos requeridos en el protocolo vigente.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD)
                <h5 class="card-title small text-justify">En mi condición de @if ($tramite->solicitud->MIGRANTE == 1)
                        adulto responsable del integrante del Registro Social de Hogares
                    @else
                        titular/representante del titular del dato perteneciente al Registro Social de Hogares
                    @endif Nº {{ $tramite->numeroRegistro }}, solicito actualizar la información de
                    Salud de
                    acuerdo a la información consignada en el sitio web del
                    Registro Social de Hogares.
                </h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según
                        corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION)
                <h5 class="card-title small text-justify">En mi condición de @if ($tramite->solicitud->MIGRANTE == 1)
                        adulto responsable del integrante del
                    @else
                        titular/representante del titular del dato perteneciente al
                    @endif Registro Social de Hogares Nº
                    {{ $tramite->numeroRegistro }}, solicito actualizar la información
                    de Educación de acuerdo a la información consignada en el
                    sitio web del Registro Social de Hogares.</h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS)
                <h5 class="card-title small text-justify">En mi condición de @if ($tramite->solicitud->MIGRANTE == 1)
                        adulto responsable del integrante del
                    @else
                        titular/representante del titular del dato perteneciente al
                    @endif Registro Social de Hogares Nº
                    {{ $tramite->numeroRegistro }}, solicito actualizar la información
                    de ocupación e Ingresos de acuerdo a la información consignada en el
                    sitio web del Registro Social de Hogares.</h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según
                        corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA)
                <h5 class="card-title small text-justify">En mi condición de @if ($tramite->solicitud->MIGRANTE == 1)
                        adulto responsable
                    @else
                        integrante del Hogar
                    @endif del Registro Social de Hogares Nº
                    {{ $tramite->numeroRegistro }}, solicito actualizar la información del Módulo de vivienda
                    de acuerdo a la información consignada en el sitio web del
                    Registro Social de Hogares.</h5>

                <p class="card-text text-justify small"><b>Con los antecedentes aportados solicito la actualización del
                        Registro Social de Hogares, según
                        corresponda.</b></p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA)
                <h5 class="card-title small text-justify"><strong>En mi condición de titular del dato (o representante
                        del titular),
                        perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }}, solicito
                        actualizar la información de
                        @switch($tramite->solicitud->ID_TIPO_SOLICITUD)
                            @case(App\Constantes\Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE)
                                ingresos trabajador dependiente de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE)
                                ingresos trabajador Independiente de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE)
                                ingresos trabajador mixto de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION)
                                ingresos de pensión vejez y/o invalidez de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL)
                                ingresos de capital de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL)
                                matrícula en establecimiento educacional de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ)
                                propiedad de bien raíz de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL)
                                propiedad de vehículo terrestre/marítimo de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR)
                                educación superior de
                                @break

                            @case(App\Constantes\Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA)
                                participación en empresa o sociedad de
                                @break

                            @default
                        @endswitch acuerdo a la información consignada en
                        el sitio web del Registro Social de Hogares.</strong></h5>

                <p class="card-title small text-justify">Con los antecedentes aportados solicito la actualización del
                    Registro Social de Hogares según corresponda.</p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD)
                <h5 class="card-title small text-justify">En mi condición de titular del dato (o representante del
                    titular), perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }},
                    solicito actualizar la información
                    de cotización de salud de acuerdo a la información consignada en el sitio web del Registro Social de
                    Hogares.</h5>

                <p class="card-title small text-justify">Con los antecedentes aportados solicito la actualización del
                    Registro Social de Hogares, según corresponda.</p>

                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD)
                <h5 class="card-title small text-justify">En mi condición de titular/representante del titular del dato
                    perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }} , solicito
                    complementar
                    la información de cotización de salud por preexistencia de acuerdo a la información consignada en
                    el sitio
                    web del Registro Social de Hogares.</h5>
                <h5 class="card-title small text-justify">Con los antecedentes aportados solicito complementar
                    información del Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE ||
                    $tramite->solicitud->ID_TIPO_SOLICITUD ==
                        App\Constantes\Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR)
                <h5 class="card-title small text-justify">En mi condición de integrante del Hogar Nº
                    {{ $tramite->numeroRegistro }}
                    del Registro Social de Hogares, solicito actualizar la información de composición
                    familiar de acuerdo a la información consignada en el sitio
                    web del Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify">Con los antecedentes aportados solicito la actualización del
                    Registro Social de Hogares, según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR)
                <h5 class="card-title small text-justify">En mi condición de Jefatura de Hogar del Folio Nº
                    {{ $tramite->numeroRegistro }} del Registro Social de
                    Hogares, solicito actualizar la información de la Jefatura
                    de Hogar de acuerdo a la información consignada en el sitio
                    web del Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify">Con los antecedentes aportados solicito la actualización del
                    Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ)
                <h5 class="card-title small text-justify">En mi condición de titular del dato (o representante del
                    titular), perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }} ,
                    solicito complementar la información de Propiedad en comunidad/bien raíz de acuerdo a la información
                    consignada en el sitio web
                    del
                    Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify">Con los antecedentes aportados solicito complementar el
                    Registro Social de Hogares, según corresponda.</h5>
                <p class="card-text text-justify small">Se acompaña a esta solicitud la documentación establecida en los
                    respectivos protocolos, los que serán cargados digitalmente en la plataforma al momento de gestionar
                    la solicitud.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL)
                <h5 class="card-title small text-justify">En mi condición de titular del dato (o representante del
                    titular), perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }},
                    solicito complementar la información de Pago de arancel de establecimiento educacional de acuerdo a
                    la información consignada
                    en el sitio web del Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify"> Con los antecedentes aportados solicito complementar la
                    información del Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS)
                <h5 class="card-title small text-justify">En mi condición de titular/representante del titular del dato
                    perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }}, solicito
                    complementar la información
                    de Pensión de alimentos de acuerdo a la información consignada en el
                    sitio web del Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify"> Con los antecedentes aportados solicito complementar
                    información del Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA)
                <h5 class="card-title small text-justify"> En mi condición de titular/representante del titular del dato
                    perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }}, solicito
                    complementar
                    la información de Compensación económica de acuerdo a la información consignada en el sitio web del
                    Registro Social de Hogares.
                </h5>
                <h5 class="card-title small text-justify"> Con los antecedentes aportados solicito complementar
                    información del Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES)
                <h5 class="card-title small text-justify">
                    En mi condición de persona cuidadora y/o persona que requiere cuidados y/o Jefatura de Hogar del
                    Registro Social de Hogares, solicito complementar
                    la información de cuidados de acuerdo a la información consignada en el sitio web del Registro
                    Social de
                    Hogares.
                </h5>
                <h5 class="card-title small text-justify">Con los antecedentes aportados solicito complementar
                    información del Registro Social de Hogares según corresponda.</h5>
                <p class="card-text text-justify small">
                    Se acompaña a esta solicitud la documentación establecida en los respectivos protocolos, los que
                    serán cargados digitalmente en la plataforma al momento de gestionar la solicitud.
                </p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS)
                <h5 class="card-title small">En mi condición de persona solicitante, solicito el Ingreso al Registro
                    Social de Hogares del niño, niña o adolescente de acuerdo a los datos consignados
                    en el presente documento.</h5>
                <p class="card-text text-justify small">Se acompaña a esta solicitud la documentación establecida en los
                    respectivos protocolos, los que serán cargados digitalmente en la plataforma al momento de gestionar
                    la solicitud.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO)
                <h5 class="card-title small text-justify">En mi condición de titular del dato (o representante del
                    titular), perteneciente al Registro Social de Hogares Nº {{ $tramite->numeroRegistro }},
                    solicito complementar la información de Sociedad sin fines de lucro de acuerdo a
                    la información consignada
                    en el sitio web del Registro Social de Hogares.
                </h5>
                <p class="card-text text-justify small">Se acompaña a esta solicitud la documentación establecida en los
                    respectivos protocolos, los que serán cargados digitalmente en la plataforma al momento de gestionar
                    la solicitud.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @else
                <h5 class="card-title small">Con los antecedentes aportados solicito el ingreso al Registro Social de
                    Hogares
                    según corresponda.</h5>
                <p class="card-text text-justify small">Se acompaña a esta solicitud la documentación establecida en los
                    respectivos protocolos, los que serán cargados digitalmente en la plataforma al momento de gestionar
                    la solicitud.</p>
                <p class="card-text text-justify small">Asimismo declaro que los NNA incorporados se encuentran bajo el
                    cuidado
                    personal de uno o más integrantes del hogar lo que se acredita con los
                    documentos requeridos en el protocolo vigente.</p>
                <p class="card-text text-justify small">
                    Declaro que la información proporcionada es fidedigna, íntegra y correcta, y autorizo al Ministerio
                    de Desarrollo Social y Familia para que la utilice y verifique toda esta información de conformidad
                    con lo dispuesto en el Decreto Supremo N°22, de 2015, del Ministerio de Desarrollo Social y Familia
                    en la Ley N°19.628 sobre Protección de la Vida Privada y en las demás normas aplicables. Declaro que
                    la información proporcionada es fidedigna y que según la Ley N°20.379, proveer información falsa en
                    el RSH puede sancionarse con multas de hasta 20 UTM ($ 1.350.000 aproximadamente), aplicada por el
                    juez de policía local.
                </p>
            @endif
            @if (isset($tramite) && isset($portal))
                <!-- Signing -->
                <div class="row justify-content-center px-5">

                    <div class="card sign-form__item col-4">
                        @if (($tramite->solicitud->FIRMA_SIMPLE || $tramite->solicitud->CANAL == 'Clave Unica') && $portal == 'ciudadano')
                            <div class="card-body text-center">
                                @if ($tramite->solicitud->TOKEN_ACCESO == env('LOGIN_RUN_TOKEN_FALSO'))
                                    <img src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVE_RUN.png') }}" alt="firma"
                                         width="70px" class="firm">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        Firma simple ClaveRun solicitante<br>
                                    </p>
                                @else
                                    <img src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVEUNICA.png') }}"
                                         alt="firma" width="70px" class="firm">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        Firma simple ClaveÚnica solicitante <br>
                                        Código de verificación: <br>
                                        <small>{{ $tramite->solicitud->TOKEN_ACCESO }}</small>
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="card-body text-center not-signed">
                                <hr class="mb-2">
                                <p class="card-text">
                                    @if ($tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                        Firma adulto/a responsable <br>
                                    @elseif ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR)
                                        Firma Jefatura de Hogar Saliente<br>
                                    @elseif ($tramite->solicitud->ID_TIPO_SOLICITUD == App\Constantes\Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR)
                                        Firma Jefatura del Hogar<br>
                                    @else
                                        Firma persona solicitante <br>
                                    @endif

                                </p>
                            </div>
                        @endif
                    </div>

                    @if($tramite->tipoSolicitud == App\Constantes\Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE  &&
                        $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_2_NOMBRE_CLAVE_UNICA && $portal == 'ciudadano')
                        @if($tramite->solicitud->FIRMA_SIMPLE)
                            @if(count($tramite->personasAsociadas) == 1)
                                @if($tramite->personasAsociadas[0]['PER_RUN'] != $tramite->solicitante->PER_RUN)
                                    <div class="card sign-form__item col-4">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVE_RUN.png') }}"
                                                 alt="firma" width="70px" class="firm">
                                            <hr class="mb-2">
                                            <p class="card-text">
                                                Firma simple ClaveRun jefe/a de hogar<br>
                                                Código de verificación: <br>
                                                <small>{{ $tramite->solicitud->TOKEN_ACCESO }}</small>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="card sign-form__item col-4">
                                    <img src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVE_RUN.png') }}"
                                         alt="firma" width="70px" class="firm">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        Firma simple ClaveRun jefe/a de hogar<br>
                                        Código de verificación: <br>
                                        <small>{{ $tramite->solicitud->TOKEN_ACCESO }}</small>
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="card sign-form__item col-4">
                                <div class="card-body text-center not-signed">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        Firma Jefatura de Hogar <br>
                                    </p>
                                </div>
                            </div>
                        @endif

                    @elseif ($tramite->solicitud->MIGRANTE != App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                        @php
                            $ocultarFirmaJefeHogarMunicipal = false;
                            $esTipo55 = ($tramite->tipoSolicitud == \App\Constantes\Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES || 
                                         (isset($tramite->solicitud->ID_TIPO_SOLICITUD) && $tramite->solicitud->ID_TIPO_SOLICITUD == \App\Constantes\Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES));

                            if ($portal == 'municipal' && $esTipo55) {
                                $subTipoCuidadores = isset($tramite->solicitud->ID_SUB_TIPO_SOLICITUD) ? $tramite->solicitud->ID_SUB_TIPO_SOLICITUD : null;
                                if (empty($subTipoCuidadores) && isset($tramite->subTipo)) {
                                    $subTipoCuidadores = $tramite->subTipo;
                                }
                                if (empty($subTipoCuidadores) && isset($tramite->registroCuidador[0]->C_TCOMPLEMENTO_CUIDADORES)) {
                                    $subTipoCuidadores = $tramite->registroCuidador[0]->C_TCOMPLEMENTO_CUIDADORES;
                                }

                                if ($subTipoCuidadores == \App\Constantes\Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE || $subTipoCuidadores == 3) {
                                    $cuidadorRun = isset($tramite->cuidador->PER_RUN) ? $tramite->cuidador->PER_RUN : (isset($tramite->registroCuidador[0]->RUT_CUIDADOR) ? $tramite->registroCuidador[0]->RUT_CUIDADOR : null);
                                    $cuidadoRun = isset($tramite->cuidado->PER_RUN) ? $tramite->cuidado->PER_RUN : (isset($tramite->registroCuidador[0]->RUT_CUIDADO) ? $tramite->registroCuidador[0]->RUT_CUIDADO : null);
                                    $solicitanteRun = isset($tramite->solicitante->PER_RUN) ? $tramite->solicitante->PER_RUN : (isset($tramite->solicitud->RUN_CIUDADANO) ? $tramite->solicitud->RUN_CIUDADANO : null);

                                    $mismoHogarCuidadorCuidado = false;
                                    if ($cuidadorRun && $cuidadoRun) {
                                        if ($cuidadorRun == $cuidadoRun) {
                                            $mismoHogarCuidadorCuidado = true;
                                        } else {
                                            $grupoCuidador = isset($tramite->cuidador->GRUPOFAMILIAR) ? $tramite->cuidador->GRUPOFAMILIAR : null;
                                            $grupoCuidado = isset($tramite->cuidado->GRUPOFAMILIAR) ? $tramite->cuidado->GRUPOFAMILIAR : null;

                                            if (!empty($grupoCuidador) && !empty($grupoCuidado)) {
                                                $mismoHogarCuidadorCuidado = ($grupoCuidador == $grupoCuidado);
                                            } else {
                                                $checkHogar = \App\Utiles::verificaRunesTienenMismoGrupoFamiliar($cuidadorRun, $cuidadoRun);
                                                if ($checkHogar && isset($checkHogar->ESTADO) && $checkHogar->ESTADO == '1') {
                                                    $mismoHogarCuidadorCuidado = true;
                                                } else {
                                                    $mismoHogarCuidadorCuidado = false;
                                                }
                                            }
                                        }
                                    }

                                    $solicitanteEnHogarCuidado = false;
                                    if ($solicitanteRun && $cuidadoRun) {
                                        if ($solicitanteRun == $cuidadoRun) {
                                            $solicitanteEnHogarCuidado = true;
                                        } else {
                                            $grupoSolicitante = isset($tramite->solicitante->GRUPOFAMILIAR) ? $tramite->solicitante->GRUPOFAMILIAR : null;
                                            $grupoCuidado = isset($tramite->cuidado->GRUPOFAMILIAR) ? $tramite->cuidado->GRUPOFAMILIAR : null;

                                            if (!empty($grupoSolicitante) && !empty($grupoCuidado)) {
                                                $solicitanteEnHogarCuidado = ($grupoSolicitante == $grupoCuidado);
                                            } else {
                                                $checkHogarSolicitante = \App\Utiles::verificaRunesTienenMismoGrupoFamiliar($solicitanteRun, $cuidadoRun);
                                                if ($checkHogarSolicitante && isset($checkHogarSolicitante->ESTADO) && $checkHogarSolicitante->ESTADO == '1') {
                                                    $solicitanteEnHogarCuidado = true;
                                                } else {
                                                    $solicitanteEnHogarCuidado = false;
                                                }
                                            }
                                        }
                                    }

                                    if (!$mismoHogarCuidadorCuidado && !$solicitanteEnHogarCuidado) {
                                        $ocultarFirmaJefeHogarMunicipal = true;
                                    }
                                }
                            }
                        @endphp
                        @if (!empty($tramite->firmaJefeHogar) && $tramite->tipoSolicitud != App\Constantes\Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE && !$ocultarFirmaJefeHogarMunicipal)
                            <div class="card sign-form__item col-4">
                                @if (
                                    ($tramite->solicitud->FIRMA_SIMPLE ||
                                        $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_2_NOMBRE_CLAVE_UNICA) &&
                                        $portal == 'ciudadano')
                                    <div class="card-body text-center">
                                        @if ($tramite->solicitud->TOKEN_ACCESO == env('LOGIN_RUN_TOKEN_FALSO'))
                                            <img src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVE_RUN.png') }}"
                                                 alt="firma" width="70px" class="firm">
                                            <hr class="mb-2">
                                            <p class="card-text">
                                                Firma simple ClaveRun jefe/a de hogar<br>
                                            </p>
                                        @else
                                            @if((in_array($tramite->tipoSolicitud,
                                                [
                                                     App\Constantes\Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                                                     App\Constantes\Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE
                                                ])) &&
                                                (isset($tramite->parentesco[0]->PER_RUN) &&  $tramite->parentesco[0]->PER_RUN!=$tramite->solicitante->PER_RUN))
                                                <div class="card-body text-center not-signed">
                                                    <hr class="mb-2">
                                                    <p class="card-text">
                                                        Firma Jefatura de Hogar <br>
                                                    </p>
                                                </div>
                                            @else
                                                @if ($tramite->solicitud->ID_TIPO_SOLICITUD == \App\Constantes\Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES)
                                                    @if(($tramite->cuidador['GRUPOFAMILIAR']==$tramite->cuidado['GRUPOFAMILIAR']) && ($tramite->solicitud->FIRMA_SIMPLE==true))
                                                        <img
                                                            src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVEUNICA.png') }}"
                                                            alt="firma" width="70px" class="firm">
                                                        <hr class="mb-2">
                                                        <p class="card-text">
                                                            Firma simple ClaveÚnica jefe de hogar de la persona que
                                                            recibe
                                                            cuidados<br>
                                                            Código de verificación: <br>
                                                            <small>{{ $tramite->solicitud->TOKEN_ACCESO }}</small>
                                                        </p>
                                                    @else
                                                        <div class="card-body text-center not-signed">
                                                            <hr class="mb-2">
                                                            <p class="card-text">
                                                                Firma Jefatura de Hogar de quien requiere cuidados
                                                            </p>
                                                        </div>
                                                    @endif
                                                @else
                                                    <img
                                                        src="{{ asset('assets/img/FIRMA_ELECTRONICA_CLAVEUNICA.png') }}"
                                                        alt="firma" width="70px" class="firm">
                                                    <hr class="mb-2">
                                                    <p class="card-text">
                                                        Firma Jefatura de Hogar <br>
                                                        Código de verificación: <br>
                                                        <small>{{ $tramite->solicitud->TOKEN_ACCESO }}</small>
                                                    </p>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                @elseif($tramite->grupoFamiliarVigente['GRUPFAMI_INSTITUCIONALIZADO'] != 0)
                                    <div class="card-body text-center not-signed">
                                        <hr class="mb-2">
                                        <p class="card-text">
                                            Firma Director/a del Centro <br>
                                        </p>
                                    </div>
                                @else
                                    @if ($tramite->solicitud->ID_TIPO_SOLICITUD == \App\Constantes\Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES)
                                        <div class="card-body text-center not-signed">
                                            <hr class="mb-2">
                                            <p class="card-text">
                                                Firma Jefatura de Hogar de quien requiere cuidados
                                            </p>
                                        </div>
                                    @else
                                        <div class="card-body text-center not-signed">
                                            <hr class="mb-2">
                                            <p class="card-text">
                                                Firma Jefatura de Hogar<br>
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    @endif

                    @if (
                        ($tramite->solicitud->TOKEN_ACCESO == env('LOGIN_RUN_TOKEN_FALSO') ||
                            $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_1_NOMBRE_MUNICIPAL ||
                            $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_4_NOMBRE_CHILE_ATIENDE_EJECUTOR) &&
                            $tramite->solicitud->CANAL != \App\Constantes\Constante::CANAL_2_NOMBRE_CLAVE_UNICA &&
                            $tramite->solicitud->ID_TIPO_SOLICITUD == \App\Constantes\Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE)
                        @if(count($tramite->personasAsociadas) == 1)
                            @if($tramite->personasAsociadas[0]['PER_RUN'] != $tramite->solicitante->PER_RUN)
                                <div class="card sign-form__item col-4">
                                    <div class="card-body text-center not-signed">
                                        <hr class="mb-2">
                                        <p class="card-text">
                                            Firma Jefatura de Hogar<br>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="card sign-form__item col-4">
                                <div class="card-body text-center not-signed">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        Firma Jefatura de Hogar<br>
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if (!empty($tramite->otrasFirmas))
                        @foreach ($tramite->otrasFirmas as $firmante)
                            <div class="card sign-form__item col-4">
                                <div class="card-body text-center not-signed">
                                    <hr class="mb-2">
                                    <p class="card-text">
                                        {{ $firmante }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if (
                        ($tramite->solicitud->TOKEN_ACCESO == env('LOGIN_RUN_TOKEN_FALSO') ||
                            $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_1_NOMBRE_MUNICIPAL ||
                            $tramite->solicitud->CANAL == \App\Constantes\Constante::CANAL_4_NOMBRE_CHILE_ATIENDE_EJECUTOR) &&
                            $tramite->solicitud->CANAL != \App\Constantes\Constante::CANAL_2_NOMBRE_CLAVE_UNICA)
                        <div class="card sign-form__item col-4">
                            <div class="card-body text-center not-signed">
                                <hr class="mb-2">
                                <p class="card-text">
                                    Nombre y Firma persona funcionaria responsable
                                </p>
                            </div>
                        </div>
                    @endif

                    {{--                <p class="source-form">Origen: <span>{{ \App\Utiles::evaluarOrigen($tramite->solicitud) }}</span></p> --}}
                    @endif
                </div>
        </div>
</footer><!-- end Footer -->
{{-- <img id="logo-footer" src="{{ asset('assets/img/mds-logo-compacto.png') }}" alt="mds-logo-compacto" width="120px" --}}
{{--    class="p-1 m-1"> --}}
