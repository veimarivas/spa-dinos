@extends('admin.dashboard')

@push('style')
    <style>
        /* ===== Filter tabs ===== */
        .filter-tabs {
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            display: inline-flex !important;
            gap: 4px;
            flex-wrap: nowrap;
        }
        .filter-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: .88rem;
            transition: all .2s ease;
            border: none;
            background: transparent;
        }
        .filter-tabs .nav-link:hover:not(.active) {
            background: rgba(13,148,136,.08);
            color: var(--turquoise-dark);
        }
        .filter-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--turquoise-dark) 0%, var(--dino-green-dark) 100%);
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(13,148,136,.25);
        }

        /* ===== KPI cards ===== */
        .kpi-card {
            transition: transform .2s ease, box-shadow .2s ease;
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,.08) !important;
        }
        .kpi-card .kpi-watermark {
            position: absolute;
            bottom: -20px;
            right: -10px;
            opacity: .06;
            font-size: 7rem;
            line-height: 1;
            pointer-events: none;
        }

        /* ===== Section card headers ===== */
        .card-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }
        .card-section-header .icon-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        /* ===== Tablas ===== */
        .table-modern thead th {
            background: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: .72rem;
            letter-spacing: .5px;
            color: #475569;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 12px 14px;
        }
        .table-modern tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-modern tbody tr:hover {
            background: rgba(13,148,136,.03);
        }
    </style>
@endpush

