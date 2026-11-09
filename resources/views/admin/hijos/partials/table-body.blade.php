@forelse ($hijos as $index => $hijo)
    <tr data-hijo-id="{{ $hijo->id }}">
        <td class="text-center">
            <span class="badge bg-info-subtle text-info rounded-pill p-2">
                {{ $hijos->firstItem() + $index }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="ri-baby-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">
                        {{ $hijo->persona->nombres }} {{ $hijo->persona->apellido_paterno }}
                        {{ $hijo->persona->apellido_materno }}
                    </h6>
                </div>
            </div>
        </td>
        <td>{{ $hijo->persona->carnet ?? 'N/A' }}</td>
        <td>{{ $hijo->persona->fecha_nacimiento ? \Carbon\Carbon::parse($hijo->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
        </td>
        <td>
            @forelse ($hijo->parentescos as $parentesco)
                @can('clientes.ver')
                <a href="{{ route('admin.clientes.seguimiento', $parentesco->cliente_id) }}"
                    class="text-primary d-block">
                    <i class="ri-user-star-line me-1"></i>{{ $parentesco->cliente->persona->nombres }}
                    {{ $parentesco->cliente->persona->apellido_paterno }}
                </a>
                @else
                <span class="d-block">
                    <i class="ri-user-star-line me-1 text-muted"></i>{{ $parentesco->cliente->persona->nombres }}
                    {{ $parentesco->cliente->persona->apellido_paterno }}
                </span>
                @endcan
                <small class="text-muted">({{ $parentesco->relacion }})</small>
                @if (!$loop->last)
                    <br>
                @endif
            @empty
                <span class="text-muted fst-italic">Sin padre/madre registrado</span>
            @endforelse
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                @can('clientes.hijos.ver')
                <a href="{{ route('admin.hijos.seguimiento', $hijo->id) }}" title="Ver seguimiento del niño"
                    class="btn btn-outline-info viewBtn">
                    <i class="ri-eye-line"></i>
                </a>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="ri-baby-line fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No se encontraron niños</h5>
                <p class="text-muted mb-4">Aún no hay hijos registrados. Puedes registrarlos desde la ficha de un
                    cliente.</p>
                <a href="{{ route('admin.clientes.listar') }}" class="btn btn-info">
                    <i class="ri-user-star-line align-bottom me-1"></i> Ir a Clientes
                </a>
            </div>
        </td>
    </tr>
@endforelse
