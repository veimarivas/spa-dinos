<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salario Pagado — {{ $mesLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            padding: 18px 22px;
            line-height: 1.4;
        }

        /* ===== HEADER ===== */
        .header {
            border-bottom: 1.5px solid #1a2d4c;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: middle; }
        .logo-cell { width: 70px; }
        .logo-cell img { width: 60px; height: auto; }
        .brand-cell { padding-left: 12px; }
        .brand-cell .empresa { font-size: 14px; font-weight: bold; color: #1a2d4c; letter-spacing: 0.3px; }
        .brand-cell .sub { font-size: 9px; color: #6b7280; margin-top: 2px; }
        .doc-cell { text-align: right; width: 220px; }
        .doc-title {
            font-size: 13px; font-weight: bold; color: #1a2d4c;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .doc-subtitle { margin-top: 4px; font-size: 9px; color: #475569; font-weight: 600; }
        .doc-fecha { margin-top: 4px; font-size: 8.5px; color: #6b7280; }

        /* ===== INFO BOX ===== */
        .info-box {
            display: block;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
        }
        .info-box .info-header {
            background: #f8fafc;
            padding: 6px 10px;
            font-weight: bold;
            color: #1a2d4c;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 7px 10px; vertical-align: top; font-size: 9px; }
        .info-table .label {
            font-weight: 600; color: #6b7280;
            font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.4px;
            display: block; margin-bottom: 2px;
        }
        .info-table .value { color: #1f2937; font-size: 9.5px; }
        .info-table .value-strong { color: #1a2d4c; font-size: 10px; font-weight: bold; }

        /* ===== STATS ===== */
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 12px; }
        .stat-box {
            padding: 10px 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #1a2d4c;
            vertical-align: middle;
        }
        .stat-box.normal   { border-left-color: #0d9488; }
        .stat-box.especial { border-left-color: #b45309; }
        .stat-box.total    { border-left-color: #15803d; }
        .stat-label {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1a2d4c;
            margin-top: 2px;
        }
        .stat-box.normal .stat-value   { color: #0d9488; }
        .stat-box.especial .stat-value { color: #b45309; }
        .stat-box.total .stat-value    { color: #15803d; }
        .stat-sub { font-size: 7.5px; color: #94a3b8; margin-top: 2px; }

        /* ===== TABLA ===== */
        .main-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .main-table th {
            background: #1a2d4c;
            color: #fff;
            padding: 7px 8px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .main-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8.5px;
            vertical-align: middle;
        }
        .main-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold     { font-weight: bold; }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-radius: 2px;
            border: 1px solid;
        }
        .badge-normal   { background: #f0fdf4; color: #0d9488; border-color: #5eead4; }
        .badge-especial { background: #fef3c7; color: #b45309; border-color: #fcd34d; }
        .badge-asist    { background: #f0fdf4; color: #15803d; border-color: #86efac; }
        .badge-no-asist { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }

        /* ===== TOTAL ROW ===== */
        .total-row td {
            background: #1a2d4c !important;
            color: #fff !important;
            font-weight: bold !important;
            padding: 9px 10px;
            font-size: 10px;
            border-top: none;
            border-bottom: none;
        }

        /* ===== FIRMAS ===== */
        .firmas { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .firmas td { width: 50%; text-align: center; padding: 0 24px; vertical-align: top; }
        .firma-linea {
            border-top: 1px solid #475569;
            margin-top: 32px;
            padding-top: 4px;
            font-size: 8.5px;
            color: #475569;
        }
        .firma-linea strong { color: #1a2d4c; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 14px;
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5px;
            color: #94a3b8;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    {{-- ===== HEADER ===== --}}
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('backend/assets/images/logo.png') }}" alt="Logo">
                </td>
                <td class="brand-cell">
                    <div class="empresa">Dino Baby Spa</div>
                    <div class="sub">Centro de Desarrollo Infantil</div>
                </td>
                <td class="doc-cell">
                    <div class="doc-title">Reporte de Salario</div>
                    <div class="doc-subtitle">{{ ucfirst($tipo) }} &middot; {{ $mesLabel }}</div>
                    <div class="doc-fecha">Emitido: {{ $fechaEmision->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== INFO BOX: TRABAJADOR + GASTO ===== --}}
    <div class="info-box">
        <div class="info-header">Información del Pago</div>
        <table class="info-table">
            <tr>
                <td style="width:33%;">
                    <span class="label">Trabajador</span>
                    <span class="value-strong">
                        {{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}
                    </span>
                    <div style="margin-top:4px;">
                        <span class="label">Cargo</span>
                        <span class="value">{{ $trabajador->cargo->nombre ?? '—' }}</span>
                    </div>
                </td>
                <td style="width:33%;">
                    <span class="label">Periodo Liquidado</span>
                    <span class="value-strong">{{ $mesLabel }}</span>
                    <div style="margin-top:4px;">
                        <span class="label">Sesiones Pagadas</span>
                        <span class="value">{{ $registros->count() }}</span>
                    </div>
                </td>
                <td style="width:34%;">
                    @if($gasto)
                        <span class="label">Gasto Asociado</span>
                        <span class="value">{{ $gasto->descripcion ?? '—' }}</span>
                        <div style="margin-top:4px;">
                            <span class="label">Fecha de pago</span>
                            <span class="value">{{ \Carbon\Carbon::parse($gasto->fecha_gasto)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== STATS ===== --}}
    <table class="stats-table">
        <tr>
            <td class="stat-box normal" style="width:33%;">
                <div class="stat-label">Tarifa Normal</div>
                <div class="stat-value">{{ number_format($totalNormal, 2) }} Bs.</div>
                <div class="stat-sub">{{ $registros->where('tarifa','normal')->count() }} sesión(es)</div>
            </td>
            <td class="stat-box especial" style="width:33%;">
                <div class="stat-label">Tarifa Especial</div>
                <div class="stat-value">{{ number_format($totalEspecial, 2) }} Bs.</div>
                <div class="stat-sub">{{ $registros->where('tarifa','especial')->count() }} sesión(es)</div>
            </td>
            <td class="stat-box total" style="width:34%;">
                <div class="stat-label">Total Pagado</div>
                <div class="stat-value">{{ number_format($totalPagado, 2) }} Bs.</div>
                <div class="stat-sub">{{ $registros->count() }} sesión(es) en total</div>
            </td>
        </tr>
    </table>

    {{-- ===== TABLA ===== --}}
    <table class="main-table">
        <thead>
            @if($tipo === 'detallado')
                <tr>
                    <th style="width:3%;">#</th>
                    <th style="width:8%;">Fecha</th>
                    <th style="width:9%;">Hora</th>
                    <th style="width:5%;" class="text-center">N°</th>
                    <th style="width:15%;">Cliente</th>
                    <th style="width:14%;">Hijo</th>
                    <th style="width:13%;">Plan</th>
                    <th style="width:12%;">Servicio</th>
                    <th style="width:7%;" class="text-center">Tarifa</th>
                    <th style="width:6%;" class="text-center">Asist.</th>
                    <th style="width:8%;" class="text-right">Pago (Bs)</th>
                </tr>
            @else
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:12%;">Fecha</th>
                    <th style="width:12%;">Hora</th>
                    <th style="width:8%;" class="text-center">N° Sesión</th>
                    <th>Servicio</th>
                    <th style="width:11%;" class="text-center">Tarifa</th>
                    <th style="width:10%;" class="text-center">Asistencia</th>
                    <th style="width:14%;" class="text-right">Pago (Bs)</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @foreach($registros as $i => $r)
                @php
                    $tarifaBadge = $r['tarifa'] === 'especial'
                        ? '<span class="badge badge-especial">Especial</span>'
                        : '<span class="badge badge-normal">Normal</span>';
                    if ($r['asistio'] === true) {
                        $asistBadge = '<span class="badge badge-asist">Sí</span>';
                    } elseif ($r['asistio'] === false) {
                        $asistBadge = '<span class="badge badge-no-asist">No</span>';
                    } else {
                        $asistBadge = '—';
                    }
                @endphp
                @if($tipo === 'detallado')
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $r['fecha'] }}</td>
                        <td>{{ $r['hora'] }}</td>
                        <td class="text-center fw-bold" style="color:#1a2d4c;">{{ $r['n_sesion'] }}</td>
                        <td>{{ $r['cliente'] }}</td>
                        <td>{{ $r['hijo'] }}</td>
                        <td>{{ $r['plan'] }}</td>
                        <td>{{ $r['servicio'] }}</td>
                        <td class="text-center">{!! $tarifaBadge !!}</td>
                        <td class="text-center">{!! $asistBadge !!}</td>
                        <td class="text-right fw-bold" style="color:#15803d;">{{ number_format($r['pago'], 2) }}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $r['fecha'] }}</td>
                        <td>{{ $r['hora'] }}</td>
                        <td class="text-center fw-bold" style="color:#1a2d4c;">{{ $r['n_sesion'] }}</td>
                        <td>{{ $r['servicio'] }}</td>
                        <td class="text-center">{!! $tarifaBadge !!}</td>
                        <td class="text-center">{!! $asistBadge !!}</td>
                        <td class="text-right fw-bold" style="color:#15803d;">{{ number_format($r['pago'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="{{ $tipo === 'detallado' ? 10 : 7 }}" class="text-right">TOTAL PAGADO</td>
                <td class="text-right">{{ number_format($totalPagado, 2) }} Bs.</td>
            </tr>
        </tfoot>
    </table>

    {{-- ===== FIRMAS ===== --}}
    <table class="firmas">
        <tr>
            <td>
                <div class="firma-linea">
                    <strong>Recibí Conforme</strong><br>
                    <span style="color:#94a3b8;">
                        {{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}
                    </span>
                </div>
            </td>
            <td>
                <div class="firma-linea">
                    <strong>Administración</strong><br>
                    <span style="color:#94a3b8;">Responsable de Pagos</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dino Baby Spa &middot; Centro de Desarrollo Infantil<br>
        Reporte {{ ucfirst($tipo) }} &middot; Generado el {{ $fechaEmision->format('d/m/Y H:i') }}
    </div>
</body>
</html>