@section('admin')
    <div class="container-fluid">
        <style>
            /* ============================================================
               Finanzas · refinamiento A1
               ============================================================ */
            body.dino-theme .page-title-box.finanzas-hero {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 52%, #6d28d9 135%) !important;
                border: 0 !important;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                flex-wrap: wrap;
                padding: 22px 26px !important;
            }

            body.dino-theme .page-title-box.finanzas-hero h4 {
                color: #fff !important;
                font-family: 'DM Serif Display', Georgia, serif !important;
                font-weight: 400 !important;
                font-size: 28px !important;
            }

            body.dino-theme .page-title-box.finanzas-hero p,
            body.dino-theme .page-title-box.finanzas-hero .text-white-50,
            body.dino-theme .page-title-box.finanzas-hero .breadcrumb-item a,
            body.dino-theme .page-title-box.finanzas-hero .breadcrumb-item.active {
                color: rgba(255, 255, 255, .78) !important;
            }

            body.dino-theme .page-title-box.finanzas-hero .breadcrumb-item + .breadcrumb-item::before {
                color: rgba(255, 255, 255, .45) !important;
            }

            .finanzas-filters .card-body { padding: 18px 20px !important; }
            .finanzas-filters .form-label {
                color: var(--dino-muted) !important;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            #btnEsteMes {
                background: var(--dino-mint-soft) !important;
                color: #1d7d5f !important;
                border: 1px solid #c9eeda !important;
            }

            #btnEsteAno {
                background: var(--dino-lilac-soft) !important;
                color: #6d28d9 !important;
                border: 1px solid #e9d5ff !important;
            }

            #btnFiltrarMesGestion,
            #btnFiltrarRango {
                background: linear-gradient(135deg, #2f9e7a, #46b98f) !important;
                border: 0 !important;
                box-shadow: 0 8px 18px rgba(47, 158, 122, .22) !important;
            }

            body.dino-theme .filter-tabs {
                background: var(--dino-surface-2) !important;
                border: 1px solid var(--dino-border);
                padding: 5px;
                border-radius: 12px;
                display: inline-flex;
                gap: 4px;
                flex-wrap: nowrap;
                max-width: 100%;
                overflow-x: auto;
            }

            body.dino-theme .filter-tabs .nav-link {
                color: var(--dino-muted) !important;
                font-weight: 700;
                border-radius: 9px;
                padding: 8px 16px;
                font-size: .8125rem;
                border: 0;
                background: transparent;
                white-space: nowrap;
            }

            body.dino-theme .filter-tabs .nav-link.active {
                background: linear-gradient(135deg, #2f9e7a, #6fc2a3) !important;
                color: #fff !important;
                box-shadow: none !important;
            }

            .kpi-card {
                transition: transform .2s ease, box-shadow .2s ease;
                position: relative;
                overflow: hidden;
                border-radius: 16px !important;
            }

            .kpi-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 30px rgba(15, 59, 67, .10) !important;
            }

            .kpi-card .kpi-watermark {
                position: absolute;
                bottom: -20px;
                right: -10px;
                opacity: .06;
                font-size: 7rem;
                line-height: 1;
                pointer-events: none;
            }

            .kpi-card .card-body { padding: 20px 22px !important; }
            .kpi-card h3 { letter-spacing: -.02em; }
            .kpi-card .progress { height: 6px !important; border-radius: 99px; }

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

            .table-modern thead th {
                background: var(--dino-mint-soft);
                font-weight: 800;
                text-transform: uppercase;
                font-size: .7rem;
                letter-spacing: .05em;
                color: #2b4a44;
                border-bottom: 1px solid #d9efe5 !important;
                padding: 12px 14px;
            }

            .table-modern tbody td {
                padding: 11px 14px;
                border-bottom: 1px solid var(--dino-border);
            }

            .table-modern tbody tr:hover { background: rgba(111, 194, 163, .06); }

            @media (max-width: 991.98px) {
                body.dino-theme .page-title-box.finanzas-hero { flex-direction: column; align-items: flex-start; }
                .card-section-header { flex-wrap: wrap; }
            }

            @media (max-width: 767.98px) {
                body.dino-theme .page-title-box.finanzas-hero { padding: 18px 16px !important; }
                body.dino-theme .page-title-box.finanzas-hero h4 { font-size: 22px !important; }
                body.dino-theme .filter-tabs { width: 100%; flex-wrap: wrap; overflow-x: visible; }
                body.dino-theme .filter-tabs .nav-link { flex: 1 1 100%; text-align: center; }
                .kpi-card .card-body { padding: 16px 18px !important; }
                .kpi-card h3 { font-size: 1.45rem !important; }
            }

            [data-layout-mode="dark"] body.dino-theme .page-title-box.finanzas-hero {
                background: linear-gradient(135deg, #0b1b1e 0%, #173b35 55%, #4c1d95 135%) !important;
            }

            [data-layout-mode="dark"] .table-modern tbody td { border-bottom-color: var(--dino-border) !important; }
            [data-layout-mode="dark"] .table-modern thead th { background: #173b35 !important; color: #bfe9da !important; }

            [data-layout-mode="dark"] body.dino-theme .kpi-balance.is-positive {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 55%, #4c1d95 135%) !important;
            }

            [data-layout-mode="dark"] body.dino-theme .kpi-balance.is-negative {
                background: linear-gradient(135deg, #3b1d1a 0%, #7a2e26 60%, #c05645 135%) !important;
            }
        </style>

        @php
            $mesesEsLargoView = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

            // Datos para el donut chart (agrupado por categoria padre)
            $gastosChart = $gastosPorCategoria
                ->groupBy('parent_nombre')
                ->map(fn($items) => (object) [
                    'nombre' => $items->first()->parent_nombre,
                    'total'  => $items->sum('total'),
                ])
                ->sortByDesc('total')
                ->values();

            // Organizar tabla: padres primero, hijos anidados debajo
            $padres = $gastosPorCategoria->filter(fn($c) => is_null($c->parent_id));
            $hijos  = $gastosPorCategoria->filter(fn($c) => !is_null($c->parent_id));
            $nombresPadres = $padres->pluck('nombre');
            $gastosTabla = collect();
            foreach ($padres as $p) {
                $gastosTabla->push((object)['tipo' => 'padre', 'nombre' => $p->nombre, 'total' => $p->total]);
                $hijosDelPadre = $hijos->where('parent_nombre', $p->nombre);
                foreach ($hijosDelPadre as $h) {
                    $gastosTabla->push((object)['tipo' => 'hijo', 'nombre' => $h->nombre, 'total' => $h->total, 'padre' => $p->nombre]);
                }
            }
            // Hijos cuyo padre no aparece (sin gastos directos el padre)
            $hijosRestantes = $hijos->reject(fn($h) => $nombresPadres->contains($h->parent_nombre));
            foreach ($hijosRestantes as $h) {
                $gastosTabla->push((object)['tipo' => 'hijo', 'nombre' => $h->nombre, 'total' => $h->total, 'padre' => $h->parent_nombre]);
            }

            // Mapa de hijos por padre para el drill-down del donut chart
            $gastosChildrenMap = [];
            foreach ($gastosPorCategoria as $item) {
                if (!is_null($item->parent_id)) {
                    $parentName = $item->parent_nombre;
                    if (!isset($gastosChildrenMap[$parentName])) {
                        $gastosChildrenMap[$parentName] = [];
                    }
                    $gastosChildrenMap[$parentName][] = [
                        'nombre' => $item->nombre,
                        'total'  => (float) $item->total,
                    ];
                }
            }

            if ($mesSeleccionado && $gestionSeleccionada) {
                $periodoLabel = $mesesEsLargoView[$mesSeleccionado - 1] . ' ' . $gestionSeleccionada;
            } else {
                $periodoLabel = \Carbon\Carbon::parse($fechaDesde)->format('d/m/Y') . ' — ' . \Carbon\Carbon::parse($fechaHasta)->format('d/m/Y');
            }
            $diasPeriodo = \Carbon\Carbon::parse($fechaDesde)->diffInDays(\Carbon\Carbon::parse($fechaHasta)) + 1;
        @endphp

        {{-- ===== PAGE TITLE ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box finanzas-hero position-relative overflow-hidden">
                    {{-- Decoración sutil --}}
                    <div class="position-absolute top-0 end-0" style="opacity:.08;font-size:14rem;line-height:1;transform:translate(20%,-20%);">
                        <i class="ri-line-chart-line"></i>
                    </div>

                    <div class="position-relative">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                style="width:54px;height:54px;background:rgba(255,255,255,.18);backdrop-filter:blur(8px);">
                                <i class="ri-line-chart-line text-white" style="font-size:1.7rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold text-white">Resumen Financiero</h4>
                                <p class="text-white-50 mb-0 small">Vista general de ingresos y gastos</p>
                            </div>
                        </div>
                        {{-- Chip de periodo --}}
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2"
                            style="background:rgba(255,255,255,.18);backdrop-filter:blur(8px);">
                            <i class="ri-calendar-2-line text-white"></i>
                            <span class="fw-semibold text-white" style="font-size:.85rem;">Periodo:</span>
                            <span class="fw-bold text-white">{{ $periodoLabel }}</span>
                            <span class="badge rounded-pill" style="background:rgba(255,255,255,.25);color:#fff;font-size:.7rem;">{{ $diasPeriodo }} día(s)</span>
                        </div>
                    </div>

                    <div class="page-title-right position-relative">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                        class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item active text-white">Finanzas</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DATE FILTER BAR ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 finanzas-filters">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('admin.finanzas.index') }}" id="formFiltroFinanzas">
                            {{-- Header del filtro --}}
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-3"
                                        style="width:36px;height:36px;background:rgba(13,148,136,.1);color:var(--turquoise-dark);">
                                        <i class="ri-filter-3-line fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="color:var(--turquoise-dark);">Filtros del periodo</h6>
                                        <small class="text-muted">Elige por mes/gestión o por rango de fechas</small>
                                    </div>
                                </div>
                                {{-- Atajos rápidos --}}
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm rounded-pill px-3" id="btnEsteMes"
                                        style="background:rgba(82,183,136,.1);color:var(--dino-green-dark);border:1px solid rgba(82,183,136,.3);">
                                        <i class="ri-calendar-check-line me-1"></i>Este mes
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3" id="btnEsteAno"
                                        style="background:rgba(13,148,136,.1);color:var(--turquoise-dark);border:1px solid rgba(13,148,136,.3);">
                                        <i class="ri-calendar-2-line me-1"></i>Este año
                                    </button>
                                </div>
                            </div>

                            {{-- Tabs del filtro --}}
                            @php
                                $modoFiltro = ($mesSeleccionado && $gestionSeleccionada) ? 'mes' : 'rango';
                            @endphp
                            <ul class="nav nav-pills filter-tabs mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $modoFiltro === 'mes' ? 'active' : '' }}" type="button"
                                        data-bs-toggle="pill" data-bs-target="#filtroPorMes" role="tab">
                                        <i class="ri-calendar-2-line me-1"></i>Por mes / gestión
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $modoFiltro === 'rango' ? 'active' : '' }}" type="button"
                                        data-bs-toggle="pill" data-bs-target="#filtroPorRango" role="tab">
                                        <i class="ri-calendar-event-line me-1"></i>Por rango de fechas
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- Pestaña Mes/Gestión --}}
                                <div class="tab-pane fade {{ $modoFiltro === 'mes' ? 'show active' : '' }}" id="filtroPorMes" role="tabpanel">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-1 small">
                                                <i class="ri-calendar-2-line me-1" style="color: var(--turquoise-dark);"></i>Mes
                                            </label>
                                            <select name="mes" id="selectMes" class="form-select form-select-lg rounded-3">
                                                <option value="">— Seleccione mes —</option>
                                                @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                                    <option value="{{ $i + 1 }}" {{ (int)($mesSeleccionado ?? 0) === ($i + 1) ? 'selected' : '' }}>{{ $mes }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-1 small">
                                                <i class="ri-calendar-event-line me-1" style="color: var(--turquoise-dark);"></i>Gestión
                                            </label>
                                            <select name="gestion" id="selectGestion" class="form-select form-select-lg rounded-3">
                                                <option value="">— Seleccione año —</option>
                                                @foreach ($gestionesDisponibles as $g)
                                                    <option value="{{ $g }}" {{ (int)($gestionSeleccionada ?? 0) === $g ? 'selected' : '' }}>{{ $g }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-lg w-100 rounded-3 text-white" id="btnFiltrarMesGestion"
                                                style="background:linear-gradient(135deg,var(--turquoise-dark),var(--dino-green-dark));border:none;box-shadow:0 6px 16px rgba(13,148,136,.25);">
                                                <i class="ri-search-2-line me-1"></i> Aplicar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pestaña Rango de fechas --}}
                                <div class="tab-pane fade {{ $modoFiltro === 'rango' ? 'show active' : '' }}" id="filtroPorRango" role="tabpanel">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-1 small">
                                                <i class="ri-calendar-line me-1" style="color: var(--dino-green-dark);"></i>Desde
                                            </label>
                                            <input type="date" name="fecha_desde" id="inputFechaDesde"
                                                class="form-control form-control-lg rounded-3" value="{{ $fechaDesde }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-1 small">
                                                <i class="ri-calendar-line me-1" style="color: var(--dino-green-dark);"></i>Hasta
                                            </label>
                                            <input type="date" name="fecha_hasta" id="inputFechaHasta"
                                                class="form-control form-control-lg rounded-3" value="{{ $fechaHasta }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3" id="btnFiltrarRango"
                                                style="background:linear-gradient(135deg,#52B788,var(--dino-green-dark));border:none;box-shadow:0 6px 16px rgba(82,183,136,.25);">
                                                <i class="ri-filter-3-line me-1"></i> Aplicar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
 
         {{-- ===== SUMMARY CARDS ROW ===== --}}
         @php
            $totalCalc = max($totalIngresos, $totalGastos, 1);
            $pctIngresos = ($totalIngresos / $totalCalc) * 100;
            $pctGastos   = ($totalGastos / $totalCalc) * 100;
         @endphp
         <div class="row mb-4 g-3">
             {{-- Total Ingresos --}}
             <div class="col-xl-4 col-md-6">
                 <div class="card kpi-card border-0 shadow-sm rounded-4 h-100"
                     style="background:#fff; border-left:4px solid var(--dino-green) !important;">
                     <i class="ri-arrow-up-circle-line kpi-watermark" style="color:var(--dino-green);"></i>
                     <div class="card-body p-4 position-relative">
                         <div class="d-flex align-items-center justify-content-between mb-3">
                             <div class="d-flex align-items-center gap-3">
                                 <div class="d-flex align-items-center justify-content-center"
                                     style="background:linear-gradient(135deg, var(--dino-green), var(--turquoise)); width:48px; height:48px; border-radius:12px; box-shadow:0 6px 14px rgba(82,183,136,.3);">
                                     <i class="ri-arrow-up-circle-line fs-3 text-white"></i>
                                 </div>
                                 <div>
                                     <p class="text-uppercase fw-bold mb-0" style="color:#94a3b8; font-size:.7rem; letter-spacing:.6px;">Total Ingresos</p>
                                     <small class="text-muted">Suscripciones + Ventas</small>
                                 </div>
                             </div>
                         </div>
                         <h3 class="mb-2 fw-bold" style="color:var(--dino-green-dark); font-size:1.85rem;">
                             {{ number_format($totalIngresos, 2, ',', '.') }} <small class="fs-6 text-muted fw-semibold">Bs</small>
                         </h3>
                         <div class="progress" style="height:5px; border-radius:4px; background:rgba(82,183,136,.1);">
                             <div class="progress-bar" role="progressbar"
                                  style="width:{{ $pctIngresos }}%; background:linear-gradient(90deg, var(--turquoise), var(--dino-green));"></div>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Total Gastos --}}
             <div class="col-xl-4 col-md-6">
                 <div class="card kpi-card border-0 shadow-sm rounded-4 h-100"
                     style="background:#fff; border-left:4px solid var(--coral-dark) !important;">
                     <i class="ri-arrow-down-circle-line kpi-watermark" style="color:var(--coral-dark);"></i>
                     <div class="card-body p-4 position-relative">
                         <div class="d-flex align-items-center justify-content-between mb-3">
                             <div class="d-flex align-items-center gap-3">
                                 <div class="d-flex align-items-center justify-content-center"
                                     style="background:linear-gradient(135deg, var(--coral-spot), var(--coral-dark)); width:48px; height:48px; border-radius:12px; box-shadow:0 6px 14px rgba(240,101,72,.3);">
                                     <i class="ri-arrow-down-circle-line fs-3 text-white"></i>
                                 </div>
                                 <div>
                                     <p class="text-uppercase fw-bold mb-0" style="color:#94a3b8; font-size:.7rem; letter-spacing:.6px;">Total Gastos</p>
                                     <small class="text-muted">Operativos + Salarios</small>
                                 </div>
                             </div>
                         </div>
                         <h3 class="mb-2 fw-bold" style="color:var(--coral-dark); font-size:1.85rem;">
                             {{ number_format($totalGastos, 2, ',', '.') }} <small class="fs-6 text-muted fw-semibold">Bs</small>
                         </h3>
                         <div class="progress" style="height:5px; border-radius:4px; background:rgba(240,101,72,.1);">
                             <div class="progress-bar" role="progressbar"
                                  style="width:{{ $pctGastos }}%; background:linear-gradient(90deg, var(--coral-spot), var(--coral-dark));"></div>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Balance --}}
             @php
                $isPositive = $balance >= 0;
                $balColor = $isPositive ? 'var(--dino-green-dark)' : 'var(--coral-dark)';
                $balGradient = $isPositive ? 'var(--turquoise), var(--dino-green)' : 'var(--coral-spot), var(--coral-dark)';
                $balIcon = $isPositive ? 'ri-trending-up-line' : 'ri-trending-down-line';
                $balShadow = $isPositive ? 'rgba(13,148,136,.3)' : 'rgba(240,101,72,.3)';
             @endphp
             <div class="col-xl-4 col-md-12">
                 <div class="card kpi-card kpi-balance {{ $isPositive ? 'is-positive' : 'is-negative' }} border-0 shadow-sm rounded-4 h-100"
                     style="background:linear-gradient(135deg,{{ $balGradient }}); color:#fff;">
                     <i class="ri-scales-3-line kpi-watermark" style="color:#fff;"></i>
                     <div class="card-body p-4 position-relative">
                         <div class="d-flex align-items-center justify-content-between mb-3">
                             <div class="d-flex align-items-center gap-3">
                                 <div class="d-flex align-items-center justify-content-center"
                                     style="background:rgba(255,255,255,.2); width:48px; height:48px; border-radius:12px; backdrop-filter:blur(8px);">
                                     <i class="ri-scales-3-line fs-3 text-white"></i>
                                 </div>
                                 <div>
                                     <p class="text-uppercase fw-bold mb-0 text-white" style="font-size:.7rem; letter-spacing:.6px; opacity:.85;">Balance</p>
                                     <small class="text-white-50">{{ $isPositive ? 'Resultado positivo' : 'Resultado negativo' }}</small>
                                 </div>
                             </div>
                             <span class="badge rounded-pill px-3 py-2" style="background:rgba(255,255,255,.2);color:#fff;">
                                 <i class="{{ $balIcon }} me-1"></i>{{ $isPositive ? 'Ganancia' : 'Pérdida' }}
                             </span>
                         </div>
                         <h3 class="mb-0 fw-bold text-white" style="font-size:2rem;">
                             {{ $isPositive ? '+' : '' }}{{ number_format($balance, 2, ',', '.') }} <small class="fs-6 text-white-50 fw-semibold">Bs</small>
                         </h3>
                     </div>
                 </div>
             </div>
         </div>

         {{-- ===== INCOME BREAKDOWN ROW ===== --}}
        <div class="row mb-4">
            {{-- Ingresos por Suscripciones --}}
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2" style="color: var(--text-light); font-size: 12px;">
                                    <i class="ri-vip-crown-line me-1" style="color: var(--turquoise-dark);"></i>
                                    Ingresos por Suscripciones
                                </p>
                                <h3 class="mb-1 fw-bold" style="color: var(--turquoise-dark);">
                                    {{ number_format($ingresoSuscripciones, 2, ',', '.') }} Bs
                                </h3>
                                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                    <span class="badge rounded-pill fs-6"
                                        style="background: var(--turquoise); color: var(--turquoise-dark);">
                                        {{ $totalIngresos > 0 ? number_format(($ingresoSuscripciones / $totalIngresos) * 100, 1) : 0 }}%
                                        del total
                                    </span>
                                    @if($pagosSuscripcionesDetalle->count() > 0)
                                        <button type="button" class="btn btn-sm rounded-pill px-3"
                                            style="background:rgba(13,148,136,.1);color:var(--turquoise-dark);border:1px solid rgba(13,148,136,.25);font-size:.75rem;"
                                            id="btnDetalleSuscripciones">
                                            <i class="ri-eye-line me-1"></i>Ver detalle
                                            <span class="badge ms-1" style="background:var(--turquoise-dark);color:#fff;font-size:.65rem;">{{ $pagosSuscripcionesDetalle->count() }}</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center"
                                    style="background: linear-gradient(135deg, var(--turquoise), var(--soft-blue)); width: 80px; height: 80px;">
                                    <i class="ri-vip-crown-line" style="font-size: 2.5rem; color: var(--turquoise-dark);"></i>
                                </div>
                            </div>
                        </div>
                        @if ($totalIngresos > 0)
                            <div class="progress mt-3" style="height: 8px; border-radius: 4px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ ($ingresoSuscripciones / $totalIngresos) * 100 }}%; background: linear-gradient(135deg, var(--turquoise), var(--turquoise-dark));"
                                    aria-valuenow="{{ ($ingresoSuscripciones / $totalIngresos) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        @endif

                        {{-- Split por tarifa --}}
                        @if ($ingresoSuscripciones > 0)
                            @php
                                $pctNormal   = $ingresoSuscripciones > 0 ? ($ingresoSuscripcionesNormal / $ingresoSuscripciones) * 100 : 0;
                                $pctEspecial = $ingresoSuscripciones > 0 ? ($ingresoSuscripcionesEspecial / $ingresoSuscripciones) * 100 : 0;
                            @endphp
                            <div class="row g-2 mt-3 pt-3 border-top">
                                <div class="col-6">
                                    <div class="rounded-3 p-2 h-100" style="background:rgba(13,148,136,0.07);border:1px solid rgba(13,148,136,0.2);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                                style="width:26px;height:26px;background:rgba(13,148,136,0.15);color:#0d9488;font-size:.8rem;">
                                                <i class="ri-sun-line"></i>
                                            </div>
                                            <span class="fw-bold text-uppercase" style="font-size:.62rem;letter-spacing:.5px;color:#0d9488;">Tarifa Normal</span>
                                        </div>
                                        <div class="fw-bold" style="font-size:1.05rem;color:#0d9488;line-height:1.1;">
                                            {{ number_format($ingresoSuscripcionesNormal, 2, ',', '.') }} <small class="fs-6">Bs</small>
                                        </div>
                                        <div class="text-muted" style="font-size:.65rem;">{{ number_format($pctNormal, 1) }}% del ingreso de suscripciones</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-2 h-100" style="background:rgba(180,83,9,0.07);border:1px solid rgba(180,83,9,0.2);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                                style="width:26px;height:26px;background:rgba(180,83,9,0.15);color:#b45309;font-size:.8rem;">
                                                <i class="ri-star-fill"></i>
                                            </div>
                                            <span class="fw-bold text-uppercase" style="font-size:.62rem;letter-spacing:.5px;color:#b45309;">Tarifa Especial</span>
                                        </div>
                                        <div class="fw-bold" style="font-size:1.05rem;color:#b45309;line-height:1.1;">
                                            {{ number_format($ingresoSuscripcionesEspecial, 2, ',', '.') }} <small class="fs-6">Bs</small>
                                        </div>
                                        <div class="text-muted" style="font-size:.65rem;">{{ number_format($pctEspecial, 1) }}% del ingreso de suscripciones</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ingresos por Ventas --}}
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2" style="color: var(--text-light); font-size: 12px;">
                                    <i class="ri-shopping-bag-line me-1" style="color: var(--dino-green-dark);"></i>
                                    Ingresos por Ventas
                                </p>
                                <h3 class="mb-1 fw-bold" style="color: var(--dino-green-dark);">
                                    {{ number_format($ingresoVentas, 2, ',', '.') }} Bs
                                </h3>
                                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                    <span class="badge rounded-pill fs-6"
                                        style="background: var(--dino-green-light); color: var(--dino-green-dark);">
                                        {{ $totalIngresos > 0 ? number_format(($ingresoVentas / $totalIngresos) * 100, 1) : 0 }}%
                                        del total
                                    </span>
                                    @if($ventasDetalleModal->count() > 0)
                                        <button type="button" class="btn btn-sm rounded-pill px-3"
                                            style="background:rgba(82,183,136,.12);color:var(--dino-green-dark);border:1px solid rgba(82,183,136,.3);font-size:.75rem;"
                                            id="btnDetalleVentas">
                                            <i class="ri-eye-line me-1"></i>Ver detalle
                                            <span class="badge ms-1" style="background:var(--dino-green-dark);color:#fff;font-size:.65rem;">{{ $ventasDetalleModal->count() }}</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center"
                                    style="background: linear-gradient(135deg, var(--dino-green-light), var(--dino-green)); width: 80px; height: 80px;">
                                    <i class="ri-shopping-bag-line"
                                        style="font-size: 2.5rem; color: var(--dino-green-dark);"></i>
                                </div>
                            </div>
                        </div>
                        @if ($totalIngresos > 0)
                            <div class="progress mt-3" style="height: 8px; border-radius: 4px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ ($ingresoVentas / $totalIngresos) * 100 }}%; background: linear-gradient(135deg, var(--dino-green), var(--dino-green-dark));"
                                    aria-valuenow="{{ ($ingresoVentas / $totalIngresos) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== CHARTS ROW ===== --}}
        <div class="row mb-4">
            {{-- Tendencia Mensual (Area Chart) --}}
            <div class="col-lg-8 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="card-section-header">
                            <div class="icon-wrap" style="background:rgba(13,148,136,.1);color:var(--turquoise-dark);">
                                <i class="ri-line-chart-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" style="color:var(--turquoise-dark);">Tendencia Mensual</h5>
                                <small class="text-muted">Ingresos vs Gastos en los últimos 6 meses</small>
                            </div>
                        </div>
                        <div id="trendChart"></div>
                    </div>
                </div>
            </div>

            {{-- Gastos por Categoria (Donut Chart) --}}
            <div class="col-lg-4 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="card-section-header">
                            <div class="icon-wrap" style="background:rgba(240,101,72,.1);color:var(--coral-dark);">
                                <i class="ri-pie-chart-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" style="color:var(--coral-dark);">Gastos por Categoría</h5>
                                <small class="text-muted">Distribución del periodo</small>
                            </div>
                        </div>
                        @if ($gastosChart->count() > 0)
                            <div id="donutChart"></div>
                            <div class="text-center mt-2">
                                <small class="text-muted" style="font-size:0.65rem;cursor:default;">
                                    <i class="ri-information-line me-1"></i>Haz clic en una categoría para ver el desglose por subcategorías
                                </small>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-pie-chart-line" style="font-size: 4rem; color: var(--dino-green-light);"></i>
                                <p class="text-muted mt-3">No hay gastos registrados en este periodo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
         </div>

         {{-- ===== DAILY INCOME CHART ===== --}}
         <div class="row mb-4">
             <div class="col-12">
                 <div class="card border-0 shadow-sm rounded-4 h-100">
                     <div class="card-body p-4">
                         <div class="card-section-header">
                             <div class="icon-wrap" style="background:rgba(13,148,136,.1);color:var(--turquoise-dark);">
                                  <i class="ri-bar-chart-grouped-line"></i>
                              </div>
                              <div>
                                  <h5 class="fw-bold mb-0" style="color:var(--turquoise-dark);">Ingresos Diarios</h5>
                                  <small class="text-muted">Ingresos diarios — barras apiladas (Suscripciones + Ventas)</small>
                             </div>
                         </div>
                         <div id="dailyIncomeChart"></div>
                     </div>
                 </div>
             </div>
         </div>

         {{-- ===== MODALS ===== --}}

        {{-- Modal: Ingresos por Suscripciones --}}
        <div class="modal fade" id="modalDetalleSuscripciones" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,var(--turquoise-dark),var(--turquoise));">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0">
                                <i class="ri-vip-crown-line me-2"></i>Detalle de Ingresos por Suscripciones
                            </h5>
                            <p class="text-white-50 mb-0 small">{{ $fechaDesde }} al {{ $fechaHasta }}</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Recibo</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Plan</th>
                                        <th class="text-center">Tarifa</th>
                                        <th>Tipo Pago</th>
                                        <th class="text-end">Monto (Bs)</th>
                                        <th class="text-end">Descuento (Bs)</th>
                                        <th>Registrado por</th>
                                        <th class="text-center">PDF</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodySuscripciones"></tbody>
                                <tfoot id="tfootSuscripciones"></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-3 pt-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Ingresos por Ventas --}}
        <div class="modal fade" id="modalDetalleVentas" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,var(--dino-green-dark),var(--dino-green));">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0">
                                <i class="ri-shopping-bag-line me-2"></i>Detalle de Ingresos por Ventas
                            </h5>
                            <p class="text-white-50 mb-0 small">{{ $fechaDesde }} al {{ $fechaHasta }}</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Recibo</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th class="text-center">Productos</th>
                                        <th>Tipo Pago</th>
                                        <th class="text-end">Monto (Bs)</th>
                                        <th class="text-end">Descuento (Bs)</th>
                                        <th>Registrado por</th>
                                        <th class="text-center">PDF</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyVentas"></tbody>
                                <tfoot id="tfootVentas"></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-3 pt-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Detalle Gastos por Categoría --}}
        <div class="modal fade" id="modalDetalleGasto" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,var(--coral-dark),var(--coral-spot));">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0" id="modalDetalleGastoTitulo">
                                <i class="ri-file-list-3-line me-2"></i>Detalle de Gastos
                            </h5>
                            <p class="text-white-50 mb-0 small">{{ $fechaDesde }} al {{ $fechaHasta }}</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover align-middle mb-0">
                                <thead class="table-light" id="theadGasto">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th class="text-end">Monto (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyGasto"></tbody>
                                <tfoot id="tfootGasto"></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-3 pt-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== EXPENSE DETAILS TABLE ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="card-section-header">
                            <div class="icon-wrap" style="background:rgba(240,101,72,.1);color:var(--coral-dark);">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-0" style="color:var(--coral-dark);">Detalle de Gastos por Categoría</h5>
                                <small class="text-muted">Distribución y proporción de cada categoría en el periodo</small>
                            </div>
                            <span class="badge rounded-pill px-3 py-2"
                                style="background:rgba(240,101,72,.1); color:var(--coral-dark); border:1px solid rgba(240,101,72,.25);">
                                <i class="ri-bookmark-line me-1"></i>{{ $gastosPorCategoria->count() }} categoria(s)
                            </span>
                        </div>
                        @if ($gastosPorCategoria->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-modern table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Categoria</th>
                                            <th class="text-end">Monto (Bs)</th>
                                            <th class="text-end" style="width: 120px;">% del Total</th>
                                            <th style="width: 180px;">Proporcion</th>
                                            <th class="text-center" style="width: 120px;">Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @foreach ($gastosTabla as $index => $item)
                                            @php
                                                $esHijo = $item->tipo === 'hijo';
                                                $barHeight = $esHijo ? '4px' : '8px';
                                                $barRadius = $esHijo ? '2px' : '4px';
                                                $pct = $totalGastos > 0 ? ($item->total / $totalGastos) * 100 : 0;
                                                $catKey = $item->nombre;
                                                $padreNombre = $esHijo ? $item->padre : null;
                                            @endphp
                                            <tr class="{{ $esHijo ? 'table-light' : '' }}">
                                                <td>
                                                    @if(!$esHijo)
                                                        <span class="badge rounded-pill"
                                                            style="background: var(--turquoise); color: var(--turquoise-dark);">
                                                            {{ $loop->parent ? '' : ($index + 1) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($esHijo)
                                                        <div class="d-flex align-items-center">
                                                            <div class="text-muted me-2" style="font-size:0.7rem;">
                                                                <i class="ri-corner-down-right-line"></i>
                                                            </div>
                                                            <div>
                                                                <span class="fw-semibold small">{{ $item->nombre }}</span>
                                                                <br>
                                                                <small class="text-muted" style="font-size:0.65rem;">
                                                                    <i class="ri-price-tag-3-line me-1"></i>{{ $item->padre }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle">
                                                                        <i class="ri-price-tag-3-line"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="fw-bold">{{ $item->nombre }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-semibold {{ $esHijo ? 'text-secondary' : 'fw-bold' }}" style="{{ $esHijo ? '' : 'color: var(--coral-dark);' }}">
                                                    {{ number_format($item->total, 2, ',', '.') }}
                                                </td>
                                                <td class="text-end text-muted">
                                                    <small>{{ number_format($pct, 1) }}%</small>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: {{ $barHeight }}; border-radius: {{ $barRadius }};">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $pct }}%; {{ $esHijo ? 'background: var(--coral-spot);' : 'background: linear-gradient(135deg, var(--coral-spot), var(--coral-dark));' }}">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        // Intentar con el nombre directo, si no, con el padre
                                                        $detKey = $catKey;
                                                        $detItems = $gastosDetalleCategoria[$detKey] ?? [];
                                                        $countDet = count($detItems);
                                                    @endphp
                                                    @if($countDet > 0)
                                                        <button type="button"
                                                            class="btn btn-sm rounded-pill px-3 btn-detalle-gasto"
                                                            style="background:rgba(240,101,72,.08);color:var(--coral-dark);border:1px solid rgba(240,101,72,.25);font-size:.75rem;"
                                                            data-categoria="{{ $detKey }}"
                                                            data-padre="{{ $padreNombre ?? '' }}"
                                                            data-total="{{ number_format($item->total, 2, ',', '.') }}">
                                                            <i class="ri-eye-line me-1"></i>Ver
                                                            <span class="badge ms-1" style="background:var(--coral-dark);color:#fff;font-size:.6rem;">{{ $countDet }}</span>
                                                        </button>
                                                    @else
                                                        <span class="text-muted" style="font-size:.75rem;">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr id="totalGastosRow">
                                            <td colspan="2" class="fw-bold" style="color: var(--turquoise-dark);">
                                                TOTAL GASTOS
                                            </td>
                                            <td class="text-end fw-bold" style="color: var(--coral-dark); font-size: 1.1rem;">
                                                {{ number_format($totalGastos, 2, ',', '.') }} Bs
                                            </td>
                                            <td class="text-end fw-bold">100%</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-file-list-3-line"
                                    style="font-size: 4rem; color: var(--dino-green-light);"></i>
                                <p class="text-muted mt-3">No hay gastos registrados en este periodo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Drill-down del donut chart (hijos por padre) --}}
    <div class="modal fade" id="modalDonutDrilldown" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,var(--coral-dark),var(--coral-spot));">
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">
                            <i class="ri-pie-chart-2-line me-2"></i><span id="donutDrilldownParent"></span>
                        </h5>
                        <p class="text-white-50 mb-0 small">Desglose por subcategorías</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Subcategoría</th>
                                    <th class="text-end">Monto (Bs)</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody id="donutDrilldownBody"></tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="2" style="color:var(--turquoise-dark);">TOTAL</td>
                                    <td class="text-end" style="color:var(--coral-dark);font-size:1rem;" id="donutDrilldownTotal"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-3 pt-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDarkMode = document.documentElement.getAttribute('data-layout-mode') === 'dark';
                const chartText = isDarkMode ? '#9db3af' : '#6f8085';
                const chartGrid = isDarkMode ? '#24423f' : '#e8e4dc';
                const tooltipTheme = isDarkMode ? 'dark' : 'light';

                // Quick date buttons
                const form = document.getElementById('formFiltroFinanzas');
                const fechaDesde = form.querySelector('[name="fecha_desde"]');
                const fechaHasta = form.querySelector('[name="fecha_hasta"]');

                const selectMes     = form.querySelector('[name="mes"]');
                const selectGestion = form.querySelector('[name="gestion"]');

                document.getElementById('btnEsteMes').addEventListener('click', function() {
                    const now = new Date();
                    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                    fechaDesde.value = formatDate(firstDay);
                    fechaHasta.value = formatDate(lastDay);
                    if (selectMes)     selectMes.value = '';
                    if (selectGestion) selectGestion.value = '';
                    form.submit();
                });

                document.getElementById('btnEsteAno').addEventListener('click', function() {
                    const now = new Date();
                    const firstDay = new Date(now.getFullYear(), 0, 1);
                    const lastDay = new Date(now.getFullYear(), 11, 31);
                    fechaDesde.value = formatDate(firstDay);
                    fechaHasta.value = formatDate(lastDay);
                    if (selectMes)     selectMes.value = '';
                    if (selectGestion) selectGestion.value = '';
                    form.submit();
                });

                // Al usar "Filtrar" del rango de fechas, también limpiamos mes/gestión
                document.getElementById('btnFiltrarRango')?.addEventListener('click', function() {
                    if (selectMes)     selectMes.value = '';
                    if (selectGestion) selectGestion.value = '';
                });

                // Al usar "Aplicar Mes/Gestión", validar que ambos estén seleccionados
                document.getElementById('btnFiltrarMesGestion')?.addEventListener('click', function(e) {
                    if (!selectMes.value || !selectGestion.value) {
                        e.preventDefault();
                        alert('Debes seleccionar Mes y Gestión para aplicar este filtro.');
                    }
                });

                function formatDate(date) {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                }

                // ===== TREND AREA CHART =====
                var trendData = @json($tendenciaMensual);
                var trendCategories = trendData.map(function(item) { return item.mes; });
                var trendIngresos = trendData.map(function(item) { return item.ingresos; });
                var trendGastos = trendData.map(function(item) { return item.gastos; });

                var trendOptions = {
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    series: [
                        { name: 'Ingresos', data: trendIngresos },
                        { name: 'Gastos', data: trendGastos }
                    ],
                    xaxis: {
                        categories: trendCategories,
                        labels: {
                            style: { colors: chartText, fontWeight: 600 }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) { return val.toFixed(0) + ' Bs'; },
                            style: { colors: chartText, fontWeight: 600 }
                        }
                    },
                    colors: ['#2f9e7a', '#d96b5b'],
                    stroke: { curve: 'smooth', width: 3 },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    tooltip: {
                        theme: tooltipTheme,
                        y: {
                            formatter: function(val) { return val.toFixed(2) + ' Bs'; }
                        }
                    },
                    dataLabels: { enabled: false },
                    grid: {
                        borderColor: chartGrid,
                        strokeDashArray: 4
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontWeight: 600
                    },
                    markers: {
                        size: 4,
                        strokeWidth: 2,
                        hover: { size: 6 }
                    }
                };

                new ApexCharts(document.querySelector("#trendChart"), trendOptions).render();

                // ===== DONUT CHART =====
                @if ($gastosChart->count() > 0)
                    var donutSeries = @json($gastosChart->pluck('total')->map(fn($v) => (float) $v)->values());
                    var donutLabels = @json($gastosChart->pluck('nombre')->values());
                    var donutChildren = @json($gastosChildrenMap);

                    var donutColors = ['#2f9e7a', '#7c3aed', '#4f9dc4', '#f2a9a0', '#d99a35', '#6fc2a3',
                        '#c4b5fd', '#bfe3e0', '#d96b5b', '#8fd6b6', '#a78bfa', '#f4c7a1'];

                    var donutOptions = {
                        chart: {
                            type: 'donut',
                            height: 350,
                            fontFamily: 'inherit',
                            events: {
                                dataPointSelection: function(event, chartContext, config) {
                                    var label = donutLabels[config.dataPointIndex];
                                    var children = donutChildren[label];
                                    if (children && children.length > 0) {
                                        showDonutDrilldown(label, children);
                                    }
                                }
                            }
                        },
                        series: donutSeries,
                        labels: donutLabels,
                        colors: donutColors.slice(0, donutLabels.length),
                        legend: {
                            position: 'bottom',
                            fontWeight: 600,
                            fontSize: '13px'
                        },
                        tooltip: {
                            theme: tooltipTheme,
                            y: {
                                formatter: function(val) { return val.toFixed(2) + ' Bs'; }
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '14px',
                                            fontWeight: 700
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '16px',
                                            fontWeight: 700,
                                            formatter: function(val) { return parseFloat(val).toFixed(2) + ' Bs'; }
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '14px',
                                            fontWeight: 600,
                                            color: '#7c3aed',
                                            formatter: function(w) {
                                                return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0).toFixed(2) + ' Bs';
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: { height: 300 },
                                legend: { position: 'bottom' }
                            }
                        }]
                    };

                    new ApexCharts(document.querySelector("#donutChart"), donutOptions).render();
                @endif

                // ===== DONUT DRILL-DOWN =====
                function showDonutDrilldown(parentName, children) {
                    var totalParent = children.reduce(function(s, c) { return s + c.total; }, 0);
                    var rows = children.map(function(c, i) {
                        var pct = totalParent > 0 ? ((c.total / totalParent) * 100).toFixed(1) : 0;
                        var color = donutColors[i % donutColors.length];
                        return `<tr>
                            <td><span class="badge rounded-pill px-2" style="background:${color}20;color:${color};">${i + 1}</span></td>
                            <td class="fw-semibold">${c.nombre}</td>
                            <td class="text-end fw-bold" style="color:var(--coral-dark);">${fmt(c.total)}</td>
                            <td class="text-end text-muted"><small>${pct}%</small></td>
                        </tr>`;
                    }).join('');

                    document.getElementById('donutDrilldownParent').textContent = parentName;
                    document.getElementById('donutDrilldownTotal').textContent = fmt(totalParent) + ' Bs';
                    document.getElementById('donutDrilldownBody').innerHTML = rows;

                    new bootstrap.Modal(document.getElementById('modalDonutDrilldown')).show();
                }

                // ===== DATA =====
                const pagosSus = @json($pagosSuscripcionesDetalle);
                const ventasData = @json($ventasDetalleModal);
                const gastosData = @json($gastosDetalleCategoria);

                // ===== HELPERS =====
                function fmt(n) {
                    return Number(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                function tipoBadge(tipo) {
                    const map = {
                        efectivo: 'bg-warning bg-opacity-10 text-warning',
                        qr:       'bg-info bg-opacity-10 text-info',
                        parcial:  'bg-secondary bg-opacity-10 text-secondary',
                    };
                    return `<span class="badge ${map[tipo] || 'bg-light text-dark'} px-2 py-1 rounded-pill">${tipo ? tipo.charAt(0).toUpperCase() + tipo.slice(1) : '-'}</span>`;
                }
                function pdfBtn(url) {
                    return `<a href="${url}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width:30px;height:30px;" title="PDF"><i class="ri-file-pdf-line"></i></a>`;
                }

                // ===== MODAL SUSCRIPCIONES =====
                document.getElementById('btnDetalleSuscripciones')?.addEventListener('click', function () {
                    const tbody = document.getElementById('tbodySuscripciones');
                    const tfoot = document.getElementById('tfootSuscripciones');
                    let totalMonto = 0, totalDesc = 0;

                    let totalNormal = 0, totalEspecial = 0;
                    tbody.innerHTML = pagosSus.map((p, i) => {
                        totalMonto += p.monto;
                        totalDesc  += p.descuento;
                        if (p.tarifa === 'especial') totalEspecial += p.monto;
                        else totalNormal += p.monto;
                        const tarifaBadge = p.tarifa === 'especial'
                            ? `<span class="badge rounded-pill px-2" style="background:#fef3c7;color:#b45309;font-size:.7rem;"><i class="ri-star-fill me-1"></i>Especial</span>`
                            : `<span class="badge rounded-pill px-2" style="background:#f0fdf4;color:#0d9488;font-size:.7rem;"><i class="ri-sun-line me-1"></i>Normal</span>`;
                        return `<tr>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">${p.recibo}</span></td>
                            <td><small class="text-muted">${p.fecha}</small></td>
                            <td class="fw-semibold">${p.cliente}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">${p.plan}</span></td>
                            <td class="text-center">${tarifaBadge}</td>
                            <td>${tipoBadge(p.tipo_pago)}</td>
                            <td class="text-end fw-bold text-success">${fmt(p.monto)}</td>
                            <td class="text-end text-danger">${p.descuento > 0 ? fmt(p.descuento) : '-'}</td>
                            <td><small>${p.trabajador}</small></td>
                            <td class="text-center">${pdfBtn(p.pdf_url)}</td>
                        </tr>`;
                    }).join('');

                    tfoot.innerHTML = `
                        <tr class="fw-semibold" style="background:rgba(13,148,136,.04);">
                            <td colspan="6" class="text-end" style="color:#0d9488;"><i class="ri-sun-line me-1"></i>Subtotal Tarifa Normal</td>
                            <td class="text-end" style="color:#0d9488;">${fmt(totalNormal)} Bs</td>
                            <td colspan="3"></td>
                        </tr>
                        <tr class="fw-semibold" style="background:rgba(180,83,9,.04);">
                            <td colspan="6" class="text-end" style="color:#b45309;"><i class="ri-star-fill me-1"></i>Subtotal Tarifa Especial</td>
                            <td class="text-end" style="color:#b45309;">${fmt(totalEspecial)} Bs</td>
                            <td colspan="3"></td>
                        </tr>
                        <tr class="table-light fw-bold">
                            <td colspan="6" style="color:var(--turquoise-dark);">TOTAL</td>
                            <td class="text-end text-success" style="font-size:1rem;">${fmt(totalMonto)} Bs</td>
                            <td class="text-end text-danger">${fmt(totalDesc)} Bs</td>
                            <td colspan="2"></td>
                        </tr>`;

                    new bootstrap.Modal(document.getElementById('modalDetalleSuscripciones')).show();
                });

                // ===== MODAL VENTAS =====
                document.getElementById('btnDetalleVentas')?.addEventListener('click', function () {
                    const tbody = document.getElementById('tbodyVentas');
                    const tfoot = document.getElementById('tfootVentas');
                    let totalMonto = 0, totalDesc = 0;

                    tbody.innerHTML = ventasData.map(v => {
                        totalMonto += v.monto;
                        totalDesc  += v.descuento;
                        return `<tr>
                            <td><span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">${v.recibo}</span></td>
                            <td><small class="text-muted">${v.fecha}</small></td>
                            <td class="fw-semibold">${v.cliente}</td>
                            <td class="text-center"><span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">${v.n_productos} prod.</span></td>
                            <td>${tipoBadge(v.tipo_pago)}</td>
                            <td class="text-end fw-bold text-success">${fmt(v.monto)}</td>
                            <td class="text-end text-danger">${v.descuento > 0 ? fmt(v.descuento) : '-'}</td>
                            <td><small>${v.trabajador}</small></td>
                            <td class="text-center">${pdfBtn(v.pdf_url)}</td>
                        </tr>`;
                    }).join('');

                    tfoot.innerHTML = `<tr class="table-light fw-bold">
                        <td colspan="5" style="color:var(--dino-green-dark);">TOTAL</td>
                        <td class="text-end text-success" style="font-size:1rem;">${fmt(totalMonto)} Bs</td>
                        <td class="text-end text-danger">${fmt(totalDesc)} Bs</td>
                        <td colspan="2"></td>
                    </tr>`;

                    new bootstrap.Modal(document.getElementById('modalDetalleVentas')).show();
                });

                // ===== MODAL GASTOS POR CATEGORIA =====
                document.addEventListener('click', function (e) {
                    const btn = e.target.closest('.btn-detalle-gasto');
                    if (!btn) return;

                    const categoria = btn.dataset.categoria;
                    const padre     = btn.dataset.padre || '';
                    const total     = btn.dataset.total;
                    const items     = gastosData[categoria] || [];

                    let tituloHtml = `<i class="ri-file-list-3-line me-2"></i>Gastos: `;
                    if (padre) {
                        tituloHtml += `<span class="opacity-75" style="font-size:0.75rem;">${padre}</span> <i class="ri-arrow-right-s-line"></i> `;
                    }
                    tituloHtml += `${categoria}`;
                    document.getElementById('modalDetalleGastoTitulo').innerHTML = tituloHtml;

                    const thead = document.getElementById('theadGasto');
                    const tbody = document.getElementById('tbodyGasto');
                    const tfoot = document.getElementById('tfootGasto');
                    let sum = 0;
                    const esSalarios = categoria.toLowerCase().includes('salario');

                    if (esSalarios) {
                        thead.innerHTML = `<tr>
                            <th>#</th>
                            <th>Periodo</th>
                            <th>Trabajador</th>
                            <th class="text-center">Sesiones</th>
                            <th>Descripción</th>
                            <th class="text-end">Monto (Bs)</th>
                        </tr>`;
                        tbody.innerHTML = items.map((g, i) => {
                            sum += g.monto;
                            return `<tr>
                                <td><span class="badge rounded-pill" style="background:rgba(240,101,72,.1);color:var(--coral-dark);">${i+1}</span></td>
                                <td><small class="text-muted">${g.fecha}</small></td>
                                <td><span class="fw-semibold">${g.trabajador || '-'}</span></td>
                                <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2">${g.n_sesiones != null ? g.n_sesiones + ' ses.' : '—'}</span></td>
                                <td><small class="text-muted">${g.descripcion || '-'}</small></td>
                                <td class="text-end fw-bold" style="color:var(--coral-dark);">${fmt(g.monto)}</td>
                            </tr>`;
                        }).join('');
                        tfoot.innerHTML = `<tr class="table-light fw-bold">
                            <td colspan="5" style="color:var(--turquoise-dark);">TOTAL</td>
                            <td class="text-end" style="color:var(--coral-dark);font-size:1rem;">${fmt(sum)} Bs</td>
                        </tr>`;
                    } else {
                        thead.innerHTML = `<tr>
                            <th>#</th>
                            <th>Periodo</th>
                            <th>Descripción</th>
                            <th>Observación</th>
                            <th class="text-end">Monto (Bs)</th>
                        </tr>`;
                        tbody.innerHTML = items.map((g, i) => {
                            sum += g.monto;
                            return `<tr>
                                <td><span class="badge rounded-pill" style="background:rgba(240,101,72,.1);color:var(--coral-dark);">${i+1}</span></td>
                                <td><small class="text-muted">${g.fecha}</small></td>
                                <td><small class="text-muted">${g.descripcion || '-'}</small></td>
                                <td><small class="text-muted">${g.observacion || '-'}</small></td>
                                <td class="text-end fw-bold" style="color:var(--coral-dark);">${fmt(g.monto)}</td>
                            </tr>`;
                        }).join('');
                        tfoot.innerHTML = `<tr class="table-light fw-bold">
                            <td colspan="4" style="color:var(--turquoise-dark);">TOTAL</td>
                            <td class="text-end" style="color:var(--coral-dark);font-size:1rem;">${fmt(sum)} Bs</td>
                        </tr>`;
                    }

                    new bootstrap.Modal(document.getElementById('modalDetalleGasto')).show();
                 });
             });
         </script>

          <script>
              (function() {
                  var el = document.querySelector("#dailyIncomeChart");
                  if (!el) return;

                  var isDarkMode = document.documentElement.getAttribute('data-layout-mode') === 'dark';
                  var chartText = isDarkMode ? '#9db3af' : '#6f8085';
                  var chartGrid = isDarkMode ? '#24423f' : '#e8e4dc';
                  var tooltipTheme = isDarkMode ? 'dark' : 'light';

                  var dd = @json($dailyIngresos);
                  if (!dd || !dd.total || !dd.total.fechas || !dd.total.fechas.length) {
                      el.innerHTML = '<div class="text-center py-5"><i class="ri-bar-chart-grouped-line" style="font-size: 4rem; color: var(--dino-green-light);"></i><p class="text-muted mt-3">No hay ingresos registrados en este periodo</p></div>';
                      return;
                  }

                  var labels = dd.total.fechas;
                  var sus    = dd.suscripciones;
                  var ven    = dd.ventas;
                  var tot    = dd.total.valores;

                  var hasVal = false;
                  for (var i = 0; i < tot.length; i++) { if (tot[i] > 0) { hasVal = true; break; } }
                  if (!hasVal) {
                      el.innerHTML = '<div class="text-center py-5"><i class="ri-bar-chart-grouped-line" style="font-size: 4rem; color: var(--dino-green-light);"></i><p class="text-muted mt-3">No hay ingresos registrados en este periodo</p></div>';
                      return;
                  }

                  var mx = 1;
                  for (var i = 0; i < tot.length; i++) { if (tot[i] > mx) mx = tot[i]; }
                  var many = labels.length > 15;

                  var opts = {
                      chart: { type: 'line', height: 340, toolbar: { show: false }, fontFamily: 'inherit' },
                      series: [
                          { name: 'Suscripciones', data: sus },
                          { name: 'Ventas', data: ven },
                          { name: 'Total Ingresos', data: tot }
                      ],
                      colors: ['#4f9dc4', '#2f9e7a', '#7c3aed'],
                      xaxis: {
                          categories: labels,
                          labels: {
                              rotate: many ? -45 : 0,
                              style: { colors: chartText, fontWeight: 500, fontSize: many ? '10px' : '12px' }
                          },
                          axisBorder: { show: false }
                      },
                      yaxis: {
                          min: 0, max: Math.ceil(mx * 1.15), tickAmount: 5,
                          labels: { formatter: function(v) { return Math.round(v) + ' Bs'; }, style: { colors: chartText, fontWeight: 500 } }
                      },
                      grid: { borderColor: chartGrid, strokeDashArray: 4 },
                      tooltip: { theme: tooltipTheme, y: { formatter: function(v) { return v.toFixed(2) + ' Bs'; } } },
                      legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600, markers: { radius: 4 } },
                      stroke: { curve: 'smooth', width: [2, 2, 2] },
                      markers: { size: many ? 2 : 4, hover: { size: 6 } },
                      dataLabels: { enabled: false }
                  };

                  try { (new ApexCharts(el, opts)).render(); }
                  catch(e) { console.error('DailyIncome ERR:', e); el.innerHTML = '<div class="text-center py-5 text-danger"><i class="ri-error-warning-line fs-1"></i><p class="mt-2">Error al cargar el grafico</p></div>'; }
              })();

              // ===== DARK MODE: Total Gastos background =====
              function getCssColor(varName) {
                  const style = getComputedStyle(document.documentElement);
                  return style.getPropertyValue(varName).trim();
              }

              function updateTotalGastosRow() {
                  const totalGastosRow = document.getElementById('totalGastosRow');
                  if (!totalGastosRow) return;

                  const isDark = document.documentElement.getAttribute('data-layout-mode') === 'dark';
                  const cream = getCssColor('--cream');
                  const bgCard = isDark ? '#1e293b' : cream;

                  totalGastosRow.style.background = bgCard;
              }

              updateTotalGastosRow();

              const observer = new MutationObserver(function(mutations) {
                  mutations.forEach(function(mutation) {
                      if (mutation.attributeName === 'data-layout-mode') {
                          updateTotalGastosRow();
                      }
                  });
              });
              observer.observe(document.documentElement, { attributes: true });
          </script>
      @endpush
@endsection
