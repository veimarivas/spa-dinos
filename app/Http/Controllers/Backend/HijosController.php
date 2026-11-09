<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;

class HijosController extends Controller
{
    /**
     * Mostrar seguimiento completo de un niño.
     */
    public function seguimiento($id)
    {
        $hijo = Hijo::with([
            'persona',
            'parentescos.cliente.persona', // para saber quién es el padre/madre
            'sesiones' => function ($q) {
                $q->with([
                    'suscripcion.plan.paquete',
                    'suscripcion.cliente.persona',
                    'suscripcion.pagos',
                    'planServicio.servicio',
                    'servicio',
                    'trabajador.persona'
                ])->orderBy('fecha', 'desc');
            }
        ])->findOrFail($id);

        // Agrupar las sesiones por suscripción
        $sesionesAgrupadas = $hijo->sesiones->groupBy('suscripcione_id')->map(function ($sesiones, $key) {
            $primera = $sesiones->first();
            return (object) [
                'suscripcion' => $primera->suscripcion,
                'sesiones'    => $sesiones,
                'total'       => $sesiones->count(),
            ];
        })->values();

        // Suscripciones activas (en desarrollo) - incluye las que tienen deuda
        $suscripcionesActivas = $sesionesAgrupadas->filter(function($sus) {
            $totalPlan = $sus->suscripcion->plan->cantidad_sesiones ?? 0;
            $sesionesCompletadas = $sus->sesiones->where('estado', 'realizada')->count();
            $susData = $sus->suscripcion;
            $precioPlan = $susData->monto_total ?? ($susData->tarifa === 'especial' && $susData->plan->precio_especial ? $susData->plan->precio_especial : ($susData->plan->precio ?? 0));
            $totalPagado = $susData->pagos->sum('pago_bs');
            $totalDescuento = $susData->pagos->sum('descuento_bs');
            $saldoPendiente = max(0, $precioPlan - $totalPagado - $totalDescuento);
            // Activas si: hay sesiones pendientes O hay deuda
            return $sesionesCompletadas < $totalPlan || $saldoPendiente > 0;
        })->values();

        // Suscripciones concluidas - solo si sesiones completadas Y sin deuda
        $suscripcionesConcluidas = $sesionesAgrupadas->filter(function($sus) {
            $totalPlan = $sus->suscripcion->plan->cantidad_sesiones ?? 0;
            $sesionesCompletadas = $sus->sesiones->where('estado', 'realizada')->count();
            $susData = $sus->suscripcion;
            $precioPlan = $susData->monto_total ?? ($susData->tarifa === 'especial' && $susData->plan->precio_especial ? $susData->plan->precio_especial : ($susData->plan->precio ?? 0));
            $totalPagado = $susData->pagos->sum('pago_bs');
            $totalDescuento = $susData->pagos->sum('descuento_bs');
            $saldoPendiente = max(0, $precioPlan - $totalPagado - $totalDescuento);
            // Concluidas solo si: sesiones completadas Y sin deuda
            return $sesionesCompletadas >= $totalPlan && $totalPlan > 0 && $saldoPendiente <= 0;
        })->values();

        // Historial clínico: todas las sesiones realizadas, agrupadas por servicio, más nuevas primero
        $historial = $hijo->sesiones
            ->filter(fn($s) => $s->estado === 'realizada')
            ->sortByDesc('fecha')
            ->groupBy(function ($s) {
                return $s->planServicio->servicio->nombre
                    ?? $s->servicio->nombre
                    ?? 'Sin servicio';
            });

        return view('admin.hijos.seguimiento', compact('hijo', 'suscripcionesActivas', 'suscripcionesConcluidas', 'historial'));
    }

    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Hijo::with([
            'persona',
            'parentescos.cliente.persona' // para obtener el nombre del padre/madre
        ]);

        if ($search) {
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%")
                    ->orWhere('carnet', 'like', "%{$search}%");
            });
        }

        $hijos = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.hijos.partials.table-body', compact('hijos'))->render(),
                'pagination' => (string) $hijos->links('pagination::bootstrap-5'),
                'total'      => $hijos->total(),
                'from'       => $hijos->firstItem(),
                'to'         => $hijos->lastItem(),
            ]);
        }

        return view('admin.hijos.listar', compact('hijos'));
    }
}
