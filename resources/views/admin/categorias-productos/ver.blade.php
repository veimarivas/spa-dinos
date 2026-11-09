@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-warning">
                            <i class="ri-price-tag-3-line me-2"></i>
                            {{ $categoria->nombre }}
                        </h4>
                        <p class="text-muted mb-0">{{ $categoria->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.categorias-productos.listar') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="ri-arrow-left-line align-bottom me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-lg">
                                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle fs-2">
                                        <i class="ri-price-tag-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-bold text-warning mb-1">{{ $categoria->nombre }}</h5>
                                <p class="text-muted mb-0">{{ $categoria->descripcion ?? 'Sin descripción' }}</p>
                                <div class="mt-2">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        <i class="ri-box-3-line me-1"></i>{{ $categoria->productos_count }} productos
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Form -->
        @can('productos.registrar')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-light shadow-sm">
                    <div class="card-header bg-warning-subtle">
                        <h5 class="card-title mb-0 fw-bold text-warning">
                            <i class="ri-add-line me-2"></i>Registrar nuevo producto en esta categoría
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="addProductForm" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="categoria_producto_id" value="{{ $categoria->id }}">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="nombre_registro" class="form-label fw-medium">
                                            <i class="ri-font-size me-1 text-primary"></i>Nombre del Producto
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="nombre_registro" name="nombre"
                                            class="form-control form-control-lg" placeholder="Ej: Shampoo, Crema hidratante"
                                            required>
                                        <div class="invalid-feedback">Por favor ingresa el nombre del producto</div>
                                        <small id="feedback_registro" class="form-text mt-1"></small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="marca_registro" class="form-label fw-medium">
                                            <i class="ri-price-tag-3-line me-1 text-primary"></i>Marca
                                        </label>
                                        <input type="text" id="marca_registro" name="marca"
                                            class="form-control form-control-lg" placeholder="Ej: Johnson's, Mustela">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="estado_registro" class="form-label fw-medium">
                                            <i class="ri-toggle-line me-1 text-primary"></i>Estado
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="estado_registro" name="estado" class="form-select form-select-lg"
                                            required>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="precio_venta_registro" class="form-label fw-medium">
                                            <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Venta
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Bs</span>
                                            <input type="number" id="precio_venta_registro" name="precio_venta"
                                                class="form-control form-control-lg" placeholder="0.00" step="0.01"
                                                min="0" required>
                                        </div>
                                        <div class="invalid-feedback">Ingresa el precio de venta (mínimo 0)</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="precio_compra_registro" class="form-label fw-medium">
                                            <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Compra
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Bs</span>
                                            <input type="number" id="precio_compra_registro" name="precio_compra"
                                                class="form-control form-control-lg" placeholder="0.00" step="0.01"
                                                min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="stock_minimo_registro" class="form-label fw-medium">
                                            <i class="ri-alarm-warning-line me-1 text-primary"></i>Stock Mínimo
                                        </label>
                                        <input type="number" id="stock_minimo_registro" name="stock_minimo"
                                            class="form-control form-control-lg" placeholder="0" min="0"
                                            value="0">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="descripcion_registro" class="form-label fw-medium">
                                            <i class="ri-file-text-line me-1 text-primary"></i>Descripción
                                        </label>
                                        <textarea id="descripcion_registro" name="descripcion" class="form-control form-control-lg"
                                            placeholder="Describe brevemente este producto..." rows="3"></textarea>
                                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitProductBtn">
                                    <i class="ri-save-3-line me-1 align-bottom"></i> Registrar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Product List -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div
                        class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-warning-subtle">
                        <h5 class="card-title mb-0 fw-bold text-warning">
                            <i class="ri-list-check me-2"></i>Productos en esta categoría
                        </h5>
                        <small class="text-muted" id="resultInfo"></small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">#</th>
                                        <th>Producto</th>
                                        <th class="text-center" width="12%">Precio Venta</th>
                                        <th class="text-center" width="10%">Stock</th>
                                        <th class="text-center" width="10%">Estado</th>
                                        @canany(['productos.modificar', 'productos.eliminar'])
                                        <th class="text-center" width="12%">Acciones</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody id="productosTableBody">
                                    <!-- Products loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                            <div class="results-count text-muted" id="resultsCount"></div>
                            <div class="pagination-container" id="paginationContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for product edit/delete (copied from productos.listar.blade.php) -->
    @can('productos.modificar')
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="productoId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre_edicion" class="form-label fw-medium">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="nombre_edicion"
                                        name="nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del producto</div>
                                    <small id="feedback_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="marca_edicion" class="form-label fw-medium">Marca</label>
                                    <input type="text" class="form-control form-control-lg" id="marca_edicion"
                                        name="marca">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="categoria_edicion" class="form-label fw-medium">Categoría</label>
                                    <select id="categoria_edicion" name="categoria_producto_id"
                                        class="form-select form-select-lg">
                                        <option value="">Sin categoría</option>
                                        @foreach ($categorias as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="estado_edicion" class="form-label fw-medium">Estado <span
                                            class="text-danger">*</span></label>
                                    <select id="estado_edicion" name="estado" class="form-select form-select-lg"
                                        required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_edicion" class="form-label fw-medium">Descripción</label>
                                    <textarea id="descripcion_edicion" name="descripcion" class="form-control form-control-lg" rows="3"></textarea>
                                    <small class="form-text text-muted">Máximo 500 caracteres</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_compra_edicion" class="form-label fw-medium">Precio Compra</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_compra_edicion" name="precio_compra"
                                            class="form-control form-control-lg" placeholder="0.00" step="0.01"
                                            min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_venta_edicion" class="form-label fw-medium">Precio Venta <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_venta_edicion" name="precio_venta"
                                            class="form-control form-control-lg" placeholder="0.00" step="0.01"
                                            min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio de venta (mínimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="stock_minimo_edicion" class="form-label fw-medium">Stock Mínimo</label>
                                    <input type="number" id="stock_minimo_edicion" name="stock_minimo"
                                        class="form-control form-control-lg" placeholder="0" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    @can('productos.eliminar')
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger">
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
                        <h4 class="mb-3 fw-bold text-danger">¿Estás seguro de eliminar este producto?</h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el producto del inventario.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium"><i
                                    class="ri-error-warning-line me-1"></i>Advertencia:</small>
                            <small class="text-muted">No se podrá eliminar si el producto tiene compras o ventas
                                relacionadas.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i> Sí, Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    @push('style')
        <style>
            .table-hover tbody tr:hover {
                background-color: rgba(13, 110, 253, 0.04);
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
        </style>
    @endpush

    @push('script')
        <script>
            $(document).ready(function() {
                let isProcessing = false; // for registration
                let isLoadingProducts = false; // for product list loading
                const categoryId = {{ $categoria->id }};

                // Load product list
                function loadProductos(pageUrl = null) {
                    if (isLoadingProducts) return;
                    isLoadingProducts = true;

                    const url = pageUrl || '{{ route('admin.categorias-productos.productos', $categoria->id) }}';

                    $.ajax({
                        url: url,
                        method: 'GET',
                        beforeSend: function() {
                            $('#productosTableBody').html(
                                '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2 text-muted">Cargando productos...</p></td></tr>'
                                );
                        },
                        success: function(response) {
                            $('#productosTableBody').html(response.html);
                            $('#paginationContainer').html(response.pagination);
                            $('#resultsCount').html(
                                `Mostrando <span class="fw-medium text-primary">${response.from}</span> a <span class="fw-medium text-primary">${response.to}</span> de <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                            $('#resultInfo').text(
                                `Mostrando ${response.from} - ${response.to} de ${response.total}`);
                            bindProductButtons();
                        },
                        error: function() {
                            showToast('error', 'Error al cargar los productos');
                            $('#productosTableBody').html(
                                '<tr><td colspan="6" class="text-center py-5 text-danger"><i class="ri-error-warning-line display-5"></i><p class="mt-2">Error al cargar los datos</p></td></tr>'
                                );
                        },
                        complete: function() {
                            isLoadingProducts = false;
                        }
                    });
                }

                // Bind edit/delete buttons after table refresh
                function bindProductButtons() {
                    $('.editBtn').off('click').on('click', function() {
                        const data = $(this).data('bs-obj');
                        $('#productoId').val(data.id);
                        $('#nombre_edicion').val(data.nombre);
                        $('#marca_edicion').val(data.marca || '');
                        $('#categoria_edicion').val(data.categoria_producto_id || '');
                        $('#descripcion_edicion').val(data.descripcion || '');
                        $('#precio_compra_edicion').val(data.precio_compra ?? '');
                        $('#precio_venta_edicion').val(data.precio_venta ?? '');
                        $('#stock_minimo_edicion').val(data.stock_minimo ?? 0);
                        $('#estado_edicion').val(data.estado || 'activo');

                        $('#feedback_edicion').removeClass('text-success text-danger').text('');
                        $('.updateBtn').prop('disabled', false);
                        $('#nombre_edicion').removeClass('is-valid is-invalid');
                        setTimeout(() => {
                            $('#nombre_edicion').trigger('input');
                        }, 100);
                    });

                    $('.deleteBtn').off('click').on('click', function() {
                        const data = $(this).data('bs-obj');
                        $('#eliminarId').val(data.id);
                        $('#modalEliminar .modal-body h4').text(
                            `¿Estás seguro de eliminar el producto "${data.nombre}"?`);
                    });
                }

                // Pagination click handling
                $(document).on('click', '#paginationContainer a.page-link', function(e) {
                    e.preventDefault();
                    loadProductos($(this).attr('href'));
                });

                // Product registration form
                $('#addProductForm').submit(function(e) {
                    e.preventDefault();
                    if (isProcessing) return;

                    const form = $(this)[0];
                    if (!form.checkValidity()) {
                        e.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }

                    isProcessing = true;
                    const submitBtn = $('#submitProductBtn');
                    const originalHtml = submitBtn.html();
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                    $.ajax({
                        url: '{{ route('admin.productos.registrar') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.success) {
                                // Clear form
                                $('#addProductForm')[0].reset();
                                $('#addProductForm').removeClass('was-validated');
                                $('#feedback_registro').removeClass('text-success text-danger')
                                    .text('');
                                $('#nombre_registro').removeClass('is-valid is-invalid');
                                // Reload product list (now allowed because we set isProcessing false after this)
                                loadProductos();
                                showToast('success', res.msg);
                            } else {
                                showToast('error', res.msg || 'Error al registrar el producto');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al registrar el producto. Intenta nuevamente.';
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

                // Edit product form
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
                        url: '{{ route('admin.productos.modificar') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.success) {
                                $('#modalModificar').modal('hide');
                                loadProductos();
                                showToast('success', res.msg);
                            } else {
                                showToast('error', res.msg || 'Error al actualizar el producto');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al actualizar el producto. Intenta nuevamente.';
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

                // Delete product form
                $('#deleteForm').submit(function(e) {
                    e.preventDefault();
                    if (isProcessing) return;

                    isProcessing = true;
                    const submitBtn = $('.btnDelete');
                    const originalHtml = submitBtn.html();
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                    $.ajax({
                        url: '{{ route('admin.productos.eliminar') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.success) {
                                $('#modalEliminar').modal('hide');
                                loadProductos();
                                showToast('success', res.msg);
                            } else {
                                showToast('error', res.msg || 'Error al eliminar el producto');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al eliminar el producto. Intenta nuevamente.';
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

                // Name uniqueness check for registration
                let debounceTimer;

                function verificarNombreRegistro() {
                    const nombre = $('#nombre_registro').val().trim();
                    const marca = $('#marca_registro').val().trim();
                    const feedback = $('#feedback_registro');
                    const submitBtn = $('#submitProductBtn');
                    const input = $('#nombre_registro');

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
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        $.ajax({
                            url: '{{ route('admin.productos.verificar') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                nombre: nombre,
                                marca: marca
                            },
                            beforeSend: () => feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'),
                            success: (res) => {
                                if (res.exists) {
                                    feedback.addClass('text-danger').html(
                                        '<i class="ri-error-warning-line me-1"></i> Este producto ya existe'
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
                            error: () => {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                                    );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            }
                        });
                    }, 500);
                }

                $('#nombre_registro').on('input', verificarNombreRegistro);
                $('#marca_registro').on('input', verificarNombreRegistro);

                // Same verification for edit
                function verificarNombreEdicion() {
                    const nombre = $('#nombre_edicion').val().trim();
                    const marca = $('#marca_edicion').val().trim();
                    const feedback = $('#feedback_edicion');
                    const submitBtn = $('.updateBtn');
                    const id = $('#productoId').val();
                    const input = $('#nombre_edicion');

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
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        $.ajax({
                            url: '{{ route('admin.productos.verificaredicion') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                nombre: nombre,
                                marca: marca,
                                id: id
                            },
                            beforeSend: () => feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'),
                            success: (res) => {
                                if (res.exists) {
                                    feedback.addClass('text-danger').html(
                                        '<i class="ri-error-warning-line me-1"></i> Este producto ya existe'
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
                            error: () => {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-alert-line me-1"></i> Error al verificar el nombre'
                                    );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                            }
                        });
                    }, 500);
                }

                $('#nombre_edicion').on('input', verificarNombreEdicion);
                $('#marca_edicion').on('input', verificarNombreEdicion);

                // Initial load
                loadProductos();

                // Toast function
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
                        }
                    };
                    const toastConfig = config[type] || config.info;
                    const toastId = 'toast-' + Date.now();
                    const toastHtml = `
            <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                    <i class="${toastConfig.icon} me-2"></i>
                    <strong class="me-auto">${toastConfig.title}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
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
                    const toast = new bootstrap.Toast(toastElement, {
                        autohide: true,
                        delay: 3000
                    });
                    toast.show();
                    toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
                }
            });
        </script>
    @endpush
@endsection
