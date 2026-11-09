@php use App\Models\Servicio; @endphp
<form id="serviciosForm">
    @csrf
    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>Servicio</th>
                    <th width="150">Cantidad de Sesiones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todosServicios as $servicio)
                    @php
                        $pivot = $plan->servicios->find($servicio->id)?->pivot;
                        $cantidad = $pivot ? $pivot->cantidad_sesiones : 0;
                    @endphp
                    <tr>
                        <td>
                            <span class="fw-medium">{{ $servicio->nombre }}</span>
                            @if ($servicio->descripcion)
                                <small class="d-block text-muted">{{ Str::limit($servicio->descripcion, 80) }}</small>
                            @endif
                        </td>
                        <td>
                            <input type="number" name="servicios[{{ $servicio->id }}][cantidad]"
                                class="form-control form-control-sm" min="0" value="{{ $cantidad }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>
