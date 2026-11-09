@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-price-tag-3-line me-2 text-primary"></i>Gestión de Planes
                        </h4>
                        <p class="text-muted mb-0">Administra los planes de suscripción para bebés</p>
                    </div>


                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Plan
                        </button>
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
                                <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                    <i class="ri-price-tag-3-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Planes</p>
                                <h4 class="mb-0" id="totalPlanesCounter">{{ $planes->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info bg-opacity-10">
                                    <i class="ri-user-heart-line fs-24 text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Con Suscripciones</p>
                                <h4 class="mb-0" id="conSuscripcionesCounter">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar plan por nombre o descripción..."
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
                            <i class="ri-list-check me-2"></i>Listado de Planes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Nombre del Plan</th>
                                        <th class="px-3 py-3 text-center" width="10%">Sesiones</th>
                                        <th class="px-3 py-3 text-center" width="10%">Precio</th>
                                        <th class="px-3 py-3" width="30%">Descripción</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="planesTableBody">
                                    @include('admin.planes.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($planes->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $planes->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $planes->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $planes->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $planes->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Plan
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
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Plan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="nombre_registro" name="nombre_plan"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Básico, Premium, Oro" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del plan</div>
                                    <small id="feedback_registro" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sesiones_registro" class="form-label fw-medium">
                                        <i class="ri-calendar-check-line me-1 text-primary"></i>Cantidad de Sesiones
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="sesiones_registro" name="cantidad_sesiones"
                                        class="form-control form-control-lg border-primary" min="1" value="1"
                                        required>
                                    <div class="invalid-feedback">Debe tener al menos 1 sesión</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_registro" class="form-label fw-medium">
                                        <i class="ri-money-euro-circle-line me-1 text-primary"></i>Precio (Bs.)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="precio_registro" name="precio"
                                        class="form-control form-control-lg border-primary" step="0.01" min="0"
                                        required>
                                    <div class="invalid-feedback">Ingresa un precio válido (0 o mayor)</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_registro" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripción
                                    </label>
                                    <textarea id="descripcion_registro" name="descripcion" class="form-control form-control-lg border-primary"
                                        placeholder="Describe brevemente este plan..." rows="3"></textarea>
                                    <small class="form-text text-muted">Máximo 500 caracteres</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Plan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="planId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="nombre_edicion" class="form-label fw-medium">
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Plan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="nombre_edicion" name="nombre_plan" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del plan</div>
                                    <small id="feedback_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sesiones_edicion" class="form-label fw-medium">
                                        <i class="ri-calendar-check-line me-1 text-primary"></i>Cantidad de Sesiones
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="sesiones_edicion" name="cantidad_sesiones"
                                        class="form-control form-control-lg border-primary" min="1" required>
                                    <div class="invalid-feedback">Debe tener al menos 1 sesión</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_edicion" class="form-label fw-medium">
                                        <i class="ri-money-euro-circle-line me-1 text-primary"></i>Precio (Bs.)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="precio_edicion" name="precio"
                                        class="form-control form-control-lg border-primary" step="0.01" min="0"
                                        required>
                                    <div class="invalid-feedback">Ingresa un precio válido (0 o mayor)</div>
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
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
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
                        <h4 class="mb-3 fw-bold text-danger">¿Estás seguro de eliminar este plan?</h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el plan.
                            Las suscripciones asociadas deberán ser reasignadas manualmente.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">Se recomienda verificar que no existan suscripciones
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
@endsection

@push('style')
    <style>
        /* Estilos específicos para Planes con tema primario (azul) */
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border-color: #0d6efd;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7, #0949b0);
            border-color: #0a58ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.08) !important;
        }

        .border-primary {
            border-color: rgba(13, 110, 253, 0.3) !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

        .table-primary {
            --bs-table-bg: rgba(13, 110, 253, 0.05);
            --bs-table-border-color: rgba(13, 110, 253, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }

        .badge-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, 0.2);
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

        /* Animación para nuevos planes */
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

            // Contador de planes con suscripciones
            function updateSuscripcionesCount() {
                let count = 0;
                $('tr').each(function() {
                    // Se asume que en la tabla hay un elemento con clase .suscripciones-count (opcional)
                    // Por simplicidad, podemos contar filas que tengan badge de suscripciones >0
                    // O podemos confiar en que el backend enviará el dato y lo pondremos en un atributo data
                    // Aquí implementaremos una versión simple: contamos las filas que tienen badge con clase .suscripciones-badge y texto >0
                    let badge = $(this).find('.suscripciones-badge');
                    if (badge.length && parseInt(badge.text()) > 0) {
                        count++;
                    }
                });
                $('#conSuscripcionesCounter').text(count);
            }

            // Llamar inicialmente (el badge se define en table-body)
            updateSuscripcionesCount();

            // Resetear formularios cuando se cierran los modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#feedback_registro').removeClass('text-success text-danger').text('');
                    $('.addBtn').prop('disabled', true);
                    $('#addForm').removeClass('was-validated');
                    $('.form-control').removeClass('is-valid is-invalid');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_edicion').removeClass('text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                    $('.form-control').removeClass('is-valid is-invalid');
                }
            });

            // Verificar nombre al registrar (nombre_plan)
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
                        url: "{{ route('admin.planes.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre_plan: nombre
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre de plan ya existe'
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

            // Verificar nombre al editar (nombre_plan)
            $('#nombre_edicion').on('input', function() {
                const nombre = $(this).val().trim();
                const feedback = $('#feedback_edicion');
                const submitBtn = $('.updateBtn');
                const id = $('#planId').val();
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
                        url: "{{ route('admin.planes.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre_plan: nombre,
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
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre de plan ya existe'
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

            // Validar sesiones y precio en tiempo real
            $('#sesiones_registro, #sesiones_edicion').on('input', function() {
                const val = parseInt($(this).val());
                if (val < 1 || isNaN(val)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            $('#precio_registro, #precio_edicion').on('input', function() {
                const val = parseFloat($(this).val());
                if (val < 0 || isNaN(val)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
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

            // Editar plan
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#planId').val(data.id);
                $('#nombre_edicion').val(data.nombre_plan);
                $('#sesiones_edicion').val(data.cantidad_sesiones);
                $('#precio_edicion').val(data.precio);
                $('#descripcion_edicion').val(data.descripcion || '');

                // Reset y activar verificación
                $('#feedback_edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#nombre_edicion').removeClass('is-valid is-invalid');
                $('#sesiones_edicion, #precio_edicion').removeClass('is-invalid');

                // Forzar verificación de nombre
                setTimeout(() => {
                    $('#nombre_edicion').trigger('input');
                }, 100);
            });

            // Eliminar plan
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminar .modal-body h4').text(
                    `¿Estás seguro de eliminar el plan "${data.nombre_plan}"?`);
            });

            // REGISTRAR PLAN
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
                    url: "{{ route('admin.planes.registrar') }}",
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
                            showToast('error', res.msg || 'Error al registrar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el plan. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre_plan) {
                            errorMsg = xhr.responseJSON.errors.nombre_plan[0];
                        } else if (xhr.responseJSON?.errors?.cantidad_sesiones) {
                            errorMsg = xhr.responseJSON.errors.cantidad_sesiones[0];
                        } else if (xhr.responseJSON?.errors?.precio) {
                            errorMsg = xhr.responseJSON.errors.precio[0];
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

            // ACTUALIZAR PLAN
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
                    url: "{{ route('admin.planes.modificar') }}",
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
                            showToast('error', res.msg || 'Error al actualizar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el plan. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors?.nombre_plan) {
                            errorMsg = xhr.responseJSON.errors.nombre_plan[0];
                        } else if (xhr.responseJSON?.errors?.cantidad_sesiones) {
                            errorMsg = xhr.responseJSON.errors.cantidad_sesiones[0];
                        } else if (xhr.responseJSON?.errors?.precio) {
                            errorMsg = xhr.responseJSON.errors.precio[0];
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

            // ELIMINAR PLAN
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.planes.eliminar') }}",
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
                            showToast('error', res.msg || 'Error al eliminar el plan');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el plan. Intenta nuevamente.';
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
                    url: '{{ route('admin.planes.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#planesTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando planes...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#planesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalPlanesCounter').text(response.total);
                                updateSuscripcionesCount();
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
                        $('#planesTableBody').html(`
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
                        $('#planesTableBody').html(`
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
                            $('#planesTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalPlanesCounter').text(response.total);
                                updateSuscripcionesCount();
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

            // Exportar PDF
            $('#exportPDF').on('click', function(e) {
                e.preventDefault();
                showToast('info', 'Exportando a PDF... (Funcionalidad en desarrollo)');
            });

            // Exportar Excel
            $('#exportExcel').on('click', function(e) {
                e.preventDefault();
                showToast('info', 'Exportando a Excel... (Funcionalidad en desarrollo)');
            });

            // FUNCIÓN TOAST MEJORADA
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
