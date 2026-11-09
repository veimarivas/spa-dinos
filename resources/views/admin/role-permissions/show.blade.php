@extends('admin.dashboard')
@section('admin')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0">Gestión de Permisos</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.role-permissions.index') }}">Roles</a></li>
                                <li class="breadcrumb-item active">Permisos para {{ $role->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i> Volver a Roles
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                            <i class="ri-shield-user-line"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 fs-12">Rol</p>
                                        <h5 class="mb-0">{{ $role->name }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted fs-12">Progreso de asignación</span>
                                            <span class="fw-semibold fs-12" id="progressText">
                                                {{ $role->permissions->count() }} / {{ $totalPermissions }}
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $percentage =
                                                    $totalPermissions > 0
                                                        ? round(($role->permissions->count() / $totalPermissions) * 100)
                                                        : 0;
                                            @endphp
                                            <div class="progress-bar bg-success progress-bar-striped" id="progressBar"
                                                role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success fs-12 px-2 py-1"
                                        id="progressPercent">{{ $percentage }}%</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <div class="text-center">
                                        <h5 class="mb-0 text-success" id="assignedCount">
                                            {{ $role->permissions->count() }}</h5>
                                        <p class="text-muted mb-0 fs-11">Asignados</p>
                                    </div>
                                    <div class="vr mx-2"></div>
                                    <div class="text-center">
                                        @php
                                            $totalAvailable = 0;
                                            foreach ($availablePermissionsByGroup as $group => $permissions) {
                                                $totalAvailable += $permissions->count();
                                            }
                                        @endphp
                                        <h5 class="mb-0 text-primary" id="availableCount">{{ $totalAvailable }}</h5>
                                        <p class="text-muted mb-0 fs-11">Disponibles</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body py-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="search-box position-relative">
                                    <i class="ri-search-line position-absolute search-icon-left"></i>
                                    <input type="text" id="permissionSearch" class="form-control ps-5"
                                        placeholder="Buscar permisos por nombre...">
                                    <button type="button" id="clearSearch"
                                        class="btn-close position-absolute search-clear-btn d-none"
                                        aria-label="Limpiar"></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                    <button type="button" id="expandAll" class="btn btn-soft-info btn-sm">
                                        <i class="ri-expand-diagonal-line me-1"></i> Expandir todo
                                    </button>
                                    <button type="button" id="collapseAll" class="btn btn-soft-info btn-sm">
                                        <i class="ri-collapse-diagonal-line me-1"></i> Colapsar todo
                                    </button>
                                    <div class="vr d-none d-md-block"></div>
                                    <button type="button" id="assignAllVisible" class="btn btn-soft-success btn-sm">
                                        <i class="ri-check-double-line me-1"></i> Asignar todos
                                    </button>
                                    <button type="button" id="revokeAllAssigned" class="btn btn-soft-danger btn-sm">
                                        <i class="ri-close-circle-line me-1"></i> Revocar todos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Columns -->
        <div class="row">
            <!-- Assigned Permissions -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-success border-opacity-25">
                    <div class="card-header bg-success-subtle border-bottom-0">
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-fill text-success fs-5 me-2"></i>
                            <h5 class="card-title mb-0 flex-grow-1">Permisos Asignados</h5>
                            <span class="badge bg-success rounded-pill fs-12"
                                id="assignedBadge">{{ $role->permissions->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3" id="assignedPermissionsContainer"
                        style="max-height: 600px; overflow-y: auto;">
                        @php
                            $hasAssigned = false;
                        @endphp
                        @foreach ($permissionGroups as $group)
                            @php
                                $assignedPermissions = $role->permissions->where('group_name', $group)->sortBy('name');
                            @endphp

                            @if ($assignedPermissions->isNotEmpty())
                                @php $hasAssigned = true; @endphp
                                <div class="permission-group-card mb-2" data-group="{{ $group }}">
                                    <div class="group-header d-flex align-items-center justify-content-between p-2 rounded cursor-pointer"
                                        data-bs-toggle="collapse" data-bs-target="#assignedGroup{{ $loop->index }}">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-arrow-down-s-line group-arrow text-muted me-1 fs-5"></i>
                                            <i class="ri-folder-3-fill text-warning me-2"></i>
                                            <span class="fw-semibold">{{ ucfirst($group) }}</span>
                                            <span
                                                class="badge bg-success-subtle text-success ms-2 rounded-pill">{{ $assignedPermissions->count() }}</span>
                                        </div>
                                        <button class="btn btn-soft-danger btn-sm py-0 px-2 revoke-group-btn"
                                            data-group="{{ $group }}" title="Revocar todo el grupo"
                                            onclick="event.stopPropagation();">
                                            <i class="ri-delete-bin-line fs-12"></i>
                                        </button>
                                    </div>
                                    <div class="collapse show" id="assignedGroup{{ $loop->index }}">
                                        <div class="permission-list ms-4 mt-1">
                                            @foreach ($assignedPermissions as $permission)
                                                <div class="permission-item d-flex align-items-center justify-content-between py-2 px-3 mb-1 rounded"
                                                    data-permission-id="{{ $permission->id }}"
                                                    data-permission-name="{{ strtolower($permission->name) }}"
                                                    data-group-name="{{ $group }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-shield-check-fill text-success me-2 fs-14"></i>
                                                        <span
                                                            class="permission-text">{{ $permission->name }}</span>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-sm btn-soft-danger rounded-circle permission-action-btn revoke-btn"
                                                        data-role-id="{{ $role->id }}"
                                                        data-permission-id="{{ $permission->id }}"
                                                        title="Revocar">
                                                        <i class="ri-arrow-right-line"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if (!$hasAssigned)
                            <div class="empty-state text-center py-5">
                                <i class="ri-shield-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">No hay permisos asignados a este rol</p>
                                <small class="text-muted">Asigna permisos desde la columna derecha</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Available Permissions -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-primary border-opacity-25">
                    <div class="card-header bg-primary-subtle border-bottom-0">
                        <div class="d-flex align-items-center">
                            <i class="ri-add-circle-fill text-primary fs-5 me-2"></i>
                            <h5 class="card-title mb-0 flex-grow-1">Permisos Disponibles</h5>
                            <span class="badge bg-primary rounded-pill fs-12"
                                id="availableBadge">{{ $totalAvailable }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3" id="availablePermissionsContainer"
                        style="max-height: 600px; overflow-y: auto;">
                        @php
                            $hasAvailable = false;
                        @endphp
                        @foreach ($permissionGroups as $group)
                            @php
                                $availablePermissions = $availablePermissionsByGroup[$group] ?? collect();
                            @endphp

                            @if ($availablePermissions->isNotEmpty())
                                @php $hasAvailable = true; @endphp
                                <div class="permission-group-card mb-2" data-group="{{ $group }}">
                                    <div class="group-header d-flex align-items-center justify-content-between p-2 rounded cursor-pointer"
                                        data-bs-toggle="collapse" data-bs-target="#availableGroup{{ $loop->index }}">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-arrow-down-s-line group-arrow text-muted me-1 fs-5"></i>
                                            <i class="ri-folder-3-fill text-warning me-2"></i>
                                            <span class="fw-semibold">{{ ucfirst($group) }}</span>
                                            <span
                                                class="badge bg-primary-subtle text-primary ms-2 rounded-pill">{{ $availablePermissions->count() }}</span>
                                        </div>
                                        <button class="btn btn-soft-success btn-sm py-0 px-2 assign-group-btn"
                                            data-group="{{ $group }}" title="Asignar todo el grupo"
                                            onclick="event.stopPropagation();">
                                            <i class="ri-add-line fs-12"></i>
                                        </button>
                                    </div>
                                    <div class="collapse show" id="availableGroup{{ $loop->index }}">
                                        <div class="permission-list ms-4 mt-1">
                                            @foreach ($availablePermissions as $permission)
                                                <div class="permission-item d-flex align-items-center justify-content-between py-2 px-3 mb-1 rounded"
                                                    data-permission-id="{{ $permission->id }}"
                                                    data-permission-name="{{ strtolower($permission->name) }}"
                                                    data-group-name="{{ $group }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-shield-line text-primary me-2 fs-14"></i>
                                                        <span
                                                            class="permission-text">{{ $permission->name }}</span>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-sm btn-soft-success rounded-circle permission-action-btn assign-btn"
                                                        data-role-id="{{ $role->id }}"
                                                        data-permission-id="{{ $permission->id }}"
                                                        title="Asignar">
                                                        <i class="ri-arrow-left-line"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if (!$hasAvailable)
                            <div class="empty-state text-center py-5">
                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                <p class="text-muted mt-2 mb-0">Todos los permisos ya están asignados</p>
                                <small class="text-muted">Este rol tiene acceso completo</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .search-icon-left {
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #878a99;
            z-index: 5;
        }

        .search-clear-btn {
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            font-size: 10px;
            z-index: 5;
        }

        .permission-group-card .group-header {
            transition: background-color 0.2s ease;
        }

        .permission-group-card .group-header:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .group-arrow {
            transition: transform 0.25s ease;
        }

        .collapsed .group-arrow {
            transform: rotate(-90deg);
        }

        .permission-item {
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .permission-item:hover {
            background-color: rgba(85, 110, 230, 0.06);
            border-color: rgba(85, 110, 230, 0.15);
        }

        .permission-action-btn {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            position: relative;
            z-index: 2;
        }

        .permission-text {
            font-size: 0.82rem;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            color: #495057;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .fs-11 {
            font-size: 0.688rem !important;
        }

        .fs-12 {
            font-size: 0.75rem !important;
        }

        .fs-14 {
            font-size: 0.875rem !important;
        }

        .permission-item.removing {
            animation: slideOutRight 0.3s ease forwards;
        }

        .permission-item.adding {
            animation: slideInLeft 0.3s ease forwards;
        }

        @keyframes slideOutRight {
            to {
                opacity: 0;
                transform: translateX(30px);
                max-height: 0;
                padding: 0;
                margin: 0;
                overflow: hidden;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .empty-state {
            opacity: 0.7;
        }

        .highlight-match {
            background-color: #fff3cd;
            padding: 1px 2px;
            border-radius: 2px;
            font-weight: 600;
        }

        #assignedPermissionsContainer::-webkit-scrollbar,
        #availablePermissionsContainer::-webkit-scrollbar {
            width: 6px;
        }

        #assignedPermissionsContainer::-webkit-scrollbar-track,
        #availablePermissionsContainer::-webkit-scrollbar-track {
            background: transparent;
        }

        #assignedPermissionsContainer::-webkit-scrollbar-thumb,
        #availablePermissionsContainer::-webkit-scrollbar-thumb {
            background: #d2d4d9;
            border-radius: 3px;
        }

        .btn-soft-success {
            color: #0ab39c;
            background-color: rgba(10, 179, 156, 0.1);
            border-color: transparent;
        }

        .btn-soft-success:hover {
            color: #fff;
            background-color: #0ab39c;
        }

        .btn-soft-danger {
            color: #f06548;
            background-color: rgba(240, 101, 72, 0.1);
            border-color: transparent;
        }

        .btn-soft-danger:hover {
            color: #fff;
            background-color: #f06548;
        }

        .btn-soft-info {
            color: #299cdb;
            background-color: rgba(41, 156, 219, 0.1);
            border-color: transparent;
        }

        .btn-soft-info:hover {
            color: #fff;
            background-color: #299cdb;
        }

        .btn-soft-secondary {
            color: #878a99;
            background-color: rgba(135, 138, 153, 0.1);
            border-color: transparent;
        }

        .btn-soft-secondary:hover {
            color: #fff;
            background-color: #878a99;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const ROLE_ID = {{ $role->id }};
            const TOTAL_PERMISSIONS = {{ $totalPermissions }};
            let debounceTimer;

            // --- Notifications ---
            function showNotification(type, message) {
                let toastContainer = $('#toast-container');
                if (toastContainer.length === 0) {
                    toastContainer = $(
                        '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>'
                    );
                    $('body').append(toastContainer);
                }

                const icons = {
                    success: 'ri-check-line',
                    danger: 'ri-error-warning-line',
                    warning: 'ri-alert-line',
                    info: 'ri-information-line'
                };

                const toast = $(`
                    <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="${icons[type] || icons.info} me-2"></i>${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `);

                toastContainer.append(toast);
                new bootstrap.Toast(toast[0], {
                    delay: 3000
                }).show();
                toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // --- Counters & Progress ---
            function updateCounters() {
                const assigned = $('#assignedPermissionsContainer .permission-item').length;
                const available = $('#availablePermissionsContainer .permission-item').length;
                const pct = TOTAL_PERMISSIONS > 0 ? Math.round((assigned / TOTAL_PERMISSIONS) * 100) : 0;

                $('#assignedCount, #assignedBadge').text(assigned);
                $('#availableCount, #availableBadge').text(available);
                $('#progressBar').css('width', pct + '%').attr('aria-valuenow', pct);
                $('#progressPercent').text(pct + '%');
                $('#progressText').text(assigned + ' / ' + TOTAL_PERMISSIONS);

                // Update group badges
                $('#assignedPermissionsContainer .permission-group-card').each(function() {
                    const count = $(this).find('.permission-item').length;
                    $(this).find('.group-header .badge').text(count);
                });
                $('#availablePermissionsContainer .permission-group-card').each(function() {
                    const count = $(this).find('.permission-item').length;
                    $(this).find('.group-header .badge').text(count);
                });

                toggleEmptyStates();
            }

            function toggleEmptyStates() {
                const assignedEmpty = $('#assignedPermissionsContainer .permission-item').length === 0;
                const availableEmpty = $('#availablePermissionsContainer .permission-item').length === 0;

                $('#assignedPermissionsContainer .empty-state').remove();
                $('#availablePermissionsContainer .empty-state').remove();

                if (assignedEmpty) {
                    $('#assignedPermissionsContainer').append(`
                        <div class="empty-state text-center py-5">
                            <i class="ri-shield-line fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No hay permisos asignados a este rol</p>
                            <small class="text-muted">Asigna permisos desde la columna derecha</small>
                        </div>
                    `);
                }
                if (availableEmpty) {
                    $('#availablePermissionsContainer').append(`
                        <div class="empty-state text-center py-5">
                            <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                            <p class="text-muted mt-2 mb-0">Todos los permisos ya están asignados</p>
                            <small class="text-muted">Este rol tiene acceso completo</small>
                        </div>
                    `);
                }
            }

            // --- Search ---
            $('#permissionSearch').on('input', function() {
                const val = $(this).val().trim();
                $('#clearSearch').toggleClass('d-none', val.length === 0);
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => filterPermissions(val.toLowerCase()), 150);
            });

            $('#clearSearch').on('click', function() {
                $('#permissionSearch').val('').trigger('input');
            });

            function filterPermissions(term) {
                $('.permission-item').each(function() {
                    const $item = $(this);
                    const name = $item.find('.permission-text').text();
                    const matches = name.toLowerCase().includes(term);
                    $item.toggle(matches);

                    // Highlight match
                    if (term && matches) {
                        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        $item.find('.permission-text').html(name.replace(regex,
                            '<span class="highlight-match">$1</span>'));
                    } else {
                        $item.find('.permission-text').text(name);
                    }
                });

                $('.permission-group-card').each(function() {
                    $(this).toggle($(this).find('.permission-item:visible').length > 0);
                });
            }

            // --- Collapse/Expand ---
            $('#expandAll').on('click', function() {
                $('.permission-group-card .collapse').collapse('show');
            });

            $('#collapseAll').on('click', function() {
                $('.permission-group-card .collapse').collapse('hide');
            });

            // --- AJAX Helpers ---
            async function ajaxAssign(permissionId) {
                return $.ajax({
                    url: "{{ route('admin.role-permissions.assign') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        role_id: ROLE_ID,
                        permission_id: permissionId
                    }
                });
            }

            async function ajaxRevoke(permissionId) {
                return $.ajax({
                    url: "{{ route('admin.role-permissions.revoke') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        role_id: ROLE_ID,
                        permission_id: permissionId
                    }
                });
            }

            // --- Find or create group card ---
            function getOrCreateGroup(container, groupName, type) {
                let $group = $(`${container} .permission-group-card[data-group="${groupName}"]`);
                if ($group.length) return $group;

                const uid = 'grp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
                const badgeClass = type === 'assigned' ? 'bg-success-subtle text-success' :
                    'bg-primary-subtle text-primary';
                const actionBtn = type === 'assigned' ?
                    `<button class="btn btn-soft-danger btn-sm py-0 px-2 revoke-group-btn" data-group="${groupName}" title="Revocar todo el grupo" onclick="event.stopPropagation();"><i class="ri-delete-bin-line fs-12"></i></button>` :
                    `<button class="btn btn-soft-success btn-sm py-0 px-2 assign-group-btn" data-group="${groupName}" title="Asignar todo el grupo" onclick="event.stopPropagation();"><i class="ri-add-line fs-12"></i></button>`;

                $group = $(`
                    <div class="permission-group-card mb-2" data-group="${groupName}">
                        <div class="group-header d-flex align-items-center justify-content-between p-2 rounded cursor-pointer"
                             data-bs-toggle="collapse" data-bs-target="#${uid}">
                            <div class="d-flex align-items-center">
                                <i class="ri-arrow-down-s-line group-arrow text-muted me-1 fs-5"></i>
                                <i class="ri-folder-3-fill text-warning me-2"></i>
                                <span class="fw-semibold">${groupName.charAt(0).toUpperCase() + groupName.slice(1)}</span>
                                <span class="badge ${badgeClass} ms-2 rounded-pill">0</span>
                            </div>
                            ${actionBtn}
                        </div>
                        <div class="collapse show" id="${uid}">
                            <div class="permission-list ms-4 mt-1"></div>
                        </div>
                    </div>
                `);

                // Insert alphabetically
                let inserted = false;
                $(`${container} .permission-group-card`).each(function() {
                    if ($(this).data('group').toString().toLowerCase() > groupName.toLowerCase()) {
                        $(this).before($group);
                        inserted = true;
                        return false;
                    }
                });
                if (!inserted) $(container).append($group);

                return $group;
            }

            // --- Move Permission (animated) ---
            function moveToAssigned($item, permissionData) {
                const groupName = permissionData.group_name;
                $item.addClass('removing');

                setTimeout(() => {
                    const $clone = $item.clone().removeClass('removing');

                    // Swap button
                    $clone.find('.assign-btn')
                        .removeClass('btn-soft-success assign-btn')
                        .addClass('btn-soft-danger revoke-btn')
                        .html('<i class="ri-arrow-right-line"></i>')
                        .attr('title', 'Revocar')
                        .attr('data-role-id', ROLE_ID)
                        .attr('data-permission-id', permissionData.id);

                    $clone.find('i.ri-shield-line').first()
                        .removeClass('ri-shield-line text-primary')
                        .addClass('ri-shield-check-fill text-success');

                    const $group = getOrCreateGroup('#assignedPermissionsContainer', groupName,
                        'assigned');

                    // Insert alphabetically in group
                    const permName = $clone.data('permission-name');
                    let appended = false;
                    $group.find('.permission-list .permission-item').each(function() {
                        if ($(this).data('permission-name') > permName) {
                            $(this).before($clone);
                            appended = true;
                            return false;
                        }
                    });
                    if (!appended) $group.find('.permission-list').append($clone);

                    $clone.addClass('adding');
                    setTimeout(() => $clone.removeClass('adding'), 300);

                    // Remove original
                    $item.remove();
                    cleanEmptyGroups('#availablePermissionsContainer');
                    updateCounters();
                }, 300);
            }

            function moveToAvailable($item) {
                const groupName = $item.data('group-name') || $item.closest('.permission-group-card').data(
                    'group');
                const permId = $item.data('permission-id');
                const permName = $item.data('permission-name');
                const permText = $item.find('.permission-text').text();

                $item.addClass('removing');

                setTimeout(() => {
                    const $clone = $item.clone().removeClass('removing');

                    $clone.find('.revoke-btn')
                        .removeClass('btn-soft-danger revoke-btn')
                        .addClass('btn-soft-success assign-btn')
                        .html('<i class="ri-arrow-left-line"></i>')
                        .attr('title', 'Asignar')
                        .attr('data-role-id', ROLE_ID)
                        .attr('data-permission-id', permId);

                    $clone.find('i.ri-shield-check-fill').first()
                        .removeClass('ri-shield-check-fill text-success')
                        .addClass('ri-shield-line text-primary');

                    const $group = getOrCreateGroup('#availablePermissionsContainer', groupName,
                        'available');

                    let appended = false;
                    $group.find('.permission-list .permission-item').each(function() {
                        if ($(this).data('permission-name') > permName) {
                            $(this).before($clone);
                            appended = true;
                            return false;
                        }
                    });
                    if (!appended) $group.find('.permission-list').append($clone);

                    $clone.addClass('adding');
                    setTimeout(() => $clone.removeClass('adding'), 300);

                    $item.remove();
                    cleanEmptyGroups('#assignedPermissionsContainer');
                    updateCounters();
                }, 300);
            }

            function cleanEmptyGroups(container) {
                $(`${container} .permission-group-card`).each(function() {
                    if ($(this).find('.permission-item').length === 0) {
                        $(this).remove();
                    }
                });
            }

            // --- Individual assign/revoke ---
            $(document).on('click', '.assign-btn', async function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const $btn = $(this);
                const $item = $btn.closest('.permission-item');
                const permissionId = $btn.data('permission-id');

                $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin"></i>');
                try {
                    const res = await ajaxAssign(permissionId);
                    if (res.success) {
                        moveToAssigned($item, res.permission);
                    }
                } catch (err) {
                    showNotification('danger', 'Error al asignar el permiso');
                    $btn.prop('disabled', false).html('<i class="ri-arrow-left-line"></i>');
                }
            });

            $(document).on('click', '.revoke-btn', async function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const $btn = $(this);
                const $item = $btn.closest('.permission-item');
                const permissionId = $btn.data('permission-id');

                $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin"></i>');
                try {
                    const res = await ajaxRevoke(permissionId);
                    if (res.success) {
                        moveToAvailable($item);
                    }
                } catch (err) {
                    showNotification('danger', 'Error al revocar el permiso');
                    $btn.prop('disabled', false).html('<i class="ri-arrow-right-line"></i>');
                }
            });

            // --- Group assign/revoke ---
            $(document).on('click', '.assign-group-btn', async function() {
                const $btn = $(this);
                const groupName = $btn.data('group');
                const $items = $btn.closest('.permission-group-card').find('.permission-item');

                if ($items.length === 0) return;

                $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin fs-12"></i>');

                try {
                    const promises = $items.map(function() {
                        return ajaxAssign($(this).data('permission-id'));
                    }).get();

                    const results = await Promise.all(promises);
                    results.forEach((res, i) => {
                        if (res.success) {
                            moveToAssigned($items.eq(i), res.permission);
                        }
                    });
                    showNotification('success',
                        `Grupo "${groupName}" asignado correctamente`);
                } catch (err) {
                    showNotification('danger', 'Error al asignar el grupo');
                }
            });

            $(document).on('click', '.revoke-group-btn', async function() {
                const $btn = $(this);
                const groupName = $btn.data('group');
                const $items = $btn.closest('.permission-group-card').find('.permission-item');

                if ($items.length === 0) return;
                if (!confirm(`¿Revocar todos los permisos del grupo "${groupName}"?`)) return;

                $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin fs-12"></i>');

                try {
                    const promises = $items.map(function() {
                        return ajaxRevoke($(this).data('permission-id'));
                    }).get();

                    await Promise.all(promises);
                    $items.each(function() {
                        moveToAvailable($(this));
                    });
                    showNotification('success',
                        `Grupo "${groupName}" revocado correctamente`);
                } catch (err) {
                    showNotification('danger', 'Error al revocar el grupo');
                }
            });

            // --- Bulk actions ---
            $('#assignAllVisible').on('click', async function() {
                const $items = $('#availablePermissionsContainer .permission-item:visible');
                if ($items.length === 0) {
                    showNotification('info', 'No hay permisos disponibles para asignar');
                    return;
                }
                if (!confirm(`¿Asignar ${$items.length} permisos a este rol?`)) return;

                const $btn = $(this).prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Asignando...');

                try {
                    const promises = $items.map(function() {
                        return ajaxAssign($(this).data('permission-id'));
                    }).get();

                    const results = await Promise.all(promises);
                    results.forEach((res, i) => {
                        if (res.success) moveToAssigned($items.eq(i), res.permission);
                    });
                    showNotification('success', `${results.length} permisos asignados`);
                } catch (err) {
                    showNotification('danger', 'Error al asignar algunos permisos');
                }

                $btn.prop('disabled', false).html(
                    '<i class="ri-check-double-line me-1"></i> Asignar todos');
            });

            $('#revokeAllAssigned').on('click', async function() {
                const $items = $('#assignedPermissionsContainer .permission-item');
                if ($items.length === 0) {
                    showNotification('info', 'No hay permisos asignados para revocar');
                    return;
                }
                if (!confirm(`¿Revocar los ${$items.length} permisos asignados?`)) return;

                const $btn = $(this).prop('disabled', true).html(
                    '<i class="ri-loader-4-line spin me-1"></i> Revocando...');

                try {
                    const promises = $items.map(function() {
                        return ajaxRevoke($(this).data('permission-id'));
                    }).get();

                    await Promise.all(promises);
                    $items.each(function() {
                        moveToAvailable($(this));
                    });
                    showNotification('success', 'Todos los permisos revocados');
                } catch (err) {
                    showNotification('danger', 'Error al revocar algunos permisos');
                }

                $btn.prop('disabled', false).html(
                    '<i class="ri-close-circle-line me-1"></i> Revocar todos');
            });
        });
    </script>
@endpush
