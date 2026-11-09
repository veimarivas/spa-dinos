@extends('admin.dashboard')

@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-4 p-4 shadow-sm">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-receipt-line me-2"></i>Reporte de Recibos y Pagos
                        </h4>
                        <p class="text-white-50 mb-0">Visualiza y analiza todos los ingresos del centro</p>
                    </div>
                    <div class="page-title-right d-flex gap-2">
                        <a href="{{ route('admin.recibos.trabajadoresPdf', [
                            'fecha_inicio' => $fechaInicio,
                            'fecha_fin' => $fechaFin,
                            'tipo_pago' => request('tipo_pago'),
                        ]) }}"
                            class="btn btn-info btn-lg rounded-pill px-4 shadow-sm" target="_blank">
                            <i class="ri-file-pdf-line me-1"></i> Reporte Trabajadores PDF
                        </a>
                        <a href="{{ route('admin.recibos.pdf', [
                            'fecha_inicio' => $fechaInicio,
                            'fecha_fin' => $fechaFin,
                            'tipo_pago' => request('tipo_pago'),
                        ]) }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm" target="_blank">
                            <i class="ri-file-pdf-line me-1"></i> Descargar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('admin.recibos.reporte') }}" id="formFiltros">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-calendar-2-line me-1 text-primary"></i>Mes
                                    </label>
                                    <select name="mes" class="form-select form-select-lg rounded-3" required>
                                        @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                            <option value="{{ $i + 1 }}" {{ ($i + 1) === (int) $mesSeleccionado ? 'selected' : '' }}>{{ $mes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-calendar-event-line me-1 text-primary"></i>Gestión
                                    </label>
                                    <select name="gestion" class="form-select form-select-lg rounded-3" required>
                                        @foreach ($gestionesDisponibles as $g)
                                            <option value="{{ $g }}" {{ (int) $g === (int) $gestionSeleccionada ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-bank-card-line me-1 text-success"></i>Tipo de Pago
                                    </label>
                                    <select name="tipo_pago" class="form-select form-select-lg rounded-3">
                                        <option value="">Todos</option>
                                        <option value="efectivo" {{ request('tipo_pago') == 'efectivo' ? 'selected' : '' }}>
                                            Efectivo</option>
                                        <option value="qr" {{ request('tipo_pago') == 'qr' ? 'selected' : '' }}>QR
                                        </option>
                                        <option value="parcial" {{ request('tipo_pago') == 'parcial' ? 'selected' : '' }}>
                                            Parcial</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                                        <i class="ri-search-line me-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('admin.recibos.reporte') }}"
                                        class="btn btn-secondary btn-lg rounded-3">
                                        <i class="ri-close-line"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                            {{-- Mantengo el rango como hidden para compatibilidad con descargas PDF u otros consumidores --}}
                            <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
                            <input type="hidden" name="fecha_fin"    value="{{ $fechaFin }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white-50 mb-1 fw-medium">Total Ingresos</p>
                                <h3 class="mb-0 fw-bold text-white">{{ number_format($totalIngresos, 2) }} Bs.</h3>
                            </div>
                            <div
                                class="avatar-lg rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white-50 mb-1 fw-medium">Total Recibos</p>
                                <h3 class="mb-0 fw-bold text-white">{{ $totalRegistros }}</h3>
                            </div>
                            <div
                                class="avatar-lg rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                                <i class="ri-receipt-line fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning text-white hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white-50 mb-1 fw-medium">Descuentos</p>
                                <h3 class="mb-0 fw-bold text-white">{{ number_format($totalDescuentos, 2) }} Bs.</h3>
                            </div>
                            <div
                                class="avatar-lg rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                                <i class="ri-percent-line fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ri-bar-chart-line me-2 text-primary"></i>Ingresos por Día
                        </h5>
                    </div>
                    <div class="card-body p-4" style="height: 350px;">
                        <canvas id="chartIngresosDia" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ri-pie-chart-line me-2 text-success"></i>Pagos por Tipo (Efectivo vs QR)
                        </h5>
                    </div>
                    <div class="card-body p-4" style="height: 350px;">
                        <canvas id="chartPagosTipo" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cobros por Trabajador -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ri-user-line me-2 text-info"></i>Cobros por Trabajador
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Trabajador</th>
                                        <th>Efectivo (Bs.)</th>
                                        <th>QR (Bs.)</th>
                                        <th>Total (Bs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trabajadoresResumen as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                        <i class="ri-user-line text-primary"></i>
                                                    </div>
                                                    <span>{{ $item['nombre'] }}</span>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($item['efectivo'] ?? 0, 2) }}</td>
                                            <td class="fw-bold text-info">{{ number_format($item['qr'] ?? 0, 2) }}</td>
                                            <td class="fw-bold text-dark">
                                                {{ number_format(($item['efectivo'] ?? 0) + ($item['qr'] ?? 0), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="ri-inbox-line fs-3 text-muted"></i>
                                                <p class="text-muted mb-0">No hay cobros registrados en el período
                                                    seleccionado</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Recibos (Pagos + Ventas) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="ri-list-unordered me-2 text-info"></i>Detalle de Recibos
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="ri-file-list-3-line me-1"></i>{{ $registros->total() }} registros
                                </span>
                                <div class="search-box position-relative" style="min-width: 200px;">
                                    <input type="text" id="searchRecibos" class="form-control form-control-sm ps-4 rounded-pill" placeholder="Buscar...">
                                    <i class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive tabla-recibos">
                            <table class="table table-hover align-middle mb-0" id="recibosTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="col-recibo"><i class="ri-file-text-line me-1"></i>Recibo</th>
                                        <th class="col-fecha"><i class="ri-calendar-line me-1"></i>Fecha</th>
                                        <th class="col-cliente"><i class="ri-user-line me-1"></i>Cliente</th>
                                        <th class="col-detalle"><i class="ri-file-list-3-line me-1"></i>Detalle</th>
                                        <th class="col-tipo"><i class="ri-wallet-line me-1"></i>Tipo</th>
                                        <th class="col-monto"><i class="ri-money-money-dollar-circle-line me-1"></i>Monto</th>
                                        <th class="col-trabajador"><i class="ri-user-settings-line me-1"></i>Atendido</th>
                                        <th class="text-center col-acciones"><i class="ri-settings-3-line me-1"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyRecibos">
                                    @forelse($registros as $reg)
                                        <tr data-search="{{ strtolower($reg['recibo'] . ' ' . $reg['cliente'] . ' ' . $reg['detalle'] . ' ' . $reg['tipo_pago'] . ' ' . ($reg['tarifa'] ?? '')) }}">
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size:0.72rem;">
                                                    <i class="ri-file-text-line me-1"></i>{{ $reg['recibo'] ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size:0.7rem;">
                                                    <i class="ri-calendar-event-line text-muted"></i>
                                                    <span>{{ \Carbon\Carbon::parse($reg['fecha'])->format('d/m/Y') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-1">
                                                <a href="{{ route('admin.clientes.seguimiento', $reg['cliente_id']) }}" class="text-decoration-none">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <i class="ri-user-follow-line text-primary" style="font-size:0.9rem;"></i>
                                                        <span class="fw-semibold text-truncate" style="font-size:0.68rem; color:#405189;" title="{{ $reg['cliente'] }}">{{ $reg['cliente'] }}</span>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $origenClass = $reg['origen'] === 'pago' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning';
                                                    $origenIcon = $reg['origen'] === 'pago' ? 'ri-bank-card-line' : 'ri-shopping-cart-line';
                                                @endphp
                                                <span class="badge {{ $origenClass }} rounded-pill" style="font-size:0.65rem;">
                                                    <i class="{{ $origenIcon }} me-1"></i>{{ Str::limit($reg['detalle'], 18) }}
                                                </span>
                                                @if ($reg['origen'] === 'pago' && !empty($reg['tarifa']))
                                                    @if ($reg['tarifa'] === 'especial')
                                                        <span class="badge rounded-pill d-block mt-1" style="font-size:0.6rem;background:#fef3c7;color:#b45309;" title="Suscripción con tarifa especial">
                                                            <i class="ri-star-fill me-1"></i>Tarifa Especial
                                                        </span>
                                                    @else
                                                        <span class="badge rounded-pill d-block mt-1" style="font-size:0.6rem;background:#f0fdf4;color:#0d9488;" title="Suscripción con tarifa normal">
                                                            <i class="ri-sun-line me-1"></i>Tarifa Normal
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $tipoBadge = match($reg['tipo_pago']) {
                                                        'efectivo' => 'bg-success-subtle text-success',
                                                        'qr'       => 'bg-primary-subtle text-primary',
                                                        default    => 'bg-secondary-subtle text-secondary',
                                                    };
                                                    $tipoIcon = match($reg['tipo_pago']) {
                                                        'efectivo' => 'ri-money-dollar-circle-line',
                                                        'qr'       => 'ri-qr-code-line',
                                                        default    => 'ri-exchange-line',
                                                    };
                                                @endphp
                                                <span class="badge {{ $tipoBadge }} rounded-pill" style="font-size:0.65rem;">
                                                    <i class="{{ $tipoIcon }} me-1"></i>{{ ucfirst($reg['tipo_pago']) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-start gap-1">
                                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:0.7rem;">
                                                        <i class="ri-add-circle-line me-1"></i>{{ number_format($reg['monto'], 2) }} Bs
                                                    </span>
                                                    @if($reg['descuento'] > 0)
                                                        <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size:0.6rem;">
                                                            <i class="ri-subtract-circle-line me-1"></i>{{ number_format($reg['descuento'], 2) }} Bs
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-1">
                                                @if ($reg['trabajador'] && $reg['trabajador_id'])
                                                    <a href="{{ route('admin.trabajadores.ver', $reg['trabajador_id']) }}" class="text-decoration-none">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <i class="ri-shield-user-line text-success" style="font-size:0.9rem;"></i>
                                                            <span class="text-truncate" style="font-size:0.68rem; color:#198754;" title="{{ $reg['trabajador'] }}">{{ $reg['trabajador'] }}</span>
                                                        </div>
                                                    </a>
                                                @else
                                                    <span class="text-muted" style="font-size:0.65rem;">
                                                        <i class="ri-user-line me-1"></i>-
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ $reg['pdf_url'] }}"
                                                        class="btn btn-outline-danger" title="PDF" target="_blank">
                                                        <i class="ri-file-pdf-line"></i>
                                                    </a>
                                                    @if($reg['ver_url'])
                                                        <a href="{{ $reg['ver_url'] }}"
                                                            class="btn btn-outline-info" title="Ver">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="avatar-xl mx-auto mb-3">
                                                    <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                                        <i class="ri-inbox-line"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">No hay recibos en el período seleccionado</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div id="noResultsRecibos" class="text-center py-5 d-none">
                            <div class="avatar-xl mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                    <i class="ri-search-line"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">Sin resultados</h5>
                        </div>
                    </div>
                    @if ($registros->hasPages())
                        <div class="card-footer bg-white border-top py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-3">
                                        <i class="ri-file-list-3-line text-primary"></i>
                                    </div>
                                    <span class="text-muted" style="font-size:0.82rem;">
                                        Mostrando <span class="fw-bold text-primary">{{ $registros->firstItem() }}</span> - <span class="fw-bold text-primary">{{ $registros->lastItem() }}</span> de <span class="fw-bold text-primary">{{ $registros->total() }}</span> registros
                                    </span>
                                </div>
                                <div>
                                    {{ $registros->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }

        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }

        .avatar-lg,
        .avatar-sm,
        .avatar-xs {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body canvas {
            max-height: 100%;
            width: 100% !important;
        }

        /* Tabla Recibos Responsive */
        .tabla-recibos {
            max-height: 65vh;
        }

        #recibosTable {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 0;
            font-size: 0.82rem;
        }

        #recibosTable .col-recibo { width: 10%; }
        #recibosTable .col-fecha { width: 9%; }
        #recibosTable .col-cliente { width: 14%; }
        #recibosTable .col-detalle { width: 16%; }
        #recibosTable .col-tipo { width: 9%; }
        #recibosTable .col-monto { width: 16%; }
        #recibosTable .col-trabajador { width: 12%; }
        #recibosTable .col-acciones { width: 8%; }

        #recibosTable thead th {
            font-weight: 600;
            color: #495057;
            background: linear-gradient(180deg, #f8f9fa 0%, #f1f5f9 100%);
            border: none;
            border-bottom: 2px solid #e9ecef;
            padding: 8px 5px;
            font-size: 0.72rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #recibosTable thead th i {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        #recibosTable tbody td {
            padding: 6px 5px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        #recibosTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Search box */
        .search-box input {
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            font-size: 0.82rem;
        }

        .search-box input:focus {
            border-color: #405189;
            box-shadow: 0 0 0 4px rgba(64, 81, 137, 0.15);
        }

        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: 1px solid #e2e8f0;
            color: #405189;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
            border-color: #405189;
        }

        /* Responsive breakpoints */
        @media (max-width: 1400px) {
            #recibosTable { font-size: 0.78rem; }
            #recibosTable .col-cliente { width: 13%; }
            #recibosTable .col-detalle { width: 17%; }
        }

        @media (max-width: 1200px) {
            .tabla-recibos { max-height: 60vh; }
            #recibosTable { font-size: 0.75rem; }
            #recibosTable .col-cliente { width: 12%; }
            #recibosTable .col-detalle { width: 19%; }
        }

        @media (max-width: 992px) {
            .tabla-recibos { max-height: 55vh; overflow-x: auto; }
            #recibosTable { font-size: 0.72rem; }
            #recibosTable thead th { font-size: 0.65rem; padding: 6px 4px; }
            #recibosTable tbody td { padding: 5px 4px; }
            #recibosTable .col-recibo { width: 9%; }
            #recibosTable .col-fecha { width: 8%; }
            #recibosTable .col-cliente { width: 11%; }
            #recibosTable .col-detalle { width: 20%; }
            #recibosTable .col-tipo { width: 9%; }
            #recibosTable .col-monto { width: 18%; }
        }

        @media (max-width: 768px) {
            .tabla-recibos { max-height: 50vh; }
            #recibosTable { font-size: 0.7rem; }
            #recibosTable thead th { font-size: 0.6rem; }
            #recibosTable .col-recibo { width: 8%; }
            #recibosTable .col-fecha { width: 7%; }
            #recibosTable .col-cliente { width: 10%; }
            #recibosTable .col-detalle { width: 22%; }
            #recibosTable .col-tipo { width: 8%; }
            #recibosTable .col-monto { width: 20%; }
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Gráfico de Ingresos por Día
        const ctxIngresos = document.getElementById('chartIngresosDia').getContext('2d');
        new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $ingresosPorDia->pluck('fecha')->map(function ($date) {
                        return \Carbon\Carbon::parse($date)->format('d/m');
                    }),
                ) !!},
                datasets: [{
                    label: 'Ingresos (Bs.)',
                    data: {!! json_encode($ingresosPorDia->pluck('total')) !!},
                    borderColor: '#405189',
                    backgroundColor: 'rgba(64, 81, 137, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#405189',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: (context) => 'Bs. ' + context.parsed.y.toFixed(2)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => 'Bs. ' + value.toFixed(2)
                        }
                    }
                }
            }
        });

        // Gráfico de Pagos por Tipo (efectivo vs QR)
        const ctxTipo = document.getElementById('chartPagosTipo').getContext('2d');
        new Chart(ctxTipo, {
            type: 'doughnut',
            data: {
                labels: ['Efectivo', 'QR'],
                datasets: [{
                    data: [{{ $efectivoTotal }}, {{ $qrTotal }}],
                    backgroundColor: ['#ffc107', '#0dcaf0'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                let label = context.label || '';
                                let value = context.parsed;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: Bs. ${value.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Buscador en tiempo real para tabla de recibos
        $(document).ready(function() {
            let searchTimeout;
            $('#searchRecibos').on('input', function() {
                clearTimeout(searchTimeout);
                const term = $(this).val().toLowerCase().trim();
                let visibleRows = 0;

                searchTimeout = setTimeout(function() {
                    $('#tableBodyRecibos tr').each(function() {
                        const searchData = $(this).data('search') || '';
                        if (term === '' || searchData.includes(term)) {
                            $(this).show();
                            visibleRows++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (visibleRows === 0) {
                        $('#noResultsRecibos').removeClass('d-none');
                        $('#recibosTable').addClass('d-none');
                    } else {
                        $('#noResultsRecibos').addClass('d-none');
                        $('#recibosTable').removeClass('d-none');
                    }
                }, 200);
            });
        });
    </script>
@endpush
