<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $numeroRecibo }}</title>
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
        .brand-cell .empresa {
            font-size: 14px;
            font-weight: bold;
            color: #1a2d4c;
            letter-spacing: 0.3px;
        }
        .brand-cell .sub {
            font-size: 9px;
            color: #6b7280;
            margin-top: 2px;
        }
        .doc-cell {
            text-align: right;
            width: 200px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: bold;
            color: #1a2d4c;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .doc-recibo {
            margin-top: 4px;
            display: inline-block;
            background: #1a2d4c;
            color: #fff;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .doc-fecha {
            margin-top: 4px;
            font-size: 8.5px;
            color: #6b7280;
        }

        /* ===== TABLAS DE INFO ===== */
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-grid > tbody > tr > td { vertical-align: top; padding: 0 4px; width: 33.33%; }
        .info-grid > tbody > tr > td:first-child { padding-left: 0; }
        .info-grid > tbody > tr > td:last-child { padding-right: 0; }

        .section {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            background: #fff;
        }
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
            font-weight: 600;
            color: #6b7280;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 1px;
        }
        .value { font-size: 9px; color: #1f2937; line-height: 1.3; }
        .value-strong { font-size: 9.5px; font-weight: bold; color: #1a2d4c; }
        .mt-1 { margin-top: 5px; }

        /* ===== TABLA DE DETALLES DE PAGO ===== */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9px;
        }
        .table-data th {
            background: #1a2d4c;
            color: #fff;
            padding: 6px 6px;
            text-align: left;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .table-data td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        /* ===== BADGE TIPO PAGO ===== */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-radius: 2px;
            border: 1px solid;
        }
        .badge-efectivo { background: #fef9c3; color: #854d0e; border-color: #facc15; }
        .badge-qr       { background: #e0f2fe; color: #075985; border-color: #38bdf8; }
        .badge-parcial  { background: #e2e8f0; color: #334155; border-color: #94a3b8; }

        /* ===== TOTALES ===== */
        .totales-wrap { margin-top: 10px; }
        .totales {
            width: 55%;
            margin-left: auto;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
        }
        .totales td {
            padding: 5px 10px;
            font-size: 9px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totales .label-row {
            text-align: right;
            color: #475569;
            font-weight: 600;
            width: 60%;
        }
        .totales .value-row {
            text-align: right;
            color: #1f2937;
            font-weight: bold;
            width: 40%;
        }
        .totales tr:last-child td {
            border-bottom: none;
            background: #1a2d4c;
            color: #fff;
            font-size: 10.5px;
            padding: 7px 10px;
        }
        .totales tr.row-descuento .value-row { color: #b91c1c; }

        /* ===== VERIFICACIÓN ===== */
        .verif {
            margin-top: 12px;
            border: 1px dashed #cbd5e1;
            padding: 8px 12px;
            background: #f8fafc;
        }
        .verif table { width: 100%; border-collapse: collapse; }
        .verif td { vertical-align: middle; }
        .verif .code {
            font-size: 11px;
            font-weight: bold;
            color: #1a2d4c;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }

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
                    <div class="doc-title">Recibo de Pago</div>
                    <div class="doc-recibo">N° {{ $numeroRecibo }}</div>
                    <div class="doc-fecha">Emitido: {{ $fechaEmision->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== INFO: 3 COLUMNAS ===== --}}
    <table class="info-grid">
        <tr>
            <td>
                <div class="section">
                    <div class="section-header">Cliente</div>
                    <div class="section-body">
                        <div class="label">Nombre completo</div>
                        <div class="value-strong">
                            {{ $cliente->nombres }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}
                        </div>

                        <div class="label mt-1">Carnet de Identidad</div>
                        <div class="value">{{ $cliente->carnet ?? 'Sin CI' }}</div>

                        <div class="label mt-1">Teléfono</div>
                        <div class="value">{{ $cliente->celular ?? ($cliente->telefono ?? 'No registrado') }}</div>

                        <div class="label mt-1">Correo</div>
                        <div class="value">{{ $cliente->correo ?? 'No registrado' }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-header">Niño(a)</div>
                    <div class="section-body">
                        @php
                            $hijoPrincipal = $suscripcion->sesiones->first()?->hijo;
                        @endphp

                        @if ($hijoPrincipal && $hijoPrincipal->persona)
                            <div class="label">Nombre</div>
                            <div class="value-strong">
                                {{ $hijoPrincipal->persona->nombres }} {{ $hijoPrincipal->persona->apellido_paterno }}
                            </div>

                            <div class="label mt-1">Identificación</div>
                            <div class="value">{{ $hijoPrincipal->persona->carnet ?? 'Sin CI' }}</div>

                            <div class="label mt-1">Fecha de nacimiento</div>
                            <div class="value">
                                {{ $hijoPrincipal->persona->fecha_nacimiento ? \Carbon\Carbon::parse($hijoPrincipal->persona->fecha_nacimiento)->format('d/m/Y') : '—' }}
                            </div>

                            <div class="label mt-1">Edad</div>
                            <div class="value">
                                {{ $hijoPrincipal->persona->fecha_nacimiento ? \Carbon\Carbon::parse($hijoPrincipal->persona->fecha_nacimiento)->age . ' años' : 'N/A' }}
                            </div>
                        @else
                            <div class="value" style="color:#94a3b8;font-style:italic;">No hay niño asignado</div>
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-header">Suscripción</div>
                    <div class="section-body">
                        <div class="label">ID Suscripción</div>
                        <div class="value-strong">#{{ $suscripcion->id }}</div>

                        <div class="label mt-1">Paquete</div>
                        <div class="value">{{ $plan->paquete->nombre ?? 'N/A' }}</div>

                        <div class="label mt-1">Plan</div>
                        <div class="value">{{ $plan->nombre_plan }}</div>

                        <div class="label mt-1">Tarifa</div>
                        <div class="value">{{ ucfirst($suscripcion->tarifa ?? 'normal') }}</div>

                        <div class="label mt-1">Fecha de registro</div>
                        <div class="value">{{ $suscripcion->fecha_registro->format('d/m/Y') }}</div>

                        <div class="label mt-1">Sesiones del plan</div>
                        <div class="value">{{ $suscripcion->sesiones_iniciales }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== DETALLE DEL PAGO ===== --}}
    <div class="section" style="margin-bottom:10px;">
        <div class="section-header">Detalle del Pago</div>
        <div class="section-body" style="padding:6px 8px;">
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width:14%;">Recibo</th>
                        <th style="width:14%;">Fecha</th>
                        <th style="width:14%;">Tipo</th>
                        <th style="width:18%;" class="text-right">Monto</th>
                        <th style="width:18%;" class="text-right">Descuento</th>
                        <th style="width:22%;">Atendido por</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong style="color:#1a2d4c;">{{ $pago->recibo }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td><span class="badge badge-{{ $pago->tipo_pago }}">{{ ucfirst($pago->tipo_pago) }}</span></td>
                        <td style="text-align:right;font-weight:bold;color:#15803d;">{{ number_format($pago->pago_bs, 2) }} Bs.</td>
                        <td style="text-align:right;color:#b91c1c;">{{ number_format($pago->descuento_bs, 2) }} Bs.</td>
                        <td>{{ $trabajador ? $trabajador->nombres . ' ' . $trabajador->apellido_paterno : 'Administración' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== TOTALES ===== --}}
    @php
        $tarifa = $suscripcion->tarifa ?? 'normal';
        $precioRef = $suscripcion->monto_total
            ?? ($tarifa === 'especial' && !is_null($plan->precio_especial)
                ? (float) $plan->precio_especial
                : (float) ($plan->precio ?? 0));
    @endphp
    <div class="totales-wrap">
        <table class="totales">
            <tr>
                <td class="label-row">Precio del plan ({{ ucfirst($tarifa) }})</td>
                <td class="value-row">{{ number_format($precioRef, 2) }} Bs.</td>
            </tr>
            <tr>
                <td class="label-row">Total pagado</td>
                <td class="value-row" style="color:#15803d;">{{ number_format($totalPagado, 2) }} Bs.</td>
            </tr>
            @if ($totalDescuento > 0)
                <tr class="row-descuento">
                    <td class="label-row">Descuentos aplicados</td>
                    <td class="value-row">- {{ number_format($totalDescuento, 2) }} Bs.</td>
                </tr>
            @endif
            <tr>
                <td class="label-row">Saldo pendiente</td>
                <td class="value-row">{{ number_format($saldoPendiente, 2) }} Bs.</td>
            </tr>
        </table>
    </div>

    {{-- ===== VERIFICACIÓN ===== --}}
    <div class="verif">
        <table>
            <tr>
                <td>
                    <div class="label">Código de verificación</div>
                    <div class="code">{{ strtoupper(substr(md5($numeroRecibo . $pago->id), 0, 12)) }}</div>
                </td>
                <td style="text-align:right;">
                    <div class="label">Documento</div>
                    <div class="value" style="font-size:8.5px;">Este recibo es un comprobante oficial de pago.</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== FIRMAS ===== --}}
    <table class="firmas">
        <tr>
            <td>
                <div class="firma-linea">
                    <strong>Firma del Cliente</strong><br>
                    <span style="color:#94a3b8;">{{ substr($cliente->nombres, 0, 20) }} {{ substr($cliente->apellido_paterno, 0, 20) }}</span>
                </div>
            </td>
            <td>
                <div class="firma-linea">
                    <strong>Responsable</strong><br>
                    <span style="color:#94a3b8;">{{ $trabajador ? substr($trabajador->nombres, 0, 20) . ' ' . substr($trabajador->apellido_paterno, 0, 20) : 'Administración' }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dino Baby Spa &middot; Centro de Desarrollo Infantil<br>
        Documento generado el {{ $fechaEmision->format('d/m/Y H:i') }} por el Sistema de Gestión
    </div>

</body>
</html>
