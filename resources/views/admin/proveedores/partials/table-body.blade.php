@forelse ($proveedores as $index => $proveedor)
    <tr class="highlight-new">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $proveedores->firstItem() + $index }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-truck-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $proveedor->nombre }}</h6>
                </div>
            </div>
        </td>
        <td>{{ $proveedor->nit ?? '-' }}</td>
        <td>{{ $proveedor->contacto_nombre ?? '-' }}</td>
        <td>{{ $proveedor->celular ?? '-' }}</td>
        <td class="text-center">
            <span
                class="badge bg-{{ $proveedor->estado === 'activo' ? 'success' : 'danger' }}-subtle text-{{ $proveedor->estado === 'activo' ? 'success' : 'danger' }}">
                {{ ucfirst($proveedor->estado) }}
            </span>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('proveedores.ver')
                <a href="{{ route('admin.proveedores.ver', $proveedor->id) }}" title="Ver detalles"
                    class="btn btn-outline-info">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
                @can('proveedores.editar')
                <button type="button" title="Editar Proveedor" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($proveedor)' data-bs-toggle="modal" data-bs-target="#modalModificar">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('proveedores.eliminar')
                <button type="button" title="Eliminar Proveedor" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($proveedor)' data-bs-toggle="modal" data-bs-target="#modalEliminar">
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
                    <i class="ri-truck-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron proveedores</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer proveedor</p>
                @can('proveedores.registrar')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Proveedor
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
