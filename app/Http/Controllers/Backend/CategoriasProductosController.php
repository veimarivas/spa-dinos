<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;

class CategoriasProductosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = CategoriaProducto::withCount('productos');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $categorias = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            $total = CategoriaProducto::count();

            return response()->json([
                'html'       => view('admin.categorias-productos.partials.table-body', compact('categorias'))->render(),
                'pagination' => (string) $categorias->links('pagination::bootstrap-5'),
                'total'      => $total,
                'filtered'   => $categorias->total(),
                'from'       => $categorias->firstItem(),
                'to'         => $categorias->lastItem(),
            ]);
        }

        $total = CategoriaProducto::count();

        return view('admin.categorias-productos.listar', compact('categorias', 'total'));
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'required|string|max:255|unique:categorias_productos,nombre',
                'descripcion' => 'nullable|string|max:500',
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio',
                'nombre.unique'   => 'Ya existe una categoría con ese nombre',
            ]);

            CategoriaProducto::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Categoría registrada exitosamente',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar la categoría',
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $exists = CategoriaProducto::where('nombre', $request->input('nombre'))->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $exists = CategoriaProducto::where('nombre', $request->input('nombre'))
            ->where('id', '!=', $request->input('id'))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'          => 'required|exists:categorias_productos,id',
                'nombre'      => 'required|string|max:255|unique:categorias_productos,nombre,' . $request->id,
                'descripcion' => 'nullable|string|max:500',
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio',
                'nombre.unique'   => 'Ya existe una categoría con ese nombre',
            ]);

            $categoria = CategoriaProducto::findOrFail($request->id);
            $categoria->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Categoría actualizada exitosamente',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar la categoría',
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $categoria = CategoriaProducto::findOrFail($request->get('id'));

            if ($categoria->productos()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar la categoría porque tiene productos asociados',
                ], 400);
            }

            $categoria->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Categoría eliminada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar la categoría',
            ], 500);
        }
    }

    /**
     * Show category details and its products.
     */
    public function ver($id)
    {
        $categoria = CategoriaProducto::withCount('productos')->findOrFail($id);
        $productos = $categoria->productos()->paginate(10);
        $categorias = CategoriaProducto::orderBy('nombre')->get(); // for product form category select

        return view('admin.categorias-productos.ver', compact('categoria', 'productos', 'categorias'));
    }

    /**
     * Return products for a category (AJAX).
     */
    public function productos(Request $request, $id)
    {
        $categoria = CategoriaProducto::findOrFail($id);
        $productos = $categoria->productos()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.categorias-productos.partials.productos-table', compact('productos'))->render(),
                'pagination' => (string) $productos->links('pagination::bootstrap-5'),
                'from'       => $productos->firstItem(),
                'to'         => $productos->lastItem(),
                'total'      => $productos->total()
            ]);
        }

        return abort(404);
    }
}
