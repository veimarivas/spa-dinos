@extends('admin.dashboard')

@section('admin')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Calendario de Sesiones</h4>
                </div>
                <div class="card-body">
                    <!-- Filtro por fisioterapeuta -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="trabajadorFilter" class="form-label fw-semibold">
                                <i class="ri-user-settings-line me-1"></i>Filtrar por Fisioterapeuta
                            </label>
                            <select id="trabajadorFilter" class="form-select" onchange="filtrarTrabajador(this.value)">
                                <option value="">Todos los fisioterapeutas</option>
                                @foreach ($fisioterapeutas as $trabajador)
                                <option value="{{ $trabajador->id }}"
                                    {{ ($trabajadorId ?? '') == $trabajador->id ? 'selected' : '' }}>
                                    {{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">
                                <i class="ri-information-line"></i>
                                @if ($trabajadorId)
                                <strong>Vista individual:</strong> Los eventos muestran el nombre del hijo y el
                                color de fondo corresponde al cliente.
                                @else
                                <strong>Vista general:</strong> Los eventos muestran el nombre del fisioterapeuta y
                                el color de fondo es el asignado a cada profesional (personalizable en su registro).
                                @endif
                            </small>
                        </div>
                    </div>

                    <!-- Leyenda de estados (colores de texto) -->
                    <div class="mt-4 p-3 border rounded bg-light">
                        <h5 class="mb-3">Estado de las sesiones (color del texto)</h5>
                        <div class="d-flex flex-wrap gap-4">
                            @foreach ($coloresPorEstado as $estado => $color)
                            <div class="d-flex align-items-center gap-2">
                                <span
                                    style="display:inline-block; width:24px; height:24px; background-color:{{ $color }}; border-radius:4px;"></span>
                                <span>{{ ucfirst($estado) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Leyenda de colores de fisioterapeutas (solo en vista general) -->
                        @if (!$trabajadorId && $fisioterapeutas->count() > 0)
                        <div class="mt-4">
                            <h6 class="mb-3">Colores asignados a fisioterapeutas:</h6>
                            <div class="d-flex flex-wrap gap-4">
                                @foreach ($fisioterapeutas as $trabajador)
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        style="display:inline-block; width:24px; height:24px; background-color:{{ $trabajador->color ?? '#405189' }}; border-radius:4px;"></span>
                                    <span>{{ $trabajador->persona->nombres }}
                                        {{ $trabajador->persona->apellido_paterno }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <p class="text-muted small mt-3 mb-0">
                            <i class="ri-information-line"></i>
                            Las sesiones reprogramadas tienen el fondo mezclado con rojo.
                        </p>
                    </div>

                    <!-- Calendario -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.modal-detalle-sesion')
@endsection

@push('style')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
<style>
    #calendar {
        height: 700px;
    }

    .fc-event {
        cursor: pointer;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
    }

    .info-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
    }

    .badge-estado {
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 30px;
    }
</style>
@endpush

@push('script')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    // Función para filtrar por trabajador (recarga la página con el parámetro)
    function filtrarTrabajador(trabajadorId) {
        let url = new URL(window.location.href);
        if (trabajadorId) {
            url.searchParams.set('trabajador_id', trabajadorId);
        } else {
            url.searchParams.delete('trabajador_id');
        }
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            slotMinTime: '{{ $slotMinTime }}',
            slotMaxTime: '{{ $slotMaxTime }}',
            slotDuration: '{{ $slotDuration }}',
            height: 'auto',
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                week: 'Semana',
                day: 'Día'
            },
            events: @json($eventos),
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventClick: function(info) {
                window.mostrarDetalleSesion(info.event.id);
            }
        });
        calendar.render();
    });
</script>
@endpush