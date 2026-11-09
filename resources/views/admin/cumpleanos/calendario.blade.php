@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <style>
            body.dino-theme .page-title-box.cumple-hero {
                background: linear-gradient(135deg, #0f3b43 0%, #1f7a63 50%, #6d28d9 130%) !important;
                border: 0 !important;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                padding: 22px 26px !important;
            }

            body.dino-theme .page-title-box.cumple-hero h4 {
                color: #fff !important;
                font-family: 'DM Serif Display', Georgia, serif !important;
                font-weight: 400 !important;
                font-size: 28px !important;
            }

            body.dino-theme .page-title-box.cumple-hero p,
            body.dino-theme .page-title-box.cumple-hero .text-white-50,
            body.dino-theme .page-title-box.cumple-hero .breadcrumb-item a,
            body.dino-theme .page-title-box.cumple-hero .breadcrumb-item.active {
                color: rgba(255, 255, 255, .78) !important;
            }

            body.dino-theme .page-title-box.cumple-hero .breadcrumb-item + .breadcrumb-item::before {
                color: rgba(255, 255, 255, .45) !important;
            }

            .cumple-hero .page-title-right { flex-wrap: wrap; }
            .cumple-reset {
                color: #fff !important;
                border-color: rgba(255, 255, 255, .55) !important;
                background: rgba(255, 255, 255, .08) !important;
            }
            .cumple-reset:hover { background: rgba(255, 255, 255, .16) !important; }

            .cumple-stats > div:nth-child(1) .avatar-title { background: var(--dino-mint-soft) !important; color: var(--dino-green) !important; }
            .cumple-stats > div:nth-child(2) .avatar-title { background: var(--dino-lilac-soft) !important; color: var(--dino-lilac) !important; }
            .cumple-stats > div:nth-child(3) .avatar-title { background: #e9f4fa !important; color: #4f9dc4 !important; }
            .cumple-stats > div:nth-child(4) .avatar-title { background: #fff4e5 !important; color: #b45309 !important; }

            .cumple-calendar-header .form-select { min-width: 0 !important; }
            .cumple-calendar-header .d-flex.gap-2 { flex-wrap: wrap; }

            .cumple-legend > .badge:nth-child(1) { background: var(--dino-mint-soft) !important; color: #1d7d5f !important; border-color: #c9eeda !important; }
            .cumple-legend > .badge:nth-child(2) { background: var(--dino-lilac-soft) !important; color: #6d28d9 !important; border-color: #e9d5ff !important; }
            .cumple-legend > .badge:nth-child(3) { background: #e9f4fa !important; color: #2c6c8c !important; border-color: #cfe8f4 !important; }
            .cumple-legend > .badge:nth-child(4) { background: #fff4e5 !important; color: #b45309 !important; border-color: #f2e2b8 !important; }

            .cumple-grid { table-layout: fixed; width: 100%; }
            .cumple-grid thead th {
                text-align: center;
                font-size: 10.5px;
                font-weight: 800;
                letter-spacing: .06em;
                text-transform: uppercase;
                color: var(--dino-muted);
                padding: 10px 4px;
                border-bottom: 1px solid var(--dino-border) !important;
            }

            .cumple-grid .cumple-day {
                vertical-align: top;
                padding: 7px !important;
                border: 1px solid var(--dino-border) !important;
                background: var(--dino-surface);
                width: 14.2857%;
            }

            .cumple-cell { min-height: 132px; }

            .cumple-day-number {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 800;
                color: var(--dino-heading);
                background: transparent;
            }

            .cumple-day-number.today {
                background: var(--dino-green);
                color: #fff;
                box-shadow: 0 0 0 3px rgba(47, 158, 122, .16);
            }

            .birthday-badge {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
                font-size: 11.5px;
                border-radius: 9px;
            }

            .birthday-badge .text-truncate {
                max-width: 100%;
                min-width: 0;
            }

            .birthday-badge:hover {
                text-decoration: none !important;
                background: #fff !important;
                transform: translateX(2px);
                box-shadow: 0 6px 16px rgba(15, 59, 67, .12);
            }

            .cumple-mobile-list { display: flex; flex-direction: column; gap: 12px; }

            .cumple-mobile-day {
                display: flex;
                gap: 12px;
                padding: 12px;
                border: 1px solid var(--dino-border);
                border-radius: 14px;
                background: var(--dino-surface-2);
            }

            .cumple-mobile-date { flex: 0 0 48px; text-align: center; }
            .cumple-mobile-date .num { display: block; font-size: 22px; font-weight: 800; line-height: 1.1; color: var(--dino-heading); }
            .cumple-mobile-date .wd { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: var(--dino-muted); }
            .cumple-mobile-items { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 6px; }

            .cumple-empty { text-align: center; padding: 40px 18px; color: var(--dino-muted); }
            .cumple-empty i { font-size: 2.5rem; color: var(--dino-mint); display: block; margin-bottom: 8px; }

            @media (max-width: 991.98px) {
                body.dino-theme .page-title-box.cumple-hero { flex-direction: column; align-items: flex-start; }
                .cumple-calendar-header > .d-flex { flex-direction: column; align-items: flex-start !important; gap: 12px; }
                .cumple-calendar-header .d-flex.gap-2 { width: 100%; }
                .cumple-calendar-header .form-select { width: 100%; }
            }

            @media (max-width: 767.98px) {
                body.dino-theme .page-title-box.cumple-hero { padding: 18px 16px !important; }
                body.dino-theme .page-title-box.cumple-hero h4 { font-size: 22px !important; }
                .cumple-legend { gap: 8px !important; }
            }

            [data-layout-mode="dark"] body.dino-theme .page-title-box.cumple-hero {
                background: linear-gradient(135deg, #0b1b1e 0%, #173b35 55%, #4c1d95 135%) !important;
            }

            [data-layout-mode="dark"] .cumple-grid .cumple-day,
            [data-layout-mode="dark"] .cumple-mobile-day {
                background: var(--dino-surface);
                border-color: var(--dino-border) !important;
            }

            [data-layout-mode="dark"] .birthday-badge:hover { background: var(--dino-surface-2) !important; }
        </style>

        <!-- Page Title con Gradiente Personalizado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box cumple-hero">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-cake-2-line me-2"></i>Calendario de Cumpleaños
                        </h4>
                        <p class="text-white-50 mb-0">
                            {{ Carbon\Carbon::create($anio, $mes)->locale('es')->isoFormat('MMMM [de] YYYY') }}
                        </p>
                    </div>
                    <div class="page-title-right d-flex gap-2">
                        <a href="{{ route('admin.cumpleanos.calendario', ['mes' => $mes - 1, 'anio' => $mes == 1 ? $anio - 1 : $anio]) }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-left-line me-1"></i>Anterior
                        </a>
                        <a href="{{ route('admin.cumpleanos.calendario', ['mes' => $mes + 1, 'anio' => $mes == 12 ? $anio + 1 : $anio]) }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            Siguiente<i class="ri-arrow-right-line ms-1"></i>
                        </a>
                        <a href="{{ route('admin.cumpleanos.calendario') }}"
                            class="btn btn-outline-light btn-lg rounded-pill px-4 cumple-reset" title="Mes actual">
                            <i class="ri-refresh-line"></i>
                            <span class="d-lg-none ms-2">Mes actual</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 cumple-stats">
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
                                <p class="text-muted mb-1 fw-medium">Total Cumpleaños</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $stats['total'] }}</h4>
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
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Clientes</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $stats['clientes'] }}</h4>
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
                                        <i class="ri-user-settings-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Trabajadores</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $stats['trabajadores'] }}</h4>
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
                                        <i class="ri-baby-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Hijos</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $stats['hijos'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-bottom py-3 px-4 cumple-calendar-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle me-3" style="background: rgba(244, 223, 22, 0.15);">
                            <span class="avatar-title" style="color: #856404;">
                                <i class="ri-cake-line fs-5"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Calendario Mensual</h5>
                            <small class="text-muted">Haz clic en un cumpleaños para enviar saludo por WhatsApp</small>
                        </div>
                    </div>

                    <!-- Selector rápido de mes -->
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm rounded-pill"
                            style="min-width: 150px; border-color: #1a2d4c;"
                            onchange="window.location.href='?mes='+this.value+'&anio={{ $anio }}'">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                        <select class="form-select form-select-sm rounded-pill"
                            style="min-width: 100px; border-color: #1a2d4c;"
                            onchange="window.location.href='?mes={{ $mes }}&anio='+this.value">
                            @for ($a = $anio - 2; $a <= $anio + 2; $a++)
                                <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>
                                    {{ $a }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Leyenda de Roles -->
                <div class="d-flex flex-wrap gap-3 mb-4 pb-3 border-bottom cumple-legend">
                    <span class="badge px-3 py-2 rounded-pill"
                        style="background: rgba(26, 45, 76, 0.1); color: #1a2d4c; border: 1px solid #1a2d4c;">
                        <i class="ri-user-star-line me-1"></i>Cliente
                    </span>
                    <span class="badge px-3 py-2 rounded-pill"
                        style="background: rgba(3, 126, 131, 0.1); color: #037e83; border: 1px solid #037e83;">
                        <i class="ri-user-settings-line me-1"></i>Trabajador
                    </span>
                    <span class="badge px-3 py-2 rounded-pill"
                        style="background: rgba(244, 223, 22, 0.15); color: #856404; border: 1px solid #f4df16;">
                        <i class="ri-baby-line me-1"></i>Hijo
                    </span>
                    <span class="badge px-3 py-2 rounded-pill"
                        style="background: rgba(111, 66, 193, 0.1); color: #6f42c1; border: 1px solid #6f42c1;">
                        <i class="ri-double-quotes-l me-1"></i>Cliente/Trabajador
                    </span>
                    <span class="badge px-3 py-2 rounded-pill bg-warning text-dark">
                        <i class="ri-time-line me-1"></i>Hoy
                    </span>
                    <span class="badge px-3 py-2 rounded-pill bg-light text-muted border">
                        <i class="ri-phone-off-line me-1"></i>Sin teléfono
                    </span>
                </div>

                <!-- Lista móvil: evita el scroll horizontal del calendario -->
                <div class="d-lg-none cumple-mobile-list">
                    @forelse ($cumpleanosPorDia->sortKeys() as $dia => $items)
                        @php $fechaCumple = Carbon\Carbon::create($anio, $mes, (int) $dia); @endphp
                        <div class="cumple-mobile-day">
                            <div class="cumple-mobile-date">
                                <span class="num">{{ str_pad((string) $dia, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="wd">{{ $fechaCumple->locale('es')->isoFormat('ddd') }}</span>
                            </div>
                            <div class="cumple-mobile-items">
                                @foreach ($items as $cumple)
                                    @include('admin.cumpleanos.partials.birthday-badge', ['cumple' => $cumple])
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="cumple-empty">
                            <i class="ri-cake-line"></i>
                            <p>No hay cumpleaños registrados en este mes.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Calendario Grid (escritorio) -->
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-borderless mb-0 cumple-grid">
                        <thead>
                            <tr class="text-center">
                                @foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                                    <th class="py-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">
                                        {{ $dia }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCeldas = ceil(($diasEnMes + $offset) / 7) * 7;
                                $primerDiaMes = Carbon\Carbon::create($anio, $mes, 1);
                                $hoy = Carbon\Carbon::today();
                            @endphp
                            @for ($i = 0; $i < $totalCeldas; $i++)
                                @if ($i % 7 == 0)
                                    <tr>
                                @endif
                                @php
                                    $diaNumero = $i - $offset + 1;
                                    $esDiaValido = $diaNumero >= 1 && $diaNumero <= $diasEnMes;
                                    $fechaActual = $esDiaValido ? $primerDiaMes->copy()->addDays($diaNumero - 1) : null;
                                    $esHoy = $fechaActual && $fechaActual->isSameDay($hoy);
                                    $cumpleanosDelDia = $cumpleanosPorDia->get($diaNumero, collect());
                                @endphp
                                <td class="cumple-day">
                                    @if ($esDiaValido)
                                        <div class="position-relative cumple-cell">
                                            <!-- Número del día -->
                                            <span class="cumple-day-number {{ $esHoy ? 'today' : '' }}">
                                                {{ $diaNumero }}
                                                @if ($esHoy)
                                                    <i class="ri-star-fill ms-1 fs-6"></i>
                                                @endif
                                            </span>

                                            <!-- Lista de cumpleaños -->
                                            <div class="d-flex flex-column gap-1">
                                                @forelse ($cumpleanosDelDia as $cumple)
                                                    @php
                                                        $tieneTelefono = !empty($cumple['celular']);
                                                        $waLink = $tieneTelefono
                                                            ? "https://wa.me/{$cumple['celular']}?text=" .
                                                                urlencode($cumple['mensaje'])
                                                            : '#';
                                                    @endphp
                                                    <a href="{{ $waLink }}" target="_blank"
                                                        class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none birthday-badge"
                                                        style="background: {{ $cumple['color_hex'] }}15; border-left: 3px solid {{ $cumple['color_hex'] }}; transition: all 0.2s ease;"
                                                        onmouseover="this.style.transform='translateX(3px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                                        onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none'"
                                                        title="{{ $cumple['rol_detalle'] }} • {{ $cumple['edad'] }} años">
                                                        <i class="{{ $cumple['icono'] }}"
                                                            style="color: {{ $cumple['color_hex'] }};"></i>
                                                        <div class="flex-grow-1 min-w-0">
                                                            <div class="fw-semibold text-truncate small"
                                                                style="color: #1a2d4c;">
                                                                {{ $cumple['nombre'] }}
                                                            </div>
                                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                                {{ $cumple['edad'] }} años
                                                            </div>
                                                        </div>
                                                        @if ($tieneTelefono)
                                                            <i class="ri-whatsapp-line fs-5" style="color: #25D366;"></i>
                                                        @else
                                                            <i class="ri-phone-off-line text-muted fs-6"></i>
                                                        @endif
                                                    </a>
                                                @empty
                                                    @if (!$esHoy)
                                                        <span class="text-muted small opacity-50">—</span>
                                                    @endif
                                                @endforelse
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                @if ($i % 7 == 6)
                                    </tr>
                                @endif
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>
    </div>
@endsection

@push('style')
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

        /* Cards */
        .card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Badges de cumpleaños */
        .birthday-badge {
            font-size: 0.85rem;
            border-radius: 10px;
        }

        .birthday-badge:hover {
            text-decoration: none !important;
            background: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
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

            .birthday-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
            }

            td {
                min-height: 100px !important;
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
    <script>
        // Función toast para notificaciones
        function showToast(type, message) {
            const config = {
                success: {
                    icon: 'ri-checkbox-circle-fill',
                    bg: '#198754',
                    title: 'Éxito'
                },
                error: {
                    icon: 'ri-close-circle-fill',
                    bg: '#dc3545',
                    title: 'Error'
                },
                warning: {
                    icon: 'ri-alert-fill',
                    bg: '#ffc107',
                    title: 'Advertencia'
                },
                info: {
                    icon: 'ri-information-fill',
                    bg: '#0dcaf0',
                    title: 'Información'
                }
            };
            const cfg = config[type] || config.info;
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast text-white shadow-lg border-0`;
            toast.style.background = cfg.bg;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
        <div class="toast-header text-white border-0 py-2" style="background: ${cfg.bg}cc;">
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

        // Mostrar toast si hay mensaje de sesión
        @session('success')
        showToast('success', '{{ session('success') }}');
        @endsession
        @session('error')
        showToast('error', '{{ session('error') }}');
        @endsession

        // Confirmación al enviar WhatsApp
        document.querySelectorAll('.birthday-badge').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href.includes('wa.me')) {
                    e.preventDefault();
                    const nombre = this.querySelector('.fw-semibold').textContent;
                    if (href === '#') {
                        showToast('warning', `⚠️ ${nombre} no tiene número de teléfono registrado`);
                    } else {
                        showToast('info', `📱 Abriendo WhatsApp para saludar a ${nombre}...`);
                        setTimeout(() => window.open(href, '_blank'), 500);
                    }
                }
            });
        });
    </script>
@endpush
