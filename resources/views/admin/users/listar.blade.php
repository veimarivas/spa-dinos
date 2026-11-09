@extends('admin.dashboard')
@section('admin')
<div class="container-fluid">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between rounded-4 p-4 shadow-sm"
                style="background:linear-gradient(135deg,#1a2d4c 0%,#0d9488 100%);">
                <div>
                    <h4 class="mb-1 fw-bold text-white">
                        <i class="ri-shield-user-line me-2"></i>Gestión de Usuarios
                    </h4>
                    <p class="text-white-50 mb-0">Administra cuentas, roles y accesos al sistema</p>
                </div>
                <div class="mt-2 mt-sm-0">
                    <span class="badge rounded-pill px-3 py-2"
                        style="background:rgba(255,255,255,.15);color:#fff;font-size:.8rem;">
                        <i class="ri-group-line me-1"></i>{{ $users->total() }} usuarios
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text border-0 rounded-start-3"
                                    style="background:linear-gradient(135deg,#1a2d4c,#0d9488);">
                                    <i class="ri-search-line text-white"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-0 shadow-none"
                                    placeholder="Buscar por nombre, correo o carnet..." style="background:#f8fafc;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="estadoFilter" class="form-select border-0 shadow-none" style="background:#f8fafc;">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100 rounded-3">
                                <i class="ri-refresh-line me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold" style="color:#1a2d4c;">
                        <i class="ri-list-unordered me-2" style="color:#0d9488;"></i>Lista de Usuarios
                    </h6>
                    <small class="text-muted">
                        Mostrando <span id="showing-count">{{ $users->firstItem() ?? 0 }}</span>–<span id="to-count">{{ $users->lastItem() ?? 0 }}</span>
                        de <span id="total-count">{{ $users->total() ?? 0 }}</span>
                    </small>
                </div>
                <div class="card-body p-0">
                    <div id="results-container">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background:rgba(26,45,76,.04);">
                                    <tr>
                                        <th class="ps-4" width="5%">N°</th>
                                        <th width="8%">Foto</th>
                                        <th width="25%">Persona</th>
                                        <th width="20%">Correo</th>
                                        <th width="18%">Rol</th>
                                        <th width="10%">Estado</th>
                                        <th class="text-center" width="14%">Acciones</th>
                                    </tr>
                                </thead>
                                @include('admin.users.partials.table-body')
                            </table>
                        </div>
                        <div class="d-flex justify-content-end p-3">
                            <div id="pagination-container">
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar Usuario --}}
<div class="modal fade" id="modalModificar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header p-4" style="background:linear-gradient(135deg,#1a2d4c,#0d9488);">
                <h5 class="modal-title fw-bold text-white">
                    <i class="ri-edit-line me-2"></i>Modificar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="updateForm">
                    @csrf
                    <input type="hidden" name="id" id="userId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_edicion" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_edicion" name="email" required>
                            <small id="feedback_email_edicion" class="form-text"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_edicion" name="role" required>
                                <option value="">Seleccionar rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado_edicion" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="updateForm" id="btn-actualizar-usuario"
                    class="btn btn-primary rounded-3" disabled>
                    <i class="ri-save-line me-1"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reset Contraseña --}}
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header p-4" style="background:linear-gradient(135deg,#b45309,#d97706);">
                <h5 class="modal-title fw-bold text-white">
                    <i class="ri-key-line me-2"></i>Reiniciar Contraseña
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="rounded-3 p-3 mb-3 d-flex align-items-start gap-3"
                    style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);">
                    <i class="ri-shield-keyhole-line fs-4 mt-1" style="color:#d97706;"></i>
                    <div>
                        <div class="fw-semibold small" style="color:#92400e;">Nueva contraseña = Carnet de identidad</div>
                        <div class="small text-muted mt-1">La contraseña del usuario se reiniciará a su número de carnet. El usuario deberá cambiarla en su próximo inicio de sesión.</div>
                    </div>
                </div>
                <form id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="id" id="userIdReset">
                </form>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="resetPasswordForm" id="btnConfirmarReset"
                    class="btn btn-warning rounded-3">
                    <i class="ri-key-line me-1"></i>Reiniciar contraseña
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Resultado Contraseña --}}
<div class="modal fade" id="modalPasswordResult" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header p-4" style="background:linear-gradient(135deg,#198754,#0d9488);">
                <h5 class="modal-title fw-bold text-white">
                    <i class="ri-checkbox-circle-line me-2"></i>Contraseña Reiniciada
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:64px;height:64px;background:rgba(25,135,84,.12);">
                        <i class="ri-key-2-line fs-2" style="color:#198754;"></i>
                    </div>
                    <div class="small text-muted">Comparte estos datos con el usuario</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Usuario (correo):</label>
                    <input type="text" class="form-control" id="usernameResult" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Nueva contraseña (carnet):</label>
                    <div class="input-group">
                        <input type="text" class="form-control fw-bold" id="passwordResult" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyPasswordBtn"
                            title="Copiar contraseña">
                            <i class="ri-clipboard-line"></i>
                        </button>
                    </div>
                </div>
                <div class="rounded-3 p-3 small"
                    style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);color:#92400e;">
                    <i class="ri-alert-line me-1"></i>
                    Solicita al usuario que cambie esta contraseña en su primer inicio de sesión.
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .spin { animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .badge-group {
        background: rgba(13,148,136,.1); color:#0d9488;
        font-size:.75rem; padding:.35rem .65rem; margin:2px;
        border-radius:.375rem; display:inline-block;
    }
    .user-photo { width:40px; height:40px; object-fit:cover; border-radius:50%; }
</style>

{{-- Modal Credenciales --}}
<div class="modal fade" id="modalCredenciales" tabindex="-1" aria-hidden="true">
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
                    Se enviará el siguiente mensaje a <strong id="credNombre"></strong> vía WhatsApp.
                </p>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Número de celular:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-phone-line"></i></span>
                        <input type="text" id="credCelular" class="form-control" placeholder="Sin celular registrado">
                    </div>
                    <small class="text-muted">Puedes editar el número si es necesario.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Vista previa del mensaje:</label>
                    <textarea id="credMensaje" class="form-control" rows="6" style="font-size:.85rem;resize:none;"></textarea>
                </div>
                <div class="rounded-3 p-3 small"
                    style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);color:#92400e;">
                    <i class="ri-alert-line me-1"></i>
                    Si el usuario ya cambió su contraseña, esta puede no ser válida. Reinicia primero si es necesario.
                </div>
            </div>
            <div class="modal-footer bg-light p-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnEnviarWhatsapp" href="#" target="_blank"
                    class="btn fw-semibold rounded-3 px-4 text-white"
                    style="background:linear-gradient(135deg,#25d366,#128c7e);">
                    <i class="ri-whatsapp-line me-1"></i>Enviar por WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    let debounceTimer;

    // ── BÚSQUEDA / FILTROS ──────────────────────────────────────────────
    function loadResults(search = '', estado = '') {
        $.ajax({
            url: '{{ route('admin.users.listar') }}',
            method: 'GET',
            data: { search, estado },
            dataType: 'json',
            success: function (r) {
                $('#results-container .table-responsive table tbody').replaceWith(r.html);
                $('#pagination-container').html(r.pagination);
                $('#showing-count').text(r.from || 0);
                $('#to-count').text(r.to || 0);
                $('#total-count').text(r.total || 0);
            },
            error: function () { showToast('danger', 'Error al cargar los resultados'); }
        });
    }

    $('#searchInput').on('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => loadResults($(this).val().trim(), $('#estadoFilter').val()), 300);
    });
    $('#estadoFilter').on('change', function () {
        loadResults($('#searchInput').val().trim(), $(this).val());
    });
    $('#clearFilters').on('click', function () {
        $('#searchInput').val(''); $('#estadoFilter').val(''); loadResults();
    });
    $(document).on('click', '#pagination-container .pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url) return;
        const search = $('#searchInput').val().trim();
        const estado = $('#estadoFilter').val();
        $.ajax({
            url, method: 'GET', data: { search, estado }, dataType: 'json',
            success: function (r) {
                $('#results-container .table-responsive table tbody').replaceWith(r.html);
                $('#pagination-container').html(r.pagination);
                $('#showing-count').text(r.from || 0);
                $('#to-count').text(r.to || 0);
                $('#total-count').text(r.total || 0);
            }
        });
    });

    // ── EDITAR USUARIO ──────────────────────────────────────────────────
    function verificarEmailEdicion() {
        const email = $('#email_edicion').val().trim();
        const fb = $('#feedback_email_edicion');
        const userId = $('#userId').val();
        fb.removeClass('text-success text-danger').text('');
        if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
            $('#btn-actualizar-usuario').prop('disabled', true);
            return;
        }
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: '{{ route('admin.users.verificar-email') }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', email, id: userId },
                success: function (res) {
                    if (res.exists) {
                        fb.addClass('text-danger').text('⚠️ Este correo ya está registrado');
                        $('#btn-actualizar-usuario').prop('disabled', true);
                    } else {
                        fb.addClass('text-success').text('✅ Correo disponible');
                        chequearFormEdicion();
                    }
                },
                error: function () {
                    fb.addClass('text-danger').text('❌ Error al verificar');
                    $('#btn-actualizar-usuario').prop('disabled', true);
                }
            });
        }, 300);
    }

    function chequearFormEdicion() {
        const ok = $('#feedback_email_edicion').hasClass('text-success')
            && $('#role_edicion').val()
            && $('#estado_edicion').val()
            && $('#name_edicion').val().trim();
        $('#btn-actualizar-usuario').prop('disabled', !ok);
    }

    $(document).on('click', '.editBtn', function () {
        const userId = $(this).data('id');
        $.ajax({
            url: '{{ route('admin.users.obtener-data') }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', id: userId },
            success: function (res) {
                $('#userId').val(res.user.id);
                $('#name_edicion').val(res.user.name);
                $('#email_edicion').val(res.user.email);
                $('#role_edicion').val(res.user.roles.length ? res.user.roles[0].name : '');
                $('#estado_edicion').val(res.user.estado);
                $('#feedback_email_edicion').removeClass('text-success text-danger').text('');
                verificarEmailEdicion();
            },
            error: function () { showToast('danger', 'No se pudo cargar los datos del usuario.'); }
        });
    });

    $('#email_edicion').on('input', verificarEmailEdicion);
    $('#name_edicion, #role_edicion, #estado_edicion').on('input change', chequearFormEdicion);

    $('#updateForm').submit(function (e) {
        e.preventDefault();
        if (!$('#feedback_email_edicion').hasClass('text-success')) {
            showToast('warning', 'Verifica el correo electrónico primero.');
            return;
        }
        const btn = $('#btn-actualizar-usuario');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Actualizando...');
        $.ajax({
            url: '{{ route('admin.users.actualizar') }}',
            type: 'POST', data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    showToast('success', res.msg);
                    $('#modalModificar').modal('hide');
                    loadResults($('#searchInput').val().trim(), $('#estadoFilter').val());
                } else {
                    showToast('danger', res.msg || 'Error al actualizar');
                    btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Actualizar');
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.msg || xhr.responseJSON?.errors?.email?.[0] || 'Error al actualizar';
                showToast('danger', msg);
                btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Actualizar');
            }
        });
    });

    // ── RESET CONTRASEÑA ────────────────────────────────────────────────
    $(document).on('click', '.resetBtn', function () {
        $('#userIdReset').val($(this).data('id'));
    });

    $('#resetPasswordForm').submit(function (e) {
        e.preventDefault();
        const btn = $('#btnConfirmarReset');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Reiniciando...');
        $.ajax({
            url: '{{ route('admin.users.reset-password') }}',
            type: 'POST', data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#usernameResult').val(res.username);
                    $('#passwordResult').val(res.password);
                    $('#modalResetPassword').modal('hide');
                    $('#modalPasswordResult').modal('show');
                } else {
                    showToast('danger', res.msg || 'Error al reiniciar contraseña');
                }
                btn.prop('disabled', false).html('<i class="ri-key-line me-1"></i>Reiniciar contraseña');
            },
            error: function () {
                showToast('danger', 'Error al reiniciar contraseña');
                btn.prop('disabled', false).html('<i class="ri-key-line me-1"></i>Reiniciar contraseña');
            }
        });
    });

    $(document).on('click', '#copyPasswordBtn', function () {
        navigator.clipboard.writeText($('#passwordResult').val())
            .then(() => showToast('success', 'Contraseña copiada al portapapeles.', 2000))
            .catch(() => showToast('danger', 'Error al copiar'));
    });

    // ── TOAST ───────────────────────────────────────────────────────────
    function showToast(type, message, delay = 5000) {
        let tc = $('#toast-container');
        if (!tc.length) {
            tc = $('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999"></div>');
            $('body').append(tc);
        }
        const colors = { success:'#198754', danger:'#dc3545', warning:'#d97706', info:'#0d9488' };
        const toast = $(`
            <div class="toast align-items-center border-0 text-white" role="alert" style="background:${colors[type]??'#1a2d4c'};">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        tc.append(toast);
        new bootstrap.Toast(toast[0], { autohide: true, delay }).show();
        toast.on('hidden.bs.toast', function () { $(this).remove(); });
    }

    chequearFormEdicion();

    // ── MODAL CREDENCIALES WHATSAPP ────────────────────────────────────
    function buildWaUrl() {
        const celular = $('#credCelular').val().replace(/\D/g, '');
        const msg     = encodeURIComponent($('#credMensaje').val());
        const url     = celular ? `https://wa.me/591${celular}?text=${msg}` : `https://wa.me/?text=${msg}`;
        $('#btnEnviarWhatsapp').attr('href', url);
    }

    $(document).on('click', '.credencialesBtn', function () {
        const btn    = $(this);
        const nombre = btn.data('nombre');
        const email  = btn.data('email');
        const carnet = btn.data('carnet') || '(sin carnet)';
        const cel    = btn.data('celular') || '';
        const appUrl = '{{ url('/') }}';

        $('#credNombre').text(nombre);
        $('#credCelular').val(cel);

        const msg = `Hola ${nombre} 👋\n\nTe comparto tus credenciales de acceso al sistema:\n\n📧 *Usuario:* ${email}\n🔑 *Contraseña:* ${carnet}\n🌐 *Ingresa en:* ${appUrl}\n\nSi ya cambiaste tu contraseña, usa la que tienes registrada.`;
        $('#credMensaje').val(msg);
        buildWaUrl();
    });

    $('#credCelular, #credMensaje').on('input', buildWaUrl);
    // ───────────────────────────────────────────────────────────────────
});
</script>
@endpush
