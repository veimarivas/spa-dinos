@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <style>
            body.dino-theme .page-title-box.sessions-hero {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 52%, #6d28d9 135%) !important;
                border: 0 !important;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                padding: 22px 26px !important;
            }

            body.dino-theme .page-title-box.sessions-hero h4 {
                color: #fff !important;
                font-family: 'DM Serif Display', Georgia, serif !important;
                font-weight: 400 !important;
                font-size: 28px !important;
            }

            body.dino-theme .page-title-box.sessions-hero p,
            body.dino-theme .page-title-box.sessions-hero .text-white-50,
            body.dino-theme .page-title-box.sessions-hero .breadcrumb-item a,
            body.dino-theme .page-title-box.sessions-hero .breadcrumb-item.active {
                color: rgba(255, 255, 255, .78) !important;
            }

            body.dino-theme .page-title-box.sessions-hero .breadcrumb-item + .breadcrumb-item::before {
                color: rgba(255, 255, 255, .45) !important;
            }

            .sessions-stats > div:nth-child(1) .avatar-title { background: var(--dino-mint-soft) !important; color: var(--dino-green) !important; }
            .sessions-stats > div:nth-child(2) .avatar-title { background: #e9f4fa !important; color: #4f9dc4 !important; }
            .sessions-stats > div:nth-child(3) .avatar-title { background: #fff4e5 !important; color: #b45309 !important; }
            .sessions-stats > div:nth-child(4) .avatar-title { background: var(--dino-lilac-soft) !important; color: var(--dino-lilac) !important; }

            .sessions-calendar-header .search-box { flex: 1 1 280px; }
            .sessions-calendar-header .form-select { min-width: 0 !important; }

            #calendar {
                --fc-page-bg-color: var(--dino-surface);
                --fc-neutral-bg-color: var(--dino-surface-2);
                --fc-neutral-text-color: var(--dino-muted);
                --fc-border-color: var(--dino-border);
                --fc-button-bg-color: var(--dino-green);
                --fc-button-border-color: var(--dino-green);
                --fc-button-hover-bg-color: #24845d;
                --fc-button-hover-border-color: #24845d;
                --fc-button-active-bg-color: #1f7a63;
                --fc-button-active-border-color: #1f7a63;
                --fc-today-bg-color: rgba(111, 194, 163, .10);
                --fc-event-bg-color: var(--dino-green);
                --fc-event-border-color: transparent;
                --fc-event-text-color: #fff;
                --fc-highlight-color: rgba(111, 194, 163, .18);

                background: var(--dino-surface);
                border: 1px solid var(--dino-border);
                border-radius: 14px;
                padding: 12px;
                min-height: 520px;
            }

            .fc { font-family: 'Manrope', system-ui, -apple-system, 'Segoe UI', sans-serif; }
            .fc .fc-toolbar { flex-wrap: wrap; gap: 8px; }
            .fc .fc-toolbar-title {
                font-family: 'DM Serif Display', Georgia, serif;
                font-size: 1.35rem;
                color: var(--dino-heading);
                letter-spacing: -.01em;
            }

            .fc .fc-button-primary {
                background: var(--dino-green);
                border: 0;
                border-radius: 8px;
                box-shadow: none;
                padding: 6px 12px;
                font-weight: 700;
                font-size: 12px;
                text-transform: capitalize;
            }

            .fc .fc-button-primary:hover { background: #24845d; }
            .fc .fc-button-primary:not(:disabled).fc-button-active,
            .fc .fc-button-primary:not(:disabled):active { background: #1f7a63; }
            .fc .fc-today-button { background: var(--dino-lilac) !important; }

            .fc-theme-standard td,
            .fc-theme-standard th { border-color: var(--dino-border); }
            .fc .fc-col-header-cell { background: var(--dino-mint-soft); }
            .fc .fc-col-header-cell-cushion {
                color: #2b4a44;
                font-weight: 800;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .03em;
                padding: 8px 4px;
                white-space: normal;
            }

            .fc .fc-daygrid-day-number,
            .fc .fc-timegrid-slot-label-cushion {
                color: var(--dino-muted);
                font-size: 11px;
                font-weight: 600;
            }

            .fc .fc-timegrid-slot { height: 36px; }
            .fc .fc-event {
                border: 0;
                border-radius: 6px;
                font-size: 11px;
                padding: 2px 4px;
            }
            .fc .fc-event-title { font-weight: 700; }
            .fc .fc-scrollgrid { border-radius: 10px; overflow: hidden; }
            .fc .fc-day-today { background: rgba(111, 194, 163, .08) !important; }
            .fc .fc-event-tarifa-especial { box-shadow: inset 3px 0 0 #b45309; }

            @media (max-width: 991.98px) {
                body.dino-theme .page-title-box.sessions-hero { flex-direction: column; align-items: flex-start; }
            }

            @media (max-width: 767.98px) {
                body.dino-theme .page-title-box.sessions-hero { padding: 18px 16px !important; }
                body.dino-theme .page-title-box.sessions-hero h4 { font-size: 22px !important; }
                #calendar { padding: 6px; min-height: 420px; }
                .fc .fc-toolbar-title { font-size: 1rem; }
                .fc .fc-button { padding: 4px 8px; font-size: 10.5px; }
                .fc .fc-col-header-cell-cushion { font-size: 9px; padding: 6px 1px; }
                .fc .fc-timegrid-slot { height: 30px; }
                .sessions-calendar-header > .d-flex { flex-direction: column; align-items: flex-start !important; gap: 10px; }
                .sessions-calendar-header .search-box,
                .sessions-calendar-header .search-box select { width: 100%; min-width: 0 !important; }
            }

            [data-layout-mode="dark"] body.dino-theme .page-title-box.sessions-hero {
                background: linear-gradient(135deg, #0b1b1e 0%, #173b35 55%, #4c1d95 135%) !important;
            }

            [data-layout-mode="dark"] #calendar { background: var(--dino-surface); }
            [data-layout-mode="dark"] .fc .fc-col-header-cell { background: #173b35; }
            [data-layout-mode="dark"] .fc .fc-col-header-cell-cushion { color: #bfe9da; }
        </style>

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box sessions-hero">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-calendar-event-line me-2"></i>Calendario de Sesiones
                        </h4>
                        <p class="text-white-50 mb-0">Visualiza y gestiona todas las sesiones programadas</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.suscripciones.index') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-left-line me-1"></i>Volver a Suscripciones
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 sessions-stats">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle fs-24"
                                        style="background: rgba(26, 45, 76, 0.1); color: #1a2d4c;">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Total Sesiones</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ count($eventos) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle fs-24"
                                        style="background: rgba(3, 126, 131, 0.1); color: #037e83;">
                                        <i class="ri-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Confirmadas</p>
                                <h4 class="mb-0 fw-bold text-dark">
                                    {{ collect($eventos)->where('extendedProps.estado', 'confirmada')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle fs-24"
                                        style="background: rgba(244, 223, 22, 0.15); color: #856404;">
                                        <i class="ri-time-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Programadas</p>
                                <h4 class="mb-0 fw-bold text-dark">
                                    {{ collect($eventos)->where('extendedProps.estado', 'programada')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle fs-24"
                                        style="background: rgba(26, 45, 76, 0.1); color: #1a2d4c;">
                                        <i class="ri-user-smile-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Fisioterapeutas</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $fisioterapeutas->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-bottom py-3 px-4 sessions-calendar-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle me-3" style="background: rgba(3, 126, 131, 0.1);">
                            <span class="avatar-title" style="color: #037e83;">
                                <i class="ri-calendar-check-line fs-5"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Calendario Interactivo</h5>
                            <small class="text-muted">
                                @if ($trabajadorId)
                                    <span class="badge bg-warning text-dark">
                                        <i class="ri-user-line me-1"></i>Filtrado:
                                        {{ $fisioterapeutas->firstWhere('id', $trabajadorId)?->persona?->nombres }}
                                        {{ $fisioterapeutas->firstWhere('id', $trabajadorId)?->persona?->apellido_paterno }}
                                    </span>
                                    <span class="ms-2 text-muted">👉 Mostrando sesiones por hijo</span>
                                @else
                                    Vista general de todos los fisioterapeutas
                                @endif
                            </small>
                        </div>
                    </div>

                    <!-- Filtro por fisioterapeuta -->
                    <div class="d-flex gap-2 align-items-center">
                        <div class="search-box position-relative">
                            <select id="trabajadorFilter" class="form-select form-select-lg ps-5 rounded-pill border-2"
                                onchange="filtrarTrabajador(this.value)" style="min-width: 280px; border-color: #1a2d4c;">
                                <option value="">👥 Todos los fisioterapeutas</option>
                                @foreach ($fisioterapeutas as $trabajador)
                                    <option value="{{ $trabajador->id }}"
                                        {{ ($trabajadorId ?? '') == $trabajador->id ? 'selected' : '' }}>
                                        {{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="ri-user-settings-line position-absolute top-50 start-0 translate-middle-y text-muted ms-3"
                                style="pointer-events: none;"></i>
                        </div>
                        @if ($trabajadorId)
                            <a href="{{ route('admin.calendario.sesiones') }}"
                                class="btn btn-outline-secondary rounded-pill px-3">
                                <i class="ri-close-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Leyenda de estados -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3" style="color: #1a2d4c;">
                                    <i class="ri-flag-line me-2" style="color: #037e83;"></i>Estado de las Sesiones
                                </h6>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($coloresPorEstado as $estado => $color)
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                style="display:inline-block; width:20px; height:20px;
                                                    background-color:{{ $color }};
                                                    border-radius:6px;
                                                    border: 2px solid {{ $color === '#FFFFFF' ? '#dee2e6' : 'transparent' }};
                                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></span>
                                            <span class="text-muted fw-medium">{{ ucfirst($estado) }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Leyenda tarifa --}}
                                <hr class="my-3" style="border-color: rgba(0,0,0,0.06);">
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.5px;font-size:.7rem;">
                                        <i class="ri-price-tag-3-line me-1"></i>Tarifa:
                                    </small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="display:inline-block; width:18px; height:18px;
                                            background:#0d9488; border-radius:5px;"></span>
                                        <span class="text-muted small fw-medium">Normal</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="display:inline-block; width:18px; height:18px;
                                            background:#fff; border-radius:5px;
                                            border-left:4px solid #b45309;
                                            box-shadow: 0 0 0 1px #fcd34d, 0 1px 3px rgba(180,83,9,0.25);"></span>
                                        <span class="fw-medium" style="color:#b45309;">⭐ Especial</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3" style="color: #1a2d4c;">
                                    <i class="ri-palette-line me-2" style="color: #037e83;"></i>
                                    @if ($trabajadorId)
                                        Color del Cliente
                                    @else
                                        Fisioterapeutas Activos
                                    @endif
                                </h6>
                                @if ($trabajadorId)
                                    <div class="alert border-0 rounded-3 mb-0"
                                        style="background: rgba(3, 126, 131, 0.08); border-left: 4px solid #037e83;">
                                        <i class="ri-information-line me-2" style="color: #037e83;"></i>
                                        <strong class="text-dark">Vista Individual:</strong>
                                        <span class="text-muted">El color de fondo de cada evento corresponde al cliente de
                                            la sesión.</span>
                                    </div>
                                @elseif ($fisioterapeutas->count() > 0)
                                    <div class="d-flex flex-wrap gap-3" style="max-height: 120px; overflow-y: auto;">
                                        @foreach ($fisioterapeutas as $trabajador)
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    style="display:inline-block; width:20px; height:20px; 
                                                        background-color:{{ $trabajador->color ?? '#1a2d4c' }}; 
                                                        border-radius:6px; 
                                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></span>
                                                <span class="text-muted small">
                                                    {{ $trabajador->persona->nombres }}
                                                    {{ $trabajador->persona->apellido_paterno }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert border-0 rounded-3 mb-0"
                                        style="background: rgba(244, 223, 22, 0.15);">
                                        <i class="ri-alert-line me-2" style="color: #856404;"></i>
                                        No hay fisioterapeutas registrados.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="alert border rounded-3 mb-4"
                    style="background: rgba(26, 45, 76, 0.05); border-left: 4px solid #1a2d4c;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm rounded-circle flex-shrink-0" style="background: rgba(26, 45, 76, 0.1);">
                            <span class="avatar-title" style="color: #1a2d4c;">
                                <i class="ri-information-line"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #1a2d4c;">Información Importante</h6>
                            <p class="text-muted mb-0 small">
                                <i class="ri-refresh-line me-1" style="color: #f4df16;"></i>
                                Las sesiones reprogramadas tienen el fondo mezclado con rojo y muestran el icono ↻
                                <span class="mx-2">|</span>
                                <i class="ri-eye-line me-1"></i>
                                Haz clic en cualquier evento para ver los detalles completos de la sesión.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Calendario -->
                <div id="calendar" class="rounded-3 overflow-hidden"></div>
            </div>
        </div>
    </div>

    <!-- Modal de detalle de sesión -->
    @include('partials.modal-detalle-sesion')

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        /* ===== PALETA PERSONALIZADA ===== */
        :root {
            --color-primary: #1a2d4c;
            --color-secondary: #037e83;
            --color-accent: #f4df16;
            --color-primary-light: rgba(26, 45, 76, 0.1);
            --color-secondary-light: rgba(3, 126, 131, 0.1);
            --color-accent-light: rgba(244, 223, 22, 0.15);
        }

        /* Calendar Styles */
        #calendar {
            height: 700px;
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .fc {
            font-family: 'Public Sans', system-ui, -apple-system, sans-serif !important;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: var(--color-primary) !important;
        }

        .fc-button-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            transition: all 0.3s ease !important;
        }

        .fc-button-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(26, 81, 137, 0.3) !important;
        }

        .fc-button-primary:not(:disabled).fc-button-active {
            background: linear-gradient(135deg, #14233a 0%, #02656a 100%) !important;
        }

        .fc-daygrid-day-number {
            font-weight: 600 !important;
            color: var(--color-primary) !important;
        }

        .fc-col-header-cell-cushion {
            font-weight: 700 !important;
            color: var(--color-primary) !important;
            padding: 12px !important;
        }

        .fc-event {
            cursor: pointer;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
            border: none !important;
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease !important;
        }

        .fc-event:hover {
            transform: scale(1.02) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            z-index: 10 !important;
        }

        /* ===== TARIFA ESPECIAL: borde ámbar + glow sutil ===== */
        .fc-event-tarifa-especial {
            border-left: 4px solid #b45309 !important;
            box-shadow: 0 0 0 1px #fcd34d, 0 2px 6px rgba(180, 83, 9, 0.25) !important;
            position: relative;
        }
        .fc-event-tarifa-especial::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 0; height: 0;
            border-style: solid;
            border-width: 0 12px 12px 0;
            border-color: transparent #b45309 transparent transparent;
            pointer-events: none;
        }
        .fc-event-tarifa-especial:hover {
            box-shadow: 0 0 0 2px #fcd34d, 0 6px 18px rgba(180, 83, 9, 0.35) !important;
        }

        .fc-timegrid-slot-label-cushion,
        .fc-timegrid-axis-cushion {
            font-weight: 500 !important;
            color: #6c757d !important;
        }

        .fc-scrollgrid {
            border-radius: 12px !important;
            overflow: hidden !important;
        }

        .fc-scrollgrid-section>* {
            border-color: #e9ecef !important;
        }

        /* Search box */
        .search-box select {
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .search-box select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-light);
            outline: none;
        }

        /* Cards */
        .card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Avatar helpers */
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-lg {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* Alertas */
        .alert {
            border-left: 4px solid;
        }

        .alert-info {
            border-left-color: var(--color-secondary);
            background-color: var(--color-secondary-light);
        }

        .alert-warning {
            border-left-color: var(--color-accent);
            background-color: var(--color-accent-light);
        }

        /* Badge personalizado */
        .badge {
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px;
            }

            #calendar {
                height: 500px;
            }

            .fc-toolbar {
                flex-direction: column;
                gap: 10px;
            }

            .fc-toolbar-title {
                font-size: 1.1rem !important;
            }
        }

        /* Toast Styles */
        .toast {
            border-radius: 12px !important;
            overflow: hidden;
            border: none !important;
        }

        .toast-header {
            padding: 12px 16px !important;
            font-weight: 600;
            border-bottom: none !important;
        }

        .toast-body {
            padding: 12px 16px !important;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('backend/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        // ===== FUNCIÓN PARA FILTRAR POR TRABAJADOR =====
        function filtrarTrabajador(trabajadorId) {
            let url = new URL(window.location.href);
            if (trabajadorId && trabajadorId !== '') {
                url.searchParams.set('trabajador_id', trabajadorId);
            } else {
                url.searchParams.delete('trabajador_id');
            }
            // Mostrar loader visual
            const calendarEl = document.getElementById('calendar');
            calendarEl.style.opacity = '0.6';
            calendarEl.style.pointerEvents = 'none';

            window.location.href = url.toString();
        }

        // ===== FUNCIÓN TOAST =====
        function showToast(type, message) {
            const config = {
                success: {
                    icon: 'ri-checkbox-circle-fill',
                    bg: 'bg-success',
                    title: 'Éxito',
                    color: '#198754'
                },
                error: {
                    icon: 'ri-close-circle-fill',
                    bg: 'bg-danger',
                    title: 'Error',
                    color: '#dc3545'
                },
                warning: {
                    icon: 'ri-alert-fill',
                    bg: 'bg-warning',
                    title: 'Advertencia',
                    color: '#856404'
                },
                info: {
                    icon: 'ri-information-fill',
                    bg: 'bg-info',
                    title: 'Información',
                    color: '#0dcaf0'
                }
            };
            const cfg = config[type] || config.info;
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast text-white shadow-lg border-0`;
            toast.style.background = cfg.color;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
        <div class="toast-header text-white border-0 py-2" style="background: ${cfg.color}cc;">
            <i class="${cfg.icon} me-2"></i>
            <strong class="me-auto">${cfg.title}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body d-flex align-items-center py-2">
            <i class="${cfg.icon} me-2"></i>
            <span class="flex-grow-1">${message}</span>
        </div>`;

            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '1090';
                document.body.appendChild(container);
            }
            container.appendChild(toast);

            new bootstrap.Toast(toast, {
                autohide: true,
                delay: 4000
            }).show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        // ===== FUNCIÓN PARA MOSTRAR DETALLE DE SESIÓN =====
        window.mostrarDetalleSesion = function(sesionId) {
            const modalEl = document.getElementById('detalleSesionModal');
            if (!modalEl) {
                console.error('❌ Modal no encontrado: #detalleSesionModal');
                showToast('error', 'Error de interfaz: modal no cargado');
                return;
            }

            const modal = new bootstrap.Modal(modalEl);
            const loader = document.getElementById('detalle-loader');
            const contenido = document.getElementById('detalle-contenido');
            const btnSuscripcion = document.getElementById('btnVerSuscripcion');

            loader.style.display = 'block';
            contenido.innerHTML = '';
            if (btnSuscripcion) btnSuscripcion.href = '#';
            modal.show();

            fetch(`/admin/sesiones/${sesionId}/detalle`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    loader.style.display = 'none';
                    renderDetalleSesion(data, contenido, btnSuscripcion);
                })
                .catch(error => {
                    loader.style.display = 'none';
                    console.error('❌ Error detallado:', error);
                    contenido.innerHTML = `
                <div class="alert border-0 rounded-4 shadow-sm" 
                     style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="ri-error-warning-line fs-4 mt-1" style="color: #dc3545;"></i>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #1a2d4c;">No se pudo cargar la sesión</h6>
                            <p class="mb-0 small text-muted">${error.message}</p>
                            <button class="btn btn-sm rounded-pill px-3 mt-2" 
                                    onclick="window.mostrarDetalleSesion(${sesionId})"
                                    style="background: linear-gradient(135deg, #1a2d4c 0%, #037e83 100%); border: none; color: #fff;">
                                <i class="ri-refresh-line me-1"></i>Reintentar
                            </button>
                        </div>
                    </div>
                </div>`;
                    showToast('error', 'Error: ' + error.message);
                });
        };

        // ===== FUNCIÓN PARA RENDERIZAR EL CONTENIDO DEL MODAL =====
        function renderDetalleSesion(data, container, btnSuscripcion) {
            const estadoBadge = (estado) => {
                const config = {
                    'programada': {
                        class: 'badge-warning',
                        icon: 'ri-time-line',
                        text: 'Programada',
                        color: '#856404'
                    },
                    'confirmada': {
                        class: 'badge-info',
                        icon: 'ri-checkbox-circle-line',
                        text: 'Confirmada',
                        color: '#037e83'
                    },
                    'realizada': {
                        class: 'badge-primary',
                        icon: 'ri-check-double-line',
                        text: 'Realizada',
                        color: '#1a2d4c'
                    },
                    'cancelada': {
                        class: 'badge-danger',
                        icon: 'ri-close-circle-line',
                        text: 'Cancelada',
                        color: '#dc3545'
                    },
                    'reprogramada': {
                        class: 'badge-secondary',
                        icon: 'ri-refresh-line',
                        text: 'Reprogramada',
                        color: '#6c757d'
                    }
                };
                const c = config[estado] || config['programada'];
                return `<span class="badge ${c.class} shadow-sm" 
                     style="background: ${c.color}20; color: ${c.color}; border: 1px solid ${c.color}; padding: 0.5rem 1.25rem; border-radius: 30px; font-weight: 600;">
            <i class="${c.icon} me-1"></i>${c.text}
        </span>`;
            };

            const iconCard = (icon, color) => `
        <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0" 
             style="width: 44px; height: 44px; background: ${color}15; color: ${color};">
            <i class="${icon} fs-5"></i>
        </div>`;

            // Badge de tarifa
            const tarifaBadge = (tarifa) => {
                if (tarifa === 'especial') {
                    return `<span class="badge shadow-sm"
                        style="background:#fef3c7;color:#b45309;border:1px solid #fcd34d;padding:0.5rem 1.25rem;border-radius:30px;font-weight:600;">
                        <i class="ri-star-fill me-1"></i>Tarifa Especial
                    </span>`;
                }
                return `<span class="badge shadow-sm"
                    style="background:#f0fdf4;color:#0d9488;border:1px solid #5eead4;padding:0.5rem 1.25rem;border-radius:30px;font-weight:600;">
                    <i class="ri-sun-line me-1"></i>Tarifa Normal
                </span>`;
            };

            let html = `
        <div class="row g-3">
            <!-- Header con sesión y estado -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4" style="background: #f8fafc;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            ${iconCard('ri-hashtag', '#1a2d4c')}
                            <div>
                                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.8px;">Sesión</div>
                                <div class="fw-bold" style="color: #1a2d4c; font-size: 1.1rem;">#${data.n_sesion}</div>
                                <small class="text-muted">
                                    <i class="ri-calendar-line me-1"></i>${data.fecha}
                                    <span class="mx-1">•</span>
                                    <i class="ri-time-line me-1"></i>${data.hora_inicio} - ${data.hora_fin}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            ${estadoBadge(data.estado)}
                            ${tarifaBadge(data.tarifa)}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cliente -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            ${iconCard('ri-user-star-line', '#1a2d4c')}
                            <div>
                                <div class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 0.8px;">Cliente</div>
                            </div>
                        </div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.cliente_nombre}</div>
                        <small class="text-muted">
                            <i class="ri-id-card-line me-1"></i>${data.cliente_carnet || 'Sin CI registrado'}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Hijo -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            ${iconCard('ri-baby-line', '#037e83')}
                            <div>
                                <div class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 0.8px;">Hijo/a</div>
                            </div>
                        </div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.hijo_nombre || 'No asignado'}</div>
                        <small class="text-muted">
                            <i class="ri-id-card-line me-1"></i>${data.hijo_carnet || 'Sin CI'}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Paquete / Plan / Servicio -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                    <div class="card-body p-3">
                        <div class="d-flex justify-center mb-2">
                            ${iconCard('ri-stack-line', '#f4df16'.replace('#', ''))}
                        </div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.8px;">Paquete</div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.paquete}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                    <div class="card-body p-3">
                        <div class="d-flex justify-center mb-2">
                            ${iconCard('ri-survey-line', '#037e83')}
                        </div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.8px;">Plan</div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.plan}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                    <div class="card-body p-3">
                        <div class="d-flex justify-center mb-2">
                            ${iconCard('ri-service-line', '#1a2d4c')}
                        </div>
                        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.8px;">Servicio</div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.servicio}</div>
                    </div>
                </div>
            </div>

            <!-- Trabajador -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            ${iconCard('ri-user-settings-line', '#037e83')}
                            <div>
                                <div class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 0.8px;">Fisioterapeuta</div>
                            </div>
                        </div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.trabajador || 'No asignado'}</div>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            ${iconCard('ri-calendar-event-line', '#1a2d4c')}
                            <div>
                                <div class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 0.8px;">Agendado</div>
                            </div>
                        </div>
                        <div class="fw-bold" style="color: #1a2d4c;">${data.fecha}</div>
                        <small class="text-muted">
                            <i class="ri-time-line me-1"></i>${data.hora_inicio} - ${data.hora_fin}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Informe (si existe) -->
            ${data.informe ? `
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ri-file-text-line fs-5" style="color: #1a2d4c;"></i>
                                    <h6 class="fw-bold mb-0" style="color: #1a2d4c;">Informe de la sesión</h6>
                                </div>
                                <p class="mb-0 text-muted small" style="line-height: 1.6; white-space: pre-wrap;">${data.informe}</p>
                            </div>
                        </div>
                    </div>` : ''}

            <!-- Historial de reprogramaciones -->
            ${data.reprogramar_id ? `
                    <div class="col-12">
                        <div class="alert border-0 rounded-4 mb-0 d-flex align-items-start gap-3" 
                             style="background: rgba(244, 223, 22, 0.15); border-left: 4px solid #f4df16;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
                                 style="width: 36px; height: 36px; background: rgba(244, 223, 22, 0.3); color: #856404;">
                                <i class="ri-refresh-line"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #1a2d4c;">Reprogramada desde</h6>
                                <p class="mb-0 small text-muted">
                                    Esta sesión fue generada a partir de la 
                                    <a href="#" class="fw-bold text-decoration-none" 
                                       style="color: #037e83; border-bottom: 2px dashed #037e83;"
                                       onclick="event.preventDefault(); window.mostrarDetalleSesion(${data.reprogramar_id});">
                                        Sesión #${data.reprogramar_n_sesion}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>` : ''}

            ${data.tiene_reprogramaciones ? `
                    <div class="col-12">
                        <div class="alert border-0 rounded-4 mb-0 d-flex align-items-start gap-3" 
                             style="background: rgba(3, 126, 131, 0.1); border-left: 4px solid #037e83;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
                                 style="width: 36px; height: 36px; background: rgba(3, 126, 131, 0.2); color: #037e83;">
                                <i class="ri-arrow-go-forward-line"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #1a2d4c;">Tiene reprogramaciones</h6>
                                <p class="mb-0 small text-muted">
                                    Esta sesión fue reprogramada en la 
                                    <a href="#" class="fw-bold text-decoration-none"
                                       style="color: #037e83; border-bottom: 2px dashed #037e83;"
                                       onclick="event.preventDefault(); window.mostrarDetalleSesion(${data.reprogramacion_id});">
                                        Sesión #${data.reprogramacion_n_sesion}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>` : ''}
        </div>
    `;

            container.innerHTML = html;

            if (btnSuscripcion && data.suscripcion_id) {
                btnSuscripcion.href = `/admin/suscripciones/ver/${data.suscripcion_id}`;
                btnSuscripcion.style.display = 'inline-flex';
            } else if (btnSuscripcion) {
                btnSuscripcion.style.display = 'none';
            }
        }

        // ===== INICIALIZACIÓN DEL CALENDARIO =====
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Restaurar opacidad si estaba en loading
            calendarEl.style.opacity = '1';
            calendarEl.style.pointerEvents = 'auto';

            var isMobileCalendar = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobileCalendar ? 'timeGridDay' : 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: isMobileCalendar ? 'timeGridDay,timeGridWeek' : 'timeGridWeek,timeGridDay'
                },
                slotMinTime: '{{ $slotMinTime }}',
                slotMaxTime: '{{ $slotMaxTime }}',
                slotDuration: '{{ $slotDuration }}',
                height: 'auto',
                locale: 'es',
                buttonText: {
                    today: 'Hoy',
                    week: 'Semana',
                    day: 'Día'
                },
                events: @json($eventos),
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventClick: function(info) {
                    window.mostrarDetalleSesion(info.event.id);
                },
                eventDidMount: function(info) {
                    const props = info.event.extendedProps;

                    // Tooltip con info adicional (incluyendo tarifa)
                    const tarifaLabel = props.tarifa === 'especial' ? '⭐ Especial' : '☀ Normal';
                    const tooltip = `${info.event.title}\n` +
                        `👤 ${props.cliente || ''}\n` +
                        `👶 ${props.hijo || ''}\n` +
                        `🏷 Tarifa: ${tarifaLabel}\n` +
                        `📋 Estado: ${props.estado || ''}`;
                    info.el.setAttribute('title', tooltip);

                    // ===== DIFERENCIACIÓN VISUAL POR TARIFA =====
                    if (props.tarifa === 'especial') {
                        info.el.classList.add('fc-event-tarifa-especial');
                        // Insertar pequeña estrella al inicio del título visible
                        const mainEl = info.el.querySelector('.fc-event-title') || info.el.querySelector('.fc-event-main-frame');
                        if (mainEl && !mainEl.dataset.tarifaMarked) {
                            const star = document.createElement('span');
                            star.innerHTML = '⭐ ';
                            star.style.cssText = 'margin-right:2px;filter:drop-shadow(0 0 1px rgba(0,0,0,.3));';
                            mainEl.prepend(star);
                            mainEl.dataset.tarifaMarked = '1';
                        }
                    }
                },
                // Ajustes visuales para mejor legibilidad
                dayHeaderFormat: {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short'
                },
                allDayText: 'Todo el día',
                moreLinkText: 'más',
                noEventsText: 'No hay sesiones programadas'
            });

            calendar.render();

            // Mostrar toast de sesión si existe
            @if (session('success'))
                showToast('success', '{{ session('success') }}');
            @endif
            @if (session('error'))
                showToast('error', '{{ session('error') }}');
            @endif
        });
    </script>
@endpush
