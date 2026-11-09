@forelse ($planes as $n => $plan)
    <tr data-id="{{ $plan->id }}">
        <td class="text-center">
            <span class="badge bg-info-subtle text-info rounded-pill p-2">
                {{ $planes->firstItem() + $n }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $plan->nombre_plan }}</h6>
                </div>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill">
                {{ $plan->cantidad_sesiones }}
            </span>
        </td>
        <td class="text-center fw-bold text-success">
            Bs {{ number_format($plan->precio, 2) }}
        </td>
        <td class="text-center">
            @if ($plan->precio_especial)
                <span class="fw-semibold" style="color:#0d9488;">Bs {{ number_format($plan->precio_especial, 2) }}</span>
            @else
                <span class="text-muted fst-italic">—</span>
            @endif
        </td>
        <td>
            @forelse ($plan->servicios as $servicio)
                <span class="badge bg-info-subtle text-info me-1 mb-1" title="{{ $servicio->descripcion }}">
                    {{ $servicio->nombre }} ({{ $servicio->pivot->cantidad_sesiones }})
                </span>
            @empty
                <span class="text-muted fst-italic">—</span>
            @endforelse
        </td>
        <td>
            <p class="mb-0 text-muted">
                @if ($plan->descripcion)
                    {{ Str::limit($plan->descripcion, 50) }}
                @else
                    <span class="text-muted fst-italic">Sin descripción</span>
                @endif
            </p>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" title="Editar Plan" class="btn btn-outline-primary editPlanBtn"
                    data-bs-obj='@json($plan)' data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                    <i class="ri-edit-line"></i>
                </button>
                <button type="button" title="Gestionar Servicios" class="btn btn-outline-success manageServiciosBtn"
                    data-plan-id="{{ $plan->id }}" data-plan-name="{{ $plan->nombre_plan }}" data-bs-toggle="modal"
                    data-bs-target="#serviciosPlanModal">
                    <i class="ri-service-line"></i>
                </button>
                <button type="button" title="Eliminar Plan" class="btn btn-outline-danger deletePlanBtn"
                    data-bs-obj='@json($plan)' data-bs-toggle="modal"
                    data-bs-target="#eliminarPlanModal">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron planes</h5>
                <p class="text-muted mb-4">Este paquete aún no tiene planes asociados</p>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#registrarPlanModal">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Plan
                </button>
            </div>
        </td>
    </tr>
@endforelse
