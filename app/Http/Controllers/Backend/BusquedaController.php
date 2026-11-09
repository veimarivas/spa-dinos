<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function index()
    {
        return view('admin.busqueda.index');
    }

    public function buscar(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $with = [
            'persona',
            'parentescos.hijo.persona',
            'suscripciones.plan.paquete',
            'suscripciones.sesiones.planServicio.servicio',
            'suscripciones.sesiones.servicio',
            'suscripciones.sesiones.hijo.persona',
            'suscripciones.pagos',
        ];

        // Buscar por nombre o carnet del cliente
        $porCliente = Cliente::with($with)
            ->whereHas('persona', function ($query) use ($q) {
                $query->whereRaw("CONCAT(nombres,' ',apellido_paterno,' ',COALESCE(apellido_materno,'')) LIKE ?", ["%{$q}%"])
                      ->orWhere('carnet', 'LIKE', "%{$q}%");
            })->get();

        // Buscar por nombre o carnet del hijo
        $porHijo = Cliente::with($with)
            ->whereHas('parentescos.hijo.persona', function ($query) use ($q) {
                $query->whereRaw("CONCAT(nombres,' ',apellido_paterno,' ',COALESCE(apellido_materno,'')) LIKE ?", ["%{$q}%"])
                      ->orWhere('carnet', 'LIKE', "%{$q}%");
            })->get();

        $clientes = $porCliente->merge($porHijo)->unique('id');

        $resultados = $clientes->map(function ($cliente) use ($q) {
            // Detectar si coincidió por hijo
            $hijosCoincidienInfo = $cliente->parentescos
                ->filter(function ($p) use ($q) {
                    $nombre = strtolower(
                        ($p->hijo->persona->nombres ?? '') . ' ' .
                        ($p->hijo->persona->apellido_paterno ?? '') . ' ' .
                        ($p->hijo->persona->apellido_materno ?? '')
                    );
                    return str_contains($nombre, strtolower($q));
                })
                ->map(fn($p) => [
                    'id'     => $p->hijo->id,
                    'nombre' => trim(($p->hijo->persona->nombres ?? '') . ' ' . ($p->hijo->persona->apellido_paterno ?? '')),
                ])
                ->values()->all();

            $hijosCoinciden = collect($hijosCoincidienInfo)->pluck('nombre')->all();

            $suscripciones = $cliente->suscripciones->sortByDesc('fecha_registro')->map(function ($sus) {
                $sesiones    = $sus->sesiones;
                $totalPlan   = $sus->plan->cantidad_sesiones ?? 0;
                $realizadas  = $sesiones->where('estado', 'realizada')->count();
                $canceladas  = $sesiones->where('estado', 'cancelada')->count();
                $asistidas   = $sesiones->where('estado', 'realizada')->where('asistio_hijo', true)->count();
                $noAsist     = $sesiones->where('estado', 'realizada')->where('asistio_hijo', false)->count();
                $porRealizar = max(0, $totalPlan - $realizadas);

                $precioPlan     = (float) ($sus->monto_total ?? ($sus->tarifa === 'especial' && $sus->plan->precio_especial ? $sus->plan->precio_especial : ($sus->plan->precio ?? 0)));
                $totalPagado    = (float) $sus->pagos->sum('pago_bs');
                $totalDescuento = (float) $sus->pagos->sum('descuento_bs');
                $pendiente      = max(0, $precioPlan - $totalPagado - $totalDescuento);
                $ultimoPago   = $sus->pagos->sortByDesc('fecha_pago')->first();

                $estadoDerivado = $sus->estado ?? 'activa';
                if ($estadoDerivado !== 'cancelada' && $totalPlan > 0 && $porRealizar == 0) {
                    $estadoDerivado = 'completada';
                }
                $pagoCompletado = $precioPlan > 0 && $pendiente == 0;

                $servicios = $sesiones->map(function ($s) {
                    return $s->planServicio->servicio->nombre ?? $s->servicio->nombre ?? null;
                })->filter()->unique()->values()->all();

                $hijos = $sesiones->map(function ($s) {
                    return trim(($s->hijo->persona->nombres ?? '') . ' ' . ($s->hijo->persona->apellido_paterno ?? ''));
                })->filter()->unique()->values()->all();

                return [
                    'id'             => $sus->id,
                    'plan'           => $sus->plan->nombre_plan ?? 'Sin plan',
                    'paquete'        => $sus->plan->paquete->nombre ?? null,
                    'tarifa'         => $sus->tarifa ?? 'normal',
                    'estado'         => $estadoDerivado,
                    'fecha_registro' => \Carbon\Carbon::parse($sus->fecha_registro)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
                    'url'            => route('admin.suscripciones.show', $sus->id),
                    'sesiones'       => [
                        'total_plan'  => $totalPlan,
                        'realizadas'  => $realizadas,
                        'por_realizar'=> $porRealizar,
                        'canceladas'  => $canceladas,
                        'asistidas'   => $asistidas,
                        'no_asistidas'=> $noAsist,
                    ],
                    'pagos' => [
                        'precio_plan'     => $precioPlan,
                        'total_pagado'    => $totalPagado,
                        'total_descuento' => $totalDescuento,
                        'pendiente'       => $pendiente,
                        'n_pagos'         => $sus->pagos->count(),
                        'ultimo_pago'     => $ultimoPago
                            ? \Carbon\Carbon::parse($ultimoPago->fecha_pago)->locale('es')->isoFormat('D MMM YYYY')
                            : null,
                    ],
                    'pagos_detalle' => $sus->pagos->sortByDesc('fecha_pago')->map(function ($p) {
                        return [
                            'id'        => $p->id,
                            'recibo'    => $p->recibo ?? ('#' . $p->id),
                            'fecha'     => \Carbon\Carbon::parse($p->fecha_pago)->format('d/m/Y'),
                            'tipo_pago' => $p->tipo_pago ?? '-',
                            'monto'     => (float) $p->pago_bs,
                            'descuento' => (float) ($p->descuento_bs ?? 0),
                            'pdf_url'   => route('admin.suscripciones.reciboPdf', $p->id),
                        ];
                    })->values()->all(),
                    'pago_completado' => $pagoCompletado,
                    'servicios' => $servicios,
                    'hijos'     => $hijos,
                ];
            })->values()->all();

            return [
                'id'                   => $cliente->id,
                'nombre'               => trim(($cliente->persona->nombres ?? '') . ' ' . ($cliente->persona->apellido_paterno ?? '') . ' ' . ($cliente->persona->apellido_materno ?? '')),
                'carnet'               => $cliente->persona->carnet ?? null,
                'celular'              => $cliente->persona->celular ?? null,
                'color'                => $cliente->color ?? '#405189',
                'seguimiento_url'      => route('admin.clientes.seguimiento', $cliente->id),
                'nueva_suscripcion_url'=> route('admin.suscripciones.create'),
                'hijos_coinciden'      => $hijosCoinciden,
                'hijos_coinciden_info' => $hijosCoincidienInfo,
                'suscripciones'        => $suscripciones,
            ];
        })->values();

        return response()->json($resultados);
    }
}
