<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="fw-bold mb-0 text-dark">
        <i class="ri-baby-line me-2" style="color:#0d9488;"></i>Hijos registrados
    </h6>
    @can('clientes.hijos.registrar')
    <button type="button" class="btn btn-sm rounded-pill px-3 fw-semibold"
        style="background:linear-gradient(135deg,#1a2d4c,#0d9488);color:#fff;border:none;"
        data-bs-toggle="modal" data-bs-target="#registrarHijoModal">
        <i class="ri-add-line me-1"></i>Registrar nuevo hijo
    </button>
    @endcan
</div>

@if ($cliente->parentescos->count())
    <div class="d-flex flex-column gap-3">
        @foreach ($cliente->parentescos as $parentesco)
            @php
                $hijo    = $parentesco->hijo;
                $persona = $hijo->persona;
                $fnac    = $persona->fecha_nacimiento
                    ? \Carbon\Carbon::parse($persona->fecha_nacimiento)
                    : null;
                $edad    = $fnac ? $fnac->age : null;

                // Sesiones del hijo a través de las suscripciones del cliente
                $sesionesHijo = collect();
                foreach ($cliente->suscripciones as $sus) {
                    $sesionesHijo = $sesionesHijo->merge(
                        $sus->sesiones->where('hijo_id', $hijo->id)
                    );
                }
                $totalPlan   = $cliente->suscripciones
                    ->filter(fn($s) => $s->sesiones->where('hijo_id', $hijo->id)->count() > 0)
                    ->sum(fn($s) => $s->plan->cantidad_sesiones ?? 0);
                $realizadas  = $sesionesHijo->where('estado','realizada')->count();
                $porRealizar = max(0, $totalPlan - $realizadas);
                $asistidas   = $sesionesHijo->where('estado','realizada')->where('asistio_hijo', true)->count();
                $noAsist     = $sesionesHijo->where('estado','realizada')->where('asistio_hijo', false)->count();

                // Suscripción con pendientes para el link de agendar
                $susPendiente = $cliente->suscripciones
                    ->filter(fn($s) => $s->sesiones->where('hijo_id', $hijo->id)->count() > 0)
                    ->first();
            @endphp

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex align-items-stretch">
                        {{-- Avatar lateral --}}
                        <div class="d-flex align-items-center justify-content-center px-4"
                            style="background:linear-gradient(180deg,rgba(13,148,136,.12),rgba(26,45,76,.08));min-width:80px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width:52px;height:52px;background:linear-gradient(135deg,#0d9488,#1a2d4c);">
                                <i class="ri-baby-line fs-4"></i>
                            </div>
                        </div>

                        {{-- Info principal --}}
                        <div class="flex-grow-1 p-3">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-0" style="color:#1a2d4c;">
                                        {{ $persona->nombres }} {{ $persona->apellido_paterno }}
                                        {{ $persona->apellido_materno ?? '' }}
                                    </h6>
                                    <div class="d-flex flex-wrap gap-3 mt-1">
                                        @if ($persona->carnet)
                                            <small class="text-muted">
                                                <i class="ri-id-card-line me-1"></i>{{ $persona->carnet }}
                                            </small>
                                        @endif
                                        @if ($fnac)
                                            <small class="text-muted">
                                                <i class="ri-calendar-line me-1"></i>
                                                {{ $fnac->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                                @if ($edad !== null)
                                                    <span class="badge rounded-pill ms-1"
                                                        style="background:rgba(13,148,136,.12);color:#0d9488;font-size:.7rem;">
                                                        {{ $edad }} {{ $edad === 1 ? 'año' : 'años' }}
                                                    </span>
                                                @endif
                                            </small>
                                        @endif
                                        <small>
                                            <span class="badge rounded-pill"
                                                style="background:rgba(26,45,76,.08);color:#1a2d4c;font-size:.7rem;">
                                                {{ $parentesco->relacion }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                                @can('clientes.hijos.ver')
                                <a href="{{ route('admin.hijos.seguimiento', $hijo->id) }}"
                                    class="btn btn-sm rounded-pill px-3"
                                    style="background:rgba(13,148,136,.1);color:#0d9488;border:1px solid rgba(13,148,136,.3);">
                                    <i class="ri-eye-line me-1"></i>Ver seguimiento
                                </a>
                                @endcan
                            </div>

                            {{-- Mini-estadísticas --}}
                            @if ($sesionesHijo->count() || $totalPlan > 0)
                                <div class="d-flex flex-wrap gap-2 mt-3 pt-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="rounded-3 px-3 py-1" style="background:rgba(25,135,84,.08);border:1px solid rgba(25,135,84,.2);">
                                        <span class="fw-bold small" style="color:#198754;">{{ $realizadas }}</span>
                                        <span class="text-muted small ms-1">realizadas</span>
                                    </div>
                                    @if ($porRealizar > 0)
                                        @can('suscripciones.ver')
                                        <a href="{{ $susPendiente ? route('admin.suscripciones.show', $susPendiente->id) : '#' }}"
                                            class="rounded-3 px-3 py-1 text-decoration-none"
                                            style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);">
                                            <span class="fw-bold small" style="color:#d97706;">{{ $porRealizar }}</span>
                                            <span class="small ms-1" style="color:#d97706;">por realizar ↗</span>
                                        </a>
                                        @else
                                        <div class="rounded-3 px-3 py-1" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);">
                                            <span class="fw-bold small" style="color:#d97706;">{{ $porRealizar }}</span>
                                            <span class="text-muted small ms-1">por realizar</span>
                                        </div>
                                        @endcan
                                    @else
                                        <div class="rounded-3 px-3 py-1" style="background:rgba(25,135,84,.06);border:1px solid rgba(25,135,84,.15);">
                                            <span class="small" style="color:#166534;"><i class="ri-checkbox-circle-line me-1"></i>Plan completo</span>
                                        </div>
                                    @endif
                                    @if ($asistidas > 0)
                                        <div class="rounded-3 px-3 py-1" style="background:rgba(25,135,84,.06);border:1px solid rgba(25,135,84,.15);">
                                            <span class="fw-bold small" style="color:#198754;">{{ $asistidas }}</span>
                                            <span class="text-muted small ms-1">asistió</span>
                                        </div>
                                    @endif
                                    @if ($noAsist > 0)
                                        <div class="rounded-3 px-3 py-1" style="background:rgba(220,53,69,.06);border:1px solid rgba(220,53,69,.15);">
                                            <span class="fw-bold small" style="color:#dc3545;">{{ $noAsist }}</span>
                                            <span class="text-muted small ms-1">no asistió</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="ri-baby-line fs-1 text-muted opacity-50"></i>
        <h5 class="mt-3 text-muted">Este cliente no tiene hijos registrados</h5>
        <p class="text-muted small">Haz clic en "Registrar nuevo hijo" para agregar uno.</p>
    </div>
@endif
