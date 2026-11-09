@forelse ($servicios as $n => $servicio)
    <tr class="highlight-new" data-nombre="{{ strtolower($servicio->nombre) }}">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $servicios->firstItem() + $n }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-service-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $servicio->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            <p class="mb-0 text-muted">
                @if ($servicio->descripcion)
                    {{ Str::limit($servicio->descripcion, 80) }}
                @else
                    <span class="text-muted fst-italic">Sin descripción</span>
                @endif
            </p>
        </td>
        <td class="text-center">
            @if ($servicio->pago_con_sesion !== null)
                <span class="fw-semibold text-success">S/ {{ number_format($servicio->pago_con_sesion, 2) }}</span>
            @else
                <span class="text-muted fst-italic">—</span>
            @endif
        </td>
        <td class="text-center">
            @if ($servicio->pago_sin_sesion !== null)
                <span class="fw-semibold text-primary">S/ {{ number_format($servicio->pago_sin_sesion, 2) }}</span>
            @else
                <span class="text-muted fst-italic">—</span>
            @endif
        </td>
        <td class="text-center">
            @if ($servicio->pago_especial !== null)
                <span class="fw-semibold text-warning">S/ {{ number_format($servicio->pago_especial, 2) }}</span>
            @else
                <span class="text-muted fst-italic">—</span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('servicios.editar')
                <button type="button" title="Editar Servicio" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($servicio)' data-bs-toggle="modal" data-bs-target="#modalModificar">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('servicios.eliminar')
                <button type="button" title="Eliminar Servicio" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($servicio)' data-bs-toggle="modal" data-bs-target="#modalEliminar">
                    <i class="ri-delete-bin-line"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron servicios</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer servicio</p>
                @can('servicios.registrar')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Servicio
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
