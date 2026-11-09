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
                            <i class="ri-shopping-cart-2-line me-2"></i>Registrar Nueva Venta
                        </h4>
                        <p class="text-white-50 mb-0">Completa los datos para registrar una nueva venta de productos</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.ventas.listar') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form id="formVenta">
            @csrf

            <!-- ========== CARD 1: CLIENTE ========== -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                            <span class="avatar-title text-primary"><i class="ri-user-star-line fs-5"></i></span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Cliente</h5>
                            <small class="text-muted">Selecciona o registra un cliente para la venta</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Radio buttons -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex gap-4 flex-wrap">
                                <div class="form-check form-check-lg">
                                    <input class="form-check-input" type="radio" name="cliente_tipo" id="clienteExistente"
                                        value="existente" checked>
                                    <label class="form-check-label fw-semibold" for="clienteExistente">
                                        <i class="ri-user-search-line me-1 text-primary"></i>Cliente existente
                                    </label>
                                </div>
                                <div class="form-check form-check-lg">
                                    <input class="form-check-input" type="radio" name="cliente_tipo" id="clienteNuevo"
                                        value="nuevo">
                                    <label class="form-check-label fw-semibold" for="clienteNuevo">
                                        <i class="ri-user-add-line me-1 text-success"></i>Cliente nuevo
                                    </label>
                                </div>
                                <div class="form-check form-check-lg">
                                    <input class="form-check-input" type="radio" name="cliente_tipo" id="sinCliente"
                                        value="sin_cliente">
                                    <label class="form-check-label fw-semibold" for="sinCliente">
                                        <i class="ri-store-2-line me-1 text-secondary"></i>Venta directa (sin cliente)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing client search -->
                    <div id="seccionClienteExistente">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="ri-search-line me-1 text-primary"></i>Buscar Cliente
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control form-control-lg" id="clienteSearchInput"
                                        placeholder="Buscar por CI, nombre..." autocomplete="off">
                                    <input type="hidden" name="cliente_id" id="clienteIdInput">
                                    <div id="clienteSearchResults"
                                        class="position-absolute w-100 bg-white border rounded-3 shadow-lg mt-1 d-none"
                                        style="z-index: 1050; max-height: 300px; overflow-y: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Selected client info -->
                        <div id="clienteSeleccionadoInfo" class="mt-3 d-none">
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success bg-opacity-25 text-success rounded-circle">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold" id="clienteNombreDisplay"></h6>
                                    <small class="text-muted">
                                        CI: <span id="clienteCarnetDisplay"></span> |
                                        Cel: <span id="clienteCelularDisplay"></span>
                                    </small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="btnCambiarCliente">
                                    <i class="ri-close-line"></i> Cambiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New client form -->
                    <div id="seccionClienteNuevo" class="d-none">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombres" id="nuevoNombres"
                                    placeholder="Nombres del cliente">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="apellido_paterno" id="nuevoApPaterno"
                                    placeholder="Apellido paterno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Apellido Materno</label>
                                <input type="text" class="form-control" name="apellido_materno" id="nuevoApMaterno"
                                    placeholder="Apellido materno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Carnet (CI) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="carnet" id="nuevoCarnet"
                                    placeholder="N&uacute;mero de carnet">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Celular</label>
                                <input type="text" class="form-control" name="celular" id="nuevoCelular"
                                    placeholder="N&uacute;mero de celular">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Correo</label>
                                <input type="email" class="form-control" name="correo" id="nuevoCorreo"
                                    placeholder="Correo electr&oacute;nico">
                            </div>
                        </div>
                    </div>

                    <!-- Sin cliente message -->
                    <div id="seccionSinCliente" class="d-none">
                        <div class="alert alert-secondary border-0 rounded-3">
                            <i class="ri-information-line me-2"></i>
                            Se registrar&aacute; como venta directa sin asociar a un cliente.
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== CARD 2: PRODUCTOS ========== -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                            <span class="avatar-title text-success"><i class="ri-box-3-line fs-5"></i></span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Productos</h5>
                            <small class="text-muted">Agrega los productos a vender</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Product search -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">
                                <i class="ri-search-line me-1 text-success"></i>Buscar Producto
                            </label>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg" id="productoSearchInput"
                                    placeholder="Buscar producto por nombre o marca..." autocomplete="off">
                                <div id="productoSearchResults"
                                    class="position-absolute w-100 bg-white border rounded-3 shadow-lg mt-1 d-none"
                                    style="z-index: 1050; max-height: 300px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle mb-0" id="productosTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width: 120px;">Stock Disp.</th>
                                    <th class="text-center" style="width: 120px;">Cantidad</th>
                                    <th class="text-center" style="width: 150px;">Precio Unit. (Bs)</th>
                                    <th class="text-center" style="width: 140px;">Subtotal (Bs)</th>
                                    <th class="text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="productosTableBody">
                                <tr id="emptyProductsRow">
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-shopping-cart-line fs-2 d-block mb-2"></i>
                                            Busca y agrega productos a la venta
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold fs-5">Subtotal:</td>
                                    <td class="text-center fw-bold fs-5 text-success" id="subtotalGeneral">0.00 Bs</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========== CARD 3: PAGO ========== -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                            <span class="avatar-title text-warning"><i class="ri-bank-card-line fs-5"></i></span>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Pago</h5>
                            <small class="text-muted">Configura el descuento y forma de pago</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Descuento (Bs)</label>
                            <input type="number" class="form-control form-control-lg" name="descuento_bs"
                                id="descuentoInput" value="0" min="0" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total a Pagar (Bs)</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-success bg-light"
                                id="totalPagarDisplay" value="0.00" readonly>
                            <input type="hidden" name="total_bs" id="totalBsInput" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo de Pago <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" name="tipo_pago" id="tipoPagoSelect">
                                <option value="">-- Seleccionar --</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="qr">QR</option>
                                <option value="parcial">Parcial</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Observaci&oacute;n (opcional)</label>
                            <textarea class="form-control" name="observacion" id="observacionInput" rows="2"
                                placeholder="Notas adicionales sobre la venta..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="row mb-4">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" id="btnRegistrarVenta">
                        <i class="ri-check-line me-2"></i>Registrar Venta
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let debounceTimer;
            let productosAgregados = {};

            // ========== CLIENT TYPE TOGGLE ==========
            const radios = document.querySelectorAll('input[name="cliente_tipo"]');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('seccionClienteExistente').classList.toggle('d-none', this.value !== 'existente');
                    document.getElementById('seccionClienteNuevo').classList.toggle('d-none', this.value !== 'nuevo');
                    document.getElementById('seccionSinCliente').classList.toggle('d-none', this.value !== 'sin_cliente');
                });
            });

            // ========== CLIENT SEARCH (AJAX) ==========
            const clienteSearchInput = document.getElementById('clienteSearchInput');
            const clienteSearchResults = document.getElementById('clienteSearchResults');
            const clienteIdInput = document.getElementById('clienteIdInput');

            clienteSearchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const term = this.value.trim();
                if (term.length < 2) {
                    clienteSearchResults.classList.add('d-none');
                    return;
                }
                debounceTimer = setTimeout(() => {
                    fetch('{{ route("admin.ventas.buscarCliente") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ q: term })
                    })
                    .then(res => res.json())
                    .then(data => {
                        clienteSearchResults.innerHTML = '';
                        if (data.length === 0) {
                            clienteSearchResults.innerHTML = '<div class="p-3 text-muted text-center">No se encontraron clientes</div>';
                        } else {
                            data.forEach(c => {
                                const item = document.createElement('div');
                                item.className = 'p-3 border-bottom cursor-pointer hover-bg-light';
                                item.style.cursor = 'pointer';
                                item.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                <i class="ri-user-line"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-medium">${c.nombre_completo}</h6>
                                            <small class="text-muted">CI: ${c.carnet || 'N/A'} | Cel: ${c.celular || 'N/A'}</small>
                                        </div>
                                    </div>`;
                                item.addEventListener('click', () => selectCliente(c));
                                clienteSearchResults.appendChild(item);
                            });
                        }
                        clienteSearchResults.classList.remove('d-none');
                    });
                }, 400);
            });

            function selectCliente(cliente) {
                clienteIdInput.value = cliente.id;
                clienteSearchInput.value = '';
                clienteSearchResults.classList.add('d-none');
                document.getElementById('clienteNombreDisplay').textContent = cliente.nombre_completo;
                document.getElementById('clienteCarnetDisplay').textContent = cliente.carnet || 'N/A';
                document.getElementById('clienteCelularDisplay').textContent = cliente.celular || 'N/A';
                document.getElementById('clienteSeleccionadoInfo').classList.remove('d-none');
                clienteSearchInput.closest('.row').classList.add('d-none');
            }

            document.getElementById('btnCambiarCliente').addEventListener('click', function() {
                clienteIdInput.value = '';
                document.getElementById('clienteSeleccionadoInfo').classList.add('d-none');
                clienteSearchInput.closest('.row').classList.remove('d-none');
                clienteSearchInput.value = '';
                clienteSearchInput.focus();
            });

            // Close dropdown on outside click
            document.addEventListener('click', function(e) {
                if (!clienteSearchInput.contains(e.target) && !clienteSearchResults.contains(e.target)) {
                    clienteSearchResults.classList.add('d-none');
                }
                if (!document.getElementById('productoSearchInput').contains(e.target) &&
                    !document.getElementById('productoSearchResults').contains(e.target)) {
                    document.getElementById('productoSearchResults').classList.add('d-none');
                }
            });

            // ========== PRODUCT SEARCH (AJAX) ==========
            const productoSearchInput = document.getElementById('productoSearchInput');
            const productoSearchResults = document.getElementById('productoSearchResults');

            productoSearchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const term = this.value.trim();
                if (term.length < 2) {
                    productoSearchResults.classList.add('d-none');
                    return;
                }
                debounceTimer = setTimeout(() => {
                    fetch('{{ route("admin.ventas.buscarProducto") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ q: term })
                    })
                    .then(res => res.json())
                    .then(data => {
                        productoSearchResults.innerHTML = '';
                        if (data.length === 0) {
                            productoSearchResults.innerHTML = '<div class="p-3 text-muted text-center">No se encontraron productos</div>';
                        } else {
                            data.forEach(p => {
                                const yaAgregado = productosAgregados[p.id] !== undefined;
                                const item = document.createElement('div');
                                item.className = 'p-3 border-bottom ' + (yaAgregado ? 'bg-light' : '');
                                item.style.cursor = yaAgregado ? 'not-allowed' : 'pointer';
                                item.innerHTML = `
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                                    <i class="ri-box-3-line"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium">${p.nombre} ${p.marca ? '<small class="text-muted">(' + p.marca + ')</small>' : ''}</h6>
                                                <small class="text-muted">Precio: ${parseFloat(p.precio_venta).toFixed(2)} Bs | Stock: ${p.stock}</small>
                                            </div>
                                        </div>
                                        ${yaAgregado ? '<span class="badge bg-secondary-subtle text-secondary">Ya agregado</span>' : '<span class="badge bg-success-subtle text-success">Agregar</span>'}
                                    </div>`;
                                if (!yaAgregado) {
                                    item.addEventListener('click', () => agregarProducto(p));
                                }
                                productoSearchResults.appendChild(item);
                            });
                        }
                        productoSearchResults.classList.remove('d-none');
                    });
                }, 400);
            });

            function agregarProducto(producto) {
                if (productosAgregados[producto.id] !== undefined) return;

                const emptyRow = document.getElementById('emptyProductsRow');
                if (emptyRow) emptyRow.remove();

                productosAgregados[producto.id] = {
                    ...producto,
                    cantidad: 1,
                    precio_unitario: parseFloat(producto.precio_venta)
                };

                const tbody = document.getElementById('productosTableBody');
                const tr = document.createElement('tr');
                tr.id = `productoRow_${producto.id}`;
                tr.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                        <i class="ri-box-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-medium">${producto.nombre}</h6>
                                ${producto.marca ? '<small class="text-muted">' + producto.marca + '</small>' : ''}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2" id="stockDisp_${producto.id}">${producto.stock}</span>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control text-center cantidadInput" data-id="${producto.id}"
                            value="1" min="1" max="${producto.stock}" style="width: 90px; margin: 0 auto;">
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control text-center precioInput" data-id="${producto.id}"
                            value="${parseFloat(producto.precio_venta).toFixed(2)}" min="0" step="0.01" style="width: 120px; margin: 0 auto;">
                    </td>
                    <td class="text-center">
                        <span class="fw-bold text-success subtotalItem" id="subtotal_${producto.id}">${parseFloat(producto.precio_venta).toFixed(2)} Bs</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btnRemoveProduct" data-id="${producto.id}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>`;
                tbody.appendChild(tr);

                productoSearchInput.value = '';
                productoSearchResults.classList.add('d-none');
                recalcularTotales();
                bindProductEvents();
            }

            function bindProductEvents() {
                // Cantidad change
                document.querySelectorAll('.cantidadInput').forEach(input => {
                    input.onchange = function() {
                        const id = this.dataset.id;
                        let val = parseInt(this.value) || 1;
                        const maxStock = parseInt(this.max);

                        if (val < 1) val = 1;
                        if (val > maxStock) {
                            val = maxStock;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Stock insuficiente',
                                text: `Solo hay ${maxStock} unidades disponibles.`,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                        this.value = val;
                        productosAgregados[id].cantidad = val;
                        updateSubtotal(id);
                    };
                });

                // Precio change
                document.querySelectorAll('.precioInput').forEach(input => {
                    input.onchange = function() {
                        const id = this.dataset.id;
                        let val = parseFloat(this.value) || 0;
                        if (val < 0) val = 0;
                        this.value = val.toFixed(2);
                        productosAgregados[id].precio_unitario = val;
                        updateSubtotal(id);
                    };
                });

                // Remove product
                document.querySelectorAll('.btnRemoveProduct').forEach(btn => {
                    btn.onclick = function() {
                        const id = this.dataset.id;
                        const row = document.getElementById(`productoRow_${id}`);
                        if (row) row.remove();
                        delete productosAgregados[id];

                        if (Object.keys(productosAgregados).length === 0) {
                            const tbody = document.getElementById('productosTableBody');
                            tbody.innerHTML = `
                                <tr id="emptyProductsRow">
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-shopping-cart-line fs-2 d-block mb-2"></i>
                                            Busca y agrega productos a la venta
                                        </div>
                                    </td>
                                </tr>`;
                        }
                        recalcularTotales();
                    };
                });
            }

            function updateSubtotal(id) {
                const p = productosAgregados[id];
                const subtotal = p.cantidad * p.precio_unitario;
                document.getElementById(`subtotal_${id}`).textContent = subtotal.toFixed(2) + ' Bs';
                recalcularTotales();
            }

            function recalcularTotales() {
                let subtotalGeneral = 0;
                Object.values(productosAgregados).forEach(p => {
                    subtotalGeneral += p.cantidad * p.precio_unitario;
                });

                document.getElementById('subtotalGeneral').textContent = subtotalGeneral.toFixed(2) + ' Bs';

                const descuento = parseFloat(document.getElementById('descuentoInput').value) || 0;
                const total = Math.max(subtotalGeneral - descuento, 0);
                document.getElementById('totalPagarDisplay').value = total.toFixed(2);
                document.getElementById('totalBsInput').value = total.toFixed(2);
            }

            document.getElementById('descuentoInput').addEventListener('input', recalcularTotales);

            // ========== FORM SUBMIT ==========
            document.getElementById('formVenta').addEventListener('submit', function(e) {
                e.preventDefault();

                const clienteTipo = document.querySelector('input[name="cliente_tipo"]:checked').value;

                // Validations
                if (clienteTipo === 'existente' && !clienteIdInput.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar un cliente existente.',
                    });
                    return;
                }

                if (clienteTipo === 'nuevo') {
                    const nombres = document.getElementById('nuevoNombres').value.trim();
                    const apPat = document.getElementById('nuevoApPaterno').value.trim();
                    const carnet = document.getElementById('nuevoCarnet').value.trim();
                    if (!nombres || !apPat || !carnet) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Complete los campos obligatorios del nuevo cliente (Nombres, Apellido Paterno, Carnet).',
                        });
                        return;
                    }
                }

                if (Object.keys(productosAgregados).length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe agregar al menos un producto a la venta.',
                    });
                    return;
                }

                const tipoPago = document.getElementById('tipoPagoSelect').value;
                if (!tipoPago) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Seleccione un tipo de pago.',
                    });
                    return;
                }

                // Build items array
                const items = Object.values(productosAgregados).map(p => ({
                    producto_id: p.id,
                    cantidad: p.cantidad,
                    precio_unitario: p.precio_unitario,
                }));

                const formData = {
                    _token: csrfToken,
                    cliente_tipo: clienteTipo,
                    cliente_id: clienteIdInput.value || null,
                    nombres: document.getElementById('nuevoNombres')?.value || '',
                    apellido_paterno: document.getElementById('nuevoApPaterno')?.value || '',
                    apellido_materno: document.getElementById('nuevoApMaterno')?.value || '',
                    carnet: document.getElementById('nuevoCarnet')?.value || '',
                    celular: document.getElementById('nuevoCelular')?.value || '',
                    correo: document.getElementById('nuevoCorreo')?.value || '',
                    items: items,
                    total_bs: document.getElementById('totalBsInput').value,
                    descuento_bs: document.getElementById('descuentoInput').value || 0,
                    tipo_pago: tipoPago,
                    observacion: document.getElementById('observacionInput').value,
                };

                const btn = document.getElementById('btnRegistrarVenta');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

                fetch('{{ route("admin.ventas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Venta Registrada',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ocurri\u00f3 un error al registrar la venta.',
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-check-line me-2"></i>Registrar Venta';
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurri\u00f3 un error inesperado. Intente nuevamente.',
                    });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-check-line me-2"></i>Registrar Venta';
                });
            });
        });
    </script>
@endsection
