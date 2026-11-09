<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plane;
use App\Models\Paquete;
use App\Models\Servicio;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    /**
     * Listar planes (con filtro opcional por paquete).
     */
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $paqueteId = $request->get('paquete_id');

        $query = Plane::with('paquete', 'servicios');

        if ($paqueteId) {
            $query->where('paquete_id', $paqueteId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_plan', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $planes = $query->orderBy('nombre_plan')->paginate(10);

        if ($request->ajax()) {
            $html = view('admin.planes.partials.table-body', compact('planes'))->render();
            return response()->json([
                'html'       => $html,
                'pagination' => (string) $planes->links('pagination::bootstrap-5'),
                'total'      => $planes->total(),
                'from'       => $planes->firstItem(),
                'to'         => $planes->lastItem(),
            ]);
        }

        return view('admin.planes.listar', compact('planes'));
    }

    /**
     * Ver detalle de un plan.
     */
    public function ver($id)
    {
        try {
            $plan = Plane::with('paquete')->withCount('suscripciones')->findOrFail($id);
            return view('admin.planes.ver', compact('plan'));
        } catch (\Exception $e) {
            return redirect()->route('admin.planes.listar')
                ->with('error', 'Plan no encontrado');
        }
    }

    /**
     * Registrar un nuevo plan.
     */
    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_plan'       => 'required|string|max:100',
                'cantidad_sesiones' => 'required|integer|min:1',
                'precio'            => 'required|numeric|min:0',
                'precio_especial'   => 'nullable|numeric|min:0',
                'descripcion'       => 'nullable|string|max:500',
                'paquete_id'        => 'required|exists:paquetes,id',
            ], [
                'nombre_plan.required'       => 'El nombre del plan es obligatorio',
                'nombre_plan.unique'          => 'Este nombre de plan ya está registrado en este paquete',
                'cantidad_sesiones.required'  => 'La cantidad de sesiones es obligatoria',
                'cantidad_sesiones.integer'   => 'Debe ser un número entero',
                'cantidad_sesiones.min'       => 'Debe tener al menos 1 sesión',
                'precio.required'              => 'El precio es obligatorio',
                'precio.numeric'                => 'El precio debe ser un número',
                'precio.min'                    => 'El precio no puede ser negativo',
                'precio_especial.numeric'       => 'El precio especial debe ser un número',
                'precio_especial.min'           => 'El precio especial no puede ser negativo',
                'descripcion.max'                => 'La descripción no puede exceder los 500 caracteres',
                'paquete_id.required'            => 'El paquete es obligatorio',
                'paquete_id.exists'               => 'El paquete seleccionado no existe',
            ]);

            // Validación adicional: nombre único por paquete
            $exists = Plane::where('nombre_plan', $validated['nombre_plan'])
                ->where('paquete_id', $validated['paquete_id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'errors'  => ['nombre_plan' => ['Ya existe un plan con ese nombre en este paquete']],
                    'message' => 'Error de validación'
                ], 422);
            }

            Plane::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Plan registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar disponibilidad de nombre (para registro).
     */
    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $paqueteId = $request->input('paquete_id');

        if (!$paqueteId) {
            return response()->json(['exists' => false]); // No se puede verificar sin paquete
        }

        $exists = Plane::where('nombre_plan', $nombre)
            ->where('paquete_id', $paqueteId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Verificar disponibilidad de nombre en edición.
     */
    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');
        $paqueteId = $request->input('paquete_id');

        if (!$paqueteId) {
            return response()->json(['exists' => false]);
        }

        $exists = Plane::where('nombre_plan', $nombre)
            ->where('paquete_id', $paqueteId)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Modificar un plan existente.
     */
    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'                => 'required|exists:planes,id',
                'nombre_plan'       => 'required|string|max:100',
                'cantidad_sesiones' => 'required|integer|min:1',
                'precio'            => 'required|numeric|min:0',
                'precio_especial'   => 'nullable|numeric|min:0',
                'descripcion'       => 'nullable|string|max:500',
                'paquete_id'        => 'required|exists:paquetes,id',
            ], [
                'nombre_plan.required'       => 'El nombre del plan es obligatorio',
                'cantidad_sesiones.required'  => 'La cantidad de sesiones es obligatoria',
                'precio.required'              => 'El precio es obligatorio',
            ]);

            $plan = Plane::findOrFail($validated['id']);

            // Validar nombre único por paquete (excepto el mismo)
            $exists = Plane::where('nombre_plan', $validated['nombre_plan'])
                ->where('paquete_id', $validated['paquete_id'])
                ->where('id', '!=', $plan->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'errors'  => ['nombre_plan' => ['Ya existe otro plan con ese nombre en este paquete']],
                    'message' => 'Error de validación'
                ], 422);
            }

            $plan->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Plan actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el plan'
            ], 500);
        }
    }

    /**
     * Eliminar un plan.
     */
    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $plan = Plane::findOrFail($id);

            // Verificar si tiene suscripciones asociadas
            if ($plan->suscripciones()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el plan porque tiene suscripciones asociadas'
                ], 400);
            }

            $plan->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Plan eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el plan'
            ], 500);
        }
    }

    public function servicios(Plane $plan)
    {
        $plan->load('servicios');
        $todosServicios = Servicio::orderBy('nombre')->get();
        $html = view('admin.planes.partials.servicios-modal-content', compact('plan', 'todosServicios'))->render();
        return response()->json([
            'html' => $html,
            'plan_name' => $plan->nombre_plan
        ]);
    }

    public function guardarServicios(Request $request, Plane $plan)
    {
        try {
            $request->validate([
                'servicios' => 'array',
                'servicios.*.cantidad' => 'required|integer|min:0'
            ]);

            $syncData = [];
            foreach ($request->servicios as $servicioId => $data) {
                $cantidad = intval($data['cantidad']);
                if ($cantidad > 0) {
                    $syncData[$servicioId] = ['cantidad_sesiones' => $cantidad];
                }
            }

            $plan->servicios()->sync($syncData);

            return response()->json([
                'success' => true,
                'msg' => 'Servicios actualizados correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar servicios: ' . $e->getMessage()
            ], 500);
        }
    }
}
