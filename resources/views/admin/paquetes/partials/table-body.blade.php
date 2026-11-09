@forelse ($paquetes as $n => $paquete)
    <tr class="highlight-new" data-nombre="{{ strtolower($paquete->nombre) }}">
        <td class="text-center">
            <span class="badge bg-info-subtle text-info rounded-pill p-2">
                {{ $paquetes->firstItem() + $n }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="ri-stack-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $paquete->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>
            <p class="mb-0 text-muted">
                @if ($paquete->descripcion)
                    {{ Str::limit($paquete->descripcion, 80) }}
                @else
                    <span class="text-muted fst-italic">Sin descripción</span>
                @endif
            </p>
        </td>
        <td class="text-center">
            @php
                $planesCount = $paquete->planes_count ?? $paquete->planes->count();
            @endphp
            <span class="badge bg-success-subtle text-success rounded-pill planes-badge">
                {{ $planesCount }}
            </span>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('paquetes.ver')
                <a href="{{ route('admin.paquetes.ver', $paquete->id) }}" title="Ver Detalles"
                    class="btn btn-outline-info viewBtn">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
                @can('paquetes.editar')
                <button type="button" title="Editar Paquete" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($paquete)' data-bs-toggle="modal" data-bs-target="#modalModificar">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('paquetes.eliminar')
                <button type="button" title="Eliminar Paquete" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($paquete)' data-bs-toggle="modal" data-bs-target="#modalEliminar">
                    <i class="ri-delete-bin-line"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron paquetes</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer paquete</p>
                @can('paquetes.registrar')
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Paquete
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
