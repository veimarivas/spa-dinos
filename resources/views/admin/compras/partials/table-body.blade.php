@forelse ($compras as $index => $compra)
    <tr class="highlight-new">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $compras->firstItem() + $index }}
            </span>
        </td>
        <td>
            <span class="fw-medium">{{ $compra->fecha_compra->format('d/m/Y') }}</span>
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
                    <h6 class="mb-0 fw-semibold">{{ $compra->proveedor->nombre ?? 'Sin proveedor' }}</h6>
                    @if($compra->proveedor && $compra->proveedor->nit)
                        <small class="text-muted">NIT: {{ $compra->proveedor->nit }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                {{ $compra->detalles->count() }} {{ $compra->detalles->count() === 1 ? 'producto' : 'productos' }}
            </span>
        </td>
        <td class="text-center">
            <span class="fw-bold text-success fs-6">{{ number_format($compra->total_bs, 2) }} Bs</span>
        </td>
        <td class="text-center">
            <a href="{{ route('admin.compras.show', $compra->id) }}" title="Ver Detalle"
                class="btn btn-sm btn-outline-primary">
                <i class="ri-eye-line me-1"></i> Ver
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-shopping-cart-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron compras</h5>
                <p class="text-muted mb-4">Comienza registrando tu primera compra</p>
                <a href="{{ route('admin.compras.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-bottom me-1"></i> Registrar Primera Compra
                </a>
            </div>
        </td>
    </tr>
@endforelse
