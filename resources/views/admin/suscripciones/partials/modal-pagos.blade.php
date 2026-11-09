<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Recibo</th>
                <th>Fecha</th>
                <th>Detalle</th>
                <th>Total</th>
                <th>Descuento</th>
                <th>Trabajador</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pago)
                <tr>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <i class="ri-receipt-line me-1"></i>{{ $pago->recibo }}
                        </span>
                    </td>
                    <td>
                        <i class="ri-calendar-line me-1 text-muted"></i>
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->locale('es')->format('d/m/Y') }}
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            @forelse($pago->detalles as $detalle)
                                <span
                                    class="badge {{ $detalle->tipo_pago === 'efectivo' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-info bg-opacity-10 text-info' }} px-3 py-1 rounded-pill">
                                    <i
                                        class="ri-{{ $detalle->tipo_pago === 'efectivo' ? 'money-dollar-circle' : 'qr-code' }}-line me-1"></i>
                                    {{ number_format($detalle->pago_bs, 2) }} Bs. ({{ ucfirst($detalle->tipo_pago) }})
                                </span>
                            @empty
                                <span class="text-muted">Sin detalles</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="fw-semibold text-success">
                        {{ number_format($pago->pago_bs, 2) }} Bs.
                    </td>
                    <td class="text-danger">
                        {{ number_format($pago->descuento_bs, 2) }} Bs.
                    </td>
                    <td>
                        @if ($pago->trabajador)
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2">
                                    <span class="avatar-title text-primary">
                                        <i class="ri-user-line"></i>
                                    </span>
                                </div>
                                <span class="text-truncate" style="max-width: 150px;">
                                    {{ $pago->trabajador->persona->nombres }}
                                    {{ $pago->trabajador->persona->apellido_paterno }}
                                </span>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.suscripciones.reciboPdf', $pago->id) }}"
                                class="btn btn-sm btn-icon btn-outline-danger rounded-circle"
                                title="Descargar Recibo PDF" target="_blank">
                                <i class="ri-file-pdf-line"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                        </div>
                        <h6 class="text-muted">No hay pagos registrados</h6>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="3" class="text-end">Total Plan:</th>
                <td colspan="4" class="fw-bold">
                    {{ number_format($precioTotal, 2) }} Bs.
                    @if($suscripcion->tarifa === 'especial')
                        <span class="badge rounded-pill ms-1" style="background:#fef3c7;color:#b45309;font-size:.62rem;">
                            <i class="ri-star-fill me-1"></i>Especial
                        </span>
                    @else
                        <span class="badge rounded-pill ms-1" style="background:#f0fdf4;color:#16a34a;font-size:.62rem;">
                            <i class="ri-sun-line me-1"></i>Normal
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th colspan="3" class="text-end text-success">Total Pagado:</th>
                <td colspan="4" class="fw-bold text-success">{{ number_format($totalPagado, 2) }} Bs.</td>
            </tr>
            @if ($totalDescuento > 0)
                <tr>
                    <th colspan="3" class="text-end text-danger">Total Descuentos:</th>
                    <td colspan="4" class="fw-bold text-danger">-{{ number_format($totalDescuento, 2) }} Bs.</td>
                </tr>
            @endif
            <tr class="table-primary">
                <th colspan="3" class="text-end">Saldo Pendiente:</th>
                <td colspan="4" class="fw-bold {{ $saldoPendiente > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($saldoPendiente, 2) }} Bs.
                </td>
            </tr>
        </tfoot>
    </table>
</div>
