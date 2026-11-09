@extends('admin.dashboard')

@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-info">
                            <i class="ri-baby-line me-2 text-info"></i>Gestión de Niños (Hijos)
                        </h4>
                        <p class="text-muted mb-0">Administra los hijos de los clientes y consulta su información</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards (opcional) -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card border border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- AÑADIDO: d-flex align-items-center justify-content-center para centrar el icono -->
                                <div
                                    class="avatar-sm rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <i class="ri-group-line fs-24 text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Niños</p>
                                <h4 class="mb-0" id="totalHijosCounter">{{ $hijos->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por nombre, apellido o carnet..."
                                        value="{{ request('search') ?? '' }}">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
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
                    <div
                        class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-info-subtle">
                        <h5 class="card-title mb-0 fw-bold text-info">
                            <i class="ri-list-check me-2"></i>Listado de Niños
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-info text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Niño</th>
                                        <th class="px-3 py-3" width="15%">Carnet</th>
                                        <th class="px-3 py-3" width="15%">Fecha Nacimiento</th>
                                        <th class="px-3 py-3" width="25%">Padre/Madre</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="hijosTableBody">
                                    @include('admin.hijos.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($hijos->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-info">{{ $hijos->firstItem() }}</span> a
                                    <span class="fw-medium text-info">{{ $hijos->lastItem() }}</span> de
                                    <span class="fw-medium text-info">{{ $hijos->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $hijos->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        /* Estilos específicos para niños con tema info (celeste) */
        .btn-info {
            background: linear-gradient(135deg, #299cdb, #1c7ab3);
            border-color: #299cdb;
            box-shadow: 0 2px 4px rgba(41, 156, 219, 0.2);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #1c7ab3, #135e8a);
            border-color: #1c7ab3;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(41, 156, 219, 0.3);
        }

        .bg-info-subtle {
            background-color: rgba(41, 156, 219, 0.08) !important;
        }

        .border-info {
            border-color: rgba(41, 156, 219, 0.3) !important;
        }

        .text-info {
            color: #299cdb !important;
        }

        .table-info {
            --bs-table-bg: rgba(41, 156, 219, 0.05);
            --bs-table-border-color: rgba(41, 156, 219, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(41, 156, 219, 0.04);
        }

        .badge-info-subtle {
            background-color: rgba(41, 156, 219, 0.1);
            color: #299cdb;
            border: 1px solid rgba(41, 156, 219, 0.2);
        }

        .card:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: scale(1.1);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .search-box i {
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .page-title-box {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let debounceTimer;
            let isProcessing = false;

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Actualizar contador total (si se necesita)
            function updateTotalCounter() {
                let total = $('#totalHijosCounter').text();
                // Podrías actualizar otras estadísticas si las tienes
            }

            // Reset de búsqueda
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

            // Búsqueda por input
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            // Función para cargar resultados vía AJAX
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.hijos.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#hijosTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando niños...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#hijosTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-info">${response.from || 0}</span> a 
                                     <span class="fw-medium text-info">${response.to || 0}</span> de 
                                     <span class="fw-medium text-info">${response.total}</span> resultados`
                                );
                                $('#totalHijosCounter').text(response.total);
                                updateTotalCounter();
                            }
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) this._tooltip.dispose();
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar los resultados');
                        $('#hijosTableBody').html(`
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

            // Manejar paginación
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const url = $(this).attr('href');
                const search = $('#searchInput').val().trim();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#hijosTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando página...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#hijosTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-info">${response.from || 0}</span> a 
                                     <span class="fw-medium text-info">${response.to || 0}</span> de 
                                     <span class="fw-medium text-info">${response.total}</span> resultados`
                                );
                                $('#totalHijosCounter').text(response.total);
                            }
                            const newUrl = url + (search ? '&search=' + encodeURIComponent(
                                search) : '');
                            window.history.pushState({}, '', newUrl);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la página');
                    }
                });
            });

            // Función showToast (copiada de clientes)
            function showToast(type, message) {
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: 'Éxito'
                    },
                    error: {
                        icon: 'ri-close-circle-fill',
                        bgClass: 'bg-danger',
                        title: 'Error'
                    },
                    warning: {
                        icon: 'ri-alert-fill',
                        bgClass: 'bg-warning',
                        title: 'Advertencia'
                    },
                    info: {
                        icon: 'ri-information-fill',
                        bgClass: 'bg-info',
                        title: 'Información'
                    }
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
                </div>
                `;

                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);

                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });
                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        });
    </script>
@endpush
