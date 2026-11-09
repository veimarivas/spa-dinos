<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $venta->recibo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
            line-height: 1.4;
            color: #1f2937;
            padding: 18px 22px;
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
        .doc-cell { text-align: right; width: 200px; }
        .doc-title { font-size: 13px; font-weight: bold; color: #1a2d4c; text-transform: uppercase; letter-spacing: 0.8px; }
        .doc-recibo {
            margin-top: 4px; display: inline-block;
            background: #1a2d4c; color: #fff;
            padding: 4px 12px; font-size: 10px; font-weight: bold; letter-spacing: 0.5px;
        }
        .doc-fecha { margin-top: 4px; font-size: 8.5px; color: #6b7280; }

        /* ===== INFO ===== */
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-grid td { vertical-align: top; padding: 0 4px; }
        .info-grid td:first-child { padding-left: 0; }
        .info-grid td:last-child { padding-right: 0; }

        .section { border: 1px solid #e2e8f0; border-radius: 3px; background: #fff; }
        .section-header {
            background: #f8fafc;
            padding: 6px 10px;
            font-weight: bold;
            color: #1a2d4c;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-body { padding: 8px 10px; }
        .label {
            font-weight: 600; color: #6b7280;
            font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.4px;
            margin-bottom: 1px;
        }
        .value { font-size: 9px; color: #1f2937; line-height: 1.3; }
        .value-strong { font-size: 9.5px; font-weight: bold; color: #1a2d4c; }
        .mt-1 { margin-top: 5px; }

        /* ===== TABLA PRODUCTOS ===== */
        .table-data { width: 100%; border-collapse: collapse; font-size: 9px; }
        .table-data th {
            background: #1a2d4c;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .table-data td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }
        .table-data tbody tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        /* ===== TOTALES ===== */
        .totales-wrap { margin-top: 10px; }
        .totales {
            width: 55%; margin-left: auto;
            border-collapse: collapse; border: 1px solid #e2e8f0;
        }
        .totales td { padding: 5px 10px; font-size: 9px; border-bottom: 1px solid #f1f5f9; }
        .totales .label-row { text-align: right; color: #475569; font-weight: 600; width: 60%; }
        .totales .value-row { text-align: right; color: #1f2937; font-weight: bold; width: 40%; }
        .totales tr:last-child td {
            border-bottom: none;
            background: #1a2d4c;
            color: #fff;
            font-size: 10.5px;
            padding: 7px 10px;
        }
        .totales tr.row-descuento .value-row { color: #b91c1c; }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-radius: 2px;
            border: 1px solid;
            margin-right: 4px;
        }
        .badge-efectivo { background: #fef9c3; color: #854d0e; border-color: #facc15; }
        .badge-qr       { background: #e0f2fe; color: #075985; border-color: #38bdf8; }
        .badge-parcial  { background: #e2e8f0; color: #334155; border-color: #94a3b8; }

        /* ===== FIRMAS ===== */
        .firmas { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .firmas td { width: 50%; text-align: center; padding: 0 18px; vertical-align: top; }
        .firma-linea {
            border-top: 1px solid #475569;
            margin-top: 28px;
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
                    <div class="sub">contacto@dinobabyspa.com &middot; +591 XXX XXX XXX</div>
                </td>
                <td class="doc-cell">
                    <div class="doc-title">Recibo de Venta</div>
                    <div class="doc-recibo">N° {{ $venta->recibo }}</div>
                    <div class="doc-fecha">Emitido: {{ $fechaEmision->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== INFO ===== --}}
    <table class="info-grid">
        <tr>
            <td style="width:40%;">
                <div class="section">
                    <div class="section-header">Cliente</div>
                    <div class="section-body">
                        <div class="label">Nombre completo</div>
                        <div class="value-strong">
                            {{ $cliente->nombres }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno ?? '' }}
                        </div>
                        @if($cliente->carnet)
                            <div class="label mt-1">Carnet</div>
                            <div class="value">{{ $cliente->carnet }}</div>
                        @endif
                        @if($cliente->celular)
                            <div class="label mt-1">Celular</div>
                            <div class="value">{{ $cliente->celular }}</div>
                        @endif
                    </div>
                </div>
            </td>

            <td style="width:32%;">
                <div class="section">
                    <div class="section-header">Sesión</div>
                    <div class="section-body">
                        @if($venta->sesion)
                            <div class="label">Nº Sesión</div>
                            <div class="value-strong">#{{ $venta->sesion->n_sesion }}</div>
                            @if($venta->sesion->fecha)
                                <div class="label mt-1">Fecha</div>
                                <div class="value">{{ \Carbon\Carbon::parse($venta->sesion->fecha)->format('d/m/Y') }}</div>
                            @endif
                            @if($venta->sesion->servicio)
                                <div class="label mt-1">Servicio</div>
                                <div class="value">{{ $venta->sesion->servicio->nombre }}</div>
                            @endif
                        @else
                            <div class="value" style="color:#94a3b8;font-style:italic;">Sin sesión asociada</div>
                        @endif
                    </div>
                </div>
            </td>

            <td style="width:28%;">
                <div class="section">
                    <div class="section-header">Registrado por</div>
                    <div class="section-body">
                        @if($venta->trabajador)
                            <div class="label">Trabajador</div>
                            <div class="value-strong">
                                {{ $venta->trabajador->persona->nombres }} {{ $venta->trabajador->persona->apellido_paterno }}
                            </div>
                        @endif
                        <div class="label mt-1">Fecha de venta</div>
                        <div class="value">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                        <div class="label mt-1">Tipo de pago</div>
                        <div class="value">
                            <span class="badge badge-{{ $venta->tipo_pago }}">{{ ucfirst($venta->tipo_pago) }}</span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== PRODUCTOS ===== --}}
    <div class="section" style="margin-bottom:10px;">
        <div class="section-header">Detalle de Productos</div>
        <div class="section-body" style="padding:6px 8px;">
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:50%;">Producto</th>
                        <th style="width:12%;" class="text-center">Cant.</th>
                        <th style="width:16%;" class="text-right">Precio Unit. (Bs)</th>
                        <th style="width:17%;" class="text-right">Subtotal (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $i => $det)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>
                                {{ $det->producto->nombre }}
                                @if($det->producto->marca)
                                    <span style="color:#94a3b8;font-size:7.5px;">({{ $det->producto->marca }})</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $det->cantidad }}</td>
                            <td class="text-right">{{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-right fw-bold" style="color:#1a2d4c;">{{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== TOTALES ===== --}}
    <div class="totales-wrap">
        <table class="totales">
            <tr>
                <td class="label-row">Subtotal</td>
                <td class="value-row">{{ number_format($venta->detalles->sum('subtotal'), 2) }} Bs.</td>
            </tr>
            @if($venta->descuento_bs > 0)
                <tr class="row-descuento">
                    <td class="label-row">Descuento</td>
                    <td class="value-row">- {{ number_format($venta->descuento_bs, 2) }} Bs.</td>
                </tr>
            @endif
            <tr>
                <td class="label-row">TOTAL</td>
                <td class="value-row">{{ number_format($venta->total_bs, 2) }} Bs.</td>
            </tr>
        </table>
    </div>

    {{-- ===== DETALLE DE PAGO ===== --}}
    @if($venta->pagoDetalles->count() > 0)
        <div class="section" style="margin-top:10px;">
            <div class="section-header">Detalle de Pago</div>
            <div class="section-body">
                @foreach($venta->pagoDetalles as $pd)
                    <span class="badge badge-{{ $pd->tipo_pago }}">
                        {{ ucfirst($pd->tipo_pago) }}: {{ number_format($pd->pago_bs, 2) }} Bs.
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    @if($venta->observacion)
        <div class="section" style="margin-top:8px;">
            <div class="section-header">Observación</div>
            <div class="section-body"><div class="value">{{ $venta->observacion }}</div></div>
        </div>
    @endif

    {{-- ===== FIRMAS ===== --}}
    <table class="firmas">
        <tr>
            <td>
                <div class="firma-linea">
                    <strong>Firma del Cliente</strong>
                </div>
            </td>
            <td>
                <div class="firma-linea">
                    <strong>Responsable</strong><br>
                    <span style="color:#94a3b8;">
                        @if($venta->trabajador)
                            {{ $venta->trabajador->persona->nombres }} {{ $venta->trabajador->persona->apellido_paterno }}
                        @else
                            Administración
                        @endif
                    </span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dino Baby Spa &middot; Centro de Desarrollo Infantil<br>
        Recibo N° {{ $venta->recibo }} &middot; Emitido el {{ $fechaEmision->format('d/m/Y H:i') }}
    </div>
</body>
</html>
