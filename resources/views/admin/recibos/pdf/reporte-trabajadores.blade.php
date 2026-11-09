<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cobros por Trabajador</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
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
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 16px; }
        .stat-box {
            width: 33.33%;
            padding: 12px 14px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #1a2d4c;
            vertical-align: middle;
        }
        .stat-box.efectivo { border-left-color: #b45309; }
        .stat-box.qr       { border-left-color: #075985; }
        .stat-box.total    { border-left-color: #15803d; }
        .stat-label {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #6b7280;
            font-weight: 600;
        }
        .stat-value {
            font-size: 15px;
            font-weight: bold;
            color: #1a2d4c;
            margin-top: 3px;
        }
        .stat-box.efectivo .stat-value { color: #b45309; }
        .stat-box.qr .stat-value       { color: #075985; }
        .stat-box.total .stat-value    { color: #15803d; }

        /* ===== TABLA PRINCIPAL ===== */
        .main-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .main-table th {
            background: #1a2d4c;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: bold;
        }
        .main-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5px;
        }
        .main-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        .total-row td {
            background: #1a2d4c !important;
            color: #fff !important;
            font-weight: bold !important;
            border-top: none;
            border-bottom: none;
            padding: 10px;
            font-size: 10px;
        }

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
                    <div class="doc-title">Cobros por Trabajador</div>
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
        @if (request('suscripcion_id'))
            &nbsp;&middot;&nbsp; <strong>Suscripción:</strong> #{{ request('suscripcion_id') }}
        @endif
    </div>

    {{-- ===== STATS ===== --}}
    <table class="stats-table">
        <tr>
            <td class="stat-box efectivo">
                <div class="stat-label">Total Efectivo</div>
                <div class="stat-value">{{ number_format($efectivoTotal, 2) }} Bs.</div>
            </td>
            <td class="stat-box qr">
                <div class="stat-label">Total QR</div>
                <div class="stat-value">{{ number_format($qrTotal, 2) }} Bs.</div>
            </td>
            <td class="stat-box total">
                <div class="stat-label">Total General</div>
                <div class="stat-value">{{ number_format($efectivoTotal + $qrTotal, 2) }} Bs.</div>
            </td>
        </tr>
    </table>

    {{-- ===== TABLA ===== --}}
    <table class="main-table">
        <thead>
            <tr>
                <th style="width:40%;">Trabajador</th>
                <th class="text-right" style="width:20%;">Efectivo (Bs.)</th>
                <th class="text-right" style="width:20%;">QR (Bs.)</th>
                <th class="text-right" style="width:20%;">Total (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trabajadoresResumen as $item)
                <tr>
                    <td class="fw-bold" style="color:#1a2d4c;">{{ $item['nombre'] }}</td>
                    <td class="text-right" style="color:#b45309;">{{ number_format($item['efectivo'] ?? 0, 2) }}</td>
                    <td class="text-right" style="color:#075985;">{{ number_format($item['qr'] ?? 0, 2) }}</td>
                    <td class="text-right fw-bold" style="color:#15803d;">{{ number_format(($item['efectivo'] ?? 0) + ($item['qr'] ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding:20px;color:#94a3b8;font-style:italic;">
                        No hay cobros registrados en el periodo seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if (count($trabajadoresResumen) > 0)
            <tfoot>
                <tr class="total-row">
                    <td>TOTALES</td>
                    <td class="text-right">{{ number_format($efectivoTotal, 2) }} Bs.</td>
                    <td class="text-right">{{ number_format($qrTotal, 2) }} Bs.</td>
                    <td class="text-right">{{ number_format($efectivoTotal + $qrTotal, 2) }} Bs.</td>
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
