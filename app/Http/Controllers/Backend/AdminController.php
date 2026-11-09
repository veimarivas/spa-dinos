<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard(Request $request)
    {
        // Clientes con más suscripciones (con detalle para modal)
        $clientesTopSuscripciones = Cliente::with([
                'persona',
                'suscripciones.plan',
                'suscripciones.pagos',
                'parentescos.hijo.persona',
            ])
            ->withCount('suscripciones')
            ->having('suscripciones_count', '>', 0)
            ->orderByDesc('suscripciones_count')
            ->limit(10)
            ->get();

        // Sesiones de hoy y próximos 2 días
        $hoy = \Carbon\Carbon::today();
        $manana = \Carbon\Carbon::tomorrow();
        $pasadoManana = \Carbon\Carbon::today()->addDays(2);

        $sesionesHoy = \App\Models\Sesione::with(['hijo.persona', 'suscripcion.plan', 'suscripcion.cliente.persona', 'trabajador.persona'])
            ->whereDate('fecha', $hoy)
            ->whereIn('estado', ['programada', 'confirmada'])
            ->orderBy('hora_inicio')
            ->orderBy('n_sesion')
            ->get();

        $sesionesManana = \App\Models\Sesione::with(['hijo.persona', 'suscripcion.plan', 'suscripcion.cliente.persona', 'trabajador.persona'])
            ->whereDate('fecha', $manana)
            ->whereIn('estado', ['programada', 'confirmada'])
            ->orderBy('hora_inicio')
            ->orderBy('n_sesion')
            ->get();

        $sesionesPasadoManana = \App\Models\Sesione::with(['hijo.persona', 'suscripcion.plan', 'suscripcion.cliente.persona', 'trabajador.persona'])
            ->whereDate('fecha', $pasadoManana)
            ->whereIn('estado', ['programada', 'confirmada'])
            ->orderBy('hora_inicio')
            ->orderBy('n_sesion')
            ->get();

        // Clientes con deudas (considera tarifa Y descuentos de los pagos)
        $clientesConDeudasAgrupados = Cliente::whereHas('suscripciones', function ($query) {
            $query->where('estado', 'activa');
        })->with(['persona', 'suscripciones.plan', 'suscripciones.pagos', 'parentescos.hijo.persona'])->get()
            ->map(function ($cliente) {
                $deudaTotal = 0;
                $suscripcionesData = [];

                foreach ($cliente->suscripciones as $suscripcion) {
                    $plan   = $suscripcion->plan;
                    $tarifa = $suscripcion->tarifa ?? 'normal';

                    // Precio efectivo: snapshot del momento de la suscripción si existe,
                    // sino precio_especial cuando aplica, sino precio normal del plan.
                    $precio = $suscripcion->monto_total
                        ?? ($tarifa === 'especial' && $plan && !is_null($plan->precio_especial)
                            ? (float) $plan->precio_especial
                            : (float) ($plan?->precio ?? 0));

                    $totalPagado    = (float) $suscripcion->pagos->sum('pago_bs');
                    $totalDescuento = (float) $suscripcion->pagos->sum('descuento_bs');

                    // Saldo = precio - pagado - descuentos aplicados
                    $saldo = max(0, $precio - $totalPagado - $totalDescuento);

                    $nombreHijo = 'Sin hijo';
                    foreach ($cliente->parentescos as $parentesco) {
                        if ($parentesco->hijo) {
                            $nombreHijo = $parentesco->hijo->persona->nombres ?? 'Sin hijo';
                            break;
                        }
                    }

                    if ($saldo > 0) {
                        $deudaTotal += $saldo;
                        $suscripcionesData[] = [
                            'plan_nombre' => $plan?->nombre_plan ?? 'Sin plan',
                            'nombre_hijo' => $nombreHijo,
                            'tarifa' => $tarifa,
                            'precio' => $precio,
                            'total_pagado' => $totalPagado,
                            'total_descuento' => $totalDescuento,
                            'saldo' => $saldo,
                            'suscripcion' => $suscripcion,
                        ];
                    }
                }

                return [
                    'cliente' => $cliente,
                    'deuda_total' => $deudaTotal,
                    'suscripciones' => $suscripcionesData,
                ];
            })
            ->filter(function ($item) {
                return $item['deuda_total'] > 0;
            })
            ->sortByDesc('deuda_total')
            ->take(10)
            ->values();

        $clientesConDeudas = $clientesConDeudasAgrupados;

        // Recibos de hoy
        $recibosHoy = \App\Models\Pago::whereDate('fecha_pago', $hoy)
            ->with(['suscripcion.cliente.persona', 'trabajador.persona'])
            ->get()
            ->map(function ($pago) {
                $cliente = $pago->suscripcion?->cliente?->persona;

                return [
                    'recibo' => $pago->recibo,
                    'fecha' => $pago->fecha_pago,
                    'cliente' => $cliente ? ($cliente->nombres.' '.$cliente->apellido_paterno) : 'Sin cliente',
                    'cliente_id' => $pago->suscripcion->cliente->id ?? null,
                    'detalle' => $pago->suscripcion->plan->nombre_plan ?? 'Sin plan',
                    'monto' => $pago->pago_bs,
                    'descuento' => $pago->descuento_bs,
                    'tipo_pago' => $pago->tipo_pago,
                    'trabajador' => $pago->trabajador->persona->nombres ?? null,
                    'trabajador_id' => $pago->trabajador->id ?? null,
                    'pago_id' => $pago->id,
                ];
            });

        return view('admin.index', compact('clientesTopSuscripciones', 'clientesConDeudas', 'sesionesHoy', 'sesionesManana', 'sesionesPasadoManana', 'recibosHoy'));
    }

    public function estadisticas(Request $request)
    {
        $mesSeleccionado     = (int) ($request->get('mes')     ?? now()->month);
        $gestionSeleccionada = (int) ($request->get('gestion') ?? now()->year);

        $base = \Carbon\Carbon::create($gestionSeleccionada, $mesSeleccionado, 1);
        $fechaInicio = $base->copy()->startOfMonth()->format('Y-m-d');
        $fechaFin    = $base->copy()->endOfMonth()->format('Y-m-d');

        $anioActual = (int) now()->year;
        $gestionesDisponibles = range($anioActual, $anioActual - 5);

        return view('admin.estadisticas.index', compact(
            'fechaInicio',
            'fechaFin',
            'mesSeleccionado',
            'gestionSeleccionada',
            'gestionesDisponibles'
        ));
    }

    public function estadisticasServicios(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        // Debug: Registrar parámetros recibidos
        \Illuminate\Support\Facades\Log::info("Params: fecha_inicio=$fechaInicio, fecha_fin=$fechaFin");

        // Verificar si hay sesiones en la base de datos (sin filtro de fechas)
        $totalSesionesDB = \App\Models\Sesione::count();
        $sesionesRealizadasDB = \App\Models\Sesione::where('estado', 'realizada')->count();

        // Verificar si hay datos en el rango de fechas
        $sesionesEnRango = \App\Models\Sesione::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();

        \Illuminate\Support\Facades\Log::info("DB - Total: $totalSesionesDB, Realizadas: $sesionesRealizadasDB, En rango: $sesionesEnRango");

        $sesiones = \App\Models\Sesione::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('estado', 'realizada')
            ->with(['suscripcion.plan.paquete', 'planServicio.servicio'])
            ->get();

        \Illuminate\Support\Facades\Log::info('Sesiones cargadas: '.$sesiones->count());

        // Debug: ver estructura de las primeras sesiones
        if ($sesiones->isNotEmpty()) {
            $firstSesion = $sesiones->first();
            \Illuminate\Support\Facades\Log::info('Primera sesión:', [
                'id' => $firstSesion->id,
                'suscripcione_id' => $firstSesion->suscripcione_id,
                'planes_servicio_id' => $firstSesion->planes_servicio_id,
                'suscripcion' => $firstSesion->suscripcion ? $firstSesion->suscripcion->toArray() : null,
                'planServicio' => $firstSesion->planServicio ? $firstSesion->planServicio->toArray() : null,
            ]);
        }

        // Si no hay sesiones, devolver datos vacíos de forma consistente
        if ($sesiones->isEmpty()) {
            return response()->json([
                'topServicios' => [],
                'desglose' => [],
                'tendencia' => [],
                'total' => 0,
                'debug' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'sesiones_en_rango' => $sesionesEnRango,
                ],
            ]);
        }

        // Helper: precio prorrateado por sesión, según tarifa de la suscripción
        $precioPorSesion = function ($sesion) {
            $plan = $sesion->suscripcion?->plan;
            if (!$plan) return ['monto' => 0.0, 'tarifa' => 'normal'];
            $tarifa = $sesion->suscripcion->tarifa ?? 'normal';
            $precioPlan = ($tarifa === 'especial' && !is_null($plan->precio_especial))
                ? (float) $plan->precio_especial
                : (float) ($plan->precio ?? 0);
            $cantSes = (int) ($plan->cantidad_sesiones ?: 1);
            $monto = $cantSes > 0 ? $precioPlan / $cantSes : 0.0;
            return ['monto' => $monto, 'tarifa' => $tarifa];
        };

        // Obtener nombre del servicio + montos prorrateados desde planes.precio/precio_especial
        $serviciosAgrupados = $sesiones->groupBy('planes_servicio_id')->map(function ($group) use ($precioPorSesion) {
            $sesion = $group->first();
            $servicio = $sesion->planServicio?->servicio;
            $montoTotal = 0.0; $montoNormal = 0.0; $montoEspecial = 0.0;
            $cntNormal = 0; $cntEspecial = 0;
            foreach ($group as $s) {
                $r = $precioPorSesion($s);
                $montoTotal += $r['monto'];
                if ($r['tarifa'] === 'especial') { $montoEspecial += $r['monto']; $cntEspecial++; }
                else                              { $montoNormal   += $r['monto']; $cntNormal++; }
            }
            return [
                'id' => $servicio?->id,
                'nombre' => $servicio?->nombre ?? 'Sin servicio',
                'cantidad' => $group->count(),
                'cantidad_normal' => $cntNormal,
                'cantidad_especial' => $cntEspecial,
                'monto' => round($montoTotal, 2),
                'monto_normal' => round($montoNormal, 2),
                'monto_especial' => round($montoEspecial, 2),
            ];
        })->sortByDesc('cantidad')->take(5)->values();

        $totalSesiones = $sesiones->count();
        $serviciosConPorcentaje = $serviciosAgrupados->map(function ($item) use ($totalSesiones) {
            $item['porcentaje'] = $totalSesiones > 0 ? round(($item['cantidad'] / $totalSesiones) * 100, 1) : 0;

            return $item;
        });

        // Totales globales de ingresos por servicios (prorrateados)
        $totalMonto = 0.0; $totalMontoNormal = 0.0; $totalMontoEspecial = 0.0;
        $cntTotalNormal = 0; $cntTotalEspecial = 0;
        foreach ($sesiones as $s) {
            $r = $precioPorSesion($s);
            $totalMonto += $r['monto'];
            if ($r['tarifa'] === 'especial') { $totalMontoEspecial += $r['monto']; $cntTotalEspecial++; }
            else                              { $totalMontoNormal   += $r['monto']; $cntTotalNormal++; }
        }

        // Desglose: Paquete -> Plan -> Servicio (con monto)
        $desglosePaquetePlan = $sesiones->groupBy(function ($sesion) {
            $paquete = $sesion->suscripcion?->plan?->paquete;
            $plan = $sesion->suscripcion?->plan;

            return ($paquete?->id ?? 'sin_paquete').'|'.($plan?->id ?? 'sin_plan').'|'.($sesion->planes_servicio_id ?? 'sin_servicio');
        })->map(function ($group) use ($precioPorSesion) {
            $sesion = $group->first();
            $paquete = $sesion->suscripcion?->plan?->paquete;
            $plan = $sesion->suscripcion?->plan;
            $servicio = $sesion->planServicio?->servicio;
            $montoTotal = 0.0; $montoNormal = 0.0; $montoEspecial = 0.0;
            foreach ($group as $s) {
                $r = $precioPorSesion($s);
                $montoTotal += $r['monto'];
                if ($r['tarifa'] === 'especial') $montoEspecial += $r['monto'];
                else                              $montoNormal   += $r['monto'];
            }

            return [
                'paquete' => $paquete?->nombre ?? 'Sin paquete',
                'plan' => $plan?->nombre_plan ?? 'Sin plan',
                'servicio' => $servicio?->nombre ?? 'Sin servicio',
                'cantidad' => $group->count(),
                'monto' => round($montoTotal, 2),
                'monto_normal' => round($montoNormal, 2),
                'monto_especial' => round($montoEspecial, 2),
                'precio_plan' => (float) ($plan?->precio ?? 0),
                'precio_especial' => $plan?->precio_especial !== null ? (float) $plan->precio_especial : null,
            ];
        })->sortByDesc('cantidad')->take(10)->values();

        $semanas = $sesiones->groupBy(function ($sesion) {
            return $sesion->fecha->startOfWeek()->format('Y-m-d');
        })->sortKeys()->map(function ($group, $semana) {
            return [
                'semana' => $semana,
                'cantidad' => $group->count(),
            ];
        })->values();

        // Tendencia diaria
        $dias = $sesiones->groupBy(function ($sesion) {
            return $sesion->fecha->format('Y-m-d');
        })->sortKeys()->map(function ($group, $dia) {
            return [
                'dia' => \Carbon\Carbon::parse($dia)->format('d M'),
                'fecha' => $dia,
                'cantidad' => $group->count(),
            ];
        })->values();

        return response()->json([
            'topServicios' => $serviciosConPorcentaje,
            'desglose' => $desglosePaquetePlan,
            'tendenciaSemanal' => $semanas,
            'tendenciaDiaria' => $dias,
            'total' => $totalSesiones,
            'totalMonto' => round($totalMonto, 2),
            'totalMontoNormal' => round($totalMontoNormal, 2),
            'totalMontoEspecial' => round($totalMontoEspecial, 2),
            'cantidadNormal' => $cntTotalNormal,
            'cantidadEspecial' => $cntTotalEspecial,
        ]);
    }

    public function estadisticasProductos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $tipoVenta = $request->get('tipo_venta', 'todos');

        // Venta directa
        $ventasDirectas = \App\Models\VentaDetalle::whereHas('venta', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
                ->whereNull('sesione_id');
        })->with('producto')->get()
            ->groupBy('producto_id')
            ->map(function ($group) {
                $producto = $group->first()->producto;

                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad' => $group->sum('cantidad'),
                    'monto' => $group->sum('subtotal'),
                ];
            })->sortByDesc('cantidad')->take(5)->values();

        // Venta por sesión - usando la relación sesione_id en ventas
        $productosSesiones = \App\Models\VentaDetalle::whereHas('venta', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
                ->whereNotNull('sesione_id');
        })->with('producto')->get()
            ->groupBy('producto_id')
            ->map(function ($group) {
                $producto = $group->first()->producto;

                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad' => $group->sum('cantidad'),
                    'monto' => $group->sum('subtotal'),
                ];
            })->sortByDesc('cantidad')->take(5)->values();

        $totalDirecta = $ventasDirectas->sum('cantidad');
        $totalSesion = $productosSesiones->sum('cantidad');

        $tendenciaDirecta = \App\Models\Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->with('detalles')
            ->get()
            ->groupBy(function ($venta) {
                return $venta->fecha_venta->format('Y-m');
            })->sortKeys()
            ->map(function ($group, $mes) {
                return [
                    'mes' => $mes,
                    'cantidad' => $group->flatMap->detalles->sum('cantidad'),
                    'monto' => $group->sum('total_bs'),
                ];
            })->values();

        return response()->json([
            'topDirecta' => $ventasDirectas,
            'topSesion' => $productosSesiones,
            'totales' => [
                'directa' => $totalDirecta,
                'sesion' => $totalSesion,
            ],
            'tendencia' => $tendenciaDirecta,
        ]);
    }

    public function estadisticasStock(Request $request)
    {
        $categoriaId = $request->get('categoria_id');
        $filtroStock = $request->get('filtro_stock', 'todos');

        $productos = \App\Models\Producto::with('categoria')
            ->when($categoriaId, function ($q) use ($categoriaId) {
                $q->where('categoria_producto_id', $categoriaId);
            })
            ->get()
            ->map(function ($producto) {
                $stockMinimo = $producto->stock_minimo ?? 0;
                $stockActual = $producto->stock ?? 0;

                if ($stockMinimo == 0) {
                    $estado = 'ok';
                    $nivel = 'Sin mínimo';
                } else {
                    $porcentaje = ($stockActual / $stockMinimo) * 100;
                    if ($porcentaje < 50) {
                        $estado = 'agotado';
                    } elseif ($porcentaje < 100) {
                        $estado = 'bajo';
                    } else {
                        $estado = 'ok';
                    }
                }

                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'categoria' => $producto->categoria?->nombre ?? 'Sin categoría',
                    'stock' => $stockActual,
                    'stock_minimo' => $stockMinimo,
                    'estado' => $estado,
                    'diferencia' => $stockMinimo - $stockActual,
                ];
            });

        if ($filtroStock === 'bajo') {
            $productos = $productos->filter(function ($p) {
                return $p['estado'] === 'bajo';
            })->values();
        } elseif ($filtroStock === 'agotado') {
            $productos = $productos->filter(function ($p) {
                return $p['estado'] === 'agotado';
            })->values();
        }

        $alertas = $productos->filter(function ($p) {
            return in_array($p['estado'], ['bajo', 'agotado']);
        })->sortBy('stock')->take(10)->values();

        $resumenCategoria = $productos->groupBy('categoria')->map(function ($group, $categoria) {
            return [
                'categoria' => $categoria,
                'cantidad' => $group->count(),
            ];
        })->values();

        return response()->json([
            'productos' => $productos,
            'alertas' => $alertas,
            'resumenCategoria' => $resumenCategoria,
            'stats' => [
                'total' => $productos->count(),
                'bajo' => $productos->where('estado', 'bajo')->count(),
                'agotado' => $productos->where('estado', 'agotado')->count(),
                'ok' => $productos->where('estado', 'ok')->count(),
            ],
        ]);
    }
}
