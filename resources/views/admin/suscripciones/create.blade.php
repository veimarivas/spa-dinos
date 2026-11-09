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
                            <i class="ri-add-circle-line me-2"></i>Nueva Suscripción
                        </h4>
                        <p class="text-white-50 mb-0">Registra una nueva suscripción seleccionando cliente, plan,
                            programando las sesiones y registrando los pagos</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.suscripciones.index') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Steps (opcional, puedes mantenerlo igual) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div class="progress-line position-absolute top-50 start-0 translate-middle-y w-100 d-none d-md-block"
                                style="height: 3px; background: #e9ecef; z-index: 1;"></div>
                            <div class="step-item active text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-primary text-white mb-2 mx-auto">
                                    <span class="avatar-title"><i class="ri-user-line"></i></span>
                                </div>
                                <small class="fw-semibold">1. Cliente</small>
                            </div>
                            <div class="step-item text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-light text-muted mb-2 mx-auto border">
                                    <span class="avatar-title"><i class="ri-stack-line"></i></span>
                                </div>
                                <small class="fw-semibold">2. Plan</small>
                            </div>
                            <div class="step-item text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-light text-muted mb-2 mx-auto border">
                                    <span class="avatar-title"><i class="ri-baby-line"></i></span>
                                </div>
                                <small class="fw-semibold">3. Hijo</small>
                            </div>
                            <div class="step-item text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-light text-muted mb-2 mx-auto border">
                                    <span class="avatar-title"><i class="ri-calendar-line"></i></span>
                                </div>
                                <small class="fw-semibold">4. Sesiones</small>
                            </div>
                            <div class="step-item text-center" style="z-index: 2;">
                                <div class="avatar-md rounded-circle bg-light text-muted mb-2 mx-auto border">
                                    <span class="avatar-title"><i class="ri-bank-card-line"></i></span>
                                </div>
                                <small class="fw-semibold">5. Pagos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('admin.suscripciones.store') }}" id="formSuscripcion">
                    @csrf

                    <!-- ========== SECCIÓN 1: CLIENTE ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-primary"><i class="ri-user-star-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Seleccionar Cliente</h5>
                                    <small class="text-muted">Busca y selecciona el cliente para esta suscripción</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-search-line me-1 text-primary"></i>Buscar Cliente
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <div class="cliente-search-box d-flex align-items-center rounded-3 px-3 gap-2">
                                            <i class="ri-search-2-line text-primary fs-5 flex-shrink-0"></i>
                                            <input type="text" id="cliente-search-input"
                                                class="form-control border-0 shadow-none bg-transparent py-3 px-0 fs-6"
                                                placeholder="Buscar por CI, nombre o correo..."
                                                autocomplete="off">
                                            <div id="cliente-search-spinner" class="flex-shrink-0" style="display:none;">
                                                <span class="spinner-border spinner-border-sm text-primary"></span>
                                            </div>
                                            <button type="button" id="btnLimpiarCliente"
                                                class="btn btn-link p-0 text-danger flex-shrink-0" style="display:none;" title="Limpiar">
                                                <i class="ri-close-circle-fill fs-5"></i>
                                            </button>
                                        </div>
                                        <div id="cliente-search-results" class="cliente-search-dropdown position-absolute w-100"
                                            style="display:none; z-index:1050; top: calc(100% + 6px); background-color:#ffffff; border-radius:12px; border:1.5px solid #c7d0e8; box-shadow:0 12px 40px rgba(64,81,137,0.25),0 4px 12px rgba(0,0,0,0.12);">
                                        </div>
                                    </div>
                                    <input type="hidden" name="cliente_id" id="cliente_id_hidden" required>
                                    <div class="form-text text-muted mt-1">
                                        <i class="ri-information-line me-1"></i>Escribe al menos 2 caracteres
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-start pt-4">
                                    <button type="button" class="btn btn-outline-primary btn-lg w-100 rounded-3 mt-2"
                                        id="btnRegistrarCliente">
                                        <i class="ri-user-add-line me-2"></i>Nuevo Cliente
                                    </button>
                                </div>
                                <!-- Card cliente seleccionado -->
                                <div class="col-12" id="cliente-seleccionado-card" style="display:none;">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 cliente-card-selected">
                                        <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-5"
                                            id="cliente-card-avatar"
                                            style="width:52px; height:52px; background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);">
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-dark" id="cliente-card-nombre"></div>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                <span class="badge bg-primary bg-opacity-10 text-primary small fw-normal">
                                                    <i class="ri-id-card-line me-1"></i><span id="cliente-card-carnet"></span>
                                                </span>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary small fw-normal" id="cliente-card-correo-wrap" style="display:none;">
                                                    <i class="ri-mail-line me-1"></i><span id="cliente-card-correo"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="badge bg-success px-3 py-2 rounded-pill flex-shrink-0">
                                            <i class="ri-check-line me-1"></i>Seleccionado
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 2: HIJO ========== -->
                    <div id="hijo-selection-container" style="display: none;"
                        class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-info"><i class="ri-baby-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Seleccionar Hijo</h5>
                                    <small class="text-muted">Elige el hijo que recibirá las sesiones</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-user-smile-line me-1 text-info"></i>Hijo para todas las sesiones
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="hijo_default" class="form-select form-select-lg rounded-3 border-2">
                                        <option value="">-- Primero seleccione un cliente --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success btn-lg rounded-3 w-100 shadow-sm"
                                        id="btnRegistrarHijo">
                                        <i class="ri-add-circle-line me-2"></i>Registrar Nuevo Hijo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 3: PAQUETE Y PLAN ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-success"><i class="ri-stack-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Paquete y Plan</h5>
                                    <small class="text-muted">Selecciona el paquete y plan de suscripción</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-box-3-line me-1 text-success"></i>Paquete
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="paquete_select" class="form-select form-select-lg rounded-3 border-2"
                                        required>
                                        <option value="">-- Seleccione un paquete --</option>
                                        @foreach ($paquetes as $paquete)
                                            <option value="{{ $paquete->id }}">{{ $paquete->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-file-list-3-line me-1 text-success"></i>Plan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="plane_id" id="plane_select"
                                        class="form-select form-select-lg rounded-3 border-2" required disabled>
                                        <option value="">-- Primero seleccione un paquete --</option>
                                    </select>
                                    <div class="form-text text-muted mt-2">
                                        <i class="ri-information-line me-1"></i>Los planes disponibles dependen del paquete
                                        seleccionado
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 rounded-3"
                                        style="background: rgba(255,193,7,0.08); border: 1.5px dashed rgba(255,193,7,0.4);">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:42px;height:42px;">
                                                <i class="ri-heart-pulse-line text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">Aplicar precio especial</div>
                                                <small class="text-muted">
                                                    Para clientes con hijos con necesidades especiales. Al activar,
                                                    los planes mostrarán su <strong>precio especial</strong> y la deuda
                                                    de la suscripción se registrará con ese monto.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch" style="min-height: auto;">
                                            <input type="hidden" name="tarifa" value="normal">
                                            <input class="form-check-input" type="checkbox"
                                                role="switch"
                                                id="tarifa_especial_switch"
                                                name="tarifa"
                                                value="especial"
                                                style="width: 3em; height: 1.6em; cursor: pointer;">
                                            <label class="form-check-label fw-semibold ms-2" for="tarifa_especial_switch">
                                                <span id="tarifa-label" class="badge bg-secondary">Precio normal</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 4: FECHA DE REGISTRO ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                    <span class="avatar-title text-warning"><i class="ri-calendar-line fs-5"></i></span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">Fecha de Registro</h5>
                                    <small class="text-muted">Establece la fecha de creación de la suscripción</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-calendar-event-line me-1 text-warning"></i>Fecha
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="fecha_registro"
                                        class="form-control form-control-lg rounded-3 border-2"
                                        value="{{ old('fecha_registro', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 5: SESIONES ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4" id="sesiones-card" style="display: none;">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle me-3">
                                        <span class="avatar-title text-danger"><i
                                                class="ri-calendar-check-line fs-5"></i></span>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Programar Sesiones</h5>
                                        <small class="text-muted">Configura cada sesión del plan seleccionado</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill"
                                    id="total-sesiones-badge">
                                    <i class="ri-time-line me-1"></i><span id="total-sesiones-count">0</span> sesiones
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info d-flex align-items-center rounded-3 mb-3" role="alert">
                                <i class="ri-calendar-check-line fs-4 me-3 text-info"></i>
                                <div>
                                    <strong>¿Cómo programar sesiones?</strong>
                                    <p class="mb-0 small">Haz clic en cualquier día del calendario para agregar una sesión. Puedes programar hasta <strong id="info-total-sesiones">0</strong> sesiones (puedes guardar sin agendar todas). Haz clic sobre un evento para eliminarlo.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small fw-medium">Sesiones programadas: <strong id="sesiones-programadas">0</strong> / <strong id="sesiones-requeridas">0</strong></span>
                                <span id="badge-sesiones-ok" class="badge bg-success d-none"><i class="ri-check-line me-1"></i>¡Completado!</span>
                            </div>
                            <div class="progress mb-4" style="height: 8px; border-radius: 10px;">
                                <div class="progress-bar bg-danger" id="progreso-sesiones" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <!-- Filtro por fisioterapeuta y leyenda -->
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 fw-medium small"><i class="ri-user-settings-line me-1"></i>Ver agenda de:</label>
                                    <select id="filtro-fisioterapeuta-cal" class="form-select form-select-sm" style="min-width: 220px;">
                                        <option value="">Todos los fisioterapeutas</option>
                                        @foreach ($trabajadores as $trabajador)
                                            <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:14px;height:14px;background:#dc3545;border-radius:3px;"></span> Horario ocupado</span>
                                    <span class="d-flex align-items-center gap-1 small"><span style="display:inline-block;width:14px;height:14px;background:#405189;border-radius:3px;"></span> Sesión a programar</span>
                                </div>
                            </div>
                            <div id="calendar-sesiones" class="rounded-3"></div>
                            <div id="sesiones-inputs"></div>

                            <!-- Panel detalle de sesiones programadas -->
                            <div id="panel-detalle-sesiones" class="mt-4" style="display:none;">
                                <hr class="my-3" style="border-color: rgba(64,81,137,0.15);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="ri-list-check-2 me-2" style="color:#405189;"></i>Detalle de Sesiones Programadas
                                    </h6>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                        <span id="detalle-count">0</span> sesiones
                                    </span>
                                </div>
                                <div id="lista-sesiones-detalle" class="row g-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 6: PAGOS ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4" id="pagos-card" style="display: none;">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                        <span class="avatar-title text-success"><i class="ri-bank-card-line fs-5"></i></span>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Registrar Pago</h5>
                                        <small class="text-muted">Registra el pago inicial de la suscripción</small>
                                    </div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" id="total-pagado-badge">
                                    <i class="ri-money-dollar-circle-line me-1"></i><span id="total-pagado">0.00</span> Bs.
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Resumen del plan -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="rounded-3 p-3 text-center h-100 d-flex flex-column justify-content-center" style="background: rgba(64,81,137,0.07); border: 1px solid rgba(64,81,137,0.15);">
                                        <div class="text-muted small mb-1">Precio del Plan</div>
                                        <div class="fw-bold fs-5" style="color:#405189;"><span id="precio-plan">0.00</span> Bs.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="rounded-3 p-3 text-center h-100 d-flex flex-column justify-content-center" style="background: rgba(25,135,84,0.07); border: 1px solid rgba(25,135,84,0.15);">
                                        <div class="text-muted small mb-1">Monto a Pagar</div>
                                        <div class="fw-bold fs-5 text-success" id="pago-resumen-monto">0.00 Bs.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="rounded-3 p-3 text-center h-100 d-flex flex-column justify-content-center" id="saldo-box" style="background: rgba(220,53,69,0.07); border: 1px solid rgba(220,53,69,0.15);">
                                        <div class="text-muted small mb-1">Saldo Restante</div>
                                        <div class="fw-bold fs-5 text-danger" id="pago-resumen-saldo">0.00 Bs.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de pago único -->
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><i class="ri-bank-card-line me-1 text-success"></i>Tipo de Pago <span class="text-danger">*</span></label>
                                    <select name="pagos[0][tipo_pago]" id="tipo-pago-select" class="form-select form-select-lg rounded-3" required>
                                        <option value="">-- Seleccionar --</option>
                                        <option value="efectivo">💵 Efectivo</option>
                                        <option value="qr">📱 QR</option>
                                        <option value="parcial">🔀 Parcial (Efectivo + QR)</option>
                                    </select>
                                </div>

                                <div class="col-md-4" id="campo-monto-unico">
                                    <label class="form-label fw-semibold"><i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto (Bs.) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" name="pagos[0][monto]" id="pago-monto"
                                        class="form-control form-control-lg rounded-3" placeholder="0.00">
                                </div>
                                <div class="col-md-4" id="campo-monto-efectivo" style="display:none;">
                                    <label class="form-label fw-semibold"><i class="ri-money-dollar-circle-line me-1"></i>Efectivo (Bs.)</label>
                                    <input type="number" step="0.01" min="0" name="pagos[0][monto_efectivo]" id="pago-monto-efectivo"
                                        class="form-control form-control-lg rounded-3" placeholder="0.00">
                                </div>
                                <div class="col-md-4" id="campo-monto-qr" style="display:none;">
                                    <label class="form-label fw-semibold"><i class="ri-qr-code-line me-1"></i>QR (Bs.)</label>
                                    <input type="number" step="0.01" min="0" name="pagos[0][monto_qr]" id="pago-monto-qr"
                                        class="form-control form-control-lg rounded-3" placeholder="0.00">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><i class="ri-calendar-line me-1 text-success"></i>Fecha de Pago <span class="text-danger">*</span></label>
                                    <input type="date" name="pagos[0][fecha_pago]" id="pago-fecha"
                                        class="form-control form-control-lg rounded-3"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><i class="ri-percent-line me-1 text-success"></i>Descuento (Bs.)</label>
                                    <input type="number" step="0.01" min="0" name="pagos[0][descuento]" id="pago-descuento"
                                        class="form-control form-control-lg rounded-3" placeholder="0.00" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><i class="ri-user-check-line me-1 text-success"></i>Cobrado por</label>
                                    <div class="form-control form-control-lg rounded-3 bg-light d-flex align-items-center gap-2">
                                        <i class="ri-account-circle-line text-success"></i>
                                        <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.suscripciones.index') }}"
                                    class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                    <i class="ri-close-circle-line align-bottom me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm"
                                    id="btnSubmit">
                                    <i class="ri-save-3-line align-bottom me-1"></i> Crear Suscripción
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Cliente -->
    <div class="modal fade" id="registrarClienteModalCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary bg-gradient text-white py-3">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-user-add-line me-2"></i>Registrar Nuevo Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addFormCreate" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3"><i class="ri-id-card-line me-2"></i>Información
                                    Personal</h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                                <input type="text" name="carnet" class="form-control form-control-lg border-primary"
                                    required>
                                <div class="invalid-feedback">El carnet es obligatorio</div>
                                <small class="form-text text-muted feedback-carnet-create"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">NIT</label>
                                <input type="text" name="nit" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Color de identificación</label>
                                <input type="color" name="color" class="form-control form-control-lg border-primary"
                                    value="#405189">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" class="form-control form-control-lg border-primary"
                                    required>
                                <div class="invalid-feedback">El nombre es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="apellido_paterno"
                                    class="form-control form-control-lg border-primary" required>
                                <div class="invalid-feedback">El apellido paterno es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" name="apellido_materno"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3 mt-3"><i
                                        class="ri-contact-book-line me-2"></i>Información de Contacto</h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Correo</label>
                                <input type="email" name="correo" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Celular</label>
                                <input type="text" name="celular" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Teléfono</label>
                                <input type="text" name="telefono"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Dirección</label>
                                <input type="text" name="direccion"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Fecha Nacimiento</label>
                                <input type="date" name="fecha_nacimiento"
                                    class="form-control form-control-lg border-primary">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtnCreate" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i> Registrar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Hijo -->
    <div class="modal fade" id="registrarHijoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success bg-gradient text-white py-3">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-baby-line me-2"></i>Registrar Nuevo Hijo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addHijoForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="cliente_id" id="hijo_cliente_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Carnet (opcional)</label>
                                <input type="text" name="carnet" class="form-control form-control-lg border-success">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" class="form-control form-control-lg border-success"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="apellido_paterno"
                                    class="form-control form-control-lg border-success" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" name="apellido_materno"
                                    class="form-control form-control-lg border-success">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Nacimiento <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha_nacimiento"
                                    class="form-control form-control-lg border-success" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Relación <span class="text-danger">*</span></label>
                                <input type="text" name="relacion" class="form-control form-control-lg border-success"
                                    placeholder="Ej: hijo, hija" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="ri-save-3-line me-1 align-bottom"></i> Registrar Hijo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Sesión desde Calendario -->
    <div class="modal fade" id="modalAgregarSesion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger bg-gradient text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-calendar-add-line me-2"></i>Programar Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="sesion-fecha-selected">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-calendar-line me-1"></i>Fecha seleccionada</label>
                            <input type="text" id="sesion-fecha-display" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-baby-line me-1"></i>Hijo <span class="text-danger">*</span></label>
                            <select id="sesion-hijo" class="form-select">
                                <option value="">-- Seleccionar hijo --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-service-line me-1"></i>Servicio <span class="text-danger">*</span></label>
                            <select id="sesion-servicio" class="form-select">
                                <option value="">-- Seleccionar servicio --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="ri-user-line me-1"></i>Fisioterapeuta <span class="text-danger">*</span></label>
                            <select id="sesion-trabajador" class="form-select">
                                <option value="">-- Seleccionar fisioterapeuta --</option>
                                @foreach ($trabajadores as $trabajador)
                                    <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class="ri-time-line me-1"></i>Hora Inicio <span class="text-danger">*</span></label>
                            <select id="sesion-hora-inicio" class="form-select">
                                <option value="">-- Hora --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class="ri-time-line me-1"></i>Hora Fin</label>
                            <input type="text" id="sesion-hora-fin" class="form-control" readonly placeholder="Automático">
                        </div>
                        <div id="disponibilidad-msg" class="col-12" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnGuardarSesion" class="btn btn-danger">
                        <i class="ri-save-3-line me-1"></i>Agregar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        /* Gradientes y sombras */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
        }

        /* Step Progress */
        .step-item .avatar-md {
            transition: all 0.3s ease;
        }

        .step-item.active .avatar-md {
            background: #405189 !important;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(64, 81, 137, 0.4);
        }

        /* Cards mejoradas */
        .card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Buscador de cliente personalizado */
        .cliente-search-box {
            border: 2px solid #405189;
            border-radius: 12px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .cliente-search-box:focus-within {
            box-shadow: 0 0 0 4px rgba(64, 81, 137, 0.15);
            border-color: #5c6ac4;
        }

        .cliente-search-box input {
            font-size: 1rem;
        }

        .cliente-search-box input:focus {
            outline: none;
            box-shadow: none;
        }

        #cliente-search-results.cliente-search-dropdown {
            background-color: #ffffff !important;
            border-radius: 12px !important;
            border: 1.5px solid #c7d0e8 !important;
            box-shadow: 0 12px 40px rgba(64, 81, 137, 0.25), 0 4px 12px rgba(0,0,0,0.12) !important;
            overflow: hidden !important;
            max-height: 340px !important;
            overflow-y: auto !important;
        }

        #cliente-search-results.cliente-search-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        #cliente-search-results.cliente-search-dropdown::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        #cliente-search-results.cliente-search-dropdown::-webkit-scrollbar-thumb {
            background: #c7d0e8;
            border-radius: 6px;
        }
        #cliente-search-results.cliente-search-dropdown::-webkit-scrollbar-thumb:hover {
            background: #405189;
        }

        #cliente-search-results .cliente-search-results-header {
            padding: 8px 16px 6px !important;
            background-color: #f0f3fb !important;
            border-bottom: 1px solid #dce2f0 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            color: #5a6a8a !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #cliente-search-results .cliente-result-item {
            padding: 12px 16px !important;
            border-bottom: 1px solid #eef1f7 !important;
            cursor: pointer !important;
            transition: background-color 0.15s ease !important;
            background-color: #ffffff !important;
            color: #212529 !important;
        }

        #cliente-search-results .cliente-result-item:last-child {
            border-bottom: none !important;
        }

        #cliente-search-results .cliente-result-item:hover {
            background-color: #eef1fa !important;
        }

        #cliente-search-results .cliente-result-item:hover .result-arrow {
            transform: translateX(4px);
            color: #405189 !important;
        }

        #cliente-search-results .fw-semibold {
            color: #1a2035 !important;
        }

        .result-arrow {
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .cliente-card-selected {
            background: linear-gradient(135deg, rgba(64, 81, 137, 0.06) 0%, rgba(92, 106, 196, 0.06) 100%);
            border: 2px solid rgba(64, 81, 137, 0.2);
        }

        /* Panel de sesiones programadas */
        .sesion-detalle-card {
            border-left: 4px solid #405189 !important;
            transition: all 0.2s ease;
        }

        .sesion-detalle-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(64, 81, 137, 0.15) !important;
        }

        .btn-icon-sm {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.85rem;
        }

        /* Tabla de pagos */
        #pagos-table th {
            background: #f8f9fa !important;
            font-weight: 600 !important;
            color: #495057 !important;
            border: none !important;
            padding: 15px 10px !important;
        }

        #pagos-table td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
        }

        #pagos-table .form-select,
        #pagos-table .form-control {
            border-radius: 8px !important;
            border: 1px solid #dee2e6 !important;
            font-size: 0.9rem !important;
        }

        #pagos-table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        /* Calendario de sesiones */
        #calendar-sesiones {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        #calendar-sesiones .fc-timegrid-slot {
            height: 2.2em !important;
        }

        #calendar-sesiones .fc-timegrid-slot-label-cushion,
        #calendar-sesiones .fc-timegrid-axis-cushion {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        #calendar-sesiones .fc-now-indicator-line {
            border-color: #405189 !important;
        }

        #calendar-sesiones .fc-button-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 6px 14px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }

        #calendar-sesiones .fc-button-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(64, 81, 137, 0.3) !important;
        }

        #calendar-sesiones .fc-button-primary:not(:disabled).fc-button-active {
            background: linear-gradient(135deg, #2e3b6e 0%, #4757a8 100%) !important;
        }

        #calendar-sesiones .fc-toolbar-title {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #405189 !important;
        }

        #calendar-sesiones .fc-daygrid-day-number {
            font-weight: 600;
            color: #405189;
        }

        #calendar-sesiones .fc-col-header-cell-cushion {
            font-weight: 700;
            color: #405189;
            padding: 10px;
        }

        #calendar-sesiones .fc-daygrid-day:hover {
            background: rgba(64, 81, 137, 0.05) !important;
            cursor: pointer;
        }

        #calendar-sesiones .fc-event {
            cursor: pointer;
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            border: none !important;
            transition: all 0.2s ease !important;
        }

        #calendar-sesiones .fc-event:hover {
            transform: scale(1.02) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2) !important;
        }

        #calendar-sesiones .fc-day-past {
            background: #f8f9fa !important;
            opacity: 0.7;
        }

        /* Botones */
        .btn-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(64, 81, 137, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(64, 81, 137, 0.4);
        }

        /* Botón eliminar en tabla de pagos */
        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px;
            }

            .step-item small {
                font-size: 10px;
            }

            .avatar-md {
                width: 35px !important;
                height: 35px !important;
            }
        }

        /* Tabla de pagos - Campos condicionales */
        #pagos-table .campo-monto-unico,
        #pagos-table .campo-monto-efectivo,
        #pagos-table .campo-monto-qr {
            transition: all 0.3s ease;
        }

        #pagos-table tfoot th {
            background: #f8f9fa !important;
            font-weight: 600 !important;
            border-top: 2px solid #dee2e6 !important;
        }

        #pagos-table tfoot td {
            font-size: 1rem !important;
            border-top: 2px solid #dee2e6 !important;
        }

        /* Badge de total pagado */
        #total-pagado-badge {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        $(document).ready(function() {
            // CSRF token
            var token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            // ========== MOSTRAR TOAST DE SESIÓN ==========
            @if (session('success'))
                showToast('success', '{{ session('success') }}');
            @endif
            @if (session('error'))
                showToast('error', '{{ session('error') }}');
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('error', '{{ $error }}');
                @endforeach
            @endif

            // Generar opciones de hora cada 30 minutos (08:00 - 20:00)
            let horasOptions = '';
            for (let h = 8; h <= 20; h++) {
                for (let m = 0; m < 60; m += 30) {
                    let hora = (h < 10 ? '0' + h : h) + ':' + (m === 0 ? '00' : '30');
                    horasOptions += `<option value="${hora}">${hora}</option>`;
                }
            }

            // Variables globales
            var hijosPorCliente = [];
            var serviciosPorPlan = [];
            var cantidadSesiones = 0;
            var clienteActualId = null;
            var precioPlan = 0; // Nuevo

            // ========== BUSCADOR PERSONALIZADO DE CLIENTE ==========
            var clienteSearchTimeout = null;

            function seleccionarCliente(id, nombre, carnet, correo, hijoId) {
                var iniciales = nombre.split(' ').slice(0, 2).map(function(p) { return p.charAt(0); }).join('').toUpperCase();
                $('#cliente_id_hidden').val(id);
                $('#cliente-search-input').val(nombre);
                $('#btnLimpiarCliente').show();
                $('#cliente-card-avatar').text(iniciales);
                $('#cliente-card-nombre').text(nombre);
                $('#cliente-card-carnet').text(carnet || 'Sin CI');
                if (correo) {
                    $('#cliente-card-correo').text(correo);
                    $('#cliente-card-correo-wrap').css('display', 'inline-flex');
                } else {
                    $('#cliente-card-correo-wrap').hide();
                }
                $('#cliente-seleccionado-card').show();
                $('#cliente-search-results').hide();
                clienteActualId = id;
                cargarHijos(id, hijoId || null);
            }

            $('#cliente-search-input').on('input', function() {
                var q = $(this).val().trim();
                clearTimeout(clienteSearchTimeout);
                if (q.length < 2) {
                    $('#cliente-search-results').hide();
                    return;
                }
                clienteSearchTimeout = setTimeout(function() {
                    $('#cliente-search-spinner').show();
                    $.ajax({
                        url: '{{ route('admin.suscripciones.buscarCliente') }}',
                        type: 'POST',
                        data: { q: q },
                        success: function(data) {
                            $('#cliente-search-spinner').hide();
                            if (!data.length) {
                                $('#cliente-search-results').html(
                                    '<div class="p-3 text-muted small text-center"><i class="ri-search-line me-2 fs-5 opacity-50"></i>No se encontraron clientes para "' + q + '"</div>'
                                ).show();
                                return;
                            }
                            var header = '<div class="cliente-search-results-header">' +
                                '<i class="ri-user-search-line me-1"></i>' + data.length + ' resultado' + (data.length !== 1 ? 's' : '') + ' encontrado' + (data.length !== 1 ? 's' : '') +
                                '</div>';
                            var html = '';
                            data.forEach(function(c) {
                                var p = c.persona || {};
                                var nombre = ((p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p.apellido_materno || '')).trim();
                                var carnet = p.carnet || 'Sin CI';
                                var correo = p.correo || '';
                                var celular = p.celular || '';
                                var iniciales = ((p.nombres || ' ').charAt(0) + (p.apellido_paterno || ' ').charAt(0)).toUpperCase();
                                var badgeColor = '#0d9488';
                                html += '<div class="cliente-result-item d-flex align-items-center gap-3"' +
                                    ' data-id="' + c.id + '" data-nombre="' + $('<div>').text(nombre).html() + '" data-carnet="' + carnet + '" data-correo="' + correo + '">' +
                                    '<div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:44px;height:44px;font-size:15px;background:linear-gradient(135deg,#405189 0%,#5c6ac4 100%);">' + iniciales + '</div>' +
                                    '<div class="flex-grow-1 min-w-0">' +
                                    '<div class="fw-semibold text-dark" style="font-size:14px;">' + $('<div>').text(nombre).html() + '</div>' +
                                    '<div class="d-flex flex-wrap gap-1 mt-1">' +
                                    '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:600;padding:2px 9px;border-radius:20px;background:#e8ecf8;color:#405189;"><i class="ri-id-card-line me-1"></i>' + carnet + '</span>' +
                                    (correo ? '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:500;padding:2px 9px;border-radius:20px;background:#f0f3fb;color:#5a6a8a;"><i class="ri-mail-line me-1"></i>' + correo + '</span>' : '') +
                                    (celular ? '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:500;padding:2px 9px;border-radius:20px;background:#f0fdf4;color:#16a34a;"><i class="ri-phone-line me-1"></i>' + celular + '</span>' : '') +
                                    '</div></div>' +
                                    '<span class="badge rounded-pill flex-shrink-0 px-3 py-2" style="font-size:11px;font-weight:500;background:' + badgeColor + '17;color:' + badgeColor + ';border:1px solid ' + badgeColor + '33;">' +
                                    'Seleccionar <i class="ri-arrow-right-s-line ms-1" style="font-size:14px;"></i></span>' +
                                    '</div>';
                            });
                            $('#cliente-search-results').html(header + html).show();
                        },
                        error: function() {
                            $('#cliente-search-spinner').hide();
                            $('#cliente-search-results').html('<div class="p-3 text-danger small text-center"><i class="ri-error-warning-line me-2"></i>Error al buscar. Intenta de nuevo.</div>').show();
                        }
                    });
                }, 300);
            });

            $(document).on('click', '.cliente-result-item', function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var carnet = $(this).data('carnet');
                var correo = $(this).data('correo');
                seleccionarCliente(id, nombre, carnet, correo);
            });

            $('#btnLimpiarCliente').on('click', function() {
                $('#cliente_id_hidden').val('');
                $('#cliente-search-input').val('').focus();
                $('#cliente-seleccionado-card').hide();
                $(this).hide();
                $('#cliente-search-results').hide();
                clienteActualId = null;
                hijosPorCliente = [];
                $('#hijo-selection-container').hide();
                sesionesData = [];
                filtroTrabajadorId = '';
                actualizarInputsSesiones();
                if (calendarSesiones) { calendarSesiones.destroy(); calendarSesiones = null; }
                $('#sesiones-card').hide();
                $('#pagos-card').hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#cliente-search-input, #cliente-search-results').length) {
                    $('#cliente-search-results').hide();
                }
            });

            // Botón registrar cliente
            $('#btnRegistrarCliente').click(function() {
                $('#registrarClienteModalCreate').modal('show');
            });

            // Verificación de carnet en modal registro cliente
            $('#addFormCreate input[name="carnet"]').on('input', function() {
                let carnet = $(this).val().trim();
                let feedback = $('.feedback-carnet-create');
                let submitBtn = $('.addBtnCreate');
                if (carnet.length === 0) {
                    feedback.text('');
                    submitBtn.prop('disabled', true);
                    return;
                }
                if (carnet.length < 2) {
                    feedback.addClass('text-danger').text('El carnet debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    return;
                }
                clearTimeout(window.carnetDebounce);
                window.carnetDebounce = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.clientes.verificarCarnet') }}",
                        type: "POST",
                        data: {
                            _token: token,
                            carnet: carnet
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este carnet ya está registrado'
                                );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Carnet disponible'
                                );
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar'
                            );
                            submitBtn.prop('disabled', true);
                        }
                    });
                }, 500);
            });

            // Enviar formulario registro cliente
            $('#addFormCreate').submit(function(e) {
                e.preventDefault();
                if ($('.addBtnCreate').prop('disabled')) return;
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }
                let submitBtn = $('.addBtnCreate');
                let originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');
                $.ajax({
                    url: "{{ route('admin.clientes.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            $('#registrarClienteModalCreate').modal('hide');
                            showToast('success', res.msg);
                            var p = res.cliente.persona;
                            var nombre = (p.nombres + ' ' + p.apellido_paterno).trim();
                            seleccionarCliente(res.cliente.id, nombre, p.carnet || 'Sin CI', p.correo || '');
                        } else {
                            showToast('error', res.msg || 'Error al registrar');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Cargar hijos del cliente
            function cargarHijos(clienteId, selectedHijoId = null) {
                $.ajax({
                    url: '{{ route('admin.suscripciones.hijosPorCliente') }}',
                    type: 'POST',
                    data: {
                        cliente_id: clienteId
                    },
                    success: function(response) {
                        hijosPorCliente = response;
                        let $select = $('#hijo_default');
                        $select.empty().append('<option value="">-- Seleccionar hijo --</option>');
                        if (response.length > 0) {
                            $.each(response, function(i, hijo) {
                                $select.append('<option value="' + hijo.id + '">' + hijo.text +
                                    '</option>');
                            });
                            $('#hijo-selection-container').show();
                            if (selectedHijoId) {
                                $select.val(selectedHijoId).trigger('change');
                            }
                            if (cantidadSesiones > 0) {
                                initCalendarioSesiones();
                            }
                        } else {
                            $select.append('<option value="">No hay hijos registrados</option>');
                            $('#hijo-selection-container').show();
                            if (cantidadSesiones > 0) {
                                showToast('warning', 'El cliente no tiene hijos registrados. Por favor, registre un hijo primero.');
                            }
                        }
                    },
                    error: function() {
                        hijosPorCliente = [];
                        showToast('error', 'No se pudieron cargar los hijos');
                    }
                });
            }

            // Cache de planes cargados para re-renderizar al toggle de tarifa
            var planesCache = [];

            function tarifaActual() {
                return $('#tarifa_especial_switch').is(':checked') ? 'especial' : 'normal';
            }

            function precioPlanSegunTarifa(plan) {
                if (tarifaActual() === 'especial' && plan.precio_especial !== null && plan.precio_especial !== undefined) {
                    return parseFloat(plan.precio_especial);
                }
                return parseFloat(plan.precio);
            }

            function renderPlanes(seleccionadoId) {
                var $planeSelect = $('#plane_select');
                if (!planesCache.length) {
                    $planeSelect.html('<option value="">-- Primero seleccione un paquete --</option>')
                        .prop('disabled', true);
                    return;
                }
                var tarifa = tarifaActual();
                var options = '<option value="">-- Seleccione un plan --</option>';
                $.each(planesCache, function(i, plan) {
                    var precio = precioPlanSegunTarifa(plan);
                    var sinEspecial = (tarifa === 'especial' && (plan.precio_especial === null || plan.precio_especial === undefined));
                    var sufijo = sinEspecial ? ' (sin precio especial)' : '';
                    var disabled = sinEspecial ? ' disabled' : '';
                    options +=
                        `<option value="${plan.id}" data-sesiones="${plan.cantidad_sesiones}" data-precio="${precio}"${disabled}>` +
                        `${plan.nombre_plan} - ${plan.cantidad_sesiones} sesiones - ${precio.toFixed(2)} Bs.${sufijo}` +
                        `</option>`;
                });
                $planeSelect.prop('disabled', false).html(options);
                if (seleccionadoId) {
                    $planeSelect.val(String(seleccionadoId)).trigger('change');
                }
            }

            // Evento cambio de paquete
            $('#paquete_select').on('change', function() {
                let paqueteId = $(this).val();
                let $planeSelect = $('#plane_select');
                $planeSelect.html('<option value="">Cargando planes...</option>').prop('disabled', true);
                $('#sesiones-card').hide();
                sesionesData = [];
                filtroTrabajadorId = '';
                actualizarInputsSesiones();
                if (calendarSesiones) { calendarSesiones.destroy(); calendarSesiones = null; }
                // Ocultar pagos al cambiar paquete
                $('#pagos-card').hide();
                planesCache = [];
                if (!paqueteId) {
                    $planeSelect.html('<option value="">-- Primero seleccione un paquete --</option>');
                    return;
                }
                $.ajax({
                    url: '{{ route('admin.suscripciones.planesPorPaquete') }}',
                    type: 'POST',
                    data: {
                        paquete_id: paqueteId
                    },
                    success: function(planes) {
                        if (!planes.length) {
                            $planeSelect.html('<option value="">No hay planes en este paquete</option>')
                                .prop('disabled', true);
                            return;
                        }
                        planesCache = planes;
                        renderPlanes();
                    },
                    error: function() {
                        $planeSelect.html('<option value="">Error al cargar planes</option>');
                    }
                });
            });

            // Cargar servicios del plan
            function cargarServicios(planId) {
                return $.ajax({
                    url: '{{ route('admin.suscripciones.serviciosPorPlan') }}',
                    type: 'POST',
                    data: {
                        plan_id: planId
                    },
                    dataType: 'json'
                });
            }

            // Evento cambio de plan
            $('#plane_select').on('change', function() {
                let planId = $(this).val();
                let selectedOption = $(this).find(':selected');
                cantidadSesiones = selectedOption.data('sesiones') || 0;
                precioPlan = parseFloat(selectedOption.data('precio')) || 0;
                $('#total-sesiones-count').text('0/' + cantidadSesiones);
                $('#precio-plan').text(precioPlan.toFixed(2));

                // Limpiar pagos y sesiones anteriores
                $('#pago-monto, #pago-monto-efectivo, #pago-monto-qr').val('');
                $('#pago-descuento').val('0');
                $('#tipo-pago-select').val('');
                $('#campo-monto-efectivo, #campo-monto-qr').hide();
                $('#campo-monto-unico').show();
                actualizarTotalPagado();
                sesionesData = [];
                filtroTrabajadorId = '';
                actualizarInputsSesiones();
                if (calendarSesiones) { calendarSesiones.destroy(); calendarSesiones = null; }

                if (!planId || cantidadSesiones === 0) {
                    $('#sesiones-card').hide();
                    $('#pagos-card').hide();
                    return;
                }

                $('#sesiones-card').show();
                $('#pagos-card').show(); // Mostrar card de pagos solo si hay plan seleccionado

                cargarServicios(planId).then(function(servicios) {
                    serviciosPorPlan = servicios;
                    if (hijosPorCliente.length === 0) {
                        showToast('warning', 'Primero debe seleccionar un cliente que tenga hijos registrados.');
                        return;
                    }
                    initCalendarioSesiones();
                }).catch(function() {
                    showToast('error', 'Error al cargar servicios del plan.');
                });
            });

            // ========== CALENDARIO DE SESIONES ==========
            var calendarSesiones = null;
            var sesionesData = [];
            var filtroTrabajadorId = '';
            var fuenteOcupados = null;

            function initCalendarioSesiones() {
                sesionesData = [];
                actualizarInputsSesiones();
                actualizarProgresoSesiones();

                if (calendarSesiones) {
                    calendarSesiones.destroy();
                    calendarSesiones = null;
                }

                var calendarEl = document.getElementById('calendar-sesiones');
                var today = new Date().toISOString().slice(0, 10);

                calendarSesiones = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    height: 650,
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    slotDuration: '00:30:00',
                    slotLabelInterval: '01:00:00',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay'
                    },
                    buttonText: { today: 'Hoy', week: 'Semana', day: 'Día' },
                    allDaySlot: false,
                    nowIndicator: true,
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    dayHeaderFormat: { weekday: 'short', day: 'numeric', month: 'short' },
                    eventSources: [
                        // Fuente 1: sesiones ya guardadas en DB (ocupadas) - carga por AJAX
                        {
                            id: 'ocupados',
                            url: '{{ route('admin.suscripciones.calendarioOcupados') }}',
                            method: 'GET',
                            extraParams: function() {
                                return { trabajador_id: filtroTrabajadorId };
                            },
                            failure: function() {
                                showToast('error', 'Error al cargar sesiones existentes del calendario');
                            }
                        },
                        // Fuente 2: sesiones en memoria (recién añadidas en este formulario)
                        { id: 'nuevas', events: [] }
                    ],
                    dateClick: function(info) {
                        if (info.allDay) {
                            showToast('info', 'Haz clic en un bloque de hora específico para agregar una sesión');
                            return;
                        }
                        if (sesionesData.length >= cantidadSesiones) {
                            showToast('warning', 'Ya programaste todas las ' + cantidadSesiones + ' sesiones requeridas');
                            return;
                        }
                        var dateStr = info.dateStr;
                        var fecha = dateStr.substring(0, 10);
                        var hora = dateStr.length >= 16 ? dateStr.substring(11, 16) : '';
                        // Verificar si el slot clickeado ya está ocupado visualmente
                        var clickedMs = info.date.getTime();
                        var conflicto = false;
                        calendarSesiones.getEvents().forEach(function(ev) {
                            if (ev.extendedProps.tipo === 'ocupado') {
                                var evTrabajadorId = ev.extendedProps.trabajador_id;
                                // Solo bloquear si el filtro activo coincide con el trabajador del evento (o no hay filtro)
                                if (!filtroTrabajadorId || String(evTrabajadorId) === String(filtroTrabajadorId)) {
                                    if (ev.start && ev.end && clickedMs >= ev.start.getTime() && clickedMs < ev.end.getTime()) {
                                        conflicto = true;
                                    }
                                }
                            }
                        });
                        if (conflicto) {
                            showToast('error', 'Este horario ya está ocupado. Selecciona otro slot disponible.');
                            return;
                        }
                        abrirModalSesion(fecha, hora);
                    },
                    eventClick: function(info) {
                        var tipo = info.event.extendedProps.tipo;
                        if (tipo === 'ocupado') {
                            showToast('warning', 'Horario ocupado: ' + info.event.title);
                            return;
                        }
                        var eventId = info.event.id;
                        var title = info.event.title;
                        if (confirm('¿Deseas eliminar la sesión "' + title + '"?')) {
                            eliminarSesion(eventId);
                        }
                    },
                    eventDidMount: function(info) {
                        if (info.event.extendedProps.tipo === 'ocupado') {
                            info.el.setAttribute('title', 'Ocupado: ' + info.event.title);
                            info.el.style.cursor = 'not-allowed';
                        }
                    }
                });

                calendarSesiones.render();

                // Sincronizar filtro de trabajador con el select del DOM
                $('#filtro-fisioterapeuta-cal').on('change', function() {
                    filtroTrabajadorId = $(this).val();
                    // Recargar solo la fuente de ocupados
                    var source = calendarSesiones.getEventSourceById('ocupados');
                    if (source) source.refetch();
                });
            }

            function abrirModalSesion(fecha, horaPreseleccionada) {
                $('#sesion-fecha-selected').val(fecha);
                var parts = fecha.split('-');
                $('#sesion-fecha-display').val(parts[2] + '/' + parts[1] + '/' + parts[0]);

                // Poblar hijos
                var hijoDefault = $('#hijo_default').val();
                var $hijoSelect = $('#sesion-hijo');
                $hijoSelect.html('<option value="">-- Seleccionar hijo --</option>');
                hijosPorCliente.forEach(function(h) {
                    $hijoSelect.append('<option value="' + h.id + '"' + (h.id == hijoDefault ? ' selected' : '') + '>' + h.text + '</option>');
                });

                // Poblar servicios
                var $servicioSelect = $('#sesion-servicio');
                $servicioSelect.html('<option value="">-- Seleccionar servicio --</option>');
                serviciosPorPlan.forEach(function(s, index) {
                    $servicioSelect.append('<option value="' + s.id + '"' + (index === 0 ? ' selected' : '') + '>' + s.servicio.nombre + ' (' + s.cantidad_sesiones + ' sesiones)</option>');
                });

                // Poblar horas
                var $horaSelect = $('#sesion-hora-inicio');
                $horaSelect.html('<option value="">-- Hora --</option>' + horasOptions);

                // Pre-seleccionar hora del slot clickeado
                if (horaPreseleccionada) {
                    $horaSelect.val(horaPreseleccionada);
                    var partes = horaPreseleccionada.split(':');
                    var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : String(parseInt(partes[0]) + 1)) + ':' + partes[1];
                    $('#sesion-hora-fin').val(horaFin);
                } else {
                    $('#sesion-hora-fin').val('');
                }

                // Pre-seleccionar fisioterapeuta del filtro activo
                if (filtroTrabajadorId) {
                    $('#sesion-trabajador').val(filtroTrabajadorId);
                } else {
                    $('#sesion-trabajador').val('');
                }

                $('#disponibilidad-msg').hide().html('');
                $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Agregar Sesión');

                var modal = new bootstrap.Modal(document.getElementById('modalAgregarSesion'));
                modal.show();
            }

            function eliminarSesion(eventId) {
                var idx = sesionesData.findIndex(function(s) { return String(s.id) === String(eventId); });
                if (idx === -1) return;
                sesionesData.splice(idx, 1);
                var event = calendarSesiones.getEventById(String(eventId));
                if (event) event.remove();
                // Renumerar eventos de sesiones nuevas
                sesionesData.forEach(function(s, i) {
                    var ev = calendarSesiones.getEventById(String(s.id));
                    if (ev) ev.setProp('title', '#' + (i + 1) + ' ' + s._titulo);
                });
                actualizarInputsSesiones();
                actualizarProgresoSesiones();
                actualizarListaSesiones();
                showToast('info', 'Sesión eliminada del calendario');
            }

            function actualizarInputsSesiones() {
                var html = '';
                sesionesData.forEach(function(s, i) {
                    html += '<input type="hidden" name="sesiones[' + i + '][hijo_id]" value="' + s.hijo_id + '">';
                    html += '<input type="hidden" name="sesiones[' + i + '][planes_servicio_id]" value="' + s.planes_servicio_id + '">';
                    html += '<input type="hidden" name="sesiones[' + i + '][trabajadore_id]" value="' + s.trabajadore_id + '">';
                    html += '<input type="hidden" name="sesiones[' + i + '][fecha]" value="' + s.fecha + '">';
                    html += '<input type="hidden" name="sesiones[' + i + '][hora_inicio]" value="' + s.hora_inicio + '">';
                    html += '<input type="hidden" name="sesiones[' + i + '][hora_fin]" value="' + s.hora_fin + '">';
                });
                $('#sesiones-inputs').html(html);
            }

            function actualizarListaSesiones() {
                if (sesionesData.length === 0) {
                    $('#panel-detalle-sesiones').hide();
                    return;
                }
                var html = '';
                sesionesData.forEach(function(s, i) {
                    var hijoText = $('#sesion-hijo option[value="' + s.hijo_id + '"]').text() || '-';
                    var trabajadorText = $('#sesion-trabajador option[value="' + s.trabajadore_id + '"]').text() || '-';
                    var servicioText = $('#sesion-servicio option[value="' + s.planes_servicio_id + '"]').text().split('(')[0].trim() || '-';
                    var fechaDisplay = s.fecha.split('-').reverse().join('/');
                    html += '<div class="col-md-6 col-xl-4">' +
                        '<div class="card border-0 shadow-sm rounded-3 h-100 sesion-detalle-card">' +
                        '<div class="card-body p-3">' +
                        '<div class="d-flex align-items-start justify-content-between mb-2">' +
                        '<span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:rgba(64,81,137,0.12);color:#405189;font-size:11px;">Sesión #' + (i + 1) + '</span>' +
                        '<div class="d-flex gap-1">' +
                        '<button type="button" class="btn-icon-sm btn btn-light edit-sesion-btn" data-id="' + s.id + '" title="Editar sesión"><i class="ri-edit-line" style="color:#405189;font-size:13px;"></i></button>' +
                        '<button type="button" class="btn-icon-sm btn btn-light eliminar-sesion-btn" data-id="' + s.id + '" title="Eliminar sesión"><i class="ri-delete-bin-line text-danger" style="font-size:13px;"></i></button>' +
                        '</div></div>' +
                        '<div class="mb-1" style="font-size:13px;"><i class="ri-calendar-event-line me-1" style="color:#405189;"></i><strong>' + fechaDisplay + '</strong><span class="text-muted ms-1">' + s.hora_inicio + ' – ' + s.hora_fin + '</span></div>' +
                        '<div class="text-muted text-truncate" style="font-size:12px;"><i class="ri-user-settings-line me-1 text-success"></i>' + trabajadorText + '</div>' +
                        '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-baby-line me-1 text-info"></i>' + hijoText + '</div>' +
                        '<div class="text-muted text-truncate mt-1" style="font-size:12px;"><i class="ri-service-line me-1" style="color:#f4a200;"></i>' + servicioText + '</div>' +
                        '</div></div></div>';
                });
                $('#lista-sesiones-detalle').html(html);
                $('#detalle-count').text(sesionesData.length);
                $('#panel-detalle-sesiones').show();
            }

            // Eliminar desde el panel de detalle
            $(document).on('click', '.eliminar-sesion-btn', function() {
                var id = $(this).data('id');
                if (confirm('¿Eliminar esta sesión?')) {
                    eliminarSesion(id);
                }
            });

            // Editar desde el panel de detalle
            $(document).on('click', '.edit-sesion-btn', function() {
                var id = $(this).data('id');
                var idx = sesionesData.findIndex(function(s) { return String(s.id) === String(id); });
                if (idx === -1) return;
                var s = sesionesData[idx];
                // Eliminar la sesión actual y abrir modal con sus datos pre-rellenados
                eliminarSesion(id);
                abrirModalSesion(s.fecha, s.hora_inicio);
                // Pre-seleccionar los campos del modal
                setTimeout(function() {
                    $('#sesion-hijo').val(s.hijo_id);
                    $('#sesion-servicio').val(s.planes_servicio_id);
                    $('#sesion-trabajador').val(s.trabajadore_id);
                    $('#sesion-hora-inicio').val(s.hora_inicio);
                    $('#sesion-hora-fin').val(s.hora_fin);
                }, 100);
            });

            function actualizarProgresoSesiones() {
                var programadas = sesionesData.length;
                var requeridas = cantidadSesiones;
                $('#sesiones-programadas').text(programadas);
                $('#sesiones-requeridas').text(requeridas);
                $('#info-total-sesiones').text(requeridas);
                var porcentaje = requeridas > 0 ? Math.min(100, (programadas / requeridas) * 100) : 0;
                var $bar = $('#progreso-sesiones');
                $bar.css('width', porcentaje + '%');
                if (programadas >= requeridas && requeridas > 0) {
                    $bar.removeClass('bg-danger bg-warning').addClass('bg-success');
                    $('#badge-sesiones-ok').removeClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-danger bg-opacity-10 text-danger').addClass('bg-success bg-opacity-10 text-success');
                } else if (porcentaje > 50) {
                    $bar.removeClass('bg-danger bg-success').addClass('bg-warning');
                    $('#badge-sesiones-ok').addClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-success bg-opacity-10 text-success').addClass('bg-danger bg-opacity-10 text-danger');
                } else {
                    $bar.removeClass('bg-warning bg-success').addClass('bg-danger');
                    $('#badge-sesiones-ok').addClass('d-none');
                    $('#total-sesiones-badge').removeClass('bg-success bg-opacity-10 text-success').addClass('bg-danger bg-opacity-10 text-danger');
                }
                $('#total-sesiones-count').text(programadas + '/' + requeridas);
            }

            // Calcular hora fin automáticamente en el modal
            $(document).on('change', '#sesion-hora-inicio', function() {
                var h = $(this).val();
                if (h) {
                    var partes = h.split(':');
                    var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : (parseInt(partes[0]) + 1)) + ':' + partes[1];
                    $('#sesion-hora-fin').val(horaFin);
                } else {
                    $('#sesion-hora-fin').val('');
                }
                $('#disponibilidad-msg').hide();
            });

            // Guardar sesión desde el modal
            $('#btnGuardarSesion').on('click', function() {
                var fecha = $('#sesion-fecha-selected').val();
                var hijoId = $('#sesion-hijo').val();
                var servicioId = $('#sesion-servicio').val();
                var trabajadorId = $('#sesion-trabajador').val();
                var horaInicio = $('#sesion-hora-inicio').val();

                if (!fecha || !hijoId || !servicioId || !trabajadorId || !horaInicio) {
                    showToast('warning', 'Completa todos los campos requeridos');
                    return;
                }

                var partes = horaInicio.split(':');
                var horaFin = (parseInt(partes[0]) + 1 < 10 ? '0' + (parseInt(partes[0]) + 1) : String(parseInt(partes[0]) + 1)) + ':' + partes[1];
                var hijoText = $('#sesion-hijo option:selected').text();
                var trabajadorText = $('#sesion-trabajador option:selected').text();
                var titulo = hijoText.split(' ')[0] + ' - ' + trabajadorText.split(' ')[0];

                $('#btnGuardarSesion').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Verificando disponibilidad...');
                $('#disponibilidad-msg').hide();

                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: { _token: token, fecha: fecha, trabajador_id: trabajadorId, sesion_id: null },
                    success: function(response) {
                        var horasNoDisponibles = response.horas_no_disponibles || [];
                        if (horasNoDisponibles.includes(horaInicio)) {
                            // BLOQUEO DEFINITIVO: no se permite guardar
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-danger mb-0 py-2 small d-flex align-items-start gap-2">' +
                                '<i class="ri-forbid-line fs-5 flex-shrink-0"></i>' +
                                '<div><strong>Horario no disponible</strong><br>' +
                                'El fisioterapeuta <strong>' + trabajadorText + '</strong> ya tiene una sesión a las <strong>' + horaInicio + '</strong> el <strong>' + fecha.split('-').reverse().join('/') + '</strong>.' +
                                ' Por favor elige otra hora u otro fisioterapeuta.' +
                                '</div></div>'
                            ).show();
                            $('#sesion-hora-inicio').val('');
                            $('#sesion-hora-fin').val('');
                            $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Agregar Sesión');
                            return;
                        }
                        // Verificar también conflicto con sesiones ya añadidas en este formulario
                        var conflictoLocal = sesionesData.some(function(s) {
                            if (String(s.trabajadore_id) !== String(trabajadorId) || s.fecha !== fecha) return false;
                            var ini1 = horaInicio, fin1 = horaFin;
                            var ini2 = s.hora_inicio, fin2 = s.hora_fin;
                            return ini1 < fin2 && ini2 < fin1;
                        });
                        if (conflictoLocal) {
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-danger mb-0 py-2 small d-flex align-items-start gap-2">' +
                                '<i class="ri-forbid-line fs-5 flex-shrink-0"></i>' +
                                '<div><strong>Conflicto con sesión ya programada</strong><br>' +
                                'Ya programaste una sesión para <strong>' + trabajadorText + '</strong> a las <strong>' + horaInicio + '</strong> ese día.' +
                                '</div></div>'
                            ).show();
                            $('#sesion-hora-inicio').val('');
                            $('#sesion-hora-fin').val('');
                            $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Agregar Sesión');
                            return;
                        }
                        agregarSesionAlCalendario(fecha, hijoId, servicioId, trabajadorId, horaInicio, horaFin, titulo);
                    },
                    error: function() {
                        // Si el AJAX falla, bloqueamos igualmente (no podemos verificar)
                        $('#disponibilidad-msg').html(
                            '<div class="alert alert-warning mb-0 py-2 small"><i class="ri-alert-line me-1"></i>No se pudo verificar disponibilidad. Intenta de nuevo.</div>'
                        ).show();
                        $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Agregar Sesión');
                    }
                });
            });

            // Cuando cambia el fisioterapeuta en el modal → verificar disponibilidad en tiempo real
            $(document).on('change', '#sesion-trabajador', function() {
                var trabajadorId = $(this).val();
                var fecha = $('#sesion-fecha-selected').val();
                var horaInicio = $('#sesion-hora-inicio').val();
                $('#disponibilidad-msg').hide().html('');
                if (!trabajadorId || !fecha || !horaInicio) return;

                $('#disponibilidad-msg').html(
                    '<div class="text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Verificando disponibilidad...</div>'
                ).show();

                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: { _token: token, fecha: fecha, trabajador_id: trabajadorId, sesion_id: null },
                    success: function(response) {
                        var horasNoDisponibles = response.horas_no_disponibles || [];
                        if (horasNoDisponibles.includes(horaInicio)) {
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-danger mb-0 py-2 small"><i class="ri-forbid-line me-1"></i>Este fisioterapeuta <strong>no está disponible</strong> a las ' + horaInicio + ' ese día.</div>'
                            ).show();
                            $('#btnGuardarSesion').prop('disabled', true);
                        } else {
                            $('#disponibilidad-msg').html(
                                '<div class="alert alert-success mb-0 py-2 small"><i class="ri-checkbox-circle-line me-1"></i>Horario <strong>disponible</strong> para este fisioterapeuta.</div>'
                            ).show();
                            $('#btnGuardarSesion').prop('disabled', false);
                        }
                    },
                    error: function() {
                        $('#disponibilidad-msg').hide();
                    }
                });
            });

            function agregarSesionAlCalendario(fecha, hijoId, servicioId, trabajadorId, horaInicio, horaFin, titulo) {
                var id = Date.now();
                var numSesion = sesionesData.length + 1;
                var sesion = {
                    id: id,
                    hijo_id: hijoId,
                    planes_servicio_id: servicioId,
                    trabajadore_id: trabajadorId,
                    fecha: fecha,
                    hora_inicio: horaInicio,
                    hora_fin: horaFin,
                    _titulo: titulo
                };
                sesionesData.push(sesion);

                // Agregar evento a la fuente "nuevas" del calendario
                calendarSesiones.addEvent({
                    id: String(id),
                    title: '#' + numSesion + ' ' + titulo,
                    start: fecha + 'T' + horaInicio,
                    end: fecha + 'T' + horaFin,
                    backgroundColor: '#405189',
                    borderColor: '#405189',
                    extendedProps: { tipo: 'nueva' }
                }, 'nuevas');

                actualizarInputsSesiones();
                actualizarProgresoSesiones();
                actualizarListaSesiones();

                var fechaDisplay = fecha.split('-').reverse().join('/');
                bootstrap.Modal.getInstance(document.getElementById('modalAgregarSesion')).hide();
                showToast('success', 'Sesión #' + numSesion + ' agregada para el ' + fechaDisplay + ' a las ' + horaInicio);
                $('#btnGuardarSesion').prop('disabled', false).html('<i class="ri-save-3-line me-1"></i>Agregar Sesión');
            }

            // Al cambiar el hijo por defecto (no aplica al calendario, solo referencia)

            // Botón registrar hijo
            $('#btnRegistrarHijo').click(function() {
                let clienteId = $('#cliente_id_hidden').val();
                if (!clienteId) {
                    showToast('warning', 'Primero debe seleccionar un cliente');
                    return;
                }
                $('#hijo_cliente_id').val(clienteId);
                $('#registrarHijoModal').modal('show');
            });

            // Enviar formulario registro hijo
            $('#addHijoForm').submit(function(e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }
                let submitBtn = $(this).find('button[type="submit"]');
                let originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');
                let clienteId = $('#hijo_cliente_id').val();
                $.ajax({
                    url: "{{ url('admin/clientes') }}/" + clienteId + "/registrar-hijo",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            $('#registrarHijoModal').modal('hide');
                            showToast('success', res.msg);
                            cargarHijos(clienteId, res.hijo.id);
                        } else {
                            showToast('error', res.msg || 'Error al registrar hijo');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        showToast('error', errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });


            // ========== PAGO ÚNICO ==========
            function actualizarTotalPagado() {
                var tipoPago = $('#tipo-pago-select').val();
                var total = 0;
                if (tipoPago === 'parcial') {
                    total = (parseFloat($('#pago-monto-efectivo').val()) || 0) + (parseFloat($('#pago-monto-qr').val()) || 0);
                } else {
                    total = parseFloat($('#pago-monto').val()) || 0;
                }
                var descuento = parseFloat($('#pago-descuento').val()) || 0;
                var neto = Math.max(0, total - descuento);
                var saldo = precioPlan - neto;

                $('#total-pagado').text(neto.toFixed(2));
                $('#pago-resumen-monto').text(neto.toFixed(2) + ' Bs.');
                $('#pago-resumen-saldo').text(saldo.toFixed(2) + ' Bs.');

                if (neto > precioPlan) {
                    $('#total-pagado-badge').removeClass('bg-success bg-opacity-10 text-success').addClass('bg-danger bg-opacity-10 text-danger');
                    $('#saldo-box').css({'background':'rgba(220,53,69,0.07)','border-color':'rgba(220,53,69,0.15)'});
                    $('#pago-resumen-saldo').removeClass('text-success').addClass('text-danger');
                    $('#btnSubmit').prop('disabled', true);
                } else {
                    $('#total-pagado-badge').removeClass('bg-danger bg-opacity-10 text-danger').addClass('bg-success bg-opacity-10 text-success');
                    $('#btnSubmit').prop('disabled', false);
                    if (saldo <= 0) {
                        $('#saldo-box').css({'background':'rgba(25,135,84,0.07)','border-color':'rgba(25,135,84,0.15)'});
                        $('#pago-resumen-saldo').removeClass('text-danger').addClass('text-success');
                    } else {
                        $('#saldo-box').css({'background':'rgba(220,53,69,0.07)','border-color':'rgba(220,53,69,0.15)'});
                        $('#pago-resumen-saldo').removeClass('text-success').addClass('text-danger');
                    }
                }
            }

            $('#tipo-pago-select').on('change', function() {
                var tipo = $(this).val();
                if (tipo === 'parcial') {
                    $('#campo-monto-unico').hide();
                    $('#campo-monto-efectivo, #campo-monto-qr').show();
                    $('#pago-monto').prop('required', false).val('');
                    $('#pago-monto-efectivo, #pago-monto-qr').prop('required', true);
                } else if (tipo === 'efectivo' || tipo === 'qr') {
                    $('#campo-monto-unico').show();
                    $('#campo-monto-efectivo, #campo-monto-qr').hide();
                    $('#pago-monto').prop('required', true);
                    $('#pago-monto-efectivo').prop('required', false).val('');
                    $('#pago-monto-qr').prop('required', false).val('');
                } else {
                    $('#campo-monto-unico').show();
                    $('#campo-monto-efectivo, #campo-monto-qr').hide();
                }
                actualizarTotalPagado();
            });

            $(document).on('input', '#pago-monto, #pago-monto-efectivo, #pago-monto-qr, #pago-descuento', function() {
                actualizarTotalPagado();
            });

            // Validar formulario antes de enviar
            $('#formSuscripcion').on('submit', function(e) {
                // Validar sesiones: mínimo 1, máximo cantidadSesiones
                if (cantidadSesiones > 0 && sesionesData.length === 0) {
                    e.preventDefault();
                    showToast('error', 'Debes programar al menos 1 sesión.');
                    document.getElementById('sesiones-card').scrollIntoView({ behavior: 'smooth' });
                    return false;
                }
                if (cantidadSesiones > 0 && sesionesData.length > cantidadSesiones) {
                    e.preventDefault();
                    showToast('error', 'El plan solo permite ' + cantidadSesiones + ' sesiones. Tienes ' + sesionesData.length + '.');
                    document.getElementById('sesiones-card').scrollIntoView({ behavior: 'smooth' });
                    return false;
                }
                // Validar pago
                var tipoPago = $('#tipo-pago-select').val();
                if (!tipoPago) {
                    e.preventDefault();
                    showToast('error', 'Selecciona el tipo de pago');
                    return false;
                }
                var totalPagado = 0;
                if (tipoPago === 'parcial') {
                    var ef = parseFloat($('#pago-monto-efectivo').val()) || 0;
                    var qr = parseFloat($('#pago-monto-qr').val()) || 0;
                    if (ef <= 0 && qr <= 0) {
                        e.preventDefault();
                        showToast('error', 'En pago parcial, al menos un monto debe ser mayor a 0');
                        return false;
                    }
                    totalPagado = ef + qr;
                } else {
                    totalPagado = parseFloat($('#pago-monto').val()) || 0;
                }
                var descuento = parseFloat($('#pago-descuento').val()) || 0;
                var neto = Math.max(0, totalPagado - descuento);
                if (neto > precioPlan) {
                    e.preventDefault();
                    showToast('error', 'El monto pagado no puede exceder el precio del plan (' + precioPlan.toFixed(2) + ' Bs.)');
                    return false;
                }
            });
            // ========== FIN FUNCIONES PAGOS ==========

            // Función toast
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
                const cfg = config[type] || config.info;
                const toastId = 'toast-' + Date.now();
                const toastHtml = `
                    <div id="${toastId}" class="toast ${cfg.bgClass} text-white shadow-lg" role="alert">
                        <div class="toast-header ${cfg.bgClass} text-white border-bottom-0">
                            <i class="${cfg.icon} me-2"></i>
                            <strong class="me-auto">${cfg.title}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body d-flex align-items-center">
                            <i class="${cfg.icon} me-2 fs-5"></i>
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
                new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                }).show();
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

            // ========== AUTO-SELECCIÓN DESDE BÚSQUEDA ==========
            @if(isset($preCliente) && $preCliente)
            (function () {
                var pc = @json($preCliente);
                var preHijoId = @json($preHijoId);
                var nombre = [
                    pc.persona.nombres || '',
                    pc.persona.apellido_paterno || '',
                    pc.persona.apellido_materno || ''
                ].join(' ').replace(/\s+/g, ' ').trim();

                seleccionarCliente(
                    pc.id,
                    nombre,
                    pc.persona.carnet || '',
                    pc.persona.correo || '',
                    preHijoId
                );
            })();
            @endif

            // ========== TOGGLE PRECIO ESPECIAL ==========
            $('#tarifa_especial_switch').on('change', function() {
                var $lbl = $('#tarifa-label');
                if (this.checked) {
                    $lbl.removeClass('bg-secondary').addClass('bg-warning text-dark')
                        .html('<i class="ri-heart-pulse-line me-1"></i>Precio especial');
                } else {
                    $lbl.removeClass('bg-warning text-dark').addClass('bg-secondary')
                        .html('Precio normal');
                }
                // Re-renderizar planes con el precio adecuado, conservando selección si sigue válida
                var seleccionado = $('#plane_select').val();
                if (typeof planesCache !== 'undefined' && planesCache.length) {
                    renderPlanes(seleccionado || null);
                }
            });
        });
    </script>
@endpush
