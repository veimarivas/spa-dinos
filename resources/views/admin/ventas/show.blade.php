@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-4 p-4 shadow-sm">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-file-list-3-line me-2"></i>Detalle de Venta #{{ $venta->id }}
                        </h4>
                        <p class="text-white-50 mb-0">Informaci&oacute;n completa de la venta registrada</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.ventas.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Info General -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-header bg-primary-subtle border-0 py-3 px-4 rounded-top-4">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ri-information-line me-2"></i>Informaci&oacute;n General
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="text-muted fw-medium d-block mb-1">
                                <i class="ri-user-line me-1"></i>Cliente
                            </label>
                            @if ($venta->cliente && $venta->cliente->persona)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $venta->cliente->persona->nombres }}
                                            {{ $venta->cliente->persona->apellido_paterno }}
                                            {{ $venta->cliente->persona->apellido_materno }}
                                        </h6>
                                        <small class="text-muted">
                                            CI: {{ $venta->cliente->persona->carnet ?? 'N/A' }}
                                            @if ($venta->cliente->persona->celular)
                                                | Cel: {{ $venta->cliente->persona->celular }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted fst-italic">
                                    <i class="ri-store-2-line me-1"></i>Venta directa (sin cliente)
                                </span>
                            @endif
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="text-muted fw-medium d-block mb-1">
                                    <i class="ri-calendar-line me-1"></i>Fecha
                                </label>
                                <span class="fw-semibold">{{ $venta->fecha_venta->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted fw-medium d-block mb-1">
                                    <i class="ri-bank-card-line me-1"></i>Tipo de Pago
                                </label>
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
                            </div>
                        </div>

                        @if ($venta->observacion)
                            <div class="mt-4">
                                <label class="text-muted fw-medium d-block mb-1">
                                    <i class="ri-chat-3-line me-1"></i>Observaci&oacute;n
                                </label>
                                <p class="mb-0 bg-light rounded-3 p-3">{{ $venta->observacion }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-header bg-success-subtle border-0 py-3 px-4 rounded-top-4">
                        <h5 class="card-title mb-0 fw-bold text-success">
                            <i class="ri-box-3-line me-2"></i>Productos Vendidos
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio Unit.</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($venta->detalles as $i => $detalle)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                                                    {{ $i + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <div class="avatar-xs">
                                                            <div
                                                                class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                                                <i class="ri-box-3-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</h6>
                                                        @if ($detalle->producto && $detalle->producto->marca)
                                                            <small class="text-muted">{{ $detalle->producto->marca }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                    {{ $detalle->cantidad }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-medium">
                                                {{ number_format($detalle->precio_unitario, 2) }} Bs
                                            </td>
                                            <td class="text-center fw-bold text-success">
                                                {{ number_format($detalle->subtotal, 2) }} Bs
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 rounded-bottom-4">
                        @php
                            $subtotalGeneral = $venta->detalles->sum('subtotal');
                        @endphp
                        <div class="row justify-content-end">
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td class="text-end text-muted fw-medium py-1">Subtotal:</td>
                                        <td class="text-end fw-semibold py-1">{{ number_format($subtotalGeneral, 2) }} Bs
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end text-muted fw-medium py-1">Descuento:</td>
                                        <td class="text-end fw-semibold text-danger py-1">
                                            -{{ number_format($venta->descuento_bs, 2) }} Bs
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="text-end fw-bold fs-5 py-2">Total:</td>
                                        <td class="text-end fw-bold fs-5 text-success py-2">
                                            {{ number_format($venta->total_bs, 2) }} Bs
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
