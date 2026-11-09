@extends('admin.dashboard')

@section('admin')
    <div class="container-fluid py-4">
        {{-- Header con datos del cliente - Diseño elegante --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 rounded-4 overflow-hidden" style="box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="position-relative">
                        {{-- Banner decorativo --}}
                        <div class="profile-banner" style="height: 120px; background: linear-gradient(135deg, #1a2d4c 0%, #0d9488 50%, #6366f1 100%); position: relative; overflow: hidden;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.08) 0%, transparent 50%);"></div>
                            <div class="position-absolute bottom-0 start-0 w-100" style="height: 40px; background: linear-gradient(to top, rgba(255,255,255,0.1), transparent);"></div>
                        </div>
                        
                        {{-- Avatar flotante --}}
                        <div class="position-absolute" style="top: 70px; left: 40px;">
                            <div class="avatar-profile" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 5px solid white; box-shadow: 0 8px 25px rgba(0,0,0,0.2);">
                                <i class="ri-user-star-line text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="position-absolute bottom-0 end-0" style="width: 32px; height: 32px; background: #10b981; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-check-line text-white" style="font-size: 0.9rem;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body pt-5 px-4 pb-4">
                        <div class="row align-items-end mb-3">
                            <div class="col-md-9 ps-5">
                                <h3 class="fw-bold mb-2" style="color: #1e293b;">
                                    {{ $cliente->persona->nombres }} {{ $cliente->persona->apellido_paterno }} {{ $cliente->persona->apellido_materno }}
                                </h3>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span style="display: inline-flex; align-items: center; gap: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 30px; color: white; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                        <i class="ri-shield-user-line" style="font-size: 1.2rem; color: white;"></i>
                                        <span style="color: white;">Cliente</span>
                                    </span>
                                    <span style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.08) 100%); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 25px;">
                                        <i class="ri-id-card-line" style="font-size: 1.1rem; color: #ef4444;"></i>
                                        <span style="color: #ef4444; font-weight: 600; font-size: 0.82rem;">CI: {{ $cliente->persona->carnet ?? 'N/A' }}</span>
                                    </span>
                                </div>
                                @if ($cliente->persona->correo)
                                <div class="mt-2">
                                    <span style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 20px;">
                                        <i class="ri-mail-line" style="font-size: 1rem; color: #d97706;"></i>
                                        <span style="color: #d97706; font-weight: 500; font-size: 0.82rem;">{{ $cliente->persona->correo }}</span>
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.clientes.listar') }}" class="btn btn-outline-secondary rounded-pill px-3">
                                        <i class="ri-arrow-go-back-line me-1"></i>Volver
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Info adicional en cards --}}
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(16,185,129,0.02) 100%); border: 1px solid rgba(16,185,129,0.15); border-radius: 16px;">
                                    <i class="ri-smartphone-line d-block" style="color: #10b981; font-size: 2rem;"></i>
                                    <div class="small text-muted fw-medium mt-2" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Celular</div>
                                    <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">{{ $cliente->persona->celular ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(59,130,246,0.02) 100%); border: 1px solid rgba(59,130,246,0.15); border-radius: 16px;">
                                    <i class="ri-phone-line d-block" style="color: #3b82f6; font-size: 2rem;"></i>
                                    <div class="small text-muted fw-medium mt-2" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Teléfono</div>
                                    <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">{{ $cliente->persona->telefono ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(236,72,153,0.08) 0%, rgba(236,72,153,0.02) 100%); border: 1px solid rgba(236,72,153,0.15); border-radius: 16px;">
                                    <i class="ri-team-line d-block" style="color: #ec4899; font-size: 2rem;"></i>
                                    <div class="small text-muted fw-medium mt-2" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Hijos</div>
                                    <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">{{ $cliente->parentescos->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(139,92,246,0.08) 0%, rgba(139,92,246,0.02) 100%); border: 1px solid rgba(139,92,246,0.15); border-radius: 16px;">
                                    <i class="ri-calendar-check-line d-block" style="color: #8b5cf6; font-size: 2rem;"></i>
                                    <div class="small text-muted fw-medium mt-2" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Suscripciones</div>
                                    <div class="fw-bold" style="font-size: 1rem; color: #1e293b;">{{ $cliente->suscripciones->count() }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if ($cliente->persona->direccion)
                        <div class="mt-3">
                            <div class="info-chip-full">
                                <i class="ri-map-pin-line text-danger"></i>
                                <span>{{ $cliente->persona->direccion }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Pestañas de información - Diseño elegante --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 rounded-4" style="box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);">
                        <ul class="nav nav-pills" id="seguimientoTabs" role="tablist">
                            @can('clientes.hijos.listar')
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link active rounded-pill px-4 py-2" id="hijos-tab" data-bs-toggle="tab" data-bs-target="#hijos" type="button" role="tab">
                                    <i class="ri-baby-line me-1"></i> Hijos
                                    <span class="badge bg-white text-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $cliente->parentescos->count() }}</span>
                                </button>
                            </li>
                            @endcan
                            @can('suscripciones.listar')
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link {{ auth()->user()->cannot('clientes.hijos.listar') ? 'active' : '' }} rounded-pill px-4 py-2" id="suscripciones-tab" data-bs-toggle="tab" data-bs-target="#suscripciones" type="button" role="tab">
                                    <i class="ri-calendar-check-line me-1"></i> Suscripciones
                                    <span class="badge bg-white text-success rounded-pill ms-1" style="font-size: 0.7rem;">{{ $suscripcionesActivas->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link rounded-pill px-4 py-2" id="suscripciones-concluidas-tab" data-bs-toggle="tab" data-bs-target="#suscripciones-concluidas" type="button" role="tab">
                                    <i class="ri-checkbox-circle-line me-1"></i> Concluidas
                                    <span class="badge bg-white text-secondary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $suscripcionesConcluidas->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 py-2" id="sesiones-tab" data-bs-toggle="tab" data-bs-target="#sesiones" type="button" role="tab">
                                    <i class="ri-calendar-event-line me-1"></i> Todas las Sesiones
                                </button>
                            </li>
                            @endcan
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content" id="seguimientoTabContent">
                            @can('clientes.hijos.listar')
                            <div class="tab-pane fade show active" id="hijos" role="tabpanel">
                                @include('admin.clientes.partials._hijos_tab', ['cliente' => $cliente])
                            </div>
                            @endcan
                            @can('suscripciones.listar')
                            <div class="tab-pane fade {{ auth()->user()->cannot('clientes.hijos.listar') ? 'show active' : '' }}" id="suscripciones" role="tabpanel">
                                @include('admin.clientes.partials._suscripciones_tab', ['suscripciones' => $suscripcionesActivas, 'tabId' => 'suscripcionesActivasPills'])
                            </div>
                            <div class="tab-pane fade" id="suscripciones-concluidas" role="tabpanel">
                                @include('admin.clientes.partials._suscripciones_tab', ['suscripciones' => $suscripcionesConcluidas, 'tabId' => 'suscripcionesConcluidasPills'])
                            </div>
                            <div class="tab-pane fade" id="sesiones" role="tabpanel">
                                @include('admin.clientes.partials._sesiones_tab', ['cliente' => $cliente])
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('clientes.hijos.registrar')
    {{-- Modal Registrar Hijo --}}
    <div class="modal fade" id="registrarHijoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <div>
                        <span class="badge bg-info-subtle text-info rounded-pill mb-2">
                            <i class="ri-baby-line me-1"></i>Nuevo Registro
                        </span>
                        <h5 class="modal-title fw-bold" id="registrarHijoLabel">
                            Registrar Nuevo Hijo
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formRegistrarHijo" method="POST" action="{{ route('admin.clientes.registrar-hijo', $cliente->id) }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">CI (opcional)</label>
                                <input type="text" name="carnet" class="form-control form-control-lg rounded-3 border-info" placeholder="Ej: 1234567">
                                <small class="form-text text-muted feedback-carnet"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" class="form-control form-control-lg rounded-3 border-info" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" name="apellido_paterno" class="form-control form-control-lg rounded-3 border-info" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control form-control-lg rounded-3 border-info">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Fecha Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_nacimiento" class="form-control form-control-lg rounded-3 border-info" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Relación con el cliente <span class="text-danger">*</span></label>
                                <input type="text" name="relacion" class="form-control form-control-lg rounded-3 border-info" value="Padre/Madre" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-soft-secondary btn-lg rounded-pill" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-info btn-lg rounded-pill" id="btnRegistrarHijo">
                            <i class="ri-save-3-line me-1"></i>
                            <span class="submit-text">Registrar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('style')
    <style>
        .profile-banner {
            transition: all 0.3s ease;
        }
        
        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            font-size: 0.82rem;
            color: #475569;
            transition: all 0.2s ease;
        }
        
        .info-chip:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }
        
        .info-chip i {
            font-size: 1rem;
        }
        
        .info-chip-full {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 0.82rem;
            color: #475569;
        }
        
        .badge-cliente {
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            padding: 10px 20px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .badge-cliente span {
            color: white !important;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge-cliente i {
            color: white !important;
            font-size: 1.2rem;
        }
        
        .badge-cliente:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .badge-carnet {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            padding: 10px 18px !important;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(239, 68, 68, 0.06) 100%) !important;
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 25px;
        }
        
        .badge-carnet span {
            color: #ef4444 !important;
            font-weight: 600;
            font-size: 0.82rem;
        }
        
        .badge-carnet i {
            color: #ef4444 !important;
            font-size: 1.1rem;
        }
        
        .badge-correo {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            padding: 8px 16px !important;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0.06) 100%) !important;
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-radius: 20px;
        }
        
        .badge-correo span {
            color: #d97706 !important;
            font-weight: 500;
            font-size: 0.82rem;
        }
        
        .badge-correo i {
            color: #d97706 !important;
            font-size: 1rem;
        }
        
        .stat-card {
            padding: 14px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        
        .nav-pills .nav-link {
            background: transparent;
            color: #64748b;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .nav-pills .nav-link.active .badge {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
        
        .avatar-profile {
            transition: transform 0.3s ease;
        }
        
        .avatar-profile:hover {
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .profile-banner {
                height: 80px !important;
            }
            
            .avatar-profile {
                width: 70px !important;
                height: 70px !important;
            }
            
            .avatar-profile i {
                font-size: 1.8rem !important;
            }
            
            .stat-card {
                padding: 10px 12px;
            }
            
            .stat-icon {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }
            
            .info-chip {
                font-size: 0.75rem;
                padding: 4px 10px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#formRegistrarHijo').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                const submitBtn = $('#btnRegistrarHijo');
                const originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Registrando...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('registrarHijoModal'));
                            modal.hide();
                            showToast('success', res.msg);
                            setTimeout(() => { location.reload(); }, 1500);
                        } else {
                            showToast('error', res.msg || 'Error al registrar el hijo');
                            submitBtn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al registrar el hijo. Intenta nuevamente.';
                        if (xhr.responseJSON?.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMsg = errors.join('<br>');
                        } else if (xhr.responseJSON?.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showToast('error', errorMsg);
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            function showToast(type, message) {
                const config = {
                    success: { icon: 'ri-checkbox-circle-fill', bgClass: 'bg-success', title: 'Éxito' },
                    error: { icon: 'ri-close-circle-fill', bgClass: 'bg-danger', title: 'Error' },
                    warning: { icon: 'ri-alert-fill', bgClass: 'bg-warning', title: 'Advertencia' },
                    info: { icon: 'ri-information-fill', bgClass: 'bg-info', title: 'Información' }
                };
                const toastConfig = config[type] || config.info;
                const toastId = 'toast-' + Date.now();
                const toastHtml = `
                <div id="${toastId}" class="toast ${toastConfig.bgClass} text-white shadow-lg" role="alert">
                    <div class="toast-header ${toastConfig.bgClass} text-white border-bottom-0">
                        <i class="${toastConfig.icon} me-2"></i>
                        <strong class="me-auto">${toastConfig.title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body d-flex align-items-center">
                        <i class="${toastConfig.icon} me-2 fs-5"></i>
                        <span>${message}</span>
                    </div>
                </div>`;

                let container = document.getElementById('toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    document.body.appendChild(container);
                }

                container.insertAdjacentHTML('afterbegin', toastHtml);
                const toastElement = document.getElementById(toastId);
                new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 }).show();
                toastElement.addEventListener('hidden.bs.toast', function() { this.remove(); });
            }
        });
    </script>
@endpush
