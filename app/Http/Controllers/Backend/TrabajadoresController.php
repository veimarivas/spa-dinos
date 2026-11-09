<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trabajadore;
use App\Models\Persona;
use App\Models\Cargo;
use App\Models\CategoriaGasto;
use App\Models\Gasto;
use App\Models\Sesione;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TrabajadoresController extends Controller
{
    /**
     * Listar trabajadores con búsqueda y paginación.
     */
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Trabajadore::with(['persona', 'cargo'])->withCount('sesiones');

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

        $trabajadores = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.trabajadores.partials.table-body', compact('trabajadores'))->render(),
                'pagination' => (string) $trabajadores->links('pagination::bootstrap-5'),
                'total'      => $trabajadores->total(),
                'from'       => $trabajadores->firstItem(),
                'to'         => $trabajadores->lastItem(),
            ]);
        }

        // Para el modal de registro necesitamos los cargos y roles
        $cargos = Cargo::orderBy('nombre')->get();
        $roles  = Role::orderBy('name')->get();
        return view('admin.trabajadores.listar', compact('trabajadores', 'cargos', 'roles'));
    }

    /**
     * Registrar un nuevo trabajador (persona + trabajador).
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
                'cargo_id'              => 'required|exists:cargos,id',
                'color'                 => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            ]);

            DB::beginTransaction();

            $persona = Persona::where('carnet', $validated['carnet'])->first();

            if ($persona) {
                if ($persona->trabajador) {
                    return response()->json([
                        'success' => false,
                        'msg'     => 'Esta persona ya está registrada como trabajador.'
                    ], 422);
                }

                $persona->update($validated);
            } else {
                $persona = Persona::create($validated);
            }

            $trabajador = Trabajadore::create([
                'persona_id' => $persona->id,
                'cargo_id'   => $validated['cargo_id'],
                'color'      => $validated['color'] ?? '#405189',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Trabajador registrado exitosamente',
                'trabajador' => $trabajador->load('persona', 'cargo')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Error de validación'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al registrar el trabajador: ' . $e->getMessage()], 500);
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
                'is_worker' => false,
                'message'   => 'Carnet disponible',
                'persona'   => null
            ]);
        }

        $isWorker = $persona->trabajador()->exists();

        if ($isWorker) {
            return response()->json([
                'exists'    => true,
                'is_worker' => true,
                'message'   => 'Ya existe un trabajador con este carnet',
                'persona'   => null
            ]);
        } else {
            return response()->json([
                'exists'    => true,
                'is_worker' => false,
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
        $personaId = $request->input('persona_id');

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
     * Modificar datos del trabajador (persona y cargo).
     */
    public function modificar(Request $request)
    {
        try {
            $trabajador = Trabajadore::with('persona')->findOrFail($request->id);

            $validated = $request->validate([
                'carnet'           => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('personas', 'carnet')->ignore($trabajador->persona->id),
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
                'cargo_id'         => 'required|exists:cargos,id',
                'color'            => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            ]);

            DB::beginTransaction();

            $trabajador->persona->update($validated);
            $trabajador->update([
                'cargo_id' => $validated['cargo_id'],
                'color'    => $validated['color'] ?? $trabajador->color, // mantiene el actual si no se envía
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Trabajador actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Error de validación'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al actualizar el trabajador'], 500);
        }
    }

    /**
     * Eliminar trabajador (y su persona si no está vinculada a otros roles).
     */
    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $trabajador = Trabajadore::with('persona')->findOrFail($id);

            // Verificar si tiene sesiones asociadas
            if ($trabajador->sesiones()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar el trabajador porque tiene sesiones asignadas'
                ], 400);
            }

            $persona = $trabajador->persona;

            DB::beginTransaction();
            $trabajador->delete();

            // Verificar si la persona es usada por otros modelos (cliente, user, hijo)
            $usages = 0;
            if ($persona->cliente) $usages++;
            if ($persona->user) $usages++;
            if ($persona->hijo) $usages++;

            if ($usages === 0) {
                $persona->delete();
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Trabajador eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => 'Error al eliminar el trabajador'], 500);
        }
    }

    /**
     * Ver perfil y calendario de actividades del trabajador.
     */
    public function ver($id)
    {
        $trabajador = Trabajadore::with(['persona', 'cargo'])->findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('Fisioterapeuta') && $user->persona_id !== $trabajador->persona_id) {
            abort(403, 'No tienes permiso para ver este perfil.');
        }

        $coloresPorEstado = [
            'programada'   => '#f59e0b',
            'confirmada'   => '#198754',
            'realizada'    => '#0d6efd',
            'cancelada'    => '#dc3545',
            'reprogramada' => '#6c757d',
        ];
        $textoOscuro = ['programada']; // solo amber necesita texto oscuro

        // Cargar sesiones con sus relaciones
        $sesiones = $trabajador->sesiones()
            ->with([
                'hijo.persona',
                'suscripcion.cliente.persona',
                'planServicio.servicio',
                'planServicio.plan',
            ])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        // Construir eventos para FullCalendar
        $eventos = [];
        foreach ($sesiones as $sesion) {
            if (!$sesion->fecha || !$sesion->hora_inicio) continue;

            $fecha     = $sesion->fecha instanceof \Carbon\Carbon
                ? $sesion->fecha->format('Y-m-d')
                : \Carbon\Carbon::parse($sesion->fecha)->format('Y-m-d');

            $horaInicio = \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i:s');
            $horaFin    = $sesion->hora_fin
                ? \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i:s')
                : \Carbon\Carbon::parse($sesion->hora_inicio)->addHour()->format('H:i:s');

            $clienteNombre = $sesion->suscripcion?->cliente?->persona
                ? $sesion->suscripcion->cliente->persona->nombres . ' ' . $sesion->suscripcion->cliente->persona->apellido_paterno
                : 'Cliente';

            $hijoNombre = $sesion->hijo?->persona
                ? $sesion->hijo->persona->nombres . ' ' . $sesion->hijo->persona->apellido_paterno
                : null;

            $bgColor  = $coloresPorEstado[$sesion->estado] ?? '#6c757d';
            $txtColor = in_array($sesion->estado, $textoOscuro) ? '#212529' : '#ffffff';

            $title = '#' . $sesion->n_sesion . ' ' . ($hijoNombre ?? $clienteNombre);

            $eventos[] = [
                'id'              => $sesion->id,
                'title'           => $title,
                'start'           => $fecha . 'T' . $horaInicio,
                'end'             => $fecha . 'T' . $horaFin,
                'backgroundColor' => $bgColor,
                'borderColor'     => $bgColor,
                'textColor'       => $txtColor,
                'extendedProps'   => [
                    'estado'         => $sesion->estado,
                    'cliente'        => $clienteNombre,
                    'hijo'           => $hijoNombre,
                    'servicio'       => $sesion->planServicio?->servicio?->nombre ?? '',
                    'suscripcion_id' => $sesion->suscripcion?->id,
                ],
            ];
        }

        // Estadísticas rápidas
        $stats = [
            'total'       => $sesiones->count(),
            'programadas' => $sesiones->where('estado', 'programada')->count(),
            'confirmadas' => $sesiones->where('estado', 'confirmada')->count(),
            'realizadas'  => $sesiones->where('estado', 'realizada')->count(),
            'canceladas'  => $sesiones->where('estado', 'cancelada')->count(),
        ];

        // Horario más temprano y más tardío para el slotMinTime/slotMaxTime
        $slotMinTime = '07:00:00';
        $slotMaxTime = '20:00:00';

        // Opciones para filtros del historial de sesiones realizadas
        $sesionesRealizadas = $trabajador->sesiones()
            ->with(['planServicio.servicio'])
            ->where('estado', 'realizada')
            ->get();

        $gestionesDisponibles = $sesionesRealizadas
            ->filter(fn($s) => $s->fecha)
            ->map(fn($s) => Carbon::parse($s->fecha)->year)
            ->unique()->sort()->values();

        $serviciosDisponibles = $sesionesRealizadas
            ->map(fn($s) => [
                'id'     => $s->planServicio?->servicio?->id,
                'nombre' => $s->planServicio?->servicio?->nombre ?? 'Sin servicio',
            ])
            ->filter(fn($s) => $s['id'])
            ->unique('id')->values();

        return view('admin.trabajadores.ver', compact(
            'trabajador', 'eventos', 'coloresPorEstado', 'stats', 'slotMinTime', 'slotMaxTime',
            'gestionesDisponibles', 'serviciosDisponibles'
        ));
    }

    /**
     * Historial de informes del trabajador con filtros (AJAX).
     */
    public function getInformes(Request $request, $id)
    {
        $trabajador = Trabajadore::findOrFail($id);

        $query = $trabajador->sesiones()
            ->with([
                'hijo.persona',
                'suscripcion.cliente.persona',
                'planServicio.servicio',
            ])
            ->where('estado', 'realizada');

        if ($request->filled('gestion')) {
            $query->whereYear('fecha', $request->gestion);
        }
        if ($request->filled('mes')) {
            $query->whereMonth('fecha', $request->mes);
        }
        if ($request->filled('dia')) {
            $query->whereDay('fecha', $request->dia);
        }
        if ($request->filled('servicio_id')) {
            $query->whereHas('planServicio', function ($q) use ($request) {
                $q->where('servicio_id', $request->servicio_id);
            });
        }

        $informes = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc')->get();

        $data = $informes->map(function ($s) {
            $servicio    = $s->planServicio?->servicio;
            $asistio     = $s->asistio_hijo; // true / false / null
            $esTarifaEspecial = ($s->suscripcion->tarifa ?? 'normal') === 'especial';
            $servicioConEspecial = $esTarifaEspecial && !is_null($servicio?->pago_especial);
            $pago        = match(true) {
                $servicioConEspecial              => $servicio->pago_especial,
                $asistio === true                 => $servicio?->pago_con_sesion,
                $asistio === false                => $servicio?->pago_sin_sesion,
                default                           => null,
            };

            return [
                'id'              => $s->id,
                'n_sesion'        => $s->n_sesion,
                'fecha'           => Carbon::parse($s->fecha)->format('d/m/Y'),
                'fecha_raw'       => $s->fecha,
                'hora_inicio'     => $s->hora_inicio ? substr($s->hora_inicio, 0, 5) : null,
                'hora_fin'        => $s->hora_fin    ? substr($s->hora_fin,    0, 5) : null,
                'servicio'        => $servicio?->nombre ?? 'Sin servicio',
                'hijo'            => $s->hijo?->persona
                    ? $s->hijo->persona->nombres . ' ' . $s->hijo->persona->apellido_paterno
                    : null,
                'cliente'         => $s->suscripcion?->cliente?->persona
                    ? $s->suscripcion->cliente->persona->nombres . ' ' . $s->suscripcion->cliente->persona->apellido_paterno
                    : 'Sin cliente',
                'informe'         => $s->informe_sesion,
                'asistio_hijo'    => $asistio,
                'pago'            => $pago,
                'pago_con_sesion' => $servicio?->pago_con_sesion,
                'pago_sin_sesion' => $servicio?->pago_sin_sesion,
                'pago_salario_id' => $s->pago_salario_id,
                'tarifa'          => $s->suscripcion->tarifa ?? 'normal',
            ];
        });

        $totalPago      = $data->whereNotNull('pago')->sum('pago');
        $conAsistencia  = $data->where('asistio_hijo', true)->count();
        $sinAsistencia  = $data->where('asistio_hijo', false)->count();
        $pendientes     = $data->filter(fn($r) => is_null($r['asistio_hijo']))->count();
        $pagoConTotal   = $data->where('asistio_hijo', true)->sum('pago');
        $pagoSinTotal   = $data->where('asistio_hijo', false)->sum('pago');

        $conAsistenciaNormal   = $data->where('asistio_hijo', true)->where('tarifa', 'normal')->count();
        $conAsistenciaEspecial = $data->where('asistio_hijo', true)->where('tarifa', 'especial')->count();
        $pagoConNormal         = $data->where('asistio_hijo', true)->where('tarifa', 'normal')->sum('pago');
        $pagoConEspecial       = $data->where('asistio_hijo', true)->where('tarifa', 'especial')->sum('pago');

        return response()->json([
            'success'               => true,
            'data'                  => $data->values(),
            'total'                 => $data->count(),
            'total_pago'            => $totalPago,
            'con_asistencia'        => $conAsistencia,
            'sin_asistencia'        => $sinAsistencia,
            'pendientes'            => $pendientes,
            'pago_con_total'        => $pagoConTotal,
            'pago_sin_total'        => $pagoSinTotal,
            'con_asistencia_normal'   => $conAsistenciaNormal,
            'con_asistencia_especial' => $conAsistenciaEspecial,
            'pago_con_normal'         => $pagoConNormal,
            'pago_con_especial'       => $pagoConEspecial,
        ]);
    }

    /**
     * Obtener lista de cargos para selects (AJAX).
     */
    public function getCargos()
    {
        $cargos = Cargo::orderBy('nombre')->get(['id', 'nombre']);
        return response()->json($cargos);
    }

    /**
     * Resumen de salarios pendientes y pagados agrupados por mes (AJAX).
     * Una sesión es "pagable" cuando:
     *   - estado = realizada
     *   - asistio_hijo no es null
     *   - si asistio_hijo = true  → debe tener informe_sesion registrado
     *   - si asistio_hijo = false → no requiere informe (sesión sin asistencia)
     */
    public function getSalariosResumen($id)
    {
        $trabajador = Trabajadore::with('persona')->findOrFail($id);

        $sesiones = $trabajador->sesiones()
            ->with(['planServicio.servicio', 'pagoSalario', 'suscripcion'])
            ->where('estado', 'realizada')
            ->whereNotNull('asistio_hijo')
            ->orderBy('fecha')
            ->get();

        $meses = [];

        foreach ($sesiones as $s) {
            if (!$s->fecha) continue;

            $fecha    = Carbon::parse($s->fecha);
            $key      = $fecha->format('Y-m');
            $servicio = $s->planServicio?->servicio;

            $esTarifaEspecial = ($s->suscripcion->tarifa ?? 'normal') === 'especial';
            $servicioConEspecial = $esTarifaEspecial && !is_null($servicio?->pago_especial);

            $pagable = $s->asistio_hijo === false
                || ($s->asistio_hijo === true && !empty($s->informe_sesion));

            $pago = match(true) {
                $servicioConEspecial               => (float) ($servicio->pago_especial ?? 0),
                $s->asistio_hijo === true           => (float) ($servicio?->pago_con_sesion ?? 0),
                $s->asistio_hijo === false          => (float) ($servicio?->pago_sin_sesion ?? 0),
                default                             => 0,
            };

            $sesionData = [
                'id'          => $s->id,
                'n_sesion'    => $s->n_sesion,
                'fecha'       => $fecha->format('d/m/Y'),
                'servicio'    => $servicio?->nombre ?? 'Sin servicio',
                'asistio'     => $s->asistio_hijo,
                'informe_ok'  => !empty($s->informe_sesion),
                'pago'        => $pago,
                'pagado'      => !is_null($s->pago_salario_id),
                'gasto_id'    => $s->pago_salario_id,
                'pagable'     => $pagable,
                'tarifa'      => $s->suscripcion->tarifa ?? 'normal',
            ];

            if (!isset($meses[$key])) {
                $meses[$key] = [
                    'key'              => $key,
                    'año'              => $fecha->year,
                    'mes'              => $fecha->month,
                    'mes_label'        => ucfirst($fecha->locale('es')->isoFormat('MMMM YYYY')),
                    'sesiones_pagables'    => [],
                    'sesiones_pagadas'     => [],
                    'sesiones_pendientes'  => [],
                    'total_pendiente'      => 0,
                    'total_pagado'         => 0,
                    'total_sin_informe'    => 0,
                    'gasto'                => null,
                ];
            }

            if ($sesionData['pagado']) {
                $meses[$key]['sesiones_pagadas'][] = $sesionData;
                $meses[$key]['total_pagado'] += $pago;
                // Capture gasto info once
                if (!$meses[$key]['gasto'] && $s->pagoSalario) {
                    $meses[$key]['gasto'] = [
                        'id'          => $s->pagoSalario->id,
                        'descripcion' => $s->pagoSalario->descripcion,
                        'fecha'       => Carbon::parse($s->pagoSalario->fecha_gasto)->format('d/m/Y'),
                        'monto'       => (float) $s->pagoSalario->monto_bs,
                    ];
                }
            } elseif ($pagable) {
                $meses[$key]['sesiones_pagables'][] = $sesionData;
                $meses[$key]['total_pendiente'] += $pago;
            } else {
                // asistio=true pero sin informe aún
                $meses[$key]['sesiones_pendientes'][] = $sesionData;
                $meses[$key]['total_sin_informe'] += $pago;
            }
        }

        // Sort descending
        krsort($meses);

        return response()->json([
            'success' => true,
            'meses'   => array_values($meses),
        ]);
    }

    /**
     * Registrar pago de salario de un mes: crea un Gasto y enlaza las sesiones.
     */
    public function pagarSalarioMes(Request $request, $id)
    {
        $request->validate([
            'año'                  => 'required|integer|min:2000|max:2100',
            'mes'                  => 'required|integer|min:1|max:12',
            'observacion'          => 'nullable|string|max:500',
            'incluir_sin_informe'  => 'nullable|boolean',
        ]);

        $incluirSinInforme = (bool) $request->input('incluir_sin_informe', false);

        $trabajador = Trabajadore::with('persona')->findOrFail($id);
        $nombre     = trim($trabajador->persona->nombres . ' ' . $trabajador->persona->apellido_paterno);

        // Buscar sesiones del mes no pagadas
        $sesiones = $trabajador->sesiones()
            ->with(['planServicio.servicio', 'suscripcion'])
            ->where('estado', 'realizada')
            ->whereNotNull('asistio_hijo')
            ->whereNull('pago_salario_id')
            ->whereYear('fecha', $request->año)
            ->whereMonth('fecha', $request->mes)
            ->get()
            ->filter(function ($s) use ($incluirSinInforme) {
                if ($s->asistio_hijo === false) return true;
                if (!empty($s->informe_sesion)) return true;
                return $incluirSinInforme; // si no tiene informe, depende del flag
            });

        if ($sesiones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay sesiones para pagar en este mes con los criterios seleccionados.',
            ], 422);
        }

        $totalMonto = $sesiones->sum(function ($s) {
            $servicio = $s->planServicio?->servicio;
            $esTarifaEspecial = ($s->suscripcion->tarifa ?? 'normal') === 'especial';
            $servicioConEspecial = $esTarifaEspecial && !is_null($servicio?->pago_especial);
            return match(true) {
                $servicioConEspecial               => (float) ($servicio->pago_especial ?? 0),
                $s->asistio_hijo === true           => (float) ($servicio?->pago_con_sesion ?? 0),
                $s->asistio_hijo === false          => (float) ($servicio?->pago_sin_sesion ?? 0),
                default                             => 0,
            };
        });

        $categoriaGasto = CategoriaGasto::where('nombre', 'like', '%Salario%')->first();

        if (!$categoriaGasto) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la categoría "Salarios" en gastos. Verifique la configuración.',
            ], 422);
        }

        $mesNombre = Carbon::create($request->año, $request->mes, 1)->locale('es')->isoFormat('MMMM YYYY');
        $descripcion = "Salario sesiones {$nombre} — {$mesNombre} ({$sesiones->count()} sesiones)";

        try {
            DB::transaction(function () use ($sesiones, $totalMonto, $categoriaGasto, $descripcion, $request) {
                $gasto = Gasto::create([
                    'categoria_gasto_id'    => $categoriaGasto->id,
                    'descripcion'           => $descripcion,
                    'monto_bs'              => $totalMonto,
                    'fecha_gasto'           => Carbon::now()->format('Y-m-d'),
                    'mes_correspondiente'   => $request->mes,
                    'anio_correspondiente'  => $request->año,
                    'observacion'           => $request->observacion,
                ]);

                foreach ($sesiones as $s) {
                    $s->update(['pago_salario_id' => $gasto->id]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => "Pago de Bs {$totalMonto} registrado para {$sesiones->count()} sesiones de {$mesNombre}.",
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * PDF del salario YA PAGADO de un mes (general o detallado).
     * Query params: ?año=2026&mes=6&tipo=general|detallado
     */
    public function salarioPagadoPdf(Request $request, $id)
    {
        $request->validate([
            'año'  => 'required|integer|min:2000|max:2100',
            'mes'  => 'required|integer|min:1|max:12',
            'tipo' => 'nullable|in:general,detallado',
        ]);

        $tipo       = $request->input('tipo', 'general');
        $anio       = (int) $request->input('año');
        $mes        = (int) $request->input('mes');

        $trabajador = Trabajadore::with(['persona', 'cargo'])->findOrFail($id);

        // Sesiones del mes ya pagadas a este trabajador
        $sesiones = $trabajador->sesiones()
            ->with([
                'planServicio.servicio',
                'suscripcion.cliente.persona',
                'suscripcion.plan',
                'hijo.persona',
                'pagoSalario',
            ])
            ->where('estado', 'realizada')
            ->whereNotNull('pago_salario_id')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        if ($sesiones->isEmpty()) {
            return redirect()->back()->with('error', 'No hay sesiones pagadas en el mes seleccionado.');
        }

        // Información del gasto (se asume que todas las sesiones del mes están asociadas al mismo pago)
        $gasto = $sesiones->first()->pagoSalario;

        // Armar registros para el PDF
        $registros = $sesiones->map(function ($s) {
            $servicio = $s->planServicio?->servicio;
            $tarifa   = $s->suscripcion->tarifa ?? 'normal';
            $servicioConEspecial = $tarifa === 'especial' && !is_null($servicio?->pago_especial);

            $pago = match (true) {
                $servicioConEspecial               => (float) ($servicio->pago_especial ?? 0),
                $s->asistio_hijo === true          => (float) ($servicio?->pago_con_sesion ?? 0),
                $s->asistio_hijo === false         => (float) ($servicio?->pago_sin_sesion ?? 0),
                default                            => 0,
            };

            $cliente = $s->suscripcion?->cliente?->persona;
            $hijo    = $s->hijo?->persona;

            return [
                'id'           => $s->id,
                'fecha'        => optional($s->fecha)->format('d/m/Y'),
                'hora'         => $s->hora_inicio . ' – ' . $s->hora_fin,
                'n_sesion'     => $s->n_sesion,
                'servicio'     => $servicio?->nombre ?? 'Sin servicio',
                'tarifa'       => $tarifa,
                'asistio'      => $s->asistio_hijo,
                'tiene_informe' => !empty($s->informe_sesion),
                'plan'         => $s->suscripcion?->plan?->nombre_plan ?? '—',
                'cliente'      => $cliente ? trim($cliente->nombres . ' ' . $cliente->apellido_paterno) : '—',
                'hijo'         => $hijo ? trim($hijo->nombres . ' ' . $hijo->apellido_paterno) : '—',
                'pago'         => $pago,
            ];
        });

        $totalPagado = $registros->sum('pago');
        $totalNormal = $registros->where('tarifa', 'normal')->sum('pago');
        $totalEspecial = $registros->where('tarifa', 'especial')->sum('pago');

        $mesesEs = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $mesLabel = $mesesEs[$mes - 1] . ' ' . $anio;

        $data = [
            'trabajador'    => $trabajador,
            'tipo'          => $tipo,
            'mesLabel'      => $mesLabel,
            'registros'     => $registros,
            'totalPagado'   => $totalPagado,
            'totalNormal'   => $totalNormal,
            'totalEspecial' => $totalEspecial,
            'gasto'         => $gasto,
            'fechaEmision'  => now(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.trabajadores.pdf.salario-pagado', $data);
        $pdf->setPaper('letter', $tipo === 'detallado' ? 'landscape' : 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('defaultFont', 'DejaVu Sans');

        $nombreArchivo = 'SALARIO-' . str_replace(' ', '_', $mesLabel) . '-' . strtoupper($tipo) . '-' . $trabajador->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }

    /**
     * Vista agregada: pagos a TODOS los trabajadores en un mes/gestión.
     * Aplica la misma lógica del tab "Liquidación de Salarios" pero para todos.
     */
    public function pagosTrabajadoresIndex(Request $request)
    {
        $mesSeleccionado     = (int) ($request->input('mes')     ?? now()->month);
        $gestionSeleccionada = (int) ($request->input('gestion') ?? now()->year);

        $anioActual = (int) now()->year;
        $gestionesDisponibles = range($anioActual, $anioActual - 5);

        $mesesEs = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $mesLabel = $mesesEs[$mesSeleccionado - 1] . ' ' . $gestionSeleccionada;

        // Carga de sesiones del mes/año, con relaciones necesarias para el cálculo
        $sesiones = Sesione::with([
                'planServicio.servicio',
                'trabajador.persona',
                'trabajador.cargo',
                'suscripcion',
                'pagoSalario',
            ])
            ->where('estado', 'realizada')
            ->whereNotNull('asistio_hijo')
            ->whereNotNull('trabajadore_id')
            ->whereYear('fecha', $gestionSeleccionada)
            ->whereMonth('fecha', $mesSeleccionado)
            ->get();

        $resumen = $sesiones->groupBy('trabajadore_id')->map(function ($group) {
            $trabajador = $group->first()->trabajador;
            if (!$trabajador) return null;

            $cntPagables = 0; $cntPagadas = 0; $cntPendientes = 0;
            $totalPagable = 0.0; $totalPagado = 0.0; $totalSinInforme = 0.0;
            $cntNormal = 0; $cntEspecial = 0;
            $totalNormal = 0.0; $totalEspecial = 0.0;
            $gasto = null;

            foreach ($group as $s) {
                $servicio = $s->planServicio?->servicio;
                $tarifa = $s->suscripcion->tarifa ?? 'normal';
                $servicioConEspecial = $tarifa === 'especial' && !is_null($servicio?->pago_especial);

                $pago = match (true) {
                    $servicioConEspecial               => (float) ($servicio->pago_especial ?? 0),
                    $s->asistio_hijo === true          => (float) ($servicio?->pago_con_sesion ?? 0),
                    $s->asistio_hijo === false         => (float) ($servicio?->pago_sin_sesion ?? 0),
                    default                            => 0,
                };

                if ($tarifa === 'especial') { $cntEspecial++; $totalEspecial += $pago; }
                else                          { $cntNormal++;   $totalNormal   += $pago; }

                $pagable = $s->asistio_hijo === false
                    || ($s->asistio_hijo === true && !empty($s->informe_sesion));

                if ($s->pago_salario_id) {
                    $cntPagadas++; $totalPagado += $pago;
                    if (!$gasto && $s->pagoSalario) {
                        $gasto = [
                            'id'          => $s->pagoSalario->id,
                            'descripcion' => $s->pagoSalario->descripcion,
                            'fecha'       => \Carbon\Carbon::parse($s->pagoSalario->fecha_gasto)->format('d/m/Y'),
                            'monto'       => (float) $s->pagoSalario->monto_bs,
                        ];
                    }
                } elseif ($pagable) {
                    $cntPagables++; $totalPagable += $pago;
                } else {
                    $cntPendientes++; $totalSinInforme += $pago;
                }
            }

            $estado = $cntPagables > 0
                ? 'pendiente'
                : ($cntPagadas > 0 && $cntPendientes === 0 ? 'pagado' : ($cntPendientes > 0 ? 'sin_informe' : 'mixto'));

            return [
                'trabajador'        => $trabajador,
                'cantidad_total'    => $group->count(),
                'cnt_pagables'      => $cntPagables,
                'cnt_pagadas'       => $cntPagadas,
                'cnt_pendientes'    => $cntPendientes,
                'cnt_normal'        => $cntNormal,
                'cnt_especial'      => $cntEspecial,
                'total_pagable'     => round($totalPagable, 2),
                'total_pagado'      => round($totalPagado, 2),
                'total_sin_informe' => round($totalSinInforme, 2),
                'total_normal'      => round($totalNormal, 2),
                'total_especial'    => round($totalEspecial, 2),
                'gasto'             => $gasto,
                'estado'            => $estado,
            ];
        })->filter()->values();

        // Totales globales del mes
        $totalPagadoGlobal      = $resumen->sum('total_pagado');
        $totalPagableGlobal     = $resumen->sum('total_pagable');
        $totalSinInformeGlobal  = $resumen->sum('total_sin_informe');
        $cantTrabajadoresPend   = $resumen->where('cnt_pagables', '>', 0)->count();
        $cantTrabajadoresPag    = $resumen->where('cnt_pagadas', '>', 0)->count();

        return view('admin.pagos-trabajadores.index', compact(
            'mesSeleccionado',
            'gestionSeleccionada',
            'gestionesDisponibles',
            'mesLabel',
            'resumen',
            'totalPagadoGlobal',
            'totalPagableGlobal',
            'totalSinInformeGlobal',
            'cantTrabajadoresPend',
            'cantTrabajadoresPag'
        ));
    }
}
