<div class="table-responsive tabla-suscripciones">
    <table class="table table-hover align-middle mb-0" id="suscripcionesTable">
        <thead class="table-light">
            <tr>
                <th class="text-center col-num">#</th>
                <th class="col-nino"><i class="ri-user-follow-line me-1"></i>Niñ@</th>
                <th class="col-padre"><i class="ri-parent-line me-1"></i>Responsable</th>
                <th class="col-plan"><i class="ri-file-list-3-line me-1"></i>Plan</th>
                <th class="col-fecha"><i class="ri-calendar-line me-1"></i>Fecha</th>
                <th class="col-sesiones"><i class="ri-time-line me-1"></i>Sesiones</th>
                <th class="col-estado"><i class="ri-checkbox-circle-line me-1"></i>Estado</th>
                <th class="text-center col-acciones"><i class="ri-settings-4-line me-1"></i>Acciones</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse ($suscripciones as $item)
                @php
                    $primerHijo = optional($item->primeraSesion)->hijo;
                    $badgeClass =
                        [
                            'activa' => 'success',
                            'pausada' => 'warning',
                            'finalizada' => 'secondary',
                            'Activa' => 'success',
                            'Agotada' => 'secondary',
                        ][$item->estado] ?? 'secondary';
                    $totalPlan = $item->sesiones_iniciales;
                    $realizadas = $item->sesiones_realizadas ?? 0;
                    $programadas = $item->sesiones_programadas ?? 0;
                    $restantes = max(0, $totalPlan - $realizadas);
                    $progressWidth = $totalPlan > 0 ? round(($realizadas / $totalPlan) * 100) : 0;
                    $totalPagado = $item->pagos->sum('pago_bs');
                    $totalDescuento = $item->pagos->sum('descuento_bs');
                    $precioBase = $item->monto_total ?? ($item->tarifa === 'especial' && $item->plan->precio_especial ? $item->plan->precio_especial : ($item->plan->precio ?? 0));
                    $saldoPendiente = max(0, $precioBase - $totalPagado - $totalDescuento);
                    $iteracionGlobal = ($suscripciones->firstItem() ?? 0) + $loop->index;
                @endphp
                <tr data-tarifa="{{ $item->tarifa }}">
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary rounded-pill p-1" style="font-size:0.7rem;">{{ $iteracionGlobal }}</span>
                    </td>
                    <td class="px-1">
                        @if ($primerHijo)
                            <div class="fw-semibold text-truncate" style="font-size:0.68rem;" title="{{ $primerHijo->persona->nombres }}">{{ $primerHijo->persona->nombres }}</div>
                            <div class="text-truncate" style="font-size:0.68rem;">{{ $primerHijo->persona->apellido_paterno }}</div>
                            <small class="text-muted">{{ $primerHijo->persona->carnet ?? 'S/CI' }}</small>
                        @else
                            <span class="text-muted fst-italic small">Sin asignar</span>
                        @endif
                    </td>
                    <td class="px-1">
                        <div class="fw-semibold text-truncate" style="font-size:0.68rem;" title="{{ $item->cliente->persona->nombres }}">{{ $item->cliente->persona->nombres }}</div>
                        <div class="text-truncate" style="font-size:0.68rem;">{{ $item->cliente->persona->apellido_paterno }}</div>
                        <small class="text-muted">{{ $item->cliente->persona->carnet }}</small>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary rounded-pill"
                            style="font-size:0.7rem;"
                            title="{{ $item->plan->paquete->nombre ?? 'Sin paquete' }}">
                            <i class="ri-stack-line me-1"></i>{{ Str::limit($item->plan->paquete->nombre ?? 'Sin paquete', 12) }}
                        </span>
                        <span class="badge bg-success-subtle text-success rounded-pill d-block mt-1"
                            style="font-size:0.7rem;" title="{{ $item->plan->nombre_plan }}">
                            <i class="ri-file-list-3-line me-1"></i>{{ Str::limit($item->plan->nombre_plan, 12) }}
                        </span>
                        <span class="badge d-block mt-1 rounded-pill"
                            style="font-size:.62rem;{{ $item->tarifa === 'especial' ? 'background:#fef3c7;color:#b45309;' : 'background:#f0fdf4;color:#16a34a;' }}">
                            <i class="ri-{{ $item->tarifa === 'especial' ? 'star-fill' : 'sun-line' }} me-1"></i>{{ ucfirst($item->tarifa) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size:0.7rem;">
                            <i class="ri-calendar-event-line text-muted"></i>
                            <span>{{ \Carbon\Carbon::parse($item->fecha_registro)->format('d/m/Y') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap mb-1">
                            <span class="badge bg-success-subtle text-success rounded-pill"
                                style="font-size:.65rem;" title="Realizadas">
                                <i class="ri-check-double-line"></i> {{ $realizadas }}
                            </span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill"
                                style="font-size:.65rem;" title="Programadas">
                                <i class="ri-calendar-line"></i> {{ $programadas }}
                            </span>
                            <span class="badge bg-warning-subtle text-warning rounded-pill"
                                style="font-size:.65rem;" title="Restantes">
                                <i class="ri-time-line"></i> {{ $restantes }}
                            </span>
                        </div>
                        <div class="progress" style="width:55px;height:4px;" title="{{ $progressWidth }}% completado">
                            <div class="progress-bar bg-success" style="width:{{ $progressWidth }}%"></div>
                        </div>
                    </td>
                    <td>
                        @if ($realizadas >= $totalPlan && $totalPlan > 0)
                            <span class="badge bg-success-subtle text-success rounded-pill d-block mb-1"
                                style="font-size:.7rem;">
                                <i class="ri-checkbox-circle-line me-1"></i>Ses. cumplidas
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning rounded-pill d-block mb-1"
                                style="font-size:.7rem;">
                                <i class="ri-time-line me-1"></i>Por realizar
                            </span>
                        @endif
                        @if ($saldoPendiente > 0)
                            <span class="badge bg-danger-subtle text-danger rounded-pill"
                                style="font-size:.62rem;"
                                title="Saldo pendiente: Bs {{ number_format($saldoPendiente, 2) }}">
                                <i class="ri-error-warning-line"></i> Bs {{ number_format($saldoPendiente, 2) }}
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success rounded-pill"
                                style="font-size:.62rem;">
                                <i class="ri-checkbox-circle-line"></i> Pagado
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            @can('suscripciones.ver')
                            <a href="{{ route('admin.suscripciones.show', $item->id) }}"
                                class="btn btn-outline-info" title="Ver">
                                <i class="ri-eye-line"></i>
                            </a>
                            @endcan
                            @can('suscripciones.registrar.pago')
                                @if ($saldoPendiente > 0)
                                    <a href="{{ route('admin.suscripciones.pagos.create', $item->id) }}"
                                        class="btn btn-outline-success" title="Registrar Pago">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </a>
                                @endif
                            @endcan
                            @can('suscripciones.editar')
                            <a href="{{ route('admin.suscripciones.edit', $item->id) }}"
                                class="btn btn-outline-primary" title="Editar">
                                <i class="ri-edit-line"></i>
                            </a>
                            @endcan
                            @can('suscripciones.eliminar')
                            <button type="button" class="btn btn-outline-danger delete-btn"
                                data-id="{{ $item->id }}"
                                data-url="{{ route('admin.suscripciones.destroy', $item->id) }}"
                                data-nombre="Suscripción #{{ $item->id }}" title="Eliminar">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                <i class="ri-search-line"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">No se encontraron resultados</h5>
                        <p class="text-muted small mb-0">Intenta con otro término o limpia los filtros.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($suscripciones->total() > 0)
    <div class="card-footer bg-white border-top py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-sm bg-primary bg-opacity-10 rounded-3">
                    <i class="ri-file-list-3-line text-primary"></i>
                </div>
                <span class="text-muted" style="font-size:0.82rem;">
                    Mostrando <span class="fw-bold text-primary">{{ $suscripciones->firstItem() }}</span>
                    - <span class="fw-bold text-primary">{{ $suscripciones->lastItem() }}</span>
                    de <span class="fw-bold text-primary">{{ $suscripciones->total() }}</span> registros
                </span>
            </div>
            <div>
                {{ $suscripciones->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
