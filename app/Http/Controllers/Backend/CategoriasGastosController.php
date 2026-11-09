<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoriaGasto;
use Illuminate\Http\Request;

class CategoriasGastosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = CategoriaGasto::withCount('gastos')
            ->with(['children' => function ($q) {
                $q->withCount('gastos');
            }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $categorias = $query->whereNull('parent_id')->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            $total = CategoriaGasto::count();

            return response()->json([
                'html'       => view('admin.categorias-gastos.partials.table-body', compact('categorias'))->render(),
                'pagination' => (string) $categorias->links('pagination::bootstrap-5'),
                'total'      => $total,
                'filtered'   => $categorias->total(),
                'from'       => $categorias->firstItem(),
                'to'         => $categorias->lastItem(),
            ]);
        }

        $total = CategoriaGasto::count();

        return view('admin.categorias-gastos.listar', compact('categorias', 'total'));
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'required|string|max:255|unique:categorias_gastos,nombre',
                'descripcion' => 'nullable|string|max:500',
                'parent_id'   => 'nullable|exists:categorias_gastos,id',
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio',
                'nombre.unique'   => 'Ya existe una categoría con ese nombre',
                'parent_id.exists'=> 'La categoría padre seleccionada no existe',
            ]);

            CategoriaGasto::create($validated);

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
        $exists = CategoriaGasto::where('nombre', $request->input('nombre'))->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $exists = CategoriaGasto::where('nombre', $request->input('nombre'))
            ->where('id', '!=', $request->input('id'))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'          => 'required|exists:categorias_gastos,id',
                'nombre'      => 'required|string|max:255|unique:categorias_gastos,nombre,' . $request->id,
                'descripcion' => 'nullable|string|max:500',
                'parent_id'   => 'nullable|exists:categorias_gastos,id',
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio',
                'nombre.unique'   => 'Ya existe una categoría con ese nombre',
                'parent_id.exists'=> 'La categoría padre seleccionada no existe',
            ]);

            $categoria = CategoriaGasto::findOrFail($request->id);

            if ((int) $validated['parent_id'] === (int) $categoria->id) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'Una categoría no puede ser padre de sí misma',
                ], 400);
            }

            if ($categoria->children()->where('id', $validated['parent_id'])->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede asignar como padre una subcategoría de esta categoría',
                ], 400);
            }

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
            $categoria = CategoriaGasto::findOrFail($request->get('id'));

            if ($categoria->gastos()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar la categoría porque tiene gastos asociados',
                ], 400);
            }

            if ($categoria->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar la categoría porque tiene subcategorías asociadas',
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

    public function obtenerCategorias(Request $request)
    {
        $categorias = CategoriaGasto::whereNull('parent_id')
            ->with('children')
            ->orderBy('nombre')
            ->get()
            ->map(function ($cat) {
                $label = $cat->nombre;
                $children = $cat->children->map(function ($child) {
                    return [
                        'id'   => $child->id,
                        'nombre' => '-- ' . $child->nombre,
                    ];
                })->toArray();

                $result = [['id' => $cat->id, 'nombre' => $label]];
                return array_merge($result, $children);
            })
            ->flatten(1);

        if ($request->has('exclude')) {
            $categorias = $categorias->reject(function ($cat) use ($request) {
                return $cat['id'] == $request->exclude;
            })->values();
        }

        return response()->json($categorias);
    }
}
