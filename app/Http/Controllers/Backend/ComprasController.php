<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Models\CategoriaGasto;
use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprasController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $query = Compra::with(['proveedor', 'detalles.producto']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('proveedor', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_compra', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_compra', '<=', $request->fecha_hasta);
        }

        $compras = $query->orderBy('fecha_compra', 'desc')->paginate(10);
        $totalCompras = Compra::count();
        $totalMonto = Compra::sum('total_bs');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.compras.partials.table-body', compact('compras'))->render(),
                'pagination' => (string) $compras->links('pagination::bootstrap-5'),
                'total' => $compras->total(),
                'totalCompras' => $totalCompras,
                'totalMonto' => number_format($totalMonto, 2),
            ]);
        }

        return view('admin.compras.listar', compact('compras', 'totalCompras', 'totalMonto'));
    }

    public function create()
    {
        $proveedores = Proveedore::where('estado', 'activo')->orderBy('nombre')->get();
        $productos = Producto::where('estado', 'activo')->orderBy('nombre')->get();
        return view('admin.compras.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedore_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0.01',
        ], [
            'proveedore_id.required' => 'Seleccione un proveedor',
            'fecha_compra.required' => 'La fecha es obligatoria',
            'items.required' => 'Debe agregar al menos un producto',
            'items.min' => 'Debe agregar al menos un producto',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['cantidad'] * $item['precio_unitario'];
            }

            $compra = Compra::create([
                'proveedore_id' => $request->proveedore_id,
                'fecha_compra' => $request->fecha_compra,
                'total_bs' => $total,
                'observacion' => $request->observacion,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];

                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                $producto = Producto::find($item['producto_id']);
                $producto->increment('stock', $item['cantidad']);
                $producto->update(['precio_compra' => $item['precio_unitario']]);
            }

            $catCompra = CategoriaGasto::where('nombre', 'Compra de Productos')->first();
            if ($catCompra) {
                Gasto::create([
                    'categoria_gasto_id' => $catCompra->id,
                    'descripcion' => 'Compra #' . $compra->id . ' - ' . $compra->proveedor->nombre,
                    'monto_bs' => $total,
                    'fecha_gasto' => $request->fecha_compra,
                    'compra_id' => $compra->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Compra registrada exitosamente',
                'compra_id' => $compra->id,
                'redirect' => route('admin.compras.show', $compra->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar la compra: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.producto', 'gasto'])->findOrFail($id);
        return view('admin.compras.show', compact('compra'));
    }

    public function buscarProducto(Request $request)
    {
        $q = $request->get('q', '');
        $productos = Producto::where('estado', 'activo')
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('marca', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'marca', 'precio_compra', 'stock']);

        return response()->json($productos);
    }

    public function buscarProveedor(Request $request)
    {
        $q = $request->get('q', '');
        $proveedores = Proveedore::where('estado', 'activo')
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('nit', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'nit']);

        return response()->json($proveedores);
    }
}
