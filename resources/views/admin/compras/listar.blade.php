@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-shopping-cart-line me-2 text-primary"></i>Registro de Compras
                        </h4>
                        <p class="text-muted mb-0">Administra las compras realizadas a proveedores</p>
                    </div>

                    @can('compras.registrar')
                    <div class="page-title-right">
                        <a href="{{ route('admin.compras.create') }}" class="btn btn-primary btn-lg waves-effect waves-light">
                            <i class="ri-add-line align-bottom me-1"></i> Nueva Compra
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-shopping-cart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Compras</p>
                                <h4 class="mb-0" id="totalComprasCounter">{{ $totalCompras }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Monto Total</p>
                                <h4 class="mb-0" id="totalMontoCounter">{{ number_format($totalMonto, 2) }} Bs</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 align-items-end">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por proveedor o N&deg; de compra..."
                                        value="{{ request('search') ?? '' }}">
                                    <i class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div>
                                <label class="form-label fw-medium mb-1">Fecha desde</label>
                                <input type="date" id="fechaDesde" class="form-control form-control-lg">
                            </div>
                            <div>
                                <label class="form-label fw-medium mb-1">Fecha hasta</label>
                                <input type="date" id="fechaHasta" class="form-control form-control-lg">
                            </div>
                            <div>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-primary-subtle">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ri-list-check me-2"></i>Listado de Compras
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="15%">Fecha</th>
                                        <th class="px-3 py-3" width="30%">Proveedor</th>
                                        <th class="px-3 py-3 text-center" width="15%">N&deg; Productos</th>
                                        <th class="px-3 py-3 text-center" width="20%">Total Bs</th>
                                        @can('compras.ver')
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody id="comprasTableBody">
                                    @include('admin.compras.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($compras->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $compras->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $compras->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $compras->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $compras->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;

            function loadResults(search, fechaDesde, fechaHasta) {
                if (isProcessing) return;
                isProcessing = true;

                search = search || $('#searchInput').val().trim();
                fechaDesde = fechaDesde || $('#fechaDesde').val();
                fechaHasta = fechaHasta || $('#fechaHasta').val();

                $.ajax({
                    url: '{{ route('admin.compras.listar') }}',
                    method: 'GET',
                    data: {
                        search: search,
                        fecha_desde: fechaDesde,
                        fecha_hasta: fechaHasta
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#comprasTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando compras...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#comprasTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('#totalComprasCounter').text(response.totalCompras);
                                $('#totalMontoCounter').text(response.totalMonto + ' Bs');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading results:', xhr);
                        showToast('error', 'Error al cargar los resultados');
                        $('#comprasTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5 text-danger">
                                    <i class="ri-error-warning-line display-5"></i>
                                    <p class="mt-2">Error al cargar los datos</p>
                                </td>
                            </tr>
                        `);
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            }

            // Search input
            $('#searchInput').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults();
                }, 500);
            });

            // Date filters
            $('#fechaDesde, #fechaHasta').on('change', function() {
                loadResults();
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
                loadResults('', '', '');
            });

            // Pagination
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();
                const fechaDesde = $('#fechaDesde').val();
                const fechaHasta = $('#fechaHasta').val();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: search,
                        fecha_desde: fechaDesde,
                        fecha_hasta: fechaHasta
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#comprasTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando p&aacute;gina...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#comprasTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('#totalComprasCounter').text(response.totalCompras);
                                $('#totalMontoCounter').text(response.totalMonto + ' Bs');
                            }
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la p\u00e1gina');
                    }
                });
            });

            // Toast function
            function showToast(type, message) {
                const config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: '\u00c9xito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Informaci\u00f3n' }
                };

                const toastConfig = config[type] || config.info;
                const toastId = 'toast-' + Date.now();

                const toastHtml = `
                <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                        <i class="${toastConfig.icon} me-2"></i>
                        <strong class="me-auto">${toastConfig.title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body d-flex align-items-center">
                        <i class="${toastConfig.icon} me-2 fs-5"></i>
                        <span class="flex-grow-1">${message}</span>
                    </div>
                </div>`;

                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);

                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        });
    </script>
@endpush
