@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-danger">
                            <i class="ri-price-tag-3-line me-2 text-danger"></i>Categorías de Gastos
                        </h4>
                        <p class="text-muted mb-0">Organiza los gastos por categorías y subcategorías</p>
                    </div>
                    <div class="page-title-right">
                        @can('gastos.categorias.registrar')
                        <button type="button" class="btn btn-danger btn-lg waves-effect waves-light text-white"
                            data-bs-toggle="modal" data-bs-target="#registrar">
                            <i class="ri-add-line align-bottom me-1"></i> Nueva Categoría
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats + Search --}}
        <div class="row">
            <div class="col-xl-3 col-md-4">
                <div class="card border border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-24">
                                        <i class="ri-price-tag-3-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Categorías</p>
                                <h4 class="mb-0" id="totalCounter">{{ $total }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="search-box position-relative">
                                    <input type="text" id="searchInput" class="form-control search form-control-lg ps-5"
                                        placeholder="Buscar por nombre o descripción..."
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

        {{-- Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-header border-bottom-dashed d-flex align-items-center justify-content-between bg-danger-subtle">
                        <h5 class="card-title mb-0 fw-bold text-danger">
                            <i class="ri-list-check me-2"></i>Listado de Categorías de Gastos
                        </h5>
                        <small class="text-muted" id="resultInfo"></small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:60px;">#</th>
                                        <th>Categoría / Subcategorías</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Gastos</th>
                                        <th class="text-center" style="width:120px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @include('admin.categorias-gastos.partials.table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" id="paginationContainer">
                        {{ $categorias->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Registrar --}}
    @can('gastos.categorias.registrar')
    <div class="modal fade" id="registrar" tabindex="-1" aria-labelledby="registrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger-subtle">
                    <h5 class="modal-title fw-bold text-danger" id="registrarLabel">
                        <i class="ri-price-tag-3-line me-2"></i>Nueva Categoría de Gasto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formRegistrar">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoría Principal</label>
                            <select name="parent_id" id="reg_parent_id" class="form-select">
                                <option value="">— Es categoría principal —</option>
                                @foreach(App\Models\CategoriaGasto::whereNull('parent_id')->orderBy('nombre')->get() as $padre)
                                    <option value="{{ $padre->id }}">{{ $padre->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="reg_parent_id_error"></div>
                            <small class="text-muted">Selecciona una categoría principal si esta será una subcategoría</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="reg_nombre" class="form-control"
                                placeholder="Ej: Servicio Básico, Insumos, Alquiler...">
                            <div class="invalid-feedback" id="reg_nombre_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="reg_descripcion" class="form-control" rows="3"
                                placeholder="Descripción opcional de la categoría"></textarea>
                            <div class="invalid-feedback" id="reg_descripcion_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger text-white">
                            <i class="ri-save-line me-1"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    {{-- Modal Modificar --}}
    @can('gastos.categorias.editar')
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modificarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary-subtle">
                    <h5 class="modal-title fw-bold text-primary" id="modificarLabel">
                        <i class="ri-edit-line me-2"></i>Editar Categoría de Gasto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formModificar">
                    <input type="hidden" name="id" id="mod_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoría Principal</label>
                            <select name="parent_id" id="mod_parent_id" class="form-select">
                                <option value="">— Es categoría principal —</option>
                            </select>
                            <div class="invalid-feedback" id="mod_parent_id_error"></div>
                            <small class="text-muted">Selecciona una categoría principal si esta será una subcategoría</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="mod_nombre" class="form-control">
                            <div class="invalid-feedback" id="mod_nombre_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="mod_descripcion" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback" id="mod_descripcion_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    {{-- Modal Eliminar --}}
    @can('gastos.categorias.eliminar')
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="eliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger-subtle">
                    <h5 class="modal-title fw-bold text-danger" id="eliminarLabel">
                        <i class="ri-delete-bin-line me-2"></i>Eliminar Categoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle fs-36">
                            <i class="ri-error-warning-line"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-1">¿Eliminar la categoría?</p>
                    <h6 class="fw-bold" id="del_nombre_display">-</h6>
                    <small class="text-danger">Esta acción no se puede deshacer</small>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnConfirmarEliminar" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Eliminar
                    </button>
                </div>
                <input type="hidden" id="del_id">
            </div>
        </div>
    </div>
    @endcan

@push('script')
<script>
(function () {
    const routes = {
        listar:            '{{ route('admin.categorias-gastos.listar') }}',
        registrar:         '{{ route('admin.categorias-gastos.registrar') }}',
        verificar:         '{{ route('admin.categorias-gastos.verificar') }}',
        verificaredicion:  '{{ route('admin.categorias-gastos.verificaredicion') }}',
        modificar:         '{{ route('admin.categorias-gastos.modificar') }}',
        eliminar:          '{{ route('admin.categorias-gastos.eliminar') }}',
        obtenerCategorias: '{{ route('admin.categorias-gastos.obtener-categorias') }}',
    };
    const csrf = '{{ csrf_token() }}';

    let searchTimer;

    function clearFieldErrors(formId) {
        document.querySelectorAll('#' + formId + ' .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#' + formId + ' .invalid-feedback').forEach(el => el.textContent = '');
    }

    function showFieldErrors(prefix, errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = document.getElementById(prefix + '_' + field);
            const errDiv = document.getElementById(prefix + '_' + field + '_error');
            if (input) input.classList.add('is-invalid');
            if (errDiv) errDiv.textContent = messages[0];
        });
    }

    function recargar(url) {
        const search = document.getElementById('searchInput').value;
        const params = new URLSearchParams({ search });
        fetch((url || routes.listar) + '?' + params, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('tableBody').innerHTML = data.html;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            document.getElementById('totalCounter').textContent = data.total;
            if (data.from && data.to) {
                document.getElementById('resultInfo').textContent =
                    'Mostrando ' + data.from + ' - ' + data.to + ' de ' + data.filtered;
            } else {
                document.getElementById('resultInfo').textContent = '';
            }
            bindTableButtons();
            bindPagination();
        });
    }

    function bindPagination() {
        document.querySelectorAll('#paginationContainer a.page-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                recargar(this.href);
            });
        });
    }

    function cargarCategoriasPadre(selectId, excludeId) {
        let url = routes.obtenerCategorias;
        if (excludeId) {
            url += '?exclude=' + excludeId;
        }
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">— Es categoría principal —</option>';
            data.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.nombre;
                select.appendChild(option);
            });
        });
    }

    function bindTableButtons() {
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const obj = JSON.parse(this.dataset.bsObj);
                document.getElementById('mod_id').value          = obj.id;
                document.getElementById('mod_nombre').value      = obj.nombre;
                document.getElementById('mod_descripcion').value = obj.descripcion ?? '';
                clearFieldErrors('formModificar');

                cargarCategoriasPadre('mod_parent_id', obj.id).then(() => {
                    document.getElementById('mod_parent_id').value = obj.parent_id ?? '';
                });
            });
        });

        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const obj = JSON.parse(this.dataset.bsObj);
                document.getElementById('del_id').value             = obj.id;
                document.getElementById('del_nombre_display').textContent = obj.nombre;
            });
        });
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => recargar(), 400);
    });

    document.getElementById('clearFilters').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        recargar();
    });

    document.getElementById('formRegistrar').addEventListener('submit', function (e) {
        e.preventDefault();
        clearFieldErrors('formRegistrar');
        const data = new FormData(this);
        fetch(routes.registrar, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: data,
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('registrar')).hide();
                this.reset();
                recargar();
                Swal.fire({ icon: 'success', title: '¡Listo!', text: res.msg, timer: 2000, showConfirmButton: false });
            } else if (res.errors) {
                showFieldErrors('reg', res.errors);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
            }
        });
    });

    document.getElementById('formModificar').addEventListener('submit', function (e) {
        e.preventDefault();
        clearFieldErrors('formModificar');
        const data = new FormData(this);
        data.append('_method', 'PUT');
        fetch(routes.modificar, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: data,
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalModificar')).hide();
                recargar();
                Swal.fire({ icon: 'success', title: '¡Actualizado!', text: res.msg, timer: 2000, showConfirmButton: false });
            } else if (res.errors) {
                showFieldErrors('mod', res.errors);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
            }
        });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', function () {
        const id = document.getElementById('del_id').value;
        fetch(routes.eliminar + '?id=' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(res => {
            bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
            if (res.success) {
                recargar();
                Swal.fire({ icon: 'success', title: 'Eliminado', text: res.msg, timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'No se puede eliminar', text: res.msg });
            }
        });
    });

    bindTableButtons();
    bindPagination();
})();
</script>
@endpush
@endsection
