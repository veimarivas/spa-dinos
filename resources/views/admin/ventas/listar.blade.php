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
                            <i class="ri-shopping-bag-line me-2"></i>Registro de Ventas
                        </h4>
                        <p class="text-white-50 mb-0">Administra y consulta todas las ventas realizadas</p>
                    </div>
                    @can('ventas.registrar')
                    <div class="page-title-right">
                        <a href="{{ route('admin.ventas.create') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-add-line me-1"></i>Nueva Venta
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-shopping-bag-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Total Ventas</p>
                                <h4 class="mb-0 fw-bold text-dark" id="statTotalVentas">{{ $totalVentas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <p class="text-muted mb-1 fw-medium">Monto Total Bs</p>
                                <h4 class="mb-0 fw-bold text-dark" id="statTotalMonto">{{ number_format($totalMonto, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle fs-24">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Ventas Hoy</p>
                                <h4 class="mb-0 fw-bold text-dark" id="statTotalHoy">{{ $totalHoy }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="ri-search-line me-1 text-primary"></i>Buscar</label>
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Buscar por cliente o ID..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="ri-calendar-line me-1 text-primary"></i>Fecha Desde</label>
                        <input type="date" class="form-control" id="fechaDesde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="ri-calendar-line me-1 text-primary"></i>Fecha Hasta</label>
                        <input type="date" class="form-control" id="fechaHasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-secondary w-100" id="btnLimpiar">
                            <i class="ri-refresh-line me-1"></i>Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-primary-subtle border-0 py-3 px-4 rounded-top-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="ri-list-check me-2"></i>Listado de Ventas
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th class="text-center">Tipo de Venta</th>
                                <th class="text-center">N&deg; Productos</th>
                                <th class="text-center">Total Bs</th>
                                <th class="text-center">Tipo Pago</th>
                                <th class="text-center" style="width: 120px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @include('admin.ventas.partials.table-body')
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3 px-4 rounded-bottom-4">
                <div id="paginationContainer">
                    {{ $ventas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let debounceTimer;
            const searchInput = document.getElementById('searchInput');
            const fechaDesde = document.getElementById('fechaDesde');
            const fechaHasta = document.getElementById('fechaHasta');
            const btnLimpiar = document.getElementById('btnLimpiar');

            function fetchData(page = 1) {
                const params = new URLSearchParams({
                    search: searchInput.value,
                    fecha_desde: fechaDesde.value,
                    fecha_hasta: fechaHasta.value,
                    page: page
                });

                fetch(`{{ route('admin.ventas.listar') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('tableBody').innerHTML = data.html;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;
                    document.getElementById('statTotalVentas').textContent = data.totalVentas;
                    document.getElementById('statTotalMonto').textContent = data.totalMonto;
                    document.getElementById('statTotalHoy').textContent = data.totalHoy;
                    bindPagination();
                })
                .catch(err => console.error('Error:', err));
            }

            function bindPagination() {
                document.querySelectorAll('#paginationContainer .page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page') || 1;
                        fetchData(page);
                    });
                });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchData(), 400);
            });

            fechaDesde.addEventListener('change', () => fetchData());
            fechaHasta.addEventListener('change', () => fetchData());

            btnLimpiar.addEventListener('click', function() {
                searchInput.value = '';
                fechaDesde.value = '';
                fechaHasta.value = '';
                fetchData();
            });

            bindPagination();
        });
    </script>
@endsection
