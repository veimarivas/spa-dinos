<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Hijo;
use App\Models\Parentesco;
use App\Models\Suscripcione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    /**
     * Listar clientes con búsqueda y paginación.
     */
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Cliente::with('persona')
            ->withCount(['parentescos as hijos_count', 'suscripciones as suscripciones_count']);

        if ($search) {
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('carnet', 'like', "%{$search}%")
                    ->orWhere('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%")
                    ->orWhere('celular', 'like', "%{$search}%");
            });
        }

        $clientes = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.clientes.partials.table-body', compact('clientes'))->render(),
                'pagination' => (string) $clientes->links('pagination::bootstrap-5'),
                'total'      => $clientes->total(),
                'from'       => $clientes->firstItem(),
                'to'         => $clientes->lastItem(),
            ]);
        }

        return view('admin.clientes.listar', compact('clientes'));
    }

    /**
     * Mostrar detalles completos del cliente (seguimiento).
     */
    public function seguimiento($id)
    {
        $cliente = Cliente::with([
            'persona',
            'parentescos.hijo.persona',
            'suscripciones' => function ($q) {
                $q->with(['plan.paquete', 'pagos', 'sesiones' => function ($sq) {
                    $sq->with(['planServicio.servicio', 'servicio', 'hijo.persona', 'trabajador.persona'])
                        ->orderBy('fecha', 'desc');
                }])->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        $suscripcionesActivas = $cliente->suscripciones->filter(function($sus) {
            $totalPlan = $sus->plan->cantidad_sesiones ?? 0;
            $sesionesCompletadas = $sus->sesiones->where('estado', 'realizada')->count();
            $precioPlan = $sus->monto_total ?? ($sus->tarifa === 'especial' && $sus->plan->precio_especial ? $sus->plan->precio_especial : ($sus->plan->precio ?? 0));
            $totalPagado = $sus->pagos->sum('pago_bs');
            $totalDescuento = $sus->pagos->sum('descuento_bs');
            $saldoPendiente = max(0, $precioPlan - $totalPagado - $totalDescuento);
            // Activas si: hay sesiones pendientes O hay deuda
            return $sesionesCompletadas < $totalPlan || $saldoPendiente > 0;
        })->values();

        $suscripcionesConcluidas = $cliente->suscripciones->filter(function($sus) {
            $totalPlan = $sus->plan->cantidad_sesiones ?? 0;
            $sesionesCompletadas = $sus->sesiones->where('estado', 'realizada')->count();
            $precioPlan = $sus->monto_total ?? ($sus->tarifa === 'especial' && $sus->plan->precio_especial ? $sus->plan->precio_especial : ($sus->plan->precio ?? 0));
            $totalPagado = $sus->pagos->sum('pago_bs');
            $totalDescuento = $sus->pagos->sum('descuento_bs');
            $saldoPendiente = max(0, $precioPlan - $totalPagado - $totalDescuento);
            // Concluidas solo si: sesiones completadas Y sin deuda
            return $sesionesCompletadas >= $totalPlan && $totalPlan > 0 && $saldoPendiente <= 0;
        })->values();

        return view('admin.clientes.seguimiento', compact('cliente', 'suscripcionesActivas', 'suscripcionesConcluidas'));
    }

    /**
     * Registrar un nuevo cliente (persona + cliente).
     */
    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'carnet'                => 'required|string|max:20',
                'nit'                   => 'nullable|string|max:20',
                'nombres'               => 'required|string|max:100',
                'apellido_paterno'      => 'required|string|max:50',
                'apellido_materno'      => 'nullable|string|max:50',
                'correo'                => 'nullable|email|max:100',
                'direccion'             => 'nullable|string|max:255',
                'fecha_nacimiento'      => 'nullable|date',
                'celular'               => 'nullable|string|max:20',
                'telefono'              => 'nullable|string|max:20',
                'color'                 => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            ]);

            DB::beginTransaction();

            // Buscar persona por carnet
            $persona = Persona::where('carnet', $validated['carnet'])->first();

            if ($persona) {
                // Verificar si ya es cliente
                if ($persona->cliente) {
                    return response()->json([
                        'success' => false,
                        'msg'     => 'Esta persona ya está registrada como cliente.'
                    ], 422);
                }

                // Actualizar datos de la persona (pueden haber sido modificados)
                $persona->update($validated);
            } else {
                // Crear nueva persona
                $persona = Persona::create($validated);
            }

            // Crear cliente asociado
            $cliente = Cliente::create([
                'persona_id' => $persona->id,
                'color'      => $validated['color'] ?? '#405189',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Cliente registrado exitosamente',
                'cliente' => $cliente->load('persona')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Error de validación'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al registrar el cliente: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verificar si un carnet ya existe (para registro).
     */
    public function verificarCarnet(Request $request)
    {
        $carnet = $request->input('carnet');
        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json([
                'exists'    => false,
                'is_client' => false,
                'message'   => 'Carnet disponible',
                'persona'   => null
            ]);
        }

        $isClient = $persona->cliente()->exists();

        if ($isClient) {
            return response()->json([
                'exists'    => true,
                'is_client' => true,
                'message'   => 'Ya existe un cliente con este carnet',
                'persona'   => null
            ]);
        } else {
            return response()->json([
                'exists'    => true,
                'is_client' => false,
                'message'   => 'Persona encontrada. Se han cargado sus datos.',
                'persona'   => $persona->only([
                    'carnet',
                    'nit',
                    'nombres',
                    'apellido_paterno',
                    'apellido_materno',
                    'correo',
                    'direccion',
                    'fecha_nacimiento',
                    'celular',
                    'telefono'
                ])
            ]);
        }
    }

    /**
     * Verificar carnet para edición (excluye el ID actual).
     */
    public function verificarCarnetEdicion(Request $request)
    {
        $carnet = $request->input('carnet');
        $personaId = $request->input('persona_id'); // ID de la persona (no del cliente)

        $exists = Persona::where('carnet', $carnet)
            ->when($personaId, function ($q) use ($personaId) {
                return $q->where('id', '!=', $personaId);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function verificarCorreo(Request $request)
    {
        $correo = $request->input('correo');

        if (empty($correo)) {
            return response()->json(['exists' => false]);
        }

        $exists = Persona::where('correo', $correo)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Modificar datos del cliente (persona).
     */
    public function modificar(Request $request)
    {
        try {
            $cliente = Cliente::with('persona')->findOrFail($request->id);

            $validated = $request->validate([
                'carnet'           => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('personas', 'carnet')->ignore($cliente->persona->id),
                ],
                'nit'              => 'nullable|string|max:20',
                'nombres'          => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:50',
                'apellido_materno' => 'nullable|string|max:50',
                'correo'           => 'nullable|email|max:100',
                'direccion'        => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date',
                'celular'          => 'nullable|string|max:20',
                'telefono'         => 'nullable|string|max:20',
                'color'            => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            ]);

            DB::beginTransaction();

            $cliente->persona->update($validated);
            $cliente->update([
                'color' => $validated['color'] ?? $cliente->color, // mantiene el actual si no se envía
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Cliente actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Error de validación'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al actualizar el cliente'], 500);
        }
    }

    /**
     * Eliminar cliente (y su persona si no está vinculada a otros roles).
     */
    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $cliente = Cliente::with('persona')->findOrFail($id);

            // Verificar relaciones
            if ($cliente->suscripciones()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el cliente porque tiene suscripciones activas'
                ], 400);
            }

            if ($cliente->parentescos()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el cliente porque tiene hijos asociados'
                ], 400);
            }

            $persona = $cliente->persona;

            DB::beginTransaction();
            $cliente->delete();

            // Verificar si la persona es usada por otros modelos (trabajador, user, hijo)
            $usages = 0;
            if ($persona->trabajador) $usages++;
            if ($persona->user) $usages++;
            if ($persona->hijo) $usages++;

            if ($usages === 0) {
                $persona->delete();
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al eliminar el cliente'], 500);
        }
    }

    /**
     * Obtener hijos de un cliente (para usar en selectores AJAX).
     */
    public function hijosPorCliente(Request $request)
    {
        $clienteId = $request->get('cliente_id');
        $cliente = Cliente::with('parentescos.hijo.persona')->find($clienteId);

        if (!$cliente) {
            return response()->json([]);
        }

        $hijos = $cliente->parentescos->map(function ($parentesco) {
            $hijo = $parentesco->hijo;
            return [
                'id'   => $hijo->id,
                'text' => $hijo->persona->nombres . ' ' . $hijo->persona->apellido_paterno .
                    ($hijo->persona->carnet ? ' - ' . $hijo->persona->carnet : ''),
                'persona' => $hijo->persona
            ];
        });

        return response()->json($hijos);
    }

    public function registrarHijo(Request $request, $clienteId)
    {
        try {
            $cliente = Cliente::findOrFail($clienteId);

            $validated = $request->validate([
                'carnet'            => 'nullable|string|max:20|unique:personas,carnet',
                'nombres'           => 'required|string|max:100',
                'apellido_paterno'  => 'required|string|max:50',
                'apellido_materno'  => 'nullable|string|max:50',
                'fecha_nacimiento'  => 'required|date',
                'relacion'          => 'required|string|max:50',
            ]);

            DB::beginTransaction();

            $persona = Persona::create([
                'carnet'            => $validated['carnet'],
                'nombres'           => $validated['nombres'],
                'apellido_paterno'  => $validated['apellido_paterno'],
                'apellido_materno'  => $validated['apellido_materno'],
                'fecha_nacimiento'  => $validated['fecha_nacimiento'],
            ]);

            $hijo = Hijo::create(['persona_id' => $persona->id]);

            Parentesco::create([
                'cliente_id' => $cliente->id,
                'hijo_id'    => $hijo->id,
                'relacion'   => $validated['relacion'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Hijo registrado exitosamente',
                'hijo'    => $hijo->load('persona')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Error de validación'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al registrar el hijo: ' . $e->getMessage()], 500);
        }
    }
}
