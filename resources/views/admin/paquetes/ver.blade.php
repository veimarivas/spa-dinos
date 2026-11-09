@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-info">
                            <i class="ri-stack-line me-2 text-info"></i>Detalle del Paquete: {{ $paquete->nombre }}
                        </h4>
                        <p class="text-muted mb-0">Gestión de planes asociados a este paquete</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.paquetes.listar') }}" class="btn btn-secondary btn-lg">
                            <i class="ri-arrow-go-back-line me-1"></i> Volver a Paquetes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del paquete (resumen) -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle fs-24">
                                        <i class="ri-stack-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Planes</p>
                                <h4 class="mb-0" id="totalPlanesCounter">{{ $paquete->planes_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line fs-24 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-semibold">Descripción del paquete</h6>
                                <p class="text-muted mb-0">{{ $paquete->descripcion ?: 'Sin descripción' }}</p>
                                <small class="text-muted">Creado: {{ $paquete->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Planes -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div
                        class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-info-subtle">
                        <h5 class="card-title mb-0 fw-bold text-info">
                            <i class="ri-price-tag-3-line me-2"></i>Planes del Paquete
                        </h5>
                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrarPlanModal">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Plan
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Búsqueda -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchPlanes" class="form-control form-control-lg ps-5"
                                        placeholder="Buscar plan por nombre o descripción...">
                                    <i
                                        class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" id="clearPlanesSearch" class="btn btn-outline-secondary btn-lg">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Planes -->
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-info text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Nombre del Plan</th>
                                        <th class="px-3 py-3 text-center" width="10%">Sesiones</th>
                                        <th class="px-3 py-3 text-center" width="10%">Precio (Bs)</th>
                                        <th class="px-3 py-3 text-center" width="10%">Precio Esp.</th>
                                        <th class="px-3 py-3" width="30%">Servicios</th>
                                        <th class="px-3 py-3">Descripción</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="planesTableBody">
                                    @include('admin.planes.partials.table-body', [
                                        'planes' => $paquete->planes()->paginate(10, ['*'], 'page', request('page')),
                                    ])
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación y contador -->
                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                            <div class="results-count text-muted" id="planesResultsInfo"></div>
                            <div class="pagination-container" id="planesPagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REGISTRAR PLAN -->
    <div class="modal fade" id="registrarPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info-subtle p-3">
                    <h5 class="modal-title fw-bold text-info">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Plan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addPlanForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="paquete_id" value="{{ $paquete->id }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Nombre del Plan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nombre_plan" id="nombre_plan_registro"
                                    class="form-control form-control-lg border-info" required>
                                <small id="feedback_plan_registro" class="form-text"></small>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Sesiones <span class="text-danger">*</span></label>
                                <input type="number" name="cantidad_sesiones"
                                    class="form-control form-control-lg border-info" min="1" value="1"
                                    required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Precio (Bs) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="precio"
                                    class="form-control form-control-lg border-info" min="0" value="0.00"
                                    required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Precio Especial (Bs)</label>
                                <input type="number" step="0.01" name="precio_especial"
                                    class="form-control form-control-lg border-info" min="0" value=""
                                    placeholder="Opcional">
                                <small class="form-text text-muted">Tarifa especial para suscripciones</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Descripción</label>
                                <textarea name="descripcion" rows="3" class="form-control form-control-lg border-info" maxlength="500"></textarea>
                                <small class="form-text text-muted">Máximo 500 caracteres</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info btn-lg addPlanBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR PLAN -->
    <div class="modal fade" id="editarPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info-subtle p-3">
                    <h5 class="modal-title fw-bold text-info">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Plan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPlanForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_plan_id">
                    <input type="hidden" name="paquete_id" value="{{ $paquete->id }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Nombre del Plan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nombre_plan" id="nombre_plan_edicion"
                                    class="form-control form-control-lg border-info" required>
                                <small id="feedback_plan_edicion" class="form-text"></small>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Sesiones <span class="text-danger">*</span></label>
                                <input type="number" name="cantidad_sesiones" id="edit_cantidad_sesiones"
                                    class="form-control form-control-lg border-info" min="1" required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Precio (Bs) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="precio" id="edit_precio"
                                    class="form-control form-control-lg border-info" min="0" required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-medium">Precio Especial (Bs)</label>
                                <input type="number" step="0.01" name="precio_especial" id="edit_precio_especial"
                                    class="form-control form-control-lg border-info" min="0" placeholder="Opcional">
                                <small class="form-text text-muted">Tarifa especial para suscripciones</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Descripción</label>
                                <textarea name="descripcion" id="edit_descripcion" rows="3" class="form-control form-control-lg border-info"
                                    maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info btn-lg updatePlanBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ELIMINAR PLAN -->
    <div class="modal fade" id="eliminarPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3">
                    <h5 class="modal-title fw-bold text-danger">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deletePlanForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_plan_id">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-2xl">
                                    <i class="ri-alert-line"></i>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3 fw-bold text-danger" id="deletePlanName"></h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el plan. Las suscripciones
                            asociadas quedarán inválidas.</p>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDeletePlan">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i> Sí, Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL GESTIONAR SERVICIOS -->
    <div class="modal fade" id="serviciosPlanModal" tabindex="-1" aria-labelledby="serviciosPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success-subtle p-3">
                    <h5 class="modal-title fw-bold text-success" id="serviciosPlanModalLabel">
                        <i class="ri-service-line me-2 align-bottom"></i>Servicios del Plan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- El contenido se carga vía AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando servicios...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary btn-lg"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-lg" id="guardarServiciosBtn" disabled>
                        <i class="ri-save-3-line me-1 align-bottom"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const paqueteId = {{ $paquete->id }};
            let debounceTimer;
            let isProcessing = false;

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Cargar tabla de planes vía AJAX
            function loadPlanes(search = '', page = 1) {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.planes.listar') }}',
                    method: 'GET',
                    data: {
                        paquete_id: paqueteId,
                        search: search,
                        page: page
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#planesTableBody').html(`
                            <tr><td colspan="6" class="text-center py-5">
                                <div class="spinner-border text-info" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando planes...</p>
                            </td></tr>
                        `);
                    },
                    success: function(response) {
                        $('#planesTableBody').html(response.html);
                        $('#planesPagination').html(response.pagination);
                        $('#planesResultsInfo').html(
                            `Mostrando <span class="fw-medium text-info">${response.from || 0}</span> a 
                             <span class="fw-medium text-info">${response.to || 0}</span> de 
                             <span class="fw-medium text-info">${response.total}</span> resultados`
                        );
                        $('#totalPlanesCounter').text(response.total);
                        isProcessing = false;
                    },
                    error: function(xhr) {
                        console.error('Error al cargar planes:', xhr);
                        showToast('error', 'Error al cargar los planes');
                        $('#planesTableBody').html(`
                            <tr><td colspan="6" class="text-center py-5 text-danger">
                                <i class="ri-error-warning-line display-5"></i>
                                <p class="mt-2">Error al cargar los datos</p>
                            </td></tr>
                        `);
                        isProcessing = false;
                    }
                });
            }

            // Búsqueda en tiempo real
            $('#searchPlanes').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadPlanes($(this).val().trim());
                }, 500);
            });

            // Limpiar búsqueda
            $('#clearPlanesSearch').on('click', function() {
                $('#searchPlanes').val('');
                loadPlanes();
            });

            // Paginación (delegación de eventos)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;
                let page = $(this).attr('href').split('page=')[1];
                let search = $('#searchPlanes').val().trim();
                loadPlanes(search, page);
            });

            // ----- REGISTRO DE PLAN -----
            $('#nombre_plan_registro').on('input', function() {
                let nombre = $(this).val().trim();
                let feedback = $('#feedback_plan_registro');
                let submitBtn = $('.addPlanBtn');
                let input = $(this);

                if (nombre.length < 2) {
                    feedback.removeClass('text-success').addClass('text-danger')
                        .text('El nombre debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: '{{ route('admin.planes.verificar') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: nombre,
                            paquete_id: paqueteId
                        },
                        beforeSend: () => feedback.html(
                            '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                        ),
                        success: (res) => {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre ya existe en este paquete'
                                );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: () => {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar'
                            );
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 500);
            });

            $('#addPlanForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                let form = this;
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                let btn = $('.addPlanBtn');
                let originalHtml = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: '{{ route('admin.planes.registrar') }}',
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.success) {
                            let modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrarPlanModal'));
                            if (modal) modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadPlanes($('#searchPlanes').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al registrar');
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Error al registrar el plan';
                        showToast('error', msg);
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ----- EDITAR PLAN -----
            $(document).on('click', '.editPlanBtn', function() {
                let data = $(this).data('bs-obj');
                $('#edit_plan_id').val(data.id);
                $('#nombre_plan_edicion').val(data.nombre_plan);
                $('#edit_cantidad_sesiones').val(data.cantidad_sesiones);
                $('#edit_precio').val(data.precio);
                $('#edit_precio_especial').val(data.precio_especial || '');
                $('#edit_descripcion').val(data.descripcion || '');
                $('#feedback_plan_edicion').text('');
                $('.updatePlanBtn').prop('disabled', true); // Deshabilitar botón de actualizar
                setTimeout(() => $('#nombre_plan_edicion').trigger('input'), 100);
            });

            $('#nombre_plan_edicion').on('input', function() {
                let nombre = $(this).val().trim();
                let feedback = $('#feedback_plan_edicion');
                let submitBtn = $('.updatePlanBtn');
                let id = $('#edit_plan_id').val();

                if (nombre.length < 2) {
                    feedback.removeClass('text-success').addClass('text-danger').text(
                        'Mínimo 2 caracteres');
                    submitBtn.prop('disabled', true);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: '{{ route('admin.planes.verificaredicion') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: nombre,
                            id: id,
                            paquete_id: paqueteId
                        },
                        beforeSend: () => feedback.html(
                            '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                        ),
                        success: (res) => {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este nombre ya existe en este paquete'
                                );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Nombre disponible'
                                );
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: () => {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error de verificación'
                            );
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 500);
            });

            $('#editPlanForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                let form = this;
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                isProcessing = true;
                let btn = $('.updatePlanBtn');
                let originalHtml = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');

                $.ajax({
                    url: '{{ route('admin.planes.modificar') }}',
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.success) {
                            let modal = bootstrap.Modal.getInstance(document.getElementById(
                                'editarPlanModal'));
                            if (modal) modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadPlanes($('#searchPlanes').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar');
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Error al actualizar el plan';
                        showToast('error', msg);
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ----- ELIMINAR PLAN -----
            $(document).on('click', '.deletePlanBtn', function() {
                let data = $(this).data('bs-obj');
                $('#delete_plan_id').val(data.id);
                $('#deletePlanName').text(`¿Eliminar el plan "${data.nombre_plan}"?`);
            });

            $('#deletePlanForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                let btn = $('.btnDeletePlan');
                let originalHtml = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: '{{ route('admin.planes.eliminar') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            let modal = bootstrap.Modal.getInstance(document.getElementById(
                                'eliminarPlanModal'));
                            if (modal) modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadPlanes($('#searchPlanes').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar');
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.msg || 'Error al eliminar el plan';
                        showToast('error', msg);
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ----- GESTIONAR SERVICIOS -----
            $(document).on('click', '.manageServiciosBtn', function() {
                let planId = $(this).data('plan-id');
                let planName = $(this).data('plan-name');

                $('#serviciosPlanModalLabel').text('Servicios del Plan: ' + planName);
                $('#serviciosPlanModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando servicios...</p>
                    </div>
                `);
                $('#guardarServiciosBtn').prop('disabled', true);

                let url = '{{ route('admin.planes.servicios', ':planId') }}'.replace(':planId', planId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        if (res.html) {
                            $('#serviciosPlanModal .modal-body').html(res.html);
                            $('#guardarServiciosBtn').prop('disabled', false);
                        } else {
                            $('#serviciosPlanModal .modal-body').html(
                                '<p class="text-danger">Error al cargar servicios.</p>');
                        }
                    },
                    error: function() {
                        $('#serviciosPlanModal .modal-body').html(
                            '<p class="text-danger">Error al cargar servicios.</p>');
                    }
                });
            });

            // Guardar servicios (submit del formulario cargado dinámicamente)
            $(document).on('submit', '#serviciosForm', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                let btn = $('#guardarServiciosBtn');
                let originalHtml = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...');

                let planId = $('#serviciosForm input[name="plan_id"]').val();
                let saveUrl = '{{ route('admin.planes.guardarServicios', ':planId') }}'.replace(':planId',
                    planId);

                $.ajax({
                    url: saveUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            let modal = bootstrap.Modal.getInstance(document.getElementById(
                                'serviciosPlanModal'));
                            if (modal) modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadPlanes($('#searchPlanes').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al guardar servicios');
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.msg || 'Error al guardar servicios';
                        showToast('error', msg);
                    },
                    complete: function() {
                        isProcessing = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Vincular el clic del botón guardar del modal con el submit del formulario
            $(document).on('click', '#guardarServiciosBtn', function() {
                $('#serviciosForm').submit();
            });

            // Limpiar formularios al cerrar modales
            $('#registrarPlanModal, #editarPlanModal, #eliminarPlanModal, #serviciosPlanModal').on(
                'hidden.bs.modal',
                function() {
                    if (this.id === 'registrarPlanModal') {
                        $('#addPlanForm')[0].reset();
                        $('#addPlanForm').removeClass('was-validated');
                        $('#feedback_plan_registro').text('');
                        $('.addPlanBtn').prop('disabled', true);
                    } else if (this.id === 'editarPlanModal') {
                        $('#editPlanForm')[0].reset();
                        $('#editPlanForm').removeClass('was-validated');
                        $('#feedback_plan_edicion').text('');
                        $('.updatePlanBtn').prop('disabled', true);
                    }
                    // El modal de servicios se limpia al abrir, no es necesario resetear
                });

            // Función para mostrar toast
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
                    <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert">
                        <div class="toast-header ${toastConfig.bgClass} text-white">
                            <i class="${toastConfig.icon} me-2"></i>
                            <strong class="me-auto">${toastConfig.title}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
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
                new bootstrap.Toast(document.getElementById(toastId), {
                    delay: 3000
                }).show();
            }
        });
    </script>
@endpush
