@extends('admin.dashboard')
@section('title', 'Estadísticas - Dino Baby Spa')
@section('admin')
    <div class="container-fluid">
        {{-- ===== PAGE TITLE ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box stats-hero"
                    style="background: linear-gradient(135deg, var(--turquoise-dark) 0%, var(--dino-green-dark) 100%);">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-bar-chart-box-line me-2"></i>Estadísticas del Spa
                        </h4>
                        <p class="text-white-50 mb-0">Visualiza el rendimiento de servicios, productos e inventario</p>
                    </div>
                    <div class="page-title-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                        class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item active text-white">Estadísticas</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FILTRO MES / GESTIÓN ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 stats-filters">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    <i class="ri-calendar-2-line me-1" style="color: var(--dino-green-dark);"></i>Mes
                                </label>
                                <select class="form-select form-select-lg rounded-3" id="selectMes">
                                    @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                        <option value="{{ $i + 1 }}" {{ ($i + 1) === (int) $mesSeleccionado ? 'selected' : '' }}>{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    <i class="ri-calendar-event-line me-1" style="color: var(--dino-green-dark);"></i>Gestión
                                </label>
                                <select class="form-select form-select-lg rounded-3" id="selectGestion">
                                    @foreach ($gestionesDisponibles as $g)
                                        <option value="{{ $g }}" {{ (int) $g === (int) $gestionSeleccionada ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-lg w-100 rounded-3" onclick="aplicarFiltros()">
                                    <i class="ri-filter-3-line me-1"></i> Aplicar
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div class="nav nav-pills nav-fill nav-pills-custom justify-content-end" id="estadisticasTabs" role="tablist">
                                    <button class="nav-link servicios active" id="servicios-tab" data-bs-toggle="pill"
                                        data-bs-target="#tab-servicios" type="button" role="tab">
                                        <i class="ri-settings-3-line"></i>Servicios
                                    </button>
                                    <button class="nav-link productos" id="productos-tab" data-bs-toggle="pill"
                                        data-bs-target="#tab-productos" type="button" role="tab">
                                        <i class="ri-shopping-bag-3-line"></i>Productos
                                    </button>
                                    <button class="nav-link stock" id="stock-tab" data-bs-toggle="pill"
                                        data-bs-target="#tab-stock" type="button" role="tab">
                                        <i class="ri-stock-line"></i>Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- Inputs ocultos: el resto del JS sigue usándolos --}}
                        <input type="hidden" id="fecha_inicio" value="{{ $fechaInicio }}">
                        <input type="hidden" id="fecha_fin"    value="{{ $fechaFin }}">
                    </div>
                </div>
            </div>
        </div>

        <style>
            .nav-pills-custom {
                display: flex;
                gap: 4px;
                padding: 4px;
                background: var(--cream);
                border-radius: 12px;
            }

            .nav-pills-custom .nav-link {
                padding: 10px 18px;
                border: none;
                background: transparent;
                color: var(--text-secondary);
                font-size: 0.85rem;
                font-weight: 600;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            .nav-pills-custom .nav-link i {
                font-size: 1rem;
            }

            .nav-pills-custom .nav-link:hover {
                color: var(--dino-green-dark);
                background: rgba(82, 183, 136, 0.1);
            }

            .nav-pills-custom .nav-link.active {
                color: white;
                box-shadow: 0 4px 15px rgba(82, 183, 136, 0.4);
            }

            .nav-pills-custom .nav-link.servicios.active {
                background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%);
            }

            .nav-pills-custom .nav-link.productos.active {
                background: linear-gradient(135deg, var(--turquoise-dark) 0%, var(--turquoise) 100%);
            }

            .nav-pills-custom .nav-link.stock.active {
                background: linear-gradient(135deg, var(--coral-dark) 0%, var(--coral-spot) 100%);
            }

            .stat-card-header {
                padding: 16px 20px;
                border-bottom: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                gap: 12px;
                border-radius: 0;
            }

            .stat-card-header.servicios {
                background: linear-gradient(135deg, rgba(82, 183, 136, 0.1) 0%, rgba(82, 183, 136, 0.05) 100%);
                border-left: 3px solid var(--dino-green);
            }

            .stat-card-header.productos {
                background: linear-gradient(135deg, rgba(13, 148, 136, 0.1) 0%, rgba(13, 148, 136, 0.05) 100%);
                border-left: 3px solid var(--turquoise-dark);
            }

            .stat-card-header.stock {
                background: linear-gradient(135deg, rgba(240, 101, 72, 0.1) 0%, rgba(240, 101, 72, 0.05) 100%);
                border-left: 3px solid var(--coral-dark);
            }

            .stat-card-header i {
                font-size: 1.2rem;
            }

            .stat-card-header.servicios i { color: var(--dino-green); }
            .stat-card-header.productos i { color: var(--turquoise-dark); }
            .stat-card-header.stock i { color: var(--coral-dark); }

            .stat-card-header h6 {
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--text-primary);
                margin: 0;
            }

            .stat-card-body {
                padding: 20px;
            }

            .chart-container {
                min-height: 280px;
            }

            .btn-group-tendencia {
                display: flex;
                gap: 4px;
                background: var(--cream);
                padding: 4px;
                border-radius: 8px;
            }

            .btn-tendencia {
                padding: 6px 14px;
                border: none;
                background: transparent;
                color: var(--text-secondary);
                font-size: 0.75rem;
                font-weight: 600;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .btn-tendencia i {
                font-size: 0.85rem;
            }

            .btn-tendencia:hover {
                color: var(--dino-green-dark);
                background: rgba(82, 183, 136, 0.1);
            }

            .btn-tendencia.active {
                background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%);
                color: white;
            }

            .stat-summary-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
                margin-bottom: 24px;
            }

            .summary-stat {
                background: var(--bg-card);
                border-radius: 16px;
                padding: 20px;
                text-align: center;
                border: 1px solid var(--border-color);
                transition: all 0.3s ease;
            }

            .summary-stat:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 25px rgba(82, 183, 136, 0.15);
            }

            .summary-stat .icon-wrapper {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 12px;
            }

            .summary-stat .icon-wrapper i {
                font-size: 1.2rem;
            }

            .summary-stat.ok .icon-wrapper {
                background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%);
            }
            .summary-stat.ok .icon-wrapper i { color: white; }
            .summary-stat.ok h3 { color: var(--dino-green); }

            .summary-stat.bajo .icon-wrapper {
                background: linear-gradient(135deg, var(--coral-spot) 0%, var(--coral-dark) 100%);
            }
            .summary-stat.bajo .icon-wrapper i { color: white; }
            .summary-stat.bajo h3 { color: var(--coral-dark); }

            .summary-stat.agotado .icon-wrapper {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            }
            .summary-stat.agotado .icon-wrapper i { color: white; }
            .summary-stat.agotado h3 { color: #ef4444; }

            .summary-stat.categoria .icon-wrapper {
                background: linear-gradient(135deg, var(--turquoise-dark) 0%, var(--turquoise) 100%);
            }
            .summary-stat.categoria .icon-wrapper i { color: white; }
            .summary-stat.categoria h3 { color: var(--turquoise-dark); }

            .summary-stat h3 {
                font-size: 1.6rem;
                font-weight: 700;
                margin: 0 0 4px;
            }

            .summary-stat p {
                font-size: 0.75rem;
                color: var(--text-secondary);
                margin: 0;
                font-weight: 500;
            }

            .badge-status {
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
            }

            .badge-status.ok {
                background: rgba(82, 183, 136, 0.15);
                color: var(--dino-green);
            }

            .badge-status.bajo {
                background: rgba(240, 101, 72, 0.15);
                color: var(--coral-dark);
            }

            .badge-status.agotado {
                background: rgba(239, 68, 68, 0.15);
                color: #ef4444;
            }

            .badge-status.cantidad {
                background: rgba(13, 148, 136, 0.15);
                color: var(--turquoise-dark);
            }

            .filter-dropdown {
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 8px 14px;
                font-size: 0.85rem;
                cursor: pointer;
                transition: all 0.3s ease;
                min-width: 140px;
            }

            .filter-dropdown:hover {
                border-color: var(--dino-green);
            }

            .filter-dropdown:focus {
                border-color: var(--dino-green);
                box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.1);
                outline: none;
            }

            .empty-state {
                text-align: center;
                padding: 40px 20px;
                color: var(--text-secondary);
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 12px;
                color: var(--dino-green-light);
                opacity: 0.6;
            }

            .animate-card {
                opacity: 0;
                animation: slideUp 0.4s ease-out forwards;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-card:nth-child(1) { animation-delay: 0.05s; }
            .animate-card:nth-child(2) { animation-delay: 0.1s; }
            .animate-card:nth-child(3) { animation-delay: 0.15s; }
            .animate-card:nth-child(4) { animation-delay: 0.2s; }

            @media (max-width: 768px) {
                .stat-summary-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .nav-pills-custom {
                    justify-content: flex-start;
                    overflow-x: auto;
                }
            }

            /* ============================================================
               Refinamiento A1 de la página de estadísticas
               ============================================================ */
            body.dino-theme .page-title-box.stats-hero {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 55%, #6d28d9 130%) !important;
                border: 0 !important;
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
            }

            body.dino-theme .page-title-box.stats-hero::after {
                content: "";
                position: absolute;
                width: 340px;
                height: 340px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(196, 181, 253, .28), transparent 65%);
                right: -100px;
                top: -140px;
                pointer-events: none;
            }

            body.dino-theme .page-title-box.stats-hero h4 {
                color: #fff !important;
                font-family: 'DM Serif Display', Georgia, serif !important;
                font-weight: 400 !important;
                font-size: 28px !important;
            }

            body.dino-theme .page-title-box.stats-hero p { color: rgba(255, 255, 255, .78) !important; }
            body.dino-theme .page-title-box.stats-hero .breadcrumb-item a,
            body.dino-theme .page-title-box.stats-hero .breadcrumb-item.active,
            body.dino-theme .page-title-box.stats-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255, 255, 255, .7) !important; }

            .stats-filters .card-body { padding: 18px 20px !important; }
            .stats-filters .form-label {
                color: var(--dino-muted) !important;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            @media (min-width: 768px) {
                .stats-filters .row > .col-md-4:last-child {
                    flex: 0 0 100%;
                    max-width: 100%;
                    margin-top: 4px;
                }
            }

            .stats-filters #estadisticasTabs { justify-content: flex-start !important; }
            .stats-filters .nav-pills-custom {
                background: var(--dino-surface-2) !important;
                border: 1px solid var(--dino-border);
                border-radius: 12px;
                padding: 5px;
                gap: 4px;
                width: max-content;
                max-width: 100%;
                overflow-x: auto;
            }

            .stats-filters .nav-pills-custom .nav-link {
                border-radius: 9px !important;
                padding: 8px 16px !important;
                font-size: 12.5px !important;
                font-weight: 700 !important;
                color: var(--dino-muted) !important;
                box-shadow: none !important;
            }

            .stats-filters .nav-pills-custom .nav-link.active { color: #fff !important; }
            .stats-filters .nav-pills-custom .nav-link.servicios.active { background: linear-gradient(135deg, #2f9e7a, #6fc2a3) !important; }
            .stats-filters .nav-pills-custom .nav-link.productos.active { background: linear-gradient(135deg, #4f9dc4, #6fc2a3) !important; }
            .stats-filters .nav-pills-custom .nav-link.stock.active { background: linear-gradient(135deg, #7c3aed, #a855f7) !important; }

            .stats-kpis .kpi-card .card-body {
                display: flex;
                flex-direction: column;
                gap: 4px;
                padding: 18px 20px;
            }

            .kpi-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--dino-mint-soft);
                color: var(--dino-green);
                font-size: 1.1rem;
                margin-bottom: 8px;
            }

            .kpi-icon-green { background: #e9f8f1; color: #2f9e7a; }
            .kpi-icon-teal { background: #e9f4fa; color: #4f9dc4; }
            .kpi-icon-lilac { background: var(--dino-lilac-soft); color: var(--dino-lilac); }

            .kpi-label {
                font-size: 10.5px;
                font-weight: 800;
                letter-spacing: .08em;
                text-transform: uppercase;
                color: var(--dino-muted);
            }

            .kpi-value {
                font-size: 1.55rem;
                font-weight: 800;
                line-height: 1.1;
                color: var(--dino-heading);
                letter-spacing: -.02em;
            }

            .kpi-sub { font-size: 11.5px; color: var(--dino-muted); }

            .kpi-featured {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 62%, #2f9e7a 100%) !important;
                border: 0 !important;
            }

            .kpi-featured .kpi-icon { background: rgba(255, 255, 255, .16); color: #fff; }
            .kpi-featured .kpi-label,
            .kpi-featured .kpi-sub { color: rgba(255, 255, 255, .75); }
            .kpi-featured .kpi-value { color: #fff; }

            .stat-card-header {
                background: var(--dino-surface) !important;
                border-bottom: 1px solid var(--dino-border) !important;
                border-left: 0 !important;
                padding: 15px 20px !important;
                gap: 11px !important;
            }

            .stat-card-header > i,
            .stat-card-header .d-flex.align-items-center.gap-2 > i {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: var(--dino-mint-soft);
                color: var(--dino-green) !important;
                font-size: 1rem !important;
                flex: 0 0 34px;
            }

            .stat-card-header.productos > i,
            .stat-card-header.productos .d-flex.align-items-center.gap-2 > i { background: #e9f4fa; color: #4f9dc4 !important; }
            .stat-card-header.stock > i,
            .stat-card-header.stock .d-flex.align-items-center.gap-2 > i { background: var(--dino-lilac-soft); color: var(--dino-lilac) !important; }
            .stat-card-header h6 { font-size: 14px !important; font-weight: 800 !important; color: var(--dino-heading) !important; margin: 0; }
            .stat-card-body { padding: 18px 20px !important; }

            .btn-tendencia { border-radius: 8px; font-size: 11.5px; }
            .btn-tendencia.active { background: var(--dino-green) !important; color: #fff !important; }
            .btn-group-tendencia { background: var(--dino-surface-2); border: 1px solid var(--dino-border); padding: 4px; border-radius: 10px; }

            .summary-stat {
                text-align: left;
                padding: 18px 20px;
                border-radius: 15px;
                background: var(--dino-surface) !important;
                border: 1px solid var(--dino-border) !important;
            }

            .summary-stat .icon-wrapper {
                margin: 0 0 10px;
                width: 40px;
                height: 40px;
                border-radius: 11px;
                background: var(--dino-mint-soft) !important;
            }

            .summary-stat .icon-wrapper i { color: var(--dino-green) !important; }
            .summary-stat.ok h3 { color: var(--dino-green) !important; }
            .summary-stat.bajo .icon-wrapper { background: #fff4e5 !important; }
            .summary-stat.bajo .icon-wrapper i { color: #b45309 !important; }
            .summary-stat.bajo h3 { color: #b45309 !important; }
            .summary-stat.agotado .icon-wrapper { background: #fdecec !important; }
            .summary-stat.agotado .icon-wrapper i { color: #dc2626 !important; }
            .summary-stat.agotado h3 { color: #dc2626 !important; }
            .summary-stat.categoria .icon-wrapper { background: var(--dino-lilac-soft) !important; }
            .summary-stat.categoria .icon-wrapper i { color: var(--dino-lilac) !important; }
            .summary-stat.categoria h3 { color: var(--dino-lilac) !important; }
            .summary-stat h3 { font-size: 1.5rem !important; }
            .summary-stat p { font-size: 11px !important; }

            .badge-status.ok { background: var(--dino-mint-soft) !important; color: #1d7d5f !important; }
            .badge-status.bajo { background: #fff4e5 !important; color: #b45309 !important; }
            .badge-status.agotado { background: #fdecec !important; color: #dc2626 !important; }
            .badge-status.cantidad { background: #e9f4fa !important; color: #2c6c8c !important; }

            .filter-dropdown {
                border-radius: 9px;
                border: 1px solid var(--dino-border);
                background: var(--dino-surface);
                color: var(--dino-text);
                font-size: 12px;
                padding: 7px 12px;
            }

            [data-layout-mode="dark"] body.dino-theme .card.kpi-featured {
                background: linear-gradient(135deg, #12343b 0%, #1f5e50 60%, #2f9e7a 100%) !important;
            }

            [data-layout-mode="dark"] .kpi-icon { background: #173b35; color: #6fc2a3; }
            [data-layout-mode="dark"] .kpi-icon-teal { background: #173136; color: #8fc8e4; }
            [data-layout-mode="dark"] .kpi-icon-lilac { background: #2a1f45; color: #c4b5fd; }
            [data-layout-mode="dark"] .stat-card-header > i,
            [data-layout-mode="dark"] .stat-card-header .d-flex.align-items-center.gap-2 > i { background: #173b35; color: #6fc2a3 !important; }
            [data-layout-mode="dark"] .stat-card-header.productos > i,
            [data-layout-mode="dark"] .stat-card-header.productos .d-flex.align-items-center.gap-2 > i { background: #173136; color: #8fc8e4 !important; }
            [data-layout-mode="dark"] .stat-card-header.stock > i,
            [data-layout-mode="dark"] .stat-card-header.stock .d-flex.align-items-center.gap-2 > i { background: #2a1f45; color: #c4b5fd !important; }

            @media (max-width: 767.98px) {
                .stats-hero { padding: 18px 16px !important; }
                body.dino-theme .page-title-box.stats-hero { flex-direction: column; align-items: flex-start; }
                .stats-hero h4 { font-size: 22px !important; }
                .stats-filters #estadisticasTabs { width: 100%; }
                .stats-filters .nav-pills-custom .nav-link { padding: 8px 12px !important; font-size: 11.5px !important; }
                .stats-kpis .kpi-value { font-size: 1.35rem; }
                .stat-card-header { padding: 13px 15px !important; }
                .stat-card-body { padding: 14px 15px !important; }
            }
        </style>

        {{-- ===== TABS CONTENT ===== --}}
        <div class="tab-content" id="estadisticasTabContent">
            {{-- TAB SERVICIOS --}}
            <div class="tab-pane fade show active" id="tab-servicios" role="tabpanel">
                {{-- KPIs del periodo --}}
                <div class="row g-3 mb-4 stats-kpis animate-card">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card kpi-card kpi-featured h-100">
                            <div class="card-body">
                                <div class="kpi-icon"><i class="ri-money-dollar-circle-line"></i></div>
                                <div class="kpi-label">Ingresos por servicios</div>
                                <div class="kpi-value" id="ingresos-servicios-total">Bs 0.00</div>
                                <div class="kpi-sub" id="ingresos-servicios-detalle">0 sesiones realizadas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card kpi-card h-100">
                            <div class="card-body">
                                <div class="kpi-icon kpi-icon-green"><i class="ri-calendar-check-line"></i></div>
                                <div class="kpi-label">Sesiones realizadas</div>
                                <div class="kpi-value" id="kpi-sesiones-total">0</div>
                                <div class="kpi-sub">En el periodo seleccionado</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card kpi-card h-100">
                            <div class="card-body">
                                <div class="kpi-icon kpi-icon-teal"><i class="ri-sun-line"></i></div>
                                <div class="kpi-label">Tarifa normal</div>
                                <div class="kpi-value" id="ingresos-servicios-normal">Bs 0.00</div>
                                <div class="kpi-sub" id="ingresos-servicios-normal-detalle">0 sesiones</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card kpi-card h-100">
                            <div class="card-body">
                                <div class="kpi-icon kpi-icon-lilac"><i class="ri-star-fill"></i></div>
                                <div class="kpi-label">Tarifa especial</div>
                                <div class="kpi-value" id="ingresos-servicios-especial">Bs 0.00</div>
                                <div class="kpi-sub" id="ingresos-servicios-especial-detalle">0 sesiones</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-4 animate-card">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header servicios">
                                <i class="ri-bar-chart-box-line"></i>
                                <h6>Top 5 Servicios Más Usados</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-servicios-top"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4 animate-card">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header servicios d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-line-chart-line"></i>
                                    <h6 class="mb-0">Tendencia de Sesiones</h6>
                                </div>
                                <div class="btn-group-tendencia">
                                    <button class="btn-tendencia active" data-type="semanal"
                                        onclick="cambiarTendencia('semanal')">
                                        <i class="ri-calendar-week-line"></i>Semanal
                                    </button>
                                    <button class="btn-tendencia" data-type="diaria"
                                        onclick="cambiarTendencia('diaria')">
                                        <i class="ri-calendar-line"></i>Diaria
                                    </button>
                                </div>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-servicios-tendencia"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row animate-card">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header servicios">
                                <i class="ri-node-tree"></i>
                                <h6>Desglose: Paquete → Plan → Servicio</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="tabla-desglose-servicios">
                                        <thead>
                                            <tr>
                                                <th style="background: var(--cream); color: var(--turquoise-dark);">Paquete</th>
                                                <th style="background: var(--cream); color: var(--turquoise-dark);">Plan</th>
                                                <th style="background: var(--cream); color: var(--turquoise-dark);">Servicio</th>
                                                <th style="background: var(--cream); color: var(--turquoise-dark);" class="text-center">Cantidad</th>
                                                <th style="background: var(--cream); color: #0d9488;" class="text-end" title="Ingreso por sesiones con tarifa normal (planes.precio)">Monto Normal</th>
                                                <th style="background: var(--cream); color: #b45309;" class="text-end" title="Ingreso por sesiones con tarifa especial (planes.precio_especial)">Monto Especial</th>
                                                <th style="background: var(--cream); color: var(--turquoise-dark);" class="text-end">Monto Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB PRODUCTOS --}}
            <div class="tab-pane fade" id="tab-productos" role="tabpanel">
                <div class="row">
                    <div class="col-lg-4 mb-4 animate-card">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header productos">
                                <i class="ri-shopping-cart-line"></i>
                                <h6>Venta Directa</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-productos-directa"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 animate-card">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header productos">
                                <i class="ri-calendar-check-line"></i>
                                <h6>Venta por Sesión</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-productos-sesion"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 animate-card">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header productos">
                                <i class="ri-pie-chart-line"></i>
                                <h6>Comparación</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-productos-comparacion"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row animate-card">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header productos">
                                <i class="ri-line-chart-line"></i>
                                <h6>Tendencia de Ventas</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-productos-tendencia"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB STOCK --}}
            <div class="tab-pane fade" id="tab-stock" role="tabpanel">
                <div class="stat-summary-grid">
                    <div class="summary-stat ok animate-card">
                        <div class="icon-wrapper">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <h3 id="stock-ok-count">0</h3>
                        <p>Stock OK</p>
                    </div>
                    <div class="summary-stat bajo animate-card">
                        <div class="icon-wrapper">
                            <i class="ri-alert-line"></i>
                        </div>
                        <h3 id="stock-bajo-count">0</h3>
                        <p>Stock Bajo</p>
                    </div>
                    <div class="summary-stat agotado animate-card">
                        <div class="icon-wrapper">
                            <i class="ri-forbid-line"></i>
                        </div>
                        <h3 id="stock-agotado-count">0</h3>
                        <p>Agotado</p>
                    </div>
                    <div class="summary-stat categoria animate-card">
                        <div class="icon-wrapper">
                            <i class="ri-pie-chart-fill"></i>
                        </div>
                        <h3 id="stock-categoria-count">0</h3>
                        <p>Categorías</p>
                    </div>
                </div>
                <div class="row mb-3 animate-card">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header stock">
                                <i class="ri-pie-chart-line"></i>
                                <h6 class="mb-0">Distribución por categoría</h6>
                            </div>
                            <div class="stat-card-body">
                                <div class="chart-container" id="chart-stock-categoria"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row animate-card">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="stat-card-header stock d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-alert-line"></i>
                                    <h6 class="mb-0">Productos con Stock Bajo/Agotado</h6>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted" style="font-size: 0.85rem;"><i
                                                class="ri-filter-3-line me-1"></i>Filtrar:</span>
                                        <select class="filter-dropdown" id="filtro-stock">
                                            <option value="todos">Todos los estados</option>
                                            <option value="bajo">Solo Stock Bajo</option>
                                            <option value="agotado">Solo Agotados</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="tabla-stock">
                                        <thead>
                                            <tr>
                                                <th style="background: var(--cream); color: var(--coral-dark);">Producto</th>
                                                <th style="background: var(--cream); color: var(--coral-dark);">Categoría</th>
                                                <th style="background: var(--cream); color: var(--coral-dark);" class="text-center">Stock</th>
                                                <th style="background: var(--cream); color: var(--coral-dark);" class="text-center">Mínimo</th>
                                                <th style="background: var(--cream); color: var(--coral-dark);" class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        let chartServiciosTop = null;
        let chartServiciosTendencia = null;
        let chartProductosDirecta = null;
        let chartProductosSesion = null;
        let chartProductosComparacion = null;
        let chartProductosTendencia = null;
        let chartStockCategoria = null;

        let datosServicios = null;
        let tendenciaActual = 'semanal';

        const isDark = document.documentElement.getAttribute('data-layout-mode') === 'dark';

        // Paleta A1, alineada con el tema del sistema
        const colors = {
            dinoGreen: '#2f9e7a',
            dinoGreenDark: '#1f7a63',
            dinoGreenLight: '#6fc2a3',
            turquoise: '#6fc2a3',
            turquoiseDark: '#4f9dc4',
            turquoiseLight: '#bfe3e0',
            coralDark: '#d96b5b',
            coralSpot: '#f2a9a0',
            lilac: '#7c3aed',
            lilacLight: '#c4b5fd',
            textPrimary: isDark ? '#e8f1ee' : '#22333b',
            textSecondary: isDark ? '#9db3af' : '#6f8085',
            bgCard: isDark ? '#12272b' : '#ffffff',
            borderColor: isDark ? '#24423f' : '#e8e4dc',
            cream: isDark ? '#173136' : '#f7f6f2',
        };

        const chartDefaults = {
            chart: {
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit',
                background: 'transparent'
            },
            theme: {
                mode: document.documentElement.getAttribute('data-layout-mode') === 'dark' ? 'dark' : 'light'
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            grid: {
                borderColor: colors.borderColor
            }
        };

        function cargarServicios() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            fetch(`/admin/dashboard/estadisticas/servicios?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
                .then(res => res.json())
                .then(data => {
                    datosServicios = data;

                    const fmtBs = v => 'Bs ' + Number(v || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const total = Number(data.total || 0);

                    // KPIs: se actualizan siempre, incluso cuando el periodo no tiene servicios
                    document.getElementById('ingresos-servicios-total').textContent = fmtBs(data.totalMonto);
                    document.getElementById('ingresos-servicios-detalle').textContent = `${total} sesiones realizadas`;
                    document.getElementById('kpi-sesiones-total').textContent = total;
                    document.getElementById('ingresos-servicios-normal').textContent = fmtBs(data.totalMontoNormal);
                    document.getElementById('ingresos-servicios-normal-detalle').textContent =
                        `${Number(data.cantidadNormal || 0)} sesiones · tarifa normal`;
                    document.getElementById('ingresos-servicios-especial').textContent = fmtBs(data.totalMontoEspecial);
                    document.getElementById('ingresos-servicios-especial-detalle').textContent =
                        `${Number(data.cantidadEspecial || 0)} sesiones · tarifa especial`;

                    if (!data.topServicios || data.topServicios.length === 0) {
                        document.getElementById('chart-servicios-top').innerHTML =
                            '<div class="empty-state"><i class="ri-inbox-line"></i><p>No hay datos en el período seleccionado</p></div>';
                        renderizarTendencia();
                        return;
                    }

                    const servicios = data.topServicios.map(s => s.nombre);
                    const cantidades = data.topServicios.map(s => s.cantidad);

                    if (chartServiciosTop) chartServiciosTop.destroy();
                    chartServiciosTop = new ApexCharts(document.querySelector("#chart-servicios-top"), {
                        ...chartDefaults,
                        series: [{
                            data: cantidades
                        }],
                        chart: {
                            ...chartDefaults.chart,
                            type: 'bar',
                            height: 280
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                borderRadius: 8,
                                distributed: true
                            }
                        },
                        colors: [colors.dinoGreen, colors.lilac, colors.dinoGreenLight, colors.lilacLight, colors.turquoise],
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ['#fff'],
                                fontWeight: 600
                            }
                        },
                        xaxis: {
                            categories: servicios,
                            labels: {
                                style: {
                                    colors: colors.textSecondary
                                }
                            }
                        },
                        title: {
                            text: `Total: ${data.total} sesiones`,
                            align: 'left',
                            style: {
                                fontSize: '13px',
                                fontWeight: 600,
                                color: colors.textPrimary
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            borderColor: colors.borderColor
                        }
                    });
                    chartServiciosTop.render();

                    renderizarTendencia();

                    // Desglose con montos
                    const tbody = document.querySelector('#tabla-desglose-servicios tbody');
                    tbody.innerHTML = data.desglose.map(d => `
                        <tr>
                            <td class="fw-semibold">${d.paquete}</td>
                            <td>${d.plan}</td>
                            <td>${d.servicio}</td>
                            <td class="text-center"><span class="badge-status cantidad">${d.cantidad}</span></td>
                            <td class="text-end fw-semibold" style="color:#0d9488;">${fmtBs(d.monto_normal)}</td>
                            <td class="text-end fw-semibold" style="color:#b45309;">${fmtBs(d.monto_especial)}</td>
                            <td class="text-end fw-bold" style="color:var(--turquoise-dark);">${fmtBs(d.monto)}</td>
                        </tr>
                    `).join('');
                });
        }

        function renderizarTendencia() {
            if (!datosServicios) return;

            if (chartServiciosTendencia) chartServiciosTendencia.destroy();

            const tendencia = tendenciaActual === 'semanal' ? datosServicios.tendenciaSemanal : datosServicios
                .tendenciaDiaria;
            const labels = tendenciaActual === 'semanal' ?
                tendencia.map(t => t.semana) :
                tendencia.map(t => t.dia);

            if (tendencia.length > 0) {
                chartServiciosTendencia = new ApexCharts(document.querySelector("#chart-servicios-tendencia"), {
                    ...chartDefaults,
                    series: [{
                        name: 'Sesiones',
                        data: tendencia.map(t => t.cantidad)
                    }],
                    chart: {
                        ...chartDefaults.chart,
                        type: 'area',
                        height: 280
                    },
                    colors: [colors.dinoGreen],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.5,
                            opacityTo: 0.1
                        }
                    },
                    xaxis: {
                        categories: labels,
                        labels: {
                            style: {
                                colors: colors.textSecondary
                            }
                        }
                    },
                    grid: {
                        borderColor: colors.borderColor
                    },
                    markers: {
                        size: 4,
                        strokeWidth: 2
                    }
                });
                chartServiciosTendencia.render();
            }
        }

        function cambiarTendencia(tipo) {
            tendenciaActual = tipo;

            document.querySelectorAll('.btn-tendencia').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.btn-tendencia[data-type="${tipo}"]`).classList.add('active');

            renderizarTendencia();
        }

        function cargarProductos() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            fetch(`/admin/dashboard/estadisticas/productos?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
                .then(res => res.json())
                .then(data => {
                    if (data.topDirecta.length === 0 && data.topSesion.length === 0) {
                        ['chart-productos-directa', 'chart-productos-sesion', 'chart-productos-comparacion', 'chart-productos-tendencia']
                            .forEach(id => {
                                document.getElementById(id).innerHTML =
                                    '<div class="empty-state"><i class="ri-inbox-line"></i><p>No hay datos en el período</p></div>';
                            });
                        return;
                    }

                    if (chartProductosDirecta) chartProductosDirecta.destroy();
                    if (data.topDirecta.length > 0) {
                        chartProductosDirecta = new ApexCharts(document.querySelector("#chart-productos-directa"), {
                            ...chartDefaults,
                            series: [{
                                data: data.topDirecta.map(p => p.cantidad)
                            }],
                            chart: {
                                ...chartDefaults.chart,
                                type: 'bar',
                                height: 220
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 8,
                                    columnWidth: '60%'
                                }
                            },
                            colors: [colors.turquoiseDark],
                            dataLabels: {
                                enabled: true,
                                style: {
                                    colors: ['#fff'],
                                    fontWeight: 600
                                }
                            },
                            xaxis: {
                                categories: data.topDirecta.map(p => p.nombre.substring(0, 12) + '...'),
                                labels: {
                                    style: {
                                        colors: colors.textSecondary
                                    }
                                }
                            },
                            grid: {
                                borderColor: colors.borderColor
                            }
                        });
                        chartProductosDirecta.render();
                    } else {
                        document.getElementById('chart-productos-directa').innerHTML =
                            '<div class="empty-state"><i class="ri-inbox-line"></i><p>Sin ventas directas en el período</p></div>';
                    }

                    if (chartProductosSesion) chartProductosSesion.destroy();
                    if (data.topSesion.length > 0) {
                        chartProductosSesion = new ApexCharts(document.querySelector("#chart-productos-sesion"), {
                            ...chartDefaults,
                            series: [{
                                data: data.topSesion.map(p => p.cantidad)
                            }],
                            chart: {
                                ...chartDefaults.chart,
                                type: 'bar',
                                height: 220
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 8,
                                    columnWidth: '60%'
                                }
                            },
                            colors: [colors.dinoGreen],
                            dataLabels: {
                                enabled: true,
                                style: {
                                    colors: ['#fff'],
                                    fontWeight: 600
                                }
                            },
                            xaxis: {
                                categories: data.topSesion.map(p => p.nombre.substring(0, 12) + '...'),
                                labels: {
                                    style: {
                                        colors: colors.textSecondary
                                    }
                                }
                            },
                            grid: {
                                borderColor: colors.borderColor
                            }
                        });
                        chartProductosSesion.render();
                    } else {
                        document.getElementById('chart-productos-sesion').innerHTML =
                            '<div class="empty-state"><i class="ri-inbox-line"></i><p>Sin ventas por sesión en el período</p></div>';
                    }

                    if (chartProductosComparacion) chartProductosComparacion.destroy();
                    chartProductosComparacion = new ApexCharts(document.querySelector("#chart-productos-comparacion"), {
                        ...chartDefaults,
                        series: [data.totales.directa, data.totales.sesion],
                        chart: {
                            ...chartDefaults.chart,
                            type: 'donut',
                            height: 220
                        },
                        labels: ['Venta Directa', 'Venta por Sesión'],
                        colors: [colors.dinoGreen, colors.lilac],
                        legend: {
                            position: 'bottom',
                            fontSize: '12px'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '13px',
                                            fontWeight: 600
                                        }
                                    }
                                }
                            }
                        }
                    });
                    chartProductosComparacion.render();

                    if (chartProductosTendencia) chartProductosTendencia.destroy();
                    if (data.tendencia.length > 0) {
                        chartProductosTendencia = new ApexCharts(document.querySelector(
                            "#chart-productos-tendencia"), {
                            ...chartDefaults,
                            series: [{
                                name: 'Ventas',
                                data: data.tendencia.map(t => t.cantidad)
                            }],
                            chart: {
                                ...chartDefaults.chart,
                                type: 'line',
                                height: 280
                            },
                            colors: [colors.turquoiseDark],
                            xaxis: {
                                categories: data.tendencia.map(t => t.mes),
                                labels: {
                                    style: {
                                        colors: colors.textSecondary
                                    }
                                }
                            },
                            grid: {
                                borderColor: colors.borderColor
                            },
                            markers: {
                                size: 5,
                                strokeWidth: 0,
                                fillOpacity: 1
                            }
                        });
                        chartProductosTendencia.render();
                    } else {
                        document.getElementById('chart-productos-tendencia').innerHTML =
                            '<div class="empty-state"><i class="ri-inbox-line"></i><p>Sin datos de tendencia en el período</p></div>';
                    }
                });
        }

        function cargarStock() {
            const filtroStock = document.getElementById('filtro-stock').value;

            fetch(`/admin/dashboard/estadisticas/stock?filtro_stock=${filtroStock}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('stock-ok-count').textContent = data.stats.ok;
                    document.getElementById('stock-bajo-count').textContent = data.stats.bajo;
                    document.getElementById('stock-agotado-count').textContent = data.stats.agotado;
                    document.getElementById('stock-categoria-count').textContent = data.resumenCategoria.length;

                    if (chartStockCategoria) chartStockCategoria.destroy();
                    if (data.resumenCategoria.length > 0) {
                        chartStockCategoria = new ApexCharts(document.querySelector("#chart-stock-categoria"), {
                            ...chartDefaults,
                            series: data.resumenCategoria.map(c => c.cantidad),
                            chart: {
                                ...chartDefaults.chart,
                                type: 'donut',
                                height: 180
                            },
                            labels: data.resumenCategoria.map(c => c.categoria),
                            legend: {
                                show: false
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '60%'
                                    }
                                }
                            },
                            colors: [colors.dinoGreen, colors.lilac, colors.turquoise, colors.coralSpot,
                                colors.lilacLight, colors.dinoGreenLight
                            ]
                        });
                        chartStockCategoria.render();
                    }

                    const tbody = document.querySelector('#tabla-stock tbody');
                    tbody.innerHTML = data.productos.map(p => `
                        <tr>
                            <td class="fw-semibold">${p.nombre}</td>
                            <td>${p.categoria}</td>
                            <td class="text-center">${p.stock}</td>
                            <td class="text-center">${p.stock_minimo}</td>
                            <td class="text-center"><span class="badge-status ${p.estado}">${p.estado.toUpperCase()}</span></td>
                        </tr>
                    `).join('');
                });
        }

        // Helpers para sincronizar mes/gestión → fechas
        function sincronizarFechasDesdeMesGestion() {
            const mes = parseInt(document.getElementById('selectMes').value);
            const gestion = parseInt(document.getElementById('selectGestion').value);
            if (!mes || !gestion) return;
            const inicio = new Date(gestion, mes - 1, 1);
            const fin    = new Date(gestion, mes, 0); // último día del mes
            const fmt = d => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            document.getElementById('fecha_inicio').value = fmt(inicio);
            document.getElementById('fecha_fin').value    = fmt(fin);
        }

        document.getElementById('selectMes').addEventListener('change', () => {
            sincronizarFechasDesdeMesGestion();
            cargarServicios();
        });
        document.getElementById('selectGestion').addEventListener('change', () => {
            sincronizarFechasDesdeMesGestion();
            cargarServicios();
        });

        document.getElementById('fecha_inicio').addEventListener('change', () => {
            cargarServicios();
            cargarProductos();
        });
        document.getElementById('fecha_fin').addEventListener('change', () => {
            cargarServicios();
            cargarProductos();
        });
        document.getElementById('filtro-stock').addEventListener('change', cargarStock);

        function aplicarFiltros() {
            sincronizarFechasDesdeMesGestion();
            cargarServicios();
            cargarProductos();
        }

        document.getElementById('servicios-tab').addEventListener('shown.bs.tab', function() {
            cargarServicios();
        });
        document.getElementById('productos-tab').addEventListener('shown.bs.tab', function() {
            cargarProductos();
        });
        document.getElementById('stock-tab').addEventListener('shown.bs.tab', function() {
            cargarStock();
        });

        cargarServicios();
    </script>
    @endpush
@endsection