@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-box-3-line me-2 text-primary"></i>Gesti&oacute;n de Productos
                        </h4>
                        <p class="text-muted mb-0">Administra los productos del inventario</p>
                    </div>

                    @can('productos.registrar')
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Producto
                        </button>
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
                                        <i class="ri-box-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Productos</p>
                                <h4 class="mb-0" id="totalProductosCounter">{{ $total }}</h4>
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
                                        <i class="ri-checkbox-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Activos</p>
                                <h4 class="mb-0" id="activosCounter">{{ $activos }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle fs-24">
                                        <i class="ri-error-warning-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Stock Bajo</p>
                                <h4 class="mb-0" id="stockBajoCounter">{{ $stockBajo }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-24">
                                        <i class="ri-close-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Inactivos</p>
                                <h4 class="mb-0" id="inactivosCounter">{{ $inactivos }}</h4>
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
                                        placeholder="Buscar producto por nombre, marca o descripci&oacute;n..."
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
                            <i class="ri-list-check me-2"></i>Listado de Productos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="22%">Producto</th>
                                        <th class="px-3 py-3" width="13%">Categor&iacute;a</th>
                                        <th class="px-3 py-3 text-center" width="12%">Precio Compra</th>
                                        <th class="px-3 py-3 text-center" width="12%">Precio Venta</th>
                                        <th class="px-3 py-3 text-center" width="10%">Stock</th>
                                        <th class="px-3 py-3 text-center" width="10%">Estado</th>
                                        @canany(['productos.editar', 'productos.eliminar'])
                                        <th class="px-3 py-3 text-center" width="10%">Acciones</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody id="productosTableBody">
                                    @include('admin.productos.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($productos->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $productos->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $productos->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $productos->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $productos->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    @can('productos.registrar')
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre_registro" class="form-label fw-medium">
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Producto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="nombre_registro" name="nombre"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Shampoo, Crema hidratante" required>
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
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Johnson's, Mustela">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="categoria_registro" class="form-label fw-medium">
                                        <i class="ri-folder-line me-1 text-primary"></i>Categor&iacute;a
                                    </label>
                                    <select id="categoria_registro" name="categoria_producto_id"
                                        class="form-select form-select-lg border-primary">
                                        <option value="">Sin categor&iacute;a</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
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
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_registro" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripci&oacute;n
                                    </label>
                                    <textarea id="descripcion_registro" name="descripcion" class="form-control form-control-lg border-primary"
                                        placeholder="Describe brevemente este producto..." rows="3"></textarea>
                                    <small class="form-text text-muted">M&aacute;ximo 500 caracteres</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_compra_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Compra
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_compra_registro" name="precio_compra"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_venta_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Venta
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_venta_registro" name="precio_venta"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio de venta (m&iacute;nimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="stock_minimo_registro" class="form-label fw-medium">
                                        <i class="ri-alarm-warning-line me-1 text-primary"></i>Stock M&iacute;nimo
                                    </label>
                                    <input type="number" id="stock_minimo_registro" name="stock_minimo"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="0" min="0" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Producto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    <!-- Modal Modificar -->
    @can('productos.editar')
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
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
                                    <label for="nombre_edicion" class="form-label fw-medium">
                                        <i class="ri-font-size me-1 text-primary"></i>Nombre del Producto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="nombre_edicion" name="nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa el nombre del producto</div>
                                    <small id="feedback_edicion" class="form-text mt-1"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="marca_edicion" class="form-label fw-medium">
                                        <i class="ri-price-tag-3-line me-1 text-primary"></i>Marca
                                    </label>
                                    <input type="text" class="form-control form-control-lg border-primary"
                                        id="marca_edicion" name="marca">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="categoria_edicion" class="form-label fw-medium">
                                        <i class="ri-folder-line me-1 text-primary"></i>Categor&iacute;a
                                    </label>
                                    <select id="categoria_edicion" name="categoria_producto_id"
                                        class="form-select form-select-lg border-primary">
                                        <option value="">Sin categor&iacute;a</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
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
                                    <label for="descripcion_edicion" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripci&oacute;n
                                    </label>
                                    <textarea id="descripcion_edicion" name="descripcion" class="form-control form-control-lg border-primary"
                                        rows="3"></textarea>
                                    <small class="form-text text-muted">M&aacute;ximo 500 caracteres</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_compra_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Compra
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_compra_edicion" name="precio_compra"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="precio_venta_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Precio Venta
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="precio_venta_edicion" name="precio_venta"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el precio de venta (m&iacute;nimo 0)</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="stock_minimo_edicion" class="form-label fw-medium">
                                        <i class="ri-alarm-warning-line me-1 text-primary"></i>Stock M&iacute;nimo
                                    </label>
                                    <input type="number" id="stock_minimo_edicion" name="stock_minimo"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="0" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Producto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    <!-- Modal Eliminar -->
    @can('productos.eliminar')
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
                        <h4 class="mb-3 fw-bold text-danger">&iquest;Est&aacute;s seguro de eliminar este producto?</h4>
                        <p class="text-muted mb-0">Esta acci&oacute;n eliminar&aacute; permanentemente el producto del inventario.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">No se podr&aacute; eliminar si el producto tiene compras o ventas relacionadas.</small>
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
        /* Estilos espec&iacute;ficos para Productos */
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
                    $('#addForm .form-control, #addForm .form-select').removeClass('is-valid is-invalid');
                    $('#estado_registro').val('activo');
                    $('#stock_minimo_registro').val('0');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#feedback_edicion').removeClass('text-success text-danger').text('');
                    $('.updateBtn').prop('disabled', true);
                    $('#updateForm').removeClass('was-validated');
                    $('#updateForm .form-control, #updateForm .form-select').removeClass('is-valid is-invalid');
                }
            });

            // Verificar nombre + marca al registrar
            function verificarNombreRegistro() {
                const nombre = $('#nombre_registro').val().trim();
                const marca = $('#marca_registro').val().trim();
                const feedback = $('#feedback_registro');
                const submitBtn = $('.addBtn');
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

                if (nombre.length > 255) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 255 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.productos.verificar') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            marca: marca
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
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
                        error: function() {
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

            // Verificar nombre + marca al editar
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

                if (nombre.length > 255) {
                    feedback.addClass('text-danger').text('El nombre no puede exceder los 255 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.productos.verificaredicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nombre: nombre,
                            marca: marca,
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
                        error: function() {
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

            // Validar descripcion en tiempo real
            $('#descripcion_registro, #descripcion_edicion').on('input', function() {
                const descripcion = $(this).val().trim();
                if (descripcion.length > 500) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.form-text').addClass('text-danger').text('Maximo 500 caracteres');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.form-text').removeClass('text-danger').text('Maximo 500 caracteres');
                }
            });

            // Editar producto - cargar datos en modal
            $(document).on('click', '.editBtn', function() {
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

            // Eliminar producto - cargar datos en modal
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminar .modal-body h4').text(
                    `\u00bfEst\u00e1s seguro de eliminar el producto "${data.nombre}"?`);
            });

            // REGISTRAR PRODUCTO
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
                    url: "{{ route('admin.productos.registrar') }}",
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

            // ACTUALIZAR PRODUCTO
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
                    url: "{{ route('admin.productos.modificar') }}",
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

            // ELIMINAR PRODUCTO
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.productos.eliminar') }}",
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

            // FUNCION PARA CARGAR RESULTADOS
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.productos.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#productosTableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando productos...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#productosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalProductosCounter').text(response.total);
                                $('#activosCounter').text(response.activos);
                                $('#stockBajoCounter').text(response.stockBajo);
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
                        $('#productosTableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center py-5 text-danger">
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
                        $('#productosTableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center py-5">
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
                            $('#productosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalProductosCounter').text(response.total);
                                $('#activosCounter').text(response.activos);
                                $('#stockBajoCounter').text(response.stockBajo);
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
