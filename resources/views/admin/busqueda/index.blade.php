@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold" style="color:#1a2d4c;">
                            <i class="ri-search-2-line me-2" style="color:#0d9488;"></i>Búsqueda General
                        </h4>
                        <p class="text-muted mb-0">Busca clientes o hijos por nombre y consulta sus suscripciones, sesiones y pagos</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 rounded-start-3"
                                style="background:linear-gradient(135deg,#1a2d4c,#0d9488);">
                                <i class="ri-search-line text-white fs-5"></i>
                            </span>
                            <input type="text" id="busquedaInput" class="form-control border-0 shadow-none fs-6"
                                placeholder="Busca por nombre o carnet del cliente o del niño/a..."
                                autocomplete="off"
                                style="background:#f8fafc;">
                            <button class="btn border-0 rounded-end-3" id="btnLimpiar"
                                style="background:#f0f0f0;color:#6c757d;display:none;">
                                <i class="ri-close-line fs-5"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="text-muted small"><i class="ri-information-line me-1"></i>Mínimo 2 caracteres para buscar</span>
                            <span id="busquedaStatus" class="small ms-auto text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Results --}}
        <div id="resultados"></div>

        {{-- Empty state --}}
        <div id="estadoVacio" class="text-center py-5">
            <div class="mb-3">
                <i class="ri-search-eye-line" style="font-size:4rem;color:rgba(13,148,136,.3);"></i>
            </div>
            <h5 class="text-muted fw-semibold">Busca un cliente o niño/a</h5>
            <p class="text-muted small">Los resultados mostrarán suscripciones, sesiones y pagos asociados</p>
        </div>

        {{-- No results --}}
        <div id="sinResultados" class="text-center py-5" style="display:none;">
            <i class="ri-file-search-line" style="font-size:4rem;color:rgba(220,53,69,.25);"></i>
            <h5 class="mt-3 text-muted">No se encontraron resultados</h5>
            <p class="text-muted small">Intenta con otro nombre</p>
        </div>

    </div>

    <script>
        (function () {
            const input    = document.getElementById('busquedaInput');
            const btnLimpia = document.getElementById('btnLimpiar');
            const status   = document.getElementById('busquedaStatus');
            const container = document.getElementById('resultados');
            const vacio    = document.getElementById('estadoVacio');
            const noRes    = document.getElementById('sinResultados');

            let debounceTimer = null;

            input.addEventListener('input', function () {
                const q = this.value.trim();
                btnLimpia.style.display = q.length ? '' : 'none';
                clearTimeout(debounceTimer);

                if (q.length < 2) {
                    status.textContent = '';
                    container.innerHTML = '';
                    vacio.style.display = '';
                    noRes.style.display = 'none';
                    return;
                }

                status.textContent = 'Buscando...';
                debounceTimer = setTimeout(() => buscar(q), 350);
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
                    const url = `{{ route('admin.buscar.resultados') }}?q=${encodeURIComponent(q)}`;
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
                    status.textContent = `${data.length} resultado${data.length > 1 ? 's' : ''}`;
                    renderResultados(data);
                } catch (e) {
                    status.textContent = 'Error al buscar';
                }
            }

            function renderResultados(clientes) {
                container.innerHTML = clientes.map(c => renderCliente(c)).join('');
                if (typeof bootstrap !== 'undefined') {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                        try { new bootstrap.Tooltip(el); } catch (e) {}
                    });
                }
            }

            function renderCliente(c) {
                const avatarLetras = (c.nombre || '?').substring(0, 2).toUpperCase();
                const hijosCoinciden = c.hijos_coinciden.length
                    ? `<span class="badge rounded-pill ms-2" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.72rem;">
                            <i class="ri-baby-line me-1"></i>Coincide con: ${c.hijos_coinciden.join(', ')}
                       </span>`
                    : '';

                // URL para registrar suscripción (con cliente pre-seleccionado y opcionalmente el hijo)
                const hijoCoincideId = c.hijos_coinciden_info && c.hijos_coinciden_info.length
                    ? c.hijos_coinciden_info[0].id
                    : null;
                const suscripcionUrl = c.nueva_suscripcion_url
                    + '?cliente_id=' + c.id
                    + (hijoCoincideId ? '&hijo_id=' + hijoCoincideId : '');

                const suscripciones = c.suscripciones.length
                    ? c.suscripciones.map(s => renderSuscripcion(s)).join('')
                    : `<div class="text-center py-4 text-muted small">
                            <i class="ri-calendar-check-line fs-4 d-block mb-1 opacity-50"></i>Sin suscripciones
                       </div>`;

                return `
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    {{-- Header cliente --}}
                    <div class="card-header py-3 px-4"
                        style="background:linear-gradient(135deg,${c.color ?? '#1a2d4c'} 0%,rgba(13,148,136,.85) 100%);">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                style="width:48px;height:48px;background:rgba(255,255,255,.2);font-size:1rem;letter-spacing:.05em;">
                                ${avatarLetras}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold text-white">
                                    ${c.nombre}${hijosCoinciden}
                                </h6>
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    ${c.carnet ? `<span class="small text-white-50"><i class="ri-id-card-line me-1"></i>${c.carnet}</span>` : ''}
                                    ${c.celular ? `<span class="small text-white-50"><i class="ri-phone-line me-1"></i>${c.celular}</span>` : ''}
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0 flex-wrap">
                                <a href="${c.seguimiento_url}" class="btn btn-sm rounded-pill px-3"
                                    style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);">
                                    <i class="ri-eye-line me-1"></i>Ver perfil
                                </a>
                                <a href="${suscripcionUrl}" class="btn btn-sm rounded-pill px-3 fw-semibold"
                                    style="background:rgba(255,255,255,.9);color:#0d9488;border:1px solid rgba(255,255,255,.6);">
                                    <i class="ri-add-circle-line me-1"></i>Registrar Suscripción
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Suscripciones --}}
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-3">
                            ${suscripciones}
                        </div>
                    </div>
                </div>`;
            }

            function renderSuscripcion(s) {
                const ses = s.sesiones;
                const pago = s.pagos;

                // Barras de progreso
                const pctReal      = ses.total_plan > 0 ? Math.round((ses.realizadas / ses.total_plan) * 100) : 0;
                const pctPago      = pago.precio_plan > 0 ? Math.round((pago.total_pagado / pago.precio_plan) * 100) : 0;
                const pctDescuento = pago.precio_plan > 0 ? Math.round((pago.total_descuento / pago.precio_plan) * 100) : 0;
                const pctPendiente = pago.precio_plan > 0 ? Math.round((pago.pendiente / pago.precio_plan) * 100) : 0;

                const serviciosBadges = s.servicios.length
                    ? s.servicios.map(sv => `<span class="badge rounded-pill me-1" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;">${sv}</span>`).join('')
                    : '<span class="text-muted small">—</span>';

                const hijosStr = s.hijos.length ? s.hijos.join(', ') : '—';

                const estadoBadgeColor = {
                    'activa': '#198754', 'completada': '#0d6efd', 'cancelada': '#dc3545'
                }[s.estado] ?? '#6c757d';

                const bordes = {
                    completada: s.pago_completado ? '#0d9488' : '#0d6efd',
                    cancelada:  '#dc3545',
                };
                const bordePlan  = bordes[s.estado] || 'rgba(26,45,76,.1)';
                const bgHeader   = bordes[s.estado] ? `${bordes[s.estado]}0d` : 'rgba(26,45,76,.04)';
                const bgBody     = bordes[s.estado] ? `${bordes[s.estado]}06` : '';

                return `
                <div class="rounded-4 overflow-hidden border" style="border-color:${bordePlan}!important;${bgBody ? `background:${bgBody};` : ''}">
                    {{-- Sub-header suscripción --}}
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2"
                        style="background:${bgHeader};">
                        <div>
                            <span class="fw-bold small" style="color:#1a2d4c;">
                                <i class="ri-stack-line me-1" style="color:#0d9488;"></i>${s.plan}
                            </span>
                            ${s.paquete ? `<span class="badge ms-2" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;">${s.paquete}</span>` : ''}
                            <span class="badge ms-1" style="font-size:.7rem;${s.tarifa === 'especial' ? 'background:#fef3c7;color:#b45309;' : 'background:#f0fdf4;color:#16a34a;'}"><i class="ri-${s.tarifa === 'especial' ? 'star-fill' : 'sun-line'} me-1"></i>${ucfirst(s.tarifa)}</span>
                            <span class="badge ms-1" style="background:${estadoBadgeColor}22;color:${estadoBadgeColor};font-size:.7rem;">${ucfirst(s.estado)}</span>
                            ${s.pago_completado && s.estado !== 'cancelada' ? `<span class="badge ms-1" style="background:rgba(13,110,253,.12);color:#0d6efd;font-size:.7rem;"><i class="ri-check-double-line me-1"></i>Pagado</span>` : ''}
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="text-muted small"><i class="ri-calendar-line me-1"></i>${s.fecha_registro}</span>
                            <a href="${s.url}" class="btn btn-sm rounded-pill px-3 fw-semibold"
                                style="background:linear-gradient(135deg,#1a2d4c,#0d9488);color:#fff;font-size:.78rem;">
                                <i class="ri-eye-line me-1"></i>Ver suscripción
                            </a>
                            ${ses.por_realizar > 0
                                ? `<a href="${s.url}" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold" style="font-size:.78rem;">
                                    <i class="ri-calendar-add-line me-1"></i>Agendar
                                    <span class="badge bg-dark ms-1">${ses.por_realizar}</span>
                                   </a>`
                                : ''}
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="row g-3">
                            {{-- Sesiones --}}
                            <div class="col-md-6">
                                <div class="small fw-semibold text-muted mb-2">
                                    <i class="ri-calendar-check-line me-1" style="color:#0d9488;"></i>Sesiones
                                </div>
                                <div class="row g-2 mb-2">
                                    ${sesionChip(ses.total_plan,  'Total plan',    '#1a2d4c', 'rgba(26,45,76,.06)', 'rgba(26,45,76,.1)')}
                                    ${sesionChip(ses.realizadas,  'Realizadas',    '#198754', 'rgba(25,135,84,.08)', 'rgba(25,135,84,.2)')}
                                    ${ses.por_realizar > 0
                                        ? `<div class="col-4">
                                            <a href="${s.url}" class="rounded-3 p-2 text-center d-block text-decoration-none"
                                                style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);">
                                                <div class="fw-bold" style="color:#d97706;">${ses.por_realizar}</div>
                                                <div class="small" style="color:#d97706;font-size:.7rem;">Por realizar ↗</div>
                                            </a>
                                           </div>`
                                        : sesionChip(0, 'Por realizar', '#6c757d', 'rgba(108,117,125,.06)', 'rgba(108,117,125,.15)')}
                                    ${sesionChip(ses.canceladas,  'Canceladas',    '#dc3545', 'rgba(220,53,69,.08)', 'rgba(220,53,69,.2)')}
                                    ${sesionChip(ses.asistidas,   'Asistió',       '#198754', 'rgba(25,135,84,.06)', 'rgba(25,135,84,.15)')}
                                    ${sesionChip(ses.no_asistidas,'No asistió',    '#dc3545', 'rgba(220,53,69,.06)', 'rgba(220,53,69,.15)')}
                                </div>
                                <div class="mb-1 d-flex justify-content-between small text-muted">
                                    <span>Progreso sesiones</span><span>${pctReal}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height:6px;">
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:${pctReal}%;background:linear-gradient(90deg,#1a2d4c,#0d9488);"></div>
                                </div>
                            </div>

                            {{-- Pagos --}}
                            <div class="col-md-6">
                                <div class="small fw-semibold text-muted mb-2">
                                    <i class="ri-money-dollar-circle-line me-1" style="color:#0d9488;"></i>Pagos
                                </div>
                                <div class="row g-2 mb-2">
                                    ${pagoChip('Bs ' + fmt(pago.precio_plan),    'Precio plan',     '#1a2d4c', 'rgba(26,45,76,.06)', 'rgba(26,45,76,.1)')}
                                    ${pagoChip('Bs ' + fmt(pago.total_pagado),   'Total pagado',    '#198754', 'rgba(25,135,84,.08)', 'rgba(25,135,84,.2)')}
                                    ${pago.total_descuento > 0
                                        ? pagoChip('Bs ' + fmt(pago.total_descuento), 'Descuento', '#0d6efd', 'rgba(13,110,253,.08)', 'rgba(13,110,253,.2)')
                                        : pagoChip('Bs 0.00', 'Descuento', '#6c757d', 'rgba(108,117,125,.06)', 'rgba(108,117,125,.15)')}
                                    ${pagoChip('Bs ' + fmt(pago.pendiente),      'Pendiente',
                                        pago.pendiente > 0 ? '#dc3545' : '#198754',
                                        pago.pendiente > 0 ? 'rgba(220,53,69,.08)' : 'rgba(25,135,84,.06)',
                                        pago.pendiente > 0 ? 'rgba(220,53,69,.2)' : 'rgba(25,135,84,.2)')}
                                </div>
                                <div class="d-flex flex-wrap gap-2 small">
                                    <span class="text-muted"><i class="ri-receipt-line me-1"></i>${pago.n_pagos} pago${pago.n_pagos !== 1 ? 's' : ''}</span>
                                    ${pago.ultimo_pago ? `<span class="text-muted"><i class="ri-calendar-line me-1"></i>Último: ${pago.ultimo_pago}</span>` : ''}
                                </div>
                                <div class="mt-2 mb-1 d-flex justify-content-between small text-muted">
                                    <span>
                                        <span class="text-success">${pctPago}% pagado</span>
                                        ${pago.total_descuento > 0 ? `<span class="text-primary ms-2">${pctDescuento}% descuento</span>` : ''}
                                        <span class="text-danger ms-2">${pctPendiente}% pendiente</span>
                                    </span>
                                </div>
                                <div class="progress rounded-pill" style="height:8px;">
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:${pctPago}%;background:linear-gradient(90deg,#198754,#0d9488);"
                                        data-bs-toggle="tooltip" title="Pagado: Bs ${fmt(pago.total_pagado)}">
                                    </div>
                                    ${pago.total_descuento > 0 ? `
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:${pctDescuento}%;background:linear-gradient(90deg,#0d6efd,#6610f2);"
                                        data-bs-toggle="tooltip" title="Descuento: Bs ${fmt(pago.total_descuento)}">
                                    </div>` : ''}
                                    ${pago.pendiente > 0 ? `
                                    <div class="progress-bar rounded-pill" role="progressbar"
                                        style="width:${pctPendiente}%;background:linear-gradient(90deg,#dc3545,#f08080);"
                                        data-bs-toggle="tooltip" title="Pendiente: Bs ${fmt(pago.pendiente)}">
                                    </div>` : ''}
                                </div>

                                {{-- Lista de pagos realizados --}}
                                ${(s.pagos_detalle && s.pagos_detalle.length > 0) ? `
                                <div class="mt-3 pt-2" style="border-top:1px solid #f0f0f0;">
                                    <button class="btn btn-sm w-100 d-flex align-items-center justify-content-between rounded-3 fw-semibold"
                                        style="background:rgba(13,148,136,.08);color:#0d9488;border:1px solid rgba(13,148,136,.2);font-size:.75rem;"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pagosDetalle_${s.id}" aria-expanded="false">
                                        <span><i class="ri-receipt-line me-1"></i>Ver pagos realizados (${s.pagos_detalle.length})</span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    <div class="collapse mt-2" id="pagosDetalle_${s.id}">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0" style="font-size:.72rem;">
                                                <thead style="background:#f8fafc;">
                                                    <tr>
                                                        <th class="border-0 py-2" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Recibo</th>
                                                        <th class="border-0 py-2" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Fecha</th>
                                                        <th class="border-0 py-2" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Tipo</th>
                                                        <th class="border-0 py-2 text-end" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Monto</th>
                                                        <th class="border-0 py-2 text-end" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Desc.</th>
                                                        <th class="border-0 py-2 text-center" style="text-transform:uppercase;letter-spacing:.4px;color:#475569;font-size:.65rem;">Recibo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${s.pagos_detalle.map(p => {
                                                        const tipoBadge = (p.tipo_pago === 'efectivo')
                                                            ? '<span class="badge rounded-pill" style="background:#fef9c3;color:#854d0e;font-size:.6rem;"><i class="ri-money-dollar-circle-line me-1"></i>Efectivo</span>'
                                                            : (p.tipo_pago === 'qr')
                                                                ? '<span class="badge rounded-pill" style="background:#e0f2fe;color:#075985;font-size:.6rem;"><i class="ri-qr-code-line me-1"></i>QR</span>'
                                                                : '<span class="badge rounded-pill" style="background:#e2e8f0;color:#334155;font-size:.6rem;"><i class="ri-exchange-line me-1"></i>' + (p.tipo_pago || '-') + '</span>';
                                                        return `
                                                        <tr>
                                                            <td class="py-2"><span class="fw-bold" style="color:#1a2d4c;">${p.recibo}</span></td>
                                                            <td class="py-2"><i class="ri-calendar-event-line text-muted me-1"></i>${p.fecha}</td>
                                                            <td class="py-2">${tipoBadge}</td>
                                                            <td class="py-2 text-end fw-bold" style="color:#15803d;">${fmt(p.monto)} Bs.</td>
                                                            <td class="py-2 text-end" style="color:${p.descuento > 0 ? '#b91c1c' : '#94a3b8'};">${p.descuento > 0 ? fmt(p.descuento) + ' Bs.' : '—'}</td>
                                                            <td class="py-2 text-center">
                                                                <a href="${p.pdf_url}" target="_blank" rel="noopener"
                                                                    class="btn btn-sm rounded-pill px-2"
                                                                    style="background:#dc2626;color:#fff;border:none;font-size:.6rem;line-height:1;padding:3px 8px;"
                                                                    title="Ver recibo PDF">
                                                                    <i class="ri-file-pdf-line me-1"></i>PDF
                                                                </a>
                                                            </td>
                                                        </tr>`;
                                                    }).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>` : ''}

                                {{-- Servicios y hijos --}}
                                <div class="mt-3 pt-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="small text-muted mb-1"><i class="ri-heart-pulse-line me-1"></i>Servicios:</div>
                                    <div class="mb-2">${serviciosBadges}</div>
                                    <div class="small text-muted"><i class="ri-baby-line me-1"></i>Niño/a: <span class="text-dark">${hijosStr}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            function sesionChip(valor, label, color, bg, border) {
                return `<div class="col-4">
                    <div class="rounded-3 p-2 text-center" style="background:${bg};border:1px solid ${border};">
                        <div class="fw-bold" style="color:${color};">${valor}</div>
                        <div class="small text-muted" style="font-size:.7rem;">${label}</div>
                    </div>
                </div>`;
            }

            function pagoChip(valor, label, color, bg, border) {
                return `<div class="col-4">
                    <div class="rounded-3 p-2 text-center" style="background:${bg};border:1px solid ${border};">
                        <div class="fw-bold small" style="color:${color};font-size:.82rem;">${valor}</div>
                        <div class="small text-muted" style="font-size:.7rem;">${label}</div>
                    </div>
                </div>`;
            }

            function fmt(n) {
                return Number(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function ucfirst(str) {
                return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
            }
        })();
    </script>
@endsection
