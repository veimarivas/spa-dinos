<div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link menu-link main-link text-decoration-none {{ request()->routeIs('admin.dashboard') && !request()->routeIs('admin.dashboard.estadisticas*') ? 'active' : '' }}">
                    <i class="ri-dashboard-2-line"></i>
                    <span class="menu-item-text">Menú Principal</span>
                </a>
            </li>
            @can('estadisticas.listar')
            <li class="nav-item">
                <a href="{{ route('admin.dashboard.estadisticas') }}"
                    class="nav-link menu-link stat-link {{ request()->routeIs('admin.dashboard.estadisticas*') ? 'active' : '' }}">
                    <i class="ri-bar-chart-line stat-icon"></i>
                    <span class="menu-item-text">Estadísticas</span>
                </a>
            </li>
            @endcan
            @can('financiero.resumen')
            <li class="nav-item">
                <a href="{{ route('admin.finanzas.index') }}"
                    class="nav-link menu-link stat-link {{ request()->routeIs('admin.finanzas.*') ? 'active' : '' }}">
                    <i class="ri-line-chart-line stat-icon"></i>
                    <span class="menu-item-text">Resumen Financiero</span>
                </a>
            </li>
            @endcan

            <li class="menu-separator">
                <div class="menu-separator-line"></div>
            </li>

            {{-- ========== GESTIÓN DE USUARIOS ========== --}}
            @canany(['clientes.listar', 'clientes.hijos.listar', 'trabajadores.listar'])
            <li class="nav-item">
                <a href="#sidebarUsuarios" class="nav-link menu-link collapsed" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ request()->routeIs('admin.clientes.*', 'admin.hijos.*', 'admin.trabajadores.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarUsuarios">
                    <i class="ri-user-settings-line"></i>
                    <span class="menu-item-text">Gestión de Usuarios</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.clientes.*', 'admin.hijos.*', 'admin.trabajadores.*') ? 'show' : '' }}"
                    id="sidebarUsuarios">
                    <ul class="nav nav-sm flex-column">
                        @can('clientes.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.clientes.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}">
                                <i class="ri-user-line"></i> Clientes
                            </a>
                        </li>
                        @endcan
                        @can('clientes.hijos.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.hijos.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.hijos.*') ? 'active' : '' }}">
                                <i class="ri-user-heart-line"></i> Hijos
                            </a>
                        </li>
                        @endcan
                        @can('trabajadores.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.trabajadores.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.trabajadores.*') ? 'active' : '' }}">
                                <i class="ri-user-star-line"></i> Trabajadores
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            {{-- ========== BÚSQUEDA GLOBAL ========== --}}
            @can('clientes.buscar')
            <li class="nav-item">
                <a href="{{ route('admin.buscar') }}"
                    class="nav-link menu-link {{ request()->routeIs('admin.buscar*') ? 'active' : '' }}">
                    <i class="ri-search-2-line"></i>
                    <span class="menu-item-text">Búsqueda</span>
                </a>
            </li>
            @endcan

            {{-- ========== GESTIÓN DE SERVICIOS ========== --}}
            @canany(['paquetes.listar', 'servicios.listar', 'suscripciones.listar'])
            <li class="nav-item">
                <a href="#sidebarServicios" class="nav-link menu-link collapsed" data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.paquetes.*', 'admin.planes.*', 'admin.servicios.*', 'admin.suscripciones.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarServicios">
                    <i class="ri-store-3-line"></i>
                    <span class="menu-item-text">Gestión de Servicios</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.paquetes.*', 'admin.planes.*', 'admin.servicios.*', 'admin.suscripciones.*') ? 'show' : '' }}"
                    id="sidebarServicios">
                    <ul class="nav nav-sm flex-column">
                        @can('paquetes.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.paquetes.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.paquetes.*') ? 'active' : '' }}">
                                <i class="ri-coupon-line"></i> Paquetes
                            </a>
                        </li>
                        @endcan
                        @can('servicios.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.servicios.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
                                <i class="ri-service-line"></i> Servicios
                            </a>
                        </li>
                        @endcan
                        @can('suscripciones.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.suscripciones.index') }}"
                                class="nav-link {{ request()->routeIs('admin.suscripciones.*') ? 'active' : '' }}">
                                <i class="ri-calendar-line"></i> Suscripciones
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            {{-- ========== PRODUCTOS E INVENTARIO ========== --}}
            @canany(['proveedores.listar', 'categorias.listar', 'productos.listar', 'compras.listar', 'ventas.listar'])
            <li class="nav-item">
                <a href="#sidebarInventario" class="nav-link menu-link collapsed" data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.productos.*', 'admin.proveedores.*', 'admin.compras.*', 'admin.ventas.*', 'admin.categorias-productos.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarInventario">
                    <i class="ri-shopping-bag-3-line"></i>
                    <span class="menu-item-text">Productos e Inventario</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.productos.*', 'admin.proveedores.*', 'admin.compras.*', 'admin.ventas.*', 'admin.categorias-productos.*') ? 'show' : '' }}"
                    id="sidebarInventario">
                    <ul class="nav nav-sm flex-column">
                        @can('proveedores.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.proveedores.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}">
                                <i class="ri-truck-line"></i> Proveedores
                            </a>
                        </li>
                        @endcan
                        @can('categorias.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.categorias-productos.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.categorias-productos.*') ? 'active' : '' }}">
                                <i class="ri-price-tag-3-line"></i> Categorías
                            </a>
                        </li>
                        @endcan
                        @can('productos.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.productos.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                                <i class="ri-box-3-line"></i> Productos
                            </a>
                        </li>
                        @endcan
                        @can('compras.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.compras.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.compras.*') ? 'active' : '' }}">
                                <i class="ri-shopping-cart-line"></i> Compras
                            </a>
                        </li>
                        @endcan
                        @can('ventas.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.ventas.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}">
                                <i class="ri-shopping-bag-line"></i> Ventas
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            {{-- ========== CALENDARIOS Y EVENTOS ========== --}}
            @canany(['cronogramas.listar', 'cumples.listar'])
            <li class="nav-item">
                <a href="#sidebarCalendarios" class="nav-link menu-link collapsed" data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.calendario.sesiones.*', 'admin.cumpleanos.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarCalendarios">
                    <i class="ri-calendar-line"></i>
                    <span class="menu-item-text">Calendarios y Eventos</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.calendario.sesiones.*', 'admin.cumpleanos.*') ? 'show' : '' }}"
                    id="sidebarCalendarios">
                    <ul class="nav nav-sm flex-column">
                        @can('cronogramas.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.calendario.sesiones') }}"
                                class="nav-link {{ request()->routeIs('admin.calendario.sesiones.*') ? 'active' : '' }}">
                                <i class="ri-calendar-check-line"></i> Cronogramas
                            </a>
                        </li>
                        @endcan
                        @can('cumples.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.cumpleanos.calendario') }}"
                                class="nav-link {{ request()->routeIs('admin.cumpleanos.*') ? 'active' : '' }}">
                                <i class="ri-cake-3-line"></i> Cumpleaños
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcanany

            {{-- ========== FINANZAS Y REPORTES ========== --}}
            @canany(['recibos.listar', 'cobros.listar', 'gastos.listar', 'gastos.categorias.listar'])
            <li class="nav-item">
                <a href="#sidebarFinanzas" class="nav-link menu-link collapsed" data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.finanzas.*', 'admin.recibos.*', 'admin.cobros.*', 'admin.gastos.*', 'admin.categorias-gastos.*', 'admin.dashboard.estadisticas*', 'admin.pagosTrabajadores.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarFinanzas">
                    <i class="ri-hand-coin-line"></i>
                    <span class="menu-item-text">Finanzas y Reportes</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.finanzas.*', 'admin.recibos.*', 'admin.cobros.*', 'admin.gastos.*', 'admin.categorias-gastos.*', 'admin.dashboard.estadisticas*', 'admin.pagosTrabajadores.*') ? 'show' : '' }}"
                    id="sidebarFinanzas">
                    <ul class="nav nav-sm flex-column">
                        @can('recibos.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.recibos.reporte') }}"
                                class="nav-link {{ request()->routeIs('admin.recibos.*') ? 'active' : '' }}">
                                <i class="ri-receipt-line"></i> Recibos
                            </a>
                        </li>
                        @endcan
                        @can('cobros.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.cobros.index') }}"
                                class="nav-link {{ request()->routeIs('admin.cobros.*') ? 'active' : '' }}">
                                <i class="ri-bank-card-line"></i> Cobros
                            </a>
                        </li>
                        @endcan
                        @can('gastos.categorias.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.categorias-gastos.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.categorias-gastos.*') ? 'active' : '' }}">
                                <i class="ri-price-tag-3-line"></i> Categorías Gastos
                            </a>
                        </li>
                        @endcan
                        @can('gastos.listar')
                        <li class="nav-item">
                            <a href="{{ route('admin.gastos.listar') }}"
                                class="nav-link {{ request()->routeIs('admin.gastos.*') ? 'active' : '' }}">
                                <i class="ri-money-dollar-circle-line"></i> Gastos
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{ route('admin.pagosTrabajadores.index') }}"
                                class="nav-link {{ request()->routeIs('admin.pagosTrabajadores.*') ? 'active' : '' }}">
                                <i class="ri-team-line"></i> Pagos Trabajadores
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endcanany
            <!-- Administración del Sistema -->
            @can('administracion.listar')
            <li class="nav-item">
                <a class="nav-link menu-link collapsed {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'active' : '' }}"
                    href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'true' : 'false' }}"
                    aria-controls="sidebarAdmin">
                    <i class="ri-settings-3-line"></i>
                    <span>Administración</span>
                </a>
                <div class="collapse menu-dropdown {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*', 'admin.sedes.*', 'admin.departamentos.*', 'admin.cargos.*', 'admin.fases.*', 'admin.cuentas.*') ? 'show' : '' }}"
                    id="sidebarAdmin">
                    <ul class="nav nav-sm flex-column">
                        <!-- Gestión de Usuarios y Permisos -->
                        <li class="nav-item">
                            <a href="#sidebarPermisos" class="nav-link collapsed" data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*') ? 'true' : 'false' }}"
                                aria-controls="sidebarPermisos">
                                <i class="ri-shield-keyhole-line"></i>
                                Roles y Permisos
                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.permissions.*', 'admin.roles.*', 'admin.role-permissions.*', 'admin.users.*') ? 'show' : '' }}"
                                id="sidebarPermisos">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permissions.listar') }}"
                                            class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                            Permisos
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.roles.listar') }}"
                                            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                            Roles
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.role-permissions.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.role-permissions.*') ? 'active' : '' }}">
                                            Asignar Permisos
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.listar') }}"
                                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                            Usuarios
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            @endcan
        </ul>
    </div>
