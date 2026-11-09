@extends('admin.dashboard')
@section('title', 'Pagos a Trabajadores')

@push('style')
    <style>
        /* ===== Filter tabs ===== */
        .wk-tabs {
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            display: inline-flex !important;
            gap: 4px;
            flex-wrap: wrap;
        }
        .wk-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: .88rem;
            transition: all .2s ease;
            border: none;
            background: transparent;
        }
        .wk-tabs .nav-link:hover:not(.active) {
            background: rgba(180,83,9,.08);
            color: #b45309;
        }
        .wk-tabs .nav-link.active {
            background: linear-gradient(135deg, #1a2d4c 0%, #b45309 100%);
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(180,83,9,.25);
        }
        .wk-tabs .nav-link .badge { background: rgba(0,0,0,.08); color: inherit; }
        .wk-tabs .nav-link.active .badge { background: rgba(255,255,255,.25) !important; color: #fff; }

        .kpi-card { transition: transform .2s ease, box-shadow .2s ease; }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.08) !important; }

        .card-section-header {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 12px; margin-bottom: 16px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }
        .card-section-header .icon-wrap {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        }

        .table-modern thead th {
            background: #f8fafc;
            font-weight: 700; text-transform: uppercase;
            font-size: .72rem; letter-spacing: .5px; color: #475569;
            border-bottom: 2px solid #e2e8f0 !important; padding: 12px 14px;
        }
        .table-modern tbody td {
            padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle;
        }
        .table-modern tbody tr:hover { background: rgba(180,83,9,.03); }

        .avatar-mini {
            width: 36px; height: 36px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-weight: bold; font-size: .85rem;
            background: linear-gradient(135deg, #1a2d4c, #b45309);
            flex-shrink: 0;
        }
    </style>
@endpush

@section('admin')
    <div class="container-fluid">
        <style>
            body.dino-theme .page-title-box.wk-hero {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 50%, #6d28d9 130%) !important;
                border: 0 !important;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                padding: 22px 26px !important;
            }

            body.dino-theme .page-title-box.wk-hero h4 {
                color: #fff !important;
                font-family: 'DM Serif Display', Georgia, serif !important;
                font-weight: 400 !important;
                font-size: 28px !important;
            }

            body.dino-theme .page-title-box.wk-hero p,
            body.dino-theme .page-title-box.wk-hero .text-white-50,
            body.dino-theme .page-title-box.wk-hero .breadcrumb-item a,
            body.dino-theme .page-title-box.wk-hero .breadcrumb-item.active {
                color: rgba(255, 255, 255, .78) !important;
            }

            body.dino-theme .page-title-box.wk-hero .breadcrumb-item + .breadcrumb-item::before {
                color: rgba(255, 255, 255, .45) !important;
            }

            .wk-filters .card-body { padding: 18px 20px !important; }
            .wk-filters .form-label {
                color: var(--dino-muted) !important;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .06em;
            }
            .wk-filters button[type="submit"] {
                background: linear-gradient(135deg, #2f9e7a, #46b98f) !important;
                border: 0 !important;
                box-shadow: 0 8px 18px rgba(47, 158, 122, .22) !important;
            }

            .wk-stats .kpi-card {
                border-radius: 16px !important;
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .wk-stats .kpi-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 30px rgba(15, 59, 67, .10) !important;
            }
            .wk-stats .kpi-card .card-body { padding: 20px 22px !important; }
            .wk-stats .kpi-card h3 { letter-spacing: -.02em; }

            body.dino-theme .wk-tabs {
                background: var(--dino-surface-2) !important;
                border: 1px solid var(--dino-border);
                padding: 5px;
                border-radius: 12px;
                display: inline-flex;
                gap: 4px;
                flex-wrap: wrap;
                max-width: 100%;
            }
            body.dino-theme .wk-tabs .nav-link {
                color: var(--dino-muted) !important;
                font-weight: 700;
                border-radius: 9px;
                padding: 8px 16px;
                font-size: .8125rem;
                border: 0;
                background: transparent;
            }
            body.dino-theme .wk-tabs .nav-link.active {
                background: linear-gradient(135deg, #2f9e7a, #6fc2a3) !important;
                color: #fff !important;
            }
            body.dino-theme .wk-tabs .nav-link.active .badge {
                background: rgba(255, 255, 255, .25) !important;
                color: #fff !important;
            }

            .card-section-header {
                display: flex;
                align-items: center;
                gap: 12px;
                padding-bottom: 14px;
                margin-bottom: 16px;
                border-bottom: 1px solid var(--dino-border);
            }
            .card-section-header .icon-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border-radius: 11px;
                flex-shrink: 0;
            }
            .card-section-header h5 { font-size: 15px; letter-spacing: -.01em; }

            @media (max-width: 991.98px) {
                body.dino-theme .page-title-box.wk-hero { flex-direction: column; align-items: flex-start; }
                body.dino-theme .wk-tabs { width: 100%; }
                body.dino-theme .wk-tabs .nav-link { flex: 1 1 100%; text-align: center; }
            }

            @media (max-width: 767.98px) {
                body.dino-theme .page-title-box.wk-hero { padding: 18px 16px !important; }
                body.dino-theme .page-title-box.wk-hero h4 { font-size: 22px !important; }
                .wk-stats .kpi-card .card-body { padding: 16px 18px !important; }
                .wk-stats .kpi-card h3 { font-size: 1.45rem !important; }
            }

            [data-layout-mode="dark"] body.dino-theme .page-title-box.wk-hero {
                background: linear-gradient(135deg, #0b1b1e 0%, #173b35 55%, #4c1d95 135%) !important;
            }
        </style>

        {{-- ===== PAGE TITLE ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box wk-hero position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0" style="opacity:.08;font-size:14rem;line-height:1;transform:translate(20%,-20%);">
                        <i class="ri-team-line"></i>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                style="width:54px;height:54px;background:rgba(255,255,255,.18);">
                                <i class="ri-team-line text-white" style="font-size:1.7rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold text-white">Pagos a Trabajadores</h4>
                                <p class="text-white-50 mb-0 small">Estado de liquidación de salarios por mes y gestión</p>
                            </div>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2"
                            style="background:rgba(255,255,255,.18);">
                            <i class="ri-calendar-2-line text-white"></i>
                            <span class="fw-semibold text-white" style="font-size:.85rem;">Periodo:</span>
                            <span class="fw-bold text-white">{{ $mesLabel }}</span>
                        </div>
                    </div>
                    <div class="page-title-right position-relative">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item active text-white">Pagos a Trabajadores</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FILTRO MES / GESTIÓN ===== --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 wk-filters">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.pagosTrabajadores.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1 small">
                                <i class="ri-calendar-2-line me-1" style="color:#b45309;"></i>Mes
                            </label>
                            <select name="mes" class="form-select form-select-lg rounded-3" required>
                                @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                    <option value="{{ $i + 1 }}" {{ ($i + 1) === (int) $mesSeleccionado ? 'selected' : '' }}>{{ $mes }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1 small">
                                <i class="ri-calendar-event-line me-1" style="color:#b45309;"></i>Gestión
                            </label>
                            <select name="gestion" class="form-select form-select-lg rounded-3" required>
                                @foreach ($gestionesDisponibles as $g)
                                    <option value="{{ $g }}" {{ (int) $g === (int) $gestionSeleccionada ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-lg w-100 rounded-3 text-white"
                                style="background:linear-gradient(135deg,#1a2d4c,#b45309);border:none;box-shadow:0 6px 16px rgba(180,83,9,.25);">
                                <i class="ri-search-2-line me-1"></i> Aplicar
                            </button>
                        </div>
                        <div class="col-md-3 text-end">
                            <small class="text-muted">
                                <strong>{{ $resumen->count() }}</strong> trabajador(es) con sesiones realizadas
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== KPI CARDS ===== --}}
        <div class="row g-3 mb-4 wk-stats">
            <div class="col-xl-4 col-md-6">
                <div class="card kpi-card border-0 shadow-sm rounded-4 h-100"
                    style="border-left:4px solid #b45309 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="background:linear-gradient(135deg,#b45309,#f59e0b); width:46px; height:46px; border-radius:12px; box-shadow:0 6px 14px rgba(180,83,9,.3);">
                                    <i class="ri-time-line fs-3 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-uppercase fw-bold mb-0" style="color:#94a3b8; font-size:.7rem; letter-spacing:.6px;">Por pagar</p>
                                    <small class="text-muted">{{ $cantTrabajadoresPend }} trabajador(es) pendientes</small>
                                </div>
                            </div>
                        </div>
                        <h3 class="mb-0 fw-bold" style="color:#b45309; font-size:1.8rem;">
                            {{ number_format($totalPagableGlobal, 2, ',', '.') }} <small class="fs-6 text-muted fw-semibold">Bs</small>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card kpi-card border-0 shadow-sm rounded-4 h-100"
                    style="border-left:4px solid #15803d !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="background:linear-gradient(135deg,#15803d,#22c55e); width:46px; height:46px; border-radius:12px; box-shadow:0 6px 14px rgba(21,128,61,.3);">
                                    <i class="ri-check-double-line fs-3 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-uppercase fw-bold mb-0" style="color:#94a3b8; font-size:.7rem; letter-spacing:.6px;">Ya pagado</p>
                                    <small class="text-muted">{{ $cantTrabajadoresPag }} trabajador(es) liquidados</small>
                                </div>
                            </div>
                        </div>
                        <h3 class="mb-0 fw-bold" style="color:#15803d; font-size:1.8rem;">
                            {{ number_format($totalPagadoGlobal, 2, ',', '.') }} <small class="fs-6 text-muted fw-semibold">Bs</small>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="card kpi-card border-0 shadow-sm rounded-4 h-100"
                    style="border-left:4px solid #6b7280 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="background:linear-gradient(135deg,#475569,#6b7280); width:46px; height:46px; border-radius:12px;">
                                    <i class="ri-alert-line fs-3 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-uppercase fw-bold mb-0" style="color:#94a3b8; font-size:.7rem; letter-spacing:.6px;">Sin informe</p>
                                    <small class="text-muted">Sesiones realizadas sin informe registrado</small>
                                </div>
                            </div>
                        </div>
                        <h3 class="mb-0 fw-bold" style="color:#475569; font-size:1.8rem;">
                            {{ number_format($totalSinInformeGlobal, 2, ',', '.') }} <small class="fs-6 text-muted fw-semibold">Bs</small>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TABS ===== --}}
        @php
            $trabajadoresPendientes = $resumen->filter(fn($r) => $r['cnt_pagables'] > 0 || $r['cnt_pendientes'] > 0)->values();
            $trabajadoresPagados    = $resumen->filter(fn($r) => $r['cnt_pagadas']  > 0)->values();
        @endphp

        <ul class="nav nav-pills wk-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-pendientes" type="button" role="tab">
                    <i class="ri-time-line me-1"></i>Por pagar
                    <span class="badge rounded-pill ms-1" style="font-size:.7rem;">{{ $trabajadoresPendientes->count() }} trab.</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-pagados" type="button" role="tab">
                    <i class="ri-check-double-line me-1"></i>Ya pagados
                    <span class="badge rounded-pill ms-1" style="font-size:.7rem;">{{ $trabajadoresPagados->count() }} trab.</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            {{-- ===== TAB PENDIENTES ===== --}}
            <div class="tab-pane fade show active" id="tab-pendientes" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="card-section-header">
                            <div class="icon-wrap" style="background:rgba(180,83,9,.1);color:#b45309;">
                                <i class="ri-time-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-0" style="color:#b45309;">Trabajadores por pagar</h5>
                                <small class="text-muted">Con sesiones pagables o sin informe en {{ $mesLabel }}</small>
                            </div>
                            <span class="badge rounded-pill px-3 py-2"
                                style="background:rgba(180,83,9,.1);color:#b45309;border:1px solid rgba(180,83,9,.25);">
                                {{ number_format($totalPagableGlobal + $totalSinInformeGlobal, 2, ',', '.') }} Bs.
                            </span>
                        </div>

                        @if ($trabajadoresPendientes->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ri-check-double-line fs-1 opacity-50" style="color:#15803d;"></i>
                                <p class="mt-2 mb-0">No hay trabajadores con pagos pendientes en este periodo</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-modern align-middle mb-0 table-card-responsive">
                                    <thead>
                                        <tr>
                                            <th>Trabajador</th>
                                            <th class="text-center">Sesiones</th>
                                            <th class="text-center">Tarifas</th>
                                            <th class="text-end">Por pagar</th>
                                            <th class="text-end">Sin informe</th>
                                            <th class="text-end" style="color:#b45309 !important;">Total mes</th>
                                            <th class="text-center" style="width:120px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trabajadoresPendientes as $r)
                                            @php $t = $r['trabajador']; @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-mini">{{ strtoupper(substr($t->persona->nombres ?? 'T', 0, 1)) }}</div>
                                                        <div>
                                                            <div class="fw-semibold" style="color:#1a2d4c;">{{ $t->persona->nombres }} {{ $t->persona->apellido_paterno }}</div>
                                                            <small class="text-muted">{{ $t->cargo->nombre ?? 'Sin cargo' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <span class="badge rounded-pill" style="background:rgba(180,83,9,.1);color:#b45309;font-size:.7rem;" title="Pagables">
                                                            <i class="ri-time-line me-1"></i>{{ $r['cnt_pagables'] }}
                                                        </span>
                                                        @if ($r['cnt_pendientes'] > 0)
                                                            <span class="badge rounded-pill" style="background:rgba(108,117,125,.12);color:#6c757d;font-size:.7rem;" title="Sin informe">
                                                                <i class="ri-alert-line me-1"></i>{{ $r['cnt_pendientes'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        @if ($r['cnt_normal'] > 0)
                                                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#0d9488;font-size:.65rem;"><i class="ri-sun-line me-1"></i>{{ $r['cnt_normal'] }}</span>
                                                        @endif
                                                        @if ($r['cnt_especial'] > 0)
                                                            <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.65rem;"><i class="ri-star-fill me-1"></i>{{ $r['cnt_especial'] }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-end fw-semibold" style="color:#b45309;">
                                                    {{ number_format($r['total_pagable'], 2, ',', '.') }} Bs.
                                                </td>
                                                <td class="text-end" style="color:#6b7280;">
                                                    {{ $r['total_sin_informe'] > 0 ? number_format($r['total_sin_informe'], 2, ',', '.') . ' Bs.' : '—' }}
                                                </td>
                                                <td class="text-end fw-bold" style="color:#b45309;font-size:.95rem;">
                                                    {{ number_format($r['total_pagable'] + $r['total_sin_informe'], 2, ',', '.') }} Bs.
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.trabajadores.ver', $t->id) }}"
                                                        class="btn btn-sm rounded-pill"
                                                        style="background:linear-gradient(135deg,#b45309,#f59e0b);color:#fff;border:none;font-size:.7rem;"
                                                        title="Ir a liquidación">
                                                        <i class="ri-money-dollar-circle-line me-1"></i>Liquidar
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#f8fafc;">
                                            <td colspan="3" class="fw-bold" style="color:#1a2d4c;">TOTALES</td>
                                            <td class="text-end fw-bold" style="color:#b45309;">{{ number_format($totalPagableGlobal, 2, ',', '.') }} Bs.</td>
                                            <td class="text-end fw-bold" style="color:#6b7280;">{{ number_format($totalSinInformeGlobal, 2, ',', '.') }} Bs.</td>
                                            <td class="text-end fw-bold" style="color:#b45309;font-size:1rem;">{{ number_format($totalPagableGlobal + $totalSinInformeGlobal, 2, ',', '.') }} Bs.</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===== TAB PAGADOS ===== --}}
            <div class="tab-pane fade" id="tab-pagados" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="card-section-header">
                            <div class="icon-wrap" style="background:rgba(21,128,61,.1);color:#15803d;">
                                <i class="ri-check-double-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-0" style="color:#15803d;">Trabajadores ya pagados</h5>
                                <small class="text-muted">Salarios liquidados en {{ $mesLabel }}</small>
                            </div>
                            <span class="badge rounded-pill px-3 py-2"
                                style="background:rgba(21,128,61,.1);color:#15803d;border:1px solid rgba(21,128,61,.25);">
                                {{ number_format($totalPagadoGlobal, 2, ',', '.') }} Bs.
                            </span>
                        </div>

                        @if ($trabajadoresPagados->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ri-inbox-line fs-1 opacity-50"></i>
                                <p class="mt-2 mb-0">Aún no se ha liquidado ningún trabajador en este periodo</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-modern align-middle mb-0 table-card-responsive">
                                    <thead>
                                        <tr>
                                            <th>Trabajador</th>
                                            <th class="text-center">Sesiones pagadas</th>
                                            <th class="text-center">Tarifas</th>
                                            <th class="text-end">Monto pagado</th>
                                            <th>Pago registrado</th>
                                            <th class="text-center" style="width:200px;">Reportes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trabajadoresPagados as $r)
                                            @php $t = $r['trabajador']; @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-mini" style="background:linear-gradient(135deg,#15803d,#22c55e);">
                                                            {{ strtoupper(substr($t->persona->nombres ?? 'T', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold" style="color:#1a2d4c;">{{ $t->persona->nombres }} {{ $t->persona->apellido_paterno }}</div>
                                                            <small class="text-muted">{{ $t->cargo->nombre ?? 'Sin cargo' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill" style="background:rgba(21,128,61,.1);color:#15803d;font-size:.75rem;">
                                                        <i class="ri-checkbox-circle-line me-1"></i>{{ $r['cnt_pagadas'] }} sesión(es)
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        @if ($r['cnt_normal'] > 0)
                                                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#0d9488;font-size:.65rem;"><i class="ri-sun-line me-1"></i>{{ $r['cnt_normal'] }}</span>
                                                        @endif
                                                        @if ($r['cnt_especial'] > 0)
                                                            <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.65rem;"><i class="ri-star-fill me-1"></i>{{ $r['cnt_especial'] }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold" style="color:#15803d;font-size:.95rem;">
                                                    {{ number_format($r['total_pagado'], 2, ',', '.') }} Bs.
                                                </td>
                                                <td>
                                                    @if ($r['gasto'])
                                                        <div class="d-flex flex-column">
                                                            <small style="color:#1a2d4c;font-weight:600;">{{ $r['gasto']['descripcion'] ?? '—' }}</small>
                                                            <small class="text-muted"><i class="ri-calendar-event-line me-1"></i>{{ $r['gasto']['fecha'] }}</small>
                                                        </div>
                                                    @else
                                                        <small class="text-muted">—</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <a href="{{ route('admin.trabajadores.salarioPagadoPdf', $t->id) }}?{{ http_build_query(['año' => $gestionSeleccionada, 'mes' => $mesSeleccionado, 'tipo' => 'general']) }}"
                                                            target="_blank" rel="noopener"
                                                            class="btn btn-sm rounded-pill px-2"
                                                            style="background:#1a2d4c;color:#fff;font-size:.7rem;border:none;"
                                                            title="PDF General">
                                                            <i class="ri-file-list-3-line"></i> General
                                                        </a>
                                                        <a href="{{ route('admin.trabajadores.salarioPagadoPdf', $t->id) }}?{{ http_build_query(['año' => $gestionSeleccionada, 'mes' => $mesSeleccionado, 'tipo' => 'detallado']) }}"
                                                            target="_blank" rel="noopener"
                                                            class="btn btn-sm rounded-pill px-2"
                                                            style="background:#b45309;color:#fff;font-size:.7rem;border:none;"
                                                            title="PDF Detallado">
                                                            <i class="ri-file-text-line"></i> Detallado
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#f8fafc;">
                                            <td colspan="3" class="fw-bold" style="color:#1a2d4c;">TOTAL PAGADO</td>
                                            <td class="text-end fw-bold" style="color:#15803d;font-size:1rem;">{{ number_format($totalPagadoGlobal, 2, ',', '.') }} Bs.</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
