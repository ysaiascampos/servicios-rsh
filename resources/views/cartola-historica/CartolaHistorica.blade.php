<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cartola Histórica RSH</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #2c2c2c;
            font-size: 10px;
            line-height: 1.5;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 0 35px;
        }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            padding: 20px 0 15px;
            border-bottom: 3px solid #004A80;
            margin-bottom: 18px;
        }

        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 110px;
        }

        .header-logo img {
            width: 100px;
            height: 100px;
        }

        .header-titles {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }

        .header-titles .ministerio {
            font-size: 9px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .header-titles h1 {
            font-size: 15px;
            font-weight: 700;
            color: #004A80;
            line-height: 1.3;
        }

        .header-titles .subtitulo {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        /* ── FICHA DATOS ── */
        .ficha {
            background-color: #f4f7fb;
            border-left: 4px solid #004A80;
            border-radius: 0 6px 6px 0;
            padding: 12px 18px;
            margin-bottom: 20px;
        }

        .ficha-grid {
            display: table;
            width: 100%;
        }

        .ficha-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 2px 0;
        }

        .ficha-item {
            margin-bottom: 5px;
        }

        .ficha-label {
            font-weight: 700;
            color: #004A80;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .ficha-valor {
            font-size: 10px;
            color: #2c2c2c;
            margin-top: 1px;
        }

        /* ── TABLA ── */
        .table-wrapper {
            margin-bottom: 25px;
            border: 1px solid #c8d8e8;
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        thead tr:first-child th {
            background-color: #004A80;
            color: #ffffff;
            font-weight: 600;
            padding: 9px 8px;
            text-align: center;
            font-size: 9px;
            letter-spacing: 0.2px;
            border-right: 1px solid #1a5f99;
        }

        thead tr:first-child th:last-child {
            border-right: none;
        }

        thead tr:nth-child(2) th {
            background-color: #1a6bad;
            color: #e8f0f8;
            font-weight: 500;
            padding: 7px 8px;
            text-align: center;
            font-size: 8.5px;
            border-right: 1px solid #2a7bbf;
        }

        thead tr:nth-child(2) th:last-child {
            border-right: none;
        }

        tbody tr {
            border-bottom: 1px solid #e4eaf0;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f7fafd;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody td {
            padding: 8px 7px;
            text-align: center;
            vertical-align: middle;
            color: #333;
            font-size: 9px;
            border-right: 1px solid #e4eaf0;
        }

        tbody td:last-child {
            border-right: none;
        }

        /* columna periodo y folio destacados */
        tbody td:first-child {
            font-weight: 600;
            color: #004A80;
        }

        tbody td:nth-child(2) {
            font-weight: 600;
        }

        /* ── PIE DE PÁGINA ── */
        .footer-nota {
            border-top: 1px solid #c8d8e8;
            padding-top: 10px;
            margin-top: 5px;
            font-size: 8.5px;
            color: #666;
            font-style: italic;
        }

        .footer-nota span {
            font-weight: 700;
            color: #004A80;
            font-style: normal;
        }

        /* ── SALTO DE PÁGINA ── */
        .page-break {
            page-break-before: always;
        }

        .thead-margin {
            margin-top: 40px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #004A80;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #c8d8e8;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <div class="header-logo">
            <img src="{{public_path().'/assets/img/logo.jpg'}}" alt="Ministerio">
        </div>
        <div class="header-titles">
            <div class="ministerio">Ministerio de Desarrollo Social y Familia</div>
            <h1>Cartola Histórica<br>Registro Social de Hogares</h1>
            <div class="subtitulo">Fecha de Extracción {{ $fechaExtraccion }} - {{ $horaExtraccion }}</div>
        </div>s
    </div>

    <div class="ficha">
        <div class="ficha-grid">
            <div class="ficha-col">
                <div class="ficha-item">
                    <div class="ficha-label">Nombre</div>
                    <div class="ficha-valor">{{ $nombreCompleto }}</div>
                </div>
                <div class="ficha-item">
                    <div class="ficha-label">RUN</div>
                    <div class="ficha-valor">{{ $runCompleto }}</div>
                </div>
                <div class="ficha-item">
                    <div class="ficha-label">Responsable de la emisión</div>
                    <div class="ficha-valor">{{$nombreUsuario}}</div>
                </div>
            </div>
            <div class="ficha-col">
                <div class="ficha-item">
                    <div class="ficha-label">Período Consultado</div>
                    <div class="ficha-valor">{{ $periodoConsultado }}</div>
                </div>
                <div class="ficha-item">
                    <div class="ficha-label">Fuente</div>
                    <div class="ficha-valor">{{$comunaUsuario}}</div>
                </div>
            </div>
        </div>
    </div>


    @foreach($paginas as $pageIndex => $grupos)
        <div class="{{ $pageIndex > 0 ? 'page-break' : '' }}">

            @if($pageIndex === 0)
                <div class="section-title">Registro histórico de calificación socioeconómica</div>
            @endif

            <div class="table-wrapper {{ $pageIndex > 0 ? 'thead-margin' : '' }}">
                <table>
                    <thead>
                    <tr>
                        <th rowspan="2">Periodo</th>
                        <th rowspan="2">Folio RSH</th>
                        <th rowspan="2">Comuna</th>
                        <th rowspan="2">CSE</th>
                        <th colspan="3">Integrantes del Grupo Familiar</th>
                    </tr>
                    <tr>
                        <th>RUN</th>
                        <th>Nombre Completo</th>
                        <th>Parentesco</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($grupos as $grupo)
                        @php
                            $periodo = $grupo['periodo'];
                            [$folio, $comuna, $cse] = explode('|', $grupo['clave_grupo']);
                            $mostroGrupo   = false;
                            $mostroPeriodo = false;
                        @endphp
                        @foreach($grupo['integrantes'] as $item)
                            <tr>
                                <td>{{ !$mostroPeriodo ? $periodo : '' }}</td>
                                <td>{{ !$mostroGrupo ? $folio : '' }}</td>
                                <td>{{ !$mostroGrupo ? $comuna : '' }}</td>
                                <td>{{ !$mostroGrupo ? $cse : '' }}</td>
                                <td>{{ $item['run_integrante_formateado'] }}</td>
                                <td>{{ $item['nombre_completo'] }}</td>
                                <td>{{ $item['parentesco'] }}</td>
                            </tr>
                            @php
                                $mostroGrupo   = true;
                                $mostroPeriodo = true;
                            @endphp
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="footer-nota">
        <span>Nota:</span> Desde mayo de 2023, la Calificación Socioeconómica para el RSH se actualiza en la primera y
        segunda quincena de cada mes, excepto cuando razones técnicas lo impiden.
    </div>

</div>

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 8;
            $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
            $width  = $pdf->get_width();
            $height = $pdf->get_height();
            $textWidth = $fontMetrics->get_text_width($pageText, $font, $size);
            $x = ($width - $textWidth) / 2;
            $y = $height - 15;
            $pdf->text($x, $y, $pageText, $font, $size);
        ');
    }
</script>

</body>
</html>
