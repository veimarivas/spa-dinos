<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Cliente;
use App\Models\Detalle;
use App\Models\Hijo;
use App\Models\Pago;
use App\Models\Paquete;
use App\Models\Parentesco;
use App\Models\Persona;
use App\Models\Plane;
use App\Models\PlanesServicios;
use App\Models\Servicio;
use App\Models\Sesione;

use App\Models\Suscripcione;
use App\Models\Trabajadore;
use App\Models\Venta;
use App\Models\VentaPagoDetalle;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Barryvdh\DomPDF\Facade\Pdf;

class SuscripcionesController extends Controller
{
    public function index(Request $request)
    {
        $query = Suscripcione::with([
            'cliente.persona',
            'plan.paquete',
            'pagos',
            'primeraSesion.hijo.persona',
        ])->withCount([
            'sesiones as sesiones_count'          => fn($q) => $q->whereNotIn('estado', ['reprogramada', 'cancelada']),
            'sesiones as sesiones_realizadas'      => fn($q) => $q->whereIn('estado', ['realizada', 'confirmada']),
            'sesiones as sesiones_programadas'     => fn($q) => $q->where('estado', 'programada'),
        ]);

        // Búsqueda
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->whereHas('cliente.persona', function ($qq) use ($term) {
                    $qq->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellido_paterno', 'like', "%{$term}%")
                        ->orWhere('apellido_materno', 'like', "%{$term}%")
                        ->orWhere('carnet', 'like', "%{$term}%")
                        ->orWhere('correo', 'like', "%{$term}%");
                })
                ->orWhereHas('plan', function ($qq) use ($term) {
                    $qq->where('nombre_plan', 'like', "%{$term}%");
                })
                ->orWhereHas('plan.paquete', function ($qq) use ($term) {
                    $qq->where('nombre', 'like', "%{$term}%");
                })
                ->orWhereHas('primeraSesion.hijo.persona', function ($qq) use ($term) {
                    $qq->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellido_paterno', 'like', "%{$term}%")
                        ->orWhere('carnet', 'like', "%{$term}%");
                });
            });
        }

        // Filtro por tarifa
        if ($request->filled('tarifa') && in_array($request->tarifa, ['normal', 'especial'])) {
            $query->where('tarifa', $request->tarifa);
        }

        $suscripciones = $query->orderBy('fecha_registro', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->only('search', 'tarifa'));

        // Para AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.suscripciones.partials.table', compact('suscripciones'))->render()
            ]);
        }

        return view('admin.suscripciones.index', compact('suscripciones'));
    }

    public function create(Request $request)
    {
        $paquetes = Paquete::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombre')->get();
        $trabajadores = Trabajadore::with('persona')
            ->whereHas('cargo', function ($query) {
                $query->where('nombre', 'Fisioterapeuta');
            })
            ->get();

        $preCliente = null;
        $preHijoId  = null;

        if ($request->filled('cliente_id')) {
            $preCliente = \App\Models\Cliente::with('persona')->find($request->cliente_id);
            $preHijoId  = $request->get('hijo_id');
        }

        return view('admin.suscripciones.create', compact('paquetes', 'servicios', 'trabajadores', 'preCliente', 'preHijoId'));
    }

    public function serviciosPorPlan(Request $request)
    {
        $planId = $request->get('plan_id');
        if (!$planId) {
            return response()->json([]);
        }
        $servicios = PlanesServicios::with('servicio')
            ->where('plane_id', $planId)
            ->get();
        return response()->json($servicios);
    }

    public function planesPorPaquete(Request $request)
    {
        $paqueteId = $request->get('paquete_id');
        if (!$paqueteId) {
            return response()->json([]);
        }

        $planes = Plane::where('paquete_id', $paqueteId)
            ->orderBy('nombre_plan')
            ->get(['id', 'nombre_plan', 'cantidad_sesiones', 'precio', 'precio_especial']);

        return response()->json($planes);
    }

    public function reprogramar(Request $request, $sesionId)
    {
        $request->validate([
            'fecha'          => 'required|date',
            'hora_inicio'    => 'required|date_format:H:i',
            'trabajadore_id' => 'nullable|exists:trabajadores,id',
            'motivo'         => 'nullable|string|max:255',
        ]);

        $sesionOriginal = Sesione::findOrFail($sesionId);

        // Validar que la sesión original pueda reprogramarse
        if (!in_array($sesionOriginal->estado, ['programada', 'cancelada'])) {
            return back()->with('error', 'Solo se pueden reprogramar sesiones en estado "programada" o "cancelada".');
        }

        if ($sesionOriginal->reprogramaciones()->exists()) {
            return back()->with('error', 'Esta sesión ya fue reprogramada anteriormente.');
        }

        // Usar el trabajador elegido en el modal o el original si no se cambió
        $trabajadorId = $request->filled('trabajadore_id')
            ? $request->trabajadore_id
            : $sesionOriginal->trabajadore_id;

        // Calcular hora fin (1 hora después)
        $horaFin = date('H:i', strtotime($request->hora_inicio . ' +1 hour'));

        // Validar que no haya conflicto de horario con el trabajador elegido
        $conflicto = Sesione::whereDate('fecha', $request->fecha)
            ->where('trabajadore_id', $trabajadorId)
            ->whereNotIn('estado', ['reprogramada', 'cancelada'])
            ->where('id', '!=', $sesionOriginal->id)
            ->get()
            ->first(function ($s) use ($request, $horaFin) {
                $ini = strtotime($request->hora_inicio);
                $fin = strtotime($horaFin);
                $sIni = strtotime($s->hora_inicio);
                $sFin = strtotime($s->hora_fin);
                return $ini < $sFin && $sIni < $fin;
            });

        if ($conflicto) {
            return back()->with('error', 'El horario ' . $request->hora_inicio . ' ya está ocupado para este fisioterapeuta en esa fecha.');
        }

        DB::beginTransaction();
        try {
            // Crear nueva sesión (copia con nuevos datos)
            $nuevaSesion = $sesionOriginal->replicate();
            $nuevaSesion->fecha            = $request->fecha;
            $nuevaSesion->hora_inicio      = $request->hora_inicio;
            $nuevaSesion->hora_fin         = $horaFin;
            $nuevaSesion->trabajadore_id   = $trabajadorId;
            $nuevaSesion->estado           = 'programada';
            $nuevaSesion->reprogramar_id   = $sesionOriginal->id;
            $nuevaSesion->save();

            // Marcar la sesión original como reprogramada (sin modificar fecha/hora)
            $sesionOriginal->update([
                'estado' => 'reprogramada',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Sesión reprogramada correctamente. Se ha creado una nueva sesión.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al reprogramar: ' . $e->getMessage());
        }
    }

    public function editarSesion(Request $request, Sesione $sesion)
    {
        $request->validate([
            'fecha'          => 'required|date',
            'hora_inicio'    => 'required|date_format:H:i',
            'trabajadore_id' => 'nullable|exists:trabajadores,id',
        ]);

        $trabajadorId = $request->filled('trabajadore_id')
            ? $request->trabajadore_id
            : $sesion->trabajadore_id;

        $horaFin = date('H:i', strtotime($request->hora_inicio . ' +1 hour'));

        // Validar conflicto de horario (excluir la sesión actual)
        $conflicto = Sesione::whereDate('fecha', $request->fecha)
            ->where('trabajadore_id', $trabajadorId)
            ->whereNotIn('estado', ['reprogramada', 'cancelada'])
            ->where('id', '!=', $sesion->id)
            ->get()
            ->first(function ($s) use ($request, $horaFin) {
                $ini  = strtotime($request->hora_inicio);
                $fin  = strtotime($horaFin);
                $sIni = strtotime($s->hora_inicio);
                $sFin = strtotime($s->hora_fin);
                return $ini < $sFin && $sIni < $fin;
            });

        if ($conflicto) {
            return back()->with('error', 'El horario ' . $request->hora_inicio . ' ya está ocupado para este fisioterapeuta en esa fecha.');
        }

        $sesion->update([
            'fecha'          => $request->fecha,
            'hora_inicio'    => $request->hora_inicio,
            'hora_fin'       => $horaFin,
            'trabajadore_id' => $trabajadorId,
        ]);

        return back()->with('success', 'Sesión #' . $sesion->n_sesion . ' actualizada correctamente.');
    }

    public function guardarInforme(Request $request, Sesione $sesion)
    {
        if (!auth()->user()->can('trabajadores.registrar.informe')) {
            return response()->json([
                'success' => false,
                'msg'     => 'No tienes permiso para registrar informes'
            ], 403);
        }

        if ($sesion->estado !== 'realizada') {
            return response()->json([
                'success' => false,
                'msg'     => 'Solo se puede registrar informe en sesiones con estado "realizada"'
            ], 422);
        }

        $request->validate([
            'asistio_hijo'   => 'required|boolean',
            'informe_sesion' => 'nullable|string|max:3000',
        ], [
            'asistio_hijo.required' => 'Debe indicar si el niño/a asistió a la sesión',
            'informe_sesion.max'    => 'El informe no puede exceder 3000 caracteres',
        ]);

        $sesion->load('planServicio.servicio');

        $asistio  = $request->boolean('asistio_hijo');
        $servicio = $sesion->planServicio?->servicio;
        $pago     = $asistio
            ? ($servicio?->pago_con_sesion)
            : ($servicio?->pago_sin_sesion);

        $sesion->update([
            'asistio_hijo'   => $asistio,
            'informe_sesion' => $request->informe_sesion,
        ]);

        return response()->json([
            'success'      => true,
            'msg'          => 'Registro guardado exitosamente',
            'asistio_hijo' => $asistio,
            'informe'      => $sesion->informe_sesion,
            'pago'         => $pago,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'         => 'required|in:programada,confirmada,realizada,cancelada',
            'asistio_hijo'   => 'nullable|boolean',
            'informe_sesion' => 'nullable|string|max:3000',
        ]);

        $sesion = Sesione::findOrFail($id);
        $sesion->estado = $request->estado;

        if ($request->estado === 'realizada' && $request->has('asistio_hijo')) {
            $sesion->asistio_hijo   = $request->boolean('asistio_hijo');
            $sesion->informe_sesion = $request->informe_sesion;
        }

        $sesion->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'msg' => 'Estado actualizado correctamente']);
        }

        return redirect()->back()->with('success', 'Estado de la sesión actualizado correctamente.');
    }

    public function calendario(Request $request)
    {
        $coloresPorEstado = [
            'programada'   => '#FFD700',
            'confirmada'   => '#198754',
            'realizada'    => '#0d6efd',
            'cancelada'    => '#dc3545',
            'reprogramada' => '#FFFFFF',
        ];

        $cargoFisio = Cargo::where('nombre', 'Fisioterapeuta')->first();
        $fisioterapeutas = collect();
        $idsFisioterapeutas = [];

        if ($cargoFisio) {
            $fisioterapeutas = Trabajadore::where('cargo_id', $cargoFisio->id)
                ->with('persona')
                ->get();
            $idsFisioterapeutas = $fisioterapeutas->pluck('id')->toArray();
        }

        $query = Sesione::with([
            'suscripcion.cliente.persona',
            'hijo.persona',
            'trabajador.persona',
            'sesionOriginal',
            'reprogramaciones'
        ])
            ->whereNotNull('fecha')
            ->whereNotNull('hora_inicio')
            ->whereIn('trabajadore_id', $idsFisioterapeutas);

        $trabajadorId = $request->get('trabajador_id');

        // Si solo hay 1 fisioterapeuta, seleccionarlo automáticamente
        if (!$trabajadorId && $fisioterapeutas->count() === 1) {
            $trabajadorId = $fisioterapeutas->first()->id;
        }

        if ($trabajadorId && in_array($trabajadorId, $idsFisioterapeutas)) {
            $query->where('trabajadore_id', $trabajadorId);
        }

        $sesiones = $query->orderBy('fecha')->get();

        $eventos = [];

        foreach ($sesiones as $sesion) {
            // Determinar título y color de fondo según filtro
            if ($trabajadorId) {
                // Modo individual: título = hijo, fondo = color cliente
                $hijo = $sesion->hijo;
                $nombreHijo = $hijo && $hijo->persona
                    ? $hijo->persona->nombres . ' ' . $hijo->persona->apellido_paterno
                    : 'Hijo no asignado';
                $titulo = $nombreHijo;
                $colorFondo = $sesion->suscripcion->cliente->color ?? '#0d6efd';
            } else {
                // Modo todos: título = trabajador, fondo = color trabajador
                $trabajador = $sesion->trabajador;
                $nombreTrabajador = $trabajador && $trabajador->persona
                    ? $trabajador->persona->nombres . ' ' . $trabajador->persona->apellido_paterno
                    : 'Sin asignar';
                $titulo = $nombreTrabajador;
                $colorFondo = $trabajador->color ?? '#405189'; // color del trabajador o por defecto
            }

            // Si está reprogramada, mezclar con rojo
            if ($sesion->estado === 'reprogramada') {
                $colorFondo = $this->mezclarColores($colorFondo, '#FF0000', 0.5);
            }

            $colorTexto = $coloresPorEstado[$sesion->estado] ?? '#000000';

            // Si es reprogramación, agregar icono
            if (!is_null($sesion->reprogramar_id)) {
                $titulo = '↻ ' . $titulo;
            }

            $eventos[] = [
                'id'        => $sesion->id,
                'title'     => $titulo,
                'start'     => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_inicio,
                'end'       => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_fin,
                'color'     => $colorFondo,
                'textColor' => $colorTexto,
                'extendedProps' => [
                    'cliente'   => $sesion->suscripcion->cliente->persona->nombres . ' ' . $sesion->suscripcion->cliente->persona->apellido_paterno,
                    'hijo'      => $sesion->hijo?->persona?->nombres . ' ' . $sesion->hijo?->persona?->apellido_paterno,
                    'trabajador_nombre' => $sesion->trabajador?->persona?->nombres . ' ' . $sesion->trabajador?->persona?->apellido_paterno,
                    'trabajador_color'  => $sesion->trabajador?->color,
                    'suscripcion_id' => $sesion->suscripcione_id,
                    'n_sesion'  => $sesion->n_sesion,
                    'estado'    => $sesion->estado,
                    'tarifa'    => $sesion->suscripcion->tarifa ?? 'normal',
                    'es_reprogramacion' => !is_null($sesion->reprogramar_id),
                    'reprogramada_de' => $sesion->reprogramar_id,
                ]
            ];
        }

        $slotMinTime = '08:00:00';
        $slotMaxTime = '20:00:00';
        $slotDuration = '00:30:00';

        return view('admin.calendario.sesiones', compact(
            'eventos',
            'coloresPorEstado',
            'fisioterapeutas',
            'trabajadorId',
            'slotMinTime',
            'slotMaxTime',
            'slotDuration'
        ));
    }

    /**
     * Genera un color único para un trabajador basado en su ID.
     * Usa una paleta de colores predefinida o genera un color HSL.
     */
    private function getColorParaTrabajador($trabajadorId)
    {
        // Opción 1: Paleta fija de colores
        $paleta = [
            '#1f77b4',
            '#ff7f0e',
            '#2ca02c',
            '#d62728',
            '#9467bd',
            '#8c564b',
            '#e377c2',
            '#7f7f7f',
            '#bcbd22',
            '#17becf'
        ];
        $index = $trabajadorId % count($paleta);
        return $paleta[$index];

        // Opción 2: Generar color basado en hash (más variado)
        // $hash = md5($trabajadorId);
        // $h = hexdec(substr($hash, 0, 2)) / 255 * 360; // tono
        // $s = 70; // saturación
        // $l = 60; // luminosidad
        // return "hsl($h, {$s}%, {$l}%)";
    }



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
                'id' => $hijo->id,
                'text' => $hijo->persona->nombres . ' ' . $hijo->persona->apellido_paterno . ' - ' . ($hijo->persona->carnet ?? 'Sin CI'),
                'persona' => $hijo->persona
            ];
        });
        return response()->json($hijos);
    }

    public function show($id)
    {
        $coloresPorEstado = [
            'programada'   => '#f59e0b',
            'confirmada'   => '#198754',
            'realizada'    => '#0d6efd',
            'cancelada'    => '#dc3545',
            'reprogramada' => '#6c757d',
        ];

        $suscripcion = Suscripcione::with([
            'cliente.persona',
            'plan',
            'plan.paquete',
            'pagos',
            'sesiones' => function ($q) {
                $q->with([
                    'servicio',
                    'hijo.persona',
                    'trabajador.persona',
                    'sesionOriginal',
                    'reprogramaciones',
                    'ventas.detalles.producto',
                    'ventas.pagoDetalles',
                    'ventas.trabajador.persona',
                ])->orderBy('n_sesion');
            }
        ])->findOrFail($id);

        // Obtener información del hijo principal (de la primera sesión o el más frecuente)
        $hijoPrincipal = null;
        if ($suscripcion->sesiones->isNotEmpty()) {
            $hijoPrincipal = $suscripcion->sesiones->first()->hijo;
        }

        // Calcular totales de pago considerando tarifa
        $totalPagado = $suscripcion->pagos->sum('pago_bs');
        $totalDescuento = $suscripcion->pagos->sum('descuento_bs');
        $precioTotal = $suscripcion->monto_total
            ?? ($suscripcion->tarifa === 'especial' && $suscripcion->plan && !is_null($suscripcion->plan->precio_especial)
                ? (float) $suscripcion->plan->precio_especial
                : (float) ($suscripcion->plan->precio ?? 0));
        $saldoPendiente = max(0, $precioTotal - $totalPagado - $totalDescuento);

        // Light-background states need dark text; all others use white
        // programada (#f59e0b amber) is light → dark text; all others use white text
        $textoClaro = ['programada'];

        $eventos = [];
        foreach ($suscripcion->sesiones as $sesion) {
            if ($sesion->fecha && $sesion->hora_inicio && $sesion->hora_fin) {
                $colorFondo = $coloresPorEstado[$sesion->estado] ?? '#6c757d';
                $colorTexto = in_array($sesion->estado, $textoClaro) ? '#212529' : '#ffffff';

                $title = 'Sesión #' . $sesion->n_sesion;
                if ($sesion->servicio) {
                    $title .= ' - ' . $sesion->servicio->nombre;
                }
                if ($sesion->reprogramar_id) {
                    $title = '↻ ' . $title;
                }

                $eventos[] = [
                    'id'              => $sesion->id,
                    'title'           => $title,
                    'start'           => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_inicio,
                    'end'             => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_fin,
                    'backgroundColor' => $colorFondo,
                    'borderColor'     => $colorFondo,
                    'textColor'       => $colorTexto,
                    'extendedProps' => [
                        'cliente'    => $suscripcion->cliente->persona->nombres . ' ' . $suscripcion->cliente->persona->apellido_paterno,
                        'hijo'       => $sesion->hijo?->persona?->nombres . ' ' . $sesion->hijo?->persona?->apellido_paterno,
                        'suscripcion_id' => $sesion->suscripcione_id,
                        'n_sesion'   => $sesion->n_sesion,
                        'estado'     => $sesion->estado,
                        'servicio'   => $sesion->servicio?->nombre,
                        'trabajador' => $sesion->trabajador?->persona?->nombres . ' ' . $sesion->trabajador?->persona?->apellido_paterno,
                        'tarifa'     => $sesion->suscripcion->tarifa ?? 'normal',
                        'informe'    => $sesion->informe_sesion,
                        'es_reprogramada' => !is_null($sesion->reprogramar_id),
                        'reprogramar_id' => $sesion->reprogramar_id,
                        'tiene_reprogramaciones' => $sesion->reprogramaciones->count() > 0,
                    ]
                ];
            }
        }

        $trabajadores = Trabajadore::with('persona')
            ->whereHas('cargo', fn($q) => $q->where('nombre', 'Fisioterapeuta'))
            ->get();

        // Datos para agendar sesiones pendientes
        $sesionesPendientes = max(0, $suscripcion->sesiones_iniciales - $suscripcion->sesiones->whereNotIn('estado', ['reprogramada', 'cancelada'])->count());

        $hijos = Hijo::with('persona')
            ->whereHas('parentescos', fn($q) => $q->where('cliente_id', $suscripcion->cliente_id))
            ->get();

        $serviciosDePlan = PlanesServicios::with('servicio')
            ->where('plane_id', $suscripcion->plane_id)
            ->get();

        return view('admin.suscripciones.show', compact(
            'suscripcion',
            'eventos',
            'coloresPorEstado',
            'hijoPrincipal',
            'totalPagado',
            'totalDescuento',
            'precioTotal',
            'saldoPendiente',
            'trabajadores',
            'sesionesPendientes',
            'hijos',
            'serviciosDePlan'
        ));
    }

    public function agendarPendientes(Request $request, $id)
    {
        $request->validate([
            'sesiones'                        => 'required|array|min:1',
            'sesiones.*.hijo_id'              => 'required|exists:hijos,id',
            'sesiones.*.planes_servicio_id'   => 'required|exists:planes_servicios,id',
            'sesiones.*.fecha'                => 'required|date',
            'sesiones.*.hora_inicio'          => 'required|date_format:H:i',
            'sesiones.*.hora_fin'             => 'required|date_format:H:i',
            'sesiones.*.trabajadore_id'       => 'nullable|exists:trabajadores,id',
        ]);

        $suscripcion = Suscripcione::with(['sesiones', 'plan'])->findOrFail($id);

        $sesionesActuales = $suscripcion->sesiones->whereNotIn('estado', ['reprogramada', 'cancelada'])->count();
        $maxSesiones      = $suscripcion->sesiones_iniciales;
        $pendientes       = $maxSesiones - $sesionesActuales;
        $sesionesNuevas   = $request->sesiones;

        if (count($sesionesNuevas) > $pendientes) {
            return back()->withErrors(['sesiones' => "Solo puedes agendar {$pendientes} sesión(es) más."]);
        }

        $serviciosPermitidos = PlanesServicios::where('plane_id', $suscripcion->plane_id)->pluck('id')->toArray();

        DB::beginTransaction();
        try {
            $nSesion = $sesionesActuales + 1;
            foreach ($sesionesNuevas as $sesionData) {
                if (!in_array($sesionData['planes_servicio_id'], $serviciosPermitidos)) {
                    throw new \Exception("El servicio seleccionado no pertenece al plan.");
                }

                $this->verificarDisponibilidadHorario(
                    $sesionData['fecha'],
                    $sesionData['hora_inicio'],
                    $sesionData['hora_fin'],
                    $sesionData['trabajadore_id'],
                    null
                );

                Sesione::create([
                    'suscripcione_id'    => $suscripcion->id,
                    'hijo_id'            => $sesionData['hijo_id'],
                    'n_sesion'           => $nSesion++,
                    'planes_servicio_id' => $sesionData['planes_servicio_id'],
                    'fecha'              => $sesionData['fecha'],
                    'hora_inicio'        => $sesionData['hora_inicio'],
                    'hora_fin'           => $sesionData['hora_fin'],
                    'trabajadore_id'     => $sesionData['trabajadore_id'] ?? null,
                    'estado'             => 'programada',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.suscripciones.show', $suscripcion->id)
                ->with('success', count($sesionesNuevas) . ' sesión(es) agendada(s) correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['sesiones' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'plane_id' => 'required|exists:planes,id',
            'fecha_registro' => 'required|date',
            'tarifa' => 'nullable|in:normal,especial',
            'sesiones' => 'nullable|array',
            'sesiones.*.hijo_id' => 'required_with:sesiones|exists:hijos,id',
            'sesiones.*.planes_servicio_id' => 'required_with:sesiones|exists:planes_servicios,id',
            'sesiones.*.fecha' => 'required_with:sesiones|date',
            'sesiones.*.hora_inicio' => 'required_with:sesiones|date_format:H:i',
            'sesiones.*.hora_fin' => 'required_with:sesiones|date_format:H:i|after:sesiones.*.hora_inicio',
            'sesiones.*.trabajadore_id' => 'nullable|exists:trabajadores,id',
            // Validación de pagos
            'pagos' => 'nullable|array',
            'pagos.*.tipo_pago' => 'required|string|in:efectivo,qr,parcial',
            'pagos.*.monto' => 'nullable|numeric|min:0',
            'pagos.*.monto_efectivo' => 'nullable|numeric|min:0',
            'pagos.*.monto_qr' => 'nullable|numeric|min:0',
            'pagos.*.fecha_pago' => 'required|date',
            'pagos.*.descuento' => 'nullable|numeric|min:0',
        ]);

        $plan = Plane::findOrFail($request->plane_id);
        $totalSesionesPlan = $plan->cantidad_sesiones;

        $sesionesEnviadas = $request->sesiones ?? [];
        if (!empty($sesionesEnviadas) && count($sesionesEnviadas) > $totalSesionesPlan) {
            return back()->withErrors(['sesiones' => "El plan solo permite $totalSesionesPlan sesiones."]);
        }

        $serviciosPermitidos = PlanesServicios::where('plane_id', $plan->id)->pluck('id')->toArray();

        DB::beginTransaction();
        try {
            $tarifa = $request->input('tarifa', 'normal');

            // Resolver el monto de la deuda: precio normal o precio_especial
            if ($tarifa === 'especial') {
                if (is_null($plan->precio_especial)) {
                    throw new \Exception("El plan seleccionado no tiene precio especial configurado.");
                }
                $montoTotalPlan = (float) $plan->precio_especial;
            } else {
                $montoTotalPlan = (float) $plan->precio;
            }

            // Crear suscripción
            $suscripcion = Suscripcione::create([
                'cliente_id' => $request->cliente_id,
                'plane_id' => $request->plane_id,
                'fecha_registro' => $request->fecha_registro,
                'sesiones_iniciales' => $totalSesionesPlan,
                'sesiones_restantes' => $totalSesionesPlan,
                'estado' => 'activa',
                'tarifa' => $tarifa,
                'monto_total' => $montoTotalPlan,
            ]);

            // Crear sesiones (pueden ser 0 si no se agendaron aún)
            foreach ($sesionesEnviadas as $index => $sesionData) {
                if (!in_array($sesionData['planes_servicio_id'], $serviciosPermitidos)) {
                    throw new \Exception("El servicio seleccionado no pertenece al plan.");
                }

                $this->verificarDisponibilidadHorario(
                    $sesionData['fecha'],
                    $sesionData['hora_inicio'],
                    $sesionData['hora_fin'],
                    $sesionData['trabajadore_id'],
                    null
                );

                Sesione::create([
                    'suscripcione_id' => $suscripcion->id,
                    'hijo_id' => $sesionData['hijo_id'],
                    'n_sesion' => $index + 1,
                    'planes_servicio_id' => $sesionData['planes_servicio_id'],
                    'fecha' => $sesionData['fecha'],
                    'hora_inicio' => $sesionData['hora_inicio'],
                    'hora_fin' => $sesionData['hora_fin'],
                    'trabajadore_id' => $sesionData['trabajadore_id'] ?? null,
                    'estado' => 'programada',
                ]);
            }

            // Cobrador: trabajador vinculado al usuario autenticado
            $cobradorId = Trabajadore::where('persona_id', auth()->user()?->persona_id)->value('id');

            // Procesar pagos si se enviaron
            $pagosCreados = [];
            if ($request->has('pagos') && is_array($request->pagos)) {
                $totalPagado = 0;

                foreach ($request->pagos as $pagoData) {
                    // Calcular monto total según tipo de pago
                    $montoTotal = 0;

                    if ($pagoData['tipo_pago'] === 'parcial') {
                        $montoEfectivo = $pagoData['monto_efectivo'] ?? 0;
                        $montoQr = $pagoData['monto_qr'] ?? 0;
                        $montoTotal = $montoEfectivo + $montoQr;

                        // Validar que al menos uno sea mayor a 0
                        if ($montoEfectivo <= 0 && $montoQr <= 0) {
                            throw new \Exception("En pago parcial, al menos un monto debe ser mayor a 0.");
                        }
                    } else {
                        $montoTotal = $pagoData['monto'] ?? 0;
                    }

                    $totalPagado += $montoTotal;
                }

                // Validar que el total pagado no exceda el monto a cobrar (precio o precio_especial)
                if ($totalPagado > $montoTotalPlan) {
                    throw new \Exception("El monto total de los pagos ({$totalPagado}) supera el precio a cobrar ({$montoTotalPlan}).");
                }

                // Crear cada pago
                foreach ($request->pagos as $pagoData) {
                    // Calcular monto total según tipo de pago
                    $montoTotal = 0;

                    if ($pagoData['tipo_pago'] === 'parcial') {
                        $montoEfectivo = $pagoData['monto_efectivo'] ?? 0;
                        $montoQr = $pagoData['monto_qr'] ?? 0;
                        $montoTotal = $montoEfectivo + $montoQr;
                    } else {
                        $montoTotal = $pagoData['monto'] ?? 0;
                    }

                    // Generar número de recibo secuencial
                    $recibo = IdGenerator::generate([
                        'table' => 'pagos',
                        'field' => 'recibo',
                        'length' => 10,
                        'prefix' => 'DINO-',
                        'reset_on_prefix_change' => true
                    ]);

                    // Crear el pago
                    $pago = Pago::create([
                        'recibo'           => $recibo,
                        'pago_bs'          => $montoTotal,
                        'descuento_bs'     => $pagoData['descuento'] ?? 0,
                        'fecha_pago'       => $pagoData['fecha_pago'] ?? now(),
                        'tipo_pago'        => $pagoData['tipo_pago'],
                        'suscripcione_id'  => $suscripcion->id,
                        'trabajadore_id'   => $cobradorId,
                    ]);
                    $pagosCreados[] = ['id' => $pago->id, 'recibo' => $recibo];

                    // Crear detalles del pago según tipo
                    if ($pagoData['tipo_pago'] === 'parcial') {
                        // Crear detalle para efectivo (si hay monto)
                        if (($pagoData['monto_efectivo'] ?? 0) > 0) {
                            Detalle::create([
                                'pago_id'   => $pago->id,
                                'pago_bs'   => $pagoData['monto_efectivo'],
                                'tipo_pago' => 'efectivo',
                            ]);
                        }

                        // Crear detalle para QR (si hay monto)
                        if (($pagoData['monto_qr'] ?? 0) > 0) {
                            Detalle::create([
                                'pago_id'   => $pago->id,
                                'pago_bs'   => $pagoData['monto_qr'],
                                'tipo_pago' => 'qr',
                            ]);
                        }
                    } else {
                        // Crear detalle único para efectivo o qr
                        Detalle::create([
                            'pago_id'   => $pago->id,
                            'pago_bs'   => $montoTotal,
                            'tipo_pago' => $pagoData['tipo_pago'],
                        ]);
                    }
                }

                // Actualizar estado de suscripción si está completamente pagada
                if ($totalPagado >= $montoTotalPlan) {
                    $suscripcion->estado = 'Agotada';
                    $suscripcion->save();
                }
            }

            DB::commit();

            // Redirigir al detalle con los IDs de pagos creados para generar recibos
            return redirect()
                ->route('admin.suscripciones.show', $suscripcion->id)
                ->with('recibos_nuevos', isset($pagosCreados) ? $pagosCreados : [])
                ->with('success', 'Suscripción creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la suscripción: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->has('eliminar_sesiones')) {
            foreach ($request->eliminar_sesiones as $sesionId) {
                $sesion = Sesione::find($sesionId);
                if ($sesion && in_array($sesion->estado, ['programada', 'reprogramada'])) {
                    $sesion->delete();
                }
            }
        }

        $suscripcion = Suscripcione::with('sesiones')->findOrFail($id);

        $serviciosPermitidos = PlanesServicios::where('plane_id', $suscripcion->plane_id)->pluck('id')->toArray();

        $request->validate([
            'sesiones' => 'required|array',
            'sesiones.*.id' => 'nullable|exists:sesiones,id',
            'sesiones.*.hijo_id' => 'required|exists:hijos,id',
            'sesiones.*.planes_servicio_id' => 'required|in:' . implode(',', $serviciosPermitidos),
            'sesiones.*.fecha' => 'required|date',
            'sesiones.*.hora_inicio' => 'required|date_format:H:i',
            'sesiones.*.hora_fin' => 'required|date_format:H:i|after:sesiones.*.hora_inicio',
            'sesiones.*.trabajadore_id' => 'nullable|exists:trabajadores,id',
        ]);

        DB::beginTransaction();
        try {
            $idsEnviados = collect($request->sesiones)->pluck('id')->filter();
            $idsExistentes = $suscripcion->sesiones->pluck('id');

            $idsAEliminar = $idsExistentes->diff($idsEnviados);
            foreach ($idsAEliminar as $idEliminar) {
                $sesion = Sesione::find($idEliminar);
                if ($sesion && in_array($sesion->estado, ['programada', 'reprogramada'])) {
                    $sesion->delete();
                }
                // Non-editable sessions not in sent list are silently kept
            }

            foreach ($request->sesiones as $index => $sesionData) {
                // Skip non-editable sessions sent with their IDs (they don't need updating)
                if (isset($sesionData['id']) && $sesionExistente = Sesione::find($sesionData['id'])) {
                    if (!in_array($sesionExistente->estado, ['programada', 'reprogramada'])) {
                        continue;
                    }
                }

                $this->verificarDisponibilidadHorario(
                    $sesionData['fecha'],
                    $sesionData['hora_inicio'],
                    $sesionData['hora_fin'],
                    $sesionData['trabajadore_id'],
                    $sesionData['id'] ?? null
                );

                $dataSesion = [
                    'suscripcione_id' => $suscripcion->id,
                    'hijo_id' => $sesionData['hijo_id'],
                    'n_sesion' => $index + 1,
                    'planes_servicio_id' => $sesionData['planes_servicio_id'],
                    'fecha' => $sesionData['fecha'],
                    'hora_inicio' => $sesionData['hora_inicio'],
                    'hora_fin' => $sesionData['hora_fin'],
                    'trabajadore_id' => $sesionData['trabajadore_id'] ?? null,
                ];

                if (isset($sesionData['id']) && $sesionExistente = Sesione::find($sesionData['id'])) {
                    $sesionExistente->update($dataSesion);
                } else {
                    Sesione::create($dataSesion);
                }
            }

            $suscripcion->sesiones_restantes = $suscripcion->sesiones()->whereNotIn('estado', ['realizada', 'cancelada'])->count();
            $suscripcion->save();

            DB::commit();
            return redirect()->route('admin.suscripciones.index')->with('success', 'Suscripción actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $suscripcion = Suscripcione::with([
            'cliente.persona',
            'cliente.parentescos.hijo.persona',
            'plan',
            'sesiones' => function ($q) {
                $q->with([
                    'hijo.persona',
                    'trabajador.persona',
                    'planServicio.servicio',
                    'reprogramaciones',
                    'sesionOriginal',
                ])->orderBy('n_sesion', 'asc');
            }
        ])->findOrFail($id);

        $hijos = Hijo::whereHas('parentescos', function ($q) use ($suscripcion) {
            $q->where('cliente_id', $suscripcion->cliente_id);
        })->with('persona')->get();

        $trabajadores = Trabajadore::with('persona')->get();

        $serviciosDelPlan = PlanesServicios::with('servicio')
            ->where('plane_id', $suscripcion->plane_id)
            ->get();

        return view('admin.suscripciones.edit', compact(
            'suscripcion',
            'hijos',
            'trabajadores',
            'serviciosDelPlan'
        ));
    }

    private function verificarDisponibilidadHorario($fecha, $horaInicio, $horaFin, $trabajadoreId, $sesionIdExcluir = null)
    {
        $query = Sesione::whereDate('fecha', $fecha)
            ->where('trabajadore_id', $trabajadoreId)   // ← nuevo filtro
            ->where('hora_inicio', '<', $horaFin)
            ->where('hora_fin', '>', $horaInicio)
            ->whereNotIn('estado', ['reprogramada', 'cancelada']);

        if ($sesionIdExcluir) {
            $query->where('id', '!=', $sesionIdExcluir);
        }

        if ($query->exists()) {
            throw new \Exception("El horario de $horaInicio a $horaFin del $fecha ya está ocupado para este trabajador.");
        }
    }

    public function destroy($id)
    {
        try {
            $suscripcion = Suscripcione::findOrFail($id);
            // Eliminar sesiones relacionadas (onDelete cascade si está configurado, sino hacerlo manual)
            $suscripcion->sesiones()->delete();
            $suscripcion->delete();
            return redirect()->route('admin.suscripciones.index')->with('success', 'Suscripción eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    // Métodos AJAX para búsqueda
    public function buscarCliente(Request $request)
    {
        $term = $request->get('q');
        $clientes = Cliente::with('persona')
            ->whereHas('persona', function ($q) use ($term) {
                $q->where('carnet', 'like', "%$term%")
                    ->orWhere('nombres', 'like', "%$term%")
                    ->orWhere('apellido_paterno', 'like', "%$term%")
                    ->orWhere('apellido_materno', 'like', "%$term%")
                    ->orWhere('correo', 'like', "%$term%");
            })
            ->limit(10)
            ->get()
            ->map(function ($cliente) {
                return [
                    'id' => $cliente->id,
                    'text' => $cliente->persona->carnet . ' - ' . $cliente->persona->nombres . ' ' . $cliente->persona->apellido_paterno,
                    'persona' => $cliente->persona,
                ];
            });

        return response()->json($clientes);
    }

    public function buscarHijo(Request $request)
    {
        $term = $request->get('q');
        $hijos = Hijo::with('persona')
            ->whereHas('persona', function ($q) use ($term) {
                $q->where('carnet', 'like', "%$term%")
                    ->orWhere('nombres', 'like', "%$term%")
                    ->orWhere('apellido_paterno', 'like', "%$term%")
                    ->orWhere('apellido_materno', 'like', "%$term%");
            })
            ->limit(10)
            ->get()
            ->map(function ($hijo) {
                return [
                    'id' => $hijo->id,
                    'text' => ($hijo->persona->carnet ?? 'Sin CI') . ' - ' . $hijo->persona->nombres . ' ' . $hijo->persona->apellido_paterno,
                    'persona' => $hijo->persona,
                ];
            });

        return response()->json($hijos);
    }

    public function detalleSesion(Sesione $sesion)
    {
        $sesion->load([
            'suscripcion.cliente.persona',
            'suscripcion.plan.paquete',
            'servicio',
            'hijo.persona',
            'trabajador.persona',
            'planServicio.servicio',
            'sesionOriginal',
            'reprogramaciones'
        ]);

        $reprogramarId = $sesion->reprogramar_id;
        $reprogramarNSesion = null;
        if ($reprogramarId && $sesion->sesionOriginal) {
            $reprogramarNSesion = $sesion->sesionOriginal->n_sesion;
        }

        $tieneReprogramaciones = $sesion->reprogramaciones->count() > 0;
        $reprogramacionId = $tieneReprogramaciones ? $sesion->reprogramaciones->first()->id : null;

        $clientePersona = $sesion->suscripcion->cliente->persona;

        return response()->json([
            'id'                      => $sesion->id,
            'n_sesion'                => $sesion->n_sesion,
            'cliente_nombre'          => $clientePersona->nombres . ' ' . $clientePersona->apellido_paterno,
            'cliente_carnet'          => $clientePersona->carnet,
            'cliente_celular'         => $clientePersona->celular ?? $clientePersona->telefono ?? null,
            'hijo_nombre'             => $sesion->hijo?->persona?->nombres . ' ' . $sesion->hijo?->persona?->apellido_paterno,
            'hijo_carnet'             => $sesion->hijo?->persona?->carnet,
            'paquete'                 => $sesion->suscripcion->plan->paquete->nombre ?? 'N/A',
            'plan'                    => $sesion->suscripcion->plan->nombre_plan,
            'tarifa'                  => $sesion->suscripcion->tarifa ?? 'normal',
            'servicio'                => $sesion->servicio?->nombre ?? ($sesion->planServicio?->servicio?->nombre ?? 'Sin servicio'),
            'trabajador'              => $sesion->trabajador?->persona?->nombres . ' ' . $sesion->trabajador?->persona?->apellido_paterno,
            'trabajadore_id'          => $sesion->trabajadore_id,
            'fecha'                   => $sesion->fecha ? $sesion->fecha->format('d/m/Y') : 'Sin fecha',
            'fecha_raw'               => $sesion->fecha ? $sesion->fecha->format('Y-m-d') : null,
            'hora_inicio'             => $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : null,
            'hora_inicio_raw'         => $sesion->hora_inicio ? \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') : null,
            'hora_fin'                => $sesion->hora_fin ? \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') : null,
            'estado'                  => $sesion->estado,
            'suscripcion_id'          => $sesion->suscripcione_id,
            'informe'                 => $sesion->informe_sesion,
            'asistio_hijo'            => $sesion->asistio_hijo,
            'pago_con_sesion'         => $sesion->planServicio?->servicio?->pago_con_sesion,
            'pago_sin_sesion'         => $sesion->planServicio?->servicio?->pago_sin_sesion,
            'reprogramar_id'          => $reprogramarId,
            'reprogramar_n_sesion'    => $reprogramarNSesion,
            'tiene_reprogramaciones'  => $tieneReprogramaciones,
            'reprogramacion_id'       => $reprogramacionId,
            'puede_reprogramar_directo' => $sesion->estado === 'cancelada' && !$tieneReprogramaciones,
        ]);
    }

    public function sesionesCalendarioJson(Request $request)
    {
        $trabajadorId = $request->get('trabajador_id');

        $cargoFisio = Cargo::where('nombre', 'Fisioterapeuta')->first();
        $idsFisioterapeutas = $cargoFisio
            ? Trabajadore::where('cargo_id', $cargoFisio->id)->pluck('id')->toArray()
            : [];

        $suscripcionId = $request->get('suscripcion_id');

        $query = Sesione::with(['trabajador.persona', 'hijo.persona'])
            ->whereNotNull('fecha')
            ->whereNotNull('hora_inicio')
            ->whereIn('trabajadore_id', $idsFisioterapeutas)
            ->whereNotIn('estado', ['cancelada', 'reprogramada']);

        if ($suscripcionId) {
            $query->where('suscripcione_id', '!=', $suscripcionId);
        }

        if ($trabajadorId && in_array($trabajadorId, $idsFisioterapeutas)) {
            $query->where('trabajadore_id', $trabajadorId);
        }

        $sesiones = $query->orderBy('fecha')->get();

        $eventos = $sesiones->map(function ($sesion) {
            $trabajador = $sesion->trabajador;
            $hijo = $sesion->hijo;
            $nombreTrabajador = $trabajador?->persona
                ? $trabajador->persona->nombres . ' ' . $trabajador->persona->apellido_paterno
                : 'Sin asignar';
            $nombreHijo = $hijo?->persona
                ? $hijo->persona->nombres . ' ' . $hijo->persona->apellido_paterno
                : 'Sin hijo';

            return [
                'id'              => 'occ_' . $sesion->id,
                'title'           => $nombreTrabajador . ': ' . $nombreHijo,
                'start'           => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_inicio,
                'end'             => $sesion->fecha->format('Y-m-d') . 'T' . $sesion->hora_fin,
                'backgroundColor' => '#dc3545',
                'borderColor'     => '#dc3545',
                'textColor'       => '#fff',
                'extendedProps'   => [
                    'tipo'          => 'ocupado',
                    'trabajador_id' => $sesion->trabajadore_id,
                    'estado'        => $sesion->estado,
                ],
            ];
        });

        return response()->json($eventos);
    }

    public function horasOcupadas(Request $request)
    {
        $request->validate([
            'fecha'         => 'required|date',
            'trabajador_id' => 'nullable|exists:trabajadores,id',
            'sesion_id'     => 'nullable|integer|exists:sesiones,id',
        ]);

        $fecha        = $request->fecha;
        $trabajadorId = $request->trabajador_id;
        $sesionId     = $request->sesion_id;

        // Obtener todas las sesiones existentes para esa fecha y trabajador (excluyendo la actual si se edita)
        $sesiones = Sesione::whereDate('fecha', $fecha)
            ->where('trabajadore_id', $trabajadorId)
            ->whereNotIn('estado', ['reprogramada', 'cancelada'])
            ->when($sesionId, fn($q) => $q->where('id', '!=', $sesionId))
            ->get(['hora_inicio', 'hora_fin']);

        // Generar todas las posibles horas de inicio cada 30 min entre 08:00 y 20:00
        $horasPosibles = [];
        $inicio = strtotime('08:00');
        $fin    = strtotime('20:00');
        while ($inicio <= $fin) {
            $horasPosibles[] = date('H:i', $inicio);
            $inicio = strtotime('+30 minutes', $inicio);
        }

        // Filtrar las horas que NO están disponibles
        $horasNoDisponibles = [];
        foreach ($horasPosibles as $hora) {
            $horaInicioNueva = strtotime($hora);
            $horaFinNueva    = strtotime($hora . ' +1 hour');

            foreach ($sesiones as $sesion) {
                $inicioExistente = strtotime($sesion->hora_inicio);
                $finExistente    = strtotime($sesion->hora_fin);

                // Si se superponen, marcar como no disponible
                if ($horaInicioNueva < $finExistente && $inicioExistente < $horaFinNueva) {
                    $horasNoDisponibles[] = $hora;
                    break; // no need to check other sessions
                }
            }
        }

        return response()->json(['horas_no_disponibles' => $horasNoDisponibles]);
    }

    /**
     * Mezcla dos colores hexadecimales con un porcentaje dado.
     *
     * @param string $color1 Color base (hex, con o sin #)
     * @param string $color2 Color a mezclar (hex)
     * @param float $porcentaje Proporción del segundo color (0 a 1)
     * @return string Color resultante en hex
     */
    private function mezclarColores($color1, $color2, $porcentaje = 0.5)
    {
        // Asegurar formato sin #
        $hex1 = ltrim($color1, '#');
        $hex2 = ltrim($color2, '#');

        // Convertir a RGB
        $r1 = hexdec(substr($hex1, 0, 2));
        $g1 = hexdec(substr($hex1, 2, 2));
        $b1 = hexdec(substr($hex1, 4, 2));

        $r2 = hexdec(substr($hex2, 0, 2));
        $g2 = hexdec(substr($hex2, 2, 2));
        $b2 = hexdec(substr($hex2, 4, 2));

        // Mezcla lineal
        $r = round($r1 * (1 - $porcentaje) + $r2 * $porcentaje);
        $g = round($g1 * (1 - $porcentaje) + $g2 * $porcentaje);
        $b = round($b1 * (1 - $porcentaje) + $b2 * $porcentaje);

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }


    // Método para mostrar formulario de nuevo pago
    public function createPago($id)
    {
        $suscripcion = Suscripcione::with([
            'cliente.persona',
            'plan.paquete',
            'pagos.trabajador.persona',
            'sesiones.hijo.persona'
        ])->findOrFail($id);

        // ── Precio efectivo según tarifa ──
        $tarifa = $suscripcion->tarifa ?? 'normal';
        $precioEfectivo = (float) ($suscripcion->monto_total
            ?? ($tarifa === 'especial' && !is_null($suscripcion->plan->precio_especial)
                ? $suscripcion->plan->precio_especial
                : ($suscripcion->plan->precio ?? 0)));

        // Calcular totales
        $totalPagado    = (float) $suscripcion->pagos->sum('pago_bs');
        $totalDescuento = (float) $suscripcion->pagos->sum('descuento_bs');
        $saldoPendiente = max(0, $precioEfectivo - $totalPagado - $totalDescuento);

        $trabajadores = Trabajadore::with('persona')->get();

        return view('admin.suscripciones.pagos.create', compact(
            'suscripcion',
            'tarifa',
            'precioEfectivo',
            'totalPagado',
            'totalDescuento',
            'saldoPendiente',
            'trabajadores'
        ));
    }

    // Método para guardar nuevo pago
    // Método para guardar nuevo pago
    public function storePago(Request $request, $id)
    {
        $request->validate([
            'tipo_pago' => 'required|in:efectivo,qr,parcial',
            'monto' => 'nullable|numeric|min:0.01',
            'monto_efectivo' => 'nullable|numeric|min:0',
            'monto_qr' => 'nullable|numeric|min:0',
            'fecha_pago' => 'required|date',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        $suscripcion = Suscripcione::with(['plan', 'pagos'])->findOrFail($id);

        // Precio efectivo según tarifa (igual que createPago)
        $tarifa = $suscripcion->tarifa ?? 'normal';
        $precioEfectivo = (float) ($suscripcion->monto_total
            ?? ($tarifa === 'especial' && !is_null($suscripcion->plan->precio_especial)
                ? $suscripcion->plan->precio_especial
                : ($suscripcion->plan->precio ?? 0)));

        // Calcular saldo actual contra el precio efectivo
        $totalPagado    = (float) $suscripcion->pagos->sum('pago_bs');
        $totalDescuento = (float) $suscripcion->pagos->sum('descuento_bs');
        $saldoPendiente = max(0, $precioEfectivo - $totalPagado - $totalDescuento);

        $isAjax = $request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        // Calcular monto total según tipo de pago
        $montoTotal = 0;
        if ($request->tipo_pago === 'parcial') {
            $montoEfectivo = floatval($request->monto_efectivo ?? 0);
            $montoQr = floatval($request->monto_qr ?? 0);
            $montoTotal = $montoEfectivo + $montoQr;

            // Validar que al menos uno de los montos sea mayor a 0
            if ($montoEfectivo <= 0 && $montoQr <= 0) {
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => 'Debe ingresar al menos un monto mayor a 0.'], 422);
                }
                return back()->withErrors(['monto_efectivo' => 'Debe ingresar al menos un monto mayor a 0.']);
            }
        } else {
            $montoTotal = floatval($request->monto ?? 0);
        }

        // Validar que el monto no exceda el saldo pendiente
        if ($montoTotal > $saldoPendiente) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => "El monto ({$montoTotal}) no puede exceder el saldo pendiente de {$saldoPendiente} Bs."], 422);
            }
            return back()->withErrors(['monto' => "El monto no puede exceder el saldo pendiente de {$saldoPendiente} Bs."]);
        }

        // Validar que el monto sea mayor a 0
        if ($montoTotal <= 0) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'El monto debe ser mayor a 0.'], 422);
            }
            return back()->withErrors(['monto' => 'El monto debe ser mayor a 0.']);
        }

        DB::beginTransaction();
        try {
            // Generar número de recibo
            $recibo = IdGenerator::generate([
                'table' => 'pagos',
                'field' => 'recibo',
                'length' => 10,
                'prefix' => 'DINO-',
                'reset_on_prefix_change' => true
            ]);

            // Crear el pago principal
            $pago = Pago::create([
                'recibo'           => $recibo,
                'pago_bs'          => $montoTotal,
                'descuento_bs'     => $request->descuento ?? 0,
                'fecha_pago'       => $request->fecha_pago,
                'tipo_pago'        => $request->tipo_pago,
                'suscripcione_id'  => $suscripcion->id,
                'trabajadore_id'   => Trabajadore::where('persona_id', auth()->user()?->persona_id)->value('id'),
            ]);

            // Crear detalles del pago según tipo
            if ($request->tipo_pago === 'parcial') {
                // Crear detalle para efectivo (si hay monto)
                if (($request->monto_efectivo ?? 0) > 0) {
                    Detalle::create([
                        'pago_id'   => $pago->id,
                        'pago_bs'   => $request->monto_efectivo,
                        'tipo_pago' => 'efectivo',
                    ]);
                }

                // Crear detalle para QR (si hay monto)
                if (($request->monto_qr ?? 0) > 0) {
                    Detalle::create([
                        'pago_id'   => $pago->id,
                        'pago_bs'   => $request->monto_qr,
                        'tipo_pago' => 'qr',
                    ]);
                }
            } else {
                // Crear detalle único para efectivo o qr
                Detalle::create([
                    'pago_id'   => $pago->id,
                    'pago_bs'   => $montoTotal,
                    'tipo_pago' => $request->tipo_pago,
                ]);
            }

            // ── Cálculos finales ──
            $nuevoTotalPagado   = $totalPagado + $montoTotal;
            $descuentoExtra     = floatval($request->descuento ?? 0);
            $nuevoTotalDescuento = $totalDescuento + $descuentoExtra;
            $saldoRestante      = max(0, $precioEfectivo - $nuevoTotalPagado - $nuevoTotalDescuento);
            $pagoCompletado     = $saldoRestante <= 0.009 && $precioEfectivo > 0;

            if ($pagoCompletado) {
                $suscripcion->estado = 'Agotada';
                $suscripcion->save();
            }

            DB::commit();

            $msgBase = 'Pago registrado correctamente. Recibo: ' . $recibo;
            $msgCompleto = $pagoCompletado
                ? ('¡Suscripción totalmente pagada! ' . $msgBase)
                : $msgBase;

            // Verificar si es una solicitud AJAX
            if ($isAjax) {
                return response()->json([
                    'success'         => true,
                    'message'         => $msgCompleto,
                    'recibo'          => $recibo,
                    'recibos'         => [['id' => $pago->id, 'recibo' => $recibo]],
                    'pago_completado' => $pagoCompletado,
                    'tarifa'          => $tarifa,
                    'precio_efectivo' => $precioEfectivo,
                    'total_pagado'    => $nuevoTotalPagado,
                    'total_descuento' => $nuevoTotalDescuento,
                    'saldo_restante'  => $saldoRestante,
                    'redirect'        => route('admin.suscripciones.show', $id),
                ]);
            }

            return redirect()->route('admin.suscripciones.show', $id)
                ->with('success', $msgCompleto)
                ->with('pago_completado', $pagoCompletado)
                ->with('recibos_nuevos', [['id' => $pago->id, 'recibo' => $recibo]]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el pago: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Error al registrar el pago: ' . $e->getMessage()]);
        }
    }

    // Método para mostrar detalle de pagos (ya existente, verificar que esté)
    public function detallePagos($id)
    {
        $suscripcion = Suscripcione::with([
            'pagos.trabajador.persona',
            'pagos.detalles',
            'cliente.persona',
            'plan.paquete'
        ])->findOrFail($id);

        $pagos = $suscripcion->pagos->sortByDesc('fecha_pago');

        $totalPagado = $pagos->sum('pago_bs');
        $totalDescuento = $pagos->sum('descuento_bs');
        $precioTotal = $suscripcion->monto_total
            ?? ($suscripcion->tarifa === 'especial' && $suscripcion->plan && !is_null($suscripcion->plan->precio_especial)
                ? (float) $suscripcion->plan->precio_especial
                : (float) ($suscripcion->plan->precio ?? 0));
        $saldoPendiente = max(0, $precioTotal - $totalPagado - $totalDescuento);

        return response()->json([
            'success' => true,
            'html' => view('admin.suscripciones.partials.modal-pagos', compact('suscripcion', 'pagos', 'totalPagado', 'totalDescuento', 'precioTotal', 'saldoPendiente'))->render(),
            'data' => [
                'total_pagado' => $totalPagado,
                'total_descuento' => $totalDescuento,
                'saldo_pendiente' => $saldoPendiente,
                'precio_plan' => $precioTotal
            ]
        ]);
    }

    // Método para generar recibo PDF (ya existente, verificar que esté)
    public function generarReciboPdf($pagoId)
    {
        try {
            $pago = Pago::with([
                'suscripcion.cliente.persona',
                'suscripcion.plan.paquete',
                'suscripcion.sesiones.hijo.persona',
                'trabajador.persona',
                'detalles'
            ])->findOrFail($pagoId);

            $suscripcion = $pago->suscripcion;
            $cliente = $suscripcion->cliente->persona;
            $plan = $suscripcion->plan;
            $trabajador = $pago->trabajador?->persona;

            $totalPagado = $suscripcion->pagos->sum('pago_bs');
            $totalDescuento = $suscripcion->pagos->sum('descuento_bs');
            $precioTotal = $suscripcion->monto_total
                ?? ($suscripcion->tarifa === 'especial' && $plan && !is_null($plan->precio_especial)
                    ? (float) $plan->precio_especial
                    : (float) ($plan->precio ?? 0));
            $saldoPendiente = max(0, $precioTotal - $totalPagado - $totalDescuento);

            $data = [
                'pago' => $pago,
                'suscripcion' => $suscripcion,
                'cliente' => $cliente,
                'plan' => $plan,
                'trabajador' => $trabajador,
                'totalPagado' => $totalPagado,
                'totalDescuento' => $totalDescuento,
                'saldoPendiente' => $saldoPendiente,
                'fechaEmision' => now(),
                'numeroRecibo' => $pago->recibo,
            ];

            $pdf = Pdf::loadView('admin.suscripciones.pdf.recibo-pago', $data);

            $pdf->setPaper('letter', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', false);
            $pdf->setOption('defaultFont', 'DejaVu Sans');

            $nombreArchivo = 'RECIBO-' . $pago->recibo . '-' . date('Y-m-d') . '.pdf';

            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al generar PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el recibo: ' . $e->getMessage());
        }
    }

    public function reporteRecibos(Request $request)
    {
        // === Filtro por mes / gestión ===
        // Prioridad: mes+gestion > fecha_inicio/fecha_fin > mes actual
        $mesSeleccionado     = (int) ($request->input('mes')     ?? now()->month);
        $gestionSeleccionada = (int) ($request->input('gestion') ?? now()->year);

        if ($request->filled('mes') && $request->filled('gestion')) {
            $base = \Carbon\Carbon::create($gestionSeleccionada, $mesSeleccionado, 1);
            $fechaInicio = $base->copy()->startOfMonth()->format('Y-m-d');
            $fechaFin    = $base->copy()->endOfMonth()->format('Y-m-d');
        } elseif ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            // Compatibilidad con links legacy (PDF download, etc.)
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin    = $request->input('fecha_fin');
        } else {
            $base = \Carbon\Carbon::create($gestionSeleccionada, $mesSeleccionado, 1);
            $fechaInicio = $base->copy()->startOfMonth()->format('Y-m-d');
            $fechaFin    = $base->copy()->endOfMonth()->format('Y-m-d');
        }

        $anioActual = (int) now()->year;
        $gestionesDisponibles = range($anioActual, $anioActual - 5);

        $tipoPago    = $request->input('tipo_pago');

        // === Consulta de pagos (sin paginar) ===
        $pagosQuery = Pago::with([
            'suscripcion.cliente.persona',
            'suscripcion.plan',
            'trabajador.persona',
        ])->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);

        if ($tipoPago) {
            $pagosQuery->where('tipo_pago', $tipoPago);
        }

        $pagosAll = $pagosQuery->orderBy('fecha_pago', 'desc')->get();

        // === Consulta de ventas ===
        $ventasQuery = Venta::with([
            'cliente.persona',
            'trabajador.persona',
            'detalles',
        ])->whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);

        if ($tipoPago) {
            $ventasQuery->where('tipo_pago', $tipoPago);
        }

        $ventasAll = $ventasQuery->orderBy('fecha_venta', 'desc')->get();

        // === Totales combinados ===
        $totalIngresos   = $pagosAll->sum('pago_bs') + $ventasAll->sum('total_bs');
        $totalDescuentos = $pagosAll->sum('descuento_bs') + $ventasAll->sum('descuento_bs');
        $totalRegistros  = $pagosAll->count() + $ventasAll->count();

        // === Totales efectivo/QR combinados ===
        $detallesPagos = Detalle::join('pagos', 'detalles.pago_id', '=', 'pagos.id')
            ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('pagos.tipo_pago', $tipoPago))
            ->selectRaw('detalles.tipo_pago, SUM(detalles.pago_bs) as total')
            ->groupBy('detalles.tipo_pago')
            ->pluck('total', 'tipo_pago')
            ->toArray();

        $detallesVentas = VentaPagoDetalle::join('ventas', 'venta_pago_detalles.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('ventas.tipo_pago', $tipoPago))
            ->selectRaw('venta_pago_detalles.tipo_pago, SUM(venta_pago_detalles.pago_bs) as total')
            ->groupBy('venta_pago_detalles.tipo_pago')
            ->pluck('total', 'tipo_pago')
            ->toArray();

        $efectivoTotal = ($detallesPagos['efectivo'] ?? 0) + ($detallesVentas['efectivo'] ?? 0);
        $qrTotal       = ($detallesPagos['qr'] ?? 0) + ($detallesVentas['qr'] ?? 0);

        // === Resumen por trabajador (pagos + ventas) ===
        $trabajadoresResumen = [];

        $pagosTrab = Detalle::join('pagos', 'detalles.pago_id', '=', 'pagos.id')
            ->leftJoin('trabajadores', 'pagos.trabajadore_id', '=', 'trabajadores.id')
            ->leftJoin('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('pagos.tipo_pago', $tipoPago))
            ->selectRaw('COALESCE(trabajadores.id, 0) as trabajador_id, COALESCE(personas.nombres, "Sin asignar") as nombre_trabajador, detalles.tipo_pago, SUM(detalles.pago_bs) as total')
            ->groupBy('trabajador_id', 'nombre_trabajador', 'detalles.tipo_pago')
            ->get();

        foreach ($pagosTrab as $item) {
            $tid = $item->trabajador_id;
            if (!isset($trabajadoresResumen[$tid])) {
                $trabajadoresResumen[$tid] = ['nombre' => $item->nombre_trabajador, 'efectivo' => 0, 'qr' => 0];
            }
            $trabajadoresResumen[$tid][$item->tipo_pago] = ($trabajadoresResumen[$tid][$item->tipo_pago] ?? 0) + $item->total;
        }

        $ventasTrab = VentaPagoDetalle::join('ventas', 'venta_pago_detalles.venta_id', '=', 'ventas.id')
            ->leftJoin('trabajadores', 'ventas.trabajadore_id', '=', 'trabajadores.id')
            ->leftJoin('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('ventas.tipo_pago', $tipoPago))
            ->selectRaw('COALESCE(trabajadores.id, 0) as trabajador_id, COALESCE(personas.nombres, "Sin asignar") as nombre_trabajador, venta_pago_detalles.tipo_pago, SUM(venta_pago_detalles.pago_bs) as total')
            ->groupBy('trabajador_id', 'nombre_trabajador', 'venta_pago_detalles.tipo_pago')
            ->get();

        foreach ($ventasTrab as $item) {
            $tid = $item->trabajador_id;
            if (!isset($trabajadoresResumen[$tid])) {
                $trabajadoresResumen[$tid] = ['nombre' => $item->nombre_trabajador, 'efectivo' => 0, 'qr' => 0];
            }
            $trabajadoresResumen[$tid][$item->tipo_pago] = ($trabajadoresResumen[$tid][$item->tipo_pago] ?? 0) + $item->total;
        }

        $trabajadoresResumen = array_values($trabajadoresResumen);

        // === Ingresos por día combinados (gráfico) ===
        $pagosDay  = Pago::selectRaw('DATE(fecha_pago) as fecha, SUM(pago_bs) as total')
            ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('tipo_pago', $tipoPago))
            ->groupBy('fecha')->pluck('total', 'fecha')->toArray();

        $ventasDay = Venta::selectRaw('DATE(fecha_venta) as fecha, SUM(total_bs) as total')
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->when($tipoPago, fn($q) => $q->where('tipo_pago', $tipoPago))
            ->groupBy('fecha')->pluck('total', 'fecha')->toArray();

        $allDates = array_unique(array_merge(array_keys($pagosDay), array_keys($ventasDay)));
        sort($allDates);
        $ingresosPorDia = collect($allDates)->map(fn($d) => [
            'fecha' => $d,
            'total' => ($pagosDay[$d] ?? 0) + ($ventasDay[$d] ?? 0),
        ]);
        // === Construir colección unificada y paginar ===
        $registros = collect();

        foreach ($pagosAll as $pago) {
            $registros->push([
                'origen'        => 'pago',
                'recibo'        => $pago->recibo,
                'fecha'         => $pago->fecha_pago,
                'cliente_id'    => $pago->suscripcion->cliente->id,
                'cliente'       => $pago->suscripcion->cliente->persona->nombres . ' ' . $pago->suscripcion->cliente->persona->apellido_paterno,
                'detalle'       => $pago->suscripcion->plan->nombre_plan,
                'tarifa'        => $pago->suscripcion->tarifa ?? 'normal',
                'tipo_pago'     => $pago->tipo_pago,
                'monto'         => $pago->pago_bs,
                'descuento'     => $pago->descuento_bs,
                'trabajador_id' => $pago->trabajador?->id,
                'trabajador'    => $pago->trabajador ? $pago->trabajador->persona->nombres . ' ' . $pago->trabajador->persona->apellido_paterno : null,
                'pdf_url'       => route('admin.suscripciones.reciboPdf', $pago->id),
                'ver_url'       => route('admin.suscripciones.show', $pago->suscripcion->id),
            ]);
        }

        foreach ($ventasAll as $venta) {
            $nProductos = $venta->detalles->count();
            $registros->push([
                'origen'        => 'venta',
                'recibo'        => $venta->recibo,
                'fecha'         => $venta->fecha_venta,
                'cliente_id'    => $venta->cliente?->id,
                'cliente'       => $venta->cliente ? $venta->cliente->persona->nombres . ' ' . $venta->cliente->persona->apellido_paterno : 'Sin cliente',
                'detalle'       => $nProductos . ' producto' . ($nProductos != 1 ? 's' : ''),
                'tarifa'        => null,
                'tipo_pago'     => $venta->tipo_pago,
                'monto'         => $venta->total_bs,
                'descuento'     => $venta->descuento_bs,
                'trabajador_id' => $venta->trabajador?->id,
                'trabajador'    => $venta->trabajador ? $venta->trabajador->persona->nombres . ' ' . $venta->trabajador->persona->apellido_paterno : null,
                'pdf_url'       => route('admin.ventas.reciboPdf', $venta->id),
                'ver_url'       => null,
            ]);
        }

        $registros = $registros->sortByDesc('recibo')->values();

        $perPage     = 20;
        $currentPage = (int) $request->input('page', 1);
        $paginatedItems = $registros->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $registros = new LengthAwarePaginator($paginatedItems, $registros->count(), $perPage, $currentPage, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        return view('admin.recibos.reporte', compact(
            'registros',
            'fechaInicio',
            'fechaFin',
            'mesSeleccionado',
            'gestionSeleccionada',
            'gestionesDisponibles',
            'totalIngresos',
            'totalDescuentos',
            'totalRegistros',
            'ingresosPorDia',
            'efectivoTotal',
            'qrTotal',
            'trabajadoresResumen',
        ));
    }

    public function descargarRecibosPdf(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        $pagos = Pago::with([
            'suscripcion.cliente.persona',
            'suscripcion.plan',
            'trabajador.persona'
        ])
            ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_pago', 'desc')
            ->get();

        $totalIngresos = $pagos->sum('pago_bs');

        $pdf = Pdf::loadView('admin.recibos.pdf.reporte', compact(
            'pagos',
            'fechaInicio',
            'fechaFin',
            'totalIngresos'
        ));

        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('Reporte_Recibos_' . $fechaInicio . '_al_' . $fechaFin . '.pdf');
    }

    public function reporteTrabajadoresPdf(Request $request)
    {
        // Fechas por defecto: último mes
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // === Totales por tipo de pago REAL (efectivo vs QR) usando detalles ===
        $detallesQuery = Detalle::join('pagos', 'detalles.pago_id', '=', 'pagos.id')
            ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin]);

        if ($request->filled('tipo_pago')) {
            $detallesQuery->where('pagos.tipo_pago', $request->tipo_pago);
        }
        if ($request->filled('suscripcion_id')) {
            $detallesQuery->where('pagos.suscripcione_id', $request->suscripcion_id);
        }

        $totalesPorTipoDetalle = $detallesQuery
            ->selectRaw('detalles.tipo_pago, SUM(detalles.pago_bs) as total')
            ->groupBy('detalles.tipo_pago')
            ->pluck('total', 'tipo_pago')
            ->toArray();

        $efectivoTotal = $totalesPorTipoDetalle['efectivo'] ?? 0;
        $qrTotal = $totalesPorTipoDetalle['qr'] ?? 0;

        // === Totales por trabajador (desglosado por tipo de pago) ===
        $trabajadoresQuery = Detalle::join('pagos', 'detalles.pago_id', '=', 'pagos.id')
            ->leftJoin('trabajadores', 'pagos.trabajadore_id', '=', 'trabajadores.id')
            ->leftJoin('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin]);

        if ($request->filled('tipo_pago')) {
            $trabajadoresQuery->where('pagos.tipo_pago', $request->tipo_pago);
        }
        if ($request->filled('suscripcion_id')) {
            $trabajadoresQuery->where('pagos.suscripcione_id', $request->suscripcion_id);
        }

        $trabajadoresTotales = $trabajadoresQuery
            ->selectRaw('
            COALESCE(trabajadores.id, 0) as trabajador_id,
            COALESCE(personas.nombres, "Sin asignar") as nombre_trabajador,
            detalles.tipo_pago,
            SUM(detalles.pago_bs) as total
        ')
            ->groupBy('trabajador_id', 'nombre_trabajador', 'detalles.tipo_pago')
            ->get();

        // Reorganizar en un array asociativo
        $trabajadoresResumen = [];
        foreach ($trabajadoresTotales as $item) {
            $tid = $item->trabajador_id;
            if (!isset($trabajadoresResumen[$tid])) {
                $trabajadoresResumen[$tid] = [
                    'nombre'    => $item->nombre_trabajador,
                    'efectivo'  => 0,
                    'qr'        => 0,
                ];
            }
            $trabajadoresResumen[$tid][$item->tipo_pago] = $item->total;
        }
        $trabajadoresResumen = array_values($trabajadoresResumen);

        // Generar PDF
        $pdf = Pdf::loadView('admin.recibos.pdf.reporte-trabajadores', compact(
            'fechaInicio',
            'fechaFin',
            'efectivoTotal',
            'qrTotal',
            'trabajadoresResumen'
        ));

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', false);
        $pdf->setOption('defaultFont', 'DejaVu Sans');

        $nombreArchivo = 'Reporte_Trabajadores_' . $fechaInicio . '_al_' . $fechaFin . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    public function cobrosIndex(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->cobrosJson($request);
        }

        return view('admin.cobros.index');
    }

    private function cobrosJson(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $with = [
            'cliente.persona',
            'plan.paquete',
            'pagos',
            'sesiones.hijo.persona',
            'sesiones.planServicio.servicio',
            'sesiones.servicio',
            'sesiones.ventas.detalles.producto',
            'sesiones.ventas.pagoDetalles',
            'sesiones.ventas.trabajador.persona',
        ];

        $personas = Persona::where('nombres', 'LIKE', "%{$q}%")
            ->orWhere('apellido_paterno', 'LIKE', "%{$q}%")
            ->orWhere('apellido_materno', 'LIKE', "%{$q}%")
            ->orWhere('carnet', 'LIKE', "%{$q}%")
            ->get();

        $clienteIds = $personas->map(fn($p) => $p->cliente?->id)->filter()->values();
        $hijoIds    = $personas->map(fn($p) => $p->hijo?->id)->filter()->values();

        $porCliente = Suscripcione::with($with)->whereIn('cliente_id', $clienteIds)->get();
        $porHijo    = Suscripcione::with($with)
            ->whereHas('sesiones', fn($q2) => $q2->whereIn('hijo_id', $hijoIds))
            ->get();

        $suscripciones = $porCliente->merge($porHijo)->unique('id');

        // Agrupar por cliente
        $resultado = $suscripciones->groupBy('cliente_id')->map(function ($susList) {
            $cliente = $susList->first()->cliente;

            return [
                'id'             => $cliente->id,
                'nombre'         => trim(($cliente->persona->nombres ?? '') . ' ' . ($cliente->persona->apellido_paterno ?? '') . ' ' . ($cliente->persona->apellido_materno ?? '')),
                'carnet'         => $cliente->persona->carnet ?? null,
                'celular'        => $cliente->persona->celular ?? null,
                'color'          => $cliente->color ?? '#1a2d4c',
                'seguimiento_url'=> route('admin.clientes.seguimiento', $cliente->id),
                'suscripciones'  => $susList->sortByDesc(fn($s) => $s->fecha_registro)->map(function ($sus) {
                    $sesiones    = $sus->sesiones;
                    $totalPlan   = $sus->plan->cantidad_sesiones ?? 0;
                    $realizadas  = $sesiones->where('estado', 'realizada')->count();
                    $canceladas  = $sesiones->where('estado', 'cancelada')->count();
                    $asistidas   = $sesiones->where('estado', 'realizada')->where('asistio_hijo', true)->count();
                    $noAsist     = $sesiones->where('estado', 'realizada')->where('asistio_hijo', false)->count();
                    $porRealizar = max(0, $totalPlan - $realizadas);

                    // Precio efectivo: snapshot si existe, o precio_especial cuando aplica, sino precio normal del plan
                    $tarifa = $sus->tarifa ?? 'normal';
                    $precioPlan = (float) ($sus->monto_total
                        ?? ($tarifa === 'especial' && !is_null($sus->plan->precio_especial)
                            ? $sus->plan->precio_especial
                            : ($sus->plan->precio ?? 0)));

                    $totalPagado     = (float) $sus->pagos->sum('pago_bs');
                    $totalDescuento  = (float) $sus->pagos->sum('descuento_bs');
                    // Saldo pendiente = precio efectivo - pagado - descuentos aplicados
                    $pendiente       = max(0, $precioPlan - $totalPagado - $totalDescuento);

                    $servicios = $sesiones->map(fn($s) =>
                        $s->planServicio->servicio->nombre ?? $s->servicio->nombre ?? null
                    )->filter()->unique()->values()->all();

                    $hijos = $sesiones->map(fn($s) =>
                        trim(($s->hijo->persona->nombres ?? '') . ' ' . ($s->hijo->persona->apellido_paterno ?? ''))
                    )->filter()->unique()->values()->all();

                    $pagosDetalle = $sus->pagos->sortByDesc('fecha_pago')->map(fn($p) => [
                        'id'           => $p->id,
                        'recibo'       => $p->recibo,
                        'pago_bs'      => (float) $p->pago_bs,
                        'descuento_bs' => (float) ($p->descuento_bs ?? 0),
                        'fecha_pago'   => \Carbon\Carbon::parse($p->fecha_pago)->locale('es')->isoFormat('D MMM YYYY'),
                        'tipo_pago'    => $p->tipo_pago ?? null,
                        'recibo_url'   => route('admin.suscripciones.reciboPdf', $p->id),
                    ])->values()->all();

                    $ventasDetalle = $sus->sesiones->flatMap(fn($s) => $s->ventas)
                        ->sortByDesc('fecha_venta')
                        ->map(fn($v) => [
                            'id'           => $v->id,
                            'recibo'       => $v->recibo,
                            'fecha_venta'  => \Carbon\Carbon::parse($v->fecha_venta)->locale('es')->isoFormat('D MMM YYYY'),
                            'tipo_pago'    => $v->tipo_pago,
                            'total_bs'     => (float) $v->total_bs,
                            'descuento_bs' => (float) ($v->descuento_bs ?? 0),
                            'n_sesion'     => $v->sesion->n_sesion ?? null,
                            'trabajador'   => $v->trabajador
                                ? trim($v->trabajador->persona->nombres . ' ' . $v->trabajador->persona->apellido_paterno)
                                : null,
                            'pdf_url'      => route('admin.ventas.reciboPdf', $v->id),
                            'detalles'     => $v->detalles->map(fn($d) => [
                                'nombre'          => $d->producto->nombre ?? '-',
                                'marca'           => $d->producto->marca ?? null,
                                'cantidad'        => $d->cantidad,
                                'precio_unitario' => (float) $d->precio_unitario,
                                'subtotal'        => (float) $d->subtotal,
                            ])->values()->all(),
                            'pago_detalles' => $v->pagoDetalles->map(fn($pd) => [
                                'tipo_pago' => $pd->tipo_pago,
                                'pago_bs'   => (float) $pd->pago_bs,
                            ])->values()->all(),
                        ])->values()->all();

                    return [
                        'id'                => $sus->id,
                        'plan'              => $sus->plan->nombre_plan ?? 'Sin plan',
                        'paquete'           => $sus->plan->paquete->nombre ?? null,
                        'tarifa'            => $tarifa,
                        'estado'            => $sus->estado ?? 'activa',
                        'fecha_registro'    => \Carbon\Carbon::parse($sus->fecha_registro)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
                        'fecha_registro_ts' => \Carbon\Carbon::parse($sus->fecha_registro)->timestamp,
                        'url'               => route('admin.suscripciones.show', $sus->id),
                        'pago_url'          => route('admin.suscripciones.pagos.create', $sus->id),
                        'sesiones'          => compact('totalPlan','realizadas','porRealizar','canceladas','asistidas','noAsist'),
                        'pagos'          => [
                            'precio_plan'     => $precioPlan,
                            'total_pagado'    => $totalPagado,
                            'total_descuento' => $totalDescuento,
                            'pendiente'       => $pendiente,
                            'detalle'         => $pagosDetalle,
                        ],
                        'ventas'    => $ventasDetalle,
                        'servicios' => $servicios,
                        'hijos'     => $hijos,
                    ];
                })->values()->all(),
            ];
        })->values();

        return response()->json($resultado);
    }
}
