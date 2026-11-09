@forelse ($ventas as $n => $venta)
    @php
        $tieneSesion = !is_null($venta->sesione_id) && $venta->sesion;
    @endphp
    <tr>
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $ventas->firstItem() + $n }}
            </span>
        </td>
        <td>
            <span class="fw-medium">{{ $venta->fecha_venta->format('d/m/Y') }}</span>
            <br>
            <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
        </td>
        <td>
            @if ($venta->cliente && $venta->cliente->persona)
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="ri-user-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-medium">{{ $venta->cliente->persona->nombres }} {{ $venta->cliente->persona->apellido_paterno }}</h6>
                        <small class="text-muted">CI: {{ $venta->cliente->persona->carnet ?? 'N/A' }}</small>
                    </div>
                </div>
            @else
                <span class="text-muted fst-italic">
                    <i class="ri-store-2-line me-1"></i>Sin cliente
                </span>
            @endif
        </td>
        <td class="text-center">
            @if ($tieneSesion)
                <span class="badge rounded-pill px-3 py-2"
                    style="background:rgba(124,58,237,.1);color:#7c3aed;border:1px solid rgba(124,58,237,.25);"
                    title="Vinculada a sesión #{{ $venta->sesion->n_sesion ?? '?' }}">
                    <i class="ri-calendar-check-line me-1"></i>Por sesión
                </span>
                @if($venta->sesion->n_sesion)
                    <div class="mt-1">
                        <small class="text-muted" style="font-size:.7rem;">
                            <i class="ri-hashtag"></i>Sesión {{ $venta->sesion->n_sesion }}
                            @if($venta->sesion->planServicio?->servicio?->nombre)
                                · {{ Str::limit($venta->sesion->planServicio->servicio->nombre, 14) }}
                            @endif
                        </small>
                    </div>
                @endif
            @else
                <span class="badge rounded-pill px-3 py-2"
                    style="background:rgba(13,148,136,.1);color:#0d9488;border:1px solid rgba(13,148,136,.25);"
                    title="Venta directa sin sesión asociada">
                    <i class="ri-store-2-line me-1"></i>Directa
                </span>
            @endif
        </td>
        <td class="text-center">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                {{ $venta->detalles->count() }}
            </span>
        </td>
        <td class="text-center">
            <span class="fw-bold text-success">{{ number_format($venta->total_bs, 2) }} Bs</span>
        </td>
        <td class="text-center">
            @if ($venta->tipo_pago === 'efectivo')
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    <i class="ri-money-dollar-circle-line me-1"></i>Efectivo
                </span>
            @elseif ($venta->tipo_pago === 'qr')
                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                    <i class="ri-qr-code-line me-1"></i>QR
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="ri-exchange-line me-1"></i>Parcial
                </span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('ventas.ver')
                <a href="{{ route('admin.ventas.show', $venta->id) }}" class="btn btn-outline-primary"
                    title="Ver Detalle">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
                <a href="{{ route('admin.ventas.reciboPdf', $venta->id) }}" class="btn btn-outline-danger"
                    target="_blank" rel="noopener"
                    title="Ver recibo PDF">
                    <i class="ri-file-pdf-line"></i>
                </a>
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
                <h5 class="text-muted mb-2">No se encontraron ventas</h5>
                <p class="text-muted mb-4">Comienza registrando tu primera venta</p>
                <a href="{{ route('admin.ventas.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-bottom me-1"></i> Registrar Primera Venta
                </a>
            </div>
        </td>
    </tr>
@endforelse
