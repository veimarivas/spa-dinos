@extends('admin.dashboard')
@section('title', 'Dashboard - Dino Baby Spa')
@section('admin')
@php
    $meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $diaManana = \Carbon\Carbon::today()->addDays(2)->day;
    $mesManana = $meses[\Carbon\Carbon::today()->addDays(2)->month];
    $fechaPasadoManana = $diaManana . ' ' . $mesManana;
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;">
            <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="ri-calendar-event-line text-white fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-white">
                                <i class="ri-calendar-check-line me-2 opacity-75"></i>Sesiones Programadas
                            </h5>
                            <span class="text-white-50 small">Seguimiento de sesiones próximas</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2">
                            <i class="ri-sun-line me-1"></i>Hoy: {{ $sesionesHoy->count() }}
                        </span>
                        <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2">
                            <i class="ri-sun-foggy-line me-1"></i>Mañana: {{ $sesionesManana->count() }}
                        </span>
                        <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2">
                            <i class="ri-calendar-line me-1"></i>{{ $fechaPasadoManana }}: {{ $sesionesPasadoManana->count() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills nav-justified border-bottom" id="sesionesTabs" role="tablist" style="background: #f8fafc;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-0 py-3" id="hoy-tab" data-bs-toggle="tab" data-bs-target="#sesiones-hoy" type="button" role="tab" style="border-bottom: 3px solid #7c3aed;">
                            <div class="text-center">
                                <i class="ri-sun-line fs-4 d-block mb-1" style="color: #7c3aed;"></i>
                                <span class="fw-bold" style="color: #7c3aed;">Hoy</span>
                                <span class="badge rounded-pill ms-1" style="background: #7c3aed; color: white; font-size: 0.7rem;">{{ $sesionesHoy->count() }}</span>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 py-3" id="manana-tab" data-bs-toggle="tab" data-bs-target="#sesiones-manana" type="button" role="tab" style="border-bottom: 3px solid #0d9488;">
                            <div class="text-center">
                                <i class="ri-sun-foggy-line fs-4 d-block mb-1" style="color: #0d9488;"></i>
                                <span class="fw-bold" style="color: #0d9488;">Mañana</span>
                                <span class="badge rounded-pill ms-1" style="background: #0d9488; color: white; font-size: 0.7rem;">{{ $sesionesManana->count() }}</span>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 py-3" id="pasado-tab" data-bs-toggle="tab" data-bs-target="#sesiones-pasado" type="button" role="tab" style="border-bottom: 3px solid #3b82f6;">
                            <div class="text-center">
                                <i class="ri-calendar-line fs-4 d-block mb-1" style="color: #3b82f6;"></i>
                                <span class="fw-bold" style="color: #3b82f6;">{{ $fechaPasadoManana }}</span>
                                <span class="badge rounded-pill ms-1" style="background: #3b82f6; color: white; font-size: 0.7rem;">{{ $sesionesPasadoManana->count() }}</span>
                            </div>
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-4" id="sesionesTabContent">
                    {{-- Hoy --}}
                    <div class="tab-pane fade show active" id="sesiones-hoy" role="tabpanel">
                        @if($sesionesHoy->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($sesionesHoy as $sesion)
                                    <div class="card border-0 shadow-sm rounded-4" style="border-left: 4px solid #7c3aed !important;">
                                        <div class="card-body py-3 px-4">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                                                        <i class="ri-time-line fs-5"></i>
                                                    </div>
                                                    @if($sesion->hora_inicio)
                                                        <div class="text-center mt-1">
                                                            <span class="badge rounded-pill" style="background: rgba(124,58,237,0.2); color: #7c3aed; font-size: 0.7rem; padding: 2px 8px;">
                                                                {{ substr($sesion->hora_inicio, 0, 5) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-dark">
                                                        {{ $sesion->hijo->persona->nombres ?? 'N/A' }} {{ $sesion->hijo->persona->apellido_paterno ?? '' }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="ri-heart-pulse-line me-1"></i>{{ $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan' }}
                                                        @if($sesion->suscripcion->tarifa === 'especial')
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
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill" style="background: rgba(124,58,237,0.15); color: #7c3aed; font-size: 0.8rem;">
                                                        <i class="ri-hashtag me-1"></i>Sesión {{ $sesion->n_sesion }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill {{ $sesion->estado == 'confirmada' ? 'bg-success' : 'bg-warning' }} text-dark">
                                                        <i class="{{ $sesion->estado == 'confirmada' ? 'ri-check-line' : 'ri-calendar-line' }} me-1"></i>{{ ucfirst($sesion->estado) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <small class="text-muted">
                                                        <i class="ri-user-star-line me-1"></i>{{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }} {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-end d-flex gap-2 justify-content-end">
                                                    @php
                                                        $cliente = $sesion->suscripcion->cliente->persona;
                                                        $hijo = $sesion->hijo->persona;
                                                        $celular = $cliente->celular ?? '';
                                                        $nombreCliente = trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellido_paterno ?? ''));
                                                        $nombreHijo = trim(($hijo->nombres ?? '') . ' ' . ($hijo->apellido_paterno ?? ''));
                                                        $planNombre = $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan';
                                                        $horaSesion = $sesion->hora_inicio ? substr($sesion->hora_inicio, 0, 5) : '';
                                                        $numSesion = $sesion->n_sesion;
                                                        $diaSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'][date('w')];
                                                        $fechaHoy = date('d/m/Y');
                                                        $mensaje = "Hola {$nombreCliente}, le saludamos de Dino Baby Spa.%0A%0ASu hijo(a) {$nombreHijo} tiene la sesión número {$numSesion} del plan {$planNombre} el día de hoy {$diaSemana} {$fechaHoy} a las {$horaSesion}.%0A%0A¡Te esperamos!";
                                                        $whatsappUrl = $celular ? 'https://wa.me/591' . ltrim($celular, '0') . '?text=' . $mensaje : '#';
                                                    @endphp
                                                    @if($celular)
                                                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill" title="Enviar WhatsApp">
                                                            <i class="ri-whatsapp-line"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.suscripciones.show', $sesion->suscripcion->id) }}" class="btn btn-sm rounded-pill" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white;">
                                                        <i class="ri-eye-line me-1"></i>Ver
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                </div>
                                <h5 class="mt-3 text-muted">No hay sesiones para hoy</h5>
                                <p class="text-muted small">Todas las sesiones han sido completadas o no hay programaciones</p>
                            </div>
                        @endif
                    </div>
                    {{-- Mañana --}}
                    <div class="tab-pane fade" id="sesiones-manana" role="tabpanel">
                        @if($sesionesManana->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($sesionesManana as $sesion)
                                    <div class="card border-0 shadow-sm rounded-4" style="border-left: 4px solid #0d9488 !important;">
                                        <div class="card-body py-3 px-4">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
                                                        <i class="ri-time-line fs-5"></i>
                                                    </div>
                                                    @if($sesion->hora_inicio)
                                                        <div class="text-center mt-1">
                                                            <span class="badge rounded-pill" style="background: rgba(13,148,136,0.2); color: #0d9488; font-size: 0.7rem; padding: 2px 8px;">
                                                                {{ substr($sesion->hora_inicio, 0, 5) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-dark">
                                                        {{ $sesion->hijo->persona->nombres ?? 'N/A' }} {{ $sesion->hijo->persona->apellido_paterno ?? '' }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="ri-heart-pulse-line me-1"></i>{{ $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan' }}
                                                        @if($sesion->suscripcion->tarifa === 'especial')
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
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill" style="background: rgba(13,148,136,0.15); color: #0d9488; font-size: 0.8rem;">
                                                        <i class="ri-hashtag me-1"></i>Sesión {{ $sesion->n_sesion }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill {{ $sesion->estado == 'confirmada' ? 'bg-success' : 'bg-warning' }} text-dark">
                                                        <i class="{{ $sesion->estado == 'confirmada' ? 'ri-check-line' : 'ri-calendar-line' }} me-1"></i>{{ ucfirst($sesion->estado) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <small class="text-muted">
                                                        <i class="ri-user-star-line me-1"></i>{{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }} {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-end d-flex gap-2 justify-content-end">
                                                    @php
                                                        $cliente = $sesion->suscripcion->cliente->persona;
                                                        $hijo = $sesion->hijo->persona;
                                                        $celular = $cliente->celular ?? '';
                                                        $nombreCliente = trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellido_paterno ?? ''));
                                                        $nombreHijo = trim(($hijo->nombres ?? '') . ' ' . ($hijo->apellido_paterno ?? ''));
                                                        $planNombre = $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan';
                                                        $horaSesion = $sesion->hora_inicio ? substr($sesion->hora_inicio, 0, 5) : '';
                                                        $numSesion = $sesion->n_sesion;
                                                        $manana = \Carbon\Carbon::tomorrow();
                                                        $diaSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'][$manana->dayOfWeek];
                                                        $fechaManana = $manana->format('d/m/Y');
                                                        $mensaje = "Hola {$nombreCliente}, le saludamos de Dino Baby Spa.%0A%0ANotificamos que su hijo(a) {$nombreHijo} tiene programada la sesión número {$numSesion} del plan {$planNombre} para el día {$diaSemana} {$fechaManana} a las {$horaSesion}.%0A%0APor favor, confirme su asistencia.";
                                                        $whatsappUrl = $celular ? 'https://wa.me/591' . ltrim($celular, '0') . '?text=' . $mensaje : '#';
                                                    @endphp
                                                    @if($celular)
                                                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill" title="Enviar WhatsApp">
                                                            <i class="ri-whatsapp-line"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.suscripciones.show', $sesion->suscripcion->id) }}" class="btn btn-sm rounded-pill" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: white;">
                                                        <i class="ri-eye-line me-1"></i>Ver
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ri-calendar-event-line fs-1 text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-muted">No hay sesiones para mañana</h5>
                                <p class="text-muted small">No hay sesiones programadas para el día de mañana</p>
                            </div>
                        @endif
                    </div>
                    {{-- Pasado mañana --}}
                    <div class="tab-pane fade" id="sesiones-pasado" role="tabpanel">
                        @if($sesionesPasadoManana->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($sesionesPasadoManana as $sesion)
                                    <div class="card border-0 shadow-sm rounded-4" style="border-left: 4px solid #3b82f6 !important;">
                                        <div class="card-body py-3 px-4">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);">
                                                        <i class="ri-time-line fs-5"></i>
                                                    </div>
                                                    @if($sesion->hora_inicio)
                                                        <div class="text-center mt-1">
                                                            <span class="badge rounded-pill" style="background: rgba(59,130,246,0.2); color: #3b82f6; font-size: 0.7rem; padding: 2px 8px;">
                                                                {{ substr($sesion->hora_inicio, 0, 5) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-dark">
                                                        {{ $sesion->hijo->persona->nombres ?? 'N/A' }} {{ $sesion->hijo->persona->apellido_paterno ?? '' }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="ri-heart-pulse-line me-1"></i>{{ $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan' }}
                                                        @if($sesion->suscripcion->tarifa === 'especial')
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
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.8rem;">
                                                        <i class="ri-hashtag me-1"></i>Sesión {{ $sesion->n_sesion }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge rounded-pill {{ $sesion->estado == 'confirmada' ? 'bg-success' : 'bg-warning' }} text-dark">
                                                        <i class="{{ $sesion->estado == 'confirmada' ? 'ri-check-line' : 'ri-calendar-line' }} me-1"></i>{{ ucfirst($sesion->estado) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <small class="text-muted">
                                                        <i class="ri-user-star-line me-1"></i>{{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }} {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-end d-flex gap-2 justify-content-end">
                                                    @php
                                                        $cliente = $sesion->suscripcion->cliente->persona;
                                                        $hijo = $sesion->hijo->persona;
                                                        $celular = $cliente->celular ?? '';
                                                        $nombreCliente = trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellido_paterno ?? ''));
                                                        $nombreHijo = trim(($hijo->nombres ?? '') . ' ' . ($hijo->apellido_paterno ?? ''));
                                                        $planNombre = $sesion->suscripcion->plan->nombre_plan ?? 'Sin plan';
                                                        $horaSesion = $sesion->hora_inicio ? substr($sesion->hora_inicio, 0, 5) : '';
                                                        $numSesion = $sesion->n_sesion;
                                                        $pasadoManana = \Carbon\Carbon::today()->addDays(2);
                                                        $diaSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'][$pasadoManana->dayOfWeek];
                                                        $fechaPasado = $pasadoManana->format('d/m/Y');
                                                        $mensaje = "Hola {$nombreCliente}, le saludamos de Dino Baby Spa.%0A%0ANotificamos que su hijo(a) {$nombreHijo} tiene programada la sesión número {$numSesion} del plan {$planNombre} para el día {$diaSemana} {$fechaPasado} a las {$horaSesion}.%0A%0APor favor, confirme su asistencia.";
                                                        $whatsappUrl = $celular ? 'https://wa.me/591' . ltrim($celular, '0') . '?text=' . $mensaje : '#';
                                                    @endphp
                                                    @if($celular)
                                                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill" title="Enviar WhatsApp">
                                                            <i class="ri-whatsapp-line"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.suscripciones.show', $sesion->suscripcion->id) }}" class="btn btn-sm rounded-pill" style="background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%); color: white;">
                                                        <i class="ri-eye-line me-1"></i>Ver
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ri-calendar-event-line fs-1 text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-muted">No hay sesiones para este día</h5>
                                <p class="text-muted small">No hay sesiones programadas para {{ $fechaPasadoManana }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabs: Top Clientes y Clientes con Deudas --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;">
            <div class="card-header border-0 p-0" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);">
                <ul class="nav nav-pills nav-justified" id="clientesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-0 py-3" id="top-clientes-tab" data-bs-toggle="tab" data-bs-target="#top-clientes" type="button" role="tab" style="border-bottom: 3px solid #0d9488;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-trophy-line fs-5" style="color: #0d9488;"></i>
                                <span class="fw-bold" style="color: #0d9488;">Top Clientes</span>
                                <span class="badge rounded-pill" style="background: #0d9488; color: white; font-size: 0.7rem;">{{ $clientesTopSuscripciones->count() }}</span>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 py-3" id="clientes-deudas-tab" data-bs-toggle="tab" data-bs-target="#clientes-deudas" type="button" role="tab" style="border-bottom: 3px solid #dc2626;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-error-warning-line fs-5" style="color: #dc2626;"></i>
                                <span class="fw-bold" style="color: #dc2626;">Clientes con Deudas</span>
                                <span class="badge rounded-pill" style="background: #dc2626; color: white; font-size: 0.7rem;">{{ $clientesConDeudas->count() }}</span>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 py-3" id="recibos-hoy-tab" data-bs-toggle="tab" data-bs-target="#recibos-hoy" type="button" role="tab" style="border-bottom: 3px solid #7c3aed;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-file-text-line fs-5" style="color: #7c3aed;"></i>
                                <span class="fw-bold" style="color: #7c3aed;">Recibos de Hoy</span>
                                <span class="badge rounded-pill" style="background: #7c3aed; color: white; font-size: 0.7rem;">{{ $recibosHoy->count() }}</span>
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="clientesTabContent">
                    {{-- Top Clientes --}}
                    <div class="tab-pane fade show active" id="top-clientes" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: linear-gradient(135deg, #f0fdfa 0%, #ecfeff 100%);">
                                    <tr>
                                        <th class="border-0 px-4 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #0d9488;">#</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #0d9488;">Cliente</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #0d9488;">Hijos</th>
                                        <th class="border-0 py-3 text-center" style="font-size: 0.75rem; text-transform: uppercase; color: #0d9488;">Suscrip.</th>
                                        <th class="border-0 py-3 text-end px-4" style="font-size: 0.75rem; text-transform: uppercase; color: #0d9488;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clientesTopSuscripciones as $index => $cliente)
                                        <tr>
                                            <td class="px-4">
                                                <span class="badge rounded-pill {{ $index == 0 ? 'bg-warning text-dark' : ($index == 1 ? 'bg-secondary text-white' : ($index == 2 ? 'bg-info text-white' : 'bg-light text-dark')) }}" style="font-size: 0.75rem;">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                                        style="width: 36px; height: 36px; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); font-size: 0.85rem;">
                                                        {{ strtoupper(substr($cliente->persona->nombres ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                                            {{ $cliente->persona->nombres ?? 'N/A' }} {{ $cliente->persona->apellido_paterno ?? '' }}
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="ri-smartphone-line me-1"></i>{{ $cliente->persona->celular ?? 'Sin celular' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($cliente->parentescos->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($cliente->parentescos->take(3) as $parentesco)
                                                            <span class="badge rounded-pill" style="background: rgba(168,85,247,0.15); color: #a855f7; font-size: 0.7rem;">
                                                                <i class="ri-baby-line me-1"></i>{{ $parentesco->hijo->persona->nombres ?? '' }}
                                                            </span>
                                                        @endforeach
                                                        @if($cliente->parentescos->count() > 3)
                                                            <span class="badge rounded-pill bg-light text-muted" style="font-size: 0.65rem;">+{{ $cliente->parentescos->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted small">Sin hijos</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $susData = [];
                                                    foreach ($cliente->suscripciones as $sus) {
                                                        $planSus    = $sus->plan;
                                                        $tarifaSus  = $sus->tarifa ?? 'normal';
                                                        $precioSus  = $sus->monto_total
                                                            ?? ($tarifaSus === 'especial' && $planSus && !is_null($planSus->precio_especial)
                                                                ? (float) $planSus->precio_especial
                                                                : (float) ($planSus?->precio ?? 0));
                                                        $pagadoSus    = (float) $sus->pagos->sum('pago_bs');
                                                        $descuentoSus = (float) $sus->pagos->sum('descuento_bs');
                                                        $saldoSus     = max(0, $precioSus - $pagadoSus - $descuentoSus);
                                                        $susData[] = [
                                                            'id' => $sus->id,
                                                            'plan_nombre' => $planSus?->nombre_plan ?? 'Sin plan',
                                                            'tarifa' => $tarifaSus,
                                                            'precio' => $precioSus,
                                                            'pagado' => $pagadoSus,
                                                            'descuento' => $descuentoSus,
                                                            'saldo' => $saldoSus,
                                                            'estado' => $sus->estado,
                                                            'fecha' => optional($sus->fecha_registro)->format('d/m/Y'),
                                                            'sesiones_iniciales' => $sus->sesiones_iniciales,
                                                            'sesiones_restantes' => $sus->sesiones_restantes,
                                                        ];
                                                    }
                                                @endphp
                                                <button type="button" class="btn p-0 border-0 bg-transparent btn-ver-suscripciones-cliente"
                                                    title="Ver suscripciones"
                                                    data-cliente="{{ ($cliente->persona->nombres ?? '') . ' ' . ($cliente->persona->apellido_paterno ?? '') }}"
                                                    data-suscripciones='@json($susData)'>
                                                    <span class="badge rounded-pill fs-6" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: white; padding: 8px 14px; cursor:pointer;">
                                                        <i class="ri-stack-line me-1"></i>{{ $cliente->suscripciones_count }}
                                                        <i class="ri-external-link-line ms-1" style="font-size:.7rem;opacity:.85;"></i>
                                                    </span>
                                                </button>
                                            </td>
                                            <td class="text-end px-4">
                                                <a href="{{ route('admin.clientes.seguimiento', $cliente->id) }}" 
                                                   class="btn btn-sm rounded-pill" 
                                                   style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: white; font-size: 0.75rem; padding: 6px 12px;">
                                                    <i class="ri-eye-line me-1"></i>Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="ri-user-line fs-3 opacity-50"></i>
                                                <p class="mb-0 mt-2">No hay clientes con suscripciones</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Clientes con Deudas --}}
                    <div class="tab-pane fade" id="clientes-deudas" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%);">
                                    <tr>
                                        <th class="border-0 px-4 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #dc2626;">Cliente</th>
                                        <th class="border-0 py-3 text-center" style="font-size: 0.75rem; text-transform: uppercase; color: #dc2626;">Deuda Total</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #dc2626;">Suscripciones</th>
                                        <th class="border-0 py-3 text-end px-4" style="font-size: 0.75rem; text-transform: uppercase; color: #dc2626;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clientesConDeudas as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                                        style="width: 36px; height: 36px; background: linear-gradient(135deg, #dc2626 0%, #f59e0b 100%); font-size: 0.85rem;">
                                                        {{ strtoupper(substr($item['cliente']->persona->nombres ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                                            {{ $item['cliente']->persona->nombres ?? 'N/A' }} {{ $item['cliente']->persona->apellido_paterno ?? '' }}
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="ri-smartphone-line me-1"></i>{{ $item['cliente']->persona->celular ?? 'Sin celular' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="p-2 rounded-3 d-inline-block" style="background: linear-gradient(135deg, rgba(220,53,69,0.1) 0%, rgba(245,158,11,0.05) 100%); border: 1px solid rgba(220,53,69,0.3);">
                                                    <span class="fw-bold text-danger" style="font-size: 1rem;">
                                                        <i class="ri-error-warning-line me-1"></i>{{ number_format($item['deuda_total'], 2) }} Bs.
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.75rem;">
                                                    <i class="ri-stack-line me-1"></i>{{ count($item['suscripciones']) }} suscripci{{ count($item['suscripciones']) == 1 ? 'ón' : 'ones' }}
                                                </span>
                                            </td>
                                            <td class="text-end px-4">
                                                <button type="button" class="btn btn-sm rounded-pill btn-detalle-deuda" 
                                                    style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white; font-size: 0.75rem; padding: 6px 12px;"
                                                    data-nombres="{{ $item['cliente']->persona->nombres ?? '' }}"
                                                    data-apellido="{{ $item['cliente']->persona->apellido_paterno ?? '' }}"
                                                    data-celular="{{ $item['cliente']->persona->celular ?? '' }}"
                                                    data-deuda="{{ $item['deuda_total'] }}">
                                                    <i class="ri-eye-line me-1"></i>Ver Detalle
                                                </button>
                                                <textarea class="datos-suscripciones" style="display:none;">{{ json_encode(array_map(function($s) { return ['plan_nombre' => $s['plan_nombre'], 'nombre_hijo' => $s['nombre_hijo'], 'tarifa' => $s['tarifa'] ?? 'normal', 'precio' => $s['precio'], 'total_pagado' => $s['total_pagado'], 'total_descuento' => $s['total_descuento'] ?? 0, 'saldo' => $s['saldo'], 'suscripcion_id' => $s['suscripcion']['id'] ?? null]; }, is_array($item['suscripciones']) ? $item['suscripciones'] : [])) }}</textarea>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="ri-checkbox-circle-line fs-3 opacity-50" style="color: #22c55e;"></i>
                                                <p class="mb-0 mt-2" style="color: #22c55e;">No hay clientes con deudas</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Recibos de Hoy --}}
                    <div class="tab-pane fade" id="recibos-hoy" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);">
                                    <tr>
                                        <th class="border-0 px-4 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Recibo</th>
                                        <th class="border-0 py-3 text-center" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Cliente</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Detalle</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Tipo Pago</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Monto</th>
                                        <th class="border-0 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">Trabajador</th>
                                        <th class="border-0 py-3 text-end px-4" style="font-size: 0.75rem; text-transform: uppercase; color: #7c3aed;">PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recibosHoy as $reg)
                                        <tr>
                                            <td class="px-4">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                    <i class="ri-file-text-line me-1"></i>{{ $reg['recibo'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.clientes.seguimiento', $reg['cliente_id']) }}" class="text-decoration-none">
                                                    <span class="fw-semibold" style="font-size: 0.75rem; color: #405189;">{{ $reg['cliente'] }}</span>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info rounded-pill" style="font-size: 0.65rem;">
                                                    <i class="ri-stack-line me-1"></i>{{ $reg['detalle'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $tipoBadge = match($reg['tipo_pago']) {
                                                        'efectivo' => 'bg-success-subtle text-success',
                                                        'qr' => 'bg-primary-subtle text-primary',
                                                        default => 'bg-secondary-subtle text-secondary',
                                                    };
                                                    $tipoIcon = match($reg['tipo_pago']) {
                                                        'efectivo' => 'ri-money-dollar-circle-line',
                                                        'qr' => 'ri-qr-code-line',
                                                        default => 'ri-exchange-line',
                                                    };
                                                @endphp
                                                <span class="badge {{ $tipoBadge }} rounded-pill" style="font-size: 0.65rem;">
                                                    <i class="{{ $tipoIcon }} me-1"></i>{{ ucfirst($reg['tipo_pago']) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-start gap-1">
                                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.7rem;">
                                                        <i class="ri-add-circle-line me-1"></i>{{ number_format($reg['monto'], 2) }} Bs
                                                    </span>
                                                    @if($reg['descuento'] > 0)
                                                        <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 0.6rem;">
                                                            <i class="ri-subtract-circle-line me-1"></i>{{ number_format($reg['descuento'], 2) }} Bs
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($reg['trabajador'] && $reg['trabajador_id'])
                                                    <a href="{{ route('admin.trabajadores.ver', $reg['trabajador_id']) }}" class="text-decoration-none">
                                                        <span class="text-truncate" style="font-size: 0.7rem; color: #198754;">{{ $reg['trabajador'] }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-muted" style="font-size: 0.65rem;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end px-4">
                                                <a href="/admin/suscripciones/pagos/{{ $reg['pago_id'] }}/recibo-pdf" class="btn btn-sm btn-outline-danger rounded-pill" title="PDF" target="_blank">
                                                    <i class="ri-file-pdf-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="ri-file-text-line fs-3 opacity-50" style="color: #7c3aed;"></i>
                                                <p class="mb-0 mt-2" style="color: #7c3aed;">No hay recibos el día de hoy</p>
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
    </div>
</div>

{{-- Modal: Suscripciones de un cliente (Top clientes) --}}
<div id="modalSuscripcionesOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center;">
    <div class="modal-content rounded-4 shadow-lg" style="background:#fff; max-width:920px; width:92%; max-height:90vh; display:flex; flex-direction:column;">
        <div class="d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); border-radius: 1rem 1rem 0 0;">
            <h5 class="modal-title text-white fw-bold mb-0">
                <i class="ri-stack-line me-2"></i><span id="modalSusTitulo">Suscripciones</span>
            </h5>
            <button type="button" onclick="cerrarModalSuscripciones()" style="background:none; border:none; color:white; font-size:1.5rem; cursor:pointer; padding:0; line-height:1;">&times;</button>
        </div>
        <div id="modalSusContent" style="padding:20px; overflow-y:auto; max-height:70vh;"></div>
        <div style="padding:15px 20px; border-top:1px solid #e9ecef; text-align:right;">
            <button type="button" onclick="cerrarModalSuscripciones()" class="btn btn-secondary rounded-pill">
                <i class="ri-close-line me-1"></i>Cerrar
            </button>
        </div>
    </div>
</div>

{{-- Modal personalizado para detalle de deuda --}}
<div id="modalDeudaOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; max-width: 700px; width: 90%; max-height: 90vh; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white fw-bold mb-0">
                <i class="ri-wallet-3-line me-2"></i>Detalle de Deudas
            </h5>
            <button type="button" onclick="cerrarModalDeuda()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1;">&times;</button>
        </div>
        <div id="modalDeudaContent" style="padding: 20px; overflow-y: auto; max-height: 70vh;">
            {{-- Contenido dinámico --}}
        </div>
        <div style="padding: 12px 20px; background: #f8f9fa; text-align: right; border-top: 1px solid #dee2e6;">
            <button type="button" onclick="cerrarModalDeuda()" class="btn btn-secondary rounded-pill">
                <i class="ri-close-line me-1"></i>Cerrar
            </button>
        </div>
    </div>
</div>

{{-- Modal para registrar pago --}}
<div id="modalPagoOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; max-width: 600px; width: 90%; max-height: 90vh; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #198754 0%, #22c55e 100%); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white fw-bold mb-0">
                <i class="ri-money-dollar-circle-line me-2"></i>Registrar Pago
            </h5>
            <button type="button" onclick="cerrarModalPago()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1;">&times;</button>
        </div>
        <div id="modalPagoContent" style="padding: 20px; overflow-y: auto; max-height: 70vh;">
            {{-- Contenido dinámico --}}
        </div>
        <div style="padding: 12px 20px; background: #f8f9fa; text-align: right; border-top: 1px solid #dee2e6;">
            <button type="button" onclick="cerrarModalPago()" class="btn btn-secondary rounded-pill">
                <i class="ri-close-line me-1"></i>Cancelar
            </button>
        </div>
    </div>
</div>

{{-- Modal para mostrar recibos después del pago --}}
<div id="modalRecibosOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10001; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; max-width: 500px; width: 90%; max-height: 90vh; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #1a2d4c 0%, #0d9488 100%); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 class="modal-title text-white fw-bold mb-0">
                    <i class="ri-checkbox-circle-fill me-2"></i>¡Pago registrado!
                </h5>
                <p class="text-white-50 small mb-0 mt-1" id="recibos-count-text">
                    Se generaron recibos de pago
                </p>
            </div>
            <button type="button" onclick="cerrarModalRecibos()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1;">&times;</button>
        </div>
        <div id="modalRecibosContent" style="padding: 20px; overflow-y: auto; max-height: 70vh;">
            {{-- Contenido dinámico --}}
        </div>
        <div style="padding: 12px 20px; background: #f8f9fa; text-align: right; border-top: 1px solid #dee2e6;">
            <button type="button" onclick="cerrarModalRecibos()" class="btn btn-light rounded-pill">
                <i class="ri-close-line me-1"></i>Cerrar
            </button>
        </div>
    </div>
</div>

<style>
.modal-pill {
    border-radius: 50px;
}
</style>

<script>
function abrirModalDeuda(nombres, apellido, celular, deudaTotal, suscripciones) {
    let html = `
        <div class="mb-4 p-3 rounded-3" style="background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%); border: 1px solid rgba(220,53,69,0.2);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold">${nombres} ${apellido}</h6>
                    <small class="text-muted">
                        <i class="ri-smartphone-line me-1"></i>${celular || 'Sin celular'}
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge bg-danger fs-6 rounded-pill px-3 py-2">
                        <i class="ri-error-warning-line me-1"></i>Deuda Total: ${Number(deudaTotal).toFixed(2)} Bs.
                    </span>
                </div>
            </div>
        </div>

        <h6 class="fw-bold mb-3" style="color: #dc2626;">
            <i class="ri-stack-line me-2"></i>Suscripciones con Deuda
        </h6>

        <div class="d-flex flex-column gap-3">
    `;
    
    suscripciones.forEach(function(sus) {
        const tarifa = sus.tarifa || 'normal';
        const tarifaBadge = tarifa === 'especial'
            ? `<span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.7rem;"><i class="ri-star-fill me-1"></i>Especial</span>`
            : `<span class="badge rounded-pill" style="background:#f0fdf4;color:#0d9488;font-size:.7rem;"><i class="ri-sun-line me-1"></i>Normal</span>`;
        const descuento = Number(sus.total_descuento || 0);
        html += `
            <div class="card border-0 shadow-sm rounded-3" style="border-left: 4px solid #dc2626 !important;">
                <div class="card-body py-3 px-3">
                    <div class="row align-items-center g-2">
                        <div class="col-md-3">
                            <span class="badge rounded-pill" style="background: rgba(168,85,247,0.15); color: #a855f7; font-size: 0.75rem;">
                                <i class="ri-baby-line me-1"></i>${sus.nombre_hijo}
                            </span>
                            <div class="mt-1 d-flex flex-wrap gap-1">
                                <span class="badge rounded-pill" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.7rem;">
                                    <i class="ri-stack-line me-1"></i>${sus.plan_nombre}
                                </span>
                                ${tarifaBadge}
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Precio</small>
                            <span class="fw-semibold">${Number(sus.precio).toFixed(2)} Bs.</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Pagado</small>
                            <span class="fw-semibold text-success">${Number(sus.total_pagado).toFixed(2)} Bs.</span>
                            ${descuento > 0
                                ? `<small class="d-block" style="color:#b45309;font-size:.65rem;"><i class="ri-coupon-3-line me-1"></i>Desc. ${descuento.toFixed(2)} Bs.</small>`
                                : ''}
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Pendiente</small>
                            <span class="fw-bold text-danger">${Number(sus.saldo).toFixed(2)} Bs.</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill btn-pagar-deuda"
                                style="font-size: 0.7rem;"
                                data-suscripcion-id="${sus.suscripcion_id}"
                                data-saldo="${sus.saldo}"
                                data-cliente="${nombres} ${apellido}"
                                data-plan="${sus.plan_nombre}"
                                data-hijo="${sus.nombre_hijo}"
                                data-precio="${sus.precio}"
                                data-pagado="${sus.total_pagado}">
                                <i class="ri-money-dollar-circle-line me-1"></i>Pagar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    document.getElementById('modalDeudaContent').innerHTML = html;
    document.getElementById('modalDeudaOverlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModalDeuda() {
    document.getElementById('modalDeudaOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ====== Modal: Suscripciones de un cliente (Top clientes) ======
function abrirModalSuscripcionesCliente(nombreCliente, suscripciones) {
    document.getElementById('modalSusTitulo').textContent = 'Suscripciones de ' + nombreCliente;

    if (!Array.isArray(suscripciones) || suscripciones.length === 0) {
        document.getElementById('modalSusContent').innerHTML =
            '<div class="text-center py-4 text-muted"><i class="ri-inbox-line fs-1 opacity-50"></i><p class="mb-0 mt-2">Sin suscripciones</p></div>';
    } else {
        var rows = suscripciones.map(function (s) {
            var tarifaBadge = s.tarifa === 'especial'
                ? '<span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:.7rem;"><i class="ri-star-fill me-1"></i>Especial</span>'
                : '<span class="badge rounded-pill" style="background:#f0fdf4;color:#0d9488;font-size:.7rem;"><i class="ri-sun-line me-1"></i>Normal</span>';
            var estadoLower = (s.estado || '').toLowerCase();
            var estadoBadge = estadoLower === 'activa'
                ? '<span class="badge rounded-pill bg-success-subtle text-success" style="font-size:.7rem;"><i class="ri-checkbox-circle-line me-1"></i>Activa</span>'
                : '<span class="badge rounded-pill bg-secondary-subtle text-secondary" style="font-size:.7rem;"><i class="ri-stop-circle-line me-1"></i>' + (s.estado || 'N/D') + '</span>';
            var saldo = Number(s.saldo || 0);
            var saldoHtml = saldo > 0
                ? '<span class="fw-bold text-danger">' + saldo.toFixed(2) + ' Bs.</span>'
                : '<span class="badge rounded-pill bg-success-subtle text-success" style="font-size:.7rem;"><i class="ri-check-line me-1"></i>Pagado</span>';
            return ''
                + '<tr>'
                + '<td><span class="badge rounded-pill bg-primary-subtle text-primary" style="font-size:.75rem;">#' + s.id + '</span></td>'
                + '<td><div class="fw-semibold">' + (s.plan_nombre || '-') + '</div><small class="text-muted"><i class="ri-calendar-line me-1"></i>' + (s.fecha || '-') + '</small></td>'
                + '<td>' + tarifaBadge + '</td>'
                + '<td>' + estadoBadge + '</td>'
                + '<td class="text-end fw-semibold">' + Number(s.precio || 0).toFixed(2) + ' Bs.</td>'
                + '<td class="text-end text-success">' + Number(s.pagado || 0).toFixed(2) + ' Bs.'
                + (Number(s.descuento || 0) > 0 ? '<br><small style="color:#b45309;font-size:.65rem;"><i class="ri-coupon-3-line me-1"></i>Desc. ' + Number(s.descuento).toFixed(2) + '</small>' : '')
                + '</td>'
                + '<td class="text-end">' + saldoHtml + '</td>'
                + '<td class="text-end">'
                + '<a href="/admin/suscripciones/ver/' + s.id + '" target="_blank" class="btn btn-sm btn-outline-info rounded-pill" style="font-size:.7rem;"><i class="ri-eye-line me-1"></i>Ver</a>'
                + '</td>'
                + '</tr>';
        }).join('');

        document.getElementById('modalSusContent').innerHTML =
            '<div class="table-responsive">'
            + '<table class="table table-hover align-middle mb-0">'
            + '<thead style="background:#f8fafc;">'
            + '<tr>'
            + '<th class="border-0" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">ID</th>'
            + '<th class="border-0" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Plan</th>'
            + '<th class="border-0" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Tarifa</th>'
            + '<th class="border-0" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Estado</th>'
            + '<th class="border-0 text-end" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Precio</th>'
            + '<th class="border-0 text-end" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Pagado</th>'
            + '<th class="border-0 text-end" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Saldo</th>'
            + '<th class="border-0 text-end" style="font-size:.7rem;text-transform:uppercase;color:#0d9488;">Acción</th>'
            + '</tr></thead><tbody>' + rows + '</tbody></table></div>';
    }

    document.getElementById('modalSuscripcionesOverlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModalSuscripciones() {
    document.getElementById('modalSuscripcionesOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('modalSuscripcionesOverlay').addEventListener('click', function (e) {
    if (e.target === this) cerrarModalSuscripciones();
});

document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-ver-suscripciones-cliente');
    if (!btn) return;
    e.preventDefault();
    var cliente = btn.getAttribute('data-cliente') || '';
    var raw = btn.getAttribute('data-suscripciones') || '[]';
    var lista = [];
    try { lista = JSON.parse(raw); } catch (err) { lista = []; }
    abrirModalSuscripcionesCliente(cliente.trim(), lista);
});

// Cerrar al hacer clic fuera del modal
document.getElementById('modalDeudaOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalDeuda();
    }
});

// Event delegation para los botones de detalle
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-detalle-deuda');
    if (btn) {
        var nombres = btn.getAttribute('data-nombres');
        var apellido = btn.getAttribute('data-apellido');
        var celular = btn.getAttribute('data-celular');
        var deudaTotal = parseFloat(btn.getAttribute('data-deuda'));
        
        // Obtener las suscripciones del textarea oculto
        var textarea = btn.parentElement.querySelector('.datos-suscripciones');
        var suscripciones = JSON.parse(textarea.value);
        
        abrirModalDeuda(nombres, apellido, celular, deudaTotal, suscripciones);
    }
    
    // Event delegation para los botones de pagar
    var btnPagar = e.target.closest('.btn-pagar-deuda');
    if (btnPagar) {
        e.preventDefault();
        e.stopPropagation();
        
        var suscripcionId = btnPagar.getAttribute('data-suscripcion-id');
        var saldo = parseFloat(btnPagar.getAttribute('data-saldo'));
        var cliente = btnPagar.getAttribute('data-cliente');
        var plan = btnPagar.getAttribute('data-plan');
        var hijo = btnPagar.getAttribute('data-hijo');
        var precio = parseFloat(btnPagar.getAttribute('data-precio'));
        var pagado = parseFloat(btnPagar.getAttribute('data-pagado'));
        
        var html = `
            <div class="mb-4 p-3 rounded-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid rgba(34,197,94,0.2);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">${cliente}</h6>
                        <small class="text-muted">
                            <i class="ri-baby-line me-1"></i>${hijo}
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success fs-6 rounded-pill px-3 py-2">
                            <i class="ri-stack-line me-1"></i>${plan}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="text-center p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <small class="text-muted d-block">Precio Plan</small>
                        <span class="fw-bold text-primary">${precio.toFixed(2)} Bs.</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <small class="text-muted d-block">Pagado</small>
                        <span class="fw-bold text-success">${pagado.toFixed(2)} Bs.</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, rgba(220,53,69,0.1) 0%, rgba(220,53,69,0.05) 100%); border: 1px solid rgba(220,53,69,0.3);">
                        <small class="text-muted d-block">Saldo Pendiente</small>
                        <span class="fw-bold text-danger fs-5">${saldo.toFixed(2)} Bs.</span>
                    </div>
                </div>
            </div>
            
            <div id="alerta-monto" class="alert alert-danger d-none" role="alert">
                <i class="ri-error-warning-fill me-1"></i>
                <strong>Monto excede el saldo pendiente</strong>
            </div>
            
            <form id="formPagoRapido" action="/admin/suscripciones/${suscripcionId}/pagos/guardar" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                <input type="hidden" name="fecha_pago" value="${new Date().toISOString().split('T')[0]}">
                <input type="hidden" id="saldo_oculto" value="${saldo}">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">
                        <i class="ri-bank-card-line me-1 text-primary"></i>Tipo de Pago
                        <span class="text-danger">*</span>
                    </label>
                    <select name="tipo_pago" id="tipo_pago" class="form-select form-select-lg rounded-3 border-2" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="qr">📱 QR</option>
                        <option value="parcial">🔀 Parcial (Efectivo + QR)</option>
                    </select>
                </div>
                
                <div class="mb-3" id="campo-monto-unico">
                    <label class="form-label fw-semibold text-dark">
                        <i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto a Pagar (Bs.)
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" step="0.01" name="monto" id="monto_unico" class="form-control form-control-lg rounded-3 border-2" placeholder="0.00" max="${saldo}" value="${saldo}">
                    <small class="text-muted" id="label-monto-max">Máximo: ${saldo.toFixed(2)} Bs.</small>
                </div>
                
                <div class="mb-3" id="campos-parcial" style="display: none;">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark">Monto Efectivo (Bs.)</label>
                            <input type="number" step="0.01" name="monto_efectivo" id="monto_efectivo" class="form-control rounded-3 border-2" placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark">Monto QR (Bs.)</label>
                            <input type="number" step="0.01" name="monto_qr" id="monto_qr" class="form-control rounded-3 border-2" placeholder="0.00">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">
                        <i class="ri-file-text-line me-1 text-muted"></i>Observación
                    </label>
                    <textarea name="observacion" id="observacion" class="form-control rounded-3 border-2" rows="2" placeholder="Opcional"></textarea>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill">
                        <i class="ri-check-line me-1"></i>Registrar Pago
                    </button>
                </div>
            </form>
        `;
        
        document.getElementById('modalPagoContent').innerHTML = html;
        document.getElementById('modalPagoOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Evento para tipo de pago
        var tipoPagoSelect = document.getElementById('tipo_pago');
        if (tipoPagoSelect) {
            tipoPagoSelect.addEventListener('change', function() {
                var camposParcial = document.getElementById('campos-parcial');
                var campoMontoUnico = document.getElementById('campo-monto-unico');
                
                if (this.value === 'parcial') {
                    camposParcial.style.display = 'block';
                    campoMontoUnico.style.display = 'none';
                } else {
                    camposParcial.style.display = 'none';
                    campoMontoUnico.style.display = 'block';
                }
                // Revalidar al cambiar tipo de pago
                if (typeof validarMonto === 'function') validarMonto();
            });
        }
        
        // Validación en tiempo real del monto
        var formPago = document.getElementById('formPagoRapido');
        var montoInput = document.getElementById('monto_unico');
        var montoEfectivo = document.getElementById('monto_efectivo');
        var montoQr = document.getElementById('monto_qr');
        var alertaMonto = document.getElementById('alerta-monto');
        var saldoOculto = document.getElementById('saldo_oculto');
        var btnSubmit = formPago ? formPago.querySelector('button[type="submit"]') : null;
        
        function validarMonto() {
            if (!saldoOculto || !btnSubmit) return;
            var saldo = parseFloat(saldoOculto.value);
            var monto = 0;
            var esParcial = tipoPagoSelect && tipoPagoSelect.value === 'parcial';
            
            if (esParcial) {
                monto = (parseFloat(montoEfectivo.value) || 0) + (parseFloat(montoQr.value) || 0);
            } else {
                monto = parseFloat(montoInput.value) || 0;
            }
            
            if (monto > saldo) {
                alertaMonto.classList.remove('d-none');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="ri-error-warning-line me-1"></i>Monto excede el saldo';
            } else {
                alertaMonto.classList.add('d-none');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ri-check-line me-1"></i>Registrar Pago';
            }
        }
        
        if (montoInput && saldoOculto && btnSubmit) {
            montoInput.addEventListener('input', validarMonto);
            validarMonto();
        }
        
        if (montoEfectivo && montoQr && saldoOculto && btnSubmit) {
            montoEfectivo.addEventListener('input', validarMonto);
            montoQr.addEventListener('input', validarMonto);
        }
        
        // Manejar submit del formulario con AJAX
        if (formPago) {
            formPago.addEventListener('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = this.getAttribute('action');
                var csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.redirect) {
                        if (data.recibos && data.recibos.length > 0) {
                            var count = data.recibos.length;
                            document.getElementById('recibos-count-text').textContent = 
                                'Se gener' + (count === 1 ? 'ó' : 'aron') + ' ' + count + ' recibo' + (count > 1 ? 's' : '') + ' de pago';
                            
                            var html = '<p class="text-muted small mb-3"><i class="ri-information-line me-1"></i>Descarga e imprime el recibo para entregárselo al cliente.</p>';
                            html += '<div class="d-flex flex-column gap-2">';
                            
                            data.recibos.forEach(function(rec) {
                                html += '<a href="/admin/suscripciones/pagos/' + rec.id + '/recibo-pdf" target="_blank" class="btn rounded-3 fw-semibold d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #1a2d4c, #0d9488); color: #fff;">';
                                html += '<span><i class="ri-file-pdf-line me-2 fs-5"></i>Recibo N° <strong>' + rec.recibo + '</strong></span>';
                                html += '<span class="d-flex align-items-center gap-2"><span class="small opacity-75">Descargar / Imprimir</span><i class="ri-download-2-line fs-5"></i></span>';
                                html += '</a>';
                            });
                            
                            html += '</div>';
                            document.getElementById('modalRecibosContent').innerHTML = html;
                            document.getElementById('modalRecibosOverlay').style.display = 'flex';
                            showToast('success', 'Pago registrado correctamente. Recibo: ' + data.recibo);
                        } else {
                            showToast('success', 'Pago registrado correctamente');
                            cerrarModalPago();
                            window.location.reload();
                        }
                    } else {
                        showToast('error', data.message || 'Error al registrar el pago');
                    }
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    if (error.message) {
                        showToast('error', 'Error: ' + error.message);
                    } else {
                        showToast('error', 'Error al registrar el pago');
                    }
                });
            });
        }
    }
});

function cerrarModalPago() {
    document.getElementById('modalPagoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function cerrarModalRecibos() {
    document.getElementById('modalRecibosOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
    window.location.reload();
}

document.getElementById('modalPagoOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalPago();
    }
});

document.getElementById('modalRecibosOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalRecibos();
    }
});

function showToast(type, message) {
    const config = {
        success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
        error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
        warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
        info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
    };
    const cfg = config[type] || config.info;
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast show ${cfg.bgClass} text-white shadow-lg" role="alert" style="min-width: 300px;">
            <div class="toast-header ${cfg.bgClass} text-white border-bottom-0">
                <i class="${cfg.icon} me-2"></i>
                <strong class="me-auto">${cfg.title}</strong>
                <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('${toastId}').remove()"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>`;
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '11000';
        document.body.appendChild(container);
    }
    container.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    setTimeout(() => toastElement.classList.add('show'), 10);
    setTimeout(() => {
        toastElement.classList.remove('show');
        setTimeout(() => toastElement.remove(), 300);
    }, 4000);
}
</script>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
}
.table-hover tbody tr:hover {
    background-color: #f8fafc;
}
.nav-pills .nav-link {
    background: transparent;
    border-radius: 0;
    transition: all 0.3s ease;
}
.nav-pills .nav-link:hover {
    background: rgba(0,0,0,0.03);
}
.nav-pills .nav-link.active {
    background: transparent;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-ver-detalle');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            
            var clienteId = btn.getAttribute('data-cliente-id');
            var nombres = btn.getAttribute('data-nombres');
            var apellido = btn.getAttribute('data-apellido');
            var celular = btn.getAttribute('data-celular');
            var deudaTotal = btn.getAttribute('data-deuda');
            var suscripcionesJson = btn.getAttribute('data-suscripciones');
            
            let suscripciones;
            try {
                suscripciones = JSON.parse(suscripcionesJson.replace(/&quot;/g, '"'));
            } catch(err) {
                console.error('Error:', err);
                return;
            }
            
            let html = `
                <div class="mb-4 p-3 rounded-3" style="background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%); border: 1px solid rgba(220,53,69,0.2);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold">${nombres} ${apellido}</h6>
                            <small class="text-muted">
                                <i class="ri-smartphone-line me-1"></i>${celular || 'Sin celular'}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger fs-6 rounded-pill px-3 py-2">
                                <i class="ri-error-warning-line me-1"></i>Deuda Total: ${Number(deudaTotal).toFixed(2)} Bs.
                            </span>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3" style="color: #dc2626;">
                    <i class="ri-stack-line me-2"></i>Suscripciones con Deuda
                </h6>

                <div class="d-flex flex-column gap-3">
            `;
            
            suscripciones.forEach(function(sus) {
                html += `
                    <div class="card border-0 shadow-sm rounded-3" style="border-left: 4px solid #dc2626 !important;">
                        <div class="card-body py-3 px-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <span class="badge rounded-pill" style="background: rgba(168,85,247,0.15); color: #a855f7; font-size: 0.75rem;">
                                        <i class="ri-baby-line me-1"></i>${sus.nombre_hijo}
                                    </span>
                                    <div class="mt-1">
                                        <span class="badge rounded-pill" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.7rem;">
                                            <i class="ri-stack-line me-1"></i>${sus.plan_nombre}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted d-block">Total Plan</small>
                                    <span class="fw-semibold">${Number(sus.precio).toFixed(2)} Bs.</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted d-block">Pagado</small>
                                    <span class="fw-semibold text-success">${Number(sus.total_pagado).toFixed(2)} Bs.</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted d-block">Pendiente</small>
                                    <span class="fw-bold text-danger">${Number(sus.saldo).toFixed(2)} Bs.</span>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="/admin/suscripciones/ver/${sus.suscripcion_id}" 
                                       class="btn btn-sm btn-outline-danger rounded-pill" style="font-size: 0.7rem;">
                                        <i class="ri-money-dollar-circle-line me-1"></i>Pagar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            document.getElementById('modalDetalleDeudaBody').innerHTML = html;
            
            // Mostrar modal directamente sin usar Bootstrap
            var modalEl = document.getElementById('modalDetalleDeuda');
            modalEl.style.display = 'block';
            modalEl.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Crear backdrop si no existe
            var backdrop = document.querySelector('.modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        }
    });
    
    // Cerrar modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-close') || e.target.getAttribute('data-bs-dismiss') === 'modal') {
            var modalEl = document.getElementById('modalDetalleDeuda');
            modalEl.style.display = 'none';
            modalEl.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
        
        // También cerrar al hacer clic en el backdrop
        if (e.target.classList.contains('modal') && e.target.id === 'modalDetalleDeuda') {
            var modalEl = document.getElementById('modalDetalleDeuda');
            modalEl.style.display = 'none';
            modalEl.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    });
});
</script>

@endsection