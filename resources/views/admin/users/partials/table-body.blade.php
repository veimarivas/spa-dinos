<tbody id="usersTableBody">
    @forelse ($users as $n => $user)
        <tr data-nombre="{{ strtolower($user->name) }}" data-estado="{{ strtolower($user->estado ?? '') }}">
            <td class="text-center">{{ $users->firstItem() + $n }}</td>
            <td class="text-center">
                @if ($user->persona && $user->persona->fotografia)
                    <img src="{{ asset($user->persona->fotografia) }}" alt="Foto" class="rounded-circle user-photo">
                @else
                    @if ($user->persona && $user->persona->sexo == 'Hombre')
                        <img src="{{ asset('frontend/assets/img/personal/hombre.png') }}" alt="Foto"
                            class="rounded-circle user-photo">
                    @else
                        <img src="{{ asset('frontend/assets/img/personal/mujer.png') }}" alt="Foto"
                            class="rounded-circle user-photo">
                    @endif
                @endif
            </td>
            <td>
                @if ($user->persona)
                    <strong>{{ $user->persona->apellido_paterno }} {{ $user->persona->apellido_materno }}</strong><br>
                    <span class="text-muted">{{ $user->persona->nombres }}</span><br>
                    <small class="text-muted">
                        <i class="ri-id-card-line"></i> {{ $user->persona->carnet }}
                    </small>
                @else
                    <span class="text-muted fst-italic">Sin datos personales</span>
                @endif
            </td>
            <td>{{ $user->email }}</td>
            <td>
                @php
                    $roles = $user->getRoleNames()->toArray();
                @endphp

                @if (count($roles) > 0)
                    @foreach ($roles as $role)
                        <span class="badge badge-group">{{ ucfirst(str_replace('-', ' ', $role)) }}</span>
                    @endforeach
                @else
                    <span class="badge badge-secondary">Sin rol asignado</span>
                @endif
            </td>
            <td class="text-center">
                @if ($user->estado == 'activo')
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </td>
            <td class="text-center">
                <div class="btn-group" role="group">
                    <button type="button" title="Editar Usuario" class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#modalModificar">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button type="button" title="Restablecer Contraseña" class="btn btn-info btn-sm resetBtn"
                        data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#modalResetPassword">
                        <i class="ri-key-line"></i>
                    </button>
                    <button type="button" title="Enviar credenciales por WhatsApp" class="btn btn-success btn-sm credencialesBtn"
                        data-email="{{ $user->email }}"
                        data-carnet="{{ $user->persona?->carnet }}"
                        data-nombre="{{ $user->name }}"
                        data-celular="{{ $user->persona?->celular }}"
                        data-bs-toggle="modal" data-bs-target="#modalCredenciales">
                        <i class="ri-whatsapp-line"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-4">
                <div class="text-muted">
                    <i class="ri-inbox-line display-4"></i>
                    <p class="mt-2">No se encontraron usuarios</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
