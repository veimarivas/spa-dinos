@php
    $estadoConfig = [
        'programada'   => ['color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.1)',  'icon' => 'ri-calendar-line',       'label' => 'Programada'],
        'confirmada'   => ['color' => '#0d6efd', 'bg' => 'rgba(13,110,253,.1)',  'icon' => 'ri-calendar-check-line', 'label' => 'Confirmada'],
        'realizada'    => ['color' => '#198754', 'bg' => 'rgba(25,135,84,.1)',   'icon' => 'ri-check-double-line',   'label' => 'Realizada'],
        'cancelada'    => ['color' => '#dc3545', 'bg' => 'rgba(220,53,69,.1)',   'icon' => 'ri-close-circle-line',   'label' => 'Cancelada'],
        'reprogramada' => ['color' => '#6c757d', 'bg' => 'rgba(108,117,125,.1)', 'icon' => 'ri-refresh-line',        'label' => 'Reprogramada'],
    ];
@endphp

@if ($hijo->sesiones->count())
    {{-- Resumen rápido --}}
    @php
        $sesiones    = $hijo->sesiones;
        $realizadas  = $sesiones->where('estado','realizada')->count();
        $canceladas  = $sesiones->where('estado','cancelada')->count();
        $asistidas   = $sesiones->where('estado','realizada')->where('asistio_hijo', true)->count();
        $noAsistidas = $sesiones->where('estado','realizada')->where('asistio_hijo', false)->count();
        // Total del plan = suma de cantidad_sesiones de cada suscripción única
        $totalPlan   = $sesiones->unique('suscripcione_id')
                           ->sum(fn($s) => $s->suscripcion->plan->cantidad_sesiones ?? 0);
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

    {{-- Listado de sesiones --}}
    <div class="d-flex flex-column gap-2">
        @foreach ($hijo->sesiones as $sesion)
            @php
                $cfg      = $estadoConfig[$sesion->estado] ?? $estadoConfig['programada'];
                $servicio = $sesion->planServicio->servicio->nombre ?? $sesion->servicio->nombre ?? '—';
                $plan     = $sesion->suscripcion->plan->nombre_plan ?? '—';
                $paquete  = $sesion->suscripcion->plan->paquete->nombre ?? '—';
            @endphp
            <div class="card border-0 shadow-sm"
                style="border-left:4px solid {{ $cfg['color'] }} !important;border-left-style:solid !important;">
                <div class="card-body py-2 px-3">
                    <div class="row align-items-center g-2">

                        {{-- N° y estado --}}
                        <div class="col-auto">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:38px;height:38px;min-width:38px;background:{{ $cfg['color'] }};font-size:.85rem;">
                                #{{ $sesion->n_sesion }}
                            </div>
                        </div>

                        {{-- Fecha y hora --}}
                        <div class="col-sm-3">
                            <div class="fw-semibold small" style="color:#1a2d4c;">
                                <i class="ri-calendar-line me-1" style="color:{{ $cfg['color'] }};"></i>
                                {{ $sesion->fecha ? \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('D MMM YYYY') : '—' }}
                            </div>
                            @if ($sesion->hora_inicio)
                                <div class="small text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    {{ substr($sesion->hora_inicio, 0, 5) }}
                                    @if ($sesion->hora_fin)– {{ substr($sesion->hora_fin, 0, 5) }}@endif
                                </div>
                            @endif
                        </div>

                        {{-- Servicio / Plan / Paquete --}}
                        <div class="col-sm-3">
                            <div class="fw-semibold small text-dark">
                                <i class="ri-heart-pulse-line me-1 text-muted"></i>{{ $servicio }}
                            </div>
                            <div class="small text-muted text-truncate" style="max-width:200px;" title="{{ $plan }} · {{ $paquete }}">
                                <i class="ri-stack-line me-1"></i>{{ $plan }}
                                @if ($paquete !== '—')
                                    <span class="text-muted opacity-75">· {{ $paquete }}</span>
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
                            <span class="badge rounded-pill"
                                style="background:{{ $cfg['color'] }};font-size:.75rem;">
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
        <h5 class="mt-3 text-muted">Este niño no tiene sesiones registradas</h5>
    </div>
@endif
