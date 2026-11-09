@extends('admin.dashboard')

@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-4 p-4 shadow-sm">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-file-list-3-line me-2"></i>Detalle de Compra #{{ $compra->id }}
                        </h4>
                        <p class="text-white-50 mb-0">Visualiza toda la informaci&oacute;n de esta compra</p>
                    </div>
                    @can('compras.listar')
                    <div class="page-title-right">
                        <a href="{{ route('admin.compras.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Info Cards Row -->
        <div class="row mb-4">
            <!-- Proveedor Card -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-truck-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Proveedor</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $compra->proveedor->nombre ?? 'Sin proveedor' }}</h6>
                                @if($compra->proveedor && $compra->proveedor->nit)
                                    <small class="text-muted">NIT: {{ $compra->proveedor->nit }}</small>
                                @endif
                                @if($compra->proveedor && $compra->proveedor->celular)
                                    <br><small class="text-info"><i class="ri-phone-line me-1"></i>{{ $compra->proveedor->celular }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fecha Card -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle fs-24">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Fecha de Compra</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $compra->fecha_compra->format('d/m/Y') }}</h6>
                                <small class="text-muted">{{ $compra->fecha_compra->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Card -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Total de la Compra</p>
                                <h5 class="mb-0 fw-bold text-success">{{ number_format($compra->total_bs, 2) }} Bs</h5>
                                <small class="text-muted">{{ $compra->detalles->count() }} producto(s)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observacion -->
        @if($compra->observacion)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                        <i class="ri-chat-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Observaci&oacute;n</h6>
                                <p class="text-muted mb-0">{{ $compra->observacion }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Gasto Badge -->
        @if($compra->gasto)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success d-flex align-items-center rounded-4 shadow-sm" role="alert">
                    <i class="ri-checkbox-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Gasto registrado autom&aacute;ticamente</strong> - Se cre&oacute; un registro de gasto por <strong>{{ number_format($compra->gasto->monto_bs, 2) }} Bs</strong>
                        en la categor&iacute;a "{{ $compra->gasto->categoria->nombre ?? 'Sin categor&iacute;a' }}".
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Products Detail Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm rounded-4">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-primary-subtle rounded-top-4">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ri-box-3-line me-2"></i>Detalle de Productos
                        </h5>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $compra->detalles->count() }} producto(s)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="35%">Producto</th>
                                        <th class="px-3 py-3" width="15%">Marca</th>
                                        <th class="px-3 py-3 text-center" width="10%">Cantidad</th>
                                        <th class="px-3 py-3 text-center" width="15%">Precio Unitario</th>
                                        <th class="px-3 py-3 text-center" width="15%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($compra->detalles as $index => $detalle)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                                                <i class="ri-box-3-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 fw-semibold">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $detalle->producto->marca ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                                    {{ $detalle->cantidad }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($detalle->precio_unitario, 2) }} Bs
                                            </td>
                                            <td class="text-center fw-bold text-success">
                                                {{ number_format($detalle->subtotal, 2) }} Bs
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="5" class="text-end fw-bold fs-5 pe-3">TOTAL:</td>
                                        <td class="text-center fw-bold fs-5 text-success">
                                            {{ number_format($compra->total_bs, 2) }} Bs
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
