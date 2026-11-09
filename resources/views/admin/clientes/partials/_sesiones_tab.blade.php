@php
    $estadoConfig = [
        'programada'   => ['color' => '#f59e0b', 'icon' => 'ri-calendar-line',       'label' => 'Programada'],
        'confirmada'   => ['color' => '#0d6efd', 'icon' => 'ri-calendar-check-line', 'label' => 'Confirmada'],
        'realizada'    => ['color' => '#198754', 'icon' => 'ri-check-double-line',   'label' => 'Realizada'],
        'cancelada'    => ['color' => '#dc3545', 'icon' => 'ri-close-circle-line',   'label' => 'Cancelada'],
        'reprogramada' => ['color' => '#6c757d', 'icon' => 'ri-refresh-line',        'label' => 'Reprogramada'],
    ];

    $todasSesiones = collect();
    foreach ($cliente->suscripciones as $sus) {
        $todasSesiones = $todasSesiones->merge($sus->sesiones);
    }
    $todasSesiones = $todasSesiones->sortByDesc('fecha');
@endphp

@if ($todasSesiones->count())
    {{-- Resumen rápido --}}
    @php
        $realizadas  = $todasSesiones->where('estado','realizada')->count();
        $canceladas  = $todasSesiones->where('estado','cancelada')->count();
        $asistidas   = $todasSesiones->where('estado','realizada')->where('asistio_hijo', true)->count();
        $noAsistidas = $todasSesiones->where('estado','realizada')->where('asistio_hijo', false)->count();
        $totalPlan   = $cliente->suscripciones->sum(fn($s) => $s->plan->cantidad_sesiones ?? 0);
        $porRealizar = max(0, $totalPlan - $realizadas);
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(26,45,76,.06);border:1px solid rgba(26,45,76,.1);">
                <div class="fw-bold fs-4" style="color:#1a2d4c;">{{ $totalPlan }}</div>
                <div class="small text-muted">Total del plan</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(25,135,84,.08);border:1px solid rgba(25,135,84,.2);">
                <div class="fw-bold fs-4" style="color:#198754;">{{ $realizadas }}</div>
                <div class="small text-muted">Realizadas</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);">
                <div class="fw-bold fs-4" style="color:#d97706;">{{ $porRealizar }}</div>
                <div class="small text-muted">Por realizar</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);">
                <div class="fw-bold fs-4" style="color:#dc3545;">{{ $canceladas }}</div>
                <div class="small text-muted">Canceladas</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(25,135,84,.06);border:1px solid rgba(25,135,84,.15);">
                <div class="fw-bold fs-4" style="color:#198754;">{{ $asistidas }}</div>
                <div class="small text-muted">Asistió</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="rounded-3 p-3 text-center" style="background:rgba(220,53,69,.06);border:1px solid rgba(220,53,69,.15);">
                <div class="fw-bold fs-4" style="color:#dc3545;">{{ $noAsistidas }}</div>
                <div class="small text-muted">No asistió</div>
            </div>
        </div>
    </div>

    {{-- Listado --}}
    <div class="d-flex flex-column gap-2">
        @foreach ($todasSesiones as $sesion)
            @php
                $cfg      = $estadoConfig[$sesion->estado] ?? $estadoConfig['programada'];
                $servicio = $sesion->planServicio->servicio->nombre ?? $sesion->servicio->nombre ?? '—';
                $plan     = $sesion->suscripcion->plan->nombre_plan ?? '—';
                $paquete  = $sesion->suscripcion->plan->paquete->nombre ?? '—';
                $hijo     = trim(($sesion->hijo->persona->nombres ?? '') . ' ' . ($sesion->hijo->persona->apellido_paterno ?? ''));
            @endphp
            <div class="card border-0 shadow-sm" style="border-left:4px solid {{ $cfg['color'] }} !important;">
                <div class="card-body py-2 px-3">
                    <div class="row align-items-center g-2">

                        {{-- N° --}}
                        <div class="col-auto">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:38px;height:38px;min-width:38px;background:{{ $cfg['color'] }};font-size:.85rem;">
                                #{{ $sesion->n_sesion }}
                            </div>
                        </div>

                        {{-- Fecha y hora --}}
                        <div class="col-sm-2">
                            <div class="fw-semibold small" style="color:#1a2d4c;">
                                <i class="ri-calendar-line me-1" style="color:{{ $cfg['color'] }};"></i>
                                {{ $sesion->fecha ? \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('D MMM YYYY') : '—' }}
                            </div>
                            @if ($sesion->hora_inicio)
                                <div class="small text-muted">
                                    <i class="ri-time-line me-1"></i>{{ substr($sesion->hora_inicio, 0, 5) }}
                                    @if ($sesion->hora_fin)– {{ substr($sesion->hora_fin, 0, 5) }}@endif
                                </div>
                            @endif
                        </div>

                        {{-- Hijo --}}
                        <div class="col-sm-2">
                            <div class="small text-dark fw-semibold">
                                <i class="ri-baby-line me-1 text-muted"></i>{{ $hijo ?: '—' }}
                            </div>
                        </div>

                        {{-- Servicio / Plan / Paquete --}}
                        <div class="col-sm-3">
                            <div class="fw-semibold small text-dark">
                                <i class="ri-heart-pulse-line me-1 text-muted"></i>{{ $servicio }}
                            </div>
                            <div class="small text-muted text-truncate" style="max-width:200px;" title="{{ $plan }} · {{ $paquete }}">
                                <i class="ri-stack-line me-1"></i>{{ $plan }}
                                @if ($paquete !== '—')
                                    <span class="opacity-75">· {{ $paquete }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Fisioterapeuta --}}
                        <div class="col-sm-2">
                            <div class="small text-muted">
                                <i class="ri-user-star-line me-1"></i>
                                {{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }}
                                {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                            </div>
                        </div>

                        {{-- Estado + asistencia --}}
                        <div class="col-sm-2 text-sm-end">
                            <span class="badge rounded-pill" style="background:{{ $cfg['color'] }};font-size:.75rem;">
                                <i class="{{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                            </span>
                            @if ($sesion->estado === 'realizada')
                                <br>
                                @if ($sesion->asistio_hijo === true || $sesion->asistio_hijo == 1)
                                    <span class="badge rounded-pill mt-1" style="background:rgba(25,135,84,.15);color:#198754;font-size:.7rem;">
                                        <i class="ri-checkbox-circle-line me-1"></i>Asistió
                                    </span>
                                @elseif ($sesion->asistio_hijo === false || $sesion->asistio_hijo == 0)
                                    <span class="badge rounded-pill mt-1" style="background:rgba(220,53,69,.12);color:#dc3545;font-size:.7rem;">
                                        <i class="ri-close-circle-line me-1"></i>No asistió
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="ri-calendar-event-line fs-1 text-muted opacity-50"></i>
        <h5 class="mt-3 text-muted">No hay sesiones registradas para este cliente</h5>
    </div>
@endif
