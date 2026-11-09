@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-user-star-line me-2 text-primary"></i>Gestión de Clientes
                        </h4>
                        <p class="text-muted mb-0">Administra los clientes (padres/madres) y sus datos personales</p>
                    </div>

                    @can('clientes.registrar')
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrarClienteModal">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Cliente
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- AÑADIDO: d-flex align-items-center justify-content-center -->
                                <div
                                    class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <i class="ri-group-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Clientes</p>
                                <h4 class="mb-0" id="totalClientesCounter">{{ $clientes->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-xl-3 col-md-6">
                <div class="card border border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- Contenedor con centrado flex -->
                                <div
                                    class="avatar-sm rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <!-- Icono corregido -->
                                    <i class="ri-team-line fs-24 text-success align-baseline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Con Hijos</p>
                                <h4 class="mb-0" id="conHijosCounter">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-xl-3 col-md-6">
                <div class="card border border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- AÑADIDO: d-flex align-items-center justify-content-center -->
                                <div
                                    class="avatar-sm rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <i class="ri-calendar-check-line fs-24 text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Suscrip. Activas</p>
                                <h4 class="mb-0" id="suscripcionesActivasCounter">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-xl-3 col-md-6">
                <div class="card border border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- AÑADIDO: d-flex align-items-center justify-content-center -->
                                <div
                                    class="avatar-sm rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <i class="ri-file-copy-line fs-24 text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Suscripciones</p>
                                <h4 class="mb-0" id="totalSuscripcionesCounter">0</h4>
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
                                        placeholder="Buscar por nombre, carnet, correo o teléfono..."
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
                        class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-primary-subtle">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ri-list-check me-2"></i>Listado de Clientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Cliente</th>
                                        <th class="px-3 py-3" width="20%">Contacto</th>
                                        <th class="px-3 py-3 text-center" width="10%">Hijos</th>
                                        <th class="px-3 py-3 text-center" width="15%">Suscripciones Activas</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="clientesTableBody">
                                    @include('admin.clientes.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($clientes->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $clientes->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $clientes->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $clientes->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $clientes->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('clientes.registrar')
    <!-- Modal Registrar Cliente -->
    <div class="modal fade" id="registrarClienteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarClienteLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Cliente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-id-card-line me-1 text-primary"></i>Carnet <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" name="carnet" class="form-control form-control-lg border-primary"
                                    required>
                                <div class="invalid-feedback">El carnet es obligatorio</div>
                                <small class="form-text text-muted feedback-carnet"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-barcode-line me-1 text-primary"></i>NIT
                                </label>
                                <input type="text" name="nit" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-user-line me-1 text-primary"></i>Nombres <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombres" class="form-control form-control-lg border-primary"
                                    required>
                                <div class="invalid-feedback">El nombre es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="apellido_paterno"
                                    class="form-control form-control-lg border-primary" required>
                                <div class="invalid-feedback">El apellido paterno es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" name="apellido_materno"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-mail-line me-1 text-primary"></i>Correo
                                </label>
                                <input type="email" name="correo" id="correo_registro_cli"
                                    class="form-control form-control-lg border-primary">
                                <small class="form-text feedback-correo"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-map-pin-line me-1 text-primary"></i>Dirección
                                </label>
                                <input type="text" name="direccion"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-calendar-line me-1 text-primary"></i>Fecha Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-phone-line me-1 text-primary"></i>Celular
                                </label>
                                <input type="text" name="celular" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-phone-line me-1 text-primary"></i>Teléfono
                                </label>
                                <input type="text" name="telefono"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <!-- Campo Color (registro) -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-palette-line me-1 text-primary"></i>Color de identificación
                                </label>
                                <input type="color" name="color" class="form-control form-control-lg border-primary"
                                    value="#405189">
                                <small class="form-text text-muted">Selecciona un color para identificar al cliente</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Cliente</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    @can('clientes.editar')
    <!-- Modal Modificar Cliente -->
    <div class="modal fade" id="modalModificarCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Cliente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="clienteId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="carnet_edicion" name="carnet" required>
                                <div class="invalid-feedback">El carnet es obligatorio</div>
                                <small class="form-text text-muted feedback-carnet-edicion"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">NIT</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="nit_edicion" name="nit">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="nombres_edicion" name="nombres" required>
                                <div class="invalid-feedback">El nombre es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="apellido_paterno_edicion" name="apellido_paterno" required>
                                <div class="invalid-feedback">El apellido paterno es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="apellido_materno_edicion" name="apellido_materno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Correo</label>
                                <input type="email" class="form-control form-control-lg border-primary"
                                    id="correo_edicion" name="correo">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Dirección</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="direccion_edicion" name="direccion">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Nacimiento</label>
                                <input type="date" class="form-control form-control-lg border-primary"
                                    id="fecha_nacimiento_edicion" name="fecha_nacimiento">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Celular</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="celular_edicion" name="celular">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Teléfono</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="telefono_edicion" name="telefono">
                            </div>
                            <!-- Campo Color (edición) -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-palette-line me-1 text-primary"></i>Color de identificación
                                </label>
                                <input type="color" class="form-control form-control-lg border-primary"
                                    id="color_edicion" name="color">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Cliente</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    @can('clientes.eliminar')
    <!-- Modal Eliminar Cliente -->
    <div class="modal fade" id="modalEliminarCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="eliminarId">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-2xl">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3 fw-bold text-danger">¿Estás seguro de eliminar este cliente?</h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el cliente y sus datos personales
                            si no están siendo utilizados por otros registros.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">No se puede eliminar si tiene hijos asociados o suscripciones
                                activas.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">Sí, Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('style')
    <style>
        /* Estilos específicos para clientes con tema primary (azul) */
        .btn-primary {
            background: linear-gradient(135deg, #405189, #2c3e6d);
            border-color: #405189;
            box-shadow: 0 2px 4px rgba(64, 81, 137, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2c3e6d, #1f2b4a);
            border-color: #2c3e6d;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(64, 81, 137, 0.3);
        }

        .bg-primary-subtle {
            background-color: rgba(64, 81, 137, 0.08) !important;
        }

        .border-primary {
            border-color: rgba(64, 81, 137, 0.3) !important;
        }

        .text-primary {
            color: #405189 !important;
        }

        .table-primary {
            --bs-table-bg: rgba(64, 81, 137, 0.05);
            --bs-table-border-color: rgba(64, 81, 137, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(64, 81, 137, 0.04);
        }

        .badge-primary-subtle {
            background-color: rgba(64, 81, 137, 0.1);
            color: #405189;
            border: 1px solid rgba(64, 81, 137, 0.2);
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

        .text-info {
            color: #0dcaf0 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let debounceTimer;
            let isProcessing = false;

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Contadores
            function updateCounters() {
                let total = $('#totalClientesCounter').text();
                let conHijos = 0;
                let suscripcionesActivas = 0;
                let totalSuscripciones = 0;

                $('tr[data-cliente-id]').each(function() {
                    let hijos = parseInt($(this).find('.hijos-count').text()) || 0;
                    if (hijos > 0) conHijos++;
                    let activas = parseInt($(this).find('.suscripciones-activas').data('count')) || 0;
                    suscripcionesActivas += activas;
                    let totalSusc = parseInt($(this).find('.total-suscripciones').data('count')) || 0;
                    totalSuscripciones += totalSusc;
                });

                $('#conHijosCounter').text(conHijos);
                $('#suscripcionesActivasCounter').text(suscripcionesActivas);
                $('#totalSuscripcionesCounter').text(totalSuscripciones);
            }
            updateCounters();

            // Reset formularios al cerrar modales
            $('#registrarClienteModal, #modalModificarCliente, #modalEliminarCliente').on('hidden.bs.modal',
                function() {
                    if (this.id === 'registrarClienteModal') {
                        $('#addForm')[0].reset();
                        $('.feedback-carnet').removeClass('text-success text-danger').text('');
                        $('.addBtn').prop('disabled', true);
                        $('#addForm').removeClass('was-validated');
                        $('.form-control').removeClass('is-valid is-invalid');
                    } else if (this.id === 'modalModificarCliente') {
                        $('#updateForm')[0].reset();
                        $('.feedback-carnet-edicion').removeClass('text-success text-danger').text('');
                        $('.updateBtn').prop('disabled', true);
                        $('#updateForm').removeClass('was-validated');
                        $('.form-control').removeClass('is-valid is-invalid');
                    }
                });

            // Verificar carnet al registrar
            // Verificar carnet al registrar
            $('input[name="carnet"]').on('input', function() {
                const carnet = $(this).val().trim();
                const feedback = $('.feedback-carnet');
                const submitBtn = $('.addBtn');
                const input = $(this);

                // Limpiar validaciones previas
                feedback.removeClass('text-success text-danger text-info').html('');
                submitBtn.prop('disabled', true);
                input.removeClass('is-valid is-invalid');

                if (carnet.length === 0) {
                    return;
                }

                if (carnet.length < 2) {
                    feedback.addClass('text-danger').html(
                        '<i class="ri-error-warning-line me-1"></i> El carnet debe tener al menos 2 caracteres'
                    );
                    input.addClass('is-invalid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.clientes.verificarCarnet') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            carnet: carnet
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (!res.exists) {
                                // Caso 1: Carnet no existe en personas
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                                // Limpiar todos los campos del formulario (excepto carnet)
                                $('#addForm').find('input:not([name="carnet"])').val(
                                    '');
                            } else if (res.exists && res.is_client) {
                                // Caso 2: Carnet existe y ya es cliente
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                                // Limpiar todos los campos para evitar confusiones
                                $('#addForm').find('input:not([name="carnet"])').val(
                                    '');
                            } else {
                                // Caso 3: Carnet existe pero no es cliente → autocompletar
                                feedback.addClass('text-info').html(
                                    '<i class="ri-information-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');

                                // Rellenar campos con los datos de la persona encontrada
                                const p = res.persona;
                                $('input[name="nit"]').val(p.nit || '');
                                $('input[name="nombres"]').val(p.nombres || '');
                                $('input[name="apellido_paterno"]').val(p
                                    .apellido_paterno || '');
                                $('input[name="apellido_materno"]').val(p
                                    .apellido_materno || '');
                                $('input[name="correo"]').val(p.correo || '');
                                $('input[name="direccion"]').val(p.direccion || '');
                                $('input[name="fecha_nacimiento"]').val(p
                                    .fecha_nacimiento || '');
                                $('input[name="celular"]').val(p.celular || '');
                                $('input[name="telefono"]').val(p.telefono || '');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el carnet'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // ===== Verificar correo al registrar =====
            let correoDebounce;
            let correoValido = true; // opcional, válido por defecto

            $('#correo_registro_cli').on('input', function() {
                const correo = $(this).val().trim();
                const feedback = $('.feedback-correo');
                const input = $(this);

                clearTimeout(correoDebounce);
                feedback.removeClass('text-success text-danger text-warning').html('');
                input.removeClass('is-valid is-invalid');

                if (correo.length === 0) {
                    correoValido = true;
                    actualizarBtnCli();
                    return;
                }

                correoDebounce = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.clientes.verificarCorreo') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            correo: correo
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                correoValido = false;
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este correo ya está registrado'
                                );
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                correoValido = true;
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Correo disponible'
                                );
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                            actualizarBtnCli();
                        },
                        error: function() {
                            correoValido = true;
                            input.removeClass('is-valid is-invalid');
                            actualizarBtnCli();
                        }
                    });
                }, 500);
            });

            function actualizarBtnCli() {
                const carnetOk = $('.feedback-carnet').hasClass('text-success') ||
                    $('.feedback-carnet').hasClass('text-info');
                $('.addBtn').prop('disabled', !(carnetOk && correoValido));
            }
            // ===== Fin verificar correo =====

            // Verificar carnet al editar
            $('#carnet_edicion').on('input', function() {
                const carnet = $(this).val().trim();
                const feedback = $('.feedback-carnet-edicion');
                const submitBtn = $('.updateBtn');
                const personaId = $('#clienteId').data('persona-id'); // Se debe asignar al abrir modal
                const input = $(this);

                if (carnet.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (carnet.length < 2) {
                    feedback.addClass('text-danger').text('El carnet debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.clientes.verificarCarnetEdicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            carnet: carnet,
                            persona_id: personaId
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este carnet ya está registrado'
                                );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Carnet disponible'
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-valid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el carnet'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Editar cliente: cargar datos en modal
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#clienteId').val(data.id).data('persona-id', data.persona.id);
                $('#carnet_edicion').val(data.persona.carnet);
                $('#nit_edicion').val(data.persona.nit || '');
                $('#nombres_edicion').val(data.persona.nombres);
                $('#apellido_paterno_edicion').val(data.persona.apellido_paterno);
                $('#apellido_materno_edicion').val(data.persona.apellido_materno || '');
                $('#correo_edicion').val(data.persona.correo || '');
                $('#direccion_edicion').val(data.persona.direccion || '');
                $('#fecha_nacimiento_edicion').val(data.persona.fecha_nacimiento || '');
                $('#celular_edicion').val(data.persona.celular || '');
                $('#telefono_edicion').val(data.persona.telefono || '');
                // Asignar color
                $('#color_edicion').val(data.color || '#405189');

                $('.feedback-carnet-edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#carnet_edicion').removeClass('is-valid is-invalid');
            });

            // Eliminar cliente
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminarCliente .modal-body h4').text(
                    `¿Estás seguro de eliminar a ${data.persona.nombres} ${data.persona.apellido_paterno}?`
                );
            });

            // REGISTRAR CLIENTE
            $('#addForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.addBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: "{{ route('admin.clientes.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrarClienteModal'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el cliente');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el cliente.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errors.join('<br>');
                            } else if (xhr.responseJSON.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            } else if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                        } else if (xhr.responseText) {
                            // Si la respuesta no es JSON, muestra parte del HTML (peligroso)
                            console.error('Respuesta no JSON:', xhr.responseText);
                            errorMsg = 'Error del servidor (consola para más detalles)';
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ACTUALIZAR CLIENTE
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                const submitBtn = $('.updateBtn');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');

                $.ajax({
                    url: "{{ route('admin.clientes.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalModificarCliente'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el cliente');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el cliente. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMsg = errors.join('<br>');
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ELIMINAR CLIENTE
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.clientes.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalEliminarCliente'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el cliente');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el cliente. Intenta nuevamente.';
                        if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        isProcessing = false;
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // FUNCIÓN PARA CARGAR RESULTADOS (búsqueda/paginación)
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.clientes.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#clientesTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando clientes...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#clientesTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalClientesCounter').text(response.total);
                                updateCounters();
                            }
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) this._tooltip.dispose();
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar los resultados');
                        $('#clientesTableBody').html(`
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

            // Búsqueda por texto
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults(searchTerm);
                }, 500);
            });

            // Limpiar filtros
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                loadResults();
            });

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
                        $('#clientesTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando página...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#clientesTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalClientesCounter').text(response.total);
                                updateCounters();
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

            // FUNCIÓN TOAST
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

            // Validación Bootstrap
            (function() {
                'use strict';
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        });
    </script>
@endpush
