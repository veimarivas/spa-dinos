@forelse ($categorias as $index => $categoria)
    <tr class="highlight-new">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $categorias->firstItem() + $index }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $categoria->nombre }}</h6>
                </div>
            </div>
        </td>
        <td class="text-muted">{{ $categoria->descripcion ?? '-' }}</td>
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                {{ $categoria->productos_count }}
                {{ $categoria->productos_count === 1 ? 'producto' : 'productos' }}
            </span>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('categorias.ver')
                <a href="{{ route('admin.categorias-productos.ver', $categoria->id) }}" title="Ver productos"
                    class="btn btn-outline-info">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
                @can('categorias.editar')
                <button type="button" title="Editar Categoría" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($categoria)' data-bs-toggle="modal" data-bs-target="#modalModificar">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('categorias.eliminar')
                <button type="button" title="Eliminar Categoría" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($categoria)' data-bs-toggle="modal" data-bs-target="#modalEliminar">
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
                    <i class="ri-price-tag-3-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron categorías</h5>
                <p class="text-muted mb-4">Comienza registrando tu primera categoría de producto</p>
                @can('categorias.registrar')
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primera Categoría
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
