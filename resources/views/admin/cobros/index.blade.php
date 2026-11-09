@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between rounded-3 p-3"
                    style="background:linear-gradient(135deg,#1a2d4c 0%,#0d9488 100%);">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="ri-hand-coin-line me-2"></i>Registro de Cobros
                        </h4>
                        <p class="mb-0" style="color:rgba(255,255,255,.7);">Busca clientes o hijos para consultar suscripciones y registrar pagos</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="row justify-content-center mb-3">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-transparent ps-3">
                                <i class="ri-search-line fs-5" style="color:#0d9488;"></i>
                            </span>
                            <input type="text" id="busquedaInput"
                                class="form-control border-0 shadow-none fs-6"
                                placeholder="Busca por nombre o carnet del cliente o del hijo/a..."
                                autocomplete="off">
                            <button class="btn border-0 pe-3" id="btnLimpiar" style="display:none;color:#6c757d;">
                                <i class="ri-close-circle-line fs-5"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1 px-2">
                            <small class="text-muted"><i class="ri-information-line me-1"></i>Mínimo 2 caracteres</small>
                            <span id="busquedaStatus" class="small ms-auto fw-medium" style="color:#0d9488;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Results --}}
        <div id="resultados"></div>

        {{-- Empty state --}}
        <div id="estadoVacio" class="text-center py-5">
            <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                style="width:80px;height:80px;background:rgba(13,148,136,.08);">
                <i class="ri-hand-coin-line" style="font-size:2.5rem;color:#0d9488;"></i>
            </div>
            <h5 class="text-muted fw-semibold mb-1">Busca un cliente o hijo/a</h5>
            <p class="text-muted small mb-0">Los resultados mostrarán suscripciones, sesiones y saldo pendiente</p>
        </div>

        {{-- No results --}}
        <div id="sinResultados" class="text-center py-5" style="display:none;">
            <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                style="width:80px;height:80px;background:rgba(220,53,69,.06);">
                <i class="ri-file-search-line" style="font-size:2.5rem;color:#dc3545;"></i>
            </div>
            <h5 class="text-muted fw-semibold mb-1">No se encontraron resultados</h5>
            <p class="text-muted small mb-0">Intenta con otro nombre o número de carnet</p>
        </div>

        {{-- Modal detalle de venta --}}
        <div class="modal fade" id="modalDetalleVentaCobro" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,#b8860b,#d4a017);">
                        <h5 class="modal-title text-white fw-bold mb-0">
                            <i class="ri-shopping-cart-line me-2"></i>Detalle de Venta
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4" id="modalDetalleVentaCobroBody">
                        {{-- populated by JS --}}
                    </div>
                    <div class="modal-footer border-0 px-4 pb-3 pt-0">
                        <a href="#" id="modalDetalleVentaPdfBtn" target="_blank"
                            class="btn btn-danger rounded-pill px-4">
                            <i class="ri-file-pdf-line me-1"></i>Descargar Recibo PDF
                        </a>
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script')
<style>
    .cobro-card {
        transition: box-shadow .2s ease;
    }
    .cobro-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,.08) !important;
    }
    .sus-block {
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color .2s ease;
    }
    .sus-block:hover {
        border-color: rgba(13,148,136,.25);
    }
    .stat-chip {
        border-radius: 10px;
        padding: 8px 6px;
        text-align: center;
        transition: transform .15s ease;
    }
    .stat-chip:hover {
        transform: translateY(-2px);
    }
    .pago-row {
        border-radius: 10px;
        padding: 8px 12px;
        transition: background .15s ease;
    }
    .pago-row:hover {
        background: rgba(13,148,136,.06) !important;
    }
    .venta-row {
        border-radius: 10px;
        padding: 8px 12px;
        transition: background .15s ease;
    }
    .venta-row:hover {
        background: rgba(184,134,11,.06) !important;
    }
    .btn-action-cobro {
        font-size: .78rem;
        font-weight: 600;
        border-radius: 50rem;
        padding: 5px 14px;
        transition: all .2s ease;
    }
    .btn-action-cobro:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,.12);
    }
    .pago-scroll {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(13,148,136,.3) transparent;
    }
    .pago-scroll::-webkit-scrollbar { width: 4px; }
    .pago-scroll::-webkit-scrollbar-thumb { background: rgba(13,148,136,.3); border-radius: 4px; }
    .progress-cobro {
        height: 5px;
        border-radius: 50rem;
        background: rgba(0,0,0,.04);
    }
    .progress-cobro .bar {
        height: 100%;
        border-radius: 50rem;
        transition: width .4s ease;
    }
