<table class="supervision-excel">
    <thead>
    <tr>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">N° Solicitud</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">RUN</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Comuna</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Teléfono</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Email</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">AH</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Calle</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">N°</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Estado Solicitud</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Sub-Estado</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Asignado a</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">F. Creación</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">F. Modificación</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Tipo Solicitud</th>
        <th style="background:#1d4ed8; color: #f8fafc; border:1px solid #000000; font-weight:bold; text-align: right;">Origen</th>
    </tr>
    </thead>
    <tbody>
        @foreach($listado as $item)
        <tr>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->ID_SOLICITUD }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->RUN }} - {{ $item->DV }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ ( isset($lstComuna[$item->COM_ID]) ) ? $lstComuna[$item->COM_ID] : '' }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->TEL }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->MAIL }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;"></td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;"></td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;"></td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ ( isset( $lstEsados[$item->ID_ESTADO] ) ) ? $lstEsados[$item->ID_ESTADO] : '' }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ ( isset( $lstSubEstado[$item->ID_SUB_ESTADO] ) ) ? $lstSubEstado[$item->ID_SUB_ESTADO] : '' }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->ID_USUARIO_ASIGNADO }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->FECHA_CREACION }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->FECHA_MODIFICACION }}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right">{{ ( isset( $lstTipoSolicitudes[$item->ID_TIPO_SOLICITUD] ) ? $lstTipoSolicitudes[$item->ID_TIPO_SOLICITUD] : '')}}</td>
            <td style="background:#e2e8f0; color:#0f172a; border:1px solid #000000; text-align: right;">{{ $item->CANAL }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
