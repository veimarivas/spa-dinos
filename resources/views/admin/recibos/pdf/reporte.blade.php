<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Recibos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            padding: 20px 22px;
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
        .doc-title { font-size: 13px; font-weight: bold; color: #1a2d4c; text-transform: uppercase; letter-spacing: 0.8px; }
        .doc-fecha { margin-top: 6px; font-size: 8.5px; color: #6b7280; }

        /* ===== PERIODO ===== */
        .periodo {
            margin-bottom: 14px;
            padding: 8px 12px;
            background: #f8fafc;
            border-left: 3px solid #1a2d4c;
            font-size: 9.5px;
        }
        .periodo strong { color: #1a2d4c; }

        /* ===== STATS ===== */
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 14px; }
        .stat-box {
            width: 33.33%;
            padding: 10px 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #1a2d4c;
            vertical-align: middle;
        }
        .stat-box .stat-label {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
        }
        .stat-box .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1a2d4c;
            margin-top: 2px;
        }

        /* ===== TABLA PRINCIPAL ===== */
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
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
        }
        .main-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        /* ===== BADGE ===== */
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
        .badge-efectivo { background: #fef9c3; color: #854d0e; border-color: #facc15; }
        .badge-qr       { background: #e0f2fe; color: #075985; border-color: #38bdf8; }
        .badge-parcial  { background: #e2e8f0; color: #334155; border-color: #94a3b8; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
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
                    <div class="doc-title">Reporte de Recibos</div>
                    <div class="doc-fecha">Emitido: {{ now()->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== PERIODO ===== --}}
    <div class="periodo">
        <strong>Periodo:</strong>
        {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
        al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        @if (request('tipo_pago'))
            &nbsp;&middot;&nbsp; <strong>Tipo de pago:</strong> {{ ucfirst(request('tipo_pago')) }}
        @endif
    </div>

    {{-- ===== STATS ===== --}}
    <table class="stats-table">
        <tr>
            <td class="stat-box">
                <div class="stat-label">Total Ingresos</div>
                <div class="stat-value">{{ number_format($totalIngresos, 2) }} Bs.</div>
            </td>
            <td class="stat-box">
                <div class="stat-label">Total Recibos</div>
                <div class="stat-value">{{ $pagos->count() }}</div>
            </td>
            <td class="stat-box">
                <div class="stat-label">Promedio por recibo</div>
                <div class="stat-value">{{ number_format($pagos->count() > 0 ? $totalIngresos / $pagos->count() : 0, 2) }} Bs.</div>
            </td>
        </tr>
    </table>

    {{-- ===== TABLA ===== --}}
    <table class="main-table">
        <thead>
            <tr>
                <th style="width:11%;">Recibo</th>
                <th style="width:10%;">Fecha</th>
                <th style="width:23%;">Cliente</th>
                <th style="width:21%;">Plan</th>
                <th style="width:9%;">Tipo</th>
                <th style="width:13%;" class="text-right">Monto (Bs)</th>
                <th style="width:13%;" class="text-right">Descuento (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pagos as $pago)
                <tr>
                    <td class="fw-bold" style="color:#1a2d4c;">{{ $pago->recibo }}</td>
                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                    <td>{{ $pago->suscripcion->cliente->persona->nombres }} {{ $pago->suscripcion->cliente->persona->apellido_paterno }}</td>
                    <td>{{ $pago->suscripcion->plan->nombre_plan }}</td>
                    <td><span class="badge badge-{{ $pago->tipo_pago }}">{{ ucfirst($pago->tipo_pago) }}</span></td>
                    <td class="text-right fw-bold" style="color:#15803d;">{{ number_format($pago->pago_bs, 2) }}</td>
                    <td class="text-right" style="color:#b91c1c;">{{ number_format($pago->descuento_bs, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:18px;color:#94a3b8;font-style:italic;">
                        No hay recibos en el periodo seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($pagos->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right fw-bold" style="background:#f8fafc;color:#1a2d4c;padding:8px;">
                        TOTALES
                    </td>
                    <td class="text-right fw-bold" style="background:#1a2d4c;color:#fff;padding:8px;">
                        {{ number_format($totalIngresos, 2) }} Bs.
                    </td>
                    <td class="text-right fw-bold" style="background:#1a2d4c;color:#fff;padding:8px;">
                        {{ number_format($pagos->sum('descuento_bs'), 2) }} Bs.
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dino Baby Spa &middot; Centro de Desarrollo Infantil<br>
        Documento generado el {{ now()->format('d/m/Y H:i') }} por el Sistema de Gestión
    </div>
</body>
</html>
