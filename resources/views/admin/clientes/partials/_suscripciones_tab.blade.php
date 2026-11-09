@php
    $tabId = $tabId ?? 'suscripcionesPills';
@endphp
<style>
    #{{ $tabId }} .nav-link.active .tab-text,
    #{{ $tabId }} .nav-link.active .tab-pct,
    #{{ $tabId }} .nav-link.active .tab-check {
        color: #fff !important;
    }
    #{{ $tabId }} .nav-link.active .progress {
        background: rgba(255,255,255,.3) !important;
    }
    #{{ $tabId }} .nav-link.active .badge {
        background: rgba(255,255,255,.2) !important;
        color: #fff !important;
    }
</style>
@if ($suscripciones->count())
    <ul class="nav nav-pills nav-fill gap-2 mb-4" id="{{ $tabId }}" role="tablist">
        @foreach ($suscripciones as $index => $sus)
            @php
                $sesiones = $sus->sesiones;
                $totalPlan = $sus->plan->cantidad_sesiones ?? 0;
                $realizadas = $sesiones->where('estado', 'realizada')->count();
                $pctSesiones = $totalPlan > 0 ? min(100, round(($realizadas / $totalPlan) * 100)) : 0;
                $precioBase = $sus->monto_total ?? ($sus->tarifa === 'especial' && $sus->plan->precio_especial ? $sus->plan->precio_especial : ($sus->plan->precio ?? 0));
                $totalPagado = $sus->pagos->sum('pago_bs');
                $totalDescuento = $sus->pagos->sum('descuento_bs');
                $saldoPendiente = max(0, $precioBase - $totalPagado - $totalDescuento);
                $estaPagado = $saldoPendiente <= 0;
                $planColor = ($totalPlan > 0 && $realizadas >= $totalPlan) && $estaPagado ? '#0d9488' : (($totalPlan > 0 && $realizadas >= $totalPlan) ? '#0d6efd' : '#0d9488');
            @endphp
            <li class="nav-item flex-grow-1" role="presentation">
                <button class="nav-link rounded-3 w-100 {{ $index === 0 ? 'active' : '' }}"
                    id="sus-tab-{{ $sus->id }}"
                    data-bs-toggle="tab"
                    data-bs-target="#sus-content-{{ $sus->id }}"
                    type="button" role="tab"
                    style="border:2px solid transparent;transition:all .2s;">
                    <div class="d-flex align-items-center gap-2 py-1">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                style="width:30px;height:30px;font-size:.7rem;background:linear-gradient(135deg,{{ $planColor }},#06b6d4);">
                                {{ $loop->iteration }}
                            </div>
                        </div>
                        <div class="flex-grow-1 min-w-0 text-start">
                            <div class="fw-semibold small text-truncate tab-text" style="color:#1a2d4c;">
                                {{ $sus->plan->paquete->nombre ?? '' }} {{ $sus->plan->nombre_plan ?? 'Sin plan' }}
                                @if ($sus->tarifa === 'especial')
                                    <span class="badge rounded-pill px-1" style="background:#fef3c7;color:#b45309;font-size:.55rem;vertical-align:middle;"><i class="ri-star-fill me-1"></i>Esp</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="progress rounded-pill flex-grow-1" style="height:4px;background:#e9ecef;">
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:{{ $pctSesiones }}%;background:linear-gradient(90deg,{{ $planColor }},#06b6d4);">
                                    </div>
                                </div>
                                <span class="fw-bold tab-pct" style="color:{{ $planColor }};font-size:.65rem;">{{ $pctSesiones }}%</span>
                                @if($saldoPendiente > 0)
                                    <span class="badge bg-danger rounded-pill" style="font-size:.55rem;">{{ number_format($saldoPendiente, 0) }}</span>
                                @else
                                    <i class="ri-check-double-line tab-check" style="color:#198754;font-size:.7rem;"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="suscripcionesContent">
        @foreach ($suscripciones as $index => $sus)
            @php
                $sesiones = $sus->sesiones;
                $totalPlan = $sus->plan->cantidad_sesiones ?? 0;
                $realizadas = $sesiones->where('estado', 'realizada')->count();
                $porRealizar = max(0, $totalPlan - $realizadas);
                $canceladas = $sesiones->where('estado', 'cancelada')->count();
                $asistidas = $sesiones->where('estado', 'realizada')->where('asistio_hijo', true)->count();
                $noAsist = $sesiones->where('estado', 'realizada')->where('asistio_hijo', false)->count();
                $pctSesiones = $totalPlan > 0 ? min(100, round(($realizadas / $totalPlan) * 100)) : 0;

                $precioBase = $sus->monto_total ?? ($sus->tarifa === 'especial' && $sus->plan->precio_especial ? $sus->plan->precio_especial : ($sus->plan->precio ?? 0));
                $totalPagado = $sus->pagos->sum('pago_bs');
                $totalDescuento = $sus->pagos->sum('descuento_bs');
                $saldoPendiente = max(0, $precioBase - $totalPagado - $totalDescuento);
                $estaPagado = $saldoPendiente <= 0;
                $pctPagado = $precioBase > 0 ? min(100, round(($totalPagado / $precioBase) * 100)) : 0;
                $pctDescuento = $precioBase > 0 ? min(100, round(($totalDescuento / $precioBase) * 100)) : 0;
                $pctPendiente = $precioBase > 0 ? min(100, round(($saldoPendiente / $precioBase) * 100)) : 0;

                $planCompletado = $totalPlan > 0 && $realizadas >= $totalPlan;
                $bordePlan = $planCompletado && $estaPagado ? '#0d9488' : ($planCompletado ? '#0d6efd' : ($sus->estado === 'cancelada' ? '#dc3545' : 'rgba(26,45,76,.1)'));
                $bgHeader = $planCompletado || $sus->estado === 'cancelada' ? substr($bordePlan, 0, 7) . '0d' : 'rgba(26,45,76,.04)';

                $servicios = $sesiones
                    ->map(function ($s) {
                        return $s->planServicio->servicio->nombre ?? ($s->servicio->nombre ?? null);
                    })
                    ->filter()
                    ->unique()
                    ->values();

                $estadoConfig = [
                    'programada' => ['color' => '#f59e0b', 'icon' => 'ri-calendar-line', 'label' => 'Programada'],
                    'confirmada' => ['color' => '#0d6efd', 'icon' => 'ri-calendar-check-line', 'label' => 'Confirmada'],
                    'realizada' => ['color' => '#198754', 'icon' => 'ri-check-double-line', 'label' => 'Realizada'],
                    'cancelada' => ['color' => '#dc3545', 'icon' => 'ri-close-circle-line', 'label' => 'Cancelada'],
                    'reprogramada' => ['color' => '#6c757d', 'icon' => 'ri-refresh-line', 'label' => 'Reprogramada'],
                ];

                $estadoBadgeColor = $sus->estado === 'completada' ? '#0d6efd' : ($sus->estado === 'cancelada' ? '#dc3545' : '#198754');
            @endphp

            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="sus-content-{{ $sus->id }}" role="tabpanel">
                <div class="rounded-4 overflow-hidden border mb-3" style="border-color:{{ $bordePlan }}!important;">
                    <div class="px-4 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background:{{ $bgHeader }};">
                        <div>
                            <span class="fw-bold small" style="color:#1a2d4c;">
                                <i class="ri-stack-line me-1" style="color:#0d9488;"></i>{{ $sus->plan->nombre_plan ?? 'Sin plan' }}
                            </span>
                            @if ($sus->plan->paquete->nombre ?? null)
                                <span class="badge ms-2" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;">{{ $sus->plan->paquete->nombre }}</span>
                            @endif
                            <span class="badge ms-1" style="background:{{ $estadoBadgeColor }}22;color:{{ $estadoBadgeColor }};font-size:.7rem;">{{ ucfirst($sus->estado ?? 'activa') }}</span>
                            @if ($sus->tarifa === 'especial')
                                <span class="badge ms-1" style="background:#fef3c7;color:#b45309;font-size:.65rem;"><i class="ri-star-fill me-1"></i>Especial</span>
                            @endif
                            @if ($estaPagado && $sus->estado !== 'cancelada')
                                <span class="badge ms-1" style="background:rgba(13,110,253,.12);color:#0d6efd;font-size:.7rem;"><i class="ri-check-double-line me-1"></i>Pagado</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small"><i class="ri-calendar-line me-1"></i>{{ \Carbon\Carbon::parse($sus->fecha_registro)->locale('es')->isoFormat('D MMM YYYY') }}</span>
                            @if ($porRealizar > 0)
                                <a href="{{ route('admin.suscripciones.show', $sus->id) }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold text-dark" style="font-size:.78rem;">
                                    <i class="ri-calendar-add-line me-1"></i>Agendar
                                </a>
                            @endif
                            <a href="{{ route('admin.suscripciones.show', $sus->id) }}" class="btn btn-sm rounded-pill px-3 fw-semibold" style="background:linear-gradient(135deg,#1a2d4c,#0d9488);color:#fff;font-size:.78rem;">
                                <i class="ri-eye-line me-1"></i>Ver
                            </a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="row g-3">
                            {{-- Sesiones --}}
                            <div class="col-md-6">
                                <div class="small fw-semibold text-muted mb-2">
                                    <i class="ri-calendar-check-line me-1" style="color:#0d9488;"></i>Sesiones
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <div class="rounded-3 p-2 text-center" style="background:rgba(26,45,76,.06);border:1px solid rgba(26,45,76,.1);">
                                            <div class="fw-bold small" style="color:#1a2d4c;">{{ $totalPlan }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">Total plan</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="rounded-3 p-2 text-center" style="background:rgba(25,135,84,.08);border:1px solid rgba(25,135,84,.2);">
                                            <div class="fw-bold small" style="color:#198754;">{{ $realizadas }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">Realizadas</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        @if ($porRealizar > 0)
                                            <a href="{{ route('admin.suscripciones.show', $sus->id) }}" class="rounded-3 p-2 text-center d-block text-decoration-none" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);">
                                                <div class="fw-bold small" style="color:#d97706;">{{ $porRealizar }}</div>
                                                <div class="small" style="color:#d97706;font-size:.7rem;">Por realizar ↗</div>
                                            </a>
                                        @else
                                            <div class="rounded-3 p-2 text-center" style="background:rgba(108,117,125,.06);border:1px solid rgba(108,117,125,.15);">
                                                <div class="fw-bold small" style="color:#6c757d;">0</div>
                                                <div class="text-muted" style="font-size:.7rem;">Por realizar</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-1 d-flex justify-content-between small text-muted">
                                    <span>Progreso sesiones</span><span>{{ $pctSesiones }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height:6px;">
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:{{ $pctSesiones }}%;background:linear-gradient(90deg,#1a2d4c,#0d9488);">
                                    </div>
                                </div>
                            </div>

                            {{-- Pagos --}}
                            <div class="col-md-6">
                                <div class="small fw-semibold text-muted mb-2">
                                    <i class="ri-money-dollar-circle-line me-1" style="color:#0d9488;"></i>Pagos
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <div class="rounded-3 p-2 text-center" style="background:rgba(26,45,76,.06);border:1px solid rgba(26,45,76,.1);">
                                            <div class="fw-bold small" style="color:#1a2d4c;">Bs {{ number_format($precioBase, 0) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">Precio plan</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="rounded-3 p-2 text-center" style="background:rgba(25,135,84,.08);border:1px solid rgba(25,135,84,.2);">
                                            <div class="fw-bold small" style="color:#198754;">Bs {{ number_format($totalPagado, 0) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">Total pagado</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        @if ($totalDescuento > 0)
                                            <div class="rounded-3 p-2 text-center" style="background:rgba(13,110,253,.08);border:1px solid rgba(13,110,253,.2);">
                                                <div class="fw-bold small" style="color:#0d6efd;">Bs {{ number_format($totalDescuento, 0) }}</div>
                                                <div class="text-muted" style="font-size:.7rem;">Descuento</div>
                                            </div>
                                        @else
                                            <div class="rounded-3 p-2 text-center" style="background:rgba(108,117,125,.06);border:1px solid rgba(108,117,125,.15);">
                                                <div class="fw-bold small" style="color:#6c757d;">Bs 0</div>
                                                <div class="text-muted" style="font-size:.7rem;">Descuento</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <div class="rounded-3 p-2 text-center" style="background:{{ $estaPagado ? 'rgba(25,135,84,.06)' : 'rgba(220,53,69,.08)' }};border:1px solid {{ $estaPagado ? 'rgba(25,135,84,.15)' : 'rgba(220,53,69,.2)' }};">
                                            <div class="fw-bold small" style="color:{{ $estaPagado ? '#198754' : '#dc3545' }};">Bs {{ number_format($saldoPendiente, 0) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">Pendiente</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 small mb-1">
                                    <span class="text-muted"><i class="ri-receipt-line me-1"></i>{{ $sus->pagos->count() }} pago(s)</span>
                                    @php
                                        $ultimoPago = $sus->pagos->sortByDesc('fecha_pago')->first();
                                    @endphp
                                    @if ($ultimoPago)
                                        <span class="text-muted"><i class="ri-calendar-line me-1"></i>Último: {{ \Carbon\Carbon::parse($ultimoPago->fecha_pago)->locale('es')->isoFormat('D MMM YYYY') }}</span>
                                    @endif
                                </div>
                                <div class="mb-1 d-flex justify-content-between small text-muted">
                                    <span>
                                        <span class="text-success">{{ $pctPagado }}% pagado</span>
                                        @if ($totalDescuento > 0)
                                            <span class="text-primary ms-2">{{ $pctDescuento }}% descuento</span>
                                        @endif
                                        <span class="text-danger ms-2">{{ $pctPendiente }}% pendiente</span>
                                    </span>
                                </div>
                                <div class="progress rounded-pill" style="height:6px;">
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:{{ $pctPagado }}%;background:linear-gradient(90deg,#198754,#0d9488);"
                                        data-bs-toggle="tooltip" title="Pagado: Bs {{ number_format($totalPagado, 2) }}">
                                    </div>
                                    @if ($totalDescuento > 0)
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:{{ $pctDescuento }}%;background:linear-gradient(90deg,#0d6efd,#6610f2);"
                                        data-bs-toggle="tooltip" title="Descuento: Bs {{ number_format($totalDescuento, 2) }}">
                                    </div>
                                    @endif
                                    @if ($saldoPendiente > 0)
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:{{ $pctPendiente }}%;background:linear-gradient(90deg,#dc3545,#f08080);"
                                        data-bs-toggle="tooltip" title="Pendiente: Bs {{ number_format($saldoPendiente, 2) }}">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Historial de Pagos --}}
                        @if ($sus->pagos->count() > 0)
                            <div class="mt-3 pt-3" style="border-top:1px solid #f0f0f0;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small fw-medium"><i class="ri-file-list-3-line me-1"></i>Historial de Pagos</span>
                                    @if ($saldoPendiente > 0)
                                        <a href="{{ route('admin.suscripciones.pagos.create', $sus->id) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="ri-money-dollar-circle-line me-1"></i>Registrar Pago
                                        </a>
                                    @endif
                                </div>
                                <div class="table-responsive" style="max-height:200px;">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0" style="font-size:.7rem;">#</th>
                                                <th class="border-0" style="font-size:.7rem;">Fecha</th>
                                                <th class="border-0" style="font-size:.7rem;">Tipo</th>
                                                <th class="border-0" style="font-size:.7rem;">Monto</th>
                                                <th class="border-0" style="font-size:.7rem;">Dto.</th>
                                                <th class="border-0" style="font-size:.7rem;">Recibo</th>
                                                <th class="border-0 text-center" style="font-size:.7rem;">PDF</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sus->pagos as $pago)
                                                <tr>
                                                    <td style="font-size:.72rem;">
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill p-1">{{ $loop->iteration }}</span>
                                                    </td>
                                                    <td style="font-size:.72rem;">
                                                        <i class="ri-calendar-line text-muted me-1"></i>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                                    </td>
                                                    <td style="font-size:.72rem;">
                                                        <span class="badge {{ $pago->tipo_pago === 'efectivo' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} rounded-pill" style="font-size:.65rem;">
                                                            {{ ucfirst($pago->tipo_pago) }}
                                                        </span>
                                                    </td>
                                                    <td style="font-size:.72rem;">
                                                        <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:.68rem;">
                                                            {{ number_format($pago->pago_bs, 2) }}
                                                        </span>
                                                    </td>
                                                    <td style="font-size:.72rem;">
                                                        @if ($pago->descuento_bs > 0)
                                                            <span class="badge bg-primary-subtle text-primary rounded-pill" style="font-size:.65rem;">
                                                                -{{ number_format($pago->descuento_bs, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted" style="font-size:.65rem;">—</span>
                                                        @endif
                                                    </td>
                                                    <td style="font-size:.72rem;">
                                                        <span class="badge bg-info-subtle text-info rounded-pill" style="font-size:.65rem;">
                                                            {{ $pago->recibo ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.suscripciones.reciboPdf', $pago->id) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-danger rounded-circle" style="width:26px;height:26px;padding:0;">
                                                            <i class="ri-file-pdf-line" style="font-size:.75rem;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="mt-3 pt-3" style="border-top:1px solid #f0f0f0;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small fw-medium"><i class="ri-file-list-3-line me-1"></i>Historial de Pagos</span>
                                    @if ($saldoPendiente > 0)
                                        <a href="{{ route('admin.suscripciones.pagos.create', $sus->id) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="ri-money-dollar-circle-line me-1"></i>Registrar Pago
                                        </a>
                                    @endif
                                </div>
                                <div class="text-center py-4 text-muted border rounded-4">
                                    <i class="ri-file-list-3-line fs-4 opacity-50"></i>
                                    <p class="mb-0 small">No hay pagos registrados</p>
                                </div>
                            </div>
                        @endif

                        {{-- Servicios --}}
                        @if ($servicios->count() > 0)
                            <div class="mt-3 pt-3" style="border-top:1px solid #f0f0f0;">
                                <div class="small text-muted mb-1"><i class="ri-heart-pulse-line me-1"></i>Servicios:</div>
                                <div class="mb-2">
                                    @foreach ($servicios as $sv)
                                        <span class="badge rounded-pill me-1" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;">{{ $sv }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Sesiones de la Suscripcion --}}
                        @if ($sesiones->count())
                            <div class="mt-3 pt-3" style="border-top:1px solid #f0f0f0;">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="ri-calendar-event-line" style="color:#0d9488;"></i>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size:.85rem;">Sesiones de la Suscripción</h6>
                                    <span class="badge bg-dark rounded-pill ms-1">{{ $sesiones->count() }}</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @foreach ($sesiones as $sesion)
                                        @php
                                            $cfg = $estadoConfig[$sesion->estado] ?? $estadoConfig['programada'];
                                            $servicio = $sesion->planServicio->servicio->nombre ?? ($sesion->servicio->nombre ?? '—');
                                        @endphp
                                        <div class="rounded-3 p-2 px-3 d-flex align-items-center gap-3 flex-wrap"
                                            style="background:#f8fafc;border-left:4px solid {{ $cfg['color'] }};">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                                style="width:32px;height:32px;background:{{ $cfg['color'] }};font-size:.75rem;">
                                                #{{ $sesion->n_sesion }}
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                                                <div class="fw-semibold small" style="color:{{ $cfg['color'] }};">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ $sesion->fecha ? \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('D MMM YYYY') : '—' }}
                                                    @if ($sesion->hora_inicio)
                                                        <span class="text-muted fw-normal ms-1">
                                                            <i class="ri-time-line me-1"></i>{{ substr($sesion->hora_inicio, 0, 5) }}
                                                            @if ($sesion->hora_fin) – {{ substr($sesion->hora_fin, 0, 5) }} @endif
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="small text-dark fw-medium">
                                                    <i class="ri-heart-pulse-line me-1 text-muted"></i>{{ $servicio }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="ri-user-star-line me-1"></i>{{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }} {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1 flex-shrink-0">
                                                <span class="badge rounded-pill" style="background:{{ $cfg['color'] }};font-size:.68rem;">
                                                    <i class="{{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                                </span>
                                                @if ($sesion->estado === 'realizada')
                                                    @if ($sesion->asistio_hijo === true || $sesion->asistio_hijo == 1)
                                                        <span class="badge rounded-pill" style="background:rgba(25,135,84,.15);color:#198754;font-size:.63rem;">
                                                            <i class="ri-checkbox-circle-line me-1"></i>Asistió
                                                        </span>
                                                    @elseif ($sesion->asistio_hijo === false || $sesion->asistio_hijo == 0)
                                                        <span class="badge rounded-pill" style="background:rgba(220,53,69,.12);color:#dc3545;font-size:.63rem;">
                                                            <i class="ri-close-circle-line me-1"></i>No asistió
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-3 pt-3 text-center py-4 text-muted border rounded-4" style="border-top:1px solid #f0f0f0;">
                                <i class="ri-calendar-event-line fs-4 opacity-50"></i>
                                <p class="mb-0 small">No hay sesiones registradas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="ri-calendar-check-line fs-1 text-muted opacity-50"></i>
        <h5 class="mt-3 text-muted">Este cliente no tiene suscripciones</h5>
        @can('suscripciones.registrar')
        <a href="{{ route('admin.suscripciones.create') }}" class="btn btn-primary mt-2 rounded-pill px-4">
            <i class="ri-add-line me-1"></i>Crear nueva suscripción
        </a>
        @endcan
    </div>
@endif
