@php
    $mesesNombre = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
@endphp

@forelse ($gastos as $n => $gasto)
    @php
        $cat = $gasto->categoria;
        $catNombre = $cat->nombre ?? 'Sin categoría';
        $esHijo = $cat && $cat->parent_id;
        $catPadreNombre = $esHijo && $cat->parent ? $cat->parent->nombre : null;
    @endphp
    <tr class="highlight-new">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $gastos->firstItem() + $n }}
            </span>
        </td>
        <td class="px-2">
            <div class="d-flex flex-column">
                <span class="fw-medium small">{{ $gasto->fecha_gasto->format('d/m/Y') }}</span>
                @if ($gasto->mes_correspondiente && $gasto->anio_correspondiente)
                    <span class="badge bg-info-subtle text-info px-2 py-1 mt-1" style="font-size: 0.7rem; width: fit-content;">
                        <i class="ri-calendar-event-line me-1"></i>{{ $mesesNombre[$gasto->mes_correspondiente] }} {{ $gasto->anio_correspondiente }}
                    </span>
                @endif
            </div>
        </td>
        <td class="px-2">
            <div class="d-flex align-items-center gap-1 flex-wrap">
                @if($esHijo && $catPadreNombre)
                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size:0.65rem;">
                        {{ $catPadreNombre }}
                    </span>
                    <i class="ri-arrow-right-s-line text-muted" style="font-size:0.7rem;"></i>
                    <span class="badge bg-danger-subtle text-danger px-2 py-1">
                        {{ $catNombre }}
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger px-2 py-1">
                        <i class="ri-price-tag-3-line me-1"></i>{{ $catNombre }}
                    </span>
                @endif
            </div>
        </td>
        <td class="px-2">
            <p class="mb-0 small text-wrap" style="min-width: 180px;">
                {{ Str::limit($gasto->descripcion, 80) }}
            </p>
        </td>
        <td class="text-end px-2">
            <span class="fw-semibold text-success fs-6">{{ number_format($gasto->monto_bs, 2) }} Bs</span>
        </td>
        <td class="px-2">
            <div class="d-flex flex-column gap-1">
                @if ($gasto->comprobante)
                    <span class="text-muted small fw-medium">{{ $gasto->comprobante }}</span>
                @endif
                @if ($gasto->compra_id)
                    <a href="{{ route('admin.compras.show', $gasto->compra_id) }}" class="badge bg-info-subtle text-info text-decoration-none d-inline-flex align-items-center gap-1" style="width: fit-content;">
                        <i class="ri-shopping-cart-line"></i>Compra #{{ $gasto->compra_id }}
                    </a>
                @else
                    <span class="badge bg-secondary-subtle text-secondary d-inline-flex align-items-center gap-1" style="width: fit-content;">
                        <i class="ri-edit-line"></i>Manual
                    </span>
                @endif
            </div>
        </td>
        <td class="text-center">
            @php
                $noEditable = $gasto->compra_id
                    || in_array(strtolower($catNombre), ['salarios', 'compra de productos']);
            @endphp
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" title="Ver Detalle" class="btn btn-outline-info detalleGastoBtn"
                    data-bs-obj='@json($gasto)'
                    data-bs-toggle="modal" data-bs-target="#modalDetalleGasto">
                    <i class="ri-eye-line"></i>
                </button>
                <button type="button" title="Editar Gasto" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json($gasto)'
                    data-bs-toggle="modal" data-bs-target="#modalModificar"
                    @if ($noEditable) disabled style="opacity: 0.5;" @endif>
                    <i class="ri-edit-line"></i>
                </button>
                <button type="button" title="Eliminar Gasto" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json($gasto)'
                    data-bs-toggle="modal" data-bs-target="#modalEliminar"
                    @if ($noEditable) disabled style="opacity: 0.5;" @endif>
                    <i class="ri-delete-bin-line"></i>
                </button>
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
                <h5 class="text-muted mb-2">No se encontraron gastos</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer gasto</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrar">
                    <i class="ri-add-line align-bottom me-1"></i> Registrar Primer Gasto
                </button>
            </div>
        </td>
    </tr>
@endforelse
