<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BusquedaController;
use App\Http\Controllers\Backend\CategoriasProductosController;
use App\Http\Controllers\Backend\ClientesController;
use App\Http\Controllers\Backend\ComprasController;
use App\Http\Controllers\Backend\CumpleanosController;
use App\Http\Controllers\Backend\FinanzasController;
use App\Http\Controllers\Backend\CategoriasGastosController;
use App\Http\Controllers\Backend\GastosController;
use App\Http\Controllers\Backend\HijosController;
use App\Http\Controllers\Backend\PaquetesController;
use App\Http\Controllers\Backend\PerfilController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\PlanesController;
use App\Http\Controllers\Backend\ProductosController;
use App\Http\Controllers\Backend\ProveedoresController;
use App\Http\Controllers\Backend\RolePermissionsController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ServiciosController;
use App\Http\Controllers\Backend\SuscripcionesController;
use App\Http\Controllers\Backend\TrabajadoresController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\VentasController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// /Admin Routes
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ESTADÍSTICAS
    Route::get('/admin/dashboard/estadisticas', [AdminController::class, 'estadisticas'])->name('admin.dashboard.estadisticas');
    Route::get('/admin/dashboard/estadisticas/servicios', [AdminController::class, 'estadisticasServicios'])->name('admin.dashboard.estadisticas.servicios');
    Route::get('/admin/dashboard/estadisticas/productos', [AdminController::class, 'estadisticasProductos'])->name('admin.dashboard.estadisticas.productos');
    Route::get('/admin/dashboard/estadisticas/stock', [AdminController::class, 'estadisticasStock'])->name('admin.dashboard.estadisticas.stock');

    // PAQUETES
    Route::controller(PaquetesController::class)->group(function () {
        Route::get('/admin/paquetes/listar', 'listar')->name('admin.paquetes.listar');
        Route::get('/admin/paquetes/ver/{id}', 'ver')->name('admin.paquetes.ver');
        Route::post('/admin/paquetes/registrar', 'registrar')->name('admin.paquetes.registrar');
        Route::post('/admin/paquetes/verificar', 'verificarNombre')->name('admin.paquetes.verificar');
        Route::post('/admin/paquetes/verificaredicion', 'verificarNombreEdicion')->name('admin.paquetes.verificaredicion');
        Route::put('/admin/paquetes/modificar', 'modificar')->name('admin.paquetes.modificar');
        Route::delete('/admin/paquetes/eliminar', 'eliminar')->name('admin.paquetes.eliminar');
    });

    // PLANES
    Route::controller(PlanesController::class)->group(function () {
        Route::get('/admin/planes/listar', 'listar')->name('admin.planes.listar');
        Route::get('/admin/planes/ver/{id}', 'ver')->name('admin.planes.ver');
        Route::post('/admin/planes/registrar', 'registrar')->name('admin.planes.registrar');
        Route::post('/admin/planes/verificar', 'verificarNombre')->name('admin.planes.verificar');
        Route::post('/admin/planes/verificaredicion', 'verificarNombreEdicion')->name('admin.planes.verificaredicion');
        Route::put('/admin/planes/modificar', 'modificar')->name('admin.planes.modificar');
        Route::delete('/admin/planes/eliminar', 'eliminar')->name('admin.planes.eliminar');

        Route::get('/admin/planes/{plan}/servicios', 'servicios')->name('admin.planes.servicios');
        Route::post('/admin/planes/{plan}/servicios', 'guardarServicios')->name('admin.planes.guardarServicios');
    });

    // CATEGORÍAS DE PRODUCTOS
    Route::controller(CategoriasProductosController::class)->group(function () {
        Route::get('/admin/categorias-productos/listar', 'listar')->name('admin.categorias-productos.listar');
        Route::post('/admin/categorias-productos/registrar', 'registrar')->name('admin.categorias-productos.registrar');
        Route::post('/admin/categorias-productos/verificar', 'verificarNombre')->name('admin.categorias-productos.verificar');
        Route::post('/admin/categorias-productos/verificaredicion', 'verificarNombreEdicion')->name('admin.categorias-productos.verificaredicion');
        Route::put('/admin/categorias-productos/modificar', 'modificar')->name('admin.categorias-productos.modificar');
        Route::delete('/admin/categorias-productos/eliminar', 'eliminar')->name('admin.categorias-productos.eliminar');

        Route::get('/admin/categorias-productos/ver/{id}', [CategoriasProductosController::class, 'ver'])->name('admin.categorias-productos.ver');
        Route::get('/admin/categorias-productos/ver/{id}/productos', [CategoriasProductosController::class, 'productos'])->name('admin.categorias-productos.productos');
    });

    // PROVEEDORES
    Route::controller(ProveedoresController::class)->group(function () {
        Route::get('/admin/proveedores/listar', 'listar')->name('admin.proveedores.listar');
        Route::post('/admin/proveedores/registrar', 'registrar')->name('admin.proveedores.registrar');
        Route::post('/admin/proveedores/verificar', 'verificarNombre')->name('admin.proveedores.verificar');
        Route::post('/admin/proveedores/verificaredicion', 'verificarNombreEdicion')->name('admin.proveedores.verificaredicion');
        Route::put('/admin/proveedores/modificar', 'modificar')->name('admin.proveedores.modificar');
        Route::delete('/admin/proveedores/eliminar', 'eliminar')->name('admin.proveedores.eliminar');

        Route::get('/admin/proveedores/ver/{id}', [ProveedoresController::class, 'ver'])->name('admin.proveedores.ver');
    });

    // PRODUCTOS
    Route::controller(ProductosController::class)->group(function () {
        Route::get('/admin/productos/listar', 'listar')->name('admin.productos.listar');
        Route::post('/admin/productos/registrar', 'registrar')->name('admin.productos.registrar');
        Route::post('/admin/productos/verificar', 'verificarNombre')->name('admin.productos.verificar');
        Route::post('/admin/productos/verificaredicion', 'verificarNombreEdicion')->name('admin.productos.verificaredicion');
        Route::put('/admin/productos/modificar', 'modificar')->name('admin.productos.modificar');
        Route::delete('/admin/productos/eliminar', 'eliminar')->name('admin.productos.eliminar');
    });

    // COMPRAS
    Route::controller(ComprasController::class)->group(function () {
        Route::get('/admin/compras/listar', 'listar')->name('admin.compras.listar');
        Route::get('/admin/compras/crear', 'create')->name('admin.compras.create');
        Route::post('/admin/compras/guardar', 'store')->name('admin.compras.store');
        Route::get('/admin/compras/ver/{id}', 'show')->name('admin.compras.show');
        Route::post('/admin/compras/buscar-producto', 'buscarProducto')->name('admin.compras.buscarProducto');
        Route::post('/admin/compras/buscar-proveedor', 'buscarProveedor')->name('admin.compras.buscarProveedor');
    });

    // SERVICIOS
    Route::controller(ServiciosController::class)->group(function () {
        Route::get('/admin/servicios/listar', 'listar')->name('admin.servicios.listar');
        Route::get('/admin/servicios/ver/{id}', 'ver')->name('admin.servicios.ver');
        Route::post('/admin/servicios/registrar', 'registrar')->name('admin.servicios.registrar');
        Route::post('/admin/servicios/verificar', 'verificarNombre')->name('admin.servicios.verificar');
        Route::post('/admin/servicios/verificaredicion', 'verificarNombreEdicion')->name('admin.servicios.verificaredicion');
        Route::put('/admin/servicios/modificar', 'modificar')->name('admin.servicios.modificar');
        Route::delete('/admin/servicios/eliminar', 'eliminar')->name('admin.servicios.eliminar');
    });

    Route::controller(SuscripcionesController::class)->group(function () {
        Route::get('/admin/suscripciones', 'index')->name('admin.suscripciones.index');
        Route::get('/admin/suscripciones/crear', 'create')->name('admin.suscripciones.create');
        Route::post('/admin/suscripciones/guardar', 'store')->name('admin.suscripciones.store');
        Route::get('/admin/suscripciones/ver/{id}', 'show')->name('admin.suscripciones.show');
        Route::get('/admin/suscripciones/editar/{id}', 'edit')->name('admin.suscripciones.edit');
        Route::put('/admin/suscripciones/actualizar/{id}', [SuscripcionesController::class, 'update'])->name('admin.suscripciones.update');
        Route::delete('/admin/suscripciones/eliminar/{id}', 'destroy')->name('admin.suscripciones.destroy');
        // Rutas AJAX para búsqueda
        Route::post('/admin/suscripciones/buscar-cliente', 'buscarCliente')->name('admin.suscripciones.buscarCliente');
        Route::post('/admin/suscripciones/buscar-hijo', 'buscarHijo')->name('admin.suscripciones.buscarHijo');
        Route::post('/admin/suscripciones/hijos-por-cliente', 'hijosPorCliente')->name('admin.suscripciones.hijosPorCliente');

        Route::post('/admin/suscripciones/planes-por-paquete', 'planesPorPaquete')->name('admin.suscripciones.planesPorPaquete');

        Route::post('/admin/sesiones/{sesion}/reprogramar', [SuscripcionesController::class, 'reprogramar'])
            ->name('admin.sesiones.reprogramar');

        Route::patch('/admin/sesiones/{sesion}/editar', [SuscripcionesController::class, 'editarSesion'])
            ->name('admin.sesiones.editar');

        Route::patch('/admin/sesiones/{sesion}/informe', [SuscripcionesController::class, 'guardarInforme'])
            ->name('admin.sesiones.informe');

        Route::patch('/admin/sesiones/{sesion}/estado', [SuscripcionesController::class, 'cambiarEstado'])
            ->name('admin.sesiones.cambiarEstado');

        Route::get('/admin/calendario-sesiones', [SuscripcionesController::class, 'calendario'])->name('admin.calendario.sesiones');

        Route::get('/admin/sesiones/{sesion}/detalle', [SuscripcionesController::class, 'detalleSesion'])
            ->name('admin.sesiones.detalle');

        Route::post('/admin/suscripciones/horas-ocupadas', [SuscripcionesController::class, 'horasOcupadas'])
            ->name('admin.suscripciones.horasOcupadas');

        Route::get('/admin/suscripciones/calendario-ocupados', [SuscripcionesController::class, 'sesionesCalendarioJson'])
            ->name('admin.suscripciones.calendarioOcupados');

        Route::post('/admin/suscripciones/servicios-por-plan', [SuscripcionesController::class, 'serviciosPorPlan'])->name('admin.suscripciones.serviciosPorPlan');

        Route::get('/admin/suscripciones/{id}/pagos', 'detallePagos')->name('admin.suscripciones.detallePagos');
        Route::get('/admin/suscripciones/pagos/{pagoId}/recibo-pdf', 'generarReciboPdf')
            ->name('admin.suscripciones.reciboPdf');
        Route::get('/admin/suscripciones/{id}/pagos/crear', 'createPago')->name('admin.suscripciones.pagos.create');
        Route::post('/admin/suscripciones/{id}/pagos/guardar', 'storePago')->name('admin.suscripciones.pagos.store');
        Route::post('/admin/suscripciones/{id}/agendar-pendientes', 'agendarPendientes')->name('admin.suscripciones.agendarPendientes');

        // Reporte de Recibos/Pagos
        Route::get('/admin/recibos/reporte', 'reporteRecibos')->name('admin.recibos.reporte');
        Route::get('/admin/recibos/descargar-pdf', 'descargarRecibosPdf')->name('admin.recibos.pdf');

        Route::get('/admin/recibos/reporte-trabajadores-pdf', [SuscripcionesController::class, 'reporteTrabajadoresPdf'])->name('admin.recibos.trabajadoresPdf');

        Route::get('/admin/cobros', [SuscripcionesController::class, 'cobrosIndex'])->name('admin.cobros.index');
    });

    // Clientes
    Route::controller(ClientesController::class)->group(function () {
        Route::get('/admin/clientes/listar', 'listar')->name('admin.clientes.listar');
        Route::get('/admin/clientes/seguimiento/{id}', 'seguimiento')->name('admin.clientes.seguimiento');
        Route::post('/admin/clientes/registrar', 'registrar')->name('admin.clientes.registrar');
        Route::post('/admin/clientes/verificar-carnet', 'verificarCarnet')->name('admin.clientes.verificarCarnet');
        Route::post('/admin/clientes/verificar-carnet-edicion', 'verificarCarnetEdicion')->name('admin.clientes.verificarCarnetEdicion');
        Route::post('/admin/clientes/verificar-correo', 'verificarCorreo')->name('admin.clientes.verificarCorreo');
        Route::put('/admin/clientes/modificar', 'modificar')->name('admin.clientes.modificar');
        Route::delete('/admin/clientes/eliminar', 'eliminar')->name('admin.clientes.eliminar');
        // AJAX para hijos (opcional, pero ya existe en SuscripcionesController)
        Route::post('/admin/clientes/hijos-por-cliente', 'hijosPorCliente')->name('admin.clientes.hijosPorCliente');

        Route::post('/admin/clientes/{cliente}/registrar-hijo', [ClientesController::class, 'registrarHijo'])->name('admin.clientes.registrar-hijo');
    });

    Route::controller(HijosController::class)->group(function () {
        Route::get('/admin/hijos/listar', 'listar')->name('admin.hijos.listar');
        Route::get('/admin/hijos/seguimiento/{id}', 'seguimiento')->name('admin.hijos.seguimiento');
    });

    // Búsqueda global
    Route::controller(BusquedaController::class)->group(function () {
        Route::get('/admin/buscar', 'index')->name('admin.buscar');
        Route::get('/admin/buscar/resultados', 'buscar')->name('admin.buscar.resultados');
    });

    // TRABAJADORES
    Route::controller(TrabajadoresController::class)->group(function () {
        Route::get('/admin/trabajadores/listar', 'listar')->name('admin.trabajadores.listar');
        Route::post('/admin/trabajadores/registrar', 'registrar')->name('admin.trabajadores.registrar');
        Route::post('/admin/trabajadores/verificar', 'verificarCarnet')->name('admin.trabajadores.verificarCarnet');
        Route::post('/admin/trabajadores/verificaredicion', 'verificarCarnetEdicion')->name('admin.trabajadores.verificarCarnetEdicion');
        Route::post('/admin/trabajadores/verificar-correo', 'verificarCorreo')->name('admin.trabajadores.verificarCorreo');
        Route::put('/admin/trabajadores/modificar', 'modificar')->name('admin.trabajadores.modificar');
        Route::delete('/admin/trabajadores/eliminar', 'eliminar')->name('admin.trabajadores.eliminar');
        Route::get('/admin/trabajadores/cargos', 'getCargos')->name('admin.trabajadores.cargos');
        Route::get('/admin/trabajadores/{id}/ver', 'ver')->name('admin.trabajadores.ver');
        Route::get('/admin/trabajadores/{id}/informes', 'getInformes')->name('admin.trabajadores.informes');
        Route::get('/admin/trabajadores/{id}/salarios', 'getSalariosResumen')->name('admin.trabajadores.salarios');
        Route::post('/admin/trabajadores/{id}/pagar-mes', 'pagarSalarioMes')->name('admin.trabajadores.pagarMes');
        Route::get('/admin/trabajadores/{id}/salario-pagado/pdf', 'salarioPagadoPdf')->name('admin.trabajadores.salarioPagadoPdf');
        Route::get('/admin/finanzas/pagos-trabajadores', 'pagosTrabajadoresIndex')->name('admin.pagosTrabajadores.index');
    });

    Route::controller(CumpleanosController::class)->group(function () {
        Route::get('/admin/cumpleanos/calendario', 'calendario')->name('admin.cumpleanos.calendario');
    });

    Route::controller(PermissionsController::class)->group(function () {
        Route::get('/admin/permissions/listar', 'permissionsListar')->name('admin.permissions.listar');
        Route::post('/admin/permissions/registrar', 'permissionsRegistrar')->name('admin.permissions.registrar');
        Route::post('/admin/permissions/verificar', 'verificarNombre')->name('admin.permissions.verificar');
        Route::post('/admin/permissions/verificaredicion', 'verificarNombreEdicion')->name('admin.permissions.verificaredicion');
        Route::post('/admin/permissions/modificar', 'permissionsModificar')->name('admin.permissions.modificar');
        Route::delete('/admin/permissions/eliminar', 'permissionsEliminar')->name('admin.permissions.eliminar');
        Route::get('/permissions/groups', 'getGroups')->name('admin.permissions.groups');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('/admin/roles/listar', 'rolesListar')->name('admin.roles.listar');
        Route::post('/admin/roles/registrar', 'rolesRegistrar')->name('admin.roles.registrar');
        Route::post('/admin/roles/verificar', 'verificarNombre')->name('admin.roles.verificar');
        Route::post('/admin/roles/verificaredicion', 'verificarNombreEdicion')->name('admin.roles.verificaredicion');
        Route::post('/admin/roles/modificar', 'rolesModificar')->name('admin.roles.modificar');
        Route::delete('/admin/roles/eliminar', 'rolesEliminar')->name('admin.roles.eliminar');
    });

    Route::controller(RolePermissionsController::class)->group(function () {
        Route::get('/admin/role-permissions', 'index')->name('admin.role-permissions.index');
        Route::get('/admin/role-permissions/{id}', 'show')->name('admin.role-permissions.show');
        Route::post('/admin/role-permissions/assign', 'assignPermission')->name('admin.role-permissions.assign');
        Route::post('/admin/role-permissions/revoke', 'revokePermission')->name('admin.role-permissions.revoke');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('/admin/users/listar', 'listar')->name('admin.users.listar');
        Route::post('/admin/users/verificar-email', 'verificarEmail')->name('admin.users.verificar-email');
        Route::post('/admin/users/actualizar', 'actualizar')->name('admin.users.actualizar');
        Route::post('/admin/users/obtener-data', 'obtenerUserData')->name('admin.users.obtener-data');
        Route::post('/admin/users/reset-password', 'resetPassword')->name('admin.users.reset-password');
        Route::post('/admin/users/crear-cuenta-trabajador', 'crearCuentaTrabajador')->name('admin.users.crearCuentaTrabajador');
    });

    // Perfil del usuario autenticado
    Route::controller(PerfilController::class)->group(function () {
        Route::get('/admin/perfil', 'index')->name('admin.perfil');
        Route::post('/admin/perfil/cambiar-password', 'cambiarPassword')->name('admin.perfil.cambiarPassword');
    });

    // FINANZAS
    Route::controller(FinanzasController::class)->group(function () {
        Route::get('/admin/finanzas', 'index')->name('admin.finanzas.index');
    });

    // VENTAS
    Route::controller(VentasController::class)->group(function () {
        Route::get('/admin/ventas/listar', 'listar')->name('admin.ventas.listar');
        Route::get('/admin/ventas/crear', 'create')->name('admin.ventas.create');
        Route::post('/admin/ventas/guardar', 'store')->name('admin.ventas.store');
        Route::get('/admin/ventas/ver/{id}', 'show')->name('admin.ventas.show');
        Route::post('/admin/ventas/buscar-cliente', 'buscarCliente')->name('admin.ventas.buscarCliente');
        Route::post('/admin/ventas/buscar-producto', 'buscarProducto')->name('admin.ventas.buscarProducto');
        Route::post('/admin/sesiones/{sesion}/venta', 'storeDesdeSesion')->name('admin.sesiones.venta.store');
        Route::get('/admin/ventas/{venta}/recibo-pdf', 'reciboPdf')->name('admin.ventas.reciboPdf');
    });

    // CATEGORÍAS DE GASTOS
    Route::controller(CategoriasGastosController::class)->group(function () {
        Route::get('/admin/categorias-gastos/listar', 'listar')->name('admin.categorias-gastos.listar');
        Route::post('/admin/categorias-gastos/registrar', 'registrar')->name('admin.categorias-gastos.registrar');
        Route::post('/admin/categorias-gastos/verificar', 'verificarNombre')->name('admin.categorias-gastos.verificar');
        Route::post('/admin/categorias-gastos/verificaredicion', 'verificarNombreEdicion')->name('admin.categorias-gastos.verificaredicion');
        Route::put('/admin/categorias-gastos/modificar', 'modificar')->name('admin.categorias-gastos.modificar');
        Route::delete('/admin/categorias-gastos/eliminar', 'eliminar')->name('admin.categorias-gastos.eliminar');
        Route::get('/admin/categorias-gastos/obtener-categorias', 'obtenerCategorias')->name('admin.categorias-gastos.obtener-categorias');
    });

    // GASTOS
    Route::controller(GastosController::class)->group(function () {
        Route::get('/admin/gastos/listar', 'listar')->name('admin.gastos.listar');
        Route::post('/admin/gastos/registrar', 'registrar')->name('admin.gastos.registrar');
        Route::post('/admin/gastos/verificar-periodo', 'verificarPeriodo')->name('admin.gastos.verificarPeriodo');
        Route::put('/admin/gastos/modificar', 'modificar')->name('admin.gastos.modificar');
        Route::delete('/admin/gastos/eliminar', 'eliminar')->name('admin.gastos.eliminar');
        Route::get('/admin/gastos/{id}/detalle-pdf', 'detallePdf')->name('admin.gastos.detallePdf');
    });
});
require __DIR__.'/auth.php';
