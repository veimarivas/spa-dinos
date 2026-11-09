@extends('admin.dashboard')
@section('admin')
    @php
        $sesionesEditables = $suscripcion->sesiones
            ->filter(fn($s) => in_array($s->estado, ['programada', 'reprogramada']))
            ->values();
        $sesionesNoEditables = $suscripcion->sesiones
            ->reject(fn($s) => in_array($s->estado, ['programada', 'reprogramada']))
            ->values();
        $cantidadSesiones = $suscripcion->sesiones_iniciales;
        $sesionesNoEditablesCount = $sesionesNoEditables->count();
        $coloresPorEstado = [
            'programada'   => '#FFD700',
            'confirmada'   => '#198754',
            'realizada'    => '#0d6efd',
            'cancelada'    => '#dc3545',
            'reprogramada' => '#adb5bd',
        ];
        $sesionesDataInit = $sesionesEditables->map(fn($s) => [
            'id'               => $s->id,
            'hijo_id'          => $s->hijo_id,
            'planes_servicio_id' => $s->planes_servicio_id,
            'trabajadore_id'   => $s->trabajadore_id,
            'fecha'            => $s->fecha?->format('Y-m-d'),
            'hora_inicio'      => $s->hora_inicio ? substr($s->hora_inicio, 0, 5) : null,
            'hora_fin'         => $s->hora_fin ? substr($s->hora_fin, 0, 5) : null,
            '_titulo'          => (($s->hijo->persona->nombres ?? '') . ' - ' . ($s->trabajador->persona->nombres ?? '')),
        ])->values();
        $sesionesNoEditablesCalendario = $sesionesNoEditables
            ->filter(fn($s) => $s->fecha && $s->hora_inicio && $s->hora_fin)
            ->map(fn($s) => [
                'id'              => 'fixed_' . $s->id,
                'title'           => '#' . $s->n_sesion . ' ' . ($s->hijo->persona->nombres ?? '') . ' - ' . ($s->trabajador->persona->nombres ?? ''),
                'start'           => $s->fecha->format('Y-m-d') . 'T' . substr($s->hora_inicio, 0, 5),
                'end'             => $s->fecha->format('Y-m-d') . 'T' . substr($s->hora_fin, 0, 5),
                'backgroundColor' => $coloresPorEstado[$s->estado] ?? '#adb5bd',
                'borderColor'     => $coloresPorEstado[$s->estado] ?? '#adb5bd',
                'textColor'       => '#fff',
                'extendedProps'   => ['tipo' => 'fija', 'estado' => $s->estado],
            ])->values();
    @endphp

    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-4 p-4 shadow-sm">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-edit-circle-line me-2"></i>Editar Suscripción #{{ $suscripcion->id }}
                        </h4>
                        <p class="text-white-50 mb-0">Modifica las sesiones de esta suscripción según sea necesario</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.suscripciones.index') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div class="progress-line position-absolute top-50 start-0 translate-middle-y w-100 d-none d-md-block"
                                style="height: 3px; background: #e9ecef; z-index: 1;"></div>
                            <div class="step-item active text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-primary text-white mb-2 mx-auto">
                                    <span class="avatar-title"><i class="ri-user-line"></i></span>
                                </div>
                                <small class="fw-semibold">1. Cliente</small>
                            </div>
                            <div class="step-item active text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-primary text-white mb-2 mx-auto">
                                    <span class="avatar-title"><i class="ri-stack-line"></i></span>
                                </div>
                                <small class="fw-semibold">2. Plan</small>
                            </div>
                            <div class="step-item active text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-warning text-white mb-2 mx-auto">
                                    <span class="avatar-title"><i class="ri-edit-line"></i></span>
                                </div>
                                <small class="fw-semibold">3. Editar Sesiones</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.suscripciones.update', $suscripcion->id) }}" method="POST" id="formSuscripcion">
                    @csrf
                    @method('PUT')

                    <!-- Hidden inputs for NON-EDITABLE sessions (preserves them on update) -->
                    @foreach ($sesionesNoEditables as $idx => $sesion)
                        <input type="hidden" name="sesiones[{{ $idx }}][id]"               value="{{ $sesion->id }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][hijo_id]"           value="{{ $sesion->hijo_id }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][planes_servicio_id]" value="{{ $sesion->planes_servicio_id }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][trabajadore_id]"    value="{{ $sesion->trabajadore_id }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][fecha]"             value="{{ $sesion->fecha?->format('Y-m-d') }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][hora_inicio]"       value="{{ substr($sesion->hora_inicio, 0, 5) }}">
                        <input type="hidden" name="sesiones[{{ $idx }}][hora_fin]"          value="{{ substr($sesion->hora_fin, 0, 5) }}">
                    @endforeach

                    <!-- ========== DATOS FIJOS (SOLO LECTURA) ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-info"><i class="ri-information-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Información de la Suscripción</h5>
                                    <small class="text-muted">Estos datos no pueden ser modificados</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted mb-2"><i class="ri-user-line me-1"></i>Cliente</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ri-user-line text-primary"></i></span>
                                        <input type="text" class="form-control form-control-lg border-0 bg-light"
                                            value="{{ $suscripcion->cliente->persona->nombres }} {{ $suscripcion->cliente->persona->apellido_paterno }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted mb-2"><i class="ri-stack-line me-1"></i>Plan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ri-file-list-3-line text-success"></i></span>
                                        <input type="text" class="form-control form-control-lg border-0 bg-light"
                                            value="{{ $suscripcion->plan->nombre_plan }} ({{ $suscripcion->sesiones_iniciales }} sesiones)" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted mb-2"><i class="ri-time-line me-1"></i>Sesiones Restantes</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ri-calendar-check-line text-warning"></i></span>
                                        <input type="number" class="form-control form-control-lg border-0 bg-light"
                                            value="{{ $suscripcion->sesiones_restantes }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SESIONES EDITABLES (CALENDARIO) ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                        <span class="avatar-title text-warning"><i class="ri-calendar-edit-line fs-5"></i></span>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Sesiones Programadas</h5>
                                        <small class="text-muted">Solo puedes editar sesiones en estado "programada" o "reprogramada"</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span id="total-sesiones-badge" class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                        <i class="ri-calendar-line me-1"></i><span id="total-sesiones-count">{{ $sesionesEditables->count() }}/{{ $cantidadSesiones }}</span>
                                    </span>
                                    <span id="badge-sesiones-ok" class="badge bg-success px-3 py-2 rounded-pill d-none">
                                        <i class="ri-check-line me-1"></i>Completo
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                    <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3">
                                    <i class="ri-alert-line me-2"></i>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($sesionesNoEditablesCount > 0)
                                <div class="alert alert-info d-flex align-items-center rounded-3 mb-3 py-2 px-3" style="font-size:0.875rem;">
                                    <i class="ri-lock-line me-2 flex-shrink-0"></i>
                                    <span><strong>{{ $sesionesNoEditablesCount }} sesión(es)</strong> en estado confirmada/realizada/cancelada no son editables y se conservan automáticamente.</span>
                                </div>
                            @endif

                            <div class="alert alert-info d-flex align-items-center rounded-3 mb-4" role="alert">
                                <i class="ri-information-fill fs-4 me-3 text-info flex-shrink-0"></i>
                                <div>
                                    <strong>Cómo usar:</strong>
                                    <p class="mb-0 small">Haz clic en un bloque de hora en el calendario para agregar una sesión. Haz clic en una sesión azul existente para eliminarla o editarla.</p>
                                </div>
                            </div>

                            <!-- Barra de progreso + filtro -->
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="small fw-semibold text-dark">Sesiones editables:</span>
                                        <span class="small text-muted"><span id="sesiones-programadas">{{ $sesionesEditables->count() }}</span> de <span id="sesiones-requeridas">{{ $cantidadSesiones }}</span> totales</span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius: 3px; background: #e9ecef;">
                                        <div id="progreso-sesiones" class="progress-bar bg-warning" role="progressbar" style="width: {{ $cantidadSesiones > 0 ? min(100, ($sesionesEditables->count() / $cantidadSesiones) * 100) : 0 }}%;"></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <select id="filtro-fisioterapeuta-cal" class="form-select form-select-sm" style="min-width: 220px;">
                                        <option value="">Todos los fisioterapeutas</option>
                                        @foreach ($trabajadores as $trabajador)
                                            <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                                        @endforeach
                                    </select>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:12px;height:12px;background:#dc3545;border-radius:3px;"></span> Ocupado</span>
                                        <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:12px;height:12px;background:#405189;border-radius:3px;"></span> Programada</span>
                                        <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:12px;height:12px;background:#198754;border-radius:3px;"></span> Confirmada</span>
                                        <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:12px;height:12px;background:#0d6efd;border-radius:3px;"></span> Realizada</span>
                                    </div>
                                </div>
                            </div>

                            <div id="calendar-sesiones" class="rounded-3"></div>
                            <!-- Hidden inputs para sesiones editables (gestionados por JS) -->
                            <div id="sesiones-inputs"></div>

                            <!-- Panel detalle de sesiones editables -->
                            <div id="panel-detalle-sesiones" class="mt-4" {{ $sesionesEditables->isEmpty() ? 'style=display:none;' : '' }}>
                                <hr class="my-3" style="border-color: rgba(64,81,137,0.15);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="ri-list-check-2 me-2" style="color:#405189;"></i>Sesiones Editables
                                    </h6>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                        <span id="detalle-count">{{ $sesionesEditables->count() }}</span> sesiones
                                    </span>
                                </div>
                                <div id="lista-sesiones-detalle" class="row g-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="card border-0 bg-light rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.suscripciones.index') }}"
                                    class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                    <i class="ri-close-circle-line align-bottom me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow-sm" id="btnSubmit">
                                    <i class="ri-save-3-line align-bottom me-1"></i> Actualizar Suscripción
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if ($sesionesNoEditables->isNotEmpty())
                @php
                    $gruposSesiones = $sesionesNoEditables->groupBy('estado');
                    $estadoConfig = [
                        'confirmada'   => ['color' => '#198754', 'bg' => 'rgba(25,135,84,0.08)',  'border' => 'rgba(25,135,84,0.25)',  'badge' => 'success',   'icon' => 'ri-check-line',        'label' => 'Confirmadas'],
                        'realizada'    => ['color' => '#0d6efd', 'bg' => 'rgba(13,110,253,0.08)', 'border' => 'rgba(13,110,253,0.25)', 'badge' => 'primary',   'icon' => 'ri-check-double-line', 'label' => 'Realizadas'],
                        'cancelada'    => ['color' => '#dc3545', 'bg' => 'rgba(220,53,69,0.08)',  'border' => 'rgba(220,53,69,0.25)',  'badge' => 'danger',    'icon' => 'ri-close-circle-line', 'label' => 'Canceladas'],
                        'reprogramada' => ['color' => '#6c757d', 'bg' => 'rgba(108,117,125,0.08)','border' => 'rgba(108,117,125,0.25)','badge' => 'secondary', 'icon' => 'ri-refresh-line',      'label' => 'Reprogramadas'],
                    ];
                @endphp
                <!-- ========== HISTORIAL DE SESIONES NO EDITABLES ========== -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 mt-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-secondary"><i class="ri-history-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Historial de Sesiones</h5>
                                    <small class="text-muted">Sesiones confirmadas, realizadas, canceladas y reprogramadas (solo lectura)</small>
                                </div>
                            </div>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                {{ $sesionesNoEditables->count() }} sesiones
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @foreach ($estadoConfig as $estado => $cfg)
                            @if (isset($gruposSesiones[$estado]) && $gruposSesiones[$estado]->isNotEmpty())
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-{{ $cfg['badge'] }} px-3 py-2 rounded-pill fs-6">
                                            <i class="{{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                        </span>
                                        <span class="text-muted small">{{ $gruposSesiones[$estado]->count() }} sesión(es)</span>
                                    </div>
                                    <div class="row g-3">
                                        @foreach ($gruposSesiones[$estado] as $sesion)
                                            @php
                                                $esReprog = !is_null($sesion->reprogramar_id);
                                                $tieneReprog = $sesion->reprogramaciones->count() > 0;
                                            @endphp
                                            <div class="col-md-6 col-xl-4">
                                                <div class="card border-0 rounded-3 shadow-sm h-100"
                                                     style="border-left: 4px solid {{ $cfg['color'] }} !important; border-left-width: 4px !important;">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="badge rounded-pill fw-semibold px-2 py-1"
                                                                  style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};border:1px solid {{ $cfg['border'] }};font-size:11px;">
                                                                #{{ $sesion->n_sesion }}
                                                            </span>
                                                            <span class="badge bg-{{ $cfg['badge'] }} bg-opacity-15 text-{{ $cfg['badge'] }} rounded-pill" style="font-size:0.65rem;">
                                                                {{ ucfirst($sesion->estado) }}
                                                            </span>
                                                        </div>
                                                        <div class="mb-1" style="font-size:13px;">
                                                            <i class="ri-calendar-event-line me-1" style="color:{{ $cfg['color'] }};"></i>
                                                            <strong>{{ $sesion->fecha ? $sesion->fecha->format('d/m/Y') : '-' }}</strong>
                                                            <span class="text-muted ms-1">
                                                                {{ $sesion->hora_inicio ? substr($sesion->hora_inicio, 0, 5) : '-' }}
                                                                @if($sesion->hora_fin) – {{ substr($sesion->hora_fin, 0, 5) }} @endif
                                                            </span>
                                                        </div>
                                                        <div class="text-muted text-truncate" style="font-size:12px;">
                                                            <i class="ri-baby-line me-1 text-info"></i>
                                                            {{ $sesion->hijo->persona->nombres ?? '-' }} {{ $sesion->hijo->persona->apellido_paterno ?? '' }}
                                                        </div>
                                                        <div class="text-muted text-truncate mt-1" style="font-size:12px;">
                                                            <i class="ri-user-settings-line me-1 text-success"></i>
                                                            {{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }} {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                        </div>
                                                        <div class="text-muted text-truncate mt-1" style="font-size:12px;">
                                                            <i class="ri-service-line me-1" style="color:#f4a200;"></i>
                                                            {{ $sesion->planServicio->servicio->nombre ?? ($sesion->servicio->nombre ?? '-') }}
                                                        </div>
                                                        @if ($esReprog)
                                                            <div class="mt-2">
                                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill" style="font-size:0.65rem;">
                                                                    <i class="ri-refresh-line me-1"></i>Reprog. de #{{ $sesion->sesionOriginal->n_sesion }}
                                                                </span>
                                                            </div>
                                                        @elseif ($tieneReprog)
                                                            <div class="mt-2">
                                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill" style="font-size:0.65rem;">
                                                                    <i class="ri-refresh-line me-1"></i>Nueva: #{{ $sesion->reprogramaciones->first()->n_sesion }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Agregar Sesión -->
    <div class="modal fade" id="modalAgregarSesion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header py-3" style="background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);">
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">
                            <i class="ri-calendar-add-line me-2"></i>Sesión del <span id="sesion-fecha-display"></span>
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="sesion-fecha-selected">
                    <input type="hidden" id="sesion-edit-id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-baby-line me-1"></i>Hijo <span class="text-danger">*</span></label>
                            <select id="sesion-hijo" class="form-select">
                                <option value="">-- Seleccionar hijo --</option>
                                @foreach ($hijos as $hijo)
                                    <option value="{{ $hijo->id }}">{{ $hijo->persona->nombres }} {{ $hijo->persona->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-service-line me-1"></i>Servicio <span class="text-danger">*</span></label>
                            <select id="sesion-servicio" class="form-select">
                                <option value="">-- Seleccionar servicio --</option>
                                @foreach ($serviciosDelPlan as $ps)
                                    <option value="{{ $ps->id }}">{{ $ps->servicio->nombre }} ({{ $ps->cantidad_sesiones }} sesiones)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-user-line me-1"></i>Fisioterapeuta <span class="text-danger">*</span></label>
                            <select id="sesion-trabajador" class="form-select">
                                <option value="">-- Seleccionar fisioterapeuta --</option>
                                @foreach ($trabajadores as $trabajador)
                                    <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class="ri-time-line me-1"></i>Hora Inicio <span class="text-danger">*</span></label>
                            <select id="sesion-hora-inicio" class="form-select">
                                <option value="">-- Hora --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class="ri-time-line me-1"></i>Hora Fin</label>
                            <input type="text" id="sesion-hora-fin" class="form-control" readonly placeholder="Automático">
                        </div>
                        <div id="disponibilidad-msg" class="col-12" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnGuardarSesion" class="btn btn-primary" style="background:linear-gradient(135deg,#405189 0%,#5c6ac4 100%);border:none;">
                        <i class="ri-save-3-line me-1"></i>Guardar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%); }
        .step-item .avatar-md { transition: all 0.3s ease; }
        .step-item.active .avatar-md { background: #405189 !important; transform: scale(1.1); box-shadow: 0 4px 15px rgba(64,81,137,0.4); }
        .card { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); }
        .card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; }
        .input-group-text { border: 2px solid #e2e8f0 !important; border-right: none !important; background: #f8f9fa !important; }
        .input-group .form-control { border: 2px solid #e2e8f0 !important; border-left: none !important; }

        #calendar-sesiones {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        #calendar-sesiones .fc-timegrid-slot { height: 2.2em !important; }
        #calendar-sesiones .fc-timegrid-slot-label-cushion,
        #calendar-sesiones .fc-timegrid-axis-cushion { font-size: 0.8rem; color: #6c757d; font-weight: 500; }
        #calendar-sesiones .fc-now-indicator-line { border-color: #405189 !important; }
        #calendar-sesiones .fc-button-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%) !important;
            border: none !important; border-radius: 8px !important;
            padding: 6px 14px !important; font-weight: 600 !important; transition: all 0.2s ease !important;
        }
        #calendar-sesiones .fc-button-primary:hover { transform: translateY(-1px) !important; box-shadow: 0 4px 12px rgba(64,81,137,0.3) !important; }
        #calendar-sesiones .fc-button-primary:not(:disabled).fc-button-active { background: linear-gradient(135deg, #2e3b6e 0%, #4757a8 100%) !important; }
        #calendar-sesiones .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: #405189 !important; }
        #calendar-sesiones .fc-col-header-cell-cushion { font-weight: 700; color: #405189; padding: 10px; }
        #calendar-sesiones .fc-event { cursor: pointer; border-radius: 6px !important; padding: 2px 6px !important; font-size: 0.8rem !important; font-weight: 600 !important; border: none !important; transition: all 0.2s ease !important; }
        #calendar-sesiones .fc-event:hover { transform: scale(1.02) !important; box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important; }
        #calendar-sesiones .fc-day-past { background: #f8f9fa !important; opacity: 0.7; }

        .sesion-detalle-card { border-left: 4px solid #405189 !important; transition: all 0.2s ease; }
        .sesion-detalle-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(64,81,137,0.15) !important; }
        .btn-icon-sm { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.85rem; }

        .badge { font-weight: 600; letter-spacing: 0.3px; }

        @media (max-width: 768px) {
            .page-title-box { flex-direction: column !important; align-items: flex-start !important; gap: 15px; }
            .step-item small { font-size: 10px; }
            .avatar-md { width: 35px !important; height: 35px !important; }
        }
    </style>
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        $(document).ready(function() {
            var token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });

            @if (session('success'))
                showToast('success', '{{ session('success') }}');
            @endif
            @if (session('error'))
                showToast('error', '{{ session('error') }}');
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('error', '{{ $error }}');
                @endforeach
            @endif

            // ========== DATOS INICIALES ==========
            var cantidadSesiones      = {{ $cantidadSesiones }};
            var sesionesNoEditablesCount = {{ $sesionesNoEditablesCount }};
            var suscripcionId         = {{ $suscripcion->id }};

            // Horas opciones para select
            var horasOptions = '';
            for (var h = 8; h <= 20; h++) {
                for (var m = 0; m < 60; m += 30) {
                    var hora = (h < 10 ? '0' + h : h) + ':' + (m === 0 ? '00' : '30');
                    horasOptions += '<option value="' + hora + '">' + hora + '</option>';
                }
            }

            // Pre-cargar sesiones editables existentes
            var sesionesData = @json($sesionesDataInit);

            // ========== CALENDARIO ==========
            var calendarSesiones = null;
            var filtroTrabajadorId = '';

            function initCalendarioSesiones() {
                if (calendarSesiones) { calendarSesiones.destroy(); calendarSesiones = null; }

                var calendarEl = document.getElementById('calendar-sesiones');

                calendarSesiones = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    height: 650,
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    slotDuration: '00:30:00',
                    slotLabelInterval: '01:00:00',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay'
                    },
                    buttonText: { today: 'Hoy', week: 'Semana', day: 'Día' },
                    allDaySlot: false,
                    nowIndicator: true,
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    dayHeaderFormat: { weekday: 'short', day: 'numeric', month: 'short' },
                    eventSources: [
                        // Fuente 1: sesiones ocupadas de OTRAS suscripciones (rojo)
                        {
                            id: 'ocupados',
                            url: '{{ route('admin.suscripciones.calendarioOcupados') }}',
                            method: 'GET',
                            extraParams: function() {
                                return { trabajador_id: filtroTrabajadorId, suscripcion_id: suscripcionId };
                            },
                            failure: function() {
                                showToast('error', 'Error al cargar sesiones existentes del calendario');
                            }
                        },
                        // Fuente 2: sesiones editables de esta suscripción (azul, en memoria)
                        { id: 'editables', events: [] },
                        // Fuente 3: sesiones NO editables de esta suscripción (confirmada, realizada, etc.)
                        { id: 'fijas', events: @json($sesionesNoEditablesCalendario) }
                    ],
                    dateClick: function(info) {
                        if (info.allDay) {
                            showToast('info', 'Haz clic en un bloque de hora específico para agregar una sesión');
                            return;
                        }
                        var maxEditables = cantidadSesiones - sesionesNoEditablesCount;
                        if (sesionesData.length >= maxEditables) {
                            showToast('warning', 'Ya tienes todas las sesiones programadas (' + cantidadSesiones + ' en total según el plan).');
                            return;
                        }
                        var dateStr = info.dateStr;
                        var fecha = dateStr.substring(0, 10);
                        var hora  = dateStr.length >= 16 ? dateStr.substring(11, 16) : '';

                        // Verificar conflicto con slot ocupado
                        var clickedMs = info.date.getTime();
                        var conflicto = false;
                        calendarSesiones.getEvents().forEach(function(ev) {
                            if (ev.extendedProps.tipo === 'ocupado') {
                                var evTrabId = ev.extendedProps.trabajador_id;
                                if (!filtroTrabajadorId || String(evTrabId) === String(filtroTrabajadorId)) {
                                    if (ev.start && ev.end && clickedMs >= ev.start.getTime() && clickedMs < ev.end.getTime()) {
                                        conflicto = true;
                                    }
                                }
                            }
                        });
                        if (conflicto) {
                            showToast('error', 'Este horario ya está ocupado. Selecciona otro slot disponible.');
                            return;
                        }
                        abrirModalSesion(fecha, hora);
                    },
                    eventClick: function(info) {
                        var tipo = info.event.extendedProps.tipo;
                        if (tipo === 'ocupado') {
                            showToast('warning', 'Horario ocupado: ' + info.event.title);
                            return;
                        }
                        if (tipo === 'fija') {
                            var estado = info.event.extendedProps.estado;
                            showToast('info', 'Sesión ' + estado + ': ' + info.event.title + ' — No editable');
                            return;
                        }
                        var eventId = info.event.id;
                        var title   = info.event.title;
                        if (confirm('¿Deseas eliminar la sesión "' + title + '"?')) {
                            eliminarSesion(eventId);
                        }
                    },
                    eventDidMount: function(info) {
                        var tipo = info.event.extendedProps.tipo;
                        if (tipo === 'ocupado') {
                            info.el.setAttribute('title', 'Ocupado: ' + info.event.title);
                            info.el.style.cursor = 'not-allowed';
                        } else if (tipo === 'fija') {
                            var estado = info.event.extendedProps.estado;
                            info.el.setAttribute('title', ucFirst(estado) + ': ' + info.event.title);
                            info.el.style.cursor = 'default';
                            info.el.style.opacity = '0.85';
                        }
                    }
                });

                calendarSesiones.render();

                // Pre-cargar sesiones editables existentes en el calendario
                sesionesData.forEach(function(s, i) {
                    calendarSesiones.addEvent({
                        id: String(s.id),
                        title: '#' + (i + 1) + ' ' + s._titulo,
                        start: s.fecha + 'T' + s.hora_inicio,
                        end:   s.fecha + 'T' + s.hora_fin,
                        backgroundColor: '#405189',
                        borderColor: '#405189',
                        extendedProps: { tipo: 'nueva' }
                    }, 'editables');
                });

                // Filtro por fisioterapeuta
                $('#filtro-fisioterapeuta-cal').on('change', function() {
                    filtroTrabajadorId = $(this).val();
                    var source = calendarSesiones.getEventSourceById('ocupados');
                    if (source) source.refetch();
                });
            }

            // Navegar al primer evento existente si los hay
            function navegarAlPrimerEvento() {
                if (sesionesData.length > 0 && sesionesData[0].fecha) {
                    calendarSesiones.gotoDate(sesionesData[0].fecha);
                }
            }

            initCalendarioSesiones();
            setTimeout(navegarAlPrimerEvento, 300);
            actualizarInputsSesiones();
            actualizarProgresoSesiones();
            actualizarListaSesiones();

            // ========== MODAL AGREGAR SESIÓN ==========
            function abrirModalSesion(fecha, horaPreseleccionada) {
                $('#sesion-fecha-selected').val(fecha);
                $('#sesion-edit-id').val('');
                var parts = fecha.split('-');
                $('#sesion-fecha-display').val(parts[2] + '/' + parts[1] + '/' + parts[0]);
                // Fecha en texto
                $('#sesion-fecha-display').text(parts[2] + '/' + parts[1] + '/' + parts[0]);

                var $horaSelect = $('#sesion-hora-inicio');
                $horaSelect.html('<option value="">-- Hora --</option>' + horasOptions);
                if (horaPreseleccionada) {
                    $horaSelect.val(horaPreseleccionada);
                    var partes = horaPreseleccionada.split(':');
                    var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : String(parseInt(partes[0]) + 1)) + ':' + partes[1];
                    $('#sesion-hora-fin').val(horaFin);
                } else {
                    $('#sesion-hora-fin').val('');
                }

                if (filtroTrabajadorId) {
                    $('#sesion-trabajador').val(filtroTrabajadorId);
                } else {
                    $('#sesion-trabajador').val('');
                }

                $('#disponibilidad-msg').hide().html('');
                $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Guardar Sesión');
                new bootstrap.Modal(document.getElementById('modalAgregarSesion')).show();
            }

            function eliminarSesion(eventId) {
                var idx = sesionesData.findIndex(function(s) { return String(s.id) === String(eventId); });
                if (idx === -1) return;
                sesionesData.splice(idx, 1);
                var event = calendarSesiones.getEventById(String(eventId));
                if (event) event.remove();
                // Renumerar
                sesionesData.forEach(function(s, i) {
                    var ev = calendarSesiones.getEventById(String(s.id));
                    if (ev) ev.setProp('title', '#' + (i + 1) + ' ' + s._titulo);
                });
                actualizarInputsSesiones();
                actualizarProgresoSesiones();
                actualizarListaSesiones();
                showToast('info', 'Sesión eliminada del calendario');
            }

            function actualizarInputsSesiones() {
                var html = '';
                sesionesData.forEach(function(s, i) {
                    var idx = sesionesNoEditablesCount + i;
                    if (s.id) html += '<input type="hidden" name="sesiones[' + idx + '][id]" value="' + s.id + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][hijo_id]" value="' + s.hijo_id + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][planes_servicio_id]" value="' + s.planes_servicio_id + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][trabajadore_id]" value="' + s.trabajadore_id + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][fecha]" value="' + s.fecha + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][hora_inicio]" value="' + s.hora_inicio + '">';
                    html += '<input type="hidden" name="sesiones[' + idx + '][hora_fin]" value="' + s.hora_fin + '">';
                });
                $('#sesiones-inputs').html(html);
            }

            function actualizarProgresoSesiones() {
                var programadas = sesionesData.length;
                var requeridas  = cantidadSesiones;
                $('#sesiones-programadas').text(programadas);
                $('#sesiones-requeridas').text(requeridas);
                var porcentaje = requeridas > 0 ? Math.min(100, (programadas / requeridas) * 100) : 0;
                var $bar = $('#progreso-sesiones');
                $bar.css('width', porcentaje + '%');
                if (programadas >= requeridas && requeridas > 0) {
                    $bar.removeClass('bg-danger bg-warning').addClass('bg-success');
                    $('#badge-sesiones-ok').removeClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-danger bg-opacity-10 text-danger').addClass('bg-success bg-opacity-10 text-success');
                } else if (porcentaje > 50) {
                    $bar.removeClass('bg-danger bg-success').addClass('bg-warning');
                    $('#badge-sesiones-ok').addClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-success bg-opacity-10 text-success').addClass('bg-danger bg-opacity-10 text-danger');
                } else {
                    $bar.removeClass('bg-warning bg-success').addClass('bg-danger');
                    $('#badge-sesiones-ok').addClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-success bg-opacity-10 text-success').addClass('bg-danger bg-opacity-10 text-danger');
                }
                $('#total-sesiones-count').text(programadas + '/' + requeridas);
            }

            function actualizarListaSesiones() {
                if (sesionesData.length === 0) {
                    $('#panel-detalle-sesiones').hide();
                    return;
                }
                var html = '';
                sesionesData.forEach(function(s, i) {
                    var hijoText     = $('#sesion-hijo option[value="' + s.hijo_id + '"]').text() || '-';
                    var trabText     = $('#sesion-trabajador option[value="' + s.trabajadore_id + '"]').text() || '-';
                    var servicioText = $('#sesion-servicio option[value="' + s.planes_servicio_id + '"]').text().split('(')[0].trim() || '-';
                    var fechaDisplay = s.fecha ? s.fecha.split('-').reverse().join('/') : '-';
                    html += '<div class="col-md-6 col-xl-4">' +
                        '<div class="card border-0 shadow-sm rounded-3 h-100 sesion-detalle-card">' +
                        '<div class="card-body p-3">' +
                        '<div class="d-flex align-items-start justify-content-between mb-2">' +
                        '<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(64,81,137,0.12);color:#405189;font-size:11px;">Sesión #' + (i + 1) + '</span>' +
                        '<div class="d-flex gap-1">' +
                        '<button type="button" class="btn-icon-sm btn btn-light edit-sesion-btn" data-id="' + s.id + '" title="Editar sesión"><i class="ri-edit-line" style="color:#405189;font-size:13px;"></i></button>' +
                        '<button type="button" class="btn-icon-sm btn btn-light eliminar-sesion-btn" data-id="' + s.id + '" title="Eliminar sesión"><i class="ri-delete-bin-line text-danger" style="font-size:13px;"></i></button>' +
                        '</div></div>' +
                        '<div class="mb-1" style="font-size:13px;"><i class="ri-calendar-event-line me-1" style="color:#405189;"></i><strong>' + fechaDisplay + '</strong><span class="text-muted ms-1">' + s.hora_inicio + ' – ' + s.hora_fin + '</span></div>' +
                        '<div class="text-muted text-truncate" style="font-size:12px;"><i class="ri-user-settings-line me-1 text-success"></i>' + trabText + '</div>' +
                        '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-baby-line me-1 text-info"></i>' + hijoText + '</div>' +
                        '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-service-line me-1" style="color:#f4a200;"></i>' + servicioText + '</div>' +
                        '</div></div></div>';
                });
                $('#lista-sesiones-detalle').html(html);
                $('#detalle-count').text(sesionesData.length);
                $('#panel-detalle-sesiones').show();
            }

            // Eliminar desde panel de detalle
            $(document).on('click', '.eliminar-sesion-btn', function() {
                var id = $(this).data('id');
                if (confirm('¿Eliminar esta sesión?')) { eliminarSesion(id); }
            });

            // Editar desde panel de detalle
            $(document).on('click', '.edit-sesion-btn', function() {
                var id  = $(this).data('id');
                var idx = sesionesData.findIndex(function(s) { return String(s.id) === String(id); });
                if (idx === -1) return;
                var s = sesionesData[idx];
                var originalId = s.id;
                // Quitar del calendario y datos
                eliminarSesion(id);
                // Abrir modal con datos pre-rellenados
                abrirModalSesion(s.fecha, s.hora_inicio);
                setTimeout(function() {
                    $('#sesion-edit-id').val(originalId);
                    $('#sesion-hijo').val(s.hijo_id);
                    $('#sesion-servicio').val(s.planes_servicio_id);
                    $('#sesion-trabajador').val(s.trabajadore_id);
                    $('#sesion-hora-inicio').val(s.hora_inicio);
                    $('#sesion-hora-fin').val(s.hora_fin);
                }, 100);
            });

            // Calcular hora fin en modal
            $(document).on('change', '#sesion-hora-inicio', function() {
                var h = $(this).val();
                if (h) {
                    var partes  = h.split(':');
                    var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : (parseInt(partes[0]) + 1)) + ':' + partes[1];
                    $('#sesion-hora-fin').val(horaFin);
                } else {
                    $('#sesion-hora-fin').val('');
                }
                $('#disponibilidad-msg').hide();
            });

            // Verificar disponibilidad al cambiar fisioterapeuta en modal
            $(document).on('change', '#sesion-trabajador', function() {
                var trabajadorId = $(this).val();
                var fecha        = $('#sesion-fecha-selected').val();
                var horaInicio   = $('#sesion-hora-inicio').val();
                $('#disponibilidad-msg').hide().html('');
                if (!trabajadorId || !fecha || !horaInicio) return;
                $('#disponibilidad-msg').html('<div class="text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Verificando disponibilidad...</div>').show();
                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: { _token: token, fecha: fecha, trabajador_id: trabajadorId, sesion_id: $('#sesion-edit-id').val() || null },
                    success: function(response) {
                        var noDisp = response.horas_no_disponibles || [];
                        if (noDisp.includes(horaInicio)) {
                            $('#disponibilidad-msg').html('<div class="alert alert-danger mb-0 py-2 small"><i class="ri-forbid-line me-1"></i>Este fisioterapeuta <strong>no está disponible</strong> a las ' + horaInicio + ' ese día.</div>').show();
                            $('#btnGuardarSesion').prop('disabled', true);
                        } else {
                            $('#disponibilidad-msg').html('<div class="alert alert-success mb-0 py-2 small"><i class="ri-checkbox-circle-line me-1"></i>Horario <strong>disponible</strong>.</div>').show();
                            $('#btnGuardarSesion').prop('disabled', false);
                        }
                    },
                    error: function() { $('#disponibilidad-msg').hide(); }
                });
            });

            // Guardar sesión desde el modal
            $('#btnGuardarSesion').on('click', function() {
                var fecha       = $('#sesion-fecha-selected').val();
                var hijoId      = $('#sesion-hijo').val();
                var servicioId  = $('#sesion-servicio').val();
                var trabajadorId = $('#sesion-trabajador').val();
                var horaInicio  = $('#sesion-hora-inicio').val();
                var editId      = $('#sesion-edit-id').val();

                if (!fecha || !hijoId || !servicioId || !trabajadorId || !horaInicio) {
                    showToast('warning', 'Completa todos los campos requeridos');
                    return;
                }

                var partes  = horaInicio.split(':');
                var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : String(parseInt(partes[0]) + 1)) + ':' + partes[1];
                var hijoText = $('#sesion-hijo option:selected').text();
                var trabText = $('#sesion-trabajador option:selected').text();
                var titulo   = hijoText.split(' ')[0] + ' - ' + trabText.split(' ')[0];

                $('#btnGuardarSesion').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Verificando...');
                $('#disponibilidad-msg').hide();

                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: { _token: token, fecha: fecha, trabajador_id: trabajadorId, sesion_id: editId || null },
                    success: function(response) {
                        var noDisp = response.horas_no_disponibles || [];
                        if (noDisp.includes(horaInicio)) {
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-danger mb-0 py-2 small d-flex align-items-start gap-2">' +
                                '<i class="ri-forbid-line fs-5 flex-shrink-0"></i>' +
                                '<div><strong>Horario no disponible</strong><br>' +
                                'El fisioterapeuta <strong>' + trabText + '</strong> ya tiene una sesión a las <strong>' + horaInicio + '</strong> el <strong>' + fecha.split('-').reverse().join('/') + '</strong>.' +
                                ' Por favor elige otra hora u otro fisioterapeuta.</div></div>'
                            ).show();
                            $('#sesion-hora-inicio').val('');
                            $('#sesion-hora-fin').val('');
                            $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Guardar Sesión');
                            return;
                        }
                        // Verificar conflicto local (sesiones ya en memoria)
                        var conflictoLocal = sesionesData.some(function(s) {
                            if (String(s.trabajadore_id) !== String(trabajadorId) || s.fecha !== fecha) return false;
                            return horaInicio < s.hora_fin && s.hora_inicio < horaFin;
                        });
                        if (conflictoLocal) {
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-danger mb-0 py-2 small d-flex align-items-start gap-2">' +
                                '<i class="ri-forbid-line fs-5 flex-shrink-0"></i>' +
                                '<div><strong>Conflicto con sesión ya programada</strong><br>' +
                                'Ya programaste una sesión para <strong>' + trabText + '</strong> a las <strong>' + horaInicio + '</strong> ese día.</div></div>'
                            ).show();
                            $('#sesion-hora-inicio').val('');
                            $('#sesion-hora-fin').val('');
                            $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Guardar Sesión');
                            return;
                        }
                        agregarSesionAlCalendario(fecha, hijoId, servicioId, trabajadorId, horaInicio, horaFin, titulo, editId);
                    },
                    error: function() {
                        $('#disponibilidad-msg').html('<div class="alert alert-warning mb-0 py-2 small"><i class="ri-alert-line me-1"></i>No se pudo verificar disponibilidad. Intenta de nuevo.</div>').show();
                        $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Guardar Sesión');
                    }
                });
            });

            function agregarSesionAlCalendario(fecha, hijoId, servicioId, trabajadorId, horaInicio, horaFin, titulo, editId) {
                // Usar el ID original si es edición, o generar uno temporal
                var id = editId ? editId : ('new_' + Date.now());
                var numSesion = sesionesData.length + 1;
                var sesion = {
                    id: id,
                    hijo_id: hijoId,
                    planes_servicio_id: servicioId,
                    trabajadore_id: trabajadorId,
                    fecha: fecha,
                    hora_inicio: horaInicio,
                    hora_fin: horaFin,
                    _titulo: titulo
                };
                sesionesData.push(sesion);

                calendarSesiones.addEvent({
                    id: String(id),
                    title: '#' + numSesion + ' ' + titulo,
                    start: fecha + 'T' + horaInicio,
                    end:   fecha + 'T' + horaFin,
                    backgroundColor: '#405189',
                    borderColor: '#405189',
                    extendedProps: { tipo: 'nueva' }
                }, 'editables');

                actualizarInputsSesiones();
                actualizarProgresoSesiones();
                actualizarListaSesiones();

                var fechaDisplay = fecha.split('-').reverse().join('/');
                bootstrap.Modal.getInstance(document.getElementById('modalAgregarSesion')).hide();
                showToast('success', 'Sesión #' + numSesion + ' guardada para el ' + fechaDisplay + ' a las ' + horaInicio);
                $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Guardar Sesión');
            }

            function ucFirst(str) { return str ? str.charAt(0).toUpperCase() + str.slice(1) : str; }

            // ========== TOAST ==========
            function showToast(type, message) {
                var config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
                    error:   { icon: 'ri-close-circle-fill',    bgClass: 'bg-danger',  title: 'Error' },
                    warning: { icon: 'ri-alert-fill',           bgClass: 'bg-warning', title: 'Advertencia' },
                    info:    { icon: 'ri-information-fill',     bgClass: 'bg-info',    title: 'Información' }
                };
                var cfg = config[type] || config.info;
                var toastId = 'toast-' + Date.now();
                var toastHtml = '<div id="' + toastId + '" class="toast ' + cfg.bgClass + ' text-white shadow-lg" role="alert">' +
                    '<div class="toast-header ' + cfg.bgClass + ' text-white border-bottom-0">' +
                    '<i class="' + cfg.icon + ' me-2"></i><strong class="me-auto">' + cfg.title + '</strong>' +
                    '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button></div>' +
                    '<div class="toast-body d-flex align-items-center"><i class="' + cfg.icon + ' me-2 fs-5"></i>' +
                    '<span class="flex-grow-1">' + message + '</span></div></div>';
                var container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    container.style.zIndex = '9999';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                var toastElement = document.getElementById(toastId);
                new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 }).show();
                toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
            }
        });
    </script>
@endpush