</div>

<style>
    /* Estilos específicos para el sidebar - sobrescriben Velzon */
    [data-sidebar="dark"] .navbar-brand-box {
        background: linear-gradient(135deg, var(--dino-green-dark) 0%, var(--turquoise-dark) 100%);
    }

    [data-sidebar="dark"] .menu-title-text {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link:hover {
        background-color: var(--sidebar-hover);
        color: var(--white) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link.active {
        background: var(--sidebar-active);
        color: var(--white) !important;
    }

    [data-sidebar="dark"] .menu-dropdown {
        background-color: rgba(255, 255, 255, 0.03);
        border-left-color: var(--dino-green-light);
    }

    [data-sidebar="dark"] .menu-dropdown .nav-link {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    [data-sidebar="dark"] .menu-dropdown .nav-link:hover,
    [data-sidebar="dark"] .menu-dropdown .nav-link.active {
        color: var(--dino-green) !important;
    }

    /* Estadísticas link mejorado */
    .stat-link {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        padding: 14px 18px !important;
        margin: 10px 14px !important;
        border-radius: 14px !important;
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.2) 0%, rgba(13, 148, 136, 0.15) 100%) !important;
        border: 1px solid rgba(82, 183, 136, 0.4) !important;
        transition: all 0.3s ease !important;
    }

    .stat-link:hover {
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.3) 0%, rgba(13, 148, 136, 0.25) 100%) !important;
        border-color: var(--dino-green) !important;
        transform: translateX(3px) !important;
    }

    .stat-link.active {
        background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%) !important;
        border-color: var(--dino-green) !important;
        box-shadow: 0 4px 15px rgba(82, 183, 136, 0.35) !important;
    }

    .stat-icon {
        font-size: 1.25rem !important;
        color: var(--dino-green) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 24px !important;
        height: 24px !important;
    }

    .stat-link.active .stat-icon {
        color: white !important;
    }

    .stat-link .menu-item-text {
        flex: 1;
        font-weight: 500;
    }

    .stat-link.active .menu-item-text {
        color: white !important;
        font-weight: 600 !important;
    }

    /* Menú Principal link */
    .main-link {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        padding: 14px 18px !important;
        margin: 10px 14px !important;
        border-radius: 14px !important;
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.2) 0%, rgba(168, 85, 247, 0.15) 100%) !important;
        border: 1px solid rgba(124, 58, 237, 0.4) !important;
        transition: all 0.3s ease !important;
    }

    .main-link:hover {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.3) 0%, rgba(168, 85, 247, 0.25) 100%) !important;
        border-color: #7c3aed !important;
        transform: translateX(3px) !important;
    }

    .main-link.active {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%) !important;
        border-color: #7c3aed !important;
        box-shadow: 0 4px 15px rgba(124, 58, 237, 0.35) !important;
    }

    .main-icon {
        font-size: 1.4rem !important;
        width: 28px !important;
        text-align: center !important;
        line-height: 1 !important;
    }

    .main-link:hover .main-icon,
    .main-link.active .main-icon {
        transform: scale(1.1) !important;
    }

    .main-link .menu-item-text {
        flex: 1;
        font-weight: 500;
    }

    .main-link.active .menu-item-text {
        color: white !important;
        font-weight: 600 !important;
    }

    .stat-icon {
        font-size: 1.25rem !important;
        color: var(--dino-green) !important;
        width: 24px !important;
        text-align: center !important;
    }

    .stat-link:hover .stat-icon,
    .stat-link.active .stat-icon {
        color: white !important;
    }

    .stat-link .menu-item-text {
        flex: 1;
        font-weight: 500;
    }

    .stat-link.active .menu-item-text {
        color: white !important;
        font-weight: 600 !important;
    }

    .menu-separator {
        padding: 8px 14px;
    }

    .menu-separator-line {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
    }

    [data-layout="horizontal"] .stat-link,
    [data-layout="horizontal"] .main-link {
        display: none;
    }

    /* Sidebar compact/condensed */
    [data-sidebar-size="condensed"] .main-link,
    [data-sidebar-size="condensed"] .stat-link {
        justify-content: center !important;
        padding: 12px !important;
        margin: 8px 6px !important;
    }

    [data-sidebar-size="condensed"] .main-link .menu-item-text,
    [data-sidebar-size="condensed"] .stat-link .menu-item-text {
        display: none !important;
    }

    [data-sidebar-size="condensed"] .main-icon {
        font-size: 1.3rem !important;
    }

    [data-sidebar-size="condensed"] .stat-icon {
        font-size: 1.2rem !important;
    }

    [data-sidebar-size="condensed"] .menu-separator {
        padding: 4px 6px !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link i {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    [data-sidebar="dark"] .navbar-nav .nav-link:hover i,
    [data-sidebar="dark"] .navbar-nav .nav-link.active i {
        color: var(--dino-green) !important;
    }

    [data-sidebar="dark"] .menu-arrow {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    [data-sidebar="dark"] .nav-link[aria-expanded="true"] .menu-arrow {
        color: var(--dino-green) !important;
    }

    /* Scrollbar para sidebar oscuro */
    [data-sidebar="dark"] #scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    [data-sidebar="dark"] #scrollbar::-webkit-scrollbar-thumb {
        background: var(--dino-green);
    }

    [data-sidebar="dark"] #scrollbar::-webkit-scrollbar-thumb:hover {
        background: var(--dino-green-dark);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de estado activo basado en URL
        function updateActiveState() {
            const currentPath = window.location.pathname;
            const allLinks = document.querySelectorAll('.navbar-nav a[href]');

            allLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#')) {
                    try {
                        const url = new URL(href, window.location.origin);
                        if (currentPath === url.pathname) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    } catch (e) {
                        if (currentPath === href || currentPath.startsWith(href)) {
                            link.classList.add('active');
                            expandParentMenus(link);
                        }
                    }
                }
            });
            updateMenuArrows();
        }

        function expandParentMenus(element) {
            let parentCollapse = element.closest('.collapse');
            while (parentCollapse) {
                const collapseId = parentCollapse.id;
                const trigger = document.querySelector(`[href="#${collapseId}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                    parentCollapse.classList.add('show');
                    trigger.classList.add('active');
                }
                parentCollapse = parentCollapse.parentElement.closest('.collapse');
            }
        }

        function updateMenuArrows() {
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
                const targetId = trigger.getAttribute('href');
                const target = document.querySelector(targetId);
                const arrow = trigger.querySelector('.menu-arrow');
                if (target && target.classList.contains('show')) {
                    trigger.setAttribute('aria-expanded', 'true');
                    if (arrow) arrow.style.transform = 'rotate(90deg)';
                } else if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Event listeners para flechas
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
            trigger.addEventListener('click', function() {
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    setTimeout(() => {
                        arrow.style.transform = this.getAttribute('aria-expanded') ===
                            'true' ?
                            'rotate(90deg)' : 'rotate(0deg)';
                    }, 150);
                }
            });
        });

        updateActiveState();
        window.addEventListener('popstate', updateActiveState);
    });
</script>
