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
                            <i class="ri-information-line me-2"></i>Detalle de Suscripción #{{ $suscripcion->id }}
                        </h4>
                        <p class="text-white-50 mb-0">Visualiza toda la información y el progreso de esta suscripción</p>
                    </div>
                    <div class="page-title-right">
                        <div class="d-flex gap-2">
                            @can('suscripciones.editar')
                            <a href="{{ route('admin.suscripciones.edit', $suscripcion->id) }}"
                                class="btn btn-warning btn-lg rounded-pill px-4 shadow-sm">
                                <i class="ri-edit-line me-1"></i>Editar Sesiones
                            </a>
                            @endcan
                            <a href="{{ route('admin.suscripciones.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                                <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <!-- TARJETA HIJO -->
            <div class="col-xl-6 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle fs-24">
                                        <i class="ri-baby-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Niño(a)</p>
                                @if ($hijoPrincipal && $hijoPrincipal->persona)
                                    <h6 class="mb-0 fw-bold text-dark">{{ $hijoPrincipal->persona->nombres }}
                                        {{ $hijoPrincipal->persona->apellido_paterno }}</h6>
                                    <small class="text-muted">{{ $hijoPrincipal->persona->carnet ?? 'Sin CI' }}</small>
                                    @if ($hijoPrincipal->persona->fecha_nacimiento)
                                        <br>
                                        <small class="text-info">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ \Carbon\Carbon::parse($hijoPrincipal->persona->fecha_nacimiento)->format('d/m/Y') }}
                                            ({{ \Carbon\Carbon::parse($hijoPrincipal->persona->fecha_nacimiento)->age }}
                                            años)
                                        </small>
                                    @endif
                                @else
                                    <h6 class="mb-0 fw-bold text-muted">Sin hijo asignado</h6>
                                    <small class="text-muted">No hay sesiones registradas</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA CLIENTE -->
            <div class="col-xl-6 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Cliente</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $suscripcion->cliente->persona->nombres }}
                                    {{ $suscripcion->cliente->persona->apellido_paterno }}</h6>
                                <small class="text-muted">{{ $suscripcion->cliente->persona->carnet ?? 'Sin CI' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">

            <!-- TARJETA PLAN -->
            <div class="col-xl-6 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                        <i class="ri-stack-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Plan</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $suscripcion->plan->nombre_plan }}</h6>
                                <small
                                    class="text-muted">{{ $suscripcion->plan->paquete->nombre ?? 'Sin paquete' }}</small>
                                <div class="mt-1">
                                    @if($suscripcion->tarifa === 'especial')
                                        <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.7rem;">
                                            <i class="ri-star-fill me-1"></i>Tarifa Especial
                                        </span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:.7rem;">
                                            <i class="ri-sun-line me-1"></i>Tarifa Normal
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA SESIONES -->
            @php
                $totalSesiones = $suscripcion->sesiones_iniciales;
                $sesionesValidas = $suscripcion->sesiones->whereNotIn('estado', ['reprogramada', 'cancelada']);
                $agendadas   = $sesionesValidas->count();
                $realizadas  = $sesionesValidas->where('estado', 'realizada')->count();
                $confirmadas = $sesionesValidas->whereIn('estado', ['programada', 'confirmada'])->count();
                $canceladas  = $suscripcion->sesiones->where('estado', 'cancelada')->count();
                $porAgendar  = $sesionesPendientes;
                $pctAgendadas  = $totalSesiones > 0 ? round(($agendadas / $totalSesiones) * 100) : 0;
                $pctRealizadas = $totalSesiones > 0 ? round(($realizadas / $totalSesiones) * 100) : 0;
            @endphp
            <div class="col-xl-6 col-md-6">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 {{ $porAgendar > 0 ? 'border-warning border-opacity-50' : '' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div
                                        class="avatar-title {{ $porAgendar > 0 ? 'bg-warning bg-opacity-10 text-warning' : 'bg-success bg-opacity-10 text-success' }} rounded-circle fs-24">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Sesiones</p>
                                <div class="d-flex align-items-baseline gap-1 mb-1">
                                    <h6 class="mb-0 fw-bold text-dark">{{ $agendadas }}</h6>
                                    <span class="text-muted small">/ {{ $totalSesiones }} del plan</span>
                                </div>
                                <div class="progress mb-2" style="height:5px;border-radius:10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width:{{ $pctRealizadas }}%" title="{{ $realizadas }} realizadas"></div>
                                    <div class="progress-bar bg-primary bg-opacity-50" role="progressbar"
                                        style="width:{{ $pctAgendadas - $pctRealizadas }}%"
                                        title="{{ $confirmadas }} programadas/confirmadas"></div>
                                </div>
                                <div class="d-flex flex-wrap gap-2" style="font-size:.7rem;">
                                    <span class="text-success fw-semibold">
                                        <i class="ri-check-double-line me-1"></i>{{ $realizadas }} realizadas
                                    </span>
                                    <span class="text-primary fw-semibold">
                                        <i class="ri-calendar-line me-1"></i>{{ $confirmadas }} programadas
                                    </span>
                                    @if ($porAgendar > 0)
                                        <span class="text-warning fw-semibold">
                                            <i class="ri-time-line me-1"></i>{{ $porAgendar }} por agendar
                                        </span>
                                    @endif
                                    @if ($canceladas > 0)
                                        <span class="text-danger fw-semibold">
                                            <i class="ri-close-circle-line me-1"></i>{{ $canceladas }} canceladas
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARJETA DE ESTADO DE PAGO (Fila separada) -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 {{ $saldoPendiente > 0 ? 'border-warning' : 'border-success' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-lg">
                                        <div
                                            class="avatar-title {{ $saldoPendiente > 0 ? 'bg-warning bg-opacity-10 text-warning' : 'bg-success bg-opacity-10 text-success' }} rounded-circle fs-24">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1 fw-medium">Estado de Pago</p>
                                    <h6 class="mb-0 fw-bold {{ $saldoPendiente > 0 ? 'text-warning' : 'text-success' }}">
                                        @if ($saldoPendiente > 0)
                                            <i class="ri-alert-line me-1"></i>Pendiente:
                                            {{ number_format($saldoPendiente, 2) }} Bs.
                                        @else
                                            <i class="ri-check-circle-line me-1"></i>Pagado
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ number_format($totalPagado, 2) }} Bs. de
                                        {{ number_format($precioTotal, 2) }} Bs.
                                        @if($suscripcion->tarifa === 'especial')
                                            <span class="badge rounded-pill ms-1" style="background:#fef3c7;color:#b45309;font-size:.62rem;">
                                                <i class="ri-star-fill me-1"></i>Especial
                                            </span>
                                        @else
                                            <span class="badge rounded-pill ms-1" style="background:#f0fdf4;color:#16a34a;font-size:.62rem;">
                                                <i class="ri-sun-line me-1"></i>Normal
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <!-- BOTÓN PARA REGISTRAR PAGO -->
                                @can('suscripciones.registrar.pago')
                                @if ($saldoPendiente > 0)
                                    <a href="{{ route('admin.suscripciones.pagos.create', $suscripcion->id) }}"
                                        class="btn btn-success btn-lg rounded-pill px-4 shadow-sm">
                                        <i class="ri-money-dollar-circle-line me-1"></i>Registrar Pago
                                    </a>
                                @else
                                    <button type="button" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm"
                                        disabled>
                                        <i class="ri-check-circle-line me-1"></i>Pago Completado
                                    </button>
                                @endif
                                @endcan
                                <!-- BOTÓN VER PAGOS -->
                                @can('suscripciones.ver.pagos')
                                <button type="button"
                                    class="btn btn-info btn-lg rounded-pill px-4 shadow-sm btn-ver-pagos"
                                    data-id="{{ $suscripcion->id }}" data-nombre="Suscripción #{{ $suscripcion->id }}">
                                    <i class="ri-list-unordered me-1"></i>Ver Pagos
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <!-- Calendario de Sesiones -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-3">
                                <span class="avatar-title text-info"><i class="ri-calendar-line fs-5"></i></span>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold text-dark">Calendario de Sesiones</h5>
                                <small class="text-muted">Visualización gráfica de todas las sesiones programadas</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="calendar"></div>
                        <!-- Leyenda de estados -->
                        <div class="card border-0 bg-light rounded-4 mt-4">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="ri-information-line me-2 text-info"></i>Estado de las sesiones
                                </h6>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($coloresPorEstado as $estado => $color)
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                style="display:inline-block; width:20px; height:20px; background-color:{{ $color }}; border-radius:4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></span>
                                            <span class="fw-medium">{{ ucfirst($estado) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-muted small mt-3 mb-0">
                                    <i class="ri-palette-line me-1"></i> El color de fondo de cada evento indica su estado.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== SESIONES PENDIENTES DE AGENDAR ========== --}}
                @if ($sesionesPendientes > 0)
                    <div class="card border-0 shadow-lg rounded-4 mb-4" id="card-agendar-pendientes">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle me-3">
                                        <span class="avatar-title text-danger"><i
                                                class="ri-calendar-check-line fs-5"></i></span>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Agendar Sesiones Pendientes</h5>
                                        <small class="text-muted">Programa las sesiones restantes del plan</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                    <i class="ri-time-line me-1"></i>{{ $sesionesPendientes }} sesión(es) por agendar
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if (session('pago_completado'))
                                <div class="alert alert-dismissible fade show rounded-4 mb-3 d-flex align-items-center gap-3 border-0 shadow-sm"
                                    style="background:linear-gradient(135deg, rgba(25,135,84,.15), rgba(13,148,136,.08)); border-left:5px solid #198754 !important;">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                        style="width:52px;height:52px;background:rgba(25,135,84,.18);color:#198754;">
                                        <i class="ri-check-double-line fs-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold" style="color:#198754;">¡Suscripción totalmente pagada!</h5>
                                        <p class="mb-0 text-muted small">{{ session('success') }}</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @elseif (session('success'))
                                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
                                    <i class="ri-check-circle-line me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if ($errors->has('sesiones'))
                                <div class="alert alert-danger rounded-3 mb-3">
                                    <i class="ri-error-warning-line me-2"></i>{{ $errors->first('sesiones') }}
                                </div>
                            @endif

                            <div class="alert alert-info d-flex align-items-center rounded-3 mb-3">
                                <i class="ri-information-line fs-4 me-3 text-info"></i>
                                <div>
                                    <strong>¿Cómo agendar?</strong>
                                    <p class="mb-0 small">Haz clic en el calendario para agregar una sesión. Puedes
                                        programar hasta <strong>{{ $sesionesPendientes }}</strong> sesión(es) más. Haz clic
                                        sobre un evento para eliminarlo.</p>
                                </div>
                            </div>

                            {{-- Progreso --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-medium">Sesiones a agendar: <strong
                                        id="pend-programadas">0</strong> / <strong
                                        id="pend-requeridas">{{ $sesionesPendientes }}</strong></span>
                                <span id="pend-badge-ok" class="badge bg-success d-none"><i
                                        class="ri-check-line me-1"></i>¡Completo!</span>
                            </div>
                            <div class="progress mb-4" style="height:8px;border-radius:10px;">
                                <div class="progress-bar bg-danger" id="pend-progreso" role="progressbar"
                                    style="width:0%;"></div>
                            </div>

                            {{-- Filtro fisioterapeuta + leyenda --}}
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 fw-medium small"><i
                                            class="ri-user-settings-line me-1"></i>Ver agenda de:</label>
                                    <select id="pend-filtro-fisiote" class="form-select form-select-sm"
                                        style="min-width:220px;">
                                        <option value="">Todos los fisioterapeutas</option>
                                        @foreach ($trabajadores as $t)
                                            <option value="{{ $t->id }}">{{ $t->persona->nombres }}
                                                {{ $t->persona->apellido_paterno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="d-flex align-items-center gap-1 small"><span
                                            style="display:inline-block;width:14px;height:14px;background:#dc3545;border-radius:3px;"></span>
                                        Ocupado (otro cliente)</span>
                                    <span class="d-flex align-items-center gap-1 small"><span
                                            style="display:inline-block;width:14px;height:14px;background:#f59e0b;border-radius:3px;"></span>
                                        Esta suscripción</span>
                                    <span class="d-flex align-items-center gap-1 small"><span
                                            style="display:inline-block;width:14px;height:14px;background:#405189;border-radius:3px;"></span>
                                        A agendar</span>
                                </div>
                            </div>

                            <div id="calendar-pendientes" class="rounded-3"></div>

                            <form id="formAgendarPendientes"
                                action="{{ route('admin.suscripciones.agendarPendientes', $suscripcion->id) }}"
                                method="POST">
                                @csrf
                                <div id="pend-sesiones-inputs"></div>

                                {{-- Panel de sesiones añadidas --}}
                                <div id="pend-panel-detalle" class="mt-4" style="display:none;">
                                    <hr class="my-3">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0"><i class="ri-list-check-2 me-2"
                                                style="color:#405189;"></i>Sesiones a registrar</h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3"><span
                                                id="pend-detalle-count">0</span> sesiones</span>
                                    </div>
                                    <div id="pend-lista-detalle" class="row g-2"></div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="submit" id="btnGuardarPendientes"
                                        class="btn btn-primary rounded-pill px-4" disabled>
                                        <i class="ri-save-3-line me-2"></i>Guardar Sesiones Agendadas
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Listado de Sesiones (Cards) -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-warning"><i class="ri-list-check fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Listado de Sesiones</h5>
                                    <small class="text-muted">Gestión y seguimiento del estado de cada sesión</small>
                                </div>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="ri-time-line me-1"></i>{{ $suscripcion->sesiones->count() }} sesiones
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $sesionesOrdenadas = $suscripcion->sesiones->sortBy(function ($sesion) {
                                return $sesion->fecha
                                    ? $sesion->fecha->format('Y-m-d') . ' ' . ($sesion->hora_inicio ?? '00:00')
                                    : '9999-12-31';
                            });
                            $hoy = \Carbon\Carbon::today();
                            $clienteCelular =
                                $suscripcion->cliente->persona->celular ??
                                ($suscripcion->cliente->persona->telefono ?? '');
                            $clienteNombre =
                                $suscripcion->cliente->persona->nombres .
                                ' ' .
                                $suscripcion->cliente->persona->apellido_paterno;
                        @endphp

                        <div class="row g-3">
                            @foreach ($sesionesOrdenadas as $sesion)
                                @php
                                    $esReprogramacion = !is_null($sesion->reprogramar_id);
                                    $tieneReprogramaciones = $sesion->reprogramaciones->count() > 0;
                                    $yaVencio = $sesion->fecha && \Carbon\Carbon::parse($sesion->fecha)->lt($hoy);
                                    $estadoHexColor = $coloresPorEstado[$sesion->estado] ?? '#6c757d';
                                    $estadoTextColor = in_array($sesion->estado, ['programada', 'reprogramada'])
                                        ? '#212529'
                                        : '#ffffff';

                                    // WhatsApp message
                                    $fechaSesion = $sesion->fecha
                                        ? \Carbon\Carbon::parse($sesion->fecha)
                                            ->locale('es')
                                            ->isoFormat('dddd D [de] MMMM [de] YYYY')
                                        : '';
                                    $horaSesion = $sesion->hora_inicio
                                        ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i')
                                        : '';
                                    $msgWa = "Estimado/a {$clienteNombre}, le escribimos para recordarle que tiene una sesión programada para el {$fechaSesion} a las {$horaSesion} hrs. Por favor confirme su asistencia respondiendo este mensaje. ¡Gracias!";
                                    $waUrl =
                                        'https://wa.me/' .
                                        preg_replace('/\D/', '', $clienteCelular) .
                                        '?text=' .
                                        rawurlencode($msgWa);
                                @endphp

                                <div class="col-12" id="sesion-{{ $sesion->id }}">
                                    <div
                                        class="card border-0 rounded-4 shadow-sm
                                    {{ $sesion->estado === 'realizada' ? 'border-start border-success border-3' : '' }}
                                    {{ $sesion->estado === 'cancelada' || $sesion->estado === 'reprogramada' ? 'border-start border-secondary border-3 opacity-75' : '' }}
                                    {{ in_array($sesion->estado, ['programada', 'confirmada']) && $yaVencio ? 'border-start border-danger border-3' : '' }}
                                    {{ in_array($sesion->estado, ['programada', 'confirmada']) && !$yaVencio ? 'border-start border-primary border-3' : '' }}
                                ">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center g-2">

                                                {{-- Nro sesión + estado --}}
                                                <div class="col-auto text-center" style="min-width:60px">
                                                    <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold"
                                                        style="width:38px;height:38px;background-color:{{ $estadoHexColor }};color:{{ $estadoTextColor }};font-size:0.95rem;box-shadow:0 2px 6px rgba(0,0,0,0.15);">
                                                        {{ $sesion->n_sesion }}
                                                    </div>
                                                    <span class="badge rounded-pill px-2 py-1"
                                                        style="font-size:0.62rem;background-color:{{ $estadoHexColor }};color:{{ $estadoTextColor }};border:1px solid rgba(0,0,0,0.08);">
                                                        {{ ucfirst($sesion->estado) }}
                                                    </span>
                                                </div>

                                                {{-- Info sesión --}}
                                                <div class="col">
                                                    <div class="row g-1">
                                                        <div class="col-sm-4">
                                                            <div class="d-flex align-items-center gap-1">
                                                                <i class="ri-baby-line text-info"></i>
                                                                <span
                                                                    class="fw-semibold small">{{ $sesion->hijo->persona->nombres }}
                                                                    {{ $sesion->hijo->persona->apellido_paterno }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                                <i class="ri-service-line text-success"></i>
                                                                <span
                                                                    class="text-muted small">{{ $sesion->servicio->nombre ?? '-' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                                @if($sesion->suscripcion->tarifa === 'especial')
                                                                    <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.62rem;">
                                                                        <i class="ri-star-fill me-1"></i>Especial
                                                                    </span>
                                                                @else
                                                                    <span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:.62rem;">
                                                                        <i class="ri-sun-line me-1"></i>Normal
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="d-flex align-items-center gap-1">
                                                                <i class="ri-calendar-line text-primary"></i>
                                                                <span
                                                                    class="small">{{ $sesion->fecha ? \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('D [de] MMM YYYY') : '-' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                                <i class="ri-time-line text-warning"></i>
                                                                <span class="small">
                                                                    @if ($sesion->hora_inicio && $sesion->hora_fin)
                                                                        {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="d-flex align-items-center gap-1">
                                                                <i class="ri-user-line text-primary"></i>
                                                                <span
                                                                    class="small">{{ $sesion->trabajador ? $sesion->trabajador->persona->nombres . ' ' . $sesion->trabajador->persona->apellido_paterno : '-' }}</span>
                                                            </div>
                                                            @if ($esReprogramacion)
                                                                <div class="mt-1">
                                                                    <span
                                                                        class="badge bg-info bg-opacity-10 text-info rounded-pill"
                                                                        style="font-size:0.65rem;">
                                                                        <i class="ri-refresh-line me-1"></i>Reprog. de
                                                                        #{{ $sesion->sesionOriginal->n_sesion }}
                                                                    </span>
                                                                </div>
                                                            @elseif ($tieneReprogramaciones)
                                                                <div class="mt-1">
                                                                    <span
                                                                        class="badge bg-warning bg-opacity-10 text-warning rounded-pill"
                                                                        style="font-size:0.65rem;">
                                                                        <i class="ri-refresh-line me-1"></i>Nueva:
                                                                        #{{ $sesion->reprogramaciones->first()->n_sesion }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Alerta sesión vencida --}}
                                                    @if ($yaVencio && in_array($sesion->estado, ['programada', 'confirmada']))
                                                        <div class="alert alert-danger py-1 px-2 mt-2 mb-0 d-flex align-items-center gap-2 rounded-3"
                                                            style="font-size:0.8rem;">
                                                            <i class="ri-alarm-warning-line fs-5"></i>
                                                            <span>La fecha de esta sesión ya pasó. Por favor marque como
                                                                <strong>Realizada</strong> o
                                                                <strong>Cancelada</strong>.</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Botones de acción --}}
                                                <div class="col-auto d-flex flex-column gap-2 align-items-end">
                                                    {{-- Botón Editar (solo programada y confirmada) --}}
                                                    @if (in_array($sesion->estado, ['programada', 'confirmada']))
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 editar-sesion-btn"
                                                            data-id="{{ $sesion->id }}"
                                                            data-fecha="{{ $sesion->fecha ? $sesion->fecha->format('Y-m-d') : '' }}"
                                                            data-hora="{{ $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : '' }}"
                                                            data-trabajador="{{ $sesion->trabajadore_id }}"
                                                            title="Editar fecha/hora/fisioterapeuta">
                                                            <i class="ri-edit-line me-1"></i>Editar
                                                        </button>
                                                    @endif
                                                    @if ($sesion->estado === 'programada')
                                                        {{-- WhatsApp recordatorio --}}
                                                        @if ($clienteCelular)
                                                            <a href="{{ $waUrl }}" target="_blank"
                                                                class="btn btn-sm btn-success rounded-pill px-3"
                                                                title="Enviar recordatorio por WhatsApp">
                                                                <i class="ri-whatsapp-line me-1"></i>Recordar
                                                            </a>
                                                        @endif
                                                        {{-- Confirmar --}}
                                                        <form method="POST"
                                                            action="{{ route('admin.sesiones.cambiarEstado', $sesion->id) }}"
                                                            class="d-inline">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="estado" value="confirmada">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-info rounded-pill px-3"
                                                                title="Marcar como confirmada">
                                                                <i class="ri-check-line me-1"></i>Confirmar
                                                            </button>
                                                        </form>
                                                        {{-- Cancelar (abre reprogramar modal) --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 cancelar-sesion-btn"
                                                            data-id="{{ $sesion->id }}"
                                                            data-fecha="{{ $sesion->fecha ? $sesion->fecha->format('Y-m-d') : '' }}"
                                                            data-hora="{{ $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : '' }}"
                                                            data-trabajador="{{ $sesion->trabajadore_id }}"
                                                            title="Cancelar y reprogramar">
                                                            <i class="ri-close-circle-line me-1"></i>Cancelar
                                                        </button>
                                                    @elseif ($sesion->estado === 'confirmada')
                                                        {{-- Marcar realizada: solo si la fecha+hora de la sesión ya pasó --}}
                                                        @php
                                                            $sesionYaPaso =
                                                                $sesion->fecha && $sesion->hora_inicio
                                                                    ? \Carbon\Carbon::parse(
                                                                        $sesion->fecha->format('Y-m-d') .
                                                                            ' ' .
                                                                            $sesion->hora_inicio,
                                                                    )->isPast()
                                                                    : false;
                                                        @endphp
                                                        <button type="button"
                                                            class="btn btn-sm btn-success rounded-pill px-3 btn-marcar-realizada"
                                                            data-id="{{ $sesion->id }}"
                                                            data-nsesion="{{ $sesion->n_sesion ?? '' }}"
                                                            title="{{ $sesionYaPaso ? 'Marcar como realizada' : 'La sesión aún no ha llegado su fecha y hora' }}"
                                                            {{ $sesionYaPaso ? '' : 'disabled' }}>
                                                            <i class="ri-check-double-line me-1"></i>Realizada
                                                        </button>
                                                        {{-- Cancelar (abre reprogramar modal) --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 cancelar-sesion-btn"
                                                            data-id="{{ $sesion->id }}"
                                                            data-fecha="{{ $sesion->fecha ? $sesion->fecha->format('Y-m-d') : '' }}"
                                                            data-hora="{{ $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : '' }}"
                                                            data-trabajador="{{ $sesion->trabajadore_id }}"
                                                            title="Cancelar y reprogramar">
                                                            <i class="ri-close-circle-line me-1"></i>Cancelar
                                                        </button>
                                                    @elseif ($sesion->estado === 'cancelada')
                                                        {{-- Solo reprogramar si no tiene reprogramación ya --}}
                                                        @if ($sesion->reprogramaciones->isEmpty())
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning rounded-pill px-3 reprogramar-btn"
                                                                data-id="{{ $sesion->id }}"
                                                                data-fecha="{{ $sesion->fecha ? $sesion->fecha->format('Y-m-d') : '' }}"
                                                                data-hora="{{ $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : '' }}"
                                                                data-trabajador="{{ $sesion->trabajadore_id }}"
                                                                title="Reprogramar sesión">
                                                                <i class="ri-refresh-line me-1"></i>Reprogramar
                                                            </button>
                                                        @endif
                                                    @elseif ($sesion->estado === 'realizada')
                                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                                            <i class="ri-check-double-line me-1"></i>Completada
                                                        </span>
                                                    @elseif ($sesion->estado === 'reprogramada')
                                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                            <i class="ri-refresh-line me-1"></i>Reprogramada
                                                        </span>
                                                    @endif

                                                    {{-- Botón Venta (para sesiones activas) --}}
                                                    @if (!in_array($sesion->estado, ['cancelada', 'reprogramada']))
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-warning rounded-pill px-3 btn-venta-sesion"
                                                            data-id="{{ $sesion->id }}"
                                                            data-nsesion="{{ $sesion->n_sesion }}"
                                                            data-cliente="{{ $suscripcion->cliente->persona->nombres }} {{ $suscripcion->cliente->persona->apellido_paterno }}"
                                                            title="Registrar venta de productos en esta sesión">
                                                            <i class="ri-shopping-cart-line me-1"></i>Venta
                                                        </button>
                                                    @endif

                                                    {{-- Ventas realizadas en esta sesión --}}
                                                    @if ($sesion->ventas->count() > 0)
                                                        <button type="button"
                                                            class="btn btn-sm rounded-pill px-3 btn-ver-ventas-sesion"
                                                            style="font-size:0.72rem;background:rgba(255,193,7,.15);color:#856404;border:1px solid rgba(255,193,7,.35);"
                                                            data-id="{{ $sesion->id }}"
                                                            data-nsesion="{{ $sesion->n_sesion }}"
                                                            title="Ver ventas de esta sesión">
                                                            <i class="ri-shopping-cart-2-line me-1"></i>{{ $sesion->ventas->count() }} venta(s)
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Bloques ocultos con detalle de ventas por sesión --}}
                        @foreach ($sesionesOrdenadas as $sesion)
                            @if ($sesion->ventas->count() > 0)
                                <div class="d-none" id="ventas-detalle-{{ $sesion->id }}">
                                    @foreach ($sesion->ventas as $venta)
                                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                                            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold small">
                                                    <i class="ri-receipt-line me-1 text-warning"></i>
                                                    @if ($venta->recibo)
                                                        <span class="text-warning fw-bold">{{ $venta->recibo }}</span>
                                                    @else
                                                        Venta #{{ $venta->id }}
                                                    @endif
                                                    — {{ $venta->fecha_venta->format('d/m/Y') }}
                                                </span>
                                                @php
                                                    $pagoColor = match($venta->tipo_pago) {
                                                        'efectivo' => 'success',
                                                        'qr'       => 'info',
                                                        default    => 'warning',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $pagoColor }}">{{ ucfirst($venta->tipo_pago) }}</span>
                                            </div>
                                            <div class="card-body p-3">
                                                {{-- Productos --}}
                                                <div class="table-responsive mb-2">
                                                    <table class="table table-sm table-bordered align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Producto</th>
                                                                <th class="text-center" style="width:60px">Cant.</th>
                                                                <th class="text-end" style="width:110px">Precio</th>
                                                                <th class="text-end" style="width:110px">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($venta->detalles as $det)
                                                                <tr>
                                                                    <td class="small">{{ $det->producto->nombre }}
                                                                        @if ($det->producto->marca)
                                                                            <span class="text-muted">({{ $det->producto->marca }})</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="small text-center">{{ $det->cantidad }}</td>
                                                                    <td class="small text-end">Bs {{ number_format($det->precio_unitario, 2) }}</td>
                                                                    <td class="small text-end fw-semibold">Bs {{ number_format($det->subtotal, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                {{-- Detalle de pago --}}
                                                @if ($venta->pagoDetalles->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                        <span class="small text-muted me-1 fw-medium">Detalle pago:</span>
                                                        @foreach ($venta->pagoDetalles as $pd)
                                                            <span class="badge bg-{{ $pd->tipo_pago === 'efectivo' ? 'success' : 'info' }} bg-opacity-75">
                                                                {{ ucfirst($pd->tipo_pago) }}: Bs {{ number_format($pd->pago_bs, 2) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Totales + trabajador + descarga --}}
                                                <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                                                    <div class="small text-muted">
                                                        @if ($venta->trabajador)
                                                            <i class="ri-user-line me-1"></i>Registrado por:
                                                            <strong>{{ $venta->trabajador->persona->nombres }} {{ $venta->trabajador->persona->apellido_paterno }}</strong>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="text-end small">
                                                            @if ($venta->descuento_bs > 0)
                                                                <div class="text-muted">Descuento: Bs {{ number_format($venta->descuento_bs, 2) }}</div>
                                                            @endif
                                                            <div class="fw-bold text-success fs-6">Total: Bs {{ number_format($venta->total_bs, 2) }}</div>
                                                        </div>
                                                        @if ($venta->recibo)
                                                            <a href="{{ route('admin.ventas.reciboPdf', $venta->id) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-warning rounded-pill px-3"
                                                                title="Descargar recibo {{ $venta->recibo }}">
                                                                <i class="ri-download-2-line me-1"></i>PDF
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle de Ventas de Sesión -->
    <div class="modal fade" id="modalDetalleVentas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning bg-gradient text-dark py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-shopping-cart-2-line me-2"></i>Ventas — <span id="vd-titulo"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="vd-body">
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Venta de Sesión -->
    <div class="modal fade" id="modalVentaSesion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning bg-gradient text-dark py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-shopping-cart-line me-2"></i>Registrar Venta
                        <small class="ms-2 fw-normal" id="vs-subtitle"></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="vs-sesion-id">

                    {{-- Cliente --}}
                    <div class="alert alert-light border rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2">
                        <i class="ri-user-line text-primary"></i>
                        <span class="small fw-medium">Cliente: <strong id="vs-cliente-nombre"></strong></span>
                    </div>

                    {{-- Buscar producto --}}
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-medium small"><i class="ri-search-line me-1"></i>Buscar producto</label>
                        <input type="text" id="vs-buscar-input" class="form-control"
                            placeholder="Escribe el nombre o marca del producto..." autocomplete="off">
                        <div id="vs-buscar-resultados" class="list-group shadow"
                            style="display:none;position:absolute;width:100%;max-height:220px;overflow-y:auto;z-index:1070;top:100%;left:0;"></div>
                    </div>

                    {{-- Tabla de items --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th style="width:80px">Cant.</th>
                                    <th style="width:120px">Precio (Bs)</th>
                                    <th style="width:120px">Subtotal</th>
                                    <th style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="vs-items-body">
                                <tr id="vs-empty-row">
                                    <td colspan="5" class="text-center text-muted small py-3">
                                        <i class="ri-inbox-line me-1"></i>Sin productos agregados
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-3">

                    {{-- Pago --}}
                    <div class="row g-3">
                        {{-- Tipo de pago --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small"><i class="ri-bank-card-line me-1 text-primary"></i>Tipo de pago <span class="text-danger">*</span></label>
                            <select id="vs-tipo-pago" class="form-select">
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="qr">📱 QR</option>
                                <option value="parcial">🔀 Parcial (Efectivo + QR)</option>
                            </select>
                        </div>

                        {{-- Monto único (efectivo o QR) --}}
                        <div class="col-md-6" id="vs-campo-monto-unico">
                            <label class="form-label fw-semibold small"><i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto a pagar (Bs) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" id="vs-monto" class="form-control" placeholder="0.00" min="0" step="0.01" value="0">
                            </div>
                        </div>

                        {{-- Monto efectivo (parcial) --}}
                        <div class="col-md-6" id="vs-campo-efectivo" style="display:none;">
                            <label class="form-label fw-semibold small"><i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto en Efectivo (Bs) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" id="vs-monto-efectivo" class="form-control" placeholder="0.00" min="0" step="0.01" value="0">
                            </div>
                        </div>

                        {{-- Monto QR (parcial) --}}
                        <div class="col-md-6" id="vs-campo-qr" style="display:none;">
                            <label class="form-label fw-semibold small"><i class="ri-qr-code-line me-1 text-info"></i>Monto en QR (Bs) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" id="vs-monto-qr" class="form-control" placeholder="0.00" min="0" step="0.01" value="0">
                            </div>
                        </div>

                        {{-- Descuento --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small"><i class="ri-percent-line me-1 text-warning"></i>Descuento (Bs)</label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" id="vs-descuento" class="form-control" value="0" min="0" step="0.01">
                            </div>
                        </div>

                        {{-- Observación --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Observación</label>
                            <input type="text" id="vs-observacion" class="form-control" placeholder="Opcional...">
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="alert alert-info d-flex align-items-start rounded-3 mt-3 py-2 px-3" id="vs-resumen">
                        <i class="ri-information-fill fs-5 me-2 text-info flex-shrink-0 mt-1"></i>
                        <div class="small w-100">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal productos:</span>
                                <strong id="vs-subtotal-label">Bs 0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Descuento:</span>
                                <strong id="vs-descuento-label">Bs 0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between border-top mt-1 pt-1">
                                <span class="fw-semibold">Total a pagar:</span>
                                <strong class="text-success" id="vs-total-label">Bs 0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Saldo restante:</span>
                                <strong id="vs-saldo-label">Bs 0.00</strong>
                            </div>
                        </div>
                    </div>

                    <div id="vs-monto-error" class="rounded-3 px-3 py-2 mt-2 small d-none"
                        style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.25);">
                        <i class="ri-error-warning-line text-danger me-1"></i>
                        <span class="text-danger fw-semibold" id="vs-monto-error-text"></span>
                    </div>
                    <div id="vs-error-msg" class="alert alert-danger py-2 px-3 mt-2 small d-none"></div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                    <button type="button" class="btn btn-warning fw-semibold" id="vs-btn-guardar" disabled>
                        <i class="ri-save-3-line me-1"></i>Registrar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Sesión -->
    <div class="modal fade" id="editarSesionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form method="POST" action="" id="formEditarSesion">
                    @csrf @method('PATCH')
                    <div class="modal-header bg-primary bg-gradient text-white py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="ri-edit-line me-2"></i>Editar sesión
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 px-3 rounded-3 mb-3 small">
                            <i class="ri-information-line me-1"></i>
                            Corrige la fecha, hora o fisioterapeuta. La hora de fin se recalculará automáticamente (1 hora
                            después).
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><i class="ri-user-line me-1"></i>Fisioterapeuta <span
                                    class="text-danger">*</span></label>
                            <select name="trabajadore_id" id="edit-trabajador" class="form-select form-select-lg"
                                required>
                                <option value="">-- Seleccionar fisioterapeuta --</option>
                                @foreach ($trabajadores as $t)
                                    <option value="{{ $t->id }}">{{ $t->persona->nombres }}
                                        {{ $t->persona->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="edit-fecha" class="form-control form-control-lg"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Hora de inicio <span class="text-danger">*</span></label>
                            <select name="hora_inicio" id="edit-hora" class="form-select form-select-lg" required>
                                <option value="">-- Seleccionar hora --</option>
                                @for ($h = 8; $h <= 20; $h++)
                                    @php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                    <option value="{{ $hora }}">{{ $hora }}</option>
                                    @php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':30'; @endphp
                                    <option value="{{ $hora }}">{{ $hora }}</option>
                                @endfor
                            </select>
                            <div id="edit-horas-ocupadas-msg" class="form-text text-warning d-none">
                                <i class="ri-time-line me-1"></i>Las opciones marcadas con <strong>(Ocupado)</strong> no
                                están disponibles para el fisioterapeuta.
                            </div>
                            <div class="form-text">La hora de fin se calculará automáticamente (1 hora después).</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-save-line me-1"></i>Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cancelar + Reprogramar Sesión -->
    <div class="modal fade" id="reprogramarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form method="POST" action="" id="formReprogramar">
                    @csrf
                    {{-- campo oculto para indicar si debe cancelar primero --}}
                    <input type="hidden" name="_cancelar_primero" id="cancelar_primero" value="0">
                    <div class="modal-header bg-warning bg-gradient text-white py-3">
                        <h5 class="modal-title fw-bold" id="reprogramarModalTitle">
                            <i class="ri-refresh-line me-2"></i>Reprogramar sesión
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="reprogramar-cancelar-alert" class="alert alert-danger d-none mb-3 py-2 px-3 rounded-3">
                            <i class="ri-alert-line me-2"></i>
                            Se marcará la sesión como <strong>Cancelada</strong> y se creará una nueva sesión reprogramada.
                        </div>
                        <input type="hidden" name="sesion_id" id="sesion_id">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><i class="ri-user-line me-1"></i>Fisioterapeuta <span
                                    class="text-danger">*</span></label>
                            <select name="trabajadore_id" id="modal-trabajador" class="form-select form-select-lg"
                                required>
                                <option value="">-- Seleccionar fisioterapeuta --</option>
                                @foreach ($trabajadores as $t)
                                    <option value="{{ $t->id }}">{{ $t->persona->nombres }}
                                        {{ $t->persona->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nueva fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="nueva_fecha" class="form-control form-control-lg"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nueva hora de inicio <span
                                    class="text-danger">*</span></label>
                            <select name="hora_inicio" id="nueva_hora" class="form-select form-select-lg" required>
                                <option value="">-- Seleccionar hora --</option>
                                @for ($h = 8; $h <= 20; $h++)
                                    @php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                    <option value="{{ $hora }}">{{ $hora }}</option>
                                    @php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':30'; @endphp
                                    <option value="{{ $hora }}">{{ $hora }}</option>
                                @endfor
                            </select>
                            <div id="horas-ocupadas-msg" class="form-text text-warning d-none">
                                <i class="ri-time-line me-1"></i>Las opciones marcadas con <strong>(Ocupado)</strong> no
                                están disponibles para el fisioterapeuta.
                            </div>
                            <div class="form-text">La hora de fin se calculará automáticamente (1 hora después).</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Motivo (opcional)</label>
                            <textarea name="motivo" id="motivo" class="form-control" rows="2"
                                placeholder="Ej: El cliente solicitó cambio de horario"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="ri-refresh-line me-1"></i>Confirmar reprogramación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Pagos -->
    <div class="modal fade" id="modalPagos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info bg-gradient text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-list-unordered me-2"></i>
                        <span id="modalPagosTitulo">Pagos de la Suscripción</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalPagosBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-3">Cargando información de pagos...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Marcar Realizada -->
    <div class="modal fade" id="modalRealizarSesion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,#198754 0%,#037e83 100%);border-radius:20px 20px 0 0;">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-check-double-line me-2"></i>Marcar como Realizada
                        <span id="sus-realizarSubtitle" class="fw-normal text-white-50 fs-6 ms-2"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="background:#fafbfc;">
                    <input type="hidden" id="sus-realizarSesionId">

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2" style="color:#1a2d4c;">
                            <i class="ri-user-follow-line me-1"></i>¿El niño/a asistió a la sesión?
                            <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3">
                            <label class="sus-asistencia-opcion flex-fill text-center rounded-3 py-3"
                                style="cursor:pointer;border:2px solid #e9ecef;background:#fff;transition:all .2s;">
                                <input type="radio" name="sus_asistio_hijo" id="sus-asistioSi" value="1" class="d-none">
                                <i class="ri-checkbox-circle-line d-block fs-3 mb-1" style="color:#198754;"></i>
                                <span class="fw-semibold" style="color:#198754;">Sí asistió</span>
                            </label>
                            <label class="sus-asistencia-opcion flex-fill text-center rounded-3 py-3"
                                style="cursor:pointer;border:2px solid #e9ecef;background:#fff;transition:all .2s;">
                                <input type="radio" name="sus_asistio_hijo" id="sus-asistioNo" value="0" class="d-none">
                                <i class="ri-close-circle-line d-block fs-3 mb-1" style="color:#dc3545;"></i>
                                <span class="fw-semibold" style="color:#dc3545;">No asistió</span>
                            </label>
                        </div>
                        <div id="sus-asistenciaError" class="text-danger small mt-1" style="display:none;">
                            <i class="ri-error-warning-line me-1"></i>Debe indicar si el niño/a asistió
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white border-top py-3 px-4" style="border-radius:0 0 20px 20px;">
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" id="sus-btnConfirmarRealizada" class="btn rounded-pill px-4 fw-semibold"
                        style="background:linear-gradient(135deg,#198754 0%,#037e83 100%);border:none;color:#fff;">
                        <i class="ri-check-double-line me-1"></i>Confirmar Realizada
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal-detalle-sesion')

    {{-- Modal: Agendar sesión pendiente --}}
    @if ($sesionesPendientes > 0)
        <div class="modal fade" id="modalPendienteSesion" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-bottom py-3 px-4"
                        style="background:linear-gradient(135deg,#405189,#5c6ac4);">
                        <h5 class="modal-title text-white fw-bold">
                            <i class="ri-calendar-check-line me-2"></i>Agregar Sesión
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" id="pend-sesion-fecha-selected">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i
                                        class="ri-calendar-event-line me-1 text-primary"></i>Fecha</label>
                                <input type="text" id="pend-sesion-fecha-display" class="form-control rounded-3"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ri-time-line me-1 text-primary"></i>Hora
                                    de inicio <span class="text-danger">*</span></label>
                                <select id="pend-sesion-hora-inicio" class="form-select rounded-3">
                                    <option value="">-- Seleccionar hora --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ri-baby-line me-1 text-info"></i>Niño(a)
                                    <span class="text-danger">*</span></label>
                                <select id="pend-sesion-hijo" class="form-select rounded-3">
                                    <option value="">-- Seleccionar niño --</option>
                                    @foreach ($hijos as $hijo)
                                        <option value="{{ $hijo->id }}">{{ $hijo->persona->nombres }}
                                            {{ $hijo->persona->apellido_paterno }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i
                                        class="ri-service-line me-1 text-warning"></i>Servicio <span
                                        class="text-danger">*</span></label>
                                <select id="pend-sesion-servicio" class="form-select rounded-3">
                                    <option value="">-- Seleccionar servicio --</option>
                                    @foreach ($serviciosDePlan as $ps)
                                        <option value="{{ $ps->id }}">{{ $ps->servicio->nombre }}
                                            ({{ $ps->cantidad_sesiones }} ses.)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold"><i
                                        class="ri-user-settings-line me-1 text-success"></i>Fisioterapeuta <span
                                        class="text-danger">*</span></label>
                                <select id="pend-sesion-trabajador" class="form-select rounded-3">
                                    <option value="">-- Seleccionar fisioterapeuta --</option>
                                    @foreach ($trabajadores as $t)
                                        <option value="{{ $t->id }}">{{ $t->persona->nombres }}
                                            {{ $t->persona->apellido_paterno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($suscripcion->tarifa === 'especial')
                                <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.75rem;padding:.45rem 1rem;">
                                    <i class="ri-star-fill me-1"></i>Tarifa Especial
                                </span>
                            @else
                                <span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:.75rem;padding:.45rem 1rem;">
                                    <i class="ri-sun-line me-1"></i>Tarifa Normal
                                </span>
                            @endif
                        </div>
                        <div id="pend-disponibilidad-msg" class="mt-3" style="display:none;"></div>
                    </div>
                    <div class="modal-footer border-top py-3 px-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" id="btnGuardarSesionPendiente" class="btn btn-primary rounded-pill px-4">
                            <i class="ri-save-3-line me-1"></i>Agregar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== MODAL RECIBOS NUEVOS ===== --}}
    @if (session('recibos_nuevos') && count(session('recibos_nuevos')))
        @php $recibosNuevos = session('recibos_nuevos'); @endphp
        <div class="modal fade" id="modalRecibosNuevos" tabindex="-1" aria-labelledby="modalRecibosNuevosLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                    <div class="modal-header border-0 py-4 px-4"
                        style="background:linear-gradient(135deg,#1a2d4c,#0d9488);">
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="modalRecibosNuevosLabel">
                                <i class="ri-checkbox-circle-fill me-2"></i>¡Pago registrado!
                            </h5>
                            <p class="text-white-50 small mb-0 mt-1">
                                Se generaron {{ count($recibosNuevos) }} recibo{{ count($recibosNuevos) > 1 ? 's' : '' }} de pago
                            </p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <p class="text-muted small mb-3">
                            <i class="ri-information-line me-1"></i>
                            Descarga e imprime el recibo para entregárselo al cliente.
                        </p>
                        <div class="d-flex flex-column gap-2">
                            @foreach ($recibosNuevos as $rec)
                                <a href="{{ route('admin.suscripciones.reciboPdf', $rec['id']) }}"
                                    target="_blank"
                                    class="btn rounded-3 fw-semibold d-flex align-items-center justify-content-between px-4 py-3"
                                    style="background:linear-gradient(135deg,#1a2d4c,#0d9488);color:#fff;">
                                    <span>
                                        <i class="ri-file-pdf-line me-2 fs-5"></i>
                                        Recibo N° <strong>{{ $rec['recibo'] }}</strong>
                                    </span>
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="small opacity-75">Descargar / Imprimir</span>
                                        <i class="ri-download-2-line fs-5"></i>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('modalRecibosNuevos'));
                modal.show();
            });
        </script>
    @endif
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
        }

        .card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        #calendar {
            max-width: 100%;
            height: 600px;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .fc-event {
            cursor: pointer;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.85rem;
            border: none;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table thead th {
            font-weight: 600 !important;
            color: #495057 !important;
            background: #f8f9fa !important;
            border: none !important;
            padding: 15px 10px !important;
            font-size: 0.9rem;
        }

        .table tbody td {
            padding: 15px 10px !important;
            vertical-align: middle !important;
            border-color: #f1f5f9 !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .table-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .table-info {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            transform: scale(1.1);
        }

        /* Session action cards */
        .border-3 {
            border-width: 3px !important;
        }

        .sesion-accion-card {
            transition: box-shadow 0.2s;
        }

        .sesion-accion-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
        }

        /* Badge opacity override for Bootstrap bg-opacity */
        .bg-opacity-15 {
            --bs-bg-opacity: 0.15;
        }

        @media (max-width: 768px) {
            .page-title-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px;
            }

            .fc .fc-toolbar {
                flex-direction: column;
                gap: 10px;
            }

            #calendar {
                height: 400px;
            }

            #calendar-pendientes {
                height: 480px;
            }
        }

        /* Calendario pendientes */
        #calendar-pendientes {
            height: 560px;
        }

        .pend-sesion-card {
            border-left: 4px solid #405189 !important;
        }

        /* Tarifa especial events */
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
    </style>
@endpush

@push('script')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'es',
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
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
                    // Force-apply per-event colors (overrides any theme CSS)
                    var bg = info.event.backgroundColor;
                    var color = info.event.textColor;
                    info.el.style.setProperty('background-color', bg, 'important');
                    info.el.style.setProperty('border-color', bg, 'important');
                    info.el.style.setProperty('color', color, 'important');
                    // Also target inner elements used in week/day views
                    var mainEl = info.el.querySelector('.fc-event-main');
                    if (mainEl) mainEl.style.setProperty('color', color, 'important');
                    var dotEl = info.el.querySelector('.fc-daygrid-event-dot');
                    if (dotEl) dotEl.style.setProperty('border-color', bg, 'important');

                    // Tarifa info
                    var props = info.event.extendedProps;
                    var tarifaLabel = props.tarifa === 'especial' ? '⭐ Especial' : '☀ Normal';
                    info.el.setAttribute('data-bs-toggle', 'tooltip');
                    info.el.setAttribute('title', info.event.title + '\n🏷 Tarifa: ' + tarifaLabel);

                    // Visual differentiation for especial tarifa
                    if (props.tarifa === 'especial') {
                        info.el.classList.add('fc-event-tarifa-especial');
                        var titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector('.fc-event-main-frame');
                        if (titleEl && !titleEl.dataset.tarifaMarked) {
                            var star = document.createElement('span');
                            star.innerHTML = '⭐ ';
                            star.style.cssText = 'margin-right:2px;filter:drop-shadow(0 0 1px rgba(0,0,0,.3));';
                            titleEl.prepend(star);
                            titleEl.dataset.tarifaMarked = '1';
                        }
                    }
                }
            });
            calendar.render();

            // Manejo del modal de reprogramación
            const modal = document.getElementById('reprogramarModal');
            if (modal) {
                const form = document.getElementById('formReprogramar');
                const sesionIdInput = document.getElementById('sesion_id');
                const fechaInput = document.getElementById('nueva_fecha');
                const horaSelect = document.getElementById('nueva_hora');
                const motivoTextarea = document.getElementById('motivo');
                const cancelarPrimeroInput = document.getElementById('cancelar_primero');
                const cancelarAlert = document.getElementById('reprogramar-cancelar-alert');
                const modalTitle = document.getElementById('reprogramarModalTitle');
                const horasOcupadasMsg = document.getElementById('horas-ocupadas-msg');
                const trabajadorSelect = document.getElementById('modal-trabajador');
                let currentSesionId = null;

                // Fetch horas ocupadas y marcar opciones del select
                function fetchYMarcarOcupadas() {
                    const trabajadorId = trabajadorSelect.value;
                    const fecha = fechaInput.value;
                    // Reset options first
                    Array.from(horaSelect.options).forEach(function(opt) {
                        if (opt.value) {
                            opt.disabled = false;
                            opt.text = opt.value;
                        }
                    });
                    horasOcupadasMsg.classList.add('d-none');
                    if (!trabajadorId || !fecha) return;

                    $.ajax({
                        url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            fecha: fecha,
                            trabajador_id: trabajadorId,
                            sesion_id: currentSesionId
                        },
                        success: function(resp) {
                            const ocupadas = resp.horas_no_disponibles || [];
                            let hayOcupadas = false;
                            Array.from(horaSelect.options).forEach(function(opt) {
                                if (!opt.value) return;
                                const isOcupada = ocupadas.includes(opt.value);
                                opt.disabled = isOcupada;
                                opt.text = isOcupada ? opt.value + ' (Ocupado)' : opt.value;
                                if (isOcupada) hayOcupadas = true;
                            });
                            horasOcupadasMsg.classList.toggle('d-none', !hayOcupadas);
                            // Si la hora ya seleccionada quedó ocupada, limpiar
                            if (horaSelect.value) {
                                const sel = horaSelect.options[horaSelect.selectedIndex];
                                if (sel && sel.disabled) horaSelect.value = '';
                            }
                        }
                    });
                }

                trabajadorSelect.addEventListener('change', fetchYMarcarOcupadas);
                fechaInput.addEventListener('change', fetchYMarcarOcupadas);

                function abrirReprogramarModal(btn, cancelarPrimero) {
                    const sesionId = btn.dataset.id;
                    const trabajadorOriginal = btn.dataset.trabajador || '';
                    currentSesionId = sesionId;

                    sesionIdInput.value = sesionId;
                    // Pre-seleccionar el fisioterapeuta original
                    trabajadorSelect.value = trabajadorOriginal;
                    // Resetear horas
                    Array.from(horaSelect.options).forEach(function(opt) {
                        if (opt.value) {
                            opt.disabled = false;
                            opt.text = opt.value;
                        }
                    });
                    horasOcupadasMsg.classList.add('d-none');
                    fechaInput.value = '';
                    horaSelect.value = '';
                    motivoTextarea.value = '';
                    cancelarPrimeroInput.value = cancelarPrimero ? '1' : '0';

                    if (cancelarPrimero) {
                        cancelarAlert.classList.remove('d-none');
                        modalTitle.innerHTML = '<i class="ri-close-circle-line me-2"></i>Cancelar y Reprogramar';
                    } else {
                        cancelarAlert.classList.add('d-none');
                        modalTitle.innerHTML = '<i class="ri-refresh-line me-2"></i>Reprogramar sesión';
                    }

                    form.action = `{{ url('admin/sesiones') }}/${sesionId}/reprogramar`;
                    new bootstrap.Modal(modal).show();
                }

                // Botones reprogramar directo (desde cancelada)
                document.querySelectorAll('.reprogramar-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        abrirReprogramarModal(this, false);
                    });
                });

                // Botones cancelar (desde programada/confirmada → cancelar + reprogramar)
                document.querySelectorAll('.cancelar-sesion-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        abrirReprogramarModal(this, true);
                    });
                });

                // Exponer para uso desde el modal de detalle del calendario
                window._abrirReprogramarModal = abrirReprogramarModal;

                // Al enviar: validar hora no ocupada; si cancelar_primero=1, PATCH cancelada antes
                form.addEventListener('submit', function(e) {
                    // Validar que la hora seleccionada no esté ocupada
                    if (horaSelect.value) {
                        const sel = horaSelect.options[horaSelect.selectedIndex];
                        if (sel && sel.disabled) {
                            e.preventDefault();
                            alert(
                                'La hora seleccionada está ocupada para este fisioterapeuta. Por favor elija otro horario.'
                            );
                            return;
                        }
                    }
                    if (cancelarPrimeroInput.value === '1') {
                        e.preventDefault();
                        const sesionId = sesionIdInput.value;
                        $.ajax({
                            url: `{{ url('admin/sesiones') }}/${sesionId}/estado`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'PATCH',
                                estado: 'cancelada'
                            },
                            success: function() {
                                cancelarPrimeroInput.value = '0';
                                form.submit();
                            },
                            error: function() {
                                alert('Error al cancelar la sesión. Intente nuevamente.');
                            }
                        });
                    }
                });
            }

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // ========== MODAL VER PAGOS ==========
        let modalPagos = new bootstrap.Modal(document.getElementById('modalPagos'));

        $(document).on('click', '.btn-ver-pagos', function() {
            const suscripcionId = $(this).data('id');
            const suscripcionNombre = $(this).data('nombre');

            $('#modalPagosTitulo').text('Pagos - ' + suscripcionNombre);
            $('#modalPagosBody').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Cargando información de pagos...</p>
        </div>
    `);

            modalPagos.show();

            $.ajax({
                url: '{{ route('admin.suscripciones.detallePagos', ':id') }}'.replace(':id',
                    suscripcionId),
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#modalPagosBody').html(response.html);
                    } else {
                        $('#modalPagosBody').html(`
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>
                        Error al cargar los pagos
                    </div>
                `);
                    }
                },
                error: function() {
                    $('#modalPagosBody').html(`
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    Error de conexión. Intente nuevamente.
                </div>
            `);
                }
            });
        });

        // ========== DETALLE DE SESIÓN DESDE EL CALENDARIO ==========
        window.mostrarDetalleSesion = function(sesionId) {
            const modalEl = document.getElementById('detalleSesionModal');
            if (!modalEl) return;
            const modal = new bootstrap.Modal(modalEl);
            const loader = document.getElementById('detalle-loader');
            const contenido = document.getElementById('detalle-contenido');
            const btnSuscripcion = document.getElementById('btnVerSuscripcion');
            const accionesDiv = document.getElementById('detalle-acciones');

            loader.style.display = 'block';
            contenido.innerHTML = '';
            if (accionesDiv) accionesDiv.innerHTML = '';
            if (btnSuscripcion) btnSuscripcion.href = '#';
            modal.show();

            fetch(`/admin/sesiones/${sesionId}/detalle`)
                .then(r => r.json())
                .then(data => {
                    loader.style.display = 'none';

                    const estadoColors = {
                        programada: '#f59e0b',
                        confirmada: '#198754',
                        realizada: '#0d6efd',
                        cancelada: '#dc3545',
                        reprogramada: '#6c757d'
                    };
                    const estadoTextColors = {
                        programada: '#212529'
                    };
                    const bg = estadoColors[data.estado] || '#6c757d';
                    const fg = estadoTextColors[data.estado] || '#ffffff';

                    contenido.innerHTML = `
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card border-0 rounded-4" style="background:#f8fafc;">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-muted small fw-bold text-uppercase" style="letter-spacing:.8px;">Sesión #${data.n_sesion}</div>
                                            <div class="small text-muted mt-1">
                                                <i class="ri-calendar-line me-1"></i>${data.fecha}
                                                &nbsp;•&nbsp;
                                                <i class="ri-time-line me-1"></i>${data.hora_inicio} - ${data.hora_fin}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                                style="background-color:${bg};color:${fg};font-size:.85rem;">
                                                ${data.estado.charAt(0).toUpperCase() + data.estado.slice(1)}
                                            </span>
                                            ${data.tarifa === 'especial'
                                                ? '<span class="badge rounded-pill px-3 py-2 fw-semibold" style="background:#fef3c7;color:#b45309;border:1px solid #fcd34d;font-size:.75rem;"><i class="ri-star-fill me-1"></i>Especial</span>'
                                                : '<span class="badge rounded-pill px-3 py-2 fw-semibold" style="background:#f0fdf4;color:#0d9488;border:1px solid #5eead4;font-size:.75rem;"><i class="ri-sun-line me-1"></i>Normal</span>'
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3">
                                        <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;"><i class="ri-user-settings-line me-1"></i>Fisioterapeuta</div>
                                        <div class="fw-semibold">${data.trabajador || '-'}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3">
                                        <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;"><i class="ri-service-line me-1"></i>Servicio</div>
                                        <div class="fw-semibold">${data.servicio || '-'}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3">
                                        <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;"><i class="ri-user-star-line me-1"></i>Cliente</div>
                                        <div class="fw-semibold">${data.cliente_nombre}</div>
                                        <small class="text-muted">${data.cliente_carnet || 'Sin CI'}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3">
                                        <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;"><i class="ri-baby-line me-1"></i>Niño/a</div>
                                        <div class="fw-semibold">${data.hijo_nombre || '-'}</div>
                                        <small class="text-muted">${data.hijo_carnet || ''}</small>
                                    </div>
                                </div>
                            </div>
                            ${data.informe ? `
                                        <div class="col-12">
                                            <div class="card border-0 shadow-sm rounded-4">
                                                <div class="card-body p-3">
                                                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing:.8px;"><i class="ri-file-text-line me-1"></i>Informe</div>
                                                    <p class="mb-0 small text-muted" style="white-space:pre-wrap;">${data.informe}</p>
                                                </div>
                                            </div>
                                        </div>` : ''}
                            ${data.reprogramar_id ? `
                                        <div class="col-12">
                                            <div class="alert alert-warning py-2 px-3 rounded-3 mb-0 small">
                                                <i class="ri-refresh-line me-1"></i>Reprogramación de la Sesión #${data.reprogramar_n_sesion}
                                            </div>
                                        </div>` : ''}
                            ${data.tiene_reprogramaciones ? `
                                        <div class="col-12">
                                            <div class="alert alert-info py-2 px-3 rounded-3 mb-0 small">
                                                <i class="ri-arrow-go-forward-line me-1"></i>Esta sesión fue reprogramada
                                            </div>
                                        </div>` : ''}
                        </div>`;

                    // --- Botones de acción según estado ---
                    if (accionesDiv) {
                        let html = '';
                        const sid = data.id;
                        const trab = data.trabajadore_id || '';
                        const fecha = data.fecha_raw || '';
                        const hora = data.hora_inicio_raw || '';

                        // Botón Editar solo para programada y confirmada
                        if (['programada', 'confirmada'].includes(data.estado)) {
                            html += `<button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                onclick="window._editarCal(${data.id},'${trab}','${fecha}','${hora}')">
                                <i class="ri-edit-line me-1"></i>Editar</button>`;
                        }

                        // Determinar si la sesión ya pasó (fecha+hora <= ahora)
                        const sesionYaPaso = fecha && hora ?
                            new Date(fecha + 'T' + hora + ':00') <= new Date() :
                            false;

                        if (data.estado === 'programada') {
                            if (data.cliente_celular) {
                                const msgWa = encodeURIComponent(
                                    `Estimado/a ${data.cliente_nombre}, le recordamos que tiene una sesión el ${data.fecha} a las ${data.hora_inicio} hrs. Por favor confirme su asistencia. ¡Gracias!`
                                );
                                const waNum = (data.cliente_celular || '').replace(/\D/g, '');
                                html += `<a href="https://wa.me/${waNum}?text=${msgWa}" target="_blank"
                                    class="btn btn-sm btn-success rounded-pill px-3">
                                    <i class="ri-whatsapp-line me-1"></i>Recordar</a>`;
                            }
                            html += `<button class="btn btn-sm btn-info rounded-pill px-3"
                                onclick="window._cambiarEstadoCal(${sid},'confirmada')">
                                <i class="ri-check-line me-1"></i>Confirmar</button>`;
                            html += `<button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                onclick="window._cancelarCal(${sid},'${trab}','${fecha}','${hora}')">
                                <i class="ri-close-circle-line me-1"></i>Cancelar</button>`;

                        } else if (data.estado === 'confirmada') {
                            const realizadaDisabled = sesionYaPaso ? '' :
                                'disabled title="La sesión aún no ha llegado su fecha y hora"';
                            html += `<button class="btn btn-sm btn-success rounded-pill px-3" ${realizadaDisabled}
                                onclick="window._cambiarEstadoCal(${sid},'realizada')">
                                <i class="ri-check-double-line me-1"></i>Realizada</button>`;
                            html += `<button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                onclick="window._cancelarCal(${sid},'${trab}','${fecha}','${hora}')">
                                <i class="ri-close-circle-line me-1"></i>Cancelar</button>`;

                        } else if (data.estado === 'cancelada' && data.puede_reprogramar_directo) {
                            html += `<button class="btn btn-sm btn-warning rounded-pill px-3"
                                onclick="window._reprogramarCal(${sid},'${trab}','${fecha}','${hora}')">
                                <i class="ri-refresh-line me-1"></i>Reprogramar</button>`;
                        }
                        accionesDiv.innerHTML = html;
                    }

                    if (btnSuscripcion && data.suscripcion_id) {
                        btnSuscripcion.href = `/admin/suscripciones/ver/${data.suscripcion_id}`;
                    }
                })
                .catch(() => {
                    loader.style.display = 'none';
                    contenido.innerHTML =
                        '<div class="alert alert-danger">Error al cargar los datos de la sesión.</div>';
                });
        };

        // Cambiar estado vía AJAX y recargar
        window._cambiarEstadoCal = function(sesionId, estado) {
            if (!confirm('¿Confirma el cambio de estado?')) return;
            $.ajax({
                url: `{{ url('admin/sesiones') }}/${sesionId}/estado`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    estado: estado
                },
                success: function() {
                    bootstrap.Modal.getInstance(document.getElementById('detalleSesionModal'))?.hide();
                    location.reload();
                },
                error: function() {
                    alert('Error al cambiar el estado. Intente nuevamente.');
                }
            });
        };

        // Abrir modal reprogramar desde el calendario (cerrar detalle primero)
        function _openReprogramarFromCal(sesionId, trabajadorId, fecha, hora, cancelarPrimero) {
            const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleSesionModal'));
            const syntheticBtn = {
                dataset: {
                    id: String(sesionId),
                    trabajador: String(trabajadorId),
                    fecha: fecha,
                    hora: hora
                }
            };
            if (detalleModal) {
                document.getElementById('detalleSesionModal').addEventListener('hidden.bs.modal', function onHidden() {
                    document.getElementById('detalleSesionModal').removeEventListener('hidden.bs.modal', onHidden);
                    if (window._abrirReprogramarModal) window._abrirReprogramarModal(syntheticBtn, cancelarPrimero);
                });
                detalleModal.hide();
            } else {
                if (window._abrirReprogramarModal) window._abrirReprogramarModal(syntheticBtn, cancelarPrimero);
            }
        }
        window._cancelarCal = (sid, trab, fecha, hora) => _openReprogramarFromCal(sid, trab, fecha, hora, true);
        window._reprogramarCal = (sid, trab, fecha, hora) => _openReprogramarFromCal(sid, trab, fecha, hora, false);

        // ========== MODAL EDITAR SESIÓN ==========
        const editarModalEl = document.getElementById('editarSesionModal');
        if (editarModalEl) {
            const editForm = document.getElementById('formEditarSesion');
            const editTrabajador = document.getElementById('edit-trabajador');
            const editFecha = document.getElementById('edit-fecha');
            const editHora = document.getElementById('edit-hora');
            const editOcupadasMsg = document.getElementById('edit-horas-ocupadas-msg');
            let editCurrentSesionId = null;

            function fetchEditOcupadas() {
                const trabajadorId = editTrabajador.value;
                const fecha = editFecha.value;
                resetEditHoras();
                if (!trabajadorId || !fecha) return;
                editHora.disabled = true;
                editHora.options[0].text = 'Verificando disponibilidad...';
                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        fecha: fecha,
                        trabajador_id: trabajadorId,
                        sesion_id: editCurrentSesionId
                    },
                    success: function(resp) {
                        editHora.options[0].text = '-- Seleccionar hora --';
                        aplicarOcupadasEdit(resp, null);
                    },
                    error: function() {
                        editHora.options[0].text = '-- Seleccionar hora --';
                        editHora.disabled = false;
                    }
                });
            }

            editTrabajador.addEventListener('change', fetchEditOcupadas);
            editFecha.addEventListener('change', fetchEditOcupadas);

            function resetEditHoras() {
                Array.from(editHora.options).forEach(function(opt) {
                    if (opt.value) { opt.disabled = false; opt.text = opt.value; }
                });
                editOcupadasMsg.classList.add('d-none');
            }

            function aplicarOcupadasEdit(resp, hora) {
                const ocupadas = resp.horas_no_disponibles || [];
                let hay = false;
                Array.from(editHora.options).forEach(function(opt) {
                    if (!opt.value) return;
                    const isOc = ocupadas.includes(opt.value);
                    opt.disabled = isOc;
                    opt.text = isOc ? opt.value + ' (Ocupado)' : opt.value;
                    if (isOc) hay = true;
                });
                editOcupadasMsg.classList.toggle('d-none', !hay);
                if (hora) {
                    const optHora = Array.from(editHora.options).find(o => o.value === hora);
                    if (optHora && !optHora.disabled) editHora.value = hora;
                    else editHora.value = '';
                } else if (editHora.value) {
                    const sel = editHora.options[editHora.selectedIndex];
                    if (sel && sel.disabled) editHora.value = '';
                }
                editHora.disabled = false;
            }

            function abrirEditarModal(sesionId, trabajadorId, fecha, hora) {
                editCurrentSesionId = sesionId;
                editTrabajador.value = trabajadorId || '';
                editFecha.value = fecha || '';
                editHora.value = '';
                resetEditHoras();
                editForm.action = `{{ url('admin/sesiones') }}/${sesionId}/editar`;

                if (trabajadorId && fecha) {
                    // Deshabilitar select y mostrar indicador de carga antes de mostrar el modal
                    editHora.disabled = true;
                    editHora.options[0].text = 'Verificando disponibilidad...';
                    new bootstrap.Modal(editarModalEl).show();
                    $.ajax({
                        url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            fecha: fecha,
                            trabajador_id: trabajadorId,
                            sesion_id: sesionId
                        },
                        success: function(resp) {
                            editHora.options[0].text = '-- Seleccionar hora --';
                            aplicarOcupadasEdit(resp, hora);
                        },
                        error: function() {
                            editHora.options[0].text = '-- Seleccionar hora --';
                            editHora.disabled = false;
                            if (hora) editHora.value = hora;
                        }
                    });
                } else {
                    if (hora) editHora.value = hora;
                    new bootstrap.Modal(editarModalEl).show();
                }
            }

            window._abrirEditarModal = abrirEditarModal;

            editForm.addEventListener('submit', function(e) {
                if (editHora.value) {
                    const sel = editHora.options[editHora.selectedIndex];
                    if (sel && sel.disabled) {
                        e.preventDefault();
                        alert('La hora seleccionada está ocupada. Por favor elija otro horario.');
                    }
                }
            });

            // Botones en la lista de sesiones
            document.querySelectorAll('.editar-sesion-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    abrirEditarModal(this.dataset.id, this.dataset.trabajador, this.dataset.fecha, this
                        .dataset.hora);
                });
            });
        }

        // Helper para abrir editar desde el calendario
        window._editarCal = function(sesionId, trabajadorId, fecha, hora) {
            const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleSesionModal'));
            const open = function() {
                if (window._abrirEditarModal) window._abrirEditarModal(sesionId, trabajadorId, fecha, hora);
            };
            if (detalleModal) {
                document.getElementById('detalleSesionModal').addEventListener('hidden.bs.modal', function onH() {
                    document.getElementById('detalleSesionModal').removeEventListener('hidden.bs.modal', onH);
                    open();
                });
                detalleModal.hide();
            } else {
                open();
            }
        };

        // =====================================================================
        // CALENDARIO SESIONES PENDIENTES
        // =====================================================================
        @if ($sesionesPendientes > 0)
            (function() {
                var token = '{{ csrf_token() }}';
                var suscripcionId = {{ $suscripcion->id }};
                var maxPendientes = {{ $sesionesPendientes }};
                var pendientesData = [];
                var pendCalendar = null;
                var pendFiltroTrabajadorId = null;
                var pendCounter = 0;

                // Valores por defecto pre-cargados desde la suscripción
                @php
                    $ultimaSesion = $suscripcion->sesiones->last();
                    $defaultHijoId = $ultimaSesion?->hijo_id ?? ($hijos->first()?->id ?? '');
                    $defaultServicioId = $ultimaSesion?->planes_servicio_id ?? ($serviciosDePlan->first()?->id ?? '');
                @endphp
                var defaultHijoId = '{{ $defaultHijoId }}';
                var defaultServicioId = '{{ $defaultServicioId }}';
                var suscripcionTarifa = '{{ $suscripcion->tarifa ?? "normal" }}';

                // Helper: realizar POST con form-data (compatible con el endpoint horasOcupadas)
                function checkDisponibilidad(fecha, trabajadorId, sesionId) {
                    var params = new URLSearchParams();
                    params.append('_token', token);
                    params.append('fecha', fecha);
                    params.append('trabajador_id', trabajadorId);
                    if (sesionId) params.append('sesion_id', sesionId);
                    return fetch('{{ route('admin.suscripciones.horasOcupadas') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: params.toString()
                    }).then(function(r) {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    });
                }

                // Fecha/hora actual en Bolivia (America/La_Paz)
                function getNowBolivia() {
                    return new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
                }
                function getTodayStr() {
                    var n = getNowBolivia();
                    return n.getFullYear() + '-' +
                        String(n.getMonth() + 1).padStart(2, '0') + '-' +
                        String(n.getDate()).padStart(2, '0');
                }

                // Horas disponibles 07:00 – 19:00 (cada 30 min), opcionalmente filtradas por fecha
                function buildHorasOptions(fecha) {
                    var opts = '';
                    var hoy = getTodayStr();
                    var now = getNowBolivia();
                    var nowMinutes = now.getHours() * 60 + now.getMinutes();
                    for (var h = 7; h <= 19; h++) {
                        var slots = [(h < 10 ? '0' : '') + h + ':00'];
                        if (h < 19) slots.push((h < 10 ? '0' : '') + h + ':30');
                        slots.forEach(function(s) {
                            if (fecha === hoy) {
                                var slotMin = parseInt(s.split(':')[0]) * 60 + parseInt(s.split(':')[1]);
                                if (slotMin <= nowMinutes) return; // omitir hora pasada
                            }
                            opts += '<option value="' + s + '">' + s + '</option>';
                        });
                    }
                    return opts;
                }

                // ── Inicializar FullCalendar ──────────────────────────────────────
                var calEl = document.getElementById('calendar-pendientes');
                if (!calEl) return;

                pendCalendar = new FullCalendar.Calendar(calEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    slotMinTime: '07:00:00',
                    slotMaxTime: '20:00:00',
                    allDaySlot: false,
                    nowIndicator: true,
                    selectable: false,
                    editable: false,
                    validRange: { start: getTodayStr() },
                    eventSources: [{
                            // Sesiones de OTRAS suscripciones (rojas = ocupado ajeno)
                            id: 'pendOcupados',
                            url: '{{ route('admin.suscripciones.calendarioOcupados') }}',
                            method: 'GET',
                            extraParams: function() {
                                return {
                                    trabajador_id: pendFiltroTrabajadorId || '',
                                    suscripcion_id: suscripcionId
                                };
                            },
                            color: '#dc3545',
                            textColor: '#ffffff'
                        },
                        {
                            // Sesiones YA agendadas de ESTA suscripción (con color de estado)
                            id: 'pendPropias',
                            events: @json($eventos)
                        },
                        {
                            id: 'pendNuevas',
                            events: []
                        }
                    ],
                    dateClick: function(info) {
                        var fechaClick = info.dateStr.substring(0, 10);
                        var hoy = getTodayStr();
                        if (fechaClick < hoy) {
                            mostrarToastPend('warning', 'No se puede agendar en fechas pasadas.');
                            return;
                        }
                        if (pendientesData.length >= maxPendientes) {
                            mostrarToastPend('warning', 'Ya tienes ' + maxPendientes +
                                ' sesión(es) programada(s), que es el máximo pendiente.');
                            return;
                        }
                        var hora = info.dateStr.includes('T') ? info.dateStr.split('T')[1].substring(0, 5) : '';
                        // Si es hoy y la hora ya pasó, no pre-seleccionar esa hora
                        if (fechaClick === hoy && hora) {
                            var now = getNowBolivia();
                            var slotMin = parseInt(hora.split(':')[0]) * 60 + parseInt(hora.split(':')[1]);
                            if (slotMin <= now.getHours() * 60 + now.getMinutes()) hora = '';
                        }
                        abrirModalPendiente(fechaClick, hora);
                    },
                    eventClick: function(info) {
                        var srcId = info.event.source ? info.event.source.id : '';
                        if (srcId === 'pendNuevas') {
                            if (confirm('¿Eliminar esta sesión del calendario?')) {
                                eliminarPendiente(info.event.id);
                            }
                        }
                        // pendPropias y pendOcupados: ignorar clic
                    },
                    eventDidMount: function(info) {
                        var bg = info.event.backgroundColor;
                        var tc = info.event.textColor || '#ffffff';
                        info.el.style.setProperty('background-color', bg, 'important');
                        info.el.style.setProperty('border-color', bg, 'important');
                        info.el.style.setProperty('color', tc, 'important');
                        var main = info.el.querySelector('.fc-event-main');
                        if (main) main.style.setProperty('color', tc, 'important');
                        var srcId = info.event.source ? info.event.source.id : '';
                        if (srcId === 'pendPropias' || srcId === 'pendOcupados') {
                            info.el.style.cursor = 'default';
                        }
                        // Tarifa info for pendPropias events
                        var props = info.event.extendedProps;
                        if (props && props.tarifa) {
                            var tarifaLabel = props.tarifa === 'especial' ? '⭐ Especial' : '☀ Normal';
                            var existingTitle = info.el.getAttribute('title') || info.event.title;
                            info.el.setAttribute('title', existingTitle + '\n🏷 Tarifa: ' + tarifaLabel);
                            if (props.tarifa === 'especial') {
                                info.el.classList.add('fc-event-tarifa-especial');
                            }
                        }
                    }
                });
                pendCalendar.render();

                // ── Filtro fisioterapeuta ────────────────────────────────────────
                document.getElementById('pend-filtro-fisiote').addEventListener('change', function() {
                    pendFiltroTrabajadorId = this.value || null;
                    var src = pendCalendar.getEventSourceById('pendOcupados');
                    if (src) src.refetch();
                });

                // ── Abrir modal ──────────────────────────────────────────────────
                function abrirModalPendiente(fecha, hora) {
                    document.getElementById('pend-sesion-fecha-selected').value = fecha;
                    var parts = fecha.split('-');
                    document.getElementById('pend-sesion-fecha-display').value = parts[2] + '/' + parts[1] + '/' +
                        parts[0];

                    var $h = document.getElementById('pend-sesion-hora-inicio');
                    $h.innerHTML = '<option value="">-- Seleccionar hora --</option>' + buildHorasOptions(fecha);
                    if (hora) {
                        $h.value = hora;
                        actualizarHoraFin(hora);
                    }

                    // Pre-seleccionar niño y servicio del plan
                    if (defaultHijoId) document.getElementById('pend-sesion-hijo').value = defaultHijoId;
                    if (defaultServicioId) document.getElementById('pend-sesion-servicio').value = defaultServicioId;

                    if (pendFiltroTrabajadorId) {
                        document.getElementById('pend-sesion-trabajador').value = pendFiltroTrabajadorId;
                    } else {
                        document.getElementById('pend-sesion-trabajador').value = '';
                    }

                    document.getElementById('pend-disponibilidad-msg').style.display = 'none';
                    document.getElementById('pend-disponibilidad-msg').innerHTML = '';
                    document.getElementById('btnGuardarSesionPendiente').disabled = false;
                    document.getElementById('btnGuardarSesionPendiente').innerHTML =
                        '<i class="ri-save-3-line me-1"></i>Agregar Sesión';

                    new bootstrap.Modal(document.getElementById('modalPendienteSesion')).show();
                }

                function actualizarHoraFin(hi) {
                    if (!hi) return '';
                    var p = hi.split(':');
                    return (parseInt(p[0]) + 1 < 10 ? '0' : '') + (parseInt(p[0]) + 1) + ':' + p[1];
                }

                // Hora de inicio → calcular fin
                document.getElementById('pend-sesion-hora-inicio').addEventListener('change', function() {
                    document.getElementById('pend-disponibilidad-msg').style.display = 'none';
                });

                // Trabajador cambia → verificar disponibilidad en tiempo real
                document.getElementById('pend-sesion-trabajador').addEventListener('change', function() {
                    var trabajadorId = this.value;
                    var fecha = document.getElementById('pend-sesion-fecha-selected').value;
                    var horaInicio = document.getElementById('pend-sesion-hora-inicio').value;
                    var msg = document.getElementById('pend-disponibilidad-msg');
                    msg.style.display = 'none';
                    msg.innerHTML = '';
                    if (!trabajadorId || !fecha || !horaInicio) return;

                    msg.innerHTML =
                        '<div class="text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Verificando...</div>';
                    msg.style.display = 'block';

                    checkDisponibilidad(fecha, trabajadorId, null).then(function(res) {
                        var ocupadas = res.horas_no_disponibles || [];
                        if (ocupadas.includes(horaInicio)) {
                            msg.innerHTML =
                                '<div class="alert alert-danger py-2 small mb-0"><i class="ri-forbid-line me-1"></i>Este fisioterapeuta ya tiene sesión a las <strong>' +
                                horaInicio + '</strong> ese día.</div>';
                        } else {
                            msg.innerHTML =
                                '<div class="alert alert-success py-2 small mb-0"><i class="ri-check-line me-1"></i>Horario disponible.</div>';
                        }
                        msg.style.display = 'block';
                    }).catch(function() {
                        msg.style.display = 'none';
                    });
                });

                // ── Guardar sesión del modal ─────────────────────────────────────
                document.getElementById('btnGuardarSesionPendiente').addEventListener('click', function() {
                    var fecha = document.getElementById('pend-sesion-fecha-selected').value;
                    var hijoId = document.getElementById('pend-sesion-hijo').value;
                    var servicioId = document.getElementById('pend-sesion-servicio').value;
                    var trabId = document.getElementById('pend-sesion-trabajador').value;
                    var horaInicio = document.getElementById('pend-sesion-hora-inicio').value;

                    if (!fecha || !hijoId || !servicioId || !trabId || !horaInicio) {
                        mostrarToastPend('warning', 'Completa todos los campos requeridos.');
                        return;
                    }

                    var horaFin = actualizarHoraFin(horaInicio);
                    var btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Verificando...';
                    var msg = document.getElementById('pend-disponibilidad-msg');

                    checkDisponibilidad(fecha, trabId, null).then(function(res) {
                        var ocupadas = res.horas_no_disponibles || [];
                        if (ocupadas.includes(horaInicio)) {
                            msg.innerHTML =
                                '<div class="alert alert-danger py-2 small mb-0"><i class="ri-forbid-line me-1"></i>Horario no disponible. Elige otra hora o fisioterapeuta.</div>';
                            msg.style.display = 'block';
                            btn.disabled = false;
                            btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Agregar Sesión';
                            return;
                        }
                        // Conflicto con sesiones ya añadidas en este formulario
                        var conflicto = pendientesData.some(function(s) {
                            if (String(s.trabajadore_id) !== String(trabId) || s.fecha !==
                                fecha) return false;
                            return horaInicio < s.hora_fin && s.hora_inicio < horaFin;
                        });
                        if (conflicto) {
                            msg.innerHTML =
                                '<div class="alert alert-danger py-2 small mb-0"><i class="ri-forbid-line me-1"></i>Ya agendaste una sesión para ese fisioterapeuta en ese horario.</div>';
                            msg.style.display = 'block';
                            btn.disabled = false;
                            btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Agregar Sesión';
                            return;
                        }

                        // Todo OK → agregar
                        var hijoText = document.getElementById('pend-sesion-hijo').options[document
                            .getElementById('pend-sesion-hijo').selectedIndex].text;
                        var trabText = document.getElementById('pend-sesion-trabajador').options[
                            document.getElementById('pend-sesion-trabajador').selectedIndex].text;
                        var servText = document.getElementById('pend-sesion-servicio').options[document
                            .getElementById('pend-sesion-servicio').selectedIndex].text;

                        pendCounter++;
                        var evId = 'pend-' + pendCounter;
                        pendientesData.push({
                            _id: evId,
                            hijo_id: hijoId,
                            planes_servicio_id: servicioId,
                            trabajadore_id: trabId,
                            fecha: fecha,
                            hora_inicio: horaInicio,
                            hora_fin: horaFin,
                            _hijo: hijoText,
                            _trab: trabText,
                            _serv: servText
                        });

                        // Agregar al calendario
                        var src = pendCalendar.getEventSourceById('pendNuevas');
                        pendCalendar.addEvent({
                            id: evId,
                            title: '#' + pendientesData.length + ' ' + hijoText.split(' ')[0],
                            start: fecha + 'T' + horaInicio,
                            end: fecha + 'T' + horaFin,
                            backgroundColor: '#405189',
                            borderColor: '#405189',
                            textColor: '#ffffff',
                            extendedProps: {
                                tarifa: '{{ $suscripcion->tarifa ?? "normal" }}'
                            }
                        }, src);

                        // Cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('modalPendienteSesion'))
                            .hide();
                        actualizarInputsPendientes();
                        actualizarListaPendientes();
                        actualizarProgresoPendientes();
                        mostrarToastPend('success', 'Sesión añadida al calendario.');
                    }).catch(function() {
                        msg.innerHTML =
                            '<div class="alert alert-warning py-2 small mb-0"><i class="ri-alert-line me-1"></i>No se pudo verificar disponibilidad. Intenta de nuevo.</div>';
                        msg.style.display = 'block';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Agregar Sesión';
                    });
                });

                // ── Eliminar sesión ──────────────────────────────────────────────
                function eliminarPendiente(evId) {
                    var idx = pendientesData.findIndex(function(s) {
                        return s._id === evId;
                    });
                    if (idx === -1) return;
                    pendientesData.splice(idx, 1);
                    var ev = pendCalendar.getEventById(evId);
                    if (ev) ev.remove();
                    // Renumerar títulos en calendario
                    pendientesData.forEach(function(s, i) {
                        var e = pendCalendar.getEventById(s._id);
                        if (e) e.setProp('title', '#' + (i + 1) + ' ' + s._hijo.split(' ')[0]);
                    });
                    actualizarInputsPendientes();
                    actualizarListaPendientes();
                    actualizarProgresoPendientes();
                    mostrarToastPend('info', 'Sesión eliminada.');
                }

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.pend-eliminar-btn')) {
                        var evId = e.target.closest('.pend-eliminar-btn').dataset.id;
                        if (confirm('¿Eliminar esta sesión?')) eliminarPendiente(evId);
                    }
                });

                // ── Actualizar inputs hidden ─────────────────────────────────────
                function actualizarInputsPendientes() {
                    var html = '';
                    pendientesData.forEach(function(s, i) {
                        html += '<input type="hidden" name="sesiones[' + i + '][hijo_id]" value="' + s.hijo_id +
                            '">';
                        html += '<input type="hidden" name="sesiones[' + i + '][planes_servicio_id]" value="' +
                            s.planes_servicio_id + '">';
                        html += '<input type="hidden" name="sesiones[' + i + '][trabajadore_id]" value="' + s
                            .trabajadore_id + '">';
                        html += '<input type="hidden" name="sesiones[' + i + '][fecha]" value="' + s.fecha +
                            '">';
                        html += '<input type="hidden" name="sesiones[' + i + '][hora_inicio]" value="' + s
                            .hora_inicio + '">';
                        html += '<input type="hidden" name="sesiones[' + i + '][hora_fin]" value="' + s
                            .hora_fin + '">';
                    });
                    document.getElementById('pend-sesiones-inputs').innerHTML = html;
                    document.getElementById('btnGuardarPendientes').disabled = pendientesData.length === 0;
                }

                // ── Actualizar panel de lista ────────────────────────────────────
                function actualizarListaPendientes() {
                    if (pendientesData.length === 0) {
                        document.getElementById('pend-panel-detalle').style.display = 'none';
                        return;
                    }
                    var html = '';
                    pendientesData.forEach(function(s, i) {
                        var fArr = s.fecha.split('-');
                        var fDisplay = fArr[2] + '/' + fArr[1] + '/' + fArr[0];
                        html += '<div class="col-md-6 col-xl-4">' +
                            '<div class="card border-0 shadow-sm rounded-3 h-100 pend-sesion-card">' +
                            '<div class="card-body p-3">' +
                            '<div class="d-flex align-items-start justify-content-between mb-2">' +
                            '<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(64,81,137,0.12);color:#405189;font-size:11px;">Sesión #' +
                            (i + 1) + '</span>' +
                            '<button type="button" class="btn btn-sm btn-light pend-eliminar-btn" data-id="' + s
                            ._id +
                            '" title="Eliminar"><i class="ri-delete-bin-line text-danger"></i></button>' +
                            '</div>' +
                            '<div class="mb-1" style="font-size:13px;"><i class="ri-calendar-event-line me-1" style="color:#405189;"></i><strong>' +
                            fDisplay + '</strong> <span class="text-muted">' + s.hora_inicio + ' – ' + s
                            .hora_fin + '</span></div>' +
                            '<div class="text-muted text-truncate" style="font-size:12px;"><i class="ri-user-settings-line me-1 text-success"></i>' +
                            s._trab + '</div>' +
                            '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-baby-line me-1 text-info"></i>' +
                            s._hijo + '</div>' +
                            '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-service-line me-1" style="color:#f4a200;"></i>' +
                            s._serv + '</div>' +
                            '<div class="mt-1">' + (suscripcionTarifa === 'especial'
                                ? '<span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.62rem;"><i class="ri-star-fill me-1"></i>Especial</span>'
                                : '<span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:.62rem;"><i class="ri-sun-line me-1"></i>Normal</span>'
                            ) + '</div>' +
                            '</div></div></div>';
                    });
                    document.getElementById('pend-lista-detalle').innerHTML = html;
                    document.getElementById('pend-detalle-count').textContent = pendientesData.length;
                    document.getElementById('pend-panel-detalle').style.display = 'block';
                }

                // ── Actualizar barra de progreso ─────────────────────────────────
                function actualizarProgresoPendientes() {
                    var n = pendientesData.length;
                    document.getElementById('pend-programadas').textContent = n;
                    var pct = maxPendientes > 0 ? Math.min(100, (n / maxPendientes) * 100) : 0;
                    var bar = document.getElementById('pend-progreso');
                    bar.style.width = pct + '%';
                    bar.className = 'progress-bar ' + (pct >= 100 ? 'bg-success' : pct > 50 ? 'bg-warning' :
                        'bg-danger');
                    if (pct >= 100) {
                        document.getElementById('pend-badge-ok').classList.remove('d-none');
                    } else {
                        document.getElementById('pend-badge-ok').classList.add('d-none');
                    }
                }

                // ── Toast helper ─────────────────────────────────────────────────
                function mostrarToastPend(type, msg) {
                    if (typeof showToast === 'function') {
                        showToast(type, msg);
                        return;
                    }
                    alert(msg);
                }
            })();
        @endif

        // ===== MODAL MARCAR REALIZADA =====
        (function () {
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;

            function susShowToast(type, message) {
                var cfg = {
                    success: { icon: 'ri-checkbox-circle-fill', color: '#198754', title: 'Éxito' },
                    error:   { icon: 'ri-close-circle-fill',    color: '#dc3545', title: 'Error' },
                    warning: { icon: 'ri-error-warning-fill',   color: '#f7a600', title: 'Aviso' },
                }[type] || { icon: 'ri-information-fill', color: '#6c757d', title: 'Info' };
                var t = document.createElement('div');
                t.className = 'toast text-white shadow-lg border-0';
                t.style.background = cfg.color;
                t.setAttribute('role', 'alert');
                t.innerHTML = '<div class="toast-header text-white border-0 py-2" style="background:' + cfg.color + 'cc;">' +
                    '<i class="' + cfg.icon + ' me-2"></i><strong class="me-auto">' + cfg.title + '</strong>' +
                    '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button></div>' +
                    '<div class="toast-body">' + message + '</div>';
                var c = document.getElementById('toast-container');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'toast-container';
                    c.className = 'toast-container position-fixed top-0 end-0 p-3';
                    c.style.zIndex = '1090';
                    document.body.appendChild(c);
                }
                c.appendChild(t);
                new bootstrap.Toast(t, { autohide: true, delay: 4000 }).show();
                t.addEventListener('hidden.bs.toast', function () { t.remove(); });
            }

            function susActualizarEstilo() {
                document.querySelectorAll('.sus-asistencia-opcion').forEach(function (lbl) {
                    var radio = lbl.querySelector('input[type=radio]');
                    if (radio.checked) {
                        lbl.style.borderColor = radio.value === '1' ? '#198754' : '#dc3545';
                        lbl.style.background  = radio.value === '1' ? 'rgba(25,135,84,.08)' : 'rgba(220,53,69,.08)';
                    } else {
                        lbl.style.borderColor = '#e9ecef';
                        lbl.style.background  = '#fff';
                    }
                });
            }

            document.querySelectorAll('input[name="sus_asistio_hijo"]').forEach(function (r) {
                r.addEventListener('change', function () {
                    susActualizarEstilo();
                    document.getElementById('sus-asistenciaError').style.display = 'none';
                });
            });

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-marcar-realizada');
                if (!btn) return;
                var sesionId = btn.dataset.id;
                var nSesion  = btn.dataset.nsesion;

                document.getElementById('sus-realizarSesionId').value = sesionId;
                document.getElementById('sus-realizarSubtitle').textContent =
                    nSesion ? '— Sesión #' + nSesion : '';

                document.querySelectorAll('input[name="sus_asistio_hijo"]').forEach(function (r) {
                    r.checked = false;
                });
                susActualizarEstilo();
                document.getElementById('sus-asistenciaError').style.display = 'none';

                new bootstrap.Modal(document.getElementById('modalRealizarSesion')).show();
            });

            document.getElementById('sus-btnConfirmarRealizada').addEventListener('click', function () {
                var sesionId  = document.getElementById('sus-realizarSesionId').value;
                var asistioEl = document.querySelector('input[name="sus_asistio_hijo"]:checked');
                var btn       = this;

                if (!asistioEl) {
                    document.getElementById('sus-asistenciaError').style.display = 'block';
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

                fetch('/admin/sesiones/' + sesionId + '/estado', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({
                        _method:      'PATCH',
                        estado:       'realizada',
                        asistio_hijo: parseInt(asistioEl.value),
                    }),
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        bootstrap.Modal.getInstance(
                            document.getElementById('modalRealizarSesion')
                        ).hide();
                        var msg = asistioEl.value === '1'
                            ? 'Sesión marcada como realizada. El niño/a asistió.'
                            : 'Sesión marcada como realizada. El niño/a no asistió.';
                        susShowToast('success', msg);
                        setTimeout(function () { location.reload(); }, 1800);
                    } else {
                        susShowToast('error', res.msg || 'Error al guardar');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-check-double-line me-1"></i>Confirmar Realizada';
                    }
                })
                .catch(function () {
                    susShowToast('error', 'Error de conexión');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-check-double-line me-1"></i>Confirmar Realizada';
                });
            });
        })();

        // ===== MODAL VENTA DE SESIÓN =====
        (function () {
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            const buscarUrl       = '{{ route('admin.ventas.buscarProducto') }}';
            const reciboUrlBase   = '{{ url('/admin/ventas') }}';

            var vsItems  = [];
            var vsModal  = null;
            var vsDebounceTimer = null;

            function vsGetModal() {
                if (!vsModal) vsModal = new bootstrap.Modal(document.getElementById('modalVentaSesion'));
                return vsModal;
            }

            // ── Recalcular resumen + validar monto ─────────────────────────────
            function vsRecalcular() {
                var subtotal = vsItems.reduce(function (acc, it) { return acc + it.subtotal; }, 0);
                var desc     = parseFloat(document.getElementById('vs-descuento').value) || 0;
                var total    = Math.max(0, subtotal - desc);

                document.getElementById('vs-subtotal-label').textContent  = 'Bs ' + subtotal.toFixed(2);
                document.getElementById('vs-descuento-label').textContent = 'Bs ' + desc.toFixed(2);
                document.getElementById('vs-total-label').textContent     = 'Bs ' + total.toFixed(2);

                vsValidarMonto(total);
            }

            function vsValidarMonto(totalAPagar) {
                var tipoPago   = document.getElementById('vs-tipo-pago').value;
                var errEl      = document.getElementById('vs-monto-error');
                var errTxt     = document.getElementById('vs-monto-error-text');
                var saldoEl    = document.getElementById('vs-saldo-label');
                var btnGuardar = document.getElementById('vs-btn-guardar');
                var errorMsg   = '';
                var montoIngresado = 0;

                if (tipoPago === 'parcial') {
                    var ef = parseFloat(document.getElementById('vs-monto-efectivo').value) || 0;
                    var qr = parseFloat(document.getElementById('vs-monto-qr').value) || 0;
                    montoIngresado = ef + qr;
                    if (ef <= 0 && qr <= 0) {
                        errorMsg = 'Ingrese al menos un monto mayor a 0.';
                    }
                } else {
                    montoIngresado = parseFloat(document.getElementById('vs-monto').value) || 0;
                    if (montoIngresado <= 0 && vsItems.length > 0) {
                        errorMsg = 'El monto debe ser mayor a 0.';
                    }
                }

                if (!errorMsg && montoIngresado > totalAPagar && totalAPagar > 0) {
                    errorMsg = 'El monto (' + montoIngresado.toFixed(2) + ' Bs.) supera el total a pagar de ' + totalAPagar.toFixed(2) + ' Bs.';
                }

                var saldo = Math.max(0, totalAPagar - montoIngresado);
                saldoEl.textContent = 'Bs ' + saldo.toFixed(2);
                saldoEl.className   = saldo <= 0 && montoIngresado > 0 ? 'fw-bold text-success' : 'fw-bold text-danger';

                if (errorMsg) {
                    errTxt.textContent = errorMsg;
                    errEl.classList.remove('d-none');
                } else {
                    errEl.classList.add('d-none');
                }

                var valido = vsItems.length > 0 && !errorMsg && montoIngresado > 0;
                btnGuardar.disabled = !valido;
            }

            // ── Renderizar tabla de ítems ──────────────────────────────────────
            function vsRenderItems() {
                var tbody = document.getElementById('vs-items-body');
                tbody.innerHTML = '';
                if (vsItems.length === 0) {
                    tbody.innerHTML = '<tr id="vs-empty-row"><td colspan="5" class="text-center text-muted small py-3">' +
                        '<i class="ri-inbox-line me-1"></i>Sin productos agregados</td></tr>';
                    return;
                }
                vsItems.forEach(function (it, idx) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td class="small">' + it.nombre +
                            (it.marca ? ' <span class="text-muted">(' + it.marca + ')</span>' : '') + '</td>' +
                        '<td><input type="number" class="form-control form-control-sm vs-cant" data-idx="' + idx + '" value="' + it.cantidad + '" min="1" max="' + it.stock + '"></td>' +
                        '<td><input type="number" class="form-control form-control-sm vs-precio" data-idx="' + idx + '" value="' + it.precio.toFixed(2) + '" min="0" step="0.01"></td>' +
                        '<td class="small fw-semibold vs-sub-cell-' + idx + '">Bs ' + it.subtotal.toFixed(2) + '</td>' +
                        '<td><button type="button" class="btn btn-sm btn-outline-danger vs-remove" data-idx="' + idx + '"><i class="ri-delete-bin-line"></i></button></td>';
                    tbody.appendChild(tr);
                });
            }

            document.getElementById('vs-items-body').addEventListener('input', function (e) {
                var el  = e.target;
                var idx = parseInt(el.dataset.idx);
                if (isNaN(idx)) return;
                if (el.classList.contains('vs-cant')) {
                    var v = Math.max(1, Math.min(parseInt(el.value) || 1, vsItems[idx].stock));
                    el.value = v;
                    vsItems[idx].cantidad = v;
                } else if (el.classList.contains('vs-precio')) {
                    vsItems[idx].precio = parseFloat(el.value) || 0;
                }
                vsItems[idx].subtotal = vsItems[idx].cantidad * vsItems[idx].precio;
                var cell = document.querySelector('.vs-sub-cell-' + idx);
                if (cell) cell.textContent = 'Bs ' + vsItems[idx].subtotal.toFixed(2);
                vsRecalcular();
            });

            document.getElementById('vs-items-body').addEventListener('click', function (e) {
                var btn = e.target.closest('.vs-remove');
                if (!btn) return;
                vsItems.splice(parseInt(btn.dataset.idx), 1);
                vsRenderItems();
                vsRecalcular();
            });

            // ── Toggle campos de pago ──────────────────────────────────────────
            function vsTogglePago() {
                var tipo = document.getElementById('vs-tipo-pago').value;
                document.getElementById('vs-campo-monto-unico').style.display = tipo !== 'parcial' ? '' : 'none';
                document.getElementById('vs-campo-efectivo').style.display   = tipo === 'parcial' ? '' : 'none';
                document.getElementById('vs-campo-qr').style.display         = tipo === 'parcial' ? '' : 'none';
                vsRecalcular();
            }

            document.getElementById('vs-tipo-pago').addEventListener('change', vsTogglePago);
            document.getElementById('vs-descuento').addEventListener('input', vsRecalcular);
            document.getElementById('vs-monto').addEventListener('input', vsRecalcular);
            document.getElementById('vs-monto-efectivo').addEventListener('input', vsRecalcular);
            document.getElementById('vs-monto-qr').addEventListener('input', vsRecalcular);

            // ── Buscar producto con debounce ────────────────────────────────────
            function vsBuscarProducto(q) {
                var res = document.getElementById('vs-buscar-resultados');
                if (!q) { res.style.display = 'none'; return; }
                res.innerHTML = '<div class="list-group-item disabled small"><span class="spinner-border spinner-border-sm me-1"></span>Buscando...</div>';
                res.style.display = 'block';

                var params = new URLSearchParams({ q: q });
                fetch(buscarUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: params.toString()
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.length) {
                        res.innerHTML = '<div class="list-group-item small text-muted">Sin resultados</div>';
                        res.style.display = 'block';
                        return;
                    }
                    res.innerHTML = '';
                    data.forEach(function (p) {
                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action small text-start';
                        a.innerHTML = '<span class="fw-semibold">' + p.nombre + '</span>' +
                            (p.marca ? ' <span class="text-muted">– ' + p.marca + '</span>' : '') +
                            ' <span class="badge bg-success ms-1">Bs ' + parseFloat(p.precio_venta).toFixed(2) + '</span>' +
                            ' <span class="badge bg-secondary ms-1">Stock: ' + p.stock + '</span>';
                        a.addEventListener('mousedown', function (ev) {
                            ev.preventDefault(); // evita que el input pierda foco antes del click
                            vsAgregarProducto(p);
                            res.style.display = 'none';
                            document.getElementById('vs-buscar-input').value = '';
                        });
                        res.appendChild(a);
                    });
                    res.style.display = 'block';
                })
                .catch(function () {
                    res.innerHTML = '<div class="list-group-item small text-danger">Error al buscar</div>';
                });
            }

            document.getElementById('vs-buscar-input').addEventListener('input', function () {
                clearTimeout(vsDebounceTimer);
                var q = this.value.trim();
                vsDebounceTimer = setTimeout(function () { vsBuscarProducto(q); }, 300);
            });

            document.getElementById('vs-buscar-input').addEventListener('blur', function () {
                // Ocultar con delay para que mousedown del resultado pueda ejecutarse
                setTimeout(function () {
                    document.getElementById('vs-buscar-resultados').style.display = 'none';
                }, 200);
            });

            // ── Agregar producto al carrito ─────────────────────────────────────
            function vsAgregarProducto(p) {
                var existente = vsItems.find(function (it) { return it.producto_id === p.id; });
                if (existente) {
                    if (existente.cantidad < p.stock) {
                        existente.cantidad++;
                        existente.subtotal = existente.cantidad * existente.precio;
                    }
                } else {
                    vsItems.push({
                        producto_id: p.id,
                        nombre: p.nombre,
                        marca: p.marca,
                        precio: parseFloat(p.precio_venta),
                        cantidad: 1,
                        stock: p.stock,
                        subtotal: parseFloat(p.precio_venta),
                    });
                }
                vsRenderItems();
                vsRecalcular();
            }

            // ── Abrir modal ─────────────────────────────────────────────────────
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-venta-sesion');
                if (!btn) return;
                vsItems = [];
                document.getElementById('vs-sesion-id').value = btn.dataset.id;
                document.getElementById('vs-subtitle').textContent = '— Sesión #' + btn.dataset.nsesion;
                document.getElementById('vs-cliente-nombre').textContent = btn.dataset.cliente;
                document.getElementById('vs-buscar-input').value = '';
                document.getElementById('vs-buscar-resultados').style.display = 'none';
                document.getElementById('vs-descuento').value = '0';
                document.getElementById('vs-monto').value = '0';
                document.getElementById('vs-monto-efectivo').value = '0';
                document.getElementById('vs-monto-qr').value = '0';
                document.getElementById('vs-tipo-pago').value = 'efectivo';
                document.getElementById('vs-observacion').value = '';
                document.getElementById('vs-error-msg').classList.add('d-none');
                document.getElementById('vs-monto-error').classList.add('d-none');
                document.getElementById('vs-btn-guardar').innerHTML = '<i class="ri-save-3-line me-1"></i>Registrar Venta';
                vsTogglePago();
                vsRenderItems();
                vsRecalcular();
                vsGetModal().show();
            });

            // ── Guardar venta ───────────────────────────────────────────────────
            document.getElementById('vs-btn-guardar').addEventListener('click', function () {
                var errEl    = document.getElementById('vs-error-msg');
                var tipoPago = document.getElementById('vs-tipo-pago').value;
                errEl.classList.add('d-none');

                var sesionId = document.getElementById('vs-sesion-id').value;
                var btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';

                var payload = {
                    items: vsItems.map(function (it) {
                        return { producto_id: it.producto_id, cantidad: it.cantidad, precio_unitario: it.precio };
                    }),
                    descuento_bs: parseFloat(document.getElementById('vs-descuento').value) || 0,
                    tipo_pago:    tipoPago,
                    observacion:  document.getElementById('vs-observacion').value,
                };

                if (tipoPago === 'parcial') {
                    payload.monto_efectivo = parseFloat(document.getElementById('vs-monto-efectivo').value) || 0;
                    payload.monto_qr       = parseFloat(document.getElementById('vs-monto-qr').value) || 0;
                } else {
                    payload.monto = parseFloat(document.getElementById('vs-monto').value) || 0;
                }

                fetch('/admin/sesiones/' + sesionId + '/venta', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        var pdfUrl = reciboUrlBase + '/' + res.venta_id + '/recibo-pdf';
                        document.querySelector('#modalVentaSesion .modal-body').innerHTML =
                            '<div class="text-center py-4">' +
                            '<div class="mb-3"><i class="ri-checkbox-circle-fill text-success" style="font-size:4rem;"></i></div>' +
                            '<h5 class="fw-bold text-success mb-2">¡Venta registrada exitosamente!</h5>' +
                            '<p class="text-muted mb-3">El recibo de la venta ha sido generado.</p>' +
                            '<div class="d-inline-block bg-warning bg-opacity-15 border border-warning rounded-4 px-4 py-3 mb-3">' +
                            '<div class="text-muted small mb-1"><i class="ri-receipt-line me-1"></i>Número de Recibo</div>' +
                            '<div class="fw-bold fs-4 text-dark">' + res.recibo + '</div>' +
                            '</div>' +
                            '<div class="mb-3">' +
                            '<a href="' + pdfUrl + '" target="_blank" class="btn btn-warning px-4 rounded-pill fw-semibold">' +
                            '<i class="ri-download-2-line me-1"></i>Descargar Recibo PDF</a>' +
                            '</div>' +
                            '<p class="text-muted small mb-0"><span class="spinner-border spinner-border-sm me-1"></span>Actualizando página en unos segundos...</p>' +
                            '</div>';
                        document.querySelector('#modalVentaSesion .modal-footer').innerHTML =
                            '<button type="button" class="btn btn-light rounded-pill" onclick="location.reload()">' +
                            '<i class="ri-refresh-line me-1"></i>Actualizar ahora</button>';
                        setTimeout(function () { location.reload(); }, 4000);
                    } else {
                        errEl.textContent = res.message || 'Error al registrar la venta.';
                        errEl.classList.remove('d-none');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Registrar Venta';
                    }
                })
                .catch(function () {
                    errEl.textContent = 'Error de conexión.';
                    errEl.classList.remove('d-none');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-save-3-line me-1"></i>Registrar Venta';
                });
            });

            // ===== MODAL DETALLE VENTAS =====
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-ver-ventas-sesion');
                if (!btn) return;
                var sesionId = btn.dataset.id;
                var nSesion  = btn.dataset.nsesion;
                document.getElementById('vd-titulo').textContent = 'Sesión #' + nSesion;
                var contenido = document.getElementById('ventas-detalle-' + sesionId);
                document.getElementById('vd-body').innerHTML = contenido
                    ? contenido.innerHTML
                    : '<p class="text-muted text-center py-3">Sin ventas registradas.</p>';
                new bootstrap.Modal(document.getElementById('modalDetalleVentas')).show();
            });
        })();
    </script>
@endpush
