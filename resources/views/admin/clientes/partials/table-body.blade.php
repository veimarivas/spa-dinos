@forelse ($clientes as $index => $cliente)
    <tr data-cliente-id="{{ $cliente->id }}" data-nombre="{{ strtolower($cliente->persona->nombres) }}">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $clientes->firstItem() + $index }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title rounded-circle text-white"
                            style="background-color: {{ $cliente->color ?? '#405189' }};">
                            <i class="ri-user-star-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">
                        {{ $cliente->persona->nombres }} {{ $cliente->persona->apellido_paterno }}
                        {{ $cliente->persona->apellido_materno }}
                    </h6>
                    <small class="text-muted">{{ $cliente->persona->carnet ?? 'Sin CI' }}</small>
                </div>
            </div>
        </td>
        <td>
            @if ($cliente->persona->correo || $cliente->persona->celular)
                <div class="d-flex flex-column">
                    @if ($cliente->persona->correo)
                        <span><i class="ri-mail-line me-1 text-muted"></i>{{ $cliente->persona->correo }}</span>
                    @endif
                    @if ($cliente->persona->celular)
                        <span><i class="ri-phone-line me-1 text-muted"></i>{{ $cliente->persona->celular }}</span>
                    @endif
                </div>
            @else
                <span class="text-muted fst-italic">No registrado</span>
            @endif
        </td>
        <td class="text-center">
            <span class="badge bg-success-subtle text-success rounded-pill hijos-count">
                {{ $cliente->hijos_count ?? ($cliente->parentescos_count ?? 0) }}
            </span>
        </td>
        <td class="text-center">
            @php
                $activas = $cliente->suscripciones->where('estado', 'activa')->count();
            @endphp
            <span class="badge bg-info-subtle text-info rounded-pill suscripciones-activas"
                data-count="{{ $activas }}">
                {{ $activas }}
            </span>
            <small class="text-muted d-block total-suscripciones"
                data-count="{{ $cliente->suscripciones_count ?? 0 }}">
                Total: {{ $cliente->suscripciones_count ?? 0 }}
            </small>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('clientes.ver')
                <a href="{{ route('admin.clientes.seguimiento', $cliente->id) }}" title="Ver Seguimiento Completo"
                    class="btn btn-outline-info viewBtn">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
                @can('clientes.editar')
                <button type="button" title="Editar Cliente" class="btn btn-outline-primary editBtn"
                    data-bs-obj='@json(['id' => $cliente->id, 'persona' => $cliente->persona, 'color' => $cliente->color])' data-bs-toggle="modal"
                    data-bs-target="#modalModificarCliente">
                    <i class="ri-edit-line"></i>
                </button>
                @endcan
                @can('clientes.eliminar')
                <button type="button" title="Eliminar Cliente" class="btn btn-outline-danger deleteBtn"
                    data-bs-obj='@json(['id' => $cliente->id, 'persona' => $cliente->persona])' data-bs-toggle="modal"
                    data-bs-target="#modalEliminarCliente">
                    <i class="ri-delete-bin-line"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-group-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron clientes</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer cliente</p>
                @can('clientes.registrar')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#registrarClienteModal">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Cliente
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
