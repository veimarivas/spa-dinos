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
                            <i class="ri-calendar-check-line me-2"></i>Gestión de Suscripciones
                        </h4>
                        <p class="text-white-50 mb-0">Administra y da seguimiento a todas las suscripciones activas</p>
                    </div>
                    <div class="page-title-right">
                        @can('suscripciones.registrar')
                        <a href="{{ route('admin.suscripciones.create') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-add-line me-1"></i>Nueva Suscripción
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Total Suscripciones</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $suscripciones->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                        <i class="ri-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Suscripciones Activas</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $suscripciones->where('estado', 'activa')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle fs-24">
                                        <i class="ri-pause-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Suscripciones Pausadas</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ $suscripciones->where('estado', 'pausada')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg">
                                    <div
                                        class="avatar-title bg-secondary bg-opacity-10 text-secondary rounded-circle fs-24">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fw-medium">Suscripciones Finalizadas</p>
                                <h4 class="mb-0 fw-bold text-dark">
                                    {{ $suscripciones->where('estado', 'finalizada')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                            <span class="avatar-title text-primary"><i class="ri-list-check fs-5"></i></span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Listado de Suscripciones</h5>
                            <small class="text-muted">Visualiza y gestiona todas las suscripciones registradas</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- FILTRO POR TARIFA -->
                        <select id="tarifaFilter" class="form-select form-select-lg rounded-pill" style="min-width:160px;border:2px solid #e2e8f0;">
                            <option value="">Todas las tarifas</option>
                            <option value="normal" {{ request('tarifa') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="especial" {{ request('tarifa') === 'especial' ? 'selected' : '' }}>Especial</option>
                        </select>
                        <!-- BUSCADOR EN TIEMPO REAL -->
                        <div class="search-box position-relative" style="min-width: 250px;">
                            <input type="text" id="searchInput" class="form-control form-control-lg ps-5 rounded-pill"
                                placeholder="Buscar por niñ@, responsable, carnet, plan o paquete..."
                                value="{{ request('search') }}" autocomplete="off">
                            <i
                                class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                            <!-- Loader -->
                            <div id="searchLoader" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="tableWrapper" class="position-relative">
                    <div id="tableLoaderOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-none"
                        style="background:rgba(255,255,255,0.7); z-index: 10; backdrop-filter: blur(2px);">
                        <div class="d-flex h-100 align-items-center justify-content-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                    @include('admin.suscripciones.partials.table')
                </div>
            </div>{{-- /.card-body --}}
        </div>{{-- /.card --}}

        <!-- Modal de confirmación para eliminar -->
        @can('suscripciones.eliminar')
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger bg-gradient text-white py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="ri-alert-line me-2"></i>Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-1">
                                <i class="ri-delete-bin-line"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">¿Estás seguro de eliminar?</h5>
                        <p class="text-muted mb-0">Se eliminará <strong id="deleteItemName"
                                class="text-danger"></strong>. Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer bg-light p-3 justify-content-center">
                        <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="ri-delete-bin-line me-1"></i>Sí, Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endcan

        <!-- Modal Ver Pagos -->
        <div class="modal fade" id="modalPagos" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success bg-gradient text-white py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="ri-money-dollar-circle-line me-2"></i>
                            <span id="modalPagosTitulo">Pagos de la Suscripción</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" id="modalPagosBody">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="text-muted mt-3">Cargando información de pagos...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="button" class="btn btn-success btn-lg" onclick="window.print()">
                            <i class="ri-printer-line me-1"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
        }

        .card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .tabla-suscripciones {
            max-height: 65vh;
        }

        #suscripcionesTable {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 0;
            font-size: 0.82rem;
        }

        #suscripcionesTable .col-num {
            width: 3%;
        }

        #suscripcionesTable .col-nino {
            width: 9%;
        }

        #suscripcionesTable .col-padre {
            width: 9%;
        }

        #suscripcionesTable .col-plan {
            width: 14%;
        }

        #suscripcionesTable .col-fecha {
            width: 9%;
        }

        #suscripcionesTable .col-sesiones {
            width: 26%;
        }

        #suscripcionesTable .col-estado {
            width: 15%;
        }

        #suscripcionesTable .col-acciones {
            width: 15%;
        }

        #suscripcionesTable thead th {
            font-weight: 600;
            color: #495057;
            background: linear-gradient(180deg, #f8f9fa 0%, #f1f5f9 100%);
            border: none;
            border-bottom: 2px solid #e9ecef;
            padding: 8px 5px;
            font-size: 0.72rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #suscripcionesTable thead th i {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        #suscripcionesTable tbody td {
            padding: 6px 5px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        #suscripcionesTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        #suscripcionesTable .min-w-0 {
            min-width: 0;
        }

        .search-box input {
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .search-box input:focus {
            border-color: #405189;
            box-shadow: 0 0 0 4px rgba(64, 81, 137, 0.15);
        }

        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: 1px solid #e2e8f0;
            color: #405189;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
            border-color: #405189;
        }

        .highlight-search {
            background-color: #fff3cd !important;
        }

        #modalPagos .modal-content {
            border-radius: 12px;
        }

        #modalPagos .table th {
            font-size: 0.85rem;
            font-weight: 600;
        }

        #modalPagos .table td {
            font-size: 0.9rem;
        }

        #modalPagos .badge {
            font-weight: 600;
        }

        @media (max-width: 1400px) {
            #suscripcionesTable {
                font-size: 0.78rem;
            }
            #suscripcionesTable .col-nino { width: 9%; }
            #suscripcionesTable .col-padre { width: 9%; }
            #suscripcionesTable .col-sesiones { width: 26%; }
            #suscripcionesTable .col-acciones { width: 16%; }
        }

        @media (max-width: 1200px) {
            .tabla-suscripciones {
                max-height: 60vh;
            }
            #suscripcionesTable {
                font-size: 0.75rem;
            }
            #suscripcionesTable .col-nino { width: 8%; }
            #suscripcionesTable .col-padre { width: 8%; }
            #suscripcionesTable .col-sesiones { width: 28%; }
            #suscripcionesTable .col-estado { width: 16%; }
            #suscripcionesTable .col-acciones { width: 17%; }
            #suscripcionesTable tbody td small {
                display: none;
            }
        }

        @media (max-width: 992px) {
            .page-title-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px;
            }

            .tabla-suscripciones {
                max-height: 55vh;
                overflow-x: auto;
            }

            #suscripcionesTable {
                font-size: 0.72rem;
            }

            #suscripcionesTable thead th {
                font-size: 0.65rem;
                padding: 6px 4px;
            }

            #suscripcionesTable tbody td {
                padding: 5px 4px;
            }

            #suscripcionesTable .col-nino { width: 8%; }
            #suscripcionesTable .col-padre { width: 8%; }
            #suscripcionesTable .col-fecha { width: 8%; }
            #suscripcionesTable .col-sesiones { width: 30%; }
            #suscripcionesTable .col-estado { width: 17%; }
            #suscripcionesTable .col-acciones { width: 18%; }

            .search-box {
                min-width: 100% !important;
            }
        }

        @media (max-width: 768px) {
            .tabla-suscripciones {
                max-height: 50vh;
            }

            #suscripcionesTable {
                font-size: 0.7rem;
            }

            #suscripcionesTable thead th {
                font-size: 0.6rem;
            }

            #suscripcionesTable .col-nino {
                width: 7%;
            }

            #suscripcionesTable .col-padre {
                width: 7%;
            }

            #suscripcionesTable .col-fecha {
                width: 7%;
            }

            #suscripcionesTable .col-sesiones {
                width: 30%;
            }

            #suscripcionesTable .col-estado {
                width: 16%;
            }

            #suscripcionesTable .col-acciones {
                width: 15%;
            }
        }

        @media (max-width: 576px) {
            .tabla-suscripciones {
                max-height: 45vh;
            }

            #suscripcionesTable thead th:nth-child(5),
            #suscripcionesTable tbody td:nth-child(5) {
                display: none;
            }
        }

        @media print {

            .modal-header,
            .modal-footer,
            .btn-close {
                display: none !important;
            }

            .modal-body {
                padding: 0 !important;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // ========== MOSTRAR TOAST DE SESIÓN ==========
            @if (session('success'))
                showToast('success', '{{ session('success') }}');
            @endif
            @if (session('error'))
                showToast('error', '{{ session('error') }}');
            @endif

            // ========== MODAL ELIMINAR ==========
            let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            $(document).on('click', '.delete-btn', function() {
                let url = $(this).data('url');
                let nombre = $(this).data('nombre');
                $('#deleteItemName').text(nombre);
                $('#deleteForm').attr('action', url);
                deleteModal.show();
            });

            // ========== BÚSQUEDA Y FILTRO SERVER-SIDE (AJAX) ==========
            const INDEX_URL = '{{ route('admin.suscripciones.index') }}';
            let searchTimeout = null;
            let lastRequest = null;

            function fetchSuscripciones(extraParams) {
                const params = Object.assign({
                    search: $('#searchInput').val().trim(),
                    tarifa: $('#tarifaFilter').val(),
                }, extraParams || {});

                // Limpiar vacíos para que la URL quede prolija
                Object.keys(params).forEach(k => {
                    if (params[k] === '' || params[k] === null || params[k] === undefined) {
                        delete params[k];
                    }
                });

                // Cancelar petición previa si sigue pendiente
                if (lastRequest && lastRequest.readyState !== 4) {
                    lastRequest.abort();
                }

                $('#tableLoaderOverlay').removeClass('d-none');
                $('#searchLoader').removeClass('d-none');

                lastRequest = $.ajax({
                    url: INDEX_URL,
                    method: 'GET',
                    data: params,
                    dataType: 'json',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response && response.html) {
                            // Reemplazar tabla + footer
                            const $wrapper = $('#tableWrapper');
                            // Conservar el overlay loader que vive dentro del wrapper
                            const overlay = $('#tableLoaderOverlay').detach();
                            $wrapper.html(response.html).prepend(overlay);
                            // Sincronizar URL del navegador (sin recargar) para que sea compartible/refrescable
                            const qs = $.param(params);
                            const newUrl = INDEX_URL + (qs ? ('?' + qs) : '');
                            if (window.history && window.history.replaceState) {
                                window.history.replaceState({}, '', newUrl);
                            }
                        }
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;
                        showToast('error', 'No se pudo cargar el listado.');
                    },
                    complete: function() {
                        $('#tableLoaderOverlay').addClass('d-none');
                        $('#searchLoader').addClass('d-none');
                    }
                });
            }

            // Buscador con debounce
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchSuscripciones({ page: 1 });
                }, 350);
            });

            // Filtro por tarifa (inmediato)
            $('#tarifaFilter').on('change', function() {
                fetchSuscripciones({ page: 1 });
            });

            // Paginación AJAX (delegado, sobrevive a reemplazos de #tableWrapper)
            $('#tableWrapper').on('click', '.pagination a.page-link', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                if (!href) return;
                const url = new URL(href, window.location.origin);
                const page = url.searchParams.get('page') || 1;
                fetchSuscripciones({ page: page });
                $('html, body').animate({ scrollTop: $('#tableWrapper').offset().top - 100 }, 200);
            });

            // Función toast
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
                const cfg = config[type] || config.info;
                const toastId = 'toast-' + Date.now();
                const toastHtml = `
            <div id="${toastId}" class="toast ${cfg.bgClass} text-white shadow-lg" role="alert">
                <div class="toast-header ${cfg.bgClass} text-white border-bottom-0">
                    <i class="${cfg.icon} me-2"></i>
                    <strong class="me-auto">${cfg.title}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body d-flex align-items-center">
                    <i class="${cfg.icon} me-2 fs-5"></i>
                    <span class="flex-grow-1">${message}</span>
                </div>
            </div>`;
                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    container.style.zIndex = '9999';
                    document.body.appendChild(container);
                }
                container.insertAdjacentHTML('afterbegin', toastHtml);
                const toastElement = document.getElementById(toastId);
                new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                }).show();
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

            // ========== ATAJOS DE TECLADO ==========
            // Foco en buscador con Ctrl+F
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    $('#searchInput').focus();
                }
            });

            // ========== FUNCIÓN PARA IMPRIMIR PAGO ==========
            $(document).on('click', '.btn-imprimir-pago', function() {
                const pagoId = $(this).data('pago-id');
                const pdfUrl = '{{ route('admin.suscripciones.reciboPdf', ':id') }}'.replace(':id',
                    pagoId);

                // Abrir PDF en nueva ventana para imprimir
                window.open(pdfUrl, '_blank');
            });

            // ========== MODAL VER PAGOS ==========
            let modalPagos = new bootstrap.Modal(document.getElementById('modalPagos'));

            $(document).on('click', '.btn-ver-pagos', function() {
                const suscripcionId = $(this).data('id');
                const suscripcionNombre = $(this).data('nombre');

                $('#modalPagosTitulo').text('Pagos - ' + suscripcionNombre);
                $('#modalPagosBody').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Cargando información de pagos...</p>
        </div>
    `);

                modalPagos.show();

                $.ajax({
                    url: '{{ route('admin.suscripciones.detallePagos', ':id') }}'.replace(':id',
                        suscripcionId),
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#modalPagosBody').html(response.html);
                        } else {
                            $('#modalPagosBody').html(`
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>
                        Error al cargar los pagos
                    </div>
                `);
                        }
                    },
                    error: function() {
                        $('#modalPagosBody').html(`
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    Error de conexión. Intente nuevamente.
                </div>
            `);
                    }
                });
            });
        });
    </script>
@endpush
