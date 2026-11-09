<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Servicio::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $servicios = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.servicios.partials.table-body', compact('servicios'))->render(),
                'pagination' => (string) $servicios->links('pagination::bootstrap-5'),
                'total' => $servicios->total(),
                'from' => $servicios->firstItem(),
                'to' => $servicios->lastItem()
            ]);
        }

        return view('admin.servicios.listar', compact('servicios'));
    }

    public function ver($id)
    {
        try {
            $servicio = Servicio::withCount('sesiones')
                ->with(['sesiones' => function ($query) {
                    $query->latest()->take(10); // últimas 10 sesiones
                }])
                ->findOrFail($id);

            return view('admin.servicios.ver', compact('servicio'));
        } catch (\Exception $e) {
            return redirect()->route('admin.servicios.listar')
                ->with('error', 'Servicio no encontrado');
        }
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'           => 'required|unique:servicios,nombre',
                'descripcion'      => 'nullable|string|max:500',
                'pago_con_sesion'  => 'required|numeric|min:0',
                'pago_sin_sesion'  => 'required|numeric|min:0',
                'pago_especial'    => 'nullable|numeric|min:0',
            ], [
                'nombre.required'          => 'El nombre del servicio es obligatorio',
                'nombre.unique'            => 'Este nombre de servicio ya está registrado',
                'descripcion.max'          => 'La descripción no puede exceder los 500 caracteres',
                'pago_con_sesion.required' => 'El pago con sesión es obligatorio',
                'pago_con_sesion.numeric'  => 'El pago con sesión debe ser un número',
                'pago_con_sesion.min'      => 'El pago con sesión no puede ser negativo',
                'pago_sin_sesion.required' => 'El pago sin sesión es obligatorio',
                'pago_sin_sesion.numeric'  => 'El pago sin sesión debe ser un número',
                'pago_sin_sesion.min'      => 'El pago sin sesión no puede ser negativo',
                'pago_especial.numeric'    => 'El pago especial debe ser un número',
                'pago_especial.min'        => 'El pago especial no puede ser negativo',
            ]);

            Servicio::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Servicio registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el servicio'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Servicio::where('nombre', $nombre)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id     = $request->input('id');

        $exists = Servicio::where('nombre', $nombre)
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
                'id'               => 'required|exists:servicios,id',
                'nombre'           => 'required|unique:servicios,nombre,' . $request->id,
                'descripcion'      => 'nullable|string|max:500',
                'pago_con_sesion'  => 'required|numeric|min:0',
                'pago_sin_sesion'  => 'required|numeric|min:0',
                'pago_especial'    => 'nullable|numeric|min:0',
            ], [
                'nombre.required'          => 'El nombre del servicio es obligatorio',
                'nombre.unique'            => 'Este nombre de servicio ya está registrado',
                'descripcion.max'          => 'La descripción no puede exceder los 500 caracteres',
                'pago_con_sesion.required' => 'El pago con sesión es obligatorio',
                'pago_con_sesion.numeric'  => 'El pago con sesión debe ser un número',
                'pago_con_sesion.min'      => 'El pago con sesión no puede ser negativo',
                'pago_sin_sesion.required' => 'El pago sin sesión es obligatorio',
                'pago_sin_sesion.numeric'  => 'El pago sin sesión debe ser un número',
                'pago_sin_sesion.min'      => 'El pago sin sesión no puede ser negativo',
                'pago_especial.numeric'    => 'El pago especial debe ser un número',
                'pago_especial.min'        => 'El pago especial no puede ser negativo',
            ]);

            $servicio = Servicio::findOrFail($request->id);
            $servicio->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Servicio actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el servicio'
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $servicio = Servicio::findOrFail($id);

            // Verificar si hay sesiones relacionadas
            if ($servicio->sesiones()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el servicio porque tiene sesiones relacionadas'
                ], 400);
            }

            $servicio->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Servicio eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el servicio'
            ], 500);
        }
    }
}
