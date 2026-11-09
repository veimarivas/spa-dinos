@extends('admin.dashboard')

@section('admin')
    <div class="container-fluid py-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 rounded-4 overflow-hidden" style="box-shadow: 0 6px 24px rgba(0,0,0,0.06);">
                    <div class="position-relative">
                        <div class="profile-banner" style="height: 60px; background: linear-gradient(135deg, #0d9488 0%, #06b6d4 50%, #3b82f6 100%); position: relative; overflow: hidden;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.08) 0%, transparent 50%);"></div>
                        </div>
                        <div class="position-absolute" style="top: 25px; left: 30px;">
                            <div class="avatar-profile" style="width: 64px; height: 64px; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                                <i class="ri-baby-line text-white" style="font-size: 1.6rem;"></i>
                            </div>
                            <div class="position-absolute bottom-0 end-0" style="width: 24px; height: 24px; background: #06b6d4; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-star-smile-line text-white" style="font-size: .7rem;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body pt-4 px-3 pb-3">
                        <div class="row align-items-center mb-2">
                            <div class="col-md-9 ps-5">
                                <h5 class="fw-bold mb-1" style="color: #1e293b;">
                                    {{ $hijo->persona->nombres }} {{ $hijo->persona->apellido_paterno }} {{ $hijo->persona->apellido_materno }}
                                </h5>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 14px; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); border-radius: 20px; color: white; font-weight: 600; font-size: .75rem; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);">
                                        <i class="ri-baby-line" style="font-size: .9rem;"></i>
                                        Hijo
                                    </span>
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 14px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.08) 100%); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 20px;">
                                        <i class="ri-id-card-line" style="font-size: .85rem; color: #ef4444;"></i>
                                        <span style="color: #ef4444; font-weight: 600; font-size: .75rem;">CI: {{ $hijo->persona->carnet ?? 'N/A' }}</span>
                                    </span>
                                    @if ($hijo->persona->fecha_nacimiento)
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 14px; background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.08) 100%); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 20px;">
                                        <i class="ri-cake-line" style="font-size: .85rem; color: #22c55e;"></i>
                                        <span style="color: #16a34a; font-weight: 500; font-size: .75rem;">{{ \Carbon\Carbon::parse($hijo->persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.clientes.listar') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="ri-arrow-go-back-line me-1"></i>Volver
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="padding:10px;background: linear-gradient(135deg, rgba(6,182,212,0.08) 0%, rgba(6,182,212,0.02) 100%); border: 1px solid rgba(6,182,212,0.15); border-radius: 12px;">
                                    <i class="ri-calendar-event-line d-block" style="color: #06b6d4; font-size: 1.4rem;"></i>
                                    <div class="small text-muted fw-medium mt-1" style="font-size: .6rem; text-transform: uppercase; letter-spacing: .5px;">Sesiones</div>
                                    <div class="fw-bold" style="font-size: .85rem; color: #1e293b;">{{ $hijo->sesiones->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="padding:10px;background: linear-gradient(135deg, rgba(34,197,94,0.08) 0%, rgba(34,197,94,0.02) 100%); border: 1px solid rgba(34,197,94,0.15); border-radius: 12px;">
                                    <i class="ri-stack-line d-block" style="color: #22c55e; font-size: 1.4rem;"></i>
                                    <div class="small text-muted fw-medium mt-1" style="font-size: .6rem; text-transform: uppercase; letter-spacing: .5px;">Suscripciones</div>
                                    <div class="fw-bold" style="font-size: .85rem; color: #1e293b;">{{ $suscripcionesActivas->count() + $suscripcionesConcluidas->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="padding:10px;background: linear-gradient(135deg, rgba(168,85,247,0.08) 0%, rgba(168,85,247,0.02) 100%); border: 1px solid rgba(168,85,247,0.15); border-radius: 12px;">
                                    <i class="ri-user-star-line d-block" style="color: #a855f7; font-size: 1.4rem;"></i>
                                    <div class="small text-muted fw-medium mt-1" style="font-size: .6rem; text-transform: uppercase; letter-spacing: .5px;">Padres</div>
                                    <div class="fw-bold" style="font-size: .85rem; color: #1e293b;">{{ $hijo->parentescos->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card text-center" style="padding:10px;background: linear-gradient(135deg, rgba(234,179,8,0.08) 0%, rgba(234,179,8,0.02) 100%); border: 1px solid rgba(234,179,8,0.15); border-radius: 12px;">
                                    <i class="ri-file-text-line d-block" style="color: #eab308; font-size: 1.4rem;"></i>
                                    <div class="small text-muted fw-medium mt-1" style="font-size: .6rem; text-transform: uppercase; letter-spacing: .5px;">Historial</div>
                                    <div class="fw-bold" style="font-size: .85rem; color: #1e293b;">{{ $historial->sum(fn($g) => $g->count()) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if ($hijo->parentescos->count() > 0)
                        <div class="mt-2">
                            <div class="d-flex align-items-center flex-wrap gap-1">
                                <span class="badge rounded-pill px-2 py-1" style="font-size: .7rem; background: rgba(6,182,212,0.1); color: #06b6d4;">
                                    <i class="ri-family-line me-1"></i>Padres:
                                </span>
                                @foreach ($hijo->parentescos as $parentesco)
                                    <a href="{{ route('admin.clientes.seguimiento', $parentesco->cliente_id) }}" 
                                       class="btn btn-sm rounded-pill px-2 py-1"
                                       style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: white; font-size: .7rem; box-shadow: 0 2px 6px rgba(6,182,212,0.25);">
                                        <i class="ri-user-star-line me-1"></i>
                                        {{ $parentesco->cliente->persona->nombres }} {{ $parentesco->cliente->persona->apellido_paterno }}
                                        @if($parentesco->cliente->persona->celular)
                                            <span class="opacity-75 ms-1" style="font-size:.65rem;">{{ $parentesco->cliente->persona->celular }}</span>
                                        @endif
                                    </a>
                                @endforeach
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
                        <ul class="nav nav-pills" id="hijoTabs" role="tablist">
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link active rounded-pill px-4 py-2" id="suscripciones-tab" data-bs-toggle="tab" data-bs-target="#suscripciones" type="button" role="tab">
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
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link rounded-pill px-4 py-2" id="sesiones-tab" data-bs-toggle="tab" data-bs-target="#sesiones" type="button" role="tab">
                                    <i class="ri-calendar-event-line me-1"></i> Todas las Sesiones 
                                    <span class="badge bg-white text-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $hijo->sesiones->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 py-2" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab">
                                    <i class="ri-file-text-line me-1"></i> Historial Clínico
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content" id="hijoTabContent">
                            <div class="tab-pane fade show active" id="suscripciones" role="tabpanel">
                                @include('admin.hijos.partials._suscripciones_tab', ['suscripciones' => $suscripcionesActivas, 'tabId' => 'suscripcionesActivasPills'])
                            </div>
                            <div class="tab-pane fade" id="suscripciones-concluidas" role="tabpanel">
                                @include('admin.hijos.partials._suscripciones_tab', ['suscripciones' => $suscripcionesConcluidas, 'tabId' => 'suscripcionesConcluidasPills'])
                            </div>
                            <div class="tab-pane fade" id="sesiones" role="tabpanel">
                                @include('admin.hijos.partials._sesiones_tab', ['hijo' => $hijo])
                            </div>
                            <div class="tab-pane fade" id="historial" role="tabpanel">
                                @include('admin.hijos.partials._historial_tab', ['historial' => $historial, 'hijo' => $hijo])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .profile-banner {
            transition: all 0.3s ease;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }
        
        .nav-pills .nav-link {
            background: transparent;
            color: #64748b;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:hover {
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.05);
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
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
            
            .info-chip-full {
                font-size: 0.75rem;
                padding: 4px 10px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof bootstrap !== 'undefined') {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                    try { new bootstrap.Tooltip(el); } catch (e) {}
                });
            }
        });
    </script>
@endpush