@forelse ($productos as $n => $producto)
    <tr class="highlight-new" data-nombre="{{ strtolower($producto->nombre) }}">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $productos->firstItem() + $n }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="ri-box-3-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-medium">{{ $producto->nombre }}</h6>
                    @if ($producto->marca)
                        <small class="text-muted">{{ $producto->marca }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td>
            <span class="text-muted">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
        </td>
        <td class="text-center">
            @if ($producto->precio_compra !== null)
                <span class="fw-semibold text-muted">{{ number_format($producto->precio_compra, 2) }} Bs</span>
            @else
                <span class="text-muted fst-italic">—</span>
            @endif
        </td>
        <td class="text-center">
            <span class="fw-semibold text-success">{{ number_format($producto->precio_venta, 2) }} Bs</span>
        </td>
        <td class="text-center">
            @if ($producto->stock_minimo > 0 && $producto->stock <= $producto->stock_minimo)
                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                    {{ $producto->stock }}
                </span>
            @else
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    {{ $producto->stock }}
                </span>
            @endif
        </td>
        <td class="text-center">
            @if ($producto->estado === 'activo')
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Activo</span>
            @else
                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Inactivo</span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('productos.editar')
                <button type="button" title="Editar Producto" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($producto)' data-bs-toggle="modal" data-bs-target="#modalModificar">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('productos.eliminar')
                <button type="button" title="Eliminar Producto" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($producto)' data-bs-toggle="modal" data-bs-target="#modalEliminar">
                    <i class="ri-delete-bin-line"></i>
                </button>
                @endcan
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
                <h5 class="text-muted mb-2">No se encontraron productos</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer producto</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Producto
                </button>
            </div>
        </td>
    </tr>
@endforelse
