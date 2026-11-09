@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-money-dollar-circle-line me-2 text-primary"></i>Gestion de Gastos
                        </h4>
                        <p class="text-muted mb-0">Administra los gastos del negocio: manuales y generados por compras</p>
                    </div>

                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Gasto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle fs-24">
                                        <i class="ri-file-list-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Gastos</p>
                                <h4 class="mb-0" id="totalGastosCounter">{{ $totalGastos }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-24">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Monto Total</p>
                                <h4 class="mb-0" id="totalMontoCounter">{{ number_format($totalMonto, 2) }} Bs</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle fs-24">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Gastos del Mes</p>
                                <h4 class="mb-0" id="gastosMesCounter">{{ number_format($gastosMes, 2) }} Bs</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                    style="width:36px;height:36px;background:rgba(13,110,253,.1);color:#0d6efd;">
                                    <i class="ri-filter-3-line fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color:#0d6efd;">Filtros</h6>
                                    <small class="text-muted">Elige por mes/gestión o por rango de fechas</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm rounded-pill px-3" id="btnEsteMes"
                                    style="background:rgba(13,110,253,.1);color:#0d6efd;border:1px solid rgba(13,110,253,.3);">
                                    <i class="ri-calendar-check-line me-1"></i>Este mes
                                </button>
                                <button type="button" class="btn btn-sm rounded-pill px-3" id="btnEsteAno"
                                    style="background:rgba(13,148,136,.1);color:#0d9488;border:1px solid rgba(13,148,136,.3);">
                                    <i class="ri-calendar-2-line me-1"></i>Este año
                                </button>
                            </div>
                        </div>

                        @php
                            $modoFiltro = ($mesSeleccionado && $gestionSeleccionada) ? 'mes' : 'rango';
                        @endphp
                        <ul class="nav nav-pills filter-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $modoFiltro === 'mes' ? 'active' : '' }}" type="button"
                                    data-bs-toggle="pill" data-bs-target="#filtroPorMes" role="tab">
                                    <i class="ri-calendar-2-line me-1"></i>Por mes / gestión
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $modoFiltro === 'rango' ? 'active' : '' }}" type="button"
                                    data-bs-toggle="pill" data-bs-target="#filtroPorRango" role="tab">
                                    <i class="ri-calendar-event-line me-1"></i>Por rango de fechas
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade {{ $modoFiltro === 'mes' ? 'show active' : '' }}" id="filtroPorMes" role="tabpanel">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-dark mb-1 small">
                                            <i class="ri-calendar-2-line me-1" style="color:#0d6efd;"></i>Mes
                                        </label>
                                        <select id="filterMes" class="form-select form-select-lg rounded-3">
                                            <option value="">— Seleccione mes —</option>
                                            @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $m)
                                                <option value="{{ $i + 1 }}" {{ (int)($mesSeleccionado ?? 0) === ($i + 1) ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-dark mb-1 small">
                                            <i class="ri-calendar-event-line me-1" style="color:#0d6efd;"></i>Gestión
                                        </label>
                                        <select id="filterGestion" class="form-select form-select-lg rounded-3">
                                            <option value="">— Seleccione año —</option>
                                            @foreach ($gestionesDisponibles as $g)
                                                <option value="{{ $g }}" {{ (int)($gestionSeleccionada ?? 0) === $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-lg w-100 rounded-3 text-white" id="btnFiltrarMesGestion"
                                            style="background:linear-gradient(135deg,#0d6efd,#0a58ca);border:none;">
                                            <i class="ri-search-2-line me-1"></i> Aplicar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade {{ $modoFiltro === 'rango' ? 'show active' : '' }}" id="filtroPorRango" role="tabpanel">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-dark mb-1 small">
                                            <i class="ri-calendar-line me-1" style="color:#0d9488;"></i>Desde
                                        </label>
                                        <input type="date" id="filterFechaDesde" class="form-control form-control-lg rounded-3"
                                            value="{{ $fechaDesde ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-dark mb-1 small">
                                            <i class="ri-calendar-line me-1" style="color:#0d9488;"></i>Hasta
                                        </label>
                                        <input type="date" id="filterFechaHasta" class="form-control form-control-lg rounded-3"
                                            value="{{ $fechaHasta ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold text-dark mb-1 small">
                                            <i class="ri-price-tag-3-line me-1" style="color:#6c757d;"></i>Categoria
                                        </label>
                                        <select id="filterCategoria" class="form-select form-select-lg rounded-3">
                                            <option value="">Todas</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                                @foreach ($categoria->children as $child)
                                                    <option value="{{ $child->id }}">&nbsp;&nbsp;-- {{ $child->nombre }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-lg w-100 rounded-3" id="btnFiltrarRango"
                                            style="background:linear-gradient(135deg,#0d9488,#0a7e74);border:none;">
                                            <i class="ri-filter-3-line me-1"></i> Aplicar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search ps-5"
                                        placeholder="Buscar por descripcion o comprobante..."
                                        value="{{ request('search') ?? '' }}">
                                    <i class="ri-search-line search-icon position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
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

        <!-- Gastos por Categoria -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                style="width:36px;height:36px;background:rgba(220,53,69,.1);color:#dc3545;">
                                <i class="ri-pie-chart-line fs-5"></i>
                            </div>
                            <div>
                                 <h6 class="mb-0 fw-bold" style="color:#dc3545;">Gastos por Categoria</h6>
                                <small class="text-muted">Distribucion de gastos en el periodo — incluye desglose por subcategorias</small>
                            </div>
                            <span class="badge rounded-pill ms-auto" style="background:rgba(220,53,69,.1);color:#dc3545;font-size:.75rem;">
                                <i class="ri-money-dollar-circle-line me-1"></i>Total: <strong id="totalPeriodoLabel">{{ number_format($gastosPeriodo ?? 0, 2) }}</strong> Bs
                            </span>
                        </div>
                        <div id="gastosCategoriaContainer">
                            @include('admin.gastos.partials.gastos-categoria')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-primary-subtle">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ri-list-check me-2"></i>Listado de Gastos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-2 py-3 text-center" width="4%">#</th>
                                        <th class="px-2 py-3" width="13%">Fecha / Periodo</th>
                                        <th class="px-2 py-3" width="13%">
                                            <i class="ri-price-tag-3-line me-1"></i>Categoria
                                        </th>
                                        <th class="px-2 py-3" width="24%">Descripcion</th>
                                        <th class="px-2 py-3 text-end" width="11%">Monto Bs</th>
                                        <th class="px-2 py-3" width="14%">Comprobante / Origen</th>
                                        <th class="px-2 py-3 text-center" width="11%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="gastosTableBody">
                                    @include('admin.gastos.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($gastos->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $gastos->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $gastos->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $gastos->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $gastos->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar -->
    <div class="modal fade" id="registrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Gasto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="categoria_gasto_id_registro" class="form-label fw-medium">
                                        <i class="ri-price-tag-3-line me-1 text-primary"></i>Categoria
                                        <span class="text-danger">*</span>
                                    </label>
                                        <select id="categoria_gasto_id_registro" name="categoria_gasto_id"
                                            class="form-select form-select-lg border-primary" required>
                                            <option value="">Seleccionar categoria...</option>
                                            @foreach ($categorias as $categoria)
                                                @if (!in_array(strtolower($categoria->nombre), ['salarios', 'compra de productos']))
                                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                                @endif
                                                @foreach ($categoria->children as $child)
                                                    @if (!in_array(strtolower($child->nombre), ['salarios', 'compra de productos']))
                                                        <option value="{{ $child->id }}">&nbsp;&nbsp;-- {{ $child->nombre }}</option>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    <div class="invalid-feedback">Por favor selecciona una categoria</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="fecha_gasto_registro" class="form-label fw-medium">
                                        <i class="ri-calendar-line me-1 text-primary"></i>Fecha de Pago
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="fecha_gasto_registro" name="fecha_gasto"
                                        class="form-control form-control-lg border-primary"
                                        value="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback">Por favor selecciona una fecha</div>
                                    <small class="form-text text-muted">Dia en que se efectua el pago</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="mes_correspondiente_registro" class="form-label fw-medium">
                                        <i class="ri-calendar-event-line me-1 text-primary"></i>Mes
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="mes_correspondiente_registro" name="mes_correspondiente"
                                        class="form-select form-select-lg border-primary" required>
                                        <option value="">Seleccionar mes...</option>
                                        @php
                                            $meses = [
                                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                            ];
                                        @endphp
                                        @foreach ($meses as $num => $nombreMes)
                                            <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>
                                                {{ $nombreMes }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Selecciona el mes al que corresponde</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="anio_correspondiente_registro" class="form-label fw-medium">
                                        <i class="ri-calendar-2-line me-1 text-primary"></i>Anio
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="anio_correspondiente_registro" name="anio_correspondiente"
                                        class="form-control form-control-lg border-primary"
                                        min="2000" max="2100" value="{{ date('Y') }}" required>
                                    <div class="invalid-feedback">Ingresa un anio valido</div>
                                    <small class="form-text text-muted">A que mes/anio corresponde este gasto</small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div id="feedback_periodo_registro" class="alert d-none mb-0" role="alert"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_registro" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripcion
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="descripcion_registro" name="descripcion"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: Pago de alquiler mes de marzo" required>
                                    <div class="invalid-feedback">Por favor ingresa la descripcion del gasto</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="monto_bs_registro" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Monto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="monto_bs_registro" name="monto_bs"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el monto (minimo 0.01 Bs)</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="comprobante_registro" class="form-label fw-medium">
                                        <i class="ri-receipt-line me-1 text-primary"></i>Comprobante
                                    </label>
                                    <input type="text" id="comprobante_registro" name="comprobante"
                                        class="form-control form-control-lg border-primary"
                                        placeholder="Ej: FAC-001, REC-2024-03">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="observacion_registro" class="form-label fw-medium">
                                        <i class="ri-chat-3-line me-1 text-primary"></i>Observacion
                                    </label>
                                    <textarea id="observacion_registro" name="observacion" class="form-control form-control-lg border-primary"
                                        placeholder="Observaciones adicionales..." rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn">
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Gasto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Gasto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="gastoId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="categoria_gasto_id_edicion" class="form-label fw-medium">
                                        <i class="ri-price-tag-3-line me-1 text-primary"></i>Categoria
                                        <span class="text-danger">*</span>
                                    </label>
                                        <select id="categoria_gasto_id_edicion" name="categoria_gasto_id"
                                            class="form-select form-select-lg border-primary" required>
                                            <option value="">Seleccionar categoria...</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                                @foreach ($categoria->children as $child)
                                                    <option value="{{ $child->id }}">&nbsp;&nbsp;-- {{ $child->nombre }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    <div class="invalid-feedback">Por favor selecciona una categoria</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="fecha_gasto_edicion" class="form-label fw-medium">
                                        <i class="ri-calendar-line me-1 text-primary"></i>Fecha de Pago
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="fecha_gasto_edicion" name="fecha_gasto"
                                        class="form-control form-control-lg border-primary" required>
                                    <div class="invalid-feedback">Por favor selecciona una fecha</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="mes_correspondiente_edicion" class="form-label fw-medium">
                                        <i class="ri-calendar-event-line me-1 text-primary"></i>Mes
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="mes_correspondiente_edicion" name="mes_correspondiente"
                                        class="form-select form-select-lg border-primary" required>
                                        <option value="">Seleccionar mes...</option>
                                        @foreach ($meses as $num => $nombreMes)
                                            <option value="{{ $num }}">{{ $nombreMes }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Selecciona el mes al que corresponde</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="anio_correspondiente_edicion" class="form-label fw-medium">
                                        <i class="ri-calendar-2-line me-1 text-primary"></i>Anio
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="anio_correspondiente_edicion" name="anio_correspondiente"
                                        class="form-control form-control-lg border-primary"
                                        min="2000" max="2100" required>
                                    <div class="invalid-feedback">Ingresa un anio valido</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div id="feedback_periodo_edicion" class="alert d-none mb-0" role="alert"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion_edicion" class="form-label fw-medium">
                                        <i class="ri-file-text-line me-1 text-primary"></i>Descripcion
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="descripcion_edicion" name="descripcion"
                                        class="form-control form-control-lg border-primary" required>
                                    <div class="invalid-feedback">Por favor ingresa la descripcion del gasto</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="monto_bs_edicion" class="form-label fw-medium">
                                        <i class="ri-money-dollar-circle-line me-1 text-primary"></i>Monto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" id="monto_bs_edicion" name="monto_bs"
                                            class="form-control form-control-lg border-primary"
                                            placeholder="0.00" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="invalid-feedback">Ingresa el monto (minimo 0.01 Bs)</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="comprobante_edicion" class="form-label fw-medium">
                                        <i class="ri-receipt-line me-1 text-primary"></i>Comprobante
                                    </label>
                                    <input type="text" id="comprobante_edicion" name="comprobante"
                                        class="form-control form-control-lg border-primary">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="observacion_edicion" class="form-label fw-medium">
                                        <i class="ri-chat-3-line me-1 text-primary"></i>Observacion
                                    </label>
                                    <textarea id="observacion_edicion" name="observacion" class="form-control form-control-lg border-primary"
                                        rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn">
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Gasto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
                        <i class="ri-delete-bin-line me-2 align-bottom"></i>Confirmar Eliminacion
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
                        <h4 class="mb-3 fw-bold text-danger">Estas seguro de eliminar este gasto?</h4>
                        <p class="text-muted mb-0">Esta accion eliminara permanentemente el registro del gasto.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">Esta accion no se puede deshacer.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">Si, Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detalle Gasto --}}
    <div class="modal fade" id="modalDetalleGasto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-info" id="modalDetalleGastoLabel">
                        <i class="ri-file-info-line me-2 align-bottom"></i>Detalle del Gasto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="detalleGastoBody">
                    <div class="text-center py-4 text-muted">
                        <i class="ri-loader-4-line ri-spin fs-1"></i>
                        <p class="mt-2">Cargando...</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary btn-lg" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="detalleGastoPdfBtn" target="_blank"
                        class="btn btn-danger btn-lg">
                        <i class="ri-file-pdf-line me-1 align-bottom"></i>
                        Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .filter-tabs {
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            display: inline-flex !important;
            gap: 4px;
            flex-wrap: nowrap;
        }
        .filter-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: .88rem;
            transition: all .2s ease;
            border: none;
            background: transparent;
        }
        .filter-tabs .nav-link:hover:not(.active) {
            background: rgba(13,148,136,.08);
            color: #0d9488;
        }
        .filter-tabs .nav-link.active {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(13,110,253,.25);
        }

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

            // Resetear formularios cuando se cierran los modales
            $('#registrar, #modalModificar, #modalEliminar').on('hidden.bs.modal', function() {
                if (this.id === 'registrar') {
                    $('#addForm')[0].reset();
                    $('#addForm').removeClass('was-validated');
                    $('#addForm .form-control, #addForm .form-select').removeClass('is-valid is-invalid');
                    $('#fecha_gasto_registro').val('{{ date("Y-m-d") }}');
                    $('#mes_correspondiente_registro').val('{{ date("n") }}');
                    $('#anio_correspondiente_registro').val('{{ date("Y") }}');
                    $('#feedback_periodo_registro').addClass('d-none').removeClass('alert-danger alert-info alert-success').html('');
                } else if (this.id === 'modalModificar') {
                    $('#updateForm')[0].reset();
                    $('#updateForm').removeClass('was-validated');
                    $('#updateForm .form-control, #updateForm .form-select').removeClass('is-valid is-invalid');
                    $('#feedback_periodo_edicion').addClass('d-none').removeClass('alert-danger alert-info alert-success').html('');
                }
            });

            // Verificar duplicado de gasto por categoria+mes+anio
            function verificarPeriodo(modo) {
                const sufijo = modo === 'registro' ? 'registro' : 'edicion';
                const categoria = $('#categoria_gasto_id_' + sufijo).val();
                const mes = $('#mes_correspondiente_' + sufijo).val();
                const anio = $('#anio_correspondiente_' + sufijo).val();
                const feedback = $('#feedback_periodo_' + sufijo);
                const submitBtn = modo === 'registro' ? $('.addBtn') : $('.updateBtn');
                const id = modo === 'edicion' ? $('#gastoId').val() : null;

                feedback.removeClass('d-none alert-danger alert-info alert-success').html('');

                if (!categoria || !mes || !anio || anio.length < 4) {
                    submitBtn.prop('disabled', false);
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.gastos.verificarPeriodo') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            categoria_gasto_id: categoria,
                            mes_correspondiente: mes,
                            anio_correspondiente: anio,
                            id: id
                        },
                        beforeSend: function() {
                            feedback.removeClass('d-none').addClass('alert-info')
                                .html('<i class="ri-loader-4-line me-1"></i> Verificando periodo...');
                        },
                        success: function(res) {
                            if (res.exists) {
                                feedback.removeClass('alert-info alert-success').addClass('alert-danger')
                                    .html(
                                        '<i class="ri-error-warning-line me-1"></i><strong>Ya existe un gasto:</strong> ' +
                                        res.gasto.categoria + ' - ' + res.gasto.descripcion +
                                        ' (' + parseFloat(res.gasto.monto_bs).toFixed(2) + ' Bs, registrado el ' +
                                        res.gasto.fecha_gasto + ')'
                                    );
                                submitBtn.prop('disabled', true);
                            } else {
                                feedback.removeClass('alert-info alert-danger').addClass('alert-success')
                                    .html('<i class="ri-checkbox-circle-line me-1"></i> Periodo disponible');
                                submitBtn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            feedback.removeClass('alert-info alert-success').addClass('alert-danger')
                                .html('<i class="ri-alert-line me-1"></i> Error al verificar el periodo');
                        }
                    });
                }, 400);
            }

            $('#categoria_gasto_id_registro, #mes_correspondiente_registro, #anio_correspondiente_registro')
                .on('change input', function() { verificarPeriodo('registro'); });

            $('#categoria_gasto_id_edicion, #mes_correspondiente_edicion, #anio_correspondiente_edicion')
                .on('change input', function() { verificarPeriodo('edicion'); });

            // Editar gasto - llenar modal
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#gastoId').val(data.id);
                $('#categoria_gasto_id_edicion').val(data.categoria_gasto_id);
                $('#descripcion_edicion').val(data.descripcion || '');
                $('#monto_bs_edicion').val(data.monto_bs);
                // Handle date: could be object with date property or string
                if (data.fecha_gasto) {
                    let fecha = data.fecha_gasto;
                    if (typeof fecha === 'object' && fecha.date) {
                        fecha = fecha.date;
                    }
                    $('#fecha_gasto_edicion').val(fecha.substring(0, 10));
                }
                $('#comprobante_edicion').val(data.comprobante || '');
                $('#observacion_edicion').val(data.observacion || '');
                $('#mes_correspondiente_edicion').val(data.mes_correspondiente || '');
                $('#anio_correspondiente_edicion').val(data.anio_correspondiente || '');

                $('#updateForm').removeClass('was-validated');
                $('#updateForm .form-control, #updateForm .form-select').removeClass('is-valid is-invalid');
                $('#feedback_periodo_edicion').addClass('d-none').removeClass('alert-danger alert-info alert-success').html('');
            });

            // Eliminar gasto - llenar modal
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminar .modal-body h4').text(
                    'Estas seguro de eliminar este gasto?'
                );
            });

            // REGISTRAR GASTO
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
                    url: "{{ route('admin.gastos.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('registrar'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults();
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el gasto');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el gasto. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const firstError = Object.values(xhr.responseJSON.errors)[0];
                            if (firstError) errorMsg = firstError[0];
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

            // ACTUALIZAR GASTO
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
                    url: "{{ route('admin.gastos.modificar') }}",
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
                                loadResults();
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el gasto');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el gasto. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const firstError = Object.values(xhr.responseJSON.errors)[0];
                            if (firstError) errorMsg = firstError[0];
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

            // ELIMINAR GASTO
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.gastos.eliminar') }}",
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
                                loadResults();
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el gasto');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el gasto. Intenta nuevamente.';
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
            function getFilterParams() {
                var params = {};
                var search = $('#searchInput').val().trim();
                if (search) params.search = search;

                var categoriaId = $('#filterCategoria').val();
                if (categoriaId) params.categoria_gasto_id = categoriaId;

                // Determinar modo: si el tab mes/gestion esta activo
                var modoMes = $('#filtroPorMes').hasClass('show');
                if (modoMes) {
                    var mes = $('#filterMes').val();
                    var gestion = $('#filterGestion').val();
                    if (mes && gestion) {
                        params.mes = mes;
                        params.gestion = gestion;
                    }
                } else {
                    var fechaDesde = $('#filterFechaDesde').val();
                    var fechaHasta = $('#filterFechaHasta').val();
                    if (fechaDesde) params.fecha_desde = fechaDesde;
                    if (fechaHasta) params.fecha_hasta = fechaHasta;
                }
                return params;
            }

            function loadResults() {
                if (isProcessing) return;
                isProcessing = true;

                var data = getFilterParams();

                $.ajax({
                    url: '{{ route('admin.gastos.listar') }}',
                    method: 'GET',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#gastosTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando gastos...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#gastosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalGastosCounter').text(response.totalGastos);
                                $('#totalMontoCounter').text(response.totalMonto + ' Bs');
                                $('#gastosMesCounter').text(response.gastosMes + ' Bs');
                            }
                            if (response.gastosPeriodo !== undefined) {
                                $('#totalPeriodoLabel').text(response.gastosPeriodo);
                            }
                            if (response.gastosPorCategoriaHtml) {
                                $('#gastosCategoriaContainer').html(response.gastosPorCategoriaHtml);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading results:', xhr);
                        showToast('error', 'Error al cargar los resultados');
                        $('#gastosTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5 text-danger">
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

            // Busqueda por texto con debounce
            $('#searchInput').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadResults();
                }, 500);
            });

            // ── Nuevos filtros ──
            $('#btnFiltrarMesGestion').on('click', function() {
                loadResults();
            });
            $('#btnFiltrarRango').on('click', function() {
                loadResults();
            });

            // Auto-load al cambiar filtro por categoria (solo en modo rango)
            $('#filterCategoria').on('change', function() {
                if ($('#filtroPorRango').hasClass('show')) loadResults();
            });

            // Auto-load al cambiar fechas
            $('#filterFechaDesde, #filterFechaHasta').on('change', function() {
                if ($('#filtroPorRango').hasClass('show')) loadResults();
            });

            // Limpiar filtros
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#filterCategoria').val('');
                $('#filterFechaDesde').val('');
                $('#filterFechaHasta').val('');
                $('#filterMes').val('');
                $('#filterGestion').val('');
                $('.filter-tabs .nav-link[data-bs-target="#filtroPorRango"]').tab('show');
                loadResults();
            });

            // Atajos rápidos
            $('#btnEsteMes').on('click', function() {
                var now = new Date();
                var mes = now.getMonth() + 1;
                var anio = now.getFullYear();
                $('#filterMes').val(mes);
                $('#filterGestion').val(anio);
                $('.filter-tabs .nav-link[data-bs-target="#filtroPorMes"]').tab('show');
                loadResults();
            });
            $('#btnEsteAno').on('click', function() {
                var anio = new Date().getFullYear();
                $('#filterMes').val('');
                $('#filterGestion').val(anio);
                $('.filter-tabs .nav-link[data-bs-target="#filtroPorMes"]').tab('show');
                loadResults();
            });

            // Al cambiar de tab, recargar automáticamente
            $('.filter-tabs .nav-link').on('shown.bs.tab', function() {
                loadResults();
            });

            // Manejar paginacion
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                if (isProcessing) return;

                const url = $(this).attr('href');
                var data = getFilterParams();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#gastosTableBody').html(`
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando pagina...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#gastosTableBody').html(response.html);

                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }

                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                                $('#totalGastosCounter').text(response.totalGastos);
                                $('#totalMontoCounter').text(response.totalMonto + ' Bs');
                                $('#gastosMesCounter').text(response.gastosMes + ' Bs');
                            }
                            if (response.gastosPeriodo !== undefined) {
                                $('#totalPeriodoLabel').text(response.gastosPeriodo);
                            }
                            if (response.gastosPorCategoriaHtml) {
                                $('#gastosCategoriaContainer').html(response.gastosPorCategoriaHtml);
                            }

                            window.history.pushState({}, '', url);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la pagina');
                    }
                });
            });

            // FUNCION TOAST
            function showToast(type, message) {
                const config = {
                    success: {
                        icon: 'ri-checkbox-circle-fill',
                        bgClass: 'bg-success',
                        title: 'Exito'
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
                        title: 'Informacion'
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

            // Detalle Gasto Modal
            var detallePdfBaseUrl = '{{ route('admin.gastos.detallePdf', '_0_') }}';
            $(document).on('click', '.detalleGastoBtn', function() {
                const data = $(this).data('bs-obj');
                const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                const catObj = data.categoria || null;
                let catNombre = catObj ? catObj.nombre : 'Sin categoría';
                if (catObj && catObj.parent && catObj.parent.nombre) {
                    catNombre = '<span class="text-secondary" style="font-size:0.75rem;">' + catObj.parent.nombre + '</span> <i class="ri-arrow-right-s-line text-muted" style="font-size:0.7rem;"></i> ' + catObj.nombre;
                }
                const fechaStr = data.fecha_gasto
                    ? (typeof data.fecha_gasto === 'object' && data.fecha_gasto.date
                        ? data.fecha_gasto.date.substring(0, 10)
                        : data.fecha_gasto.substring(0, 10))
                    : '';
                const mesLabel = meses[(data.mes_correspondiente || 1) - 1];
                const origenStr = data.compra_id
                    ? '<span class="badge bg-info-subtle text-info"><i class="ri-shopping-cart-line me-1"></i>Compra #' + data.compra_id + '</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary"><i class="ri-edit-line me-1"></i>Manual</span>';
                const noEditable = data.compra_id
                    || (catNombre.toLowerCase() === 'salarios' || catNombre.toLowerCase() === 'compra de productos');

                var html = '<div class="row g-4">';
                html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Categoria</small><span class="fw-semibold fs-6">' + catNombre + '</span></div></div>';
                html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Origen</small><div>' + origenStr + '</div></div></div>';
                html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Fecha de Pago</small><span class="fw-semibold fs-6">' + fechaStr + '</span></div></div>';
                html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Periodo</small><span class="fw-semibold fs-6">' + mesLabel + ' ' + (data.anio_correspondiente || '') + '</span></div></div>';
                html += '<div class="col-12"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Descripcion</small><span class="fw-semibold fs-6">' + (data.descripcion || '—') + '</span></div></div>';
                html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Monto</small><span class="fw-bold fs-5 text-success">' + parseFloat(data.monto_bs).toFixed(2) + ' Bs</span></div></div>';
                if (data.comprobante) {
                    html += '<div class="col-md-6"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Comprobante</small><span class="fw-semibold fs-6">' + data.comprobante + '</span></div></div>';
                }
                if (data.observacion) {
                    html += '<div class="col-12"><div class="p-3 bg-light rounded-3"><small class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size:11px;letter-spacing:.3px;">Observacion</small><span class="fw-semibold fs-6">' + data.observacion + '</span></div></div>';
                }
                if (noEditable) {
                    html += '<div class="col-12"><div class="alert alert-warning mb-0 py-2 small"><i class="ri-information-line me-1"></i>Este gasto es generado automaticamente y no puede editarse ni eliminarse.</div></div>';
                }
                html += '</div>';

                $('#detalleGastoBody').html(html);
                $('#detalleGastoPdfBtn').attr('href', detallePdfBaseUrl.replace('_0_', data.id));
            });

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
