@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-truck-line me-2 text-primary"></i>Gesti&oacute;n de Proveedores
                        </h4>
                        <p class="text-muted mb-0">Administra los proveedores de productos</p>
                    </div>

                    <div class="page-title-right">
                        @can('proveedores.registrar')
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Proveedor
                        </button>
                        @endcan
                    </div>

                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-7">
                <div class="row">
                    <div class="col-xl-4 col-md-4">
                        <div class="card border border-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div
                                                class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                                <i class="ri-truck-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Proveedores</p>
                                        <h4 class="mb-0" id="totalProveedoresCounter">{{ App\Models\Proveedore::count() }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <div class="card border border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div
                                                class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Activos</p>
                                        <h4 class="mb-0" id="activosCounter">
                                            {{ App\Models\Proveedore::where('estado', 'activo')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <div class="card border border-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div
                                                class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-24">
                                                <i class="ri-close-circle-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Inactivos</p>
                                        <h4 class="mb-0" id="inactivosCounter">
                                            {{ App\Models\Proveedore::where('estado', 'inactivo')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar proveedor por nombre, NIT o contacto..."
                                        value="{{ request('search') ?? '' }}">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i>
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
                            <i class="ri-list-check me-2"></i>Listado de Proveedores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Nombre</th>
                                        <th class="px-3 py-3" width="15%">NIT</th>
                                        <th class="px-3 py-3" width="15%">Contacto</th>
                                        <th class="px-3 py-3" width="12%">Celular</th>
                                        <th class="px-3 py-3 text-center" width="13%">Estado</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="proveedoresTableBody">
                                    @include('admin.proveedores.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($proveedores->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $proveedores->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $proveedores->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $proveedores->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $proveedores->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    @can('proveedores.registrar')
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Proveedor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="nombre_registro" class="form-label fw-medium">
                                        <i class="ri-building-line me-1 text-primary"></i>Nombre del Proveedor
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="nombre_registro" name="nombre"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Distribuidora ABC, Importadora XYZ" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del proveedor</div>
                                    <small id="feedback_registro" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nit_registro" class="form-label fw-medium">
                                        <i class="ri-file-list-line me-1 text-primary"></i>NIT
                                    </label>
                                    <input type="text" id="nit_registro" name="nit"
                                        class="form-control form-control-lg border-primary" placeholder="Ej: 123456789">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="contacto_nombre_registro" class="form-label fw-medium">
                                        <i class="ri-user-line me-1 text-primary"></i>Nombre de Contacto
                                    </label>
                                    <input type="text" id="contacto_nombre_registro" name="contacto_nombre"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Nombre de la persona de contacto">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="celular_registro" class="form-label fw-medium">
                                        <i class="ri-phone-line me-1 text-primary"></i>Celular
                                    </label>
                                    <input type="text" id="celular_registro" name="celular"
                                        class="form-control form-control-lg border-primary" placeholder="Ej: 70012345">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="correo_registro" class="form-label fw-medium">
                                        <i class="ri-mail-line me-1 text-primary"></i>Correo
                                    </label>
                                    <input type="email" id="correo_registro" name="correo"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="correo@ejemplo.com">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="estado_registro" class="form-label fw-medium">
                                        <i class="ri-toggle-line me-1 text-primary"></i>Estado
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="estado_registro" name="estado"
                                        class="form-select form-select-lg border-primary" required>
                                        <option value="activo" selected>Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="direccion_registro" class="form-label fw-medium">
                                        <i class="ri-map-pin-line me-1 text-primary"></i>Direcci&oacute;n
                                    </label>
                                    <textarea id="direccion_registro" name="direccion" class="form-control form-control-lg border-primary"
                                        placeholder="Direcci&oacute;n del proveedor..." rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Proveedor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Modificar -->
    @can('proveedores.editar')
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Proveedor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="proveedorId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="nombre_edicion" class="form-label fw-medium">
                                        <i class="ri-building-line me-1 text-primary"></i>Nombre del Proveedor
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="nombre_edicion" name="nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del proveedor</div>
                                    <small id="feedback_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nit_edicion" class="form-label fw-medium">
                                        <i class="ri-file-list-line me-1 text-primary"></i>NIT
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="nit_edicion" name="nit">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="contacto_nombre_edicion" class="form-label fw-medium">
                                        <i class="ri-user-line me-1 text-primary"></i>Nombre de Contacto
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="contacto_nombre_edicion" name="contacto_nombre">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="celular_edicion" class="form-label fw-medium">
                                        <i class="ri-phone-line me-1 text-primary"></i>Celular
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="celular_edicion" name="celular">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="correo_edicion" class="form-label fw-medium">
                                        <i class="ri-mail-line me-1 text-primary"></i>Correo
                                    </label>
                                    <input type="email" class="form-control form-control-lg border-primary"
                                        id="correo_edicion" name="correo">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="estado_edicion" class="form-label fw-medium">
                                        <i class="ri-toggle-line me-1 text-primary"></i>Estado
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="estado_edicion" name="estado"
                                        class="form-select form-select-lg border-primary" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="direccion_edicion" class="form-label fw-medium">
                                        <i class="ri-map-pin-line me-1 text-primary"></i>Direcci&oacute;n
                                    </label>
                                    <textarea id="direccion_edicion" name="direccion" class="form-control form-control-lg border-primary"
                                        rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Proveedor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Eliminar -->
    @can('proveedores.eliminar')
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminaci&oacute;n
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
                        <h4 class="mb-3 fw-bold text-danger">Est&aacute;s seguro de eliminar este proveedor?</h4>
                        <p class="text-muted mb-0">Esta acci&oacute;n eliminar&aacute; permanentemente el proveedor.
                            Las compras asociadas deber&aacute;n ser verificadas antes de continuar.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">Se recomienda verificar que no existan compras
                                asociadas antes de eliminar.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">S&iacute;, Eliminar</span>
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
        /* Estilos espec&iacute;ficos para Proveedores */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }

        /* Tarjetas con efecto hover */
        .card:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        /* Botones de acci&oacute;n */
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

        /* Input con icono */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #0d6efd;
        }

        .input-with-icon input {
            padding-left: 40px;
        }

        /* Animaci&oacute;n para nuevos registros */
        @keyframes highlightNew {
            0% {
                background-color: rgba(13, 110, 253, 0.2);
            }

            100% {
                background-color: transparent;
            }
        }

        .highlight-new {
            animation: highlightNew 2s ease;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .page-title-box {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
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

            // Resetear formularios cuando se cierran los modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#feedback_registro').removeClass('text-success text-danger').text('');
                    $('.addBtn').prop('disabled', true);
                    $('#addForm').removeClass('was-validated');
                    $('#addForm .form-control').removeClass('is-valid is-invalid');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_edicion').removeClass('text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                    $('#updateForm .form-control').removeClass('is-valid is-invalid');
                }
            });

            // Verificar nombre al registrar
            $('#nombre_registro').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');
                const input = $(this);

                if (nombre.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (nombre.length < 2) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                if (nombre.length > 255) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 255 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.proveedores.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este proveedor ya existe'
                                );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Verificar nombre al editar
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#proveedorId').val();
                const input = $(this);

                if (nombre.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (nombre.length < 2) {
                    feedback.addClass('text-danger').text('El nombre debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                if (nombre.length > 255) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 255 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.proveedores.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            id: id
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este proveedor ya existe'
                                );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Editar proveedor
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#proveedorId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#nit_edicion').val(data.nit || '');
                $('#contacto_nombre_edicion').val(data.contacto_nombre || '');
                $('#celular_edicion').val(data.celular || '');
                $('#correo_edicion').val(data.correo || '');
                $('#direccion_edicion').val(data.direccion || '');
                $('#estado_edicion').val(data.estado || 'activo');

                // Reset y activar verificacion
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion').removeClass('is-valid is-invalid');

                // Forzar verificacion
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                }, 100);
            });

            // Eliminar proveedor
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminar .modal-body h4').text(
                    `\u00bfEst\u00e1s seguro de eliminar el proveedor "${data.nombre}"?`);
            });

            // REGISTRAR PROVEEDOR
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
                    url: "{{ route('admin.proveedores.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrar'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el proveedor');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el proveedor. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.errors?.nit) {
                            errorMsg = xhr.responseJSON.errors.nit[0];
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

            // ACTUALIZAR PROVEEDOR
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
                    url: "{{ route('admin.proveedores.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            var modalEl = document.getElementById('modalModificar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                            else $('#modalModificar').modal('hide');

                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el proveedor');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el proveedor. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
                        } else if (xhr.responseJSON?.errors?.nit) {
                            errorMsg = xhr.responseJSON.errors.nit[0];
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

            // ELIMINAR PROVEEDOR
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.proveedores.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            var modalEl = document.getElementById('modalEliminar');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                            else $('#modalEliminar').modal('hide');

                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el proveedor');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el proveedor. Intenta nuevamente.';
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

            // FUNCION PARA CARGAR RESULTADOS
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.proveedores.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#proveedoresTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando proveedores...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#proveedoresTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.filtered}</span> resultados`
                                );
                                $('#totalProveedoresCounter').text(response.total);
                                $('#activosCounter').text(response.activos);
                                $('#inactivosCounter').text(response.inactivos);
                            }

                            // Re-inicializar tooltips
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) {
                                    this._tooltip.dispose();
                                }
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading results:', xhr);
                        showToast('error', 'Error al cargar los resultados');
                        $('#proveedoresTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5 text-danger">
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

            // Busqueda por texto
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

            // Manejar paginacion
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
                        $('#proveedoresTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando p\u00e1gina...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#proveedoresTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.filtered}</span> resultados`
                                );
                                $('#totalProveedoresCounter').text(response.total);
                                $('#activosCounter').text(response.activos);
                                $('#inactivosCounter').text(response.inactivos);
                            }

                            const newUrl = url + (search ? '&search=' + encodeURIComponent(
                                search) : '');
                            window.history.pushState({}, '', newUrl);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la p\u00e1gina');
                    }
                });
            });

            // FUNCION TOAST
            function showToast(type, message) {
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: '\u00c9xito'
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
                        title: 'Informaci\u00f3n'
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

            // Validacion Bootstrap
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
