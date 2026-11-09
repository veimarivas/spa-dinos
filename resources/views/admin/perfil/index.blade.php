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
                        <i class="ri-account-circle-line me-2"></i>Mi Perfil
                    </h4>
                    <p class="text-white-50 mb-0">Consulta tu información y gestiona tu contraseña de acceso</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Tarjeta de información --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    {{-- Avatar --}}
                    <div class="text-center mb-4">
                        @php
                            $foto = $user->persona?->fotografia
                                ? asset($user->persona->fotografia)
                                : ($user->persona?->sexo === 'Hombre'
                                    ? asset('frontend/assets/img/personal/hombre.png')
                                    : asset('frontend/assets/img/personal/mujer.png'));
                        @endphp
                        <div class="position-relative d-inline-block">
                            <img src="{{ $foto }}" alt="Foto de perfil"
                                class="rounded-circle border border-3"
                                style="width:96px;height:96px;object-fit:cover;border-color:rgba(13,148,136,.4)!important;">
                            <span class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:24px;height:24px;background:{{ $user->estado === 'Activo' ? '#198754' : '#dc3545' }};border:2px solid #fff;">
                            </span>
                        </div>
                        <h5 class="fw-bold mt-3 mb-0" style="color:#1a2d4c;">{{ $user->name }}</h5>
                        <div class="d-flex justify-content-center gap-1 mt-2 flex-wrap">
                            @foreach ($user->getRoleNames() as $rol)
                                <span class="badge rounded-pill px-3"
                                    style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.75rem;">
                                    {{ $rol }}
                                </span>
                            @endforeach
                        </div>
                        <span class="badge mt-2 rounded-pill {{ $user->estado === 'Activo' ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->estado }}
                        </span>
                    </div>

                    <hr style="border-color:rgba(13,148,136,.15);">

                    {{-- Datos personales --}}
                    @if ($user->persona)
                        @php $p = $user->persona; @endphp
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:rgba(13,148,136,.1);">
                                    <i class="ri-id-card-line" style="color:#0d9488;"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Carnet de identidad</div>
                                    <div class="fw-semibold" style="color:#1a2d4c;">{{ $p->carnet ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:rgba(13,148,136,.1);">
                                    <i class="ri-mail-line" style="color:#0d9488;"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Correo electrónico</div>
                                    <div class="fw-semibold" style="color:#1a2d4c;">{{ $user->email }}</div>
                                </div>
                            </div>
                            @if ($p->celular)
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:rgba(13,148,136,.1);">
                                    <i class="ri-phone-line" style="color:#0d9488;"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Celular</div>
                                    <div class="fw-semibold" style="color:#1a2d4c;">{{ $p->celular }}</div>
                                </div>
                            </div>
                            @endif
                            @if ($p->direccion)
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:rgba(13,148,136,.1);">
                                    <i class="ri-map-pin-line" style="color:#0d9488;"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Dirección</div>
                                    <div class="fw-semibold" style="color:#1a2d4c;">{{ $p->direccion }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center small">Sin datos personales registrados.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 px-4"
                    style="background:rgba(26,45,76,.03);">
                    <h6 class="mb-0 fw-bold" style="color:#1a2d4c;">
                        <i class="ri-lock-password-line me-2" style="color:#0d9488;"></i>Cambiar Contraseña
                    </h6>
                </div>
                <div class="card-body p-4">

                    {{-- Info carnet --}}
                    <div class="rounded-3 p-3 mb-4 d-flex align-items-start gap-3"
                        style="background:rgba(13,148,136,.06);border:1px solid rgba(13,148,136,.2);">
                        <i class="ri-information-line fs-5 mt-1" style="color:#0d9488;"></i>
                        <div class="small" style="color:#065f46;">
                            Si nunca cambiaste tu contraseña, la contraseña predeterminada es tu
                            <strong>número de carnet de identidad</strong>.
                        </div>
                    </div>

                    <form id="formCambiarPassword">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Contraseña actual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password_actual" name="password_actual"
                                    class="form-control" placeholder="Ingresa tu contraseña actual" required>
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                    data-target="#password_actual">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                            <small class="form-text text-danger" id="fb_actual"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nueva contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password_nuevo" name="password_nuevo"
                                    class="form-control" placeholder="Mínimo 6 caracteres" required>
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                    data-target="#password_nuevo">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-1"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Confirmar nueva contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password_nuevo_confirmation"
                                    name="password_nuevo_confirmation"
                                    class="form-control" placeholder="Repite la nueva contraseña" required>
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                    data-target="#password_nuevo_confirmation">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                            <small class="form-text" id="fb_confirm"></small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="btnCambiarPass"
                                class="btn btn-primary rounded-3 px-4" disabled>
                                <i class="ri-save-line me-1"></i>Guardar contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Toast container --}}
            <div id="perfilToastContainer"
                class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999;"></div>
        </div>
    </div>
</div>

<style>
    .spin { animation: spin .8s linear infinite; }
    @keyframes spin { to { transform:rotate(360deg); } }
    .strength-bar { height:5px; border-radius:5px; transition:width .3s,background .3s; }
</style>
@endsection

@push('script')
<script>
$(document).ready(function () {

    // ── TOGGLE MOSTRAR/OCULTAR CONTRASEÑA ───────────────────────────────
    $(document).on('click', '.toggle-pass', function () {
        const target = $($(this).data('target'));
        const icon   = $(this).find('i');
        if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            target.attr('type', 'password');
            icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    // ── FORTALEZA DE CONTRASEÑA ─────────────────────────────────────────
    $('#password_nuevo').on('input', function () {
        const val = $(this).val();
        const sb  = $('#passwordStrength');
        if (!val) { sb.html(''); chequearBtn(); return; }

        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { label:'Muy débil',  color:'#dc3545', w:'20%' },
            { label:'Débil',      color:'#fd7e14', w:'40%' },
            { label:'Regular',    color:'#ffc107', w:'60%' },
            { label:'Buena',      color:'#198754', w:'80%' },
            { label:'Muy fuerte', color:'#0d9488', w:'100%' },
        ];
        const lv = levels[Math.min(score - 1, 4)] ?? levels[0];
        sb.html(`
            <div style="background:#e9ecef;border-radius:5px;height:5px;">
                <div class="strength-bar" style="width:${lv.w};background:${lv.color};"></div>
            </div>
            <small style="color:${lv.color};">${lv.label}</small>
        `);
        chequearConfirm();
        chequearBtn();
    });

    $('#password_nuevo_confirmation').on('input', function () {
        chequearConfirm();
        chequearBtn();
    });
    $('#password_actual').on('input', function () {
        $('#fb_actual').text('');
        chequearBtn();
    });

    function chequearConfirm() {
        const n  = $('#password_nuevo').val();
        const nc = $('#password_nuevo_confirmation').val();
        const fb = $('#fb_confirm');
        if (!nc) { fb.text(''); return; }
        if (n === nc) {
            fb.removeClass('text-danger').addClass('text-success').html(
                '<i class="ri-checkbox-circle-line me-1"></i>Las contraseñas coinciden');
        } else {
            fb.removeClass('text-success').addClass('text-danger').html(
                '<i class="ri-error-warning-line me-1"></i>Las contraseñas no coinciden');
        }
    }

    function chequearBtn() {
        const actual = $('#password_actual').val().trim();
        const nuevo  = $('#password_nuevo').val();
        const conf   = $('#password_nuevo_confirmation').val();
        const ok = actual && nuevo.length >= 6 && nuevo === conf;
        $('#btnCambiarPass').prop('disabled', !ok);
    }

    // ── SUBMIT CAMBIAR CONTRASEÑA ───────────────────────────────────────
    $('#formCambiarPassword').submit(function (e) {
        e.preventDefault();
        const btn = $('#btnCambiarPass');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Guardando...');
        $('#fb_actual').text('');

        $.ajax({
            url: '{{ route('admin.perfil.cambiarPassword') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    mostrarToast('success', res.msg);
                    $('#formCambiarPassword')[0].reset();
                    $('#passwordStrength').html('');
                    $('#fb_confirm').text('');
                }
                btn.prop('disabled', true).html('<i class="ri-save-line me-1"></i>Guardar contraseña');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                const msg    = xhr.responseJSON?.msg;
                if (msg) {
                    $('#fb_actual').text(msg).addClass('text-danger');
                } else if (errors?.password_nuevo) {
                    mostrarToast('danger', errors.password_nuevo[0]);
                } else {
                    mostrarToast('danger', 'Error al cambiar la contraseña.');
                }
                btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Guardar contraseña');
            }
        });
    });

    // ── TOAST ───────────────────────────────────────────────────────────
    function mostrarToast(type, message) {
        const colors = { success:'#198754', danger:'#dc3545', warning:'#d97706' };
        const toast = $(`
            <div class="toast align-items-center border-0 text-white" role="alert"
                style="background:${colors[type]??'#1a2d4c'};">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        $('#perfilToastContainer').append(toast);
        new bootstrap.Toast(toast[0], { autohide: true, delay: 4000 }).show();
        toast.on('hidden.bs.toast', () => toast.remove());
    }
});
</script>
@endpush
