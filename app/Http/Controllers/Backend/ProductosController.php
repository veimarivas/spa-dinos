<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Producto::with('categoria');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('marca', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('nombre')->paginate(10);

        // Stats
        $total = Producto::count();
        $activos = Producto::where('estado', 'activo')->count();
        $stockBajo = Producto::where('stock_minimo', '>', 0)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();
        $inactivos = Producto::where('estado', 'inactivo')->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.productos.partials.table-body', compact('productos'))->render(),
                'pagination' => (string) $productos->links('pagination::bootstrap-5'),
                'total' => $total,
                'activos' => $activos,
                'stockBajo' => $stockBajo,
                'inactivos' => $inactivos,
                'from' => $productos->firstItem(),
                'to' => $productos->lastItem()
            ]);
        }

        $categorias = CategoriaProducto::orderBy('nombre')->get();

        return view('admin.productos.listar', compact('productos', 'categorias', 'total', 'activos', 'stockBajo', 'inactivos'));
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'                => 'required|string|max:255',
                'marca'                 => 'nullable|string|max:255',
                'categoria_producto_id' => 'nullable|exists:categorias_productos,id',
                'descripcion'           => 'nullable|string|max:500',
                'precio_compra'         => 'nullable|numeric|min:0',
                'precio_venta'          => 'required|numeric|min:0',
                'stock_minimo'          => 'nullable|integer|min:0',
                'estado'                => 'required|in:activo,inactivo',
            ], [
                'nombre.required'                => 'El nombre del producto es obligatorio',
                'categoria_producto_id.exists'   => 'La categoría seleccionada no es válida',
                'precio_compra.numeric'          => 'El precio de compra debe ser un número',
                'precio_compra.min'              => 'El precio de compra no puede ser negativo',
                'precio_venta.required'          => 'El precio de venta es obligatorio',
                'precio_venta.numeric'           => 'El precio de venta debe ser un número',
                'precio_venta.min'               => 'El precio de venta no puede ser negativo',
                'stock_minimo.integer'           => 'El stock mínimo debe ser un número entero',
                'stock_minimo.min'               => 'El stock mínimo no puede ser negativo',
                'estado.required'                => 'El estado es obligatorio',
                'estado.in'                      => 'El estado debe ser activo o inactivo',
            ]);

            $validated['stock'] = 0;
            $validated['stock_minimo'] = $validated['stock_minimo'] ?? 0;

            Producto::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Producto registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el producto'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $marca = $request->input('marca');

        $query = Producto::where('nombre', $nombre);
        if ($marca) {
            $query->where('marca', $marca);
        }

        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $marca = $request->input('marca');
        $id = $request->input('id');

        $query = Producto::where('nombre', $nombre);
        if ($marca) {
            $query->where('marca', $marca);
        }
        if ($id) {
            $query->where('id', '!=', $id);
        }

        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }

    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'                    => 'required|exists:productos,id',
                'nombre'                => 'required|string|max:255',
                'marca'                 => 'nullable|string|max:255',
                'categoria_producto_id' => 'nullable|exists:categorias_productos,id',
                'descripcion'           => 'nullable|string|max:500',
                'precio_compra'         => 'nullable|numeric|min:0',
                'precio_venta'          => 'required|numeric|min:0',
                'stock_minimo'          => 'nullable|integer|min:0',
                'estado'                => 'required|in:activo,inactivo',
            ], [
                'nombre.required'                => 'El nombre del producto es obligatorio',
                'categoria_producto_id.exists'   => 'La categoría seleccionada no es válida',
                'precio_compra.numeric'          => 'El precio de compra debe ser un número',
                'precio_compra.min'              => 'El precio de compra no puede ser negativo',
                'precio_venta.required'          => 'El precio de venta es obligatorio',
                'precio_venta.numeric'           => 'El precio de venta debe ser un número',
                'precio_venta.min'               => 'El precio de venta no puede ser negativo',
                'stock_minimo.integer'           => 'El stock mínimo debe ser un número entero',
                'stock_minimo.min'               => 'El stock mínimo no puede ser negativo',
                'estado.required'                => 'El estado es obligatorio',
                'estado.in'                      => 'El estado debe ser activo o inactivo',
            ]);

            $producto = Producto::findOrFail($request->id);
            $validated['stock_minimo'] = $validated['stock_minimo'] ?? 0;
            $producto->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Producto actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el producto'
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $producto = Producto::findOrFail($id);

            // Verificar si tiene detalles de compra relacionados
            if ($producto->compraDetalles()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el producto porque tiene compras relacionadas'
                ], 400);
            }

            // Verificar si tiene detalles de venta relacionados
            if ($producto->ventaDetalles()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el producto porque tiene ventas relacionadas'
                ], 400);
            }

            $producto->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el producto'
            ], 500);
        }
    }
}
