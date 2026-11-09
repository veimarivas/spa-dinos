<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedore;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Proveedore::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('nit', 'like', "%{$search}%")
                    ->orWhere('contacto_nombre', 'like', "%{$search}%");
            });
        }

        $proveedores = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            $total = Proveedore::count();
            $activos = Proveedore::where('estado', 'activo')->count();
            $inactivos = Proveedore::where('estado', 'inactivo')->count();

            return response()->json([
                'html' => view('admin.proveedores.partials.table-body', compact('proveedores'))->render(),
                'pagination' => (string) $proveedores->links('pagination::bootstrap-5'),
                'total' => $total,
                'activos' => $activos,
                'inactivos' => $inactivos,
                'filtered' => $proveedores->total(),
                'from' => $proveedores->firstItem(),
                'to' => $proveedores->lastItem()
            ]);
        }

        return view('admin.proveedores.listar', compact('proveedores'));
    }

    public function ver(Request $request, $id)
    {
        $proveedor = Proveedore::with('compras.detalles.producto')->findOrFail($id);
        $compras = $proveedor->compras()->with('detalles.producto')->orderBy('fecha_compra', 'desc')->paginate(10);

        if ($request->ajax() && $request->has('compras_only')) {
            return response()->json([
                'html'       => view('admin.proveedores.partials.compras-table', compact('compras'))->render(),
                'pagination' => (string) $compras->links('pagination::bootstrap-5'),
                'from'       => $compras->firstItem(),
                'to'         => $compras->lastItem(),
                'total'      => $compras->total()
            ]);
        }

        $productos = Producto::where('estado', 'activo')->orderBy('nombre')->get();
        return view('admin.proveedores.ver', compact('proveedor', 'compras', 'productos'));
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'          => 'required|string|max:255',
                'nit'             => 'nullable|string|max:50|unique:proveedores,nit',
                'contacto_nombre' => 'nullable|string|max:255',
                'celular'         => 'nullable|string|max:50',
                'correo'          => 'nullable|email|max:255',
                'direccion'       => 'nullable|string|max:500',
                'estado'          => 'required|in:activo,inactivo',
            ], [
                'nombre.required'  => 'El nombre del proveedor es obligatorio',
                'nit.unique'       => 'Este NIT ya esta registrado',
                'correo.email'     => 'El correo debe ser un email valido',
            ]);

            Proveedore::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Proveedor registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validacion'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el proveedor'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Proveedore::where('nombre', $nombre)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id     = $request->input('id');

        $exists = Proveedore::where('nombre', $nombre)
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
                'id'              => 'required|exists:proveedores,id',
                'nombre'          => 'required|string|max:255',
                'nit'             => 'nullable|string|max:50|unique:proveedores,nit,' . $request->id,
                'contacto_nombre' => 'nullable|string|max:255',
                'celular'         => 'nullable|string|max:50',
                'correo'          => 'nullable|email|max:255',
                'direccion'       => 'nullable|string|max:500',
                'estado'          => 'required|in:activo,inactivo',
            ], [
                'nombre.required'  => 'El nombre del proveedor es obligatorio',
                'nit.unique'       => 'Este NIT ya esta registrado',
                'correo.email'     => 'El correo debe ser un email valido',
            ]);

            $proveedor = Proveedore::findOrFail($request->id);
            $proveedor->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Proveedor actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validacion'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el proveedor'
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $proveedor = Proveedore::findOrFail($id);

            // Verificar si hay compras relacionadas
            if ($proveedor->compras()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el proveedor porque tiene compras relacionadas'
                ], 400);
            }

            $proveedor->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Proveedor eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el proveedor'
            ], 500);
        }
    }
}