</style>
<script>
(function () {
    const input     = document.getElementById('busquedaInput');
    const btnLimpia = document.getElementById('btnLimpiar');
    const status    = document.getElementById('busquedaStatus');
    const container = document.getElementById('resultados');
    const vacio     = document.getElementById('estadoVacio');
    const noRes     = document.getElementById('sinResultados');
    let debounce    = null;

    input.addEventListener('input', function () {
        const q = this.value.trim();
        btnLimpia.style.display = q.length ? '' : 'none';
        clearTimeout(debounce);

        if (q.length < 2) {
            status.textContent = '';
            container.innerHTML = '';
            vacio.style.display = '';
            noRes.style.display = 'none';
            return;
        }

        status.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Buscando...';
        debounce = setTimeout(() => buscar(q), 350);
    });

    btnLimpia.addEventListener('click', function () {
        input.value = '';
        btnLimpia.style.display = 'none';
        status.textContent = '';
        container.innerHTML = '';
        vacio.style.display = '';
        noRes.style.display = 'none';
        input.focus();
    });

    async function buscar(q) {
        try {
            const url = `{{ route('admin.cobros.index') }}?q=${encodeURIComponent(q)}`;
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            vacio.style.display = 'none';

            if (!data.length) {
                container.innerHTML = '';
                noRes.style.display = '';
                status.textContent = 'Sin resultados';
                return;
            }

            noRes.style.display = 'none';
            status.innerHTML = `<i class="ri-user-line me-1"></i>${data.length} cliente${data.length > 1 ? 's' : ''}`;
            container.innerHTML = data.map(c => renderCliente(c)).join('');
        } catch (e) {
            status.innerHTML = '<span class="text-danger"><i class="ri-error-warning-line me-1"></i>Error al buscar</span>';
        }
    }

    /* ==================== RENDER CLIENTE ==================== */
    function renderCliente(c) {
        const letras = (c.nombre || '?').split(' ').slice(0,2).map(w => w[0] ?? '').join('').toUpperCase();
        return `
        <div class="card cobro-card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
            <div class="px-4 py-3 d-flex align-items-center gap-3 flex-wrap"
                style="background:linear-gradient(135deg,${c.color} 0%,rgba(13,148,136,.85) 100%);">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                    style="width:44px;height:44px;background:rgba(255,255,255,.18);font-size:.95rem;letter-spacing:1px;">
                    ${letras}
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold text-white">${c.nombre}</h6>
                    <div class="d-flex flex-wrap gap-3 mt-1">
                        ${c.carnet  ? `<span class="small" style="color:rgba(255,255,255,.65);"><i class="ri-id-card-line me-1"></i>${c.carnet}</span>`  : ''}
                        ${c.celular ? `<span class="small" style="color:rgba(255,255,255,.65);"><i class="ri-phone-line me-1"></i>${c.celular}</span>` : ''}
                    </div>
                </div>
                <a href="${c.seguimiento_url}" class="btn btn-sm rounded-pill px-3 flex-shrink-0"
                    style="background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.25);font-size:.78rem;">
                    <i class="ri-eye-line me-1"></i>Ver perfil
                </a>
            </div>
            <div class="card-body px-4 py-3">
                <div class="d-flex flex-column gap-3">
                    ${c.suscripciones.map(s => renderSuscripcion(s)).join('')}
                </div>
            </div>
        </div>`;
    }

    /* ==================== RENDER SUSCRIPCION ==================== */
    function renderSuscripcion(s) {
        const ses  = s.sesiones;
        const pago = s.pagos;
        const ventas = s.ventas || [];
        const pctReal = ses.totalPlan > 0 ? Math.round((ses.realizadas / ses.totalPlan) * 100) : 0;
        const pctPago = pago.precio_plan > 0 ? Math.round((pago.total_pagado / pago.precio_plan) * 100) : 0;

        const estadoMap = {
            activa:     { color: '#198754', bg: 'rgba(25,135,84,.1)',  icon: 'ri-checkbox-circle-line' },
            completada: { color: '#0d6efd', bg: 'rgba(13,110,253,.1)', icon: 'ri-check-double-line' },
            cancelada:  { color: '#dc3545', bg: 'rgba(220,53,69,.1)',  icon: 'ri-close-circle-line' },
        };
        const est = estadoMap[s.estado] || { color:'#6c757d', bg:'rgba(108,117,125,.1)', icon:'ri-question-line' };

        const serviciosBadges = s.servicios.length
            ? s.servicios.map(sv => `<span class="badge rounded-pill" style="background:rgba(13,148,136,.1);color:#0d9488;font-size:.68rem;font-weight:500;">${sv}</span>`).join(' ')
            : '';

        const ventasSection = ventas.length
            ? `<div class="border-top px-3 py-2" style="background:rgba(184,134,11,.03);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-1">
                        <i class="ri-shopping-cart-line" style="color:#b8860b;font-size:.85rem;"></i>
                        <span class="small fw-semibold text-muted">Ventas de Productos</span>
                    </div>
                    <span class="badge rounded-pill" style="background:rgba(184,134,11,.12);color:#b8860b;font-size:.65rem;">
                        ${ventas.length} venta${ventas.length !== 1 ? 's' : ''}
                    </span>
                </div>
                <div class="pago-scroll d-flex flex-column gap-2">
                    ${ventas.map(v => renderVenta(v)).join('')}
                </div>
               </div>`
            : '';

        return `
        <div class="sus-block">
            <div class="px-3 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2"
                style="background:rgba(26,45,76,.03);border-bottom:1px solid rgba(0,0,0,.05);">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <i class="ri-stack-line" style="color:#0d9488;"></i>
                    <span class="fw-bold small" style="color:#1a2d4c;">${s.plan}</span>
                    ${s.paquete ? `<span class="badge" style="background:rgba(13,148,136,.1);color:#0d9488;font-size:.68rem;font-weight:500;">${s.paquete}</span>` : ''}
                    ${s.tarifa === 'especial'
                        ? `<span class="badge" style="background:#fef3c7;color:#b45309;font-size:.68rem;font-weight:600;border:1px solid #fcd34d;"><i class="ri-star-fill me-1"></i>Especial</span>`
                        : `<span class="badge" style="background:#f0fdf4;color:#0d9488;font-size:.68rem;font-weight:500;"><i class="ri-sun-line me-1"></i>Normal</span>`}
                    <span class="badge" style="background:${est.bg};color:${est.color};font-size:.68rem;font-weight:500;">
                        <i class="${est.icon} me-1" style="font-size:.65rem;"></i>${ucfirst(s.estado)}
                    </span>
                    <span class="text-muted" style="font-size:.72rem;"><i class="ri-calendar-line me-1"></i>${s.fecha_registro}</span>
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    ${pago.pendiente > 0
                        ? `<a href="${s.pago_url}" class="btn-action-cobro btn btn-sm"
                            style="background:linear-gradient(135deg,#198754,#0d9488);color:#fff;">
                            <i class="ri-bank-card-line me-1"></i>Registrar pago
                           </a>`
                        : `<span class="badge rounded-pill bg-success-subtle text-success px-3 py-1" style="font-size:.72rem;">
                            <i class="ri-check-line me-1"></i>Pagado
                           </span>`}
                    <a href="${s.url}" class="btn-action-cobro btn btn-sm"
                        style="background:rgba(26,45,76,.08);color:#1a2d4c;">
                        <i class="ri-eye-line me-1"></i>Detalle
                    </a>
                    ${ses.porRealizar > 0
                        ? `<a href="${s.url}" class="btn-action-cobro btn btn-sm"
                            style="background:rgba(245,158,11,.1);color:#d97706;border:1px solid rgba(245,158,11,.25);">
                            <i class="ri-calendar-todo-line me-1"></i>Agendar
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem;">${ses.porRealizar}</span>
                           </a>`
                        : ''}
                </div>
            </div>

            <div class="p-3">
                <div class="row g-3">

                    <div class="col-lg-4">
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <i class="ri-calendar-check-line" style="color:#0d9488;font-size:.85rem;"></i>
                            <span class="small fw-semibold text-muted">Sesiones</span>
                        </div>
                        <div class="row g-2 mb-2">
                            ${chip(ses.totalPlan,   'Total plan',   '#1a2d4c', 'rgba(26,45,76,.05)',  'rgba(26,45,76,.1)')}
                            ${chip(ses.realizadas,  'Realizadas',   '#198754', 'rgba(25,135,84,.07)', 'rgba(25,135,84,.18)')}
                            ${ses.porRealizar > 0
                                ? `<div class="col-4">
                                    <a href="${s.url}" class="stat-chip d-block text-decoration-none"
                                        style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);">
                                        <div class="fw-bold" style="color:#d97706;">${ses.porRealizar}</div>
                                        <div style="color:#d97706;font-size:.65rem;">Pendientes</div>
                                    </a>
                                   </div>`
                                : chip(0, 'Pendientes', '#6c757d', 'rgba(108,117,125,.05)', 'rgba(108,117,125,.12)')}
                            ${ses.canceladas > 0 ? chip(ses.canceladas, 'Canceladas', '#dc3545', 'rgba(220,53,69,.06)', 'rgba(220,53,69,.15)') : ''}
                            ${chip(ses.asistidas, 'Asistió', '#198754', 'rgba(25,135,84,.05)', 'rgba(25,135,84,.12)')}
                            ${chip(ses.noAsist,   'No asistió', '#dc3545', 'rgba(220,53,69,.05)', 'rgba(220,53,69,.12)')}
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted" style="font-size:.7rem;">Progreso</span>
                            <span class="fw-semibold" style="font-size:.7rem;color:#0d9488;">${pctReal}%</span>
                        </div>
                        <div class="progress-cobro">
                            <div class="bar" style="width:${pctReal}%;background:linear-gradient(90deg,#1a2d4c,#0d9488);"></div>
                        </div>
                        ${s.servicios.length ? `<div class="mt-2 d-flex flex-wrap gap-1">${serviciosBadges}</div>` : ''}
                        ${s.hijos.length ? `<div class="mt-2 d-flex align-items-center gap-1">
                            <i class="ri-user-heart-line" style="color:#0d9488;font-size:.8rem;"></i>
                            <span class="small text-dark">${s.hijos.join(', ')}</span>
                        </div>` : ''}
                    </div>

                    <div class="col-lg-3">
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <i class="ri-money-dollar-circle-line" style="color:#0d9488;font-size:.85rem;"></i>
                            <span class="small fw-semibold text-muted">Pagos</span>
                        </div>
                        <div class="row g-2 mb-2">
                            ${pagoChip('Bs ' + fmt(pago.precio_plan),  'Precio plan',  '#1a2d4c', 'rgba(26,45,76,.05)',  'rgba(26,45,76,.1)')}
                            ${pagoChip('Bs ' + fmt(pago.total_pagado), 'Pagado',       '#198754', 'rgba(25,135,84,.07)', 'rgba(25,135,84,.18)')}
                            ${(pago.total_descuento && pago.total_descuento > 0)
                                ? pagoChip('Bs ' + fmt(pago.total_descuento), 'Descuento', '#b45309', 'rgba(180,83,9,.06)', 'rgba(180,83,9,.18)')
                                : ''}
                            ${pagoChip('Bs ' + fmt(pago.pendiente),    'Saldo',
                                pago.pendiente > 0 ? '#dc3545' : '#198754',
                                pago.pendiente > 0 ? 'rgba(220,53,69,.07)' : 'rgba(25,135,84,.05)',
                                pago.pendiente > 0 ? 'rgba(220,53,69,.18)' : 'rgba(25,135,84,.15)')}
                        </div>
                        ${(pago.total_descuento && pago.total_descuento > 0) ? `
                        <div class="text-muted mb-2" style="font-size:.65rem;line-height:1.3;">
                            <i class="ri-information-line me-1"></i>Saldo = Precio − Pagado − Descuento
                        </div>` : ''}
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted" style="font-size:.7rem;">Cobrado</span>
                            <span class="fw-semibold" style="font-size:.7rem;color:#198754;">${pctPago}%</span>
                        </div>
                        <div class="progress-cobro">
                            <div class="bar" style="width:${pctPago}%;background:linear-gradient(90deg,#198754,#0d9488);"></div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-receipt-line" style="color:#0d9488;font-size:.85rem;"></i>
                                <span class="small fw-semibold text-muted">Historial de Pagos</span>
                            </div>
                            <span class="badge rounded-pill" style="background:rgba(13,148,136,.1);color:#0d9488;font-size:.65rem;">
                                ${pago.detalle.length} pago${pago.detalle.length !== 1 ? 's' : ''}
                            </span>
                        </div>
                        ${pago.detalle.length
                            ? `<div class="pago-scroll d-flex flex-column gap-2">
                                ${pago.detalle.map(p => renderPago(p)).join('')}
                               </div>`
                            : `<div class="rounded-3 p-3 text-center"
                                    style="background:rgba(108,117,125,.04);border:1px dashed rgba(108,117,125,.15);">
                                    <i class="ri-inbox-line d-block mb-1 opacity-50" style="font-size:1.5rem;color:#6c757d;"></i>
                                    <span class="small text-muted">Sin pagos registrados</span>
                               </div>`}
                    </div>

                </div>
            </div>

            ${ventasSection}
        </div>`;
    }

    /* ==================== RENDER PAGO ==================== */
    function renderPago(p) {
        const tipoMap = {
            efectivo: { icon: 'ri-money-dollar-box-line', color: '#198754', bg: 'rgba(25,135,84,.08)' },
            qr:       { icon: 'ri-qr-code-line',         color: '#0d6efd', bg: 'rgba(13,110,253,.08)' },
            parcial:  { icon: 'ri-split-cells-horizontal',color: '#f59e0b', bg: 'rgba(245,158,11,.08)' },
        };
        const tipo = tipoMap[p.tipo_pago] || { icon: 'ri-money-dollar-box-line', color: '#6c757d', bg: 'rgba(108,117,125,.06)' };

        return `
        <div class="pago-row d-flex align-items-center justify-content-between"
            style="background:rgba(25,135,84,.03);border:1px solid rgba(25,135,84,.1);">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:28px;height:28px;background:${tipo.bg};">
                    <i class="${tipo.icon}" style="color:${tipo.color};font-size:.8rem;"></i>
                </div>
                <span class="fw-bold small" style="color:#1a2d4c;">Bs ${fmt(p.pago_bs)}</span>
                ${p.descuento_bs > 0 ? `<span class="small text-muted" style="font-size:.7rem;">(-Bs ${fmt(p.descuento_bs)})</span>` : ''}
                ${p.tipo_pago ? `<span class="badge" style="background:${tipo.bg};color:${tipo.color};font-size:.6rem;font-weight:500;">${ucfirst(p.tipo_pago)}</span>` : ''}
                <span class="text-muted" style="font-size:.68rem;">${p.fecha_pago}</span>
                ${p.recibo ? `<span class="text-muted" style="font-size:.65rem;opacity:.7;">N° ${p.recibo}</span>` : ''}
            </div>
            <a href="${p.recibo_url}" target="_blank"
                class="btn btn-sm rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:30px;height:30px;background:rgba(13,148,136,.08);color:#0d9488;border:1px solid rgba(13,148,136,.2);"
                title="Descargar recibo PDF">
                <i class="ri-download-2-line" style="font-size:.8rem;"></i>
            </a>
        </div>`;
    }

    /* ==================== RENDER VENTA (fila) ==================== */
    function renderVenta(v) {
        const tipoMap = {
            efectivo: { icon: 'ri-money-dollar-box-line', color: '#b8860b', bg: 'rgba(184,134,11,.1)' },
            qr:       { icon: 'ri-qr-code-line',         color: '#b8860b', bg: 'rgba(184,134,11,.08)' },
            parcial:  { icon: 'ri-split-cells-horizontal',color: '#b8860b', bg: 'rgba(184,134,11,.08)' },
        };
        const tipo = tipoMap[v.tipo_pago] || { icon: 'ri-shopping-cart-line', color: '#b8860b', bg: 'rgba(184,134,11,.06)' };
        const nProd = v.detalles.length;

        return `
        <div class="venta-row d-flex align-items-center justify-content-between"
            style="background:rgba(184,134,11,.03);border:1px solid rgba(184,134,11,.15);">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:28px;height:28px;background:${tipo.bg};">
                    <i class="${tipo.icon}" style="color:${tipo.color};font-size:.8rem;"></i>
                </div>
                <span class="fw-bold small" style="color:#1a2d4c;">Bs ${fmt(v.total_bs)}</span>
                ${v.descuento_bs > 0 ? `<span class="small text-muted" style="font-size:.7rem;">(-Bs ${fmt(v.descuento_bs)})</span>` : ''}
                <span class="badge" style="background:${tipo.bg};color:${tipo.color};font-size:.6rem;font-weight:500;">${ucfirst(v.tipo_pago)}</span>
                <span class="text-muted" style="font-size:.68rem;">${v.fecha_venta}</span>
                ${v.n_sesion ? `<span class="text-muted" style="font-size:.65rem;opacity:.7;">Sesión #${v.n_sesion}</span>` : ''}
                ${v.recibo ? `<span class="text-muted" style="font-size:.65rem;opacity:.7;">N° ${v.recibo}</span>` : ''}
                <span class="badge rounded-pill" style="background:rgba(184,134,11,.1);color:#b8860b;font-size:.6rem;">
                    ${nProd} prod.
                </span>
            </div>
            <button type="button"
                class="btn btn-sm rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center btn-ver-venta-cobro"
                style="width:30px;height:30px;background:rgba(184,134,11,.1);color:#b8860b;border:1px solid rgba(184,134,11,.25);"
                title="Ver detalle de venta"
                data-venta='${JSON.stringify(v).replace(/'/g, "&#39;")}'>
                <i class="ri-eye-line" style="font-size:.8rem;"></i>
            </button>
        </div>`;
    }

    /* ==================== MODAL DETALLE VENTA ==================== */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-ver-venta-cobro');
        if (!btn) return;

        const v = JSON.parse(btn.dataset.venta);
        const modal = document.getElementById('modalDetalleVentaCobro');
        const body  = document.getElementById('modalDetalleVentaCobroBody');
        const pdfBtn = document.getElementById('modalDetalleVentaPdfBtn');

        pdfBtn.href = v.pdf_url;

        const tipoMap = {
            efectivo: 'bg-warning bg-opacity-10 text-warning',
            qr:       'bg-info bg-opacity-10 text-info',
            parcial:  'bg-secondary bg-opacity-10 text-secondary',
        };
        const tipoBadge = tipoMap[v.tipo_pago] || 'bg-light text-dark';

        const productosRows = v.detalles.map((d, i) => `
            <tr>
                <td class="text-center">${i+1}</td>
                <td>${d.nombre}${d.marca ? ` <small class="text-muted">(${d.marca})</small>` : ''}</td>
                <td class="text-center">${d.cantidad}</td>
                <td class="text-end">Bs ${fmt(d.precio_unitario)}</td>
                <td class="text-end fw-bold">Bs ${fmt(d.subtotal)}</td>
            </tr>`).join('');

        const pagoDetallesBadges = v.pago_detalles.length
            ? v.pago_detalles.map(pd => {
                const c = { efectivo:'bg-warning bg-opacity-10 text-warning', qr:'bg-info bg-opacity-10 text-info' }[pd.tipo_pago] || 'bg-secondary bg-opacity-10 text-secondary';
                return `<span class="badge ${c} px-3 py-2 rounded-pill me-1">${ucfirst(pd.tipo_pago)}: Bs ${fmt(pd.pago_bs)}</span>`;
              }).join('')
            : `<span class="badge ${tipoBadge} px-3 py-2 rounded-pill">${ucfirst(v.tipo_pago)}: Bs ${fmt(v.total_bs)}</span>`;

        const subtotal = v.detalles.reduce((acc, d) => acc + d.subtotal, 0);

        body.innerHTML = `
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <div class="p-3 rounded-3" style="background:rgba(184,134,11,.06);border:1px solid rgba(184,134,11,.15);">
                        <div class="small text-muted mb-1">Recibo</div>
                        <div class="fw-bold" style="color:#b8860b;">${v.recibo || '-'}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-3 rounded-3" style="background:rgba(184,134,11,.06);border:1px solid rgba(184,134,11,.15);">
                        <div class="small text-muted mb-1">Fecha</div>
                        <div class="fw-bold text-dark">${v.fecha_venta}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-3 rounded-3" style="background:rgba(184,134,11,.06);border:1px solid rgba(184,134,11,.15);">
                        <div class="small text-muted mb-1">Tipo de pago</div>
                        <span class="badge ${tipoBadge} px-2 py-1 rounded-pill">${ucfirst(v.tipo_pago)}</span>
                    </div>
                </div>
                ${v.n_sesion ? `<div class="col-sm-4">
                    <div class="p-3 rounded-3 bg-light">
                        <div class="small text-muted mb-1">Sesión</div>
                        <div class="fw-bold text-dark">#${v.n_sesion}</div>
                    </div>
                </div>` : ''}
                ${v.trabajador ? `<div class="col-sm-8">
                    <div class="p-3 rounded-3 bg-light">
                        <div class="small text-muted mb-1">Registrado por</div>
                        <div class="fw-bold text-dark"><i class="ri-user-line me-1 text-primary"></i>${v.trabajador}</div>
                    </div>
                </div>` : ''}
            </div>

            <div class="mb-3">
                <h6 class="fw-bold text-dark mb-2"><i class="ri-shopping-bag-line me-1" style="color:#b8860b;"></i>Productos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead style="background:rgba(184,134,11,.12);">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>${productosRows}</tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-semibold text-muted small">Subtotal:</td>
                                <td class="text-end fw-bold">Bs ${fmt(subtotal)}</td>
                            </tr>
                            ${v.descuento_bs > 0 ? `<tr>
                                <td colspan="4" class="text-end fw-semibold text-danger small">Descuento:</td>
                                <td class="text-end fw-bold text-danger">- Bs ${fmt(v.descuento_bs)}</td>
                            </tr>` : ''}
                            <tr style="background:rgba(184,134,11,.08);">
                                <td colspan="4" class="text-end fw-bold" style="color:#b8860b;">TOTAL:</td>
                                <td class="text-end fw-bold" style="color:#b8860b;">Bs ${fmt(v.total_bs)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div>
                <h6 class="fw-bold text-dark mb-2"><i class="ri-bank-card-line me-1" style="color:#b8860b;"></i>Detalle de Pago</h6>
                <div>${pagoDetallesBadges}</div>
            </div>`;

        new bootstrap.Modal(modal).show();
    });

    /* ==================== HELPERS ==================== */
    function chip(valor, label, color, bg, border) {
        return `<div class="col-4">
            <div class="stat-chip" style="background:${bg};border:1px solid ${border};">
                <div class="fw-bold" style="color:${color};font-size:.9rem;">${valor}</div>
                <div class="text-muted" style="font-size:.65rem;">${label}</div>
            </div>
        </div>`;
    }

    function pagoChip(valor, label, color, bg, border) {
        return `<div class="col-4">
            <div class="stat-chip" style="background:${bg};border:1px solid ${border};">
                <div class="fw-bold" style="color:${color};font-size:.78rem;">${valor}</div>
                <div class="text-muted" style="font-size:.65rem;">${label}</div>
            </div>
        </div>`;
    }

    function fmt(n) {
        return Number(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function ucfirst(s) {
        return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
    }
})();
</script>
@endpush
