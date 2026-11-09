@forelse ($trabajadores as $index => $trabajador)
    @php
        $usuarioCuenta = \App\Models\User::where('persona_id', $trabajador->persona_id)->with('roles')->first();
        $editData = [
            'id' => $trabajador->id,
            'persona' => [
                'id' => $trabajador->persona->id,
                'carnet' => $trabajador->persona->carnet,
                'nit' => $trabajador->persona->nit,
                'nombres' => $trabajador->persona->nombres,
                'apellido_paterno' => $trabajador->persona->apellido_paterno,
                'apellido_materno' => $trabajador->persona->apellido_materno,
                'correo' => $trabajador->persona->correo,
                'direccion' => $trabajador->persona->direccion,
                'fecha_nacimiento' => $trabajador->persona->fecha_nacimiento,
                'celular' => $trabajador->persona->celular,
                'telefono' => $trabajador->persona->telefono,
            ],
            'cargo_id' => $trabajador->cargo_id,
            'color' => $trabajador->color,
        ];
    @endphp
    <tr data-trabajador-id="{{ $trabajador->id }}" data-nombre="{{ strtolower($trabajador->persona->nombres) }}">
        <td class="text-center">
            <span class="badge bg-primary-subtle text-primary rounded-pill p-2">
                {{ $trabajadores->firstItem() + $index }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <div class="avatar-title rounded-circle text-white"
                            style="background-color: {{ $trabajador->color ?? '#405189' }};">
                            <i class="ri-user-settings-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">
                        {{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}
                        {{ $trabajador->persona->apellido_materno }}
                    </h6>
                    <small class="text-muted">{{ $trabajador->persona->carnet ?? 'Sin CI' }}</small>
                </div>
            </div>
        </td>
        <td>
            @if ($trabajador->persona->correo || $trabajador->persona->celular)
                <div class="d-flex flex-column">
                    @if ($trabajador->persona->correo)
                        <span><i class="ri-mail-line me-1 text-muted"></i>{{ $trabajador->persona->correo }}</span>
                    @endif
                    @if ($trabajador->persona->celular)
                        <span><i class="ri-phone-line me-1 text-muted"></i>{{ $trabajador->persona->celular }}</span>
                    @endif
                </div>
            @else
                <span class="text-muted fst-italic">No registrado</span>
            @endif
        </td>
        <td>
            <span class="badge bg-info-subtle text-info rounded-pill">
                {{ $trabajador->cargo->nombre ?? 'Sin cargo' }}
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-success-subtle text-success rounded-pill">
                {{ $trabajador->sesiones_count ?? 0 }}
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                <div class="btn-group btn-group-sm" role="group">
                    @can('trabajadores.actividades')
                    <a href="{{ route('admin.trabajadores.ver', $trabajador->id) }}"
                        title="Ver actividades" class="btn btn-outline-info">
                        <i class="ri-calendar-check-line"></i>
                    </a>
                    <button type="button" title="Ver pagos y pendientes"
                        class="btn btn-outline-warning btn-ver-salarios"
                        data-id="{{ $trabajador->id }}"
                        data-nombre="{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}"
                        data-cargo="{{ $trabajador->cargo->nombre ?? 'Sin cargo' }}"
                        data-color="{{ $trabajador->color ?? '#b45309' }}">
                        <i class="ri-money-dollar-circle-line"></i>
                    </button>
                    @endcan
                    @can('trabajadores.editar')
                    <button type="button" title="Editar Trabajador" class="btn btn-outline-primary editBtn"
                        data-bs-obj='{{ json_encode($editData) }}' data-bs-toggle="modal"
                        data-bs-target="#modalModificarTrabajador">
                        <i class="ri-edit-line"></i>
                    </button>
                    @endcan
                    @can('trabajadores.eliminar')
                    <button type="button" title="Eliminar Trabajador" class="btn btn-outline-danger deleteBtn"
                        data-bs-obj='{{ json_encode(['id' => $trabajador->id, 'persona' => ['nombres' => $trabajador->persona->nombres, 'apellido_paterno' => $trabajador->persona->apellido_paterno]]) }}'
                        data-bs-toggle="modal" data-bs-target="#modalEliminarTrabajador">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                    @endcan
                </div>
                @if ($usuarioCuenta)
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge rounded-pill px-2 py-1"
                            style="background:rgba(25,135,84,.12);color:#198754;border:1px solid rgba(25,135,84,.25);font-size:.7rem;"
                            title="Usuario: {{ $usuarioCuenta->email }}">
                            <i class="ri-user-check-line me-1"></i>{{ $usuarioCuenta->getRoleNames()->first() ?? 'Sin rol' }}
                        </span>
                        @can('trabajadores.cuentas.enviar')
                        <button type="button"
                            class="btn btn-sm btn-outline-success rounded-circle btnCredencialesTrab"
                            style="width:26px;height:26px;padding:0;line-height:1;"
                            title="Enviar credenciales por WhatsApp"
                            data-email="{{ $usuarioCuenta->email }}"
                            data-carnet="{{ $trabajador->persona?->carnet }}"
                            data-nombre="{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}"
                            data-celular="{{ $trabajador->persona?->celular }}"
                            data-bs-toggle="modal" data-bs-target="#modalCredencialesTrab">
                            <i class="ri-whatsapp-line" style="font-size:.7rem;"></i>
                        </button>
                        @endcan
                    </div>
                @else
                    @can('trabajadores.cuentas.registrar')
                    <button type="button"
                        class="btn btn-sm btn-outline-success rounded-pill btnCrearCuenta"
                        title="Crear cuenta de usuario"
                        data-id="{{ $trabajador->id }}"
                        data-nombre="{{ $trabajador->persona->nombres }} {{ $trabajador->persona->apellido_paterno }}"
                        data-correo="{{ $trabajador->persona->correo }}"
                        data-bs-toggle="modal" data-bs-target="#modalCrearCuentaTrabajador">
                        <i class="ri-user-add-line me-1"></i>Crear cuenta
                    </button>
                    @endcan
                @endif
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
                <h5 class="text-muted mb-2">No se encontraron trabajadores</h5>
                <p class="text-muted mb-4">Comienza registrando tu primer trabajador</p>
                @can('trabajadores.registrar')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#registrarTrabajadorModal">
                    <i class="ri-add-line align-bottom me-1"></i> Crear Primer Trabajador
                </button>
                @endcan
            </div>
        </td>
    </tr>
@endforelse
