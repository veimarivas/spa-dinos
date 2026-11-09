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
                            <i class="ri-money-dollar-circle-line me-2"></i>Registrar Nuevo Pago
                        </h4>
                        <p class="text-white-50 mb-0">Suscripción #{{ $suscripcion->id }} -
                            {{ $suscripcion->cliente->persona->nombres }}
                            {{ $suscripcion->cliente->persona->apellido_paterno }}</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('admin.suscripciones.show', $suscripcion->id) }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                            <i class="ri-arrow-go-back-line align-bottom me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner: suscripción ya pagada --}}
        @if ($saldoPendiente <= 0 && $precioEfectivo > 0)
            <div class="alert d-flex align-items-center gap-3 rounded-4 mb-4 border-0 shadow-sm"
                style="background:linear-gradient(135deg, rgba(25,135,84,.12), rgba(13,148,136,.05)); border-left:5px solid #198754 !important;">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width:48px;height:48px;background:rgba(25,135,84,.15);color:#198754;">
                    <i class="ri-check-double-line fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold" style="color:#198754;">¡Suscripción totalmente pagada!</h6>
                    <p class="mb-0 text-muted small">
                        Esta suscripción ya cubrió el monto total de
                        <strong>{{ number_format($precioEfectivo, 2) }} Bs.</strong>
                        Pagaste {{ number_format($totalPagado, 2) }} Bs.
                        @if ($totalDescuento > 0) y se aplicaron {{ number_format($totalDescuento, 2) }} Bs. de descuento. @endif
                        No se permiten nuevos pagos.
                    </p>
                </div>
                <a href="{{ route('admin.suscripciones.show', $suscripcion->id) }}"
                    class="btn rounded-pill px-4 text-white"
                    style="background:linear-gradient(135deg,#198754,#0d9488);border:none;">
                    <i class="ri-eye-line me-1"></i>Ver suscripción
                </a>
            </div>
        @endif

        <div class="row">
            <!-- Información de la Suscripción -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ri-information-line me-2"></i>Información de la Suscripción
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Cliente</label>
                            <div class="fw-semibold">{{ $suscripcion->cliente->persona->nombres }}
                                {{ $suscripcion->cliente->persona->apellido_paterno }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Plan</label>
                            <div class="fw-semibold">{{ $suscripcion->plan->nombre_plan }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Paquete</label>
                            <div class="fw-semibold">{{ $suscripcion->plan->paquete->nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Tarifa Aplicada</label>
                            <div>
                                @if ($tarifa === 'especial')
                                    <span class="badge rounded-pill px-3 py-2" style="background:#fef3c7;color:#b45309;border:1px solid #fcd34d;font-weight:600;">
                                        <i class="ri-star-fill me-1"></i>Tarifa Especial
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2" style="background:#f0fdf4;color:#0d9488;border:1px solid #5eead4;font-weight:600;">
                                        <i class="ri-sun-line me-1"></i>Tarifa Normal
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">
                                Precio Aplicado
                                @if ($tarifa === 'especial' && $precioEfectivo != ($suscripcion->plan->precio ?? 0))
                                    <span class="text-muted fw-normal" style="font-size:.7rem;">(precio especial)</span>
                                @endif
                            </label>
                            <div class="fw-semibold {{ $tarifa === 'especial' ? 'text-warning' : 'text-primary' }}">
                                {{ number_format($precioEfectivo, 2) }} Bs.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Total Pagado</label>
                            <div class="fw-semibold text-success">{{ number_format($totalPagado, 2) }} Bs.</div>
                        </div>
                        @if ($totalDescuento > 0)
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold">Total Descuentos</label>
                                <div class="fw-semibold text-danger">-{{ number_format($totalDescuento, 2) }} Bs.</div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Saldo Pendiente</label>
                            <div class="fw-semibold {{ $saldoPendiente > 0 ? 'text-danger' : 'text-success' }} fs-5">
                                {{ number_format($saldoPendiente, 2) }} Bs.
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $porcentajePagado = $precioEfectivo > 0 ? ($totalPagado / $precioEfectivo) * 100 : 0;
                        @endphp
                        <div class="mt-4">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Progreso de Pago</label>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $porcentajePagado }}%" aria-valuenow="{{ $porcentajePagado }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format($porcentajePagado, 1) }}% completado</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Pago -->
            <div class="col-lg-8">
                <form method="POST" action="{{ route('admin.suscripciones.pagos.store', $suscripcion->id) }}"
                    id="formPago">
                    @csrf
                    <div class="card border-0 shadow-lg rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="ri-bank-card-line me-2"></i>Detalles del Pago
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4">
                                    <i class="ri-error-warning-fill fs-4 me-3"></i>
                                    <div>
                                        <strong>Errores de validación:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="row g-4">
                                <!-- Tipo de Pago -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-bank-card-line me-1 text-primary"></i>Tipo de Pago
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipo_pago" id="tipo_pago"
                                        class="form-select form-select-lg rounded-3 border-2 @error('tipo_pago') is-invalid @enderror"
                                        required>
                                        <option value="">-- Seleccionar --</option>
                                        <option value="efectivo" {{ old('tipo_pago') == 'efectivo' ? 'selected' : '' }}>💵
                                            Efectivo</option>
                                        <option value="qr" {{ old('tipo_pago') == 'qr' ? 'selected' : '' }}>📱 QR
                                        </option>
                                        <option value="parcial" {{ old('tipo_pago') == 'parcial' ? 'selected' : '' }}>🔀
                                            Parcial (Efectivo + QR)</option>
                                    </select>
                                    @error('tipo_pago')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Campos para Efectivo o QR (único monto) -->
                                <div class="col-md-6" id="campo-monto-unico">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto a Pagar (Bs.)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="number" step="0.01" name="monto" id="monto_unico"
                                            class="form-control form-control-lg rounded-3 border-2 @error('monto') is-invalid @enderror"
                                            placeholder="0.00" max="{{ $saldoPendiente }}" value="{{ old('monto') }}">
                                        <span
                                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">Bs.</span>
                                    </div>
                                    <small class="text-muted">Máximo: {{ number_format($saldoPendiente, 2) }} Bs.</small>
                                    @error('monto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Campos para Pago Parcial (2 montos) -->
                                <div class="col-md-6" id="campo-monto-efectivo" style="display: none;">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-money-dollar-circle-line me-1 text-success"></i>Monto en Efectivo (Bs.)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="number" step="0.01" name="monto_efectivo" id="monto_efectivo"
                                            class="form-control form-control-lg rounded-3 border-2 @error('monto_efectivo') is-invalid @enderror"
                                            placeholder="0.00" value="{{ old('monto_efectivo') }}">
                                        <span
                                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">Bs.</span>
                                    </div>
                                    @error('monto_efectivo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6" id="campo-monto-qr" style="display: none;">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-qr-code-line me-1 text-info"></i>Monto en QR (Bs.)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="number" step="0.01" name="monto_qr" id="monto_qr"
                                            class="form-control form-control-lg rounded-3 border-2 @error('monto_qr') is-invalid @enderror"
                                            placeholder="0.00" value="{{ old('monto_qr') }}">
                                        <span
                                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">Bs.</span>
                                    </div>
                                    @error('monto_qr')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mensaje de validación de monto -->
                                <div class="col-12" id="montoErrorMsg" style="display:none;">
                                    <div class="rounded-3 px-3 py-2 d-flex align-items-center gap-2"
                                        style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.25);">
                                        <i class="ri-error-warning-line text-danger"></i>
                                        <span class="small text-danger fw-semibold" id="montoErrorText"></span>
                                    </div>
                                </div>

                                <!-- Descuento -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-percent-line me-1 text-warning"></i>Descuento (Bs.)
                                    </label>
                                    <div class="position-relative">
                                        <input type="number" step="0.01" name="descuento" id="descuento"
                                            class="form-control form-control-lg rounded-3 border-2 @error('descuento') is-invalid @enderror"
                                            placeholder="0.00" value="{{ old('descuento', 0) }}">
                                        <span
                                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">Bs.</span>
                                    </div>
                                    @error('descuento')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fecha de Pago -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-calendar-line me-1 text-info"></i>Fecha de Pago
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="fecha_pago"
                                        class="form-control form-control-lg rounded-3 border-2 @error('fecha_pago') is-invalid @enderror"
                                        value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                                    @error('fecha_pago')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Cobrador -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <i class="ri-user-check-line me-1 text-secondary"></i>Cobrado por
                                    </label>
                                    <div class="form-control form-control-lg rounded-3 bg-light d-flex align-items-center gap-2">
                                        <i class="ri-account-circle-line text-success"></i>
                                        <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen del Pago -->
                            <div class="alert alert-info d-flex align-items-center rounded-3 mt-4" role="alert">
                                <i class="ri-information-fill fs-4 me-3 text-info"></i>
                                <div>
                                    <strong>Resumen del Pago:</strong>
                                    <p class="mb-0 small">Total a registrar: <span class="fw-bold text-success"
                                            id="totalRegistrar">0.00</span> Bs. |
                                        Saldo restante después del pago: <span class="fw-bold text-danger"
                                            id="saldoRestante">{{ number_format($saldoPendiente, 2) }}</span> Bs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.suscripciones.show', $suscripcion->id) }}"
                                    class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                    <i class="ri-close-circle-line align-bottom me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm"
                                    id="btnSubmit" disabled>
                                    <i class="ri-check-line align-bottom me-1"></i> Registrar Pago
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const saldoPendiente = {{ $saldoPendiente }};

            // Mostrar/ocultar campos según tipo de pago
            function toggleCamposPago() {
                const tipoPago = $('#tipo_pago').val();

                if (tipoPago === 'parcial') {
                    // Mostrar campos de efectivo y QR
                    $('#campo-monto-unico').hide();
                    $('#campo-monto-efectivo').show();
                    $('#campo-monto-qr').show();

                    // Hacer requeridos los campos de parcial
                    $('#monto_efectivo').prop('required', true);
                    $('#monto_qr').prop('required', true);
                    $('#monto_unico').prop('required', false).val('');

                } else if (tipoPago === 'efectivo' || tipoPago === 'qr') {
                    // Mostrar campo único
                    $('#campo-monto-unico').show();
                    $('#campo-monto-efectivo').hide();
                    $('#campo-monto-qr').hide();

                    // Hacer requerido el campo único
                    $('#monto_unico').prop('required', true);
                    $('#monto_efectivo').prop('required', false).val('');
                    $('#monto_qr').prop('required', false).val('');

                } else {
                    // Ocultar todos
                    $('#campo-monto-unico').hide();
                    $('#campo-monto-efectivo').hide();
                    $('#campo-monto-qr').hide();

                    $('#monto_unico').prop('required', false);
                    $('#monto_efectivo').prop('required', false);
                    $('#monto_qr').prop('required', false);
                }

                actualizarTotales();
            }

            // Actualizar totales y validar monto
            function actualizarTotales() {
                const tipoPago = $('#tipo_pago').val();
                let totalPago = 0;
                let errorMsg  = '';

                if (tipoPago === 'parcial') {
                    const ef = parseFloat($('#monto_efectivo').val()) || 0;
                    const qr = parseFloat($('#monto_qr').val()) || 0;
                    totalPago = ef + qr;
                    if (totalPago > 0 && ef <= 0 && qr <= 0) {
                        errorMsg = 'Debes ingresar al menos un monto mayor a 0.';
                    }
                } else {
                    totalPago = parseFloat($('#monto_unico').val()) || 0;
                }

                const descuento      = parseFloat($('#descuento').val()) || 0;
                const totalAplicado  = totalPago + descuento;
                const saldoRestante  = saldoPendiente - totalAplicado;

                $('#totalRegistrar').text(totalPago.toFixed(2));
                $('#saldoRestante').text(Math.max(0, saldoRestante).toFixed(2));

                if (saldoRestante <= 0 && totalAplicado > 0) {
                    $('#saldoRestante').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#saldoRestante').removeClass('text-success').addClass('text-danger');
                }

                // Validar monto
                if (totalAplicado > saldoPendiente) {
                    errorMsg = 'El monto + descuento (' + totalAplicado.toFixed(2) + ' Bs.) supera el saldo pendiente de ' + saldoPendiente.toFixed(2) + ' Bs.';
                } else if (totalPago <= 0 && tipoPago) {
                    errorMsg = 'El monto debe ser mayor a 0.';
                }

                if (errorMsg) {
                    $('#montoErrorText').text(errorMsg);
                    $('#montoErrorMsg').show();
                    $('#btnSubmit').prop('disabled', true);
                } else if (totalPago > 0 && tipoPago) {
                    $('#montoErrorMsg').hide();
                    $('#btnSubmit').prop('disabled', false);
                } else {
                    $('#montoErrorMsg').hide();
                    $('#btnSubmit').prop('disabled', true);
                }
            }

            // Evento cambio de tipo de pago
            $('#tipo_pago').on('change', function () {
                toggleCamposPago();
                actualizarTotales();
            });

            // Eventos para actualizar totales
            $('#monto_unico, #monto_efectivo, #monto_qr, #descuento').on('input', actualizarTotales);

            // Doble seguridad en submit
            $('#formPago').on('submit', function(e) {
                if ($('#btnSubmit').prop('disabled')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Inicializar
            toggleCamposPago();
            actualizarTotales();

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
