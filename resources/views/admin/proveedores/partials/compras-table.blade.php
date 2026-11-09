@forelse ($compras as $compra)
    <tr class="highlight-new">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">#{{ $compra->id }}</span>
        </td>
        <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
        <td class="text-center fw-bold text-success">{{ number_format($compra->total_bs, 2) }} Bs</td>
        <td>
            @foreach ($compra->detalles as $detalle)
                {{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
        <td class="text-center">
            <a href="{{ route('admin.compras.show', $compra->id) }}" class="btn btn-sm btn-outline-primary"
                title="Ver detalle">
                <i class="ri-eye-line"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="empty-state">
                <i class="ri-truck-line fs-1 text-muted d-block mb-2"></i>
                <h6 class="text-muted">No hay compras registradas para este proveedor</h6>
            </div>
        </td>
    </tr>
@endforelse
