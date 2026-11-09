<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Gasto #{{ $gasto->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            padding: 18px 22px;
            line-height: 1.4;
        }
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
        .doc-subtitle { margin-top: 4px; font-size: 9px; color: #475569; font-weight: 600; }
        .doc-fecha { margin-top: 4px; font-size: 8.5px; color: #6b7280; }

        .info-box { display: block; margin-bottom: 12px; border: 1px solid #e2e8f0; border-radius: 3px; }
        .info-box .info-header {
            background: #f8fafc; padding: 6px 10px; font-weight: bold;
            color: #1a2d4c; font-size: 8.5px; text-transform: uppercase;
            letter-spacing: 0.6px; border-bottom: 1px solid #e2e8f0;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 7px 10px; vertical-align: top; font-size: 9px; }
        .info-table .label {
            font-weight: 600; color: #6b7280; font-size: 7.5px;
            text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 2px;
        }
        .info-table .value { color: #1f2937; font-size: 9.5px; }
        .monto-box {
            background: #f0fdf4; border: 1px solid #86efac; border-radius: 3px;
            text-align: center; padding: 16px; margin-top: 6px;
        }
        .monto-box .label { font-size: 7.5px; text-transform: uppercase; color: #166534; letter-spacing: 0.5px; }
        .monto-box .amount { font-size: 18px; font-weight: bold; color: #15803d; margin-top: 4px; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 8px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('backend/assets/images/logo.png') }}" alt="Logo">
                </td>
                <td class="brand-cell">
                    <div class="empresa">{{ config('app.name') }}</div>
                    <div class="sub">Sistema de Gestion de Gastos</div>
                </td>
                <td class="doc-cell">
                    <div class="doc-title">Detalle de Gasto</div>
                    <div class="doc-subtitle">N° {{ str_pad($gasto->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div class="doc-fecha">Emitido: {{ now()->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <div class="info-header">Informacion del Gasto</div>
        <table class="info-table">
            <tr>
                <td style="width: 33%;">
                    <span class="label">Categoria</span>
                    <span class="value">{{ $gasto->categoria->nombre ?? 'Sin categoria' }}</span>
                </td>
                <td style="width: 33%;">
                    <span class="label">Fecha de Pago</span>
                    <span class="value">{{ $gasto->fecha_gasto->format('d/m/Y') }}</span>
                </td>
                <td style="width: 33%;">
                    <span class="label">Periodo</span>
                    <span class="value">
                        @php
                            $mesesNom = [1=>'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                        @endphp
                        {{ $mesesNom[$gasto->mes_correspondiente] ?? '' }} {{ $gasto->anio_correspondiente }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <div class="info-header">Descripcion</div>
        <div style="padding:10px;">
            <span class="value">{{ $gasto->descripcion }}</span>
        </div>
    </div>

    <div style="display:flex; gap:12px; margin-bottom:12px;">
        @if ($gasto->comprobante)
        <div class="info-box" style="flex:1;">
            <div class="info-header">Comprobante</div>
            <div style="padding:10px;">
                <span class="value">{{ $gasto->comprobante }}</span>
            </div>
        </div>
        @endif
        <div class="info-box" style="flex:1;">
            <div class="info-header">Origen</div>
            <div style="padding:10px;">
                <span class="value">
                    @if ($gasto->compra_id)
                        Generado desde Compra #{{ $gasto->compra_id }}
                    @else
                        Registro Manual
                    @endif
                </span>
            </div>
        </div>
    </div>

    @if ($gasto->observacion)
    <div class="info-box">
        <div class="info-header">Observacion</div>
        <div style="padding:10px;">
            <span class="value">{{ $gasto->observacion }}</span>
        </div>
    </div>
    @endif

    <div class="monto-box">
        <div class="label">Monto Total</div>
        <div class="amount">{{ number_format($gasto->monto_bs, 2) }} Bs</div>
    </div>

    <div class="footer">
        {{ config('app.name') }} &mdash; Documento generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
