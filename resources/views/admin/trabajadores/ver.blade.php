@extends('admin.dashboard')
@section('admin')
    @php
        $nombre = $trabajador->persona->nombres . ' ' . $trabajador->persona->apellido_paterno .
                  ($trabajador->persona->apellido_materno ? ' ' . $trabajador->persona->apellido_materno : '');
        $color  = $trabajador->color ?? '#405189';
    @endphp

    <div class="container-fluid">

        {{-- ===== HEADER ===== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between rounded-4 p-4 shadow-sm"
                    style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}cc 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                            style="width:56px;height:56px;background:rgba(255,255,255,0.2);color:#fff;font-size:1.6rem;">
                            <i class="ri-user-settings-line"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-white">{{ $nombre }}</h4>
                            <p class="text-white-50 mb-0">
                                <span class="badge rounded-pill px-3 py-1 me-2" style="background:rgba(255,255,255,0.2);color:#fff;">
                                    <i class="ri-briefcase-line me-1"></i>{{ $trabajador->cargo->nombre ?? 'Sin cargo' }}
                                </span>
                                @if($trabajador->persona->celular)
                                    <span class="text-white-50"><i class="ri-phone-line me-1"></i>{{ $trabajador->persona->celular }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.trabajadores.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-left-line me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== STATS ===== --}}
        <div class="row mb-4 g-3">
            @php
                $statItems = [
                    ['label'=>'Total Sesiones',  'val'=>$stats['total'],       'icon'=>'ri-calendar-line',       'bg'=>'rgba(64,81,137,0.1)',   'clr'=>'#405189'],
                    ['label'=>'Programadas',     'val'=>$stats['programadas'], 'icon'=>'ri-time-line',            'bg'=>'rgba(245,158,11,0.15)', 'clr'=>'#856404'],
                    ['label'=>'Confirmadas',     'val'=>$stats['confirmadas'], 'icon'=>'ri-checkbox-circle-line', 'bg'=>'rgba(25,135,84,0.12)', 'clr'=>'#198754'],
                    ['label'=>'Realizadas',      'val'=>$stats['realizadas'],  'icon'=>'ri-check-double-line',    'bg'=>'rgba(13,110,253,0.12)', 'clr'=>'#0d6efd'],
                    ['label'=>'Canceladas',      'val'=>$stats['canceladas'],  'icon'=>'ri-close-circle-line',    'bg'=>'rgba(220,53,69,0.12)', 'clr'=>'#dc3545'],
                ];
            @endphp
            @foreach ($statItems as $s)
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                    style="width:48px;height:48px;background:{{ $s['bg'] }};color:{{ $s['clr'] }};font-size:1.3rem;">
                                    <i class="{{ $s['icon'] }}"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small fw-medium">{{ $s['label'] }}</p>
                                    <h4 class="mb-0 fw-bold" style="color:{{ $s['clr'] }}">{{ $s['val'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== TABS ===== --}}
        <ul class="nav nav-pills wk-tabs mb-3" id="trabajadorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-informes-btn" data-bs-toggle="pill"
                    data-bs-target="#tab-informes" type="button" role="tab">
                    <i class="ri-file-text-line me-2"></i>Historial de Informes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-salarios-btn" data-bs-toggle="pill"
                    data-bs-target="#tab-salarios" type="button" role="tab">
                    <i class="ri-money-dollar-circle-line me-2"></i>Liquidación de Salarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-calendario-btn" data-bs-toggle="pill"
                    data-bs-target="#tab-calendario" type="button" role="tab">
                    <i class="ri-calendar-event-line me-2"></i>Calendario
                </button>
            </li>
        </ul>

        <div class="tab-content" id="trabajadorTabContent">

        {{-- ===== HISTORIAL DE INFORMES ===== --}}
        <div class="tab-pane fade show active" id="tab-informes" role="tabpanel">
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:38px;height:38px;background:rgba(26,45,76,0.1);color:#1a2d4c;">
                            <i class="ri-file-text-line fs-5"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Historial de Informes</h5>
                            <small class="text-muted">Sesiones realizadas con informe registrado</small>
                        </div>
                    </div>
                    <span id="informesBadge" class="badge rounded-pill px-3 py-2 fw-semibold"
                        style="background:rgba(3,126,131,0.12);color:#037e83;font-size:.85rem;">
                        <i class="ri-file-text-fill me-1"></i><span id="informesTotal">—</span> informes
                    </span>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="card-body border-bottom py-3 px-4" style="background:#f8fafc;">
                <div class="row g-2 align-items-end">
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <label class="form-label small fw-semibold mb-1" style="color:#1a2d4c;">
                            <i class="ri-calendar-line me-1"></i>Gestión
                        </label>
                        <select id="filtroGestion" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                            <option value="">Todas</option>
                            @foreach ($gestionesDisponibles as $g)
                                <option value="{{ $g }}" {{ $g == now()->year ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <label class="form-label small fw-semibold mb-1" style="color:#1a2d4c;">
                            <i class="ri-calendar-2-line me-1"></i>Mes
                        </label>
                        <select id="filtroMes" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                            <option value="">Todos</option>
                            @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                <option value="{{ $i + 1 }}" {{ ($i + 1) == now()->month ? 'selected' : '' }}>{{ $mes }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <label class="form-label small fw-semibold mb-1" style="color:#1a2d4c;">
                            <i class="ri-calendar-todo-line me-1"></i>Día
                        </label>
                        <input type="number" id="filtroDia" min="1" max="31"
                            class="form-control form-control-sm rounded-3 border-0 shadow-sm"
                            placeholder="1 – 31">
                    </div>
                    <div class="col-sm-6 col-md-3 col-xl-3">
                        <label class="form-label small fw-semibold mb-1" style="color:#1a2d4c;">
                            <i class="ri-heart-pulse-line me-1"></i>Servicio
                        </label>
                        <select id="filtroServicio" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                            <option value="">Todos</option>
                            @foreach ($serviciosDisponibles as $s)
                                <option value="{{ $s['id'] }}">{{ $s['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xl-2 d-flex gap-2">
                        <button id="btnFiltrarInformes" class="btn btn-sm rounded-3 fw-semibold flex-fill"
                            style="background:linear-gradient(135deg,#1a2d4c 0%,#037e83 100%);border:none;color:#fff;">
                            <i class="ri-search-line me-1"></i>Filtrar
                        </button>
                        <button id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary rounded-3">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Lista de informes --}}
            <div class="card-body p-4" id="informesContainer">
                <div class="text-center py-5 text-muted">
                    <i class="ri-filter-line fs-1 opacity-50"></i>
                    <p class="mt-2 mb-0">Aplica un filtro o haz clic en <strong>Filtrar</strong> para ver los informes</p>
                </div>
            </div>

            {{-- Paginación de informes --}}
            <div class="card-footer bg-white border-top py-3 px-4 d-none" id="informesPaginacion">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <small class="text-muted" id="informesPaginacionInfo"></small>
                    <div class="btn-group" role="group">
                        <button id="informesPagPrev" class="btn btn-sm btn-outline-secondary rounded-start-pill" title="Mes anterior">
                            <i class="ri-arrow-left-s-line"></i> Mes anterior
                        </button>
                        <button class="btn btn-sm btn-light disabled" id="informesPagIndicador" style="min-width:160px;">—</button>
                        <button id="informesPagNext" class="btn btn-sm btn-outline-secondary rounded-end-pill" title="Mes siguiente">
                            Mes siguiente <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>{{-- /#tab-informes --}}

        {{-- ===== LIQUIDACIÓN DE SALARIOS ===== --}}
        <div class="tab-pane fade" id="tab-salarios" role="tabpanel">
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:38px;height:38px;background:rgba(245,158,11,0.15);color:#b45309;">
                            <i class="ri-money-dollar-circle-line fs-5"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Liquidación de Salarios por Sesión</h5>
                            <small class="text-muted">Pagos pendientes y registrados agrupados por mes</small>
                        </div>
                    </div>
                    <button id="btnRecargarSalarios" class="btn btn-sm btn-outline-secondary rounded-3">
                        <i class="ri-refresh-line me-1"></i>Recargar
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-pills wk-subtabs mb-3" id="salariosSubTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active d-flex align-items-center gap-2" id="sub-pendientes-btn"
                            data-bs-toggle="pill" data-bs-target="#sub-pendientes" type="button" role="tab">
                            <i class="ri-time-line"></i>
                            <span>Por pagar</span>
                            <span class="badge rounded-pill ms-1" id="badgePendientes"
                                style="background:rgba(255,255,255,.18);font-size:.7rem;">0 meses</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-2" id="sub-pagados-btn"
                            data-bs-toggle="pill" data-bs-target="#sub-pagados" type="button" role="tab">
                            <i class="ri-check-double-line"></i>
                            <span>Ya pagados</span>
                            <span class="badge rounded-pill ms-1" id="badgePagados"
                                style="background:rgba(255,255,255,.18);font-size:.7rem;">0 meses</span>
                        </button>
                    </li>
                </ul>


                <div class="tab-content">
                    <div class="tab-pane fade show active" id="sub-pendientes" role="tabpanel">
                        <div id="salariosContainerPendientes">
                            <div class="text-center py-5 text-muted">
                                <div class="spinner-border" role="status" style="color:#b45309;width:2.5rem;height:2.5rem;">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 mb-0">Cargando salarios...</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="sub-pagados" role="tabpanel">
                        <div id="salariosContainerPagados"></div>
                    </div>
                </div>
            </div>
        </div>

        </div>{{-- /#tab-salarios --}}

        {{-- Modal confirmar pago salario --}}
        <div class="modal fade" id="modalPagarSalario" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 py-3 px-4"
                        style="background:linear-gradient(135deg,#b45309 0%,#f59e0b 100%);border-radius:16px 16px 0 0;">
                        <h5 class="modal-title fw-bold text-white">
                            <i class="ri-money-dollar-circle-line me-2"></i>Registrar Pago de Salario
                            <span id="pagoSalarioSubtitle" class="fw-normal text-white-50 fs-6 ms-2"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" id="pagoSalarioAño">
                        <input type="hidden" id="pagoSalarioMes">

                        <div id="pagoSalarioResumen" class="mb-4"></div>

                        <div id="pagoSalarioSinInformeWrap" class="d-none mb-4 p-3 rounded-3" style="background:rgba(108,117,125,.06);border:1px dashed rgba(108,117,125,.3);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pagoSalarioIncluirSinInforme">
                                <label class="form-check-label fw-semibold" for="pagoSalarioIncluirSinInforme" style="color:#1a2d4c;">
                                    <i class="ri-alert-line me-1 text-warning"></i>Incluir sesiones sin informe
                                </label>
                                <div class="text-muted small mt-1" id="pagoSalarioSinInformeDetalle"></div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold" style="color:#1a2d4c;">
                                <i class="ri-sticky-note-line me-1 text-secondary"></i>Observación
                                <span class="text-muted fw-normal small">(opcional)</span>
                            </label>
                            <textarea id="pagoSalarioObservacion" rows="3"
                                class="form-control border-0 shadow-sm rounded-3"
                                placeholder="Ej. Pago correspondiente a sesiones del mes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top py-3 px-4" style="border-radius:0 0 16px 16px;">
                        <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" id="btnConfirmarPagoSalario" class="btn rounded-pill px-4 fw-semibold"
                            style="background:linear-gradient(135deg,#b45309 0%,#f59e0b 100%);border:none;color:#fff;">
                            <i class="ri-check-line me-1"></i>Confirmar Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== CALENDARIO ===== --}}
        <div class="tab-pane fade" id="tab-calendario" role="tabpanel">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:38px;height:38px;background:rgba(3,126,131,0.1);color:#037e83;">
                            <i class="ri-calendar-event-line fs-5"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Calendario de Actividades</h5>
                            <small class="text-muted">Sesiones asignadas a {{ $nombre }}</small>
                        </div>
                    </div>
                    {{-- Leyenda de estados --}}
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($coloresPorEstado as $estado => $color)
                            <div class="d-flex align-items-center gap-2">
                                <span style="display:inline-block;width:16px;height:16px;background-color:{{ $color }};border-radius:4px;"></span>
                                <span class="text-muted small fw-medium">{{ ucfirst($estado) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div id="calendar" class="rounded-3 overflow-hidden"></div>
            </div>
        </div>
        </div>{{-- /#tab-calendario --}}

        </div>{{-- /.tab-content --}}

    </div>

    {{-- Modal detalle sesión --}}
    @include('partials.modal-detalle-sesion')

    {{-- Modal Informe de Sesión --}}
    <div class="modal fade" id="modalInforme" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,#1a2d4c 0%,#037e83 100%);border-radius:20px 20px 0 0;">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-file-text-line me-2"></i>Informe de Sesión
                        <span id="informeModalSubtitle" class="fw-normal text-white-50 fs-6 ms-2"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="background:#fafbfc;">
                    <input type="hidden" id="informeSesionId">
                    <input type="hidden" id="informeAsistioHijo">

                    {{-- Indicador de asistencia (solo lectura) --}}
                    <div id="informeAsistenciaIndicador" class="mb-4 rounded-3 p-3 d-flex align-items-center gap-3"></div>

                    {{-- Informe: visible solo si asistió --}}
                    <div id="informeSection" class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1a2d4c;">
                            <i class="ri-edit-2-line me-1 text-secondary"></i>Informe / Observaciones
                            <span class="text-muted fw-normal small">(opcional)</span>
                        </label>
                        <textarea id="informeTexto" name="informe_sesion" rows="6"
                            class="form-control border-0 shadow-sm"
                            style="border-radius:12px;background:#fff;resize:vertical;"
                            placeholder="Describe los procedimientos realizados, evolución del paciente, observaciones..."></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Opcional — máximo 3000 caracteres</small>
                            <small id="informeCharCount" class="text-muted">0 / 3000</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-3 px-4" style="border-radius:0 0 20px 20px;">
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" id="btnGuardarInforme" class="btn rounded-pill px-4 fw-semibold"
                        style="background:linear-gradient(135deg,#1a2d4c 0%,#037e83 100%);border:none;color:#fff;">
                        <i class="ri-save-3-line me-1"></i>Guardar Informe
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;"></div>
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        :root {
            --wk-color: {{ $trabajador->color ?? '#405189' }};
        }

        #calendar {
            min-height: 680px;
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .fc { font-family: 'Public Sans', system-ui, sans-serif !important; }

        .fc-toolbar-title {
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: #1a2d4c !important;
        }

        .fc-button-primary {
            background: linear-gradient(135deg, #1a2d4c 0%, #037e83 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 7px 14px !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
        }

        .fc-button-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(26,81,137,0.3) !important;
        }

        .fc-button-primary:not(:disabled).fc-button-active {
            background: linear-gradient(135deg, #14233a 0%, #02656a 100%) !important;
        }

        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion {
            font-weight: 700 !important;
            color: #1a2d4c !important;
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 6px !important;
            padding: 3px 7px !important;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }

        .fc-event:hover {
            transform: scale(1.03) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important;
        }

        .fc-scrollgrid { border-radius: 10px !important; overflow: hidden !important; }
        .fc-scrollgrid-section > * { border-color: #e9ecef !important; }

        .fc-timegrid-slot-label-cushion,
        .fc-timegrid-axis-cushion { font-weight: 500 !important; color: #6c757d !important; }

        @media (max-width: 768px) {
            #calendar { min-height: 480px; }
            .fc-toolbar { flex-direction: column; gap: 8px; }
        }

        /* Toast */
        .toast { border-radius: 12px !important; border: none !important; overflow: hidden; }
        .toast-header { padding: 10px 16px !important; font-weight: 600; border-bottom: none !important; }
        .toast-body { padding: 10px 16px !important; }

        /* Historial de informes */
        .informe-card-item { transition: box-shadow 0.2s ease, transform 0.2s ease; }
        .informe-card-item:hover { box-shadow: 0 6px 24px rgba(3,126,131,0.15) !important; transform: translateY(-2px); }
        #filtroGestion, #filtroMes, #filtroServicio { background-color: #fff; }
        #filtroDia { background-color: #fff; }
        #informesContainer .card { border: 1px solid rgba(26,45,76,0.08) !important; }

        /* ===== TABS ===== */
        .wk-tabs {
            background: #fff;
            padding: 6px;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            gap: 4px;
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .wk-tabs .nav-link {
            color: #6b7280;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 18px;
            transition: all 0.25s ease;
            white-space: nowrap;
            border: 1px solid transparent;
            font-size: .9rem;
        }
        .wk-tabs .nav-link:hover:not(.active) {
            background: rgba(3,126,131,0.06);
            color: #037e83;
        }
        .wk-tabs .nav-link.active {
            background: linear-gradient(135deg, #1a2d4c 0%, #037e83 100%);
            color: #fff;
            box-shadow: 0 6px 16px rgba(3,126,131,0.25);
        }

        /* ===== Subtabs (Por pagar / Ya pagados) ===== */
        .wk-subtabs {
            background: #f3f4f6;
            padding: 4px;
            border-radius: 12px;
            gap: 4px;
            display: inline-flex !important;
            flex-wrap: nowrap;
        }
        .wk-subtabs .nav-link {
            color: #6b7280;
            font-weight: 600;
            border-radius: 9px;
            padding: 7px 14px;
            font-size: .85rem;
            transition: all .2s ease;
        }
        .wk-subtabs .nav-link:hover:not(.active) {
            background: rgba(180,83,9,0.08);
            color: #b45309;
        }
        .wk-subtabs .nav-link.active {
            background: linear-gradient(135deg,#b45309 0%,#f59e0b 100%);
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(180,83,9,.25);
        }
        .wk-subtabs .nav-link.active .badge { background: rgba(255,255,255,.25) !important; color: #fff; }
        .wk-subtabs .nav-link .badge { color: #6b7280; }

        /* ===== MES COLAPSABLE (Salarios) ===== */
        .mes-toggle {
            cursor: pointer;
            user-select: none;
            transition: filter .2s ease;
        }
        .mes-toggle:hover { filter: brightness(1.06); }
        .mes-toggle .chevron {
            transition: transform .25s ease;
        }
        .mes-toggle[aria-expanded="true"] .chevron { transform: rotate(180deg); }

        /* ===== Paginación informes ===== */
        #informesPaginacion .btn { transition: all .2s ease; }
        #informesPaginacion .btn:disabled { opacity: .4; }

        /* Resumen informes compacto */
        .resumen-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
            margin-bottom: 1.25rem;
        }
        .resumen-mini > div {
            padding: 12px 14px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .resumen-mini .lbl {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: #6b7280;
        }
        .resumen-mini .val {
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 2px;
        }
        .resumen-mini .sub {
            font-size: .7rem;
            color: #9ca3af;
            margin-top: 2px;
        }
        .resumen-hero {
            grid-column: 1 / -1;
            padding: 18px !important;
            background: linear-gradient(135deg,#1a2d4c 0%,#037e83 100%) !important;
            color: #fff;
            border: none !important;
        }
        .resumen-hero .lbl,
        .resumen-hero .sub { color: rgba(255,255,255,.78) !important; }
        .resumen-hero .val { color: #fff !important; font-size: 1.6rem !important; }
    </style>
@endpush

@push('script')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        // ===== TOAST =====
        function showToast(type, message) {
            const cfg = {
                success: { icon: 'ri-checkbox-circle-fill', color: '#198754', title: 'Éxito' },
                error:   { icon: 'ri-close-circle-fill',    color: '#dc3545', title: 'Error' },
                info:    { icon: 'ri-information-fill',      color: '#0d6efd', title: 'Info' },
            }[type] || { icon: 'ri-information-fill', color: '#6c757d', title: 'Aviso' };

            const t = document.createElement('div');
            t.className = 'toast text-white shadow-lg border-0';
            t.style.background = cfg.color;
            t.setAttribute('role', 'alert');
            t.innerHTML = `
                <div class="toast-header text-white border-0 py-2" style="background:${cfg.color}cc;">
                    <i class="${cfg.icon} me-2"></i>
                    <strong class="me-auto">${cfg.title}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">${message}</div>`;
            let c = document.getElementById('toast-container');
            if (!c) { c = document.createElement('div'); c.id='toast-container'; c.className='toast-container position-fixed top-0 end-0 p-3'; c.style.zIndex='1090'; document.body.appendChild(c); }
            c.appendChild(t);
            new bootstrap.Toast(t, { autohide: true, delay: 4000 }).show();
            t.addEventListener('hidden.bs.toast', () => t.remove());
        }

        // ===== DETALLE SESIÓN =====
        window.mostrarDetalleSesion = function(sesionId) {
            const modalEl = document.getElementById('detalleSesionModal');
            if (!modalEl) return;
            const modal    = new bootstrap.Modal(modalEl);
            const loader   = document.getElementById('detalle-loader');
            const contenido= document.getElementById('detalle-contenido');
            const btnSus   = document.getElementById('btnVerSuscripcion');

            loader.style.display = 'block';
            contenido.innerHTML  = '';
            if (btnSus) btnSus.href = '#';
            modal.show();

            fetch(`/admin/sesiones/${sesionId}/detalle`)
                .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
                .then(data => {
                    loader.style.display = 'none';
                    renderDetalleSesion(data, contenido, btnSus);
                })
                .catch(err => {
                    loader.style.display = 'none';
                    contenido.innerHTML = `<div class="alert alert-danger rounded-4">${err.message}</div>`;
                    showToast('error', err.message);
                });
        };

        function renderDetalleSesion(data, container, btnSus) {
            const estadoMap = {
                programada:  { icon: 'ri-time-line',           color: '#856404', bg: '#fff3cd' },
                confirmada:  { icon: 'ri-checkbox-circle-line', color: '#037e83', bg: '#d1fae5' },
                realizada:   { icon: 'ri-check-double-line',    color: '#0d6efd', bg: '#dbeafe' },
                cancelada:   { icon: 'ri-close-circle-line',    color: '#dc3545', bg: '#fee2e2' },
                reprogramada:{ icon: 'ri-refresh-line',         color: '#6c757d', bg: '#f3f4f6' },
            };
            const ecfg = estadoMap[data.estado] || estadoMap.programada;

            const ic = (icon, clr) => `
                <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0"
                     style="width:44px;height:44px;background:${clr}18;color:${clr};">
                    <i class="${icon} fs-5"></i>
                </div>`;

            container.innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4" style="background:#f8fafc;">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                ${ic('ri-hashtag','#1a2d4c')}
                                <div>
                                    <div class="text-muted small fw-bold text-uppercase" style="letter-spacing:.8px;">Sesión</div>
                                    <div class="fw-bold" style="color:#1a2d4c;font-size:1.1rem;">#${data.n_sesion}</div>
                                    <small class="text-muted"><i class="ri-calendar-line me-1"></i>${data.fecha} &bull; ${data.hora_inicio} - ${data.hora_fin}</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill px-3 py-2" style="background:${ecfg.bg};color:${ecfg.color};border:1px solid ${ecfg.color};">
                                <i class="${ecfg.icon} me-1"></i>${data.estado.charAt(0).toUpperCase()+data.estado.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            ${ic('ri-user-star-line','#1a2d4c')}
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;">Cliente</div>
                            <div class="fw-bold" style="color:#1a2d4c;">${data.cliente_nombre}</div>
                            <small class="text-muted"><i class="ri-id-card-line me-1"></i>${data.cliente_carnet||'Sin CI'}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            ${ic('ri-baby-line','#037e83')}
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;">Hijo/a</div>
                            <div class="fw-bold" style="color:#1a2d4c;">${data.hijo_nombre||'No asignado'}</div>
                            <small class="text-muted"><i class="ri-id-card-line me-1"></i>${data.hijo_carnet||'Sin CI'}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                        <div class="card-body p-3">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;">Paquete</div>
                            <div class="fw-bold" style="color:#1a2d4c;">${data.paquete}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                        <div class="card-body p-3">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;">Plan</div>
                            <div class="fw-bold" style="color:#1a2d4c;">${data.plan}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                        <div class="card-body p-3">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;">Servicio</div>
                            <div class="fw-bold" style="color:#1a2d4c;">${data.servicio}</div>
                        </div>
                    </div>
                </div>

                ${data.informe ? `
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-2" style="color:#1a2d4c;"><i class="ri-file-text-line me-2"></i>Informe</h6>
                            <p class="mb-0 text-muted small" style="white-space:pre-wrap;">${data.informe}</p>
                        </div>
                    </div>
                </div>` : ''}
            </div>`;

            if (btnSus && data.suscripcion_id) {
                btnSus.href = `/admin/suscripciones/ver/${data.suscripcion_id}`;
                btnSus.style.display = 'inline-flex';
            } else if (btnSus) {
                btnSus.style.display = 'none';
            }

            // Botón de informe solo para sesiones realizadas
            const acciones = document.getElementById('detalle-acciones');
            if (acciones) {
                acciones.innerHTML = '';
                if (data.estado === 'realizada') {
                    const yaRegistrado = data.asistio_hijo !== null && data.asistio_hijo !== undefined;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn rounded-pill px-3 fw-semibold';
                    btn.style.cssText = yaRegistrado
                        ? 'background:linear-gradient(135deg,#037e83 0%,#1a2d4c 100%);border:none;color:#fff;'
                        : 'background:linear-gradient(135deg,#198754 0%,#037e83 100%);border:none;color:#fff;';
                    btn.innerHTML = yaRegistrado
                        ? '<i class="ri-edit-line me-1"></i>Editar Registro'
                        : '<i class="ri-file-add-line me-1"></i>Registrar Asistencia';
                    btn.addEventListener('click', () => abrirModalInforme(
                        data.id, data.n_sesion, data.informe,
                        data.asistio_hijo, data.pago_con_sesion, data.pago_sin_sesion
                    ));
                    acciones.appendChild(btn);
                }
            }
        }

        // ===== HISTORIAL DE INFORMES =====
        const INFORMES_URL = '{{ route("admin.trabajadores.informes", $trabajador->id) }}';
        const PUEDE_EDITAR_INFORME = @json(auth()->user()->can('trabajadores.registrar.informe'));

        function cargarInformes() {
            const params = new URLSearchParams({
                gestion:     document.getElementById('filtroGestion').value,
                mes:         document.getElementById('filtroMes').value,
                dia:         document.getElementById('filtroDia').value,
                servicio_id: document.getElementById('filtroServicio').value,
            });

            const container = document.getElementById('informesContainer');
            container.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border" role="status" style="color:#1a2d4c;width:2.5rem;height:2.5rem;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted mb-0">Cargando informes...</p>
                </div>`;

            fetch(`${INFORMES_URL}?${params}`, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(r => r.json())
            .then(res => {
                document.getElementById('informesTotal').textContent = res.total;
                if (!res.data || res.data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="ri-inbox-line fs-1 opacity-50"></i>
                            <p class="mt-2 mb-0">No se encontraron sesiones realizadas con los filtros aplicados</p>
                        </div>`;
                    return;
                }
                renderInformes(res.data, res, container);
            })
            .catch(() => {
                container.innerHTML = `<div class="alert alert-danger rounded-4 m-0">Error al cargar los informes</div>`;
            });
        }

        // Estado de informes (paginación por mes)
        const MESES_NOMBRE = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        let _informesData = [];
        let _informesRes  = null;

        function renderInformes(data, res, container) {
            _informesData = data;
            _informesRes  = res;
            renderInformesPagina(container);
        }

        function renderInformesPagina(container) {
            const data = _informesData;
            const res  = _informesRes;
            const meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            const fmt   = v => v != null ? 'S/ ' + parseFloat(v).toFixed(2) : '—';

            // ── Resumen ──
            const statBox = (label, val, sub, color, bg, border, icon) => `
                <div class="col-6 col-md-4 col-xl">
                    <div class="rounded-4 p-3 h-100" style="background:${bg};border:1px solid ${border};">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                style="width:30px;height:30px;background:${color}1f;color:${color};font-size:.9rem;">
                                <i class="${icon}"></i>
                            </div>
                            <span class="fw-bold text-uppercase" style="font-size:.65rem;letter-spacing:.6px;color:${color};">${label}</span>
                        </div>
                        <div class="fw-bold" style="font-size:1.25rem;color:${color};line-height:1.1;">${val}</div>
                        <div class="text-muted" style="font-size:.72rem;">${sub}</div>
                    </div>
                </div>`;

            let resumenHtml = `
            <div class="row g-3 mb-3">
                <div class="col-12 col-xl-4">
                    <div class="rounded-4 p-3 h-100 text-white d-flex flex-column justify-content-center"
                        style="background:linear-gradient(135deg,#1a2d4c 0%,#037e83 100%);box-shadow:0 6px 18px rgba(3,126,131,0.25);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                style="width:34px;height:34px;background:rgba(255,255,255,.18);font-size:1rem;">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                            <span class="fw-bold text-uppercase" style="font-size:.7rem;letter-spacing:.7px;color:rgba(255,255,255,.85);">Total a pagar</span>
                        </div>
                        <div class="fw-bold" style="font-size:1.7rem;line-height:1.1;">${fmt(res.total_pago)}</div>
                        <div style="font-size:.78rem;color:rgba(255,255,255,.78);">${res.total} sesiones realizadas</div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="row g-2 h-100">
                        ${statBox('Con asistencia', fmt(res.pago_con_total), res.con_asistencia + ' sesiones', '#198754', 'rgba(25,135,84,0.06)', 'rgba(25,135,84,0.2)', 'ri-checkbox-circle-line')}
                        ${statBox('Normal',         fmt(res.pago_con_normal), res.con_asistencia_normal + ' sesiones', '#0d9488', 'rgba(13,148,136,0.06)', 'rgba(13,148,136,0.2)', 'ri-sun-line')}
                        ${statBox('Especial',       fmt(res.pago_con_especial), res.con_asistencia_especial + ' sesiones', '#b45309', 'rgba(180,83,9,0.06)', 'rgba(180,83,9,0.2)', 'ri-star-fill')}
                        ${statBox('Sin asistencia', fmt(res.pago_sin_total), res.sin_asistencia + ' sesiones', '#dc3545', 'rgba(220,53,69,0.06)', 'rgba(220,53,69,0.2)', 'ri-close-circle-line')}
                    </div>
                </div>
            </div>
            ${res.pendientes > 0 ? `
            <div class="alert alert-warning rounded-4 py-2 px-3 mb-3 d-flex align-items-center gap-2">
                <i class="ri-error-warning-line fs-5"></i>
                <span><strong>${res.pendientes}</strong> sesión(es) sin registrar asistencia — haz clic en <i class="ri-edit-line"></i> para completarlas</span>
            </div>` : ''}`;

            // ── Cards (todas las del mes seleccionado vienen del servidor) ──
            let html = '<div class="row g-3">';
            data.forEach(inf => {
                const partes    = inf.fecha.split('/');
                const dia       = partes[0], mesIdx = parseInt(partes[1]), year = partes[2];
                const texto     = inf.informe || '';
                const truncado  = texto.length > 200 ? texto.substring(0, 200) + '…' : texto;
                const tieneExp  = texto.length > 200;

                // Badge asistencia
                let asistBadge, pagoHtml;
                if (inf.asistio_hijo === true) {
                    asistBadge = `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(25,135,84,.12);color:#198754;font-size:.78rem;"><i class="ri-checkbox-circle-line me-1"></i>Asistió</span>`;
                    pagoHtml   = `<span class="fw-bold" style="color:#037e83;">${fmt(inf.pago)}</span> <small class="text-muted">(c/sesión)</small>`;
                } else if (inf.asistio_hijo === false) {
                    asistBadge = `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(220,53,69,.12);color:#dc3545;font-size:.78rem;"><i class="ri-close-circle-line me-1"></i>No asistió</span>`;
                    pagoHtml   = `<span class="fw-bold" style="color:#037e83;">${fmt(inf.pago)}</span> <small class="text-muted">(s/sesión)</small>`;
                } else {
                    asistBadge = `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(108,117,125,.12);color:#6c757d;font-size:.78rem;"><i class="ri-question-line me-1"></i>Pendiente</span>`;
                    pagoHtml   = `<span class="text-muted small">Sin registrar</span>`;
                }

                html += `
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden informe-card-item">
                        <div class="card-body p-0 d-flex">
                            <div class="d-flex flex-column align-items-center justify-content-center px-3 py-3 flex-shrink-0 text-white"
                                style="background:linear-gradient(180deg,#1a2d4c 0%,#037e83 100%);min-width:72px;">
                                <span class="fw-bold lh-1" style="font-size:1.5rem;">${dia}</span>
                                <span class="small text-uppercase">${meses[mesIdx]}</span>
                                <span class="small opacity-75">${year}</span>
                            </div>
                            <div class="flex-grow-1 p-3">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(26,45,76,.1);color:#1a2d4c;font-size:.78rem;">
                                            <i class="ri-hashtag me-1"></i>Sesión #${inf.n_sesion}
                                        </span>
                                        <span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(3,126,131,.1);color:#037e83;font-size:.78rem;">
                                            <i class="ri-heart-pulse-line me-1"></i>${inf.servicio}
                                        </span>
                                        ${inf.tarifa === 'especial' ? `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:#fef3c7;color:#b45309;font-size:.78rem;"><i class="ri-star-fill me-1"></i>Especial</span>` : ''}
                                        ${inf.hijo ? `<span class="badge rounded-pill px-2 py-1 fw-semibold bg-info-subtle text-info" style="font-size:.78rem;"><i class="ri-baby-line me-1"></i>${inf.hijo}</span>` : ''}
                                        ${asistBadge}
                                    </div>
                                    <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                        <div class="text-end">
                                            <div class="small text-muted">Pago fisio.</div>
                                            <div>${pagoHtml}</div>
                                        </div>
                                        ${inf.hora_inicio ? `<small class="text-muted"><i class="ri-time-line me-1"></i>${inf.hora_inicio}${inf.hora_fin ? '–'+inf.hora_fin : ''}</small>` : ''}
                                    </div>
                                </div>
                                ${texto ? `<p class="mb-0 informe-texto lh-lg" style="color:#495057;white-space:pre-wrap;font-size:.9rem;" data-full="${escapeHtml(texto)}">${escapeHtml(truncado)}</p>
                                ${tieneExp ? `<button class="btn btn-link btn-sm p-0 mt-1 fw-semibold btn-expandir-informe" style="color:#037e83;font-size:.8rem;" data-expanded="false"><i class="ri-arrow-down-s-line me-1"></i>Ver completo</button>` : ''}` :
                                `<p class="mb-0 text-muted fst-italic small">Sin informe registrado</p>`}
                            </div>
                            <div class="d-flex align-items-center px-3 flex-shrink-0 border-start">
                                ${inf.pago_salario_id
                                    ? `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(25,135,84,.12);color:#198754;font-size:.78rem;white-space:nowrap;"><i class="ri-checkbox-circle-fill me-1"></i>Pagado</span>`
                                    : inf.asistio_hijo === false
                                        ? `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(108,117,125,.12);color:#6c757d;font-size:.78rem;white-space:nowrap;"><i class="ri-slash-commands-2 me-1"></i>S/informe</span>`
                                        : inf.informe
                                            ? `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(3,126,131,.12);color:#037e83;font-size:.78rem;white-space:nowrap;"><i class="ri-file-check-line me-1"></i>Registrado</span>`
                                            : (PUEDE_EDITAR_INFORME
                                                ? `<button class="btn btn-sm rounded-3 fw-semibold btn-editar-informe"
                                                    data-id="${inf.id}" data-n="${inf.n_sesion}"
                                                    data-informe="${escapeHtml(texto)}"
                                                    data-asistio="${inf.asistio_hijo}"
                                                    data-pago-con="${inf.pago_con_sesion ?? ''}"
                                                    data-pago-sin="${inf.pago_sin_sesion ?? ''}"
                                                    style="background:rgba(3,126,131,0.1);color:#037e83;border:none;"
                                                    title="${inf.asistio_hijo === null ? 'Registrar asistencia' : 'Editar registro'}">
                                                    <i class="ri-edit-line"></i>
                                                </button>`
                                                : `<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(108,117,125,.12);color:#6c757d;font-size:.78rem;white-space:nowrap;"><i class="ri-time-line me-1"></i>Pendiente</span>`)
                                }
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = resumenHtml + html;

            // ── Paginación por MES ──
            // Mostrar siempre que haya un mes/gestión seleccionado para poder navegar.
            const pagWrap = document.getElementById('informesPaginacion');
            const gestionSel = document.getElementById('filtroGestion').value;
            const mesSel     = document.getElementById('filtroMes').value;

            if (gestionSel || mesSel) {
                pagWrap.classList.remove('d-none');
                const mesNombre = mesSel ? MESES_NOMBRE[parseInt(mesSel)] : 'Todos los meses';
                const gestionNombre = gestionSel || '—';
                document.getElementById('informesPagIndicador').innerHTML =
                    `<i class="ri-calendar-2-line me-1"></i><strong>${mesNombre}</strong> ${gestionSel ? gestionNombre : ''}`;

                document.getElementById('informesPaginacionInfo').textContent =
                    data.length
                        ? `${data.length} informe${data.length === 1 ? '' : 's'} en ${mesNombre} ${gestionSel}`.trim()
                        : `Sin informes en ${mesNombre} ${gestionSel}`.trim();

                // Habilitamos siempre los botones — la navegación cruza años.
                document.getElementById('informesPagPrev').disabled = false;
                document.getElementById('informesPagNext').disabled = false;
            } else {
                pagWrap.classList.add('d-none');
            }

            // Expandir/colapsar
            container.querySelectorAll('.btn-expandir-informe').forEach(btn => {
                btn.addEventListener('click', function() {
                    const p = this.previousElementSibling;
                    const expanded = this.dataset.expanded === 'true';
                    if (expanded) {
                        p.textContent = p.dataset.full.substring(0, 220) + '…';
                        this.innerHTML = '<i class="ri-arrow-down-s-line me-1"></i>Ver completo';
                        this.dataset.expanded = 'false';
                    } else {
                        p.textContent = p.dataset.full;
                        this.innerHTML = '<i class="ri-arrow-up-s-line me-1"></i>Ver menos';
                        this.dataset.expanded = 'true';
                    }
                });
            });

            // Botón editar informe
            container.querySelectorAll('.btn-editar-informe').forEach(btn => {
                btn.addEventListener('click', function() {
                    const asistio = this.dataset.asistio === 'true'  ? true
                                  : this.dataset.asistio === 'false' ? false
                                  : null;
                    abrirModalInforme(
                        this.dataset.id, this.dataset.n, this.dataset.informe,
                        asistio,
                        this.dataset.pagoCon || null,
                        this.dataset.pagoSin || null
                    );
                });
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // ── Paginación informes (por MES) ──
        function navegarMes(delta) {
            const $mes     = document.getElementById('filtroMes');
            const $gestion = document.getElementById('filtroGestion');
            const hoy = new Date();
            let mes     = parseInt($mes.value)     || (hoy.getMonth() + 1);
            let gestion = parseInt($gestion.value) || hoy.getFullYear();

            mes += delta;
            if (mes > 12) { mes = 1;  gestion += 1; }
            else if (mes < 1) { mes = 12; gestion -= 1; }

            // Si la gestión nueva no existe en el select, la agregamos dinámicamente.
            if (!Array.from($gestion.options).some(o => parseInt(o.value) === gestion)) {
                const opt = document.createElement('option');
                opt.value = gestion;
                opt.textContent = gestion;
                $gestion.appendChild(opt);
            }

            $mes.value     = String(mes);
            $gestion.value = String(gestion);

            // Limpiar día (no aplica al cambiar de mes)
            document.getElementById('filtroDia').value = '';

            cargarInformes();
            document.getElementById('informesContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.getElementById('informesPagPrev').addEventListener('click', () => navegarMes(-1));
        document.getElementById('informesPagNext').addEventListener('click', () => navegarMes(+1));

        document.getElementById('btnFiltrarInformes').addEventListener('click', cargarInformes);

        // Limpiar: vuelve al mes/año actual (mantiene la paginación funcional)
        document.getElementById('btnLimpiarFiltros').addEventListener('click', function() {
            const hoy = new Date();
            document.getElementById('filtroGestion').value  = String(hoy.getFullYear());
            document.getElementById('filtroMes').value      = String(hoy.getMonth() + 1);
            document.getElementById('filtroDia').value      = '';
            document.getElementById('filtroServicio').value = '';
            cargarInformes();
        });

        // Filtrar al presionar Enter en el campo día
        document.getElementById('filtroDia').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') cargarInformes();
        });

        // Recargar historial al guardar un informe exitosamente
        document.getElementById('btnGuardarInforme').addEventListener('click', function onSaveInforme() {}, true);

        // Carga inicial automática
        cargarInformes();

        // ===== MODAL INFORME =====
        function abrirModalInforme(sesionId, nSesion, informeActual, asistioActual, pagoConSesion, pagoSinSesion) {
            document.getElementById('informeSesionId').value = sesionId;
            document.getElementById('informeModalSubtitle').textContent = '— Sesión #' + nSesion;

            var asistio = asistioActual === true || asistioActual === '1' || asistioActual === 1;
            document.getElementById('informeAsistioHijo').value = asistio ? '1' : '0';

            // Indicador de asistencia (solo lectura)
            var indicador = document.getElementById('informeAsistenciaIndicador');
            if (asistio) {
                indicador.style.background = 'rgba(25,135,84,.08)';
                indicador.style.border = '1.5px solid rgba(25,135,84,.25)';
                indicador.innerHTML = '<i class="ri-checkbox-circle-line fs-3" style="color:#198754;"></i>' +
                    '<div><div class="fw-semibold" style="color:#198754;">El niño/a asistió a la sesión</div>' +
                    '<div class="small text-muted">Puede registrar el informe a continuación</div></div>';
            } else {
                indicador.style.background = 'rgba(220,53,69,.06)';
                indicador.style.border = '1.5px solid rgba(220,53,69,.2)';
                indicador.innerHTML = '<i class="ri-close-circle-line fs-3" style="color:#dc3545;"></i>' +
                    '<div><div class="fw-semibold" style="color:#dc3545;">El niño/a no asistió a la sesión</div>' +
                    '<div class="small text-muted">No corresponde registrar informe</div></div>';
            }

            // Mostrar/ocultar informe según asistencia
            document.getElementById('informeSection').style.display = asistio ? '' : 'none';
            document.getElementById('informeTexto').value = asistio ? (informeActual || '') : '';
            actualizarContadorInforme();

            // Ocultar botón guardar si no asistió
            document.getElementById('btnGuardarInforme').style.display = asistio ? '' : 'none';

            const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleSesionModal'));
            if (detalleModal) detalleModal.hide();

            setTimeout(() => {
                new bootstrap.Modal(document.getElementById('modalInforme')).show();
            }, 300);
        }


        function actualizarContadorInforme() {
            const val = document.getElementById('informeTexto').value.length;
            document.getElementById('informeCharCount').textContent = val + ' / 3000';
        }

        document.getElementById('informeTexto').addEventListener('input', actualizarContadorInforme);

        document.getElementById('btnGuardarInforme').addEventListener('click', function () {
            const sesionId  = document.getElementById('informeSesionId').value;
            const informe   = document.getElementById('informeTexto').value.trim();
            const asistio   = document.getElementById('informeAsistioHijo').value;
            const btn       = this;

            if (informe.length > 3000) {
                showToast('error', 'El informe excede los 3000 caracteres');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

            fetch(`/admin/sesiones/${sesionId}/informe`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    asistio_hijo:   parseInt(asistio),
                    informe_sesion: informe || null,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast('success', res.msg);
                    bootstrap.Modal.getInstance(document.getElementById('modalInforme')).hide();
                    cargarInformes();
                } else {
                    showToast('error', res.msg || 'Error al guardar');
                }
            })
            .catch(() => showToast('error', 'Error de conexión'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Guardar';
            });
        });

        // ===== LIQUIDACIÓN DE SALARIOS =====
        const SALARIOS_URL  = '{{ route("admin.trabajadores.salarios", $trabajador->id) }}';
        const PAGAR_MES_URL = '{{ route("admin.trabajadores.pagarMes", $trabajador->id) }}';
        const SALARIO_PDF_URL = '{{ route("admin.trabajadores.salarioPagadoPdf", $trabajador->id) }}';
        const CSRF_TOKEN    = document.querySelector('meta[name="csrf-token"]').content;

        function fmtBs(v) {
            return 'Bs ' + parseFloat(v || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function cargarSalarios() {
            const containerPend = document.getElementById('salariosContainerPendientes');
            const containerPag  = document.getElementById('salariosContainerPagados');
            const loading = `<div class="text-center py-5">
                <div class="spinner-border" style="color:#b45309;width:2.5rem;height:2.5rem;"><span class="visually-hidden">Cargando...</span></div>
                <p class="mt-2 text-muted mb-0">Cargando salarios...</p>
            </div>`;
            containerPend.innerHTML = loading;
            containerPag.innerHTML  = '';

            fetch(SALARIOS_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(res => renderSalarios(res.meses, containerPend, containerPag))
                .catch(() => {
                    containerPend.innerHTML = `<div class="alert alert-danger rounded-4 m-0">Error al cargar los salarios</div>`;
                });
        }

        function renderSalarios(meses, containerPend, containerPag) {
            const vacio = `<div class="text-center py-5 text-muted">
                <i class="ri-inbox-line fs-1 opacity-50"></i>
                <p class="mt-2 mb-0">No hay registros en esta sección</p>
            </div>`;

            if (!meses || meses.length === 0) {
                const vacioGlobal = `<div class="text-center py-5 text-muted">
                    <i class="ri-inbox-line fs-1 opacity-50"></i>
                    <p class="mt-2 mb-0">No hay sesiones realizadas con asistencia registrada</p>
                </div>`;
                containerPend.innerHTML = vacioGlobal;
                containerPag.innerHTML  = vacioGlobal;
                document.getElementById('badgePendientes').textContent = '0';
                document.getElementById('badgePagados').textContent = '0';
                return;
            }

            // Repartir meses entre "Por pagar" (pagables o sin informe) y "Ya pagados" (pagadas).
            const mesesPendientes = meses.filter(m => m.sesiones_pagables.length > 0 || m.sesiones_pendientes.length > 0);
            const mesesPagados    = meses.filter(m => m.sesiones_pagadas.length > 0);

            // Badges con cantidad de meses
            document.getElementById('badgePendientes').textContent =
                `${mesesPendientes.length} mes${mesesPendientes.length === 1 ? '' : 'es'}`;
            document.getElementById('badgePagados').textContent =
                `${mesesPagados.length} mes${mesesPagados.length === 1 ? '' : 'es'}`;

            containerPend.innerHTML = mesesPendientes.length
                ? renderMesesHtml(mesesPendientes, 'pendientes')
                : vacio;
            containerPag.innerHTML  = mesesPagados.length
                ? renderMesesHtml(mesesPagados, 'pagados')
                : vacio;

            bindSalariosHandlers(containerPend);
            bindSalariosHandlers(containerPag);
        }

        function renderMesesHtml(meses, modo) {
            // modo: 'pendientes' (muestra pagables + sin informe) | 'pagados' (muestra pagadas)
            let html = '';
            meses.forEach((m, idx) => {
                const hayPagables  = m.sesiones_pagables.length > 0;
                const hayPagadas   = m.sesiones_pagadas.length > 0;
                const hayPendientes= m.sesiones_pendientes.length > 0;
                const esModoPag    = modo === 'pagados';
                const puedeRegistrarPago = !esModoPag && (hayPagables || hayPendientes);

                // Header color depende del modo
                const headerStyle = esModoPag
                    ? 'background:linear-gradient(135deg,#198754,#0d9488);'
                    : (hayPagables
                        ? 'background:linear-gradient(135deg,#b45309,#f59e0b);'
                        : 'background:linear-gradient(135deg,#6c757d,#495057);');

                // Conteo de sesiones visibles según modo
                const sesionesVisibles = esModoPag
                    ? m.sesiones_pagadas.length
                    : (m.sesiones_pagables.length + m.sesiones_pendientes.length);

                // Total del mes (junto al botón / a la derecha del header)
                // En "Por pagar": suma sesiones con informe + sesiones sin informe (todas las del mes que esperan pago)
                const totalMes = esModoPag
                    ? parseFloat(m.total_pagado)
                    : (parseFloat(m.total_pendiente) || 0) + (parseFloat(m.total_sin_informe) || 0);
                const totalLabel = esModoPag ? 'Pagado' : 'Total a pagar';
                const totalMesHtml = `
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                         style="background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.25);">
                        <i class="ri-money-dollar-circle-line fs-5"></i>
                        <div class="lh-sm text-end">
                            <div style="font-size:.65rem;opacity:.85;letter-spacing:.5px;text-transform:uppercase;font-weight:600;">${totalLabel}</div>
                            <div class="fw-bold" style="font-size:1.05rem;">${fmtBs(totalMes)}</div>
                        </div>
                    </div>`;

                const expandido = !esModoPag; // En "Ya pagados" arrancan colapsados por limpieza
                const collapseId = `mes-body-${modo}-${idx}`;

                html += `<div class="border rounded-4 overflow-hidden mb-3">
                    <div class="px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-3 mes-toggle"
                        style="${headerStyle}"
                        data-bs-toggle="collapse" data-bs-target="#${collapseId}"
                        aria-expanded="${expandido}" aria-controls="${collapseId}">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <i class="ri-arrow-down-s-line text-white fs-5 chevron"></i>
                            <i class="ri-calendar-2-line text-white fs-5"></i>
                            <span class="fw-bold text-white fs-6">${m.mes_label}</span>
                            <span class="badge rounded-pill px-2 py-1" style="background:rgba(255,255,255,.15);color:#fff;font-size:.7rem;">
                                ${sesionesVisibles} sesión(es)
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            ${totalMesHtml}
                            ${esModoPag ? `
                                <button type="button"
                                    class="btn btn-sm rounded-pill fw-semibold px-3 btn-pdf-salario"
                                    data-anio="${m.año}" data-mes="${m.mes}" data-tipo="general"
                                    style="background:#fff;color:#1a2d4c;border:none;"
                                    title="Reporte general (sesiones, fecha, tarifa, pago)"
                                    onclick="event.stopPropagation();">
                                    <i class="ri-file-list-3-line me-1"></i>PDF General
                                </button>
                                <button type="button"
                                    class="btn btn-sm rounded-pill fw-semibold px-3 btn-pdf-salario"
                                    data-anio="${m.año}" data-mes="${m.mes}" data-tipo="detallado"
                                    style="background:rgba(255,255,255,.22);color:#fff;border:1px solid rgba(255,255,255,.4);"
                                    title="Reporte detallado (cliente, hijo, plan)"
                                    onclick="event.stopPropagation();">
                                    <i class="ri-file-text-line me-1"></i>PDF Detallado
                                </button>
                            ` : ''}
                            ${puedeRegistrarPago ? `<button class="btn btn-sm rounded-pill fw-semibold px-3 btn-pagar-mes"
                                style="background:#fff;color:#b45309;border:none;"
                                data-año="${m.año}" data-mes="${m.mes}" data-label="${m.mes_label}"
                                data-total="${m.total_pendiente}" data-count="${m.sesiones_pagables.length}"
                                data-total-sin-informe="${m.total_sin_informe}" data-count-sin-informe="${m.sesiones_pendientes.length}"
                                onclick="event.stopPropagation();">
                                <i class="ri-money-dollar-circle-line me-1"></i>Registrar Pago
                            </button>` : ''}
                        </div>
                    </div>
                    <div id="${collapseId}" class="collapse${expandido ? ' show' : ''}">
                    <div class="p-3">`;

                if (!esModoPag) {
                    // ───── Tab "Por pagar" ─────
                    if (hayPagables) {
                        html += `<div class="mb-3">
                            <div class="small fw-semibold text-uppercase mb-2" style="color:#b45309;letter-spacing:.5px;">
                                <i class="ri-time-line me-1"></i>Pendientes de pago (${m.sesiones_pagables.length})
                            </div>
                            <div class="d-flex flex-column gap-1">`;
                        m.sesiones_pagables.forEach(s => { html += renderSesionSalario(s, 'pendiente'); });
                        html += `</div>
                            <div class="mt-2 d-flex justify-content-end">
                                <span class="fw-bold" style="color:#b45309;">Total: ${fmtBs(m.total_pendiente)}</span>
                            </div>
                        </div>`;
                    }

                    if (hayPendientes) {
                        html += `<div class="${hayPagables ? 'border-top pt-3' : ''}">
                            <div class="small fw-semibold text-uppercase mb-2" style="color:#6c757d;letter-spacing:.5px;">
                                <i class="ri-alert-line me-1"></i>Sin informe registrado (${m.sesiones_pendientes.length}) — no pagables aún
                            </div>
                            <div class="d-flex flex-column gap-1">`;
                        m.sesiones_pendientes.forEach(s => { html += renderSesionSalario(s, 'sin-informe'); });
                        html += `</div></div>`;
                    }
                } else {
                    // ───── Tab "Ya pagados" ─────
                    const gastoInfo = m.gasto
                        ? `<span class="badge rounded-pill px-2 py-1 ms-2" style="background:rgba(25,135,84,.1);color:#198754;font-size:.7rem;">
                                <i class="ri-receipt-line me-1"></i>${m.gasto.descripcion} — ${m.gasto.fecha}
                           </span>`
                        : '';
                    html += `<div>
                        <div class="small fw-semibold text-uppercase mb-2 d-flex align-items-center flex-wrap gap-1" style="color:#198754;letter-spacing:.5px;">
                            <i class="ri-check-double-line me-1"></i>Pagadas (${m.sesiones_pagadas.length})${gastoInfo}
                        </div>
                        <div class="d-flex flex-column gap-1">`;
                    m.sesiones_pagadas.forEach(s => { html += renderSesionSalario(s, 'pagada'); });
                    html += `</div>
                        <div class="mt-2 d-flex justify-content-end">
                            <span class="fw-bold text-success">Total pagado: ${fmtBs(m.total_pagado)}</span>
                        </div>
                    </div>`;
                }

                html += `</div></div></div>`;
            });

            return html;
        }

        function bindSalariosHandlers(container) {
            // Bind PDF buttons (general / detallado)
            container.querySelectorAll('.btn-pdf-salario').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const anio = this.dataset.anio;
                    const mes  = this.dataset.mes;
                    const tipo = this.dataset.tipo;
                    if (!anio || !mes) return;
                    // Construimos query string con encoding correcto del parámetro "año"
                    const url = SALARIO_PDF_URL
                        + '?' + encodeURIComponent('año') + '=' + encodeURIComponent(anio)
                        + '&mes=' + encodeURIComponent(mes)
                        + '&tipo=' + encodeURIComponent(tipo);
                    window.open(url, '_blank', 'noopener');
                });
            });

            // Bind pagar buttons
            container.querySelectorAll('.btn-pagar-mes').forEach(btn => {
                btn.addEventListener('click', function () {
                    const totalPagables   = parseFloat(this.dataset.total) || 0;
                    const countPagables   = parseInt(this.dataset.count) || 0;
                    const totalSinInf     = parseFloat(this.dataset.totalSinInforme) || 0;
                    const countSinInf     = parseInt(this.dataset.countSinInforme) || 0;
                    const label           = this.dataset.label;

                    document.getElementById('pagoSalarioAño').value = this.dataset.año;
                    document.getElementById('pagoSalarioMes').value = this.dataset.mes;
                    document.getElementById('pagoSalarioSubtitle').textContent = '— ' + label;
                    document.getElementById('pagoSalarioObservacion').value = '';

                    const wrap = document.getElementById('pagoSalarioSinInformeWrap');
                    const checkbox = document.getElementById('pagoSalarioIncluirSinInforme');
                    const detalle  = document.getElementById('pagoSalarioSinInformeDetalle');
                    checkbox.checked = false;

                    if (countSinInf > 0) {
                        wrap.classList.remove('d-none');
                        detalle.textContent = `${countSinInf} sesión(es) sin informe registrado por ${fmtBs(totalSinInf)}. Si marcas esta opción se incluirán en el pago.`;
                    } else {
                        wrap.classList.add('d-none');
                    }

                    const resumen = document.getElementById('pagoSalarioResumen');
                    const renderResumen = () => {
                        const incluir = checkbox.checked;
                        const total = totalPagables + (incluir ? totalSinInf : 0);
                        const count = countPagables + (incluir ? countSinInf : 0);
                        const nota = incluir && countSinInf > 0
                            ? `<div class="small mt-2" style="color:#b45309;"><i class="ri-information-line me-1"></i>Incluye ${countSinInf} sesión(es) sin informe</div>`
                            : (countPagables === 0
                                ? `<div class="small mt-2 text-muted"><i class="ri-information-line me-1"></i>No hay sesiones con informe. Marca la opción para pagar las sesiones sin informe.</div>`
                                : '');
                        resumen.innerHTML = `
                            <div class="rounded-4 p-4 text-center" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);">
                                <div class="text-muted small mb-1">Total a registrar como Gasto (Salarios)</div>
                                <div class="fw-bold" style="font-size:1.8rem;color:#b45309;">${fmtBs(total)}</div>
                                <div class="text-muted small mt-1">${count} sesión(es) de ${label}</div>
                                ${nota}
                            </div>`;
                    };
                    renderResumen();
                    checkbox.onchange = renderResumen;

                    new bootstrap.Modal(document.getElementById('modalPagarSalario')).show();
                });
            });
        }

        function renderSesionSalario(s, tipo) {
            const esEspecial = s.tarifa === 'especial';
            const asistBadge = s.asistio
                ? `<span class="badge rounded-pill" style="background:rgba(25,135,84,.1);color:#198754;font-size:.65rem;"><i class="ri-checkbox-circle-line me-1"></i>Asistió</span>`
                : `<span class="badge rounded-pill" style="background:rgba(220,53,69,.1);color:#dc3545;font-size:.65rem;"><i class="ri-close-circle-line me-1"></i>No asistió</span>`;

            const informeBadge = s.asistio
                ? (s.informe_ok
                    ? `<span class="badge rounded-pill" style="background:rgba(13,110,253,.1);color:#0d6efd;font-size:.65rem;"><i class="ri-file-text-line me-1"></i>Con informe</span>`
                    : `<span class="badge rounded-pill" style="background:rgba(220,53,69,.1);color:#dc3545;font-size:.65rem;"><i class="ri-file-unknow-line me-1"></i>Sin informe</span>`)
                : '';

            const especialBadge = esEspecial
                ? `<span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.65rem;"><i class="ri-star-fill me-1"></i>Especial</span>`
                : '';

            const pagoBg = esEspecial
                ? 'background:rgba(180,83,9,.06);border:1px solid rgba(180,83,9,.25);border-left:4px solid #b45309;'
                : (tipo === 'pagada'
                    ? 'background:rgba(25,135,84,.04);border:1px solid rgba(25,135,84,.12);'
                    : (tipo === 'sin-informe'
                        ? 'background:rgba(108,117,125,.04);border:1px solid rgba(108,117,125,.1);'
                        : 'background:rgba(245,158,11,.04);border:1px solid rgba(245,158,11,.15);'));

            return `<div class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3" style="${pagoBg}">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="small fw-semibold" style="color:#1a2d4c;min-width:80px;">${s.fecha}</span>
                    <span class="badge rounded-pill" style="background:rgba(26,45,76,.08);color:#1a2d4c;font-size:.65rem;">Sesión #${s.n_sesion}</span>
                    <span class="small text-muted">${s.servicio}</span>
                    ${asistBadge}
                    ${informeBadge}
                    ${especialBadge}
                </div>
                <span class="fw-bold flex-shrink-0 ms-3" style="color:${tipo === 'pagada' ? '#198754' : (tipo === 'sin-informe' ? '#6c757d' : '#b45309')};">${fmtBs(s.pago)}</span>
            </div>`;
        }

        document.getElementById('btnRecargarSalarios').addEventListener('click', cargarSalarios);

        document.getElementById('btnConfirmarPagoSalario').addEventListener('click', function () {
            const btn = this;
            const año  = parseInt(document.getElementById('pagoSalarioAño').value);
            const mes  = parseInt(document.getElementById('pagoSalarioMes').value);
            const obs  = document.getElementById('pagoSalarioObservacion').value.trim();
            const incluirSinInforme = document.getElementById('pagoSalarioIncluirSinInforme').checked;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';

            fetch(PAGAR_MES_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ año, mes, observacion: obs, incluir_sin_informe: incluirSinInforme }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalPagarSalario')).hide();
                    showToast('success', res.message);
                    cargarSalarios();
                } else {
                    showToast('error', res.message || 'Error al registrar el pago');
                }
            })
            .catch(() => showToast('error', 'Error de conexión'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-check-line me-1"></i>Confirmar Pago';
            });
        });

        // Carga inicial
        cargarSalarios();

        // ===== FULLCALENDAR =====
        let _calendarInstance = null;
        document.addEventListener('DOMContentLoaded', function () {
            const calEl = document.getElementById('calendar');
            const eventos = @json($eventos);

            _calendarInstance = new FullCalendar.Calendar(calEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '{{ $slotMinTime }}',
                slotMaxTime: '{{ $slotMaxTime }}',
                slotDuration: '00:30:00',
                height: 'auto',
                locale: 'es',
                buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día' },
                events: eventos,
                eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                dayHeaderFormat: { weekday: 'short', day: 'numeric', month: 'short' },
                allDayText: 'Todo el día',
                moreLinkText: 'más',
                noEventsText: 'No hay sesiones registradas',

                eventDidMount: function (info) {
                    var bg  = info.event.backgroundColor;
                    var txt = info.event.textColor;
                    info.el.style.setProperty('background-color', bg,  'important');
                    info.el.style.setProperty('border-color',     bg,  'important');
                    info.el.style.setProperty('color',            txt, 'important');
                    var main = info.el.querySelector('.fc-event-main');
                    if (main) main.style.setProperty('color', txt, 'important');
                    var dot = info.el.querySelector('.fc-daygrid-event-dot');
                    if (dot) dot.style.setProperty('border-color', bg, 'important');

                    const p = info.event.extendedProps;
                    info.el.setAttribute('title',
                        `${info.event.title}\n👤 ${p.cliente}\n📋 ${p.estado}`);
                },

                eventClick: function (info) {
                    window.mostrarDetalleSesion(info.event.id);
                },
            });

            _calendarInstance.render();

            // Reajustar tamaño del calendario al activar su tab
            const calTabBtn = document.getElementById('tab-calendario-btn');
            if (calTabBtn) {
                calTabBtn.addEventListener('shown.bs.tab', function () {
                    if (_calendarInstance) {
                        _calendarInstance.updateSize();
                    }
                });
            }
        });
    </script>
@endpush