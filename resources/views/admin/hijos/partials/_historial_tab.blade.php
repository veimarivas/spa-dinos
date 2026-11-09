@if ($historial->isEmpty())
    <div class="text-center py-5">
        <div class="position-relative d-inline-block mb-4">
            <div class="avatar-xl">
                <div class="avatar-title bg-secondary bg-opacity-10 text-secondary rounded-circle" style="width: 100px; height: 100px;">
                    <i class="ri-file-text-line fs-2"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border: 2px solid white;">
                <i class="ri-inbox-line text-muted fs-6"></i>
            </div>
        </div>
        <h4 class="fw-bold text-muted mb-2">Sin historial clínico</h4>
        <p class="text-muted mb-0">El historial aparecerá aquí cuando el niño tenga sesiones realizadas.</p>
    </div>
@else
    <div class="d-flex flex-column gap-4">
        @foreach ($historial as $servicio => $sesiones)
            @php
                $conInforme = $sesiones->filter(fn($s) => $s->asistio_hijo && !empty($s->informe_sesion))->count();
                $sinInforme = $sesiones->filter(fn($s) => $s->asistio_hijo && empty($s->informe_sesion))->count();
                $noAsistio = $sesiones->filter(fn($s) => $s->asistio_hijo === false || $s->asistio_hijo === 0)->count();
                $total = $sesiones->count();
            @endphp

            {{-- Card principal del servicio --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;">
                {{-- Header con gradiente --}}
                <div class="card-header border-0 py-4 px-4" 
                    style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #06b6d4 100%); position: relative; overflow: hidden;">
                    <div class="position-absolute top-0 end-0" style="width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
                    <div class="position-absolute bottom-0 start-0" style="width: 100px; height: 100px; background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, transparent 70%); transform: translate(-30%, 30%);"></div>
                    
                    <div class="d-flex align-items-center justify-content-between position-relative">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-white bg-opacity-20 d-flex align-items-center justify-content-center shadow-sm" 
                                style="width: 56px; height: 56px; min-width: 56px;">
                                <i class="ri-heart-pulse-line text-white fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-white">
                                    <i class="ri-medicine-bottle-line me-2 opacity-75"></i>{{ $servicio }}
                                </h5>
                                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                                    <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2">
                                        <i class="ri-calendar-check-line me-1"></i>
                                        {{ $total }} {{ $total === 1 ? 'sesión' : 'sesiones' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Stats en el header --}}
                        <div class="d-flex gap-2">
                            <div class="text-center">
                                <div class="rounded-3 p-2" style="background: rgba(25,135,84,0.25); min-width: 60px;">
                                    <div class="fw-bold text-white fs-4">{{ $conInforme }}</div>
                                    <div class="small text-white-50">Con informe</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="rounded-3 p-2" style="background: rgba(245,158,11,0.25); min-width: 60px;">
                                    <div class="fw-bold text-white fs-4">{{ $sinInforme }}</div>
                                    <div class="small text-white-50">Sin informe</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="rounded-3 p-2" style="background: rgba(220,53,69,0.25); min-width: 60px;">
                                    <div class="fw-bold text-white fs-4">{{ $noAsistio }}</div>
                                    <div class="small text-white-50">No asistió</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Timeline de Sesiones --}}
                    <div class="position-relative">
                        {{-- Línea vertical decorativa --}}
                        <div class="position-absolute start-0 top-0 bottom-0" style="width: 3px; background: linear-gradient(to bottom, #0d9488, #06b6d4); border-radius: 3px; left: 19px;"></div>
                        
                        <div class="d-flex flex-column gap-4">
                            @foreach ($sesiones as $sesion)
                                @php
                                    $asistio = $sesion->asistio_hijo;
                                    $informe = $sesion->informe_sesion;

                                    if (!$asistio && $asistio !== null) {
                                        $markerBg = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                                        $markerIcon = 'ri-close-circle-fill';
                                        $accentColor = '#dc3545';
                                        $accentBg = 'rgba(220,53,69,0.08)';
                                        $statusLabel = 'No asistió';
                                    } elseif ($asistio && !empty($informe)) {
                                        $markerBg = 'linear-gradient(135deg, #0d9488 0%, #14b8a6 100%)';
                                        $markerIcon = 'ri-file-text-fill';
                                        $accentColor = '#0d9488';
                                        $accentBg = 'rgba(13,148,136,0.08)';
                                        $statusLabel = 'Con informe';
                                    } else {
                                        $markerBg = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                                        $markerIcon = 'ri-file-warning-fill';
                                        $accentColor = '#f59e0b';
                                        $accentBg = 'rgba(245,158,11,0.08)';
                                        $statusLabel = 'Sin informe';
                                    }
                                @endphp

                                <div class="d-flex gap-3 position-relative">
                                    {{-- Marcador grande y destacado --}}
                                    <div class="flex-shrink-0" style="z-index: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-lg"
                                            style="width: 42px; height: 42px; min-width: 42px; background: {{ $markerBg }}; border: 3px solid white;">
                                            <i class="{{ $markerIcon }} text-white fs-5"></i>
                                        </div>
                                    </div>

                                    {{-- Tarjeta de la sesión --}}
                                    <div class="card border-0 shadow-sm flex-grow-1 rounded-4" 
                                        style="background: linear-gradient(135deg, white 0%, #f8fafc 100%);">
                                        {{-- Header con borde de color --}}
                                        <div class="card-header border-0 py-3 px-4 rounded-top-4 d-flex align-items-center justify-content-between flex-wrap gap-2"
                                            style="background: {{ $accentBg }}; border-bottom: 2px solid {{ $accentColor }};">
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <span class="fw-bold" style="color: {{ $accentColor }}; font-size: 1.1rem;">
                                                    <i class="ri-calendar-line me-2"></i>
                                                    {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('dddd D [de] MMMM') }}
                                                </span>
                                                <span class="badge rounded-pill" style="background: {{ $accentColor }}; color: white; font-size: 0.75rem; padding: 6px 12px;">
                                                    <i class="ri-hashtag me-1"></i>Sesión {{ $sesion->n_sesion }}
                                                </span>
                                            </div>
                                            <span class="badge rounded-pill fs-6" style="background: white; color: {{ $accentColor }}; border: 1px solid {{ $accentColor }};">
                                                <i class="{{ $markerIcon }} me-1"></i>{{ $statusLabel }}
                                            </span>
                                        </div>

                                        {{-- Cuerpo --}}
                                        <div class="card-body p-4">
                                            @if (!$asistio && $asistio !== null)
                                                <div class="d-flex align-items-start gap-3 p-4 rounded-4" 
                                                    style="background: linear-gradient(135deg, rgba(220,53,69,0.05) 0%, rgba(220,53,69,0.02) 100%); border: 1px solid rgba(220,53,69,0.2);">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width: 48px; height: 48px; background: rgba(220,53,69,0.15);">
                                                        <i class="ri-user-unfollow-line fs-5" style="color: #dc3545;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-danger mb-1">Sesión no asistida</h6>
                                                        <p class="mb-0 text-muted">El niño/a no asistió a esta sesión. No se registró ningún informe.</p>
                                                    </div>
                                                </div>
                                            @elseif (!empty($informe))
                                                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid rgba(13,148,136,0.15);">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <span class="badge rounded-pill" style="background: rgba(13,148,136,0.15); color: #0d9488;">
                                                            <i class="ri-file-text-line me-1"></i>Informe registrado
                                                        </span>
                                                    </div>
                                                    <div class="ps-3" style="border-left: 3px solid #0d9488;">
                                                        <p class="mb-0 text-dark lh-lg" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.8;">{{ $informe }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-start gap-3 p-4 rounded-4" 
                                                    style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(245,158,11,0.02) 100%); border: 1px solid rgba(245,158,11,0.2);">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width: 48px; height: 48px; background: rgba(245,158,11,0.15);">
                                                        <i class="ri-file-warning-line fs-5" style="color: #f59e0b;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold" style="color: #b45309;">Informe pendiente</h6>
                                                        <p class="mb-0 text-muted">El fisioterapeuta aún no ha registrado el informe de esta sesión.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Footer con info adicional --}}
                                        <div class="card-footer bg-transparent py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2"
                                            style="border-top: 1px solid #f1f5f9;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-light text-dark rounded-pill" style="font-size: 0.75rem; padding: 6px 12px;">
                                                    <i class="ri-user-star-line me-1 text-primary"></i>
                                                    {{ $sesion->trabajador->persona->nombres ?? 'Sin asignar' }}
                                                    {{ $sesion->trabajador->persona->apellido_paterno ?? '' }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 text-muted small">
                                                @if ($sesion->hora_inicio)
                                                    <span>
                                                        <i class="ri-time-line me-1"></i>
                                                        {{ substr($sesion->hora_inicio, 0, 5) }}
                                                        @if ($sesion->hora_fin)
                                                            - {{ substr($sesion->hora_fin, 0, 5) }}
                                                        @endif
                                                    </span>
                                                @endif
                                                @if ($sesion->suscripcion->plan->nombre_plan ?? null)
                                                    <span>
                                                        <i class="ri-stack-line me-1"></i>
                                                        {{ $sesion->suscripcion->plan->nombre_plan }}
                                                    </span>
                                                @endif
                                                @if ($sesion->suscripcion->plan->paquete->nombre ?? null)
                                                    <span>
                                                        <i class="ri-gift-line me-1"></i>
                                                        {{ $sesion->suscripcion->plan->paquete->nombre }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Leyenda mejorada --}}
    <div class="mt-4 p-4 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="ri-information-line text-muted fs-5"></i>
            <span class="fw-semibold text-muted">Leyenda</span>
        </div>
        <div class="d-flex flex-wrap gap-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle" style="width: 14px; height: 14px; background: linear-gradient(135deg, #0d9488, #14b8a6);"></div>
                <span class="small text-muted">Asistió con informe</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle" style="width: 14px; height: 14px; background: linear-gradient(135deg, #f59e0b, #d97706);"></div>
                <span class="small text-muted">Asistió sin informe</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle" style="width: 14px; height: 14px; background: linear-gradient(135deg, #dc3545, #c82333);"></div>
                <span class="small text-muted">No asistió</span>
            </div>
        </div>
    </div>
@endif