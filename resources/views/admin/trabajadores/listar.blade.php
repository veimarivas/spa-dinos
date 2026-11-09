{{-- resources/views/admin/trabajadores/listar.blade.php --}}
@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">
                            <i class="ri-user-settings-line me-2 text-primary"></i>Gestión de Trabajadores
                        </h4>
                        <p class="text-muted mb-0">Administra el personal (administradores, cobradores, fisioterapeutas)</p>
                    </div>
                    <div class="page-title-right">
                        @can('trabajadores.registrar')
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#registrarTrabajadorModal">
                            <i class="ri-add-line align-bottom me-1"></i> Nuevo Trabajador
                        </button>
                        @endcan
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
                                        placeholder="Buscar por nombre, carnet, correo o teléfono..."
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
                            <i class="ri-list-check me-2"></i>Listado de Trabajadores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="table-primary text-muted fw-semibold">
                                    <tr>
                                        <th class="px-3 py-3 text-center" width="5%">#</th>
                                        <th class="px-3 py-3" width="25%">Trabajador</th>
                                        <th class="px-3 py-3" width="15%">Contacto</th>
                                        <th class="px-3 py-3" width="15%">Cargo</th>
                                        <th class="px-3 py-3 text-center" width="10%">Sesiones</th>
                                        <th class="px-3 py-3 text-center" width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="trabajadoresTableBody">
                                    @include('admin.trabajadores.partials.table-body')
                                </tbody>
                            </table>
                        </div>

                        @if ($trabajadores->total() > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                                <div class="results-count text-muted">
                                    Mostrando <span class="fw-medium text-primary">{{ $trabajadores->firstItem() }}</span> a
                                    <span class="fw-medium text-primary">{{ $trabajadores->lastItem() }}</span> de
                                    <span class="fw-medium text-primary">{{ $trabajadores->total() }}</span> resultados
                                </div>
                                <div class="pagination-container">
                                    {{ $trabajadores->appends(request()->input())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Trabajador -->
    @can('trabajadores.registrar')
    <div class="modal fade" id="registrarTrabajadorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="registrarTrabajadorLabel">
                        <i class="ri-add-line me-2 align-bottom"></i>Registrar Nuevo Trabajador
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-id-card-line me-1 text-primary"></i>Carnet <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" name="carnet" class="form-control form-control-lg border-primary"
                                    required>
                                <div class="invalid-feedback">El carnet es obligatorio</div>
                                <small class="form-text text-muted feedback-carnet"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-barcode-line me-1 text-primary"></i>NIT
                                </label>
                                <input type="text" name="nit" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-user-line me-1 text-primary"></i>Nombres <span class="text-danger">*</span>
                                </label>
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
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-mail-line me-1 text-primary"></i>Correo
                                </label>
                                <input type="email" name="correo" id="correo_registro_trab" class="form-control form-control-lg border-primary">
                                <small class="form-text feedback-correo"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-map-pin-line me-1 text-primary"></i>Dirección
                                </label>
                                <input type="text" name="direccion"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-calendar-line me-1 text-primary"></i>Fecha Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-phone-line me-1 text-primary"></i>Celular
                                </label>
                                <input type="text" name="celular" class="form-control form-control-lg border-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-phone-line me-1 text-primary"></i>Teléfono
                                </label>
                                <input type="text" name="telefono"
                                    class="form-control form-control-lg border-primary">
                            </div>
                            <!-- Campo Cargo -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-briefcase-line me-1 text-primary"></i>Cargo <span
                                        class="text-danger">*</span>
                                </label>
                                <select name="cargo_id" class="form-select form-select-lg border-primary" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Debe seleccionar un cargo</div>
                            </div>
                            <!-- Campo Color -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-palette-line me-1 text-primary"></i>Color de identificación
                                </label>
                                <input type="color" name="color" class="form-control form-control-lg border-primary"
                                    value="#405189">
                                <small class="form-text text-muted">Selecciona un color para identificar al trabajador en
                                    el calendario general</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg addBtn" disabled>
                            <i class="ri-save-3-line me-1 align-bottom"></i>
                            <span class="submit-text">Registrar Trabajador</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Modificar Trabajador -->
    @can('trabajadores.editar')
    <div class="modal fade" id="modalModificarTrabajador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-primary" id="modalModificarLabel">
                        <i class="ri-edit-line me-2 align-bottom"></i>Editar Trabajador
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="trabajadorId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Carnet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="carnet_edicion" name="carnet" required>
                                <div class="invalid-feedback">El carnet es obligatorio</div>
                                <small class="form-text text-muted feedback-carnet-edicion"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">NIT</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="nit_edicion" name="nit">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="nombres_edicion" name="nombres" required>
                                <div class="invalid-feedback">El nombre es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="apellido_paterno_edicion" name="apellido_paterno" required>
                                <div class="invalid-feedback">El apellido paterno es obligatorio</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="apellido_materno_edicion" name="apellido_materno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Correo</label>
                                <input type="email" class="form-control form-control-lg border-primary"
                                    id="correo_edicion" name="correo">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Dirección</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="direccion_edicion" name="direccion">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Nacimiento</label>
                                <input type="date" class="form-control form-control-lg border-primary"
                                    id="fecha_nacimiento_edicion" name="fecha_nacimiento">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Celular</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="celular_edicion" name="celular">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Teléfono</label>
                                <input type="text" class="form-control form-control-lg border-primary"
                                    id="telefono_edicion" name="telefono">
                            </div>
                            <!-- Campo Cargo (edición) -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Cargo <span class="text-danger">*</span></label>
                                <select name="cargo_id" class="form-select form-select-lg border-primary"
                                    id="cargo_edicion" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Debe seleccionar un cargo</div>
                            </div>
                            <!-- Campo Color (edición) -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="ri-palette-line me-1 text-primary"></i>Color de identificación
                                </label>
                                <input type="color" class="form-control form-control-lg border-primary"
                                    id="color_edicion" name="color">
                                <small class="form-text text-muted">Color para identificar al trabajador en el calendario
                                    general</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-lg updateBtn" disabled>
                            <i class="ri-refresh-line me-1 align-bottom"></i>
                            <span class="submit-text">Actualizar Trabajador</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    <!-- Modal Eliminar Trabajador -->
    @can('trabajadores.eliminar')
    <div class="modal fade" id="modalEliminarTrabajador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger-subtle p-3 position-relative">
                    <h5 class="modal-title fw-bold text-danger" id="modalEliminarLabel">
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
                        <h4 class="mb-3 fw-bold text-danger">¿Estás seguro de eliminar este trabajador?</h4>
                        <p class="text-muted mb-0">Esta acción eliminará permanentemente el trabajador y sus datos
                            personales si no están siendo utilizados por otros registros.</p>
                        <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                            <small class="text-danger fw-medium">
                                <i class="ri-error-warning-line me-1"></i>Advertencia:
                            </small>
                            <small class="text-muted">No se puede eliminar si tiene sesiones asignadas.</small>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light p-3">
                        <button type="button" class="btn btn-soft-secondary btn-lg"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-lg btnDelete">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i>
                            <span class="submit-text">Sí, Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endcan

    {{-- ============== Modal Detalle de Pagos del Trabajador ============== --}}
    <div class="modal fade" id="modalSalariosTrab" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header p-3 px-4" id="modalSalariosTrabHeader"
                    style="background:linear-gradient(135deg,#1a2d4c,#b45309);border-radius:1rem 1rem 0 0;">
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">
                            <i class="ri-money-dollar-circle-line me-2"></i>
                            <span id="modalSalariosTrabNombre">Pagos del Trabajador</span>
                        </h5>
                        <small class="text-white-50" id="modalSalariosTrabCargo">—</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4" id="modalSalariosTrabBody">
                    <div class="text-center py-5">
                        <div class="spinner-border" style="color:#b45309;width:2.5rem;height:2.5rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted mb-0">Cargando información de salarios...</p>
                    </div>
                </div>

                <div class="modal-footer bg-light p-3" style="border-radius:0 0 1rem 1rem;">
                    <a href="#" id="btnIrLiquidacion" class="btn rounded-pill px-4 text-white"
                        style="background:linear-gradient(135deg,#1a2d4c,#b45309);border:none;">
                        <i class="ri-arrow-right-circle-line me-1"></i>Ir a Liquidación completa
                    </a>
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Credenciales Trabajador --}}
    @can('trabajadores.cuentas.enviar')
    <div class="modal fade" id="modalCredencialesTrab" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header p-4" style="background:linear-gradient(135deg,#25d366,#128c7e);">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-whatsapp-line me-2"></i>Enviar Credenciales por WhatsApp
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Se enviará el siguiente mensaje a <strong id="trabCredNombre"></strong> vía WhatsApp.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Número de celular:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                            <input type="text" id="trabCredCelular" class="form-control" placeholder="Sin celular registrado">
                        </div>
                        <small class="text-muted">Puedes editar el número si es necesario.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Vista previa del mensaje:</label>
                        <textarea id="trabCredMensaje" class="form-control" rows="6" style="font-size:.85rem;resize:none;"></textarea>
                    </div>
                    <div class="rounded-3 p-3 small"
                        style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);color:#92400e;">
                        <i class="ri-alert-line me-1"></i>
                        Si el trabajador ya cambió su contraseña, esta puede no ser válida.
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <a id="btnEnviarWaTrab" href="#" target="_blank"
                        class="btn fw-semibold rounded-3 px-4 text-white"
                        style="background:linear-gradient(135deg,#25d366,#128c7e);">
                        <i class="ri-whatsapp-line me-1"></i>Enviar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    @endcan

    {{-- Modal Crear Cuenta de Usuario --}}
    @can('trabajadores.cuentas.registrar')
    <div class="modal fade" id="modalCrearCuentaTrabajador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header p-4"
                    style="background:linear-gradient(135deg,#1a2d4c,#0d9488);">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="ri-user-add-line me-2"></i>Crear Cuenta de Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert mb-4 rounded-3 d-flex align-items-center gap-2"
                        style="background:rgba(13,148,136,.08);border:1px solid rgba(13,148,136,.2);">
                        <i class="ri-information-line fs-5" style="color:#0d9488;"></i>
                        <div class="small" style="color:#0d9488;">
                            La contraseña inicial será el <strong>carnet de identidad</strong> del trabajador.
                            Se recomienda cambiarlo en el primer inicio de sesión.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Trabajador</label>
                        <input type="text" id="cuentaNombre" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Correo electrónico <span class="text-danger">*</span></label>
                        <input type="email" id="cuentaEmail" class="form-control" placeholder="correo@ejemplo.com">
                        <small class="form-text feedback-cuenta-email"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Rol <span class="text-danger">*</span></label>
                        <select id="cuentaRol" class="form-select">
                            <option value="">Seleccionar rol</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="cuentaTrabajadorId">
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnGuardarCuenta" class="btn btn-primary" disabled>
                        <i class="ri-user-add-line me-1"></i>Crear cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('style')
    <style>
        /* Puedes copiar los estilos de clientes.listar y ajustar colores si quieres */
        .btn-primary {
            background: linear-gradient(135deg, #405189, #2c3e6d);
            border-color: #405189;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2c3e6d, #1f2b4a);
            border-color: #2c3e6d;
        }

        .bg-primary-subtle {
            background-color: rgba(64, 81, 137, 0.08) !important;
        }

        .border-primary {
            border-color: rgba(64, 81, 137, 0.3) !important;
        }

        .text-primary {
            color: #405189 !important;
        }

        .table-primary {
            --bs-table-bg: rgba(64, 81, 137, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(64, 81, 137, 0.04);
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
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Configurar CSRF token para todas las peticiones AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let debounceTimer;
            let isProcessing = false;

            // Inicializar tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Reset formularios al cerrar modales
            $('#registrarTrabajadorModal, #modalModificarTrabajador, #modalEliminarTrabajador').on(
                'hidden.bs.modal',
                function() {
                    if (this.id === 'registrarTrabajadorModal') {
                        $('#addForm')[0].reset();
                        $('.feedback-carnet').removeClass('text-success text-danger').text('');
                        $('.addBtn').prop('disabled', true);
                        $('#addForm').removeClass('was-validated');
                        $('.form-control').removeClass('is-valid is-invalid');
                    } else if (this.id === 'modalModificarTrabajador') {
                        $('#updateForm')[0].reset();
                        $('.feedback-carnet-edicion').removeClass('text-success text-danger').text('');
                        $('.updateBtn').prop('disabled', true);
                        $('#updateForm').removeClass('was-validated');
                        $('.form-control').removeClass('is-valid is-invalid');
                    }
                });

            // Verificar carnet al registrar
            $('input[name="carnet"]').on('input', function() {
                const carnet = $(this).val().trim();
                const feedback = $('.feedback-carnet');
                const submitBtn = $('.addBtn');
                const input = $(this);

                feedback.removeClass('text-success text-danger text-info').html('');
                submitBtn.prop('disabled', true);
                input.removeClass('is-valid is-invalid');

                if (carnet.length === 0) {
                    return;
                }

                if (carnet.length < 2) {
                    feedback.addClass('text-danger').html(
                        '<i class="ri-error-warning-line me-1"></i> El carnet debe tener al menos 2 caracteres'
                    );
                    input.addClass('is-invalid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.trabajadores.verificarCarnet') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            carnet: carnet
                        },
                        beforeSend: function() {
                            feedback.html(
                                '<i class="ri-loader-4-line spin me-1"></i> Verificando...'
                            );
                        },
                        success: function(res) {
                            if (!res.exists) {
                                // Caso 1: Carnet no existe en personas
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');
                                // Limpiar campos excepto carnet y cargo
                                $('#addForm').find(
                                    'input:not([name="carnet"]):not([name="cargo_id"])'
                                ).val('');
                            } else if (res.exists && res.is_worker) {
                                // Caso 2: Carnet existe y ya es trabajador
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', true);
                                input.addClass('is-invalid').removeClass('is-valid');
                                // Limpiar campos excepto carnet y cargo
                                $('#addForm').find(
                                    'input:not([name="carnet"]):not([name="cargo_id"])'
                                ).val('');
                            } else {
                                // Caso 3: Carnet existe pero no es trabajador → autocompletar
                                feedback.addClass('text-info').html(
                                    '<i class="ri-information-line me-1"></i> ' +
                                    res.message
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-invalid');

                                const p = res.persona;
                                $('input[name="nit"]').val(p.nit || '');
                                $('input[name="nombres"]').val(p.nombres || '');
                                $('input[name="apellido_paterno"]').val(p
                                    .apellido_paterno || '');
                                $('input[name="apellido_materno"]').val(p
                                    .apellido_materno || '');
                                $('input[name="correo"]').val(p.correo || '');
                                $('input[name="direccion"]').val(p.direccion || '');
                                $('input[name="fecha_nacimiento"]').val(p
                                    .fecha_nacimiento || '');
                                $('input[name="celular"]').val(p.celular || '');
                                $('input[name="telefono"]').val(p.telefono || '');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el carnet'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // ===== Verificar correo al registrar =====
            let correoDebounce;
            let correoValido = true; // opcional, válido por defecto

            $('#correo_registro_trab').on('input', function () {
                const correo = $(this).val().trim();
                const feedback = $('.feedback-correo');
                const input = $(this);

                clearTimeout(correoDebounce);
                feedback.removeClass('text-success text-danger text-warning').html('');
                input.removeClass('is-valid is-invalid');

                if (correo.length === 0) {
                    correoValido = true;
                    actualizarBtnTrab();
                    return;
                }

                correoDebounce = setTimeout(function () {
                    $.ajax({
                        url: '{{ route('admin.trabajadores.verificarCorreo') }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', correo: correo },
                        beforeSend: function () {
                            feedback.html('<i class="ri-loader-4-line spin me-1"></i> Verificando...');
                        },
                        success: function (res) {
                            if (res.exists) {
                                correoValido = false;
                                feedback.addClass('text-danger').html(
                                    '<i class="ri-error-warning-line me-1"></i> Este correo ya está registrado'
                                );
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                correoValido = true;
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Correo disponible'
                                );
                                input.addClass('is-valid').removeClass('is-invalid');
                            }
                            actualizarBtnTrab();
                        },
                        error: function () {
                            correoValido = true;
                            input.removeClass('is-valid is-invalid');
                            actualizarBtnTrab();
                        }
                    });
                }, 500);
            });

            function actualizarBtnTrab() {
                const carnetOk = $('.feedback-carnet').hasClass('text-success') ||
                                 $('.feedback-carnet').hasClass('text-info');
                $('.addBtn').prop('disabled', !(carnetOk && correoValido));
            }
            // ===== Fin verificar correo =====

            // Verificar carnet al editar
            $('#carnet_edicion').on('input', function() {
                const carnet = $(this).val().trim();
                const feedback = $('.feedback-carnet-edicion');
                const submitBtn = $('.updateBtn');
                const personaId = $('#trabajadorId').data('persona-id');
                const input = $(this);

                if (carnet.length === 0) {
                    feedback.removeClass('text-success text-danger').text('');
                    submitBtn.prop('disabled', true);
                    input.removeClass('is-valid is-invalid');
                    return;
                }

                if (carnet.length < 2) {
                    feedback.addClass('text-danger').text('El carnet debe tener al menos 2 caracteres');
                    submitBtn.prop('disabled', true);
                    input.addClass('is-invalid').removeClass('is-valid');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('admin.trabajadores.verificarCarnetEdicion') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            carnet: carnet,
                            persona_id: personaId
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
                                input.addClass('is-invalid').removeClass('is-valid');
                            } else {
                                feedback.addClass('text-success').html(
                                    '<i class="ri-checkbox-circle-line me-1"></i> Carnet disponible'
                                );
                                submitBtn.prop('disabled', false);
                                input.addClass('is-valid').removeClass('is-valid');
                            }
                        },
                        error: function() {
                            feedback.addClass('text-danger').html(
                                '<i class="ri-alert-line me-1"></i> Error al verificar el carnet'
                            );
                            submitBtn.prop('disabled', true);
                            input.addClass('is-invalid').removeClass('is-valid');
                        }
                    });
                }, 500);
            });

            // Editar trabajador: cargar datos en modal
            $(document).on('click', '.editBtn', function() {
                const data = $(this).data('bs-obj');
                $('#trabajadorId').val(data.id).data('persona-id', data.persona.id);
                $('#carnet_edicion').val(data.persona.carnet);
                $('#nit_edicion').val(data.persona.nit || '');
                $('#nombres_edicion').val(data.persona.nombres);
                $('#apellido_paterno_edicion').val(data.persona.apellido_paterno);
                $('#apellido_materno_edicion').val(data.persona.apellido_materno || '');
                $('#correo_edicion').val(data.persona.correo || '');
                $('#direccion_edicion').val(data.persona.direccion || '');
                $('#fecha_nacimiento_edicion').val(data.persona.fecha_nacimiento || '');
                $('#celular_edicion').val(data.persona.celular || '');
                $('#telefono_edicion').val(data.persona.telefono || '');
                $('#cargo_edicion').val(data.cargo_id);
                $('#color_edicion').val(data.color || '#405189');

                $('.feedback-carnet-edicion').removeClass('text-success text-danger').text('');
                $('.updateBtn').prop('disabled', false);
                $('#updateForm').removeClass('was-validated');
                $('#carnet_edicion').removeClass('is-valid is-invalid');
            });

            // Eliminar trabajador
            $(document).on('click', '.deleteBtn', function() {
                const data = $(this).data('bs-obj');
                $('#eliminarId').val(data.id);
                $('#modalEliminarTrabajador .modal-body h4').text(
                    `¿Estás seguro de eliminar a ${data.persona.nombres} ${data.persona.apellido_paterno}?`
                );
            });

            // REGISTRAR TRABAJADOR
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
                    url: "{{ route('admin.trabajadores.registrar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'registrarTrabajadorModal'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el trabajador');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el trabajador. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMsg = errors.join('<br>');
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

            // ACTUALIZAR TRABAJADOR
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
                    url: "{{ route('admin.trabajadores.modificar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalModificarTrabajador'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al actualizar el trabajador');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al actualizar el trabajador. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMsg = errors.join('<br>');
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

            // ELIMINAR TRABAJADOR
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                if (isProcessing) return;

                isProcessing = true;
                const submitBtn = $('.btnDelete');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...');

                $.ajax({
                    url: "{{ route('admin.trabajadores.eliminar') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalEliminarTrabajador'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => {
                                loadResults($('#searchInput').val().trim());
                            }, 300);
                        } else {
                            showToast('error', res.msg || 'Error al eliminar el trabajador');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al eliminar el trabajador. Intenta nuevamente.';
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

            // FUNCIÓN PARA CARGAR RESULTADOS (búsqueda/paginación)
            function loadResults(search = '') {
                if (isProcessing) return;
                isProcessing = true;

                $.ajax({
                    url: '{{ route('admin.trabajadores.listar') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#trabajadoresTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando trabajadores...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#trabajadoresTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                            }
                            $('[data-bs-toggle="tooltip"]').each(function() {
                                if (this._tooltip) this._tooltip.dispose();
                                new bootstrap.Tooltip(this);
                            });
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar los resultados');
                        $('#trabajadoresTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5 text-danger">
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

            // Búsqueda por texto
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

            // Manejar paginación
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
                        $('#trabajadoresTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando página...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#trabajadoresTableBody').html(response.html);
                            if (response.pagination) {
                                $('.pagination-container').html(response.pagination);
                            }
                            if (response.total !== undefined) {
                                $('.results-count').html(
                                    `Mostrando <span class="fw-medium text-primary">${response.from || 0}</span> a 
                                     <span class="fw-medium text-primary">${response.to || 0}</span> de 
                                     <span class="fw-medium text-primary">${response.total}</span> resultados`
                                );
                            }
                            const newUrl = url + (search ? '&search=' + encodeURIComponent(
                                search) : '');
                            window.history.pushState({}, '', newUrl);
                        }
                    },
                    error: function() {
                        showToast('error', 'Error al cargar la página');
                    }
                });
            });

            // FUNCIÓN TOAST
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

            // ===== MODAL CREDENCIALES WHATSAPP TRABAJADOR =====
            function buildWaTrabUrl() {
                const celular = $('#trabCredCelular').val().replace(/\D/g, '');
                const msg     = encodeURIComponent($('#trabCredMensaje').val());
                const url     = celular ? `https://wa.me/591${celular}?text=${msg}` : `https://wa.me/?text=${msg}`;
                $('#btnEnviarWaTrab').attr('href', url);
            }

            $(document).on('click', '.btnCredencialesTrab', function () {
                const btn    = $(this);
                const nombre = btn.data('nombre');
                const email  = btn.data('email');
                const carnet = btn.data('carnet') || '(sin carnet)';
                const cel    = btn.data('celular') || '';
                const appUrl = '{{ url('/') }}';

                $('#trabCredNombre').text(nombre);
                $('#trabCredCelular').val(cel);

                const msg = `Hola ${nombre} 👋\n\nTe comparto tus credenciales de acceso al sistema:\n\n📧 *Usuario:* ${email}\n🔑 *Contraseña:* ${carnet}\n🌐 *Ingresa en:* ${appUrl}\n\nSi ya cambiaste tu contraseña, usa la que tienes registrada.`;
                $('#trabCredMensaje').val(msg);
                buildWaTrabUrl();
            });

            $('#trabCredCelular, #trabCredMensaje').on('input', buildWaTrabUrl);
            // ===== FIN MODAL CREDENCIALES =====

            // ===== CREAR CUENTA DE USUARIO PARA TRABAJADOR =====
            let cuentaEmailDebounce;
            let cuentaEmailOk = false;

            $(document).on('click', '.btnCrearCuenta', function () {
                const btn = $(this);
                $('#cuentaTrabajadorId').val(btn.data('id'));
                $('#cuentaNombre').val(btn.data('nombre'));
                $('#cuentaEmail').val(btn.data('correo') || '');
                $('#cuentaRol').val('');
                $('.feedback-cuenta-email').removeClass('text-success text-danger').html('');
                $('#cuentaEmail').removeClass('is-valid is-invalid');
                $('#btnGuardarCuenta').prop('disabled', true);
                cuentaEmailOk = false;
                // Si hay correo precargado, verificar
                if (btn.data('correo')) {
                    verificarCuentaEmail();
                }
            });

            function verificarCuentaEmail() {
                const email = $('#cuentaEmail').val().trim();
                const fb = $('.feedback-cuenta-email');
                clearTimeout(cuentaEmailDebounce);
                fb.removeClass('text-success text-danger').html('');
                $('#cuentaEmail').removeClass('is-valid is-invalid');
                cuentaEmailOk = false;
                actualizarBtnCuenta();

                if (!email) return;

                cuentaEmailDebounce = setTimeout(function () {
                    $.ajax({
                        url: '{{ route('admin.users.verificar-email') }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', email: email },
                        beforeSend: function () {
                            fb.html('<i class="ri-loader-4-line spin me-1"></i> Verificando...');
                        },
                        success: function (res) {
                            if (res.exists) {
                                cuentaEmailOk = false;
                                fb.addClass('text-danger').html('<i class="ri-error-warning-line me-1"></i> Este correo ya está en uso');
                                $('#cuentaEmail').addClass('is-invalid');
                            } else {
                                cuentaEmailOk = true;
                                fb.addClass('text-success').html('<i class="ri-checkbox-circle-line me-1"></i> Correo disponible');
                                $('#cuentaEmail').addClass('is-valid');
                            }
                            actualizarBtnCuenta();
                        },
                        error: function () {
                            cuentaEmailOk = false;
                            actualizarBtnCuenta();
                        }
                    });
                }, 400);
            }

            function actualizarBtnCuenta() {
                const rolOk = $('#cuentaRol').val() !== '';
                $('#btnGuardarCuenta').prop('disabled', !(cuentaEmailOk && rolOk));
            }

            $('#cuentaEmail').on('input', verificarCuentaEmail);
            $('#cuentaRol').on('change', actualizarBtnCuenta);

            $('#btnGuardarCuenta').on('click', function () {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Creando...');

                $.ajax({
                    url: '{{ route('admin.users.crearCuentaTrabajador') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        trabajador_id: $('#cuentaTrabajadorId').val(),
                        email: $('#cuentaEmail').val().trim(),
                        role: $('#cuentaRol').val(),
                    },
                    success: function (res) {
                        if (res.success) {
                            $('#modalCrearCuentaTrabajador').modal('hide');
                            showToast('success',
                                `Cuenta creada. Email: <strong>${res.email}</strong> · Contraseña: <strong>${res.password}</strong>`
                            );
                            setTimeout(() => loadResults($('#searchInput').val().trim()), 400);
                        } else {
                            showToast('danger', res.msg || 'Error al crear la cuenta');
                            btn.prop('disabled', false).html('<i class="ri-user-add-line me-1"></i>Crear cuenta');
                        }
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.msg || xhr.responseJSON?.errors?.email?.[0] || 'Error al crear la cuenta';
                        showToast('danger', msg);
                        btn.prop('disabled', false).html('<i class="ri-user-add-line me-1"></i>Crear cuenta');
                    }
                });
            });
            // ===== FIN CREAR CUENTA =====

            // ===========================================================
            // ===== MODAL: DETALLE DE PAGOS DEL TRABAJADOR ============
            // ===========================================================
            var __modalSalariosTrab = null;
            var __trabajadorActual  = null;
            var SALARIO_PDF_BASE_URL = '{{ url('admin/trabajadores') }}';

            function buildPdfBtnsForMes(trabajadorId, anio, mes) {
                var urlGeneral = SALARIO_PDF_BASE_URL + '/' + trabajadorId + '/salario-pagado/pdf'
                    + '?' + encodeURIComponent('año') + '=' + encodeURIComponent(anio)
                    + '&mes=' + encodeURIComponent(mes) + '&tipo=general';
                var urlDetallado = SALARIO_PDF_BASE_URL + '/' + trabajadorId + '/salario-pagado/pdf'
                    + '?' + encodeURIComponent('año') + '=' + encodeURIComponent(anio)
                    + '&mes=' + encodeURIComponent(mes) + '&tipo=detallado';
                return ''
                    + '<div class="btn-group btn-group-sm" role="group">'
                    + '<a href="' + urlGeneral + '" target="_blank" rel="noopener" '
                    +    'class="btn btn-sm" '
                    +    'style="background:#1a2d4c;color:#fff;border:none;font-size:.65rem;padding:3px 8px;" '
                    +    'title="PDF General">'
                    +    '<i class="ri-file-list-3-line me-1"></i>General</a>'
                    + '<a href="' + urlDetallado + '" target="_blank" rel="noopener" '
                    +    'class="btn btn-sm" '
                    +    'style="background:#b45309;color:#fff;border:none;font-size:.65rem;padding:3px 8px;" '
                    +    'title="PDF Detallado">'
                    +    '<i class="ri-file-text-line me-1"></i>Detallado</a>'
                    + '</div>';
            }

            function fmtBs(v) {
                return 'Bs ' + Number(v || 0).toLocaleString('es-BO',
                    { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            $(document).on('click', '.btn-ver-salarios', function () {
                var id      = $(this).data('id');
                var nombre  = $(this).data('nombre') || 'Trabajador';
                var cargo   = $(this).data('cargo')  || '';
                var color   = $(this).data('color')  || '#b45309';
                __trabajadorActual = { id: id, nombre: nombre, cargo: cargo, color: color };

                $('#modalSalariosTrabNombre').text('Pagos de ' + nombre);
                $('#modalSalariosTrabCargo').text(cargo);
                $('#modalSalariosTrabHeader').css('background',
                    'linear-gradient(135deg, #1a2d4c, ' + color + ')');

                $('#btnIrLiquidacion').attr('href',
                    '{{ url('admin/trabajadores') }}/' + id + '/ver');

                // Reset body
                $('#modalSalariosTrabBody').html(
                    '<div class="text-center py-5">' +
                    '<div class="spinner-border" style="color:#b45309;width:2.5rem;height:2.5rem;"></div>' +
                    '<p class="mt-3 text-muted mb-0">Cargando información de salarios...</p>' +
                    '</div>'
                );

                if (!__modalSalariosTrab) {
                    __modalSalariosTrab = new bootstrap.Modal(document.getElementById('modalSalariosTrab'));
                }
                __modalSalariosTrab.show();

                // Fetch salarios
                var url = '{{ url('admin/trabajadores') }}/' + id + '/salarios';
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (res) { renderSalariosTrabModal(res.meses || []); })
                    .catch(function () {
                        $('#modalSalariosTrabBody').html(
                            '<div class="alert alert-danger rounded-4 m-0">' +
                            '<i class="ri-error-warning-line me-1"></i>Error al cargar los salarios.' +
                            '</div>'
                        );
                    });
            });

            function renderSalariosTrabModal(meses) {
                if (!meses.length) {
                    $('#modalSalariosTrabBody').html(
                        '<div class="text-center py-5 text-muted">' +
                        '<i class="ri-inbox-line fs-1 opacity-50"></i>' +
                        '<p class="mt-2 mb-0">No hay sesiones realizadas registradas para este trabajador.</p>' +
                        '</div>'
                    );
                    return;
                }

                // ── Agregados ──
                var totalPagado = 0, totalPagable = 0, totalSinInf = 0;
                var cntPagados = 0, cntPagables = 0, cntPendientes = 0;
                meses.forEach(function (m) {
                    totalPagado   += parseFloat(m.total_pagado)      || 0;
                    totalPagable  += parseFloat(m.total_pendiente)   || 0;
                    totalSinInf   += parseFloat(m.total_sin_informe) || 0;
                    cntPagados    += (m.sesiones_pagadas    || []).length;
                    cntPagables   += (m.sesiones_pagables   || []).length;
                    cntPendientes += (m.sesiones_pendientes || []).length;
                });

                var kpisHtml =
                    '<div class="row g-3 mb-4">' +
                        '<div class="col-md-4">' +
                            '<div class="rounded-4 p-3 h-100" style="background:rgba(180,83,9,.06);border:1px solid rgba(180,83,9,.25);border-left:4px solid #b45309;">' +
                                '<div class="d-flex align-items-center gap-2 mb-1">' +
                                    '<div class="d-flex align-items-center justify-content-center rounded-3" style="width:34px;height:34px;background:rgba(180,83,9,.15);color:#b45309;"><i class="ri-time-line fs-5"></i></div>' +
                                    '<span class="fw-bold text-uppercase" style="font-size:.7rem;letter-spacing:.5px;color:#b45309;">Por pagar</span>' +
                                '</div>' +
                                '<div class="fw-bold" style="font-size:1.3rem;color:#b45309;line-height:1.1;">' + fmtBs(totalPagable) + '</div>' +
                                '<div class="text-muted small">' + cntPagables + ' sesion(es) con informe pagables</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<div class="rounded-4 p-3 h-100" style="background:rgba(21,128,61,.06);border:1px solid rgba(21,128,61,.25);border-left:4px solid #15803d;">' +
                                '<div class="d-flex align-items-center gap-2 mb-1">' +
                                    '<div class="d-flex align-items-center justify-content-center rounded-3" style="width:34px;height:34px;background:rgba(21,128,61,.15);color:#15803d;"><i class="ri-check-double-line fs-5"></i></div>' +
                                    '<span class="fw-bold text-uppercase" style="font-size:.7rem;letter-spacing:.5px;color:#15803d;">Ya pagado</span>' +
                                '</div>' +
                                '<div class="fw-bold" style="font-size:1.3rem;color:#15803d;line-height:1.1;">' + fmtBs(totalPagado) + '</div>' +
                                '<div class="text-muted small">' + cntPagados + ' sesion(es) liquidadas</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<div class="rounded-4 p-3 h-100" style="background:rgba(108,117,125,.06);border:1px solid rgba(108,117,125,.25);border-left:4px solid #6b7280;">' +
                                '<div class="d-flex align-items-center gap-2 mb-1">' +
                                    '<div class="d-flex align-items-center justify-content-center rounded-3" style="width:34px;height:34px;background:rgba(108,117,125,.15);color:#6b7280;"><i class="ri-alert-line fs-5"></i></div>' +
                                    '<span class="fw-bold text-uppercase" style="font-size:.7rem;letter-spacing:.5px;color:#6b7280;">Sin informe</span>' +
                                '</div>' +
                                '<div class="fw-bold" style="font-size:1.3rem;color:#6b7280;line-height:1.1;">' + fmtBs(totalSinInf) + '</div>' +
                                '<div class="text-muted small">' + cntPendientes + ' sesion(es) sin informe registrado</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

                // ── Tabla mes a mes ──
                var rows = '';
                meses.forEach(function (m) {
                    var sesPag  = (m.sesiones_pagadas    || []).length;
                    var sesPb   = (m.sesiones_pagables   || []).length;
                    var sesPe   = (m.sesiones_pendientes || []).length;
                    var totMes  = (parseFloat(m.total_pagado) || 0)
                                + (parseFloat(m.total_pendiente) || 0)
                                + (parseFloat(m.total_sin_informe) || 0);

                    // Estado
                    var estadoBadge;
                    if (sesPb > 0) {
                        estadoBadge = '<span class="badge rounded-pill px-2 py-1" style="background:rgba(180,83,9,.12);color:#b45309;font-size:.7rem;"><i class="ri-time-line me-1"></i>Pendiente</span>';
                    } else if (sesPag > 0 && sesPe === 0) {
                        estadoBadge = '<span class="badge rounded-pill px-2 py-1" style="background:rgba(21,128,61,.12);color:#15803d;font-size:.7rem;"><i class="ri-checkbox-circle-line me-1"></i>Pagado</span>';
                    } else if (sesPe > 0 && sesPag === 0 && sesPb === 0) {
                        estadoBadge = '<span class="badge rounded-pill px-2 py-1" style="background:rgba(108,117,125,.12);color:#6b7280;font-size:.7rem;"><i class="ri-alert-line me-1"></i>Sin informe</span>';
                    } else {
                        estadoBadge = '<span class="badge rounded-pill px-2 py-1" style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;"><i class="ri-information-line me-1"></i>Mixto</span>';
                    }

                    // Gasto info
                    var gastoInfo = m.gasto
                        ? ('<small class="text-muted d-block" title="' + (m.gasto.descripcion || '') + '"><i class="ri-receipt-line me-1"></i>' + (m.gasto.fecha || '—') + '</small>')
                        : '';

                    var reportesCell = sesPag > 0
                        ? buildPdfBtnsForMes(__trabajadorActual.id, m.año, m.mes)
                        : '<span class="text-muted small">—</span>';

                    rows +=
                        '<tr>' +
                            '<td><div class="fw-semibold" style="color:#1a2d4c;"><i class="ri-calendar-2-line me-1"></i>' + m.mes_label + '</div>' + gastoInfo + '</td>' +
                            '<td class="text-center">' +
                                '<div class="d-flex justify-content-center gap-1 flex-wrap">' +
                                    (sesPb  > 0 ? '<span class="badge rounded-pill" style="background:rgba(180,83,9,.1);color:#b45309;font-size:.7rem;"><i class="ri-time-line me-1"></i>' + sesPb + '</span>' : '') +
                                    (sesPag > 0 ? '<span class="badge rounded-pill" style="background:rgba(21,128,61,.1);color:#15803d;font-size:.7rem;"><i class="ri-check-double-line me-1"></i>' + sesPag + '</span>' : '') +
                                    (sesPe  > 0 ? '<span class="badge rounded-pill" style="background:rgba(108,117,125,.12);color:#6b7280;font-size:.7rem;"><i class="ri-alert-line me-1"></i>' + sesPe + '</span>' : '') +
                                '</div>' +
                            '</td>' +
                            '<td class="text-end" style="color:#b45309;font-weight:600;">' +
                                (parseFloat(m.total_pendiente) > 0 ? fmtBs(m.total_pendiente) : '<span class="text-muted">—</span>') +
                            '</td>' +
                            '<td class="text-end" style="color:#15803d;font-weight:600;">' +
                                (parseFloat(m.total_pagado) > 0 ? fmtBs(m.total_pagado) : '<span class="text-muted">—</span>') +
                            '</td>' +
                            '<td class="text-end" style="color:#6b7280;">' +
                                (parseFloat(m.total_sin_informe) > 0 ? fmtBs(m.total_sin_informe) : '<span class="text-muted">—</span>') +
                            '</td>' +
                            '<td class="text-end fw-bold" style="color:#1a2d4c;">' + fmtBs(totMes) + '</td>' +
                            '<td class="text-center">' + estadoBadge + '</td>' +
                            '<td class="text-center">' + reportesCell + '</td>' +
                        '</tr>';
                });

                var tableHtml =
                    '<div class="table-responsive">' +
                        '<table class="table table-hover align-middle mb-0">' +
                            '<thead style="background:#f8fafc;">' +
                                '<tr>' +
                                    '<th class="border-0 py-2" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;">Mes / Gestion</th>' +
                                    '<th class="border-0 py-2 text-center" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;">Sesiones</th>' +
                                    '<th class="border-0 py-2 text-end" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#b45309;">Por pagar</th>' +
                                    '<th class="border-0 py-2 text-end" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#15803d;">Pagado</th>' +
                                    '<th class="border-0 py-2 text-end" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">Sin informe</th>' +
                                    '<th class="border-0 py-2 text-end" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#1a2d4c;">Total mes</th>' +
                                    '<th class="border-0 py-2 text-center" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;">Estado</th>' +
                                    '<th class="border-0 py-2 text-center" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;">Reportes</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' + rows + '</tbody>' +
                            '<tfoot>' +
                                '<tr style="background:#f8fafc;">' +
                                    '<td class="fw-bold" style="color:#1a2d4c;">TOTALES</td>' +
                                    '<td class="text-center text-muted">' + (cntPagables + cntPagados + cntPendientes) + '</td>' +
                                    '<td class="text-end fw-bold" style="color:#b45309;">' + fmtBs(totalPagable) + '</td>' +
                                    '<td class="text-end fw-bold" style="color:#15803d;">' + fmtBs(totalPagado) + '</td>' +
                                    '<td class="text-end fw-bold" style="color:#6b7280;">' + fmtBs(totalSinInf) + '</td>' +
                                    '<td class="text-end fw-bold" style="color:#1a2d4c;font-size:1rem;">' + fmtBs(totalPagable + totalPagado + totalSinInf) + '</td>' +
                                    '<td></td>' +
                                    '<td></td>' +
                                '</tr>' +
                            '</tfoot>' +
                        '</table>' +
                    '</div>';

                $('#modalSalariosTrabBody').html(kpisHtml + tableHtml);
            }
            // ===== FIN MODAL SALARIOS =====
        });
    </script>
@endpush
