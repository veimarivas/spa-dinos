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
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-search-line me-1 text-primary"></i>Buscar Cliente
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control cliente-search" id="cliente_search" name="cliente_id"
                                        style="width: 100%;" required></select>
                                    <div class="form-text text-muted mt-2">
                                        <i class="ri-information-line me-1"></i>Escribe al menos 2 caracteres para buscar
                                        por carnet, nombre o correo
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-primary btn-lg w-100 rounded-3"
                                        id="btnRegistrarCliente">
                                        <i class="ri-user-add-line me-2"></i>Registrar Nuevo Cliente
                                    </button>
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
                            <div class="alert alert-info d-flex align-items-center rounded-3 mb-4" role="alert">
                                <i class="ri-information-fill fs-4 me-3 text-info"></i>
                                <div>
                                    <strong>Información importante:</strong>
                                    <p class="mb-0 small">Todas las sesiones deben ser programadas. La hora de fin se
                                        calcula automáticamente (1 hora después del inicio).</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="sesiones-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="20%"><i class="ri-baby-line me-1"></i>Hijo <span
                                                    class="text-danger">*</span></th>
                                            <th width="20%"><i class="ri-service-line me-1"></i>Servicio <span
                                                    class="text-danger">*</span></th>
                                            <th width="15%"><i class="ri-user-line me-1"></i>Trabajador <span
                                                    class="text-danger">*</span></th>
                                            <th width="15%"><i class="ri-calendar-line me-1"></i>Fecha <span
                                                    class="text-danger">*</span></th>
                                            <th width="15%"><i class="ri-time-line me-1"></i>Hora Inicio <span
                                                    class="text-danger">*</span></th>
                                            <th width="10%"><i class="ri-time-line me-1"></i>Hora Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sesiones-tbody">
                                        <!-- Se llenará vía JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECCIÓN 6: PAGOS ========== -->
                    <!-- ========== SECCIÓN 6: PAGOS ========== -->
                    <div class="card border-0 shadow-lg rounded-4 mb-4" id="pagos-card" style="display: none;">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                        <span class="avatar-title text-success"><i
                                                class="ri-bank-card-line fs-5"></i></span>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Pagos</h5>
                                        <small class="text-muted">Registra los pagos realizados para esta
                                            suscripción</small>
                                    </div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"
                                    id="total-pagado-badge">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Total: <span
                                        id="total-pagado">0.00</span> Bs.
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info d-flex align-items-center rounded-3 mb-4" role="alert">
                                <i class="ri-information-fill fs-4 me-3 text-info"></i>
                                <div>
                                    <strong>Información importante:</strong>
                                    <p class="mb-0 small">Puedes registrar uno o varios pagos. El total no debe superar el
                                        precio del plan (<span id="precio-plan">0.00</span> Bs.).</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="pagos-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="20%"><i class="ri-bank-card-line me-1"></i>Tipo de Pago</th>
                                            <th width="20%" id="th-monto-unico"><i
                                                    class="ri-money-dollar-circle-line me-1"></i>Monto (Bs.)</th>
                                            <th width="15%" id="th-monto-efectivo" style="display: none;"><i
                                                    class="ri-money-dollar-circle-line me-1"></i>Efectivo</th>
                                            <th width="15%" id="th-monto-qr" style="display: none;"><i
                                                    class="ri-qr-code-line me-1"></i>QR</th>
                                            <th width="15%"><i class="ri-calendar-line me-1"></i>Fecha</th>
                                            <th width="15%"><i class="ri-percent-line me-1"></i>Descuento</th>
                                            <th width="15%"><i class="ri-user-line me-1"></i>Trabajador</th>
                                            <th class="text-center" width="10%"><i
                                                    class="ri-delete-bin-line me-1"></i>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pagos-tbody">
                                        <!-- Se llenará vía JavaScript -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2" class="text-end">Total Plan:</th>
                                            <td colspan="6" class="fw-bold" id="tfoot-precio-plan">0.00 Bs.</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end text-success">Total Pagado:</th>
                                            <td colspan="6" class="fw-bold text-success" id="tfoot-total-pagado">0.00
                                                Bs.</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end text-danger">Saldo Restante:</th>
                                            <td colspan="6" class="fw-bold text-danger" id="tfoot-saldo-restante">0.00
                                                Bs.</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-outline-success btn-lg rounded-pill"
                                    id="btnAgregarPago">
                                    <i class="ri-add-line me-2"></i>Agregar Pago
                                </button>
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
                    <h5 class="modal-title fw-bold">
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
                    <h5 class="modal-title fw-bold">
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
@endsection

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        /* Select2 personalizado */
        .cliente-search+.select2-container {
            width: 100% !important;
        }

        .cliente-search+.select2-container .select2-selection--single {
            height: auto !important;
            padding: 14px 18px !important;
            border-radius: 12px !important;
            border: 2px solid #e2e8f0 !important;
            background: #ffffff !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
        }

        .cliente-search+.select2-container .select2-selection--single:hover,
        .cliente-search+.select2-container.select2-container--open .select2-selection--single {
            border-color: #405189 !important;
            box-shadow: 0 0 0 4px rgba(64, 81, 137, 0.15) !important;
        }

        .select2-result-item {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
            padding: 10px !important;
        }

        .select2-result-avatar {
            width: 50px !important;
            height: 50px !important;
            background: linear-gradient(145deg, #405189, #5c6ac4) !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            text-transform: uppercase !important;
        }

        .select2-result-nombre {
            font-weight: 700 !important;
            color: #212529 !important;
            font-size: 16px !important;
        }

        .badge-ci,
        .badge-email {
            background: #f1f5f9 !important;
            color: #64748b !important;
            padding: 4px 12px !important;
            border-radius: 20px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
        }

        /* Tabla de sesiones y pagos */
        #sesiones-table th,
        #pagos-table th {
            background: #f8f9fa !important;
            font-weight: 600 !important;
            color: #495057 !important;
            border: none !important;
            padding: 15px 10px !important;
        }

        #sesiones-table td,
        #pagos-table td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
        }

        #sesiones-table .form-select,
        #sesiones-table .form-control,
        #pagos-table .form-select,
        #pagos-table .form-control {
            border-radius: 8px !important;
            border: 1px solid #dee2e6 !important;
            font-size: 0.9rem !important;
        }

        #sesiones-table tbody tr:hover,
        #pagos-table tbody tr:hover {
            background-color: #f8f9fa !important;
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

            // Inicializar Select2 para cliente
            $('.cliente-search').select2({
                ajax: {
                    url: '{{ route('admin.suscripciones.buscarCliente') }}',
                    type: 'POST',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                placeholder: 'Buscar por carnet, nombre o correo...',
                minimumInputLength: 2,
                allowClear: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Escribe al menos 2 caracteres';
                    },
                    searching: function() {
                        return 'Buscando...';
                    },
                    noResults: function() {
                        return 'No se encontraron clientes';
                    }
                },
                templateResult: function(data) {
                    if (data.loading || !data.id) return data.text;
                    let persona = data.persona || {};
                    let nombres = persona.nombres || '';
                    let apellidoPaterno = persona.apellido_paterno || '';
                    let apellidoMaterno = persona.apellido_materno || '';
                    let nombreCompleto = `${nombres} ${apellidoPaterno} ${apellidoMaterno}`.trim();
                    let carnet = persona.carnet ? `CI: ${persona.carnet}` : 'Sin CI';
                    let correo = persona.correo || '';
                    let iniciales = (nombres && apellidoPaterno) ? (nombres.charAt(0) + apellidoPaterno
                        .charAt(0)).toUpperCase() : (nombreCompleto ? nombreCompleto.charAt(0)
                        .toUpperCase() : '');
                    return $(`
                        <div class="select2-result-item">
                            <div class="select2-result-avatar">${iniciales ? iniciales : '<i class="ri-user-line"></i>'}</div>
                            <div class="select2-result-info">
                                <span class="select2-result-nombre">${nombreCompleto || 'Nombre no disponible'}</span>
                                <div class="select2-result-detalle">
                                    <span class="badge-ci"><i class="ri-id-card-line"></i> ${carnet}</span>
                                    ${correo ? `<span class="badge-email"><i class="ri-mail-line"></i> ${correo}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    `);
                },
                templateSelection: function(data) {
                    if (!data.id) return data.text;
                    let persona = data.persona || {};
                    let nombres = persona.nombres || '';
                    let apellido = persona.apellido_paterno || '';
                    let carnet = persona.carnet || '';
                    return (nombres && apellido) ? `${carnet} - ${nombres} ${apellido}`.trim() : data
                        .text;
                }
            }).on('select2:select', function(e) {
                clienteActualId = e.params.data.id;
                cargarHijos(clienteActualId);
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
                            let newOption = new Option(res.cliente.persona.carnet + ' - ' + res
                                .cliente.persona.nombres + ' ' + res.cliente.persona
                                .apellido_paterno, res.cliente.id, true, true);
                            $('.cliente-search').append(newOption).trigger('change');
                            clienteActualId = res.cliente.id;
                            cargarHijos(res.cliente.id);
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
                                generarFilasSesiones();
                            }
                        } else {
                            $select.append('<option value="">No hay hijos registrados</option>');
                            $('#hijo-selection-container').show();
                            if (cantidadSesiones > 0) {
                                $('#sesiones-tbody').html(
                                    '<tr><td colspan="7" class="text-center text-warning py-4">El cliente no tiene hijos registrados. Por favor, registre un hijo primero.</td></tr>'
                                );
                            }
                        }
                    },
                    error: function() {
                        hijosPorCliente = [];
                        showToast('error', 'No se pudieron cargar los hijos');
                    }
                });
            }

            // Evento cambio de paquete
            $('#paquete_select').on('change', function() {
                let paqueteId = $(this).val();
                let $planeSelect = $('#plane_select');
                $planeSelect.html('<option value="">Cargando planes...</option>').prop('disabled', true);
                $('#sesiones-card').hide();
                $('#sesiones-tbody').empty();
                // Ocultar pagos al cambiar paquete
                $('#pagos-card').hide();
                $('#pagos-tbody').empty();
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
                        let options = '<option value="">-- Seleccione un plan --</option>';
                        if (planes.length === 0) {
                            options = '<option value="">No hay planes en este paquete</option>';
                            $planeSelect.prop('disabled', true);
                        } else {
                            $.each(planes, function(i, plan) {
                                options +=
                                    `<option value="${plan.id}" data-sesiones="${plan.cantidad_sesiones}" data-precio="${plan.precio}">${plan.nombre_plan} - ${plan.cantidad_sesiones} sesiones - ${plan.precio} Bs.</option>`;
                            });
                            $planeSelect.prop('disabled', false);
                        }
                        $planeSelect.html(options);
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
                $('#total-sesiones-count').text(cantidadSesiones);
                $('#precio-plan').text(precioPlan.toFixed(2));

                // Limpiar pagos anteriores
                $('#pagos-tbody').empty();
                actualizarTotalPagado();

                if (!planId || cantidadSesiones === 0) {
                    $('#sesiones-card').hide();
                    $('#sesiones-tbody').empty();
                    $('#pagos-card').hide();
                    return;
                }

                $('#sesiones-card').show();
                $('#pagos-card').show(); // Mostrar card de pagos solo si hay plan seleccionado
                $('#sesiones-tbody').html(
                    '<tr><td colspan="7" class="text-center text-muted py-4">Cargando servicios del plan...</td></tr>'
                );

                cargarServicios(planId).then(function(servicios) {
                    serviciosPorPlan = servicios;
                    if (hijosPorCliente.length === 0) {
                        $('#sesiones-tbody').html(
                            '<tr><td colspan="7" class="text-center text-danger py-4">Primero debe seleccionar un cliente que tenga hijos registrados.</td></tr>'
                        );
                        return;
                    }
                    generarFilasSesiones();
                }).catch(function() {
                    $('#sesiones-tbody').html(
                        '<tr><td colspan="7" class="text-center text-danger py-4">Error al cargar servicios del plan.</td></tr>'
                    );
                });
            });

            // Generar filas de sesiones
            function generarFilasSesiones() {
                if (!cantidadSesiones || !hijosPorCliente.length || !serviciosPorPlan.length) return;
                let hijoDefault = $('#hijo_default').val();
                let html = '';
                for (let i = 1; i <= cantidadSesiones; i++) {
                    html += `
                        <tr class="sesion-row">
                            <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">${i}</span></td>
                            <td>
                                <select name="sesiones[${i-1}][hijo_id]" class="form-select form-select-sm" required>
                                    <option value="">-- Seleccionar hijo --</option>
                                    ${hijosPorCliente.map(h => `<option value="${h.id}" ${h.id == hijoDefault ? 'selected' : ''}>${h.text}</option>`).join('')}
                                </select>
                            </td>
                            <td>
                                <select name="sesiones[${i-1}][planes_servicio_id]" class="form-select form-select-sm" required>
                                    <option value="">-- Seleccionar servicio --</option>
                                    ${serviciosPorPlan.map((s, index) => `<option value="${s.id}" ${index === 0 ? 'selected' : ''}>${s.servicio.nombre} (${s.cantidad_sesiones} sesiones)</option>`).join('')}
                                </select>
                            </td>
                            <td>
                                <select name="sesiones[${i-1}][trabajadore_id]" class="form-select form-select-sm" required>
                                    <option value="">Seleccionar trabajador</option>
                                    @foreach ($trabajadores as $trabajador)
                                        <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date" name="sesiones[${i-1}][fecha]" class="form-control form-control-sm fecha-sesion" min="{{ date('Y-m-d') }}" required>
                            </td>
                            <td>
                                <select name="sesiones[${i-1}][hora_inicio]" class="form-select form-select-sm hora-inicio" required>
                                    <option value="">-- Hora --</option>
                                    ${horasOptions}
                                </select>
                            </td>
                            <td>
                                <input type="time" name="sesiones[${i-1}][hora_fin]" class="form-control form-control-sm" step="3600" readonly>
                            </td>
                        </tr>
                    `;
                }
                $('#sesiones-tbody').html(html);
            }

            // Al cambiar el hijo por defecto, actualizar todas las filas
            $(document).on('change', '#hijo_default', function() {
                let hijoId = $(this).val();
                if (!hijoId) return;
                $('select[name*="[hijo_id]"]').each(function() {
                    $(this).val(hijoId);
                });
            });

            // Botón registrar hijo
            $('#btnRegistrarHijo').click(function() {
                let clienteId = $('.cliente-search').val();
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

            // Calcular hora_fin automáticamente
            $(document).on('change', '.hora-inicio', function() {
                const $row = $(this).closest('tr');
                const horaInicio = $(this).val();
                if (horaInicio) {
                    const partes = horaInicio.split(':');
                    let hora = parseInt(partes[0]) + 1;
                    let minutos = partes[1];
                    const horaFin = (hora < 10 ? '0' + hora : hora) + ':' + minutos;
                    $row.find('input[name*="[hora_fin]"]').val(horaFin);
                } else {
                    $row.find('input[name*="[hora_fin]"]').val('');
                }
            });

            // Validar disponibilidad de horarios por trabajador
            $(document).on('change', '.fecha-sesion, .hora-inicio, select[name*="[trabajadore_id]"]', function() {
                const $row = $(this).closest('tr');
                const fecha = $row.find('.fecha-sesion').val();
                const horaInicio = $row.find('.hora-inicio').val();
                const trabajadorId = $row.find('select[name*="[trabajadore_id]"]').val();
                if (!fecha || !horaInicio) return;
                if (!trabajadorId) {
                    showToast('warning', 'Seleccione un trabajador para validar disponibilidad.');
                    return;
                }
                const $horaSelect = $row.find('.hora-inicio');
                $horaSelect.prop('disabled', true);
                $.ajax({
                    url: '{{ route('admin.suscripciones.horasOcupadas') }}',
                    method: 'POST',
                    data: {
                        _token: token,
                        fecha: fecha,
                        trabajador_id: trabajadorId,
                        sesion_id: null
                    },
                    success: function(response) {
                        $horaSelect.prop('disabled', false);
                        const valorActual = $horaSelect.val();
                        $horaSelect.find('option').prop('disabled', false);
                        if (response.horas_no_disponibles && response.horas_no_disponibles
                            .length) {
                            response.horas_no_disponibles.forEach(function(hora) {
                                $horaSelect.find('option[value="' + hora + '"]').prop(
                                    'disabled', true);
                            });
                        }
                        if (valorActual && $horaSelect.find('option[value="' + valorActual +
                                '"]').prop('disabled')) {
                            $horaSelect.val('');
                            showToast('warning',
                                'La hora seleccionada ya no está disponible para este trabajador en esta fecha. Por favor elige otra.'
                            );
                        } else if (valorActual) {
                            $horaSelect.val(valorActual);
                        }
                    },
                    error: function() {
                        $horaSelect.prop('disabled', false);
                        showToast('error', 'Error al verificar disponibilidad de horarios.');
                    }
                });
            });

            // ========== FUNCIONES PARA PAGOS ==========
            let pagoIndex = 0;

            // Actualizar total pagado
            function actualizarTotalPagado() {
                let total = 0;

                $('#pagos-tbody tr').each(function() {
                    const tipoPago = $(this).find('select[name*="[tipo_pago]"]').val();

                    if (tipoPago === 'parcial') {
                        const montoEfectivo = parseFloat($(this).find('input[name*="[monto_efectivo]"]')
                            .val()) || 0;
                        const montoQr = parseFloat($(this).find('input[name*="[monto_qr]"]').val()) || 0;
                        total += montoEfectivo + montoQr;
                    } else {
                        const monto = parseFloat($(this).find('input[name*="[monto]"]').val()) || 0;
                        total += monto;
                    }
                });

                $('#total-pagado').text(total.toFixed(2));
                $('#tfoot-total-pagado').text(total.toFixed(2) + ' Bs.');

                const saldoRestante = precioPlan - total;
                $('#tfoot-saldo-restante').text(saldoRestante.toFixed(2) + ' Bs.');
                $('#tfoot-precio-plan').text(precioPlan.toFixed(2) + ' Bs.');

                // Validar contra precio del plan
                if (total > precioPlan) {
                    $('#total-pagado-badge').removeClass('bg-success bg-opacity-10 text-success').addClass(
                        'bg-danger bg-opacity-10 text-danger');
                    $('#btnSubmit').prop('disabled', true);
                    $('#tfoot-saldo-restante').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#total-pagado-badge').removeClass('bg-danger bg-opacity-10 text-danger').addClass(
                        'bg-success bg-opacity-10 text-success');
                    $('#btnSubmit').prop('disabled', false);
                    $('#tfoot-saldo-restante').removeClass('text-success').addClass('text-danger');
                }

                // Si está completamente pagado
                if (saldoRestante <= 0 && saldoRestante >= 0) {
                    $('#tfoot-saldo-restante').removeClass('text-danger').addClass('text-success');
                }
            }

            // Toggle campos según tipo de pago
            function toggleCamposPago($row) {
                const tipoPago = $row.find('select[name*="[tipo_pago]"]').val();
                const thMontoUnico = $('#th-monto-unico');
                const thMontoEfectivo = $('#th-monto-efectivo');
                const thMontoQr = $('#th-monto-qr');

                if (tipoPago === 'parcial') {
                    // Mostrar campos de efectivo y QR
                    $row.find('.campo-monto-unico').hide();
                    $row.find('.campo-monto-efectivo').show();
                    $row.find('.campo-monto-qr').show();

                    // Hacer requeridos los campos de parcial
                    $row.find('input[name*="[monto_efectivo]"]').prop('required', true);
                    $row.find('input[name*="[monto_qr]"]').prop('required', true);
                    $row.find('input[name*="[monto]"]').prop('required', false).val('');

                    // Ocultar columnas de tabla para otros rows
                    thMontoUnico.hide();
                    thMontoEfectivo.show();
                    thMontoQr.show();

                } else if (tipoPago === 'efectivo' || tipoPago === 'qr') {
                    // Mostrar campo único
                    $row.find('.campo-monto-unico').show();
                    $row.find('.campo-monto-efectivo').hide();
                    $row.find('.campo-monto-qr').hide();

                    // Hacer requerido el campo único
                    $row.find('input[name*="[monto]"]').prop('required', true);
                    $row.find('input[name*="[monto_efectivo]"]').prop('required', false).val('');
                    $row.find('input[name*="[monto_qr]"]').prop('required', false).val('');

                    // Mostrar columnas de tabla
                    thMontoUnico.show();
                    thMontoEfectivo.hide();
                    thMontoQr.hide();
                }

                actualizarTotalPagado();
            }

            // Botón agregar pago
            $('#btnAgregarPago').click(function() {
                let index = pagoIndex++;
                let fechaDefault = new Date().toISOString().slice(0, 10);

                let fila = `
    <tr class="pago-row">
        <td>
            <select name="pagos[${index}][tipo_pago]" class="form-select form-select-sm tipo-pago-select" required>
                <option value="">-- Seleccionar --</option>
                <option value="efectivo">💵 Efectivo</option>
                <option value="qr">📱 QR</option>
                <option value="parcial">🔀 Parcial (Efectivo + QR)</option>
            </select>
        </td>
        <td class="campo-monto-unico">
            <input type="number" step="0.01" name="pagos[${index}][monto]" class="form-control form-control-sm monto-pago" placeholder="0.00" required>
        </td>
        <td class="campo-monto-efectivo" style="display: none;">
            <input type="number" step="0.01" name="pagos[${index}][monto_efectivo]" class="form-control form-control-sm monto-efectivo" placeholder="0.00">
        </td>
        <td class="campo-monto-qr" style="display: none;">
            <input type="number" step="0.01" name="pagos[${index}][monto_qr]" class="form-control form-control-sm monto-qr" placeholder="0.00">
        </td>
        <td>
            <input type="date" name="pagos[${index}][fecha_pago]" class="form-control form-control-sm" value="${fechaDefault}" required>
        </td>
        <td>
            <input type="number" step="0.01" name="pagos[${index}][descuento]" class="form-control form-control-sm" placeholder="0.00" value="0">
        </td>
        <td>
            <select name="pagos[${index}][trabajadore_id]" class="form-select form-select-sm">
                <option value="">-- Opcional --</option>
                @foreach ($trabajadores as $trabajador)
                <option value="{{ $trabajador->id }}">{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-circle remove-pago">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    </tr>
    `;

                $('#pagos-tbody').append(fila);

                // Inicializar toggle para la nueva fila
                const $newRow = $('#pagos-tbody tr').last();
                $newRow.find('.tipo-pago-select').on('change', function() {
                    toggleCamposPago($newRow);
                });

                // Escuchar cambios en montos
                $newRow.find('.monto-pago, .monto-efectivo, .monto-qr').on('input', function() {
                    actualizarTotalPagado();
                });

                actualizarTotalPagado();
            });

            // Eliminar fila de pago
            $(document).on('click', '.remove-pago', function() {
                $(this).closest('tr').remove();
                actualizarTotalPagado();
            });

            // Evento change para tipo de pago en filas existentes
            $(document).on('change', '.tipo-pago-select', function() {
                const $row = $(this).closest('tr');
                toggleCamposPago($row);
            });

            // Actualizar total al cambiar montos
            $(document).on('input', '.monto-pago, .monto-efectivo, .monto-qr', function() {
                actualizarTotalPagado();
            });

            // Validar formulario antes de enviar
            $('#formSuscripcion').on('submit', function(e) {
                let totalPagado = 0;

                $('#pagos-tbody tr').each(function() {
                    const tipoPago = $(this).find('select[name*="[tipo_pago]"]').val();

                    if (tipoPago === 'parcial') {
                        const montoEfectivo = parseFloat($(this).find(
                            'input[name*="[monto_efectivo]"]').val()) || 0;
                        const montoQr = parseFloat($(this).find('input[name*="[monto_qr]"]')
                            .val()) || 0;

                        if (montoEfectivo <= 0 && montoQr <= 0) {
                            e.preventDefault();
                            showToast('error',
                                'En pago parcial, al menos un monto debe ser mayor a 0');
                            return false;
                        }

                        totalPagado += montoEfectivo + montoQr;
                    } else {
                        const monto = parseFloat($(this).find('input[name*="[monto]"]').val()) || 0;
                        totalPagado += monto;
                    }
                });

                if (totalPagado > precioPlan) {
                    e.preventDefault();
                    showToast('error', 'El total de pagos no puede exceder el precio del plan (' +
                        precioPlan.toFixed(2) + ' Bs.)');
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
        });
    </script>
@endpush
