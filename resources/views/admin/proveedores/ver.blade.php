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
                            <i class="ri-truck-line me-2"></i>Detalle del Proveedor
                        </h4>
                        <p class="text-white-50 mb-0">Información y compras realizadas</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.proveedores.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                <span class="avatar-title text-primary"><i class="ri-information-line fs-5"></i></span>
                            </div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Información del Proveedor</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-building-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Nombre</small>
                                        <strong class="fs-5">{{ $proveedor->nombre }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-file-list-line fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">NIT</small>
                                        <strong>{{ $proveedor->nit ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-toggle-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Estado</small>
                                        <span
                                            class="badge bg-{{ $proveedor->estado === 'activo' ? 'success' : 'danger' }}-subtle text-{{ $proveedor->estado === 'activo' ? 'success' : 'danger' }}">
                                            {{ ucfirst($proveedor->estado) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-user-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Contacto</small>
                                        <strong>{{ $proveedor->contacto_nombre ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-phone-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Celular</small>
                                        <strong>{{ $proveedor->celular ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-mail-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Correo</small>
                                        <strong>{{ $proveedor->correo ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-primary me-2"><i class="ri-map-pin-line fs-5"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Dirección</small>
                                        <strong>{{ $proveedor->direccion ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Registration Form -->
        @can('compras.registrar')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                <span class="avatar-title text-success"><i class="ri-shopping-cart-line fs-5"></i></span>
                            </div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Registrar Nueva Compra</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form id="formCompra">
                            @csrf
                            <input type="hidden" name="proveedore_id" value="{{ $proveedor->id }}">

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-calendar-line me-1 text-primary"></i>Fecha de Compra
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="fecha_compra" name="fecha_compra"
                                        class="form-control form-control-lg border-primary" value="{{ date('Y-m-d') }}"
                                        required>
                                    <div class="invalid-feedback">La fecha es obligatoria</div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-chat-3-line me-1 text-primary"></i>Observación
                                    </label>
                                    <textarea id="observacion" name="observacion" class="form-control form-control-lg border-primary" rows="1"
                                        placeholder="Observación opcional..."></textarea>
                                </div>
                            </div>

                            <!-- Product search -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-search-line me-1 text-primary"></i>Buscar y Agregar Producto
                                    </label>
                                    <select id="productoSelect" class="form-select form-select-lg border-primary">
                                        <option value="">-- Buscar producto por nombre o marca --</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}"
                                                data-marca="{{ $producto->marca }}"
                                                data-precio="{{ $producto->precio_compra }}"
                                                data-stock="{{ $producto->stock }}">
                                                {{ $producto->nombre }}
                                                @if ($producto->marca)
                                                    ({{ $producto->marca }})
                                                @endif
                                                - Stock: {{ $producto->stock }}
                                                - Precio: {{ number_format($producto->precio_compra, 2) }} Bs
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" id="btnAgregarProducto" class="btn btn-success btn-lg w-100">
                                        <i class="ri-add-line me-1"></i> Agregar Producto
                                    </button>
                                </div>
                            </div>

                            <!-- Products table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-centered align-middle table-nowrap mb-0"
                                    id="tablaProductos">
                                    <thead class="table-primary text-muted fw-semibold">
                                        <tr>
                                            <th class="px-3 py-3 text-center" width="5%">#</th>
                                            <th class="px-3 py-3" width="35%">Producto</th>
                                            <th class="px-3 py-3 text-center" width="15%">Cantidad</th>
                                            <th class="px-3 py-3 text-center" width="15%">Precio Unit. (Bs)</th>
                                            <th class="px-3 py-3 text-center" width="15%">Subtotal (Bs)</th>
                                            <th class="px-3 py-3 text-center" width="10%">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productosBody">
                                        <tr id="emptyRow">
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ri-box-3-line fs-2 d-block mb-2"></i>
                                                    Agrega productos a la compra usando el buscador de arriba
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold fs-5 pe-3">TOTAL:</td>
                                            <td class="text-center fw-bold fs-5 text-success" id="totalCompra">0.00 Bs
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" id="btnRegistrar" class="btn btn-primary btn-lg px-5">
                                        <i class="ri-save-3-line me-1"></i> Registrar Compra
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endcan

        <!-- Purchases List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-warning"><i class="ri-history-line fs-5"></i></span>
                                </div>
                                <h5 class="card-title mb-0 fw-bold text-dark">Compras Realizadas</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="10%"># Compra</th>
                                        <th class="px-3 py-3" width="20%">Fecha</th>
                                        <th class="px-3 py-3 text-center" width="20%">Total (Bs)</th>
                                        <th class="px-3 py-3" width="40%">Productos</th>
                                        <th class="px-3 py-3 text-center" width="10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="comprasTableBody">
                                    @include('admin.proveedores.partials.compras-table', [
                                        'compras' => $compras,
                                    ])
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                            <div class="results-count text-muted" id="comprasResultsCount">
                                Mostrando <span class="fw-medium text-primary">{{ $compras->firstItem() }}</span> a
                                <span class="fw-medium text-primary">{{ $compras->lastItem() }}</span> de
                                <span class="fw-medium text-primary">{{ $compras->total() }}</span> resultados
                            </div>
                            <div class="pagination-container" id="comprasPagination">
                                {{ $compras->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .card:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
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
            let itemCounter = 0;
            let items = {};
            let isProcessing = false;

            // Add product
            $('#btnAgregarProducto').on('click', function() {
                const select = $('#productoSelect');
                const selectedOption = select.find(':selected');
                const productoId = select.val();

                if (!productoId) {
                    showToast('warning', 'Seleccione un producto primero');
                    return;
                }

                if (items[productoId]) {
                    showToast('warning',
                        'Este producto ya fue agregado. Modifica la cantidad en la fila existente.');
                    select.val('');
                    return;
                }

                const nombre = selectedOption.data('nombre');
                const marca = selectedOption.data('marca') || '';
                const precio = parseFloat(selectedOption.data('precio')) || 0;
                const stock = selectedOption.data('stock');

                itemCounter++;
                items[productoId] = itemCounter;

                $('#emptyRow').hide();

                const row = `
        <tr id="row-${itemCounter}" data-producto-id="${productoId}">
            <td class="text-center"><span class="badge bg-primary-subtle text-primary rounded-pill p-2">${itemCounter}</span></td>
            <td>
                <div class="fw-semibold">${nombre}</div>
                ${marca ? '<small class="text-muted">' + marca + '</small>' : ''}
                <br><small class="text-info">Stock actual: ${stock}</small>
            </td>
            <td class="text-center">
                <input type="number" class="form-control form-control-sm text-center item-cantidad"
                    data-row="${itemCounter}" value="1" min="1" step="1" style="width:80px;margin:0 auto;">
            </td>
            <td class="text-center">
                <input type="number" class="form-control form-control-sm text-center item-precio"
                    data-row="${itemCounter}" value="${precio.toFixed(2)}" min="0.01" step="0.01" style="width:100px;margin:0 auto;">
            </td>
            <td class="text-center fw-bold text-success item-subtotal" id="subtotal-${itemCounter}">
                ${precio.toFixed(2)} Bs
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove" data-row="${itemCounter}" data-producto-id="${productoId}">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>`;

                $('#productosBody').append(row);
                select.val('');
                recalculateTotal();
            });

            // Remove row
            $(document).on('click', '.btn-remove', function() {
                const row = $(this).data('row');
                const productoId = $(this).data('producto-id');
                $(`#row-${row}`).remove();
                delete items[productoId];

                if (Object.keys(items).length === 0) {
                    $('#emptyRow').show();
                }
                recalculateTotal();
            });

            // Recalculate row subtotal
            $(document).on('input', '.item-cantidad, .item-precio', function() {
                const row = $(this).data('row');
                recalculateRow(row);
                recalculateTotal();
            });

            function recalculateRow(row) {
                const cantidad = parseFloat($(`#row-${row} .item-cantidad`).val()) || 0;
                const precio = parseFloat($(`#row-${row} .item-precio`).val()) || 0;
                const subtotal = cantidad * precio;
                $(`#subtotal-${row}`).text(subtotal.toFixed(2) + ' Bs');
            }

            function recalculateTotal() {
                let total = 0;
                $('#productosBody tr[id^="row-"]').each(function() {
                    const rowId = $(this).attr('id').replace('row-', '');
                    const cantidad = parseFloat($(this).find('.item-cantidad').val()) || 0;
                    const precio = parseFloat($(this).find('.item-precio').val()) || 0;
                    total += cantidad * precio;
                });
                $('#totalCompra').text(total.toFixed(2) + ' Bs');
            }

            // Submit form
            $('#formCompra').on('submit', function(e) {
                e.preventDefault();

                const fechaCompra = $('#fecha_compra').val();
                const observacion = $('#observacion').val();

                if (!fechaCompra) {
                    showToast('error', 'La fecha de compra es obligatoria');
                    return;
                }

                const itemsArray = [];
                $('#productosBody tr[id^="row-"]').each(function() {
                    const productoId = $(this).data('producto-id');
                    const cantidad = parseInt($(this).find('.item-cantidad').val()) || 0;
                    const precioUnitario = parseFloat($(this).find('.item-precio').val()) || 0;

                    if (productoId && cantidad > 0 && precioUnitario > 0) {
                        itemsArray.push({
                            producto_id: productoId,
                            cantidad: cantidad,
                            precio_unitario: precioUnitario
                        });
                    }
                });

                if (itemsArray.length === 0) {
                    showToast('error', 'Debe agregar al menos un producto a la compra');
                    return;
                }

                if (isProcessing) return;
                isProcessing = true;

                const submitBtn = $('#btnRegistrar');
                const originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Registrando...'
                );

                $.ajax({
                    url: '{{ route('admin.compras.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        proveedore_id: {{ $proveedor->id }},
                        fecha_compra: fechaCompra,
                        observacion: observacion,
                        items: itemsArray
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.msg);
                            // Reset form
                            $('#formCompra')[0].reset();
                            $('#fecha_compra').val('{{ date('Y-m-d') }}');
                            $('#observacion').val('');
                            $('#productosBody tr[id^="row-"]').remove();
                            items = {};
                            itemCounter = 0;
                            $('#emptyRow').show();
                            recalculateTotal();
                            // Reload purchases list
                            loadCompras();
                        } else {
                            showToast('error', response.msg || 'Error al registrar la compra');
                        }
                        submitBtn.prop('disabled', false).html(originalHtml);
                        isProcessing = false;
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar la compra';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.msg) {
                                errorMsg = xhr.responseJSON.msg;
                            } else if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors);
                                errorMsg = errors.flat().join('<br>');
                            }
                        }
                        showToast('error', errorMsg);
                        submitBtn.prop('disabled', false).html(originalHtml);
                        isProcessing = false;
                    }
                });
            });

            // Load purchases via AJAX (for pagination and after new purchase)
            function loadCompras(pageUrl = null) {
                const url = pageUrl || '{{ route('admin.proveedores.ver', $proveedor->id) }}?compras_only=1';
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        compras_only: 1
                    },
                    beforeSend: function() {
                        $('#comprasTableBody').html(
                            '<tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando compras...</p></td></tr>'
                            );
                    },
                    success: function(response) {
                        $('#comprasTableBody').html(response.html);
                        $('#comprasResultsCount').html(
                            `Mostrando <span class="fw-medium text-primary">${response.from}</span> a
                     <span class="fw-medium text-primary">${response.to}</span> de
                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                        );
                        $('#comprasPagination').html(response.pagination);
                        // Rebind pagination click
                        $('#comprasPagination a.page-link').off('click').on('click', function(e) {
                            e.preventDefault();
                            loadCompras($(this).attr('href'));
                        });
                    },
                    error: function() {
                        showToast('error', 'Error al cargar las compras');
                    }
                });
            }

            // Handle pagination clicks
            $(document).on('click', '#comprasPagination a.page-link', function(e) {
                e.preventDefault();
                loadCompras($(this).attr('href'));
            });

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
                    },
                    warning: {
                        icon: 'ri-alert-fill',
                        bgClass: 'bg-warning',
                        title: 'Advertencia'
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
                    container.style.zIndex = '9999';
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
