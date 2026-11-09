<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Sesione;
use App\Models\Trabajadore;
use Barryvdh\DomPDF\Facade\Pdf;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaPagoDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $fechaDesde = $request->get('fecha_desde', '');
        $fechaHasta = $request->get('fecha_hasta', '');

        $query = Venta::with(['cliente.persona', 'detalles', 'sesion.suscripcion.cliente.persona', 'sesion.planServicio.servicio']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('cliente.persona', function ($qp) use ($search) {
                        $qp->where('nombres', 'like', "%{$search}%")
                            ->orWhere('apellido_paterno', 'like', "%{$search}%")
                            ->orWhere('apellido_materno', 'like', "%{$search}%")
                            ->orWhere('carnet', 'like', "%{$search}%");
                    });
            });
        }

        if ($fechaDesde) {
            $query->whereDate('fecha_venta', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('fecha_venta', '<=', $fechaHasta);
        }

        $ventas = $query->orderByDesc('fecha_venta')->orderByDesc('id')->paginate(10);

        // Stats
        $totalVentas = Venta::count();
        $totalMonto = Venta::sum('total_bs');
        $totalHoy = Venta::whereDate('fecha_venta', Carbon::today())->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.ventas.partials.table-body', compact('ventas'))->render(),
                'pagination' => (string) $ventas->links('pagination::bootstrap-5'),
                'totalVentas' => $totalVentas,
                'totalMonto' => number_format($totalMonto, 2),
                'totalHoy' => $totalHoy,
            ]);
        }

        return view('admin.ventas.listar', compact('ventas', 'totalVentas', 'totalMonto', 'totalHoy'));
    }

    public function create()
    {
        $productos = Producto::where('estado', 'activo')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('admin.ventas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_tipo' => 'required|in:existente,nuevo,sin_cliente',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'total_bs' => 'required|numeric|min:0',
            'descuento_bs' => 'nullable|numeric|min:0',
            'tipo_pago' => 'required|in:efectivo,qr,parcial',
        ], [
            'items.required' => 'Debe agregar al menos un producto a la venta.',
            'items.min' => 'Debe agregar al menos un producto a la venta.',
            'tipo_pago.required' => 'Seleccione un tipo de pago.',
            'total_bs.required' => 'El total es obligatorio.',
        ]);

        try {
            $venta = DB::transaction(function () use ($request) {
                $clienteId = null;

                // Handle client
                if ($request->cliente_tipo === 'nuevo') {
                    $request->validate([
                        'nombres' => 'required|string|max:255',
                        'apellido_paterno' => 'required|string|max:255',
                        'carnet' => 'required|string|max:50|unique:personas,carnet',
                    ], [
                        'nombres.required' => 'El nombre del cliente es obligatorio.',
                        'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
                        'carnet.required' => 'El carnet es obligatorio.',
                        'carnet.unique' => 'Ya existe una persona con este carnet.',
                    ]);

                    $persona = Persona::create([
                        'carnet' => $request->carnet,
                        'nombres' => $request->nombres,
                        'apellido_paterno' => $request->apellido_paterno,
                        'apellido_materno' => $request->apellido_materno,
                        'celular' => $request->celular,
                        'correo' => $request->correo,
                    ]);

                    $cliente = Cliente::create([
                        'persona_id' => $persona->id,
                    ]);

                    $clienteId = $cliente->id;
                } elseif ($request->cliente_tipo === 'existente') {
                    $request->validate([
                        'cliente_id' => 'required|exists:clientes,id',
                    ], [
                        'cliente_id.required' => 'Debe seleccionar un cliente existente.',
                    ]);
                    $clienteId = $request->cliente_id;
                }

                // Create Venta
                $venta = Venta::create([
                    'cliente_id' => $clienteId,
                    'fecha_venta' => Carbon::now(),
                    'total_bs' => $request->total_bs,
                    'descuento_bs' => $request->descuento_bs ?? 0,
                    'tipo_pago' => $request->tipo_pago,
                    'observacion' => $request->observacion,
                ]);

                // Create details and decrement stock
                foreach ($request->items as $item) {
                    $producto = Producto::findOrFail($item['producto_id']);

                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para '{$producto->nombre}'. Stock disponible: {$producto->stock}");
                    }

                    $subtotal = $item['cantidad'] * $item['precio_unitario'];

                    VentaDetalle::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'subtotal' => $subtotal,
                    ]);

                    $producto->decrement('stock', $item['cantidad']);
                }

                return $venta;
            });

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente.',
                'redirect' => route('admin.ventas.show', $venta->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente.persona', 'detalles.producto'])->findOrFail($id);

        return view('admin.ventas.show', compact('venta'));
    }

    public function storeDesdeSesion(Request $request, $sesionId)
    {
        $sesion = Sesione::with('suscripcion.cliente')->findOrFail($sesionId);

        $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.producto_id'     => 'required|exists:productos,id',
            'items.*.cantidad'        => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'tipo_pago'               => 'required|in:efectivo,qr,parcial',
            'descuento_bs'            => 'nullable|numeric|min:0',
            'monto'                   => 'nullable|numeric|min:0',
            'monto_efectivo'          => 'nullable|numeric|min:0',
            'monto_qr'                => 'nullable|numeric|min:0',
        ], [
            'items.required'  => 'Debe agregar al menos un producto.',
            'items.min'       => 'Debe agregar al menos un producto.',
            'tipo_pago.required' => 'Seleccione un tipo de pago.',
        ]);

        try {
            $venta = DB::transaction(function () use ($request, $sesion) {
                // Calcular montos de pago
                $descuento = $request->descuento_bs ?? 0;

                if ($request->tipo_pago === 'parcial') {
                    $montoEfectivo = $request->monto_efectivo ?? 0;
                    $montoQr       = $request->monto_qr ?? 0;
                    if ($montoEfectivo <= 0 && $montoQr <= 0) {
                        throw new \Exception('Debe ingresar al menos un monto mayor a 0 para el pago parcial.');
                    }
                    $totalBs = $montoEfectivo + $montoQr;
                } else {
                    $montoUnico = $request->monto ?? 0;
                    if ($montoUnico <= 0) {
                        throw new \Exception('El monto debe ser mayor a 0.');
                    }
                    $totalBs = $montoUnico;
                }

                // Calcular subtotal de productos
                $subtotalProductos = collect($request->items)->sum(fn($i) => $i['cantidad'] * $i['precio_unitario']);

                $trabajadorId = Trabajadore::where('persona_id', auth()->user()?->persona_id)->value('id');

                $recibo = IdGenerator::generate([
                    'table'                  => 'ventas',
                    'field'                  => 'recibo',
                    'length'                 => 12,
                    'prefix'                 => 'V-DINO-',
                    'reset_on_prefix_change' => true,
                ]);

                $venta = Venta::create([
                    'cliente_id'     => $sesion->suscripcion->cliente_id,
                    'sesione_id'     => $sesion->id,
                    'trabajadore_id' => $trabajadorId,
                    'recibo'         => $recibo,
                    'fecha_venta'    => Carbon::now(),
                    'total_bs'       => $subtotalProductos - $descuento,
                    'descuento_bs'   => $descuento,
                    'tipo_pago'      => $request->tipo_pago,
                    'observacion'    => $request->observacion,
                ]);

                // Productos
                foreach ($request->items as $item) {
                    $producto = Producto::findOrFail($item['producto_id']);

                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para '{$producto->nombre}'. Stock disponible: {$producto->stock}");
                    }

                    VentaDetalle::create([
                        'venta_id'        => $venta->id,
                        'producto_id'     => $item['producto_id'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'subtotal'        => $item['cantidad'] * $item['precio_unitario'],
                    ]);

                    $producto->decrement('stock', $item['cantidad']);
                }

                // Detalles de pago
                if ($request->tipo_pago === 'parcial') {
                    if (($request->monto_efectivo ?? 0) > 0) {
                        VentaPagoDetalle::create([
                            'venta_id'  => $venta->id,
                            'pago_bs'   => $request->monto_efectivo,
                            'tipo_pago' => 'efectivo',
                        ]);
                    }
                    if (($request->monto_qr ?? 0) > 0) {
                        VentaPagoDetalle::create([
                            'venta_id'  => $venta->id,
                            'pago_bs'   => $request->monto_qr,
                            'tipo_pago' => 'qr',
                        ]);
                    }
                } else {
                    VentaPagoDetalle::create([
                        'venta_id'  => $venta->id,
                        'pago_bs'   => $totalBs,
                        'tipo_pago' => $request->tipo_pago,
                    ]);
                }

                return $venta;
            });

            return response()->json([
                'success'  => true,
                'message'  => 'Venta registrada exitosamente.',
                'venta_id' => $venta->id,
                'recibo'   => $venta->recibo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function buscarCliente(Request $request)
    {
        $term = $request->get('q', '');

        $clientes = Cliente::with('persona')
            ->whereHas('persona', function ($q) use ($term) {
                $q->where('carnet', 'like', "%{$term}%")
                    ->orWhere('nombres', 'like', "%{$term}%")
                    ->orWhere('apellido_paterno', 'like', "%{$term}%")
                    ->orWhere('apellido_materno', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($cliente) {
                return [
                    'id' => $cliente->id,
                    'nombre_completo' => $cliente->persona->nombres . ' ' . $cliente->persona->apellido_paterno . ($cliente->persona->apellido_materno ? ' ' . $cliente->persona->apellido_materno : ''),
                    'carnet' => $cliente->persona->carnet,
                    'celular' => $cliente->persona->celular,
                ];
            });

        return response()->json($clientes);
    }

    public function buscarProducto(Request $request)
    {
        $term = $request->get('q', '');

        $productos = Producto::where('estado', 'activo')
            ->where('stock', '>', 0)
            ->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('marca', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'marca' => $producto->marca,
                    'precio_venta' => $producto->precio_venta,
                    'stock' => $producto->stock,
                ];
            });

        return response()->json($productos);
    }

    public function reciboPdf($ventaId)
    {
        try {
            $venta = Venta::with([
                'cliente.persona',
                'sesion.servicio',
                'detalles.producto',
                'pagoDetalles',
                'trabajador.persona',
            ])->findOrFail($ventaId);

            $pdf = Pdf::loadView('admin.ventas.pdf.recibo-venta', [
                'venta'        => $venta,
                'cliente'      => $venta->cliente->persona,
                'fechaEmision' => now(),
            ]);

            $pdf->setPaper('letter', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', false);
            $pdf->setOption('defaultFont', 'DejaVu Sans');

            $nombreArchivo = 'RECIBO-VENTA-' . $venta->recibo . '-' . date('Y-m-d') . '.pdf';

            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al generar recibo de venta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el recibo: ' . $e->getMessage());
        }
    }
}
