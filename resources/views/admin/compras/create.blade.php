@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-4 p-4 shadow-sm">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-shopping-cart-line me-2"></i>Registrar Nueva Compra
                        </h4>
                        <p class="text-white-50 mb-0">Registra una compra de productos a un proveedor</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.compras.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form id="formCompra">
            @csrf

            <!-- Card 1: Datos de la Compra -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                            <span class="avatar-title text-primary"><i class="ri-file-list-3-line fs-5"></i></span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Datos de la Compra</h5>
                            <small class="text-muted">Informaci&oacute;n general de la compra</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="ri-truck-line me-1 text-primary"></i>Proveedor
                                <span class="text-danger">*</span>
                            </label>
                            <select id="proveedore_id" name="proveedore_id" class="form-select form-select-lg border-primary" required>
                                <option value="">-- Seleccionar Proveedor --</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">
                                        {{ $proveedor->nombre }}
                                        @if($proveedor->nit) (NIT: {{ $proveedor->nit }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Seleccione un proveedor</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="ri-calendar-line me-1 text-primary"></i>Fecha de Compra
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="fecha_compra" name="fecha_compra"
                                class="form-control form-control-lg border-primary"
                                value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">La fecha es obligatoria</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="ri-chat-3-line me-1 text-primary"></i>Observaci&oacute;n
                            </label>
                            <textarea id="observacion" name="observacion" class="form-control form-control-lg border-primary"
                                rows="1" placeholder="Observaci&oacute;n opcional..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Productos -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                <span class="avatar-title text-success"><i class="ri-box-3-line fs-5"></i></span>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold text-dark">Productos</h5>
                                <small class="text-muted">Agrega los productos de esta compra</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Product search -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="ri-search-line me-1 text-primary"></i>Buscar y Agregar Producto
                            </label>
                            <select id="productoSelect" class="form-select form-select-lg border-primary">
                                <option value="">-- Buscar producto por nombre o marca --</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-marca="{{ $producto->marca }}"
                                        data-precio="{{ $producto->precio_compra }}"
                                        data-stock="{{ $producto->stock }}">
                                        {{ $producto->nombre }}
                                        @if($producto->marca) ({{ $producto->marca }}) @endif
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
                        <table class="table table-hover table-centered align-middle table-nowrap mb-0" id="tablaProductos">
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
                                    <td class="text-center fw-bold fs-5 text-success" id="totalCompra">0.00 Bs</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mb-4">
                <div class="col-12 text-end">
                    <a href="{{ route('admin.compras.listar') }}" class="btn btn-soft-secondary btn-lg me-2">
                        <i class="ri-close-line me-1"></i> Cancelar
                    </a>
                    <button type="submit" id="btnRegistrar" class="btn btn-primary btn-lg px-5">
                        <i class="ri-save-3-line me-1"></i> Registrar Compra
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let itemCounter = 0;
            let items = {};

            // Add product
            $('#btnAgregarProducto').on('click', function() {
                const select = $('#productoSelect');
                const selectedOption = select.find(':selected');
                const productoId = select.val();

                if (!productoId) {
                    showToast('warning', 'Seleccione un producto primero');
                    return;
                }

                // Check if product already added
                if (items[productoId]) {
                    showToast('warning', 'Este producto ya fue agregado. Modifica la cantidad en la fila existente.');
                    select.val('');
                    return;
                }

                const nombre = selectedOption.data('nombre');
                const marca = selectedOption.data('marca') || '';
                const precio = parseFloat(selectedOption.data('precio')) || 0;
                const stock = selectedOption.data('stock');

                itemCounter++;
                items[productoId] = itemCounter;

                // Remove empty row
                $('#emptyRow').hide();

                const row = `
                <tr id="row-${itemCounter}" data-producto-id="${productoId}">
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary rounded-pill p-2">${itemCounter}</span>
                    </td>
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

            // Recalculate on quantity/price change
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

                const proveedoreId = $('#proveedore_id').val();
                const fechaCompra = $('#fecha_compra').val();
                const observacion = $('#observacion').val();

                if (!proveedoreId) {
                    showToast('error', 'Seleccione un proveedor');
                    return;
                }
                if (!fechaCompra) {
                    showToast('error', 'La fecha de compra es obligatoria');
                    return;
                }

                // Build items array
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
                        proveedore_id: proveedoreId,
                        fecha_compra: fechaCompra,
                        observacion: observacion,
                        items: itemsArray
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.msg);
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        } else {
                            showToast('error', response.msg || 'Error al registrar la compra');
                            submitBtn.prop('disabled', false).html(originalHtml);
                        }
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
                    }
                });
            });

            // Toast function
            function showToast(type, message) {
                const config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: '\u00c9xito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Informaci\u00f3n' }
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
                const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();

                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        });
    </script>
@endpush
