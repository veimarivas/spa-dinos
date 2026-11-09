@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-service-line me-2 text-primary"></i>Gestión de Servicios
                        </h4>
                        <p class="text-muted mb-0">Administra los servicios ofrecidos en el SPA para bebés</p>
                    </div>

                    <div class="page-title-right">
                        @can('servicios.registrar')
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Servicio
                        </button>
                        @endcan
                    </div>

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
                                        <i class="ri-service-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Servicios</p>
                                <h4 class="mb-0" id="totalServiciosCounter">{{ $servicios->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar servicio por nombre o descripción..."
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
                            <i class="ri-list-check me-2"></i>Listado de Servicios
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Nombre</th>
                                        <th class="px-3 py-3" width="30%">Descripción</th>
                                        <th class="px-3 py-3 text-center" width="13%">Pago c/Sesión</th>
                                        <th class="px-3 py-3 text-center" width="13%">Pago s/Sesión</th>
                                        <th class="px-3 py-3 text-center" width="13%">Pago Especial</th>
                                        <th class="px-3 py-3 text-center" width="8%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="serviciosTableBody">
                                    @include('admin.servicios.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($servicios->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $servicios->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $servicios->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $servicios->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $servicios->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    @can('servicios.registrar')
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Servicio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="nombre_registro" class="form-label fw-medium">
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Servicio
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="nombre_registro" name="nombre"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Masaje infantil, Estimulación temprana" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del servicio</div>
                                    <small id="feedback_registro" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_registro" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripción
                                    </label>
                                    <textarea id="descripcion_registro" name="descripcion" class="form-control form-control-lg border-primary"
                                        placeholder="Describe brevemente este servicio..." rows="3"></textarea>
                                    <small class="form-text text-muted">Máximo 500 caracteres</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_con_sesion_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago con Sesión
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_con_sesion_registro" name="pago_con_sesion"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio con sesión (mínimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_sin_sesion_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago sin Sesión
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_sin_sesion_registro" name="pago_sin_sesion"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio sin sesión (mínimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_especial_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago Especial
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_especial_registro" name="pago_especial"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <small class="form-text text-muted">Opcional. Precio especial si aplica</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Servicio</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Modificar -->
    @can('servicios.editar')
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Servicio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="servicioId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="nombre_edicion" class="form-label fw-medium">
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Servicio
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="nombre_edicion" name="nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del servicio</div>
                                    <small id="feedback_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_edicion" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripción
                                    </label>
                                    <textarea id="descripcion_edicion" name="descripcion" class="form-control form-control-lg border-primary"
                                        rows="3"></textarea>
                                    <small class="form-text text-muted">Máximo 500 caracteres</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_con_sesion_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago con Sesión
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_con_sesion_edicion" name="pago_con_sesion"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio con sesión (mínimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_sin_sesion_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago sin Sesión
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_sin_sesion_edicion" name="pago_sin_sesion"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio sin sesión (mínimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pago_especial_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Pago Especial
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" id="pago_especial_edicion" name="pago_especial"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <small class="form-text text-muted">Opcional. Precio especial si aplica</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Servicio</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Eliminar -->
    @can('servicios.eliminar')
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
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
                        <h4 class="mb-3 fw-bold text-danger">¿Estás seguro de eliminar este servicio?</h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el servicio.
                            Las sesiones asociadas deberán ser reasignadas manualmente.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">Se recomienda verificar que no existan sesiones
                                asociadas antes de eliminar.</small>
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
        /* Estilos específicos para Servicios (usando primary de Bootstrap) */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }

        /* Tarjetas con efecto hover */
        .card:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        /* Botones de acción */
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

        /* Animación para nuevos servicios */
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

            // Contador de servicios con sesiones
            function updateSesionesCount() {
                let count = 0;
                $('tr').each(function() {
                    let badge = $(this).find('.sesiones-count');
                    if (badge.length && parseInt(badge.text()) > 0) {
                        count++;
                    }
                });
                $('#conSesionesCounter').text(count);
            }

            // Llamar inicialmente
            updateSesionesCount();

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

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.servicios.verificar') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Este servicio ya existe'
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
                const id = $('#servicioId').val();
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

                if (nombre.length > 100) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 100 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.servicios.verificaredicion') }}",
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
                                    '<i class="ri-error-warning-line me-1"></i> Este servicio ya existe'
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

            // Validar descripción en tiempo real
            $('#descripcion_registro, #descripcion_edicion').on('input', function() {
                const descripcion = $(this).val().trim();
                if (descripcion.length > 500) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.form-text').addClass('text-danger').text('Máximo 500 caracteres');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.form-text').removeClass('text-danger').text('Máximo 500 caracteres');
                }
            });

            // Editar servicio
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#servicioId').val(data.id);
                $('#nombre_edicion').val(data.nombre);
                $('#descripcion_edicion').val(data.descripcion || '');
                $('#pago_con_sesion_edicion').val(data.pago_con_sesion ?? '');
                $('#pago_sin_sesion_edicion').val(data.pago_sin_sesion ?? '');
                $('#pago_especial_edicion').val(data.pago_especial ?? '');

                // Reset y activar verificación
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion').removeClass('is-valid is-invalid');

                // Forzar verificación
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                }, 100);
            });

            // Eliminar servicio
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminar .modal-body h4').text(
                    `¿Estás seguro de eliminar el servicio "${data.nombre}"?`);
            });

            // REGISTRAR SERVICIO
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
                    url: "{{ route('admin.servicios.registrar') }}",
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
                            showToast('error', res.msg || 'Error al registrar el servicio');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el servicio. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
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

            // ACTUALIZAR SERVICIO
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
                    url: "{{ route('admin.servicios.modificar') }}",
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
                            showToast('error', res.msg || 'Error al actualizar el servicio');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el servicio. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre) {
                            errorMsg = xhr.responseJSON.errors.nombre[0];
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

            // ELIMINAR SERVICIO
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.servicios.eliminar') }}",
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
                            showToast('error', res.msg || 'Error al eliminar el servicio');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el servicio. Intenta nuevamente.';
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

            // FUNCIÓN PARA CARGAR RESULTADOS
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.servicios.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#serviciosTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando servicios...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#serviciosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalServiciosCounter').text(response.total);
                                updateSesionesCount();
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
                        $('#serviciosTableBody').html(`
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
                        $('#serviciosTableBody').html(`
                            <tr>
                                <td colspan="5" class="text-center py-5">
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
                            $('#serviciosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalServiciosCounter').text(response.total);
                                updateSesionesCount();
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
