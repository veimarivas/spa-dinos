<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Paquete;
use Illuminate\Http\Request;

class PaquetesController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Paquete::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $paquetes = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.paquetes.partials.table-body', compact('paquetes'))->render(),
                'pagination' => (string) $paquetes->links('pagination::bootstrap-5'),
                'total' => $paquetes->total(),
                'from' => $paquetes->firstItem(),
                'to' => $paquetes->lastItem()
            ]);
        }

        return view('admin.paquetes.listar', compact('paquetes'));
    }

    public function ver($id)
    {
        try {
            $paquete = Paquete::withCount('planes')
                ->with(['planes' => function ($query) {
                    $query->latest()->take(10); // últimos 10 planes asociados
                }])
                ->findOrFail($id);

            return view('admin.paquetes.ver', compact('paquete'));
        } catch (\Exception $e) {
            return redirect()->route('admin.paquetes.listar')
                ->with('error', 'Paquete no encontrado');
        }
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'required|unique:paquetes,nombre',
                'descripcion' => 'nullable|string|max:500',
            ], [
                'nombre.required'       => 'El nombre del paquete es obligatorio',
                'nombre.unique'         => 'Este nombre de paquete ya está registrado',
                'descripcion.max'       => 'La descripción no puede exceder los 500 caracteres'
            ]);

            Paquete::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Paquete registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el paquete'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Paquete::where('nombre', $nombre)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id     = $request->input('id');

        $exists = Paquete::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'          => 'required|exists:paquetes,id',
                'nombre'      => 'required|unique:paquetes,nombre,' . $request->id,
                'descripcion' => 'nullable|string|max:500',
            ], [
                'nombre.required'       => 'El nombre del paquete es obligatorio',
                'nombre.unique'         => 'Este nombre de paquete ya está registrado',
                'descripcion.max'       => 'La descripción no puede exceder los 500 caracteres'
            ]);

            $paquete = Paquete::findOrFail($request->id);
            $paquete->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Paquete actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el paquete'
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $paquete = Paquete::findOrFail($id);

            // Verificar si hay planes relacionados
            if ($paquete->planes()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el paquete porque tiene planes asociados'
                ], 400);
            }

            $paquete->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Paquete eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el paquete'
            ], 500);
        }
    }
}
