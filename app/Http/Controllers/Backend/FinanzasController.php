<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Gasto;
use App\Models\Pago;
use App\Models\Sesione;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanzasController extends Controller
{
    public function index(Request $request)
    {
        // ── Resolver rango de fechas ──
        // Prioridad: mes+gestion → fecha_desde/fecha_hasta → mes actual
        $mesSeleccionado     = $request->input('mes');
        $gestionSeleccionada = $request->input('gestion');

        if ($mesSeleccionado && $gestionSeleccionada) {
            $base = Carbon::create((int) $gestionSeleccionada, (int) $mesSeleccionado, 1);
            $fechaDesde = $base->copy()->startOfMonth()->format('Y-m-d');
            $fechaHasta = $base->copy()->endOfMonth()->format('Y-m-d');
        } else {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        // Si el usuario no eligió mes/gestion, intentamos inferirlos del rango cuando coincide con un mes completo
        if (!$mesSeleccionado || !$gestionSeleccionada) {
            $desdeC = Carbon::parse($fechaDesde);
            $hastaC = Carbon::parse($fechaHasta);
            if ($desdeC->isSameDay($desdeC->copy()->startOfMonth())
                && $hastaC->isSameDay($hastaC->copy()->endOfMonth())
                && $desdeC->isSameMonth($hastaC)) {
                $mesSeleccionado     = (int) $desdeC->month;
                $gestionSeleccionada = (int) $desdeC->year;
            }
        }

        // Gestiones disponibles para el select (últimos 5 años + actual)
        $anioActual = (int) Carbon::now()->year;
        $gestionesDisponibles = range($anioActual, $anioActual - 5);

        // INGRESOS por suscripciones (split por tarifa)
        $ingresoSuscripcionesNormal = (float) Pago::whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
            ->whereHas('suscripcion', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('tarifa', 'normal')->orWhereNull('tarifa');
                });
            })
            ->sum('pago_bs');

        $ingresoSuscripcionesEspecial = (float) Pago::whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
            ->whereHas('suscripcion', function ($q) {
                $q->where('tarifa', 'especial');
            })
            ->sum('pago_bs');

        $ingresoSuscripciones = $ingresoSuscripcionesNormal + $ingresoSuscripcionesEspecial;
        $ingresoVentas = (float) Venta::whereBetween('fecha_venta', [$fechaDesde, $fechaHasta])->sum('total_bs');
        $totalIngresos = $ingresoSuscripciones + $ingresoVentas;

        // GASTOS (filtrado por mes_correspondiente y anio_correspondiente)
        [$desdeYM, $hastaYM] = $this->rangoPeriodo($fechaDesde, $fechaHasta);

        $totalGastosRegistrados = (float) Gasto::whereRaw('(anio_correspondiente * 100 + mes_correspondiente) BETWEEN ? AND ?', [$desdeYM, $hastaYM])
            ->sum('monto_bs');

        $totalGastos = $totalGastosRegistrados;
        $balance = $totalIngresos - $totalGastos;

        // GASTOS POR CATEGORIA con jerarquia
        $gastosPorCategoria = Gasto::whereRaw('(gastos.anio_correspondiente * 100 + gastos.mes_correspondiente) BETWEEN ? AND ?', [$desdeYM, $hastaYM])
            ->join('categorias_gastos', 'gastos.categoria_gasto_id', '=', 'categorias_gastos.id')
            ->leftJoin('categorias_gastos as parent_cat', 'categorias_gastos.parent_id', '=', 'parent_cat.id')
            ->select(
                'categorias_gastos.id',
                'categorias_gastos.nombre',
                'categorias_gastos.parent_id',
                DB::raw('COALESCE(parent_cat.nombre, categorias_gastos.nombre) as parent_nombre'),
                DB::raw('SUM(gastos.monto_bs) as total')
            )
            ->groupBy('categorias_gastos.id', 'categorias_gastos.nombre', 'categorias_gastos.parent_id', DB::raw('COALESCE(parent_cat.nombre, categorias_gastos.nombre)'))
            ->orderBy('parent_nombre')
            ->orderByDesc('total')
            ->get();

        // MONTHLY TREND (last 6 months for line chart)
        $mesesEs = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $tendenciaMensual = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $inicioMes = $mes->copy()->startOfMonth()->format('Y-m-d');
            $finMes = $mes->copy()->endOfMonth()->format('Y-m-d');

            $ingMes = (float) Pago::whereBetween('fecha_pago', [$inicioMes, $finMes])->sum('pago_bs')
                + (float) Venta::whereBetween('fecha_venta', [$inicioMes, $finMes])->sum('total_bs');

            $gasMes = (float) Gasto::where('mes_correspondiente', $mes->month)
                ->where('anio_correspondiente', $mes->year)
                ->sum('monto_bs');

            $tendenciaMensual[] = [
                'mes' => $mesesEs[$mes->month - 1].' '.$mes->year,
                'ingresos' => $ingMes,
                'gastos' => $gasMes,
            ];
        }

        // ── Detail data for modals ──
        $pagosSuscripcionesDetalle = Pago::with([
            'suscripcion.cliente.persona',
            'suscripcion.plan',
            'trabajador.persona',
        ])
            ->whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
            ->orderByDesc('recibo')
            ->get()
            ->map(fn ($p) => [
                'recibo' => $p->recibo ?? '-',
                'fecha' => Carbon::parse($p->fecha_pago)->format('d/m/Y'),
                'cliente' => trim(($p->suscripcion->cliente->persona->nombres ?? '').' '.($p->suscripcion->cliente->persona->apellido_paterno ?? '')),
                'plan' => $p->suscripcion->plan->nombre_plan ?? '-',
                'tarifa' => $p->suscripcion->tarifa ?? 'normal',
                'tipo_pago' => $p->tipo_pago ?? '-',
                'monto' => (float) $p->pago_bs,
                'descuento' => (float) ($p->descuento_bs ?? 0),
                'trabajador' => $p->trabajador ? trim($p->trabajador->persona->nombres.' '.$p->trabajador->persona->apellido_paterno) : '-',
                'pdf_url' => route('admin.suscripciones.reciboPdf', $p->id),
            ])->values();

        $ventasDetalleModal = Venta::with([
            'cliente.persona',
            'detalles.producto',
            'trabajador.persona',
        ])
            ->whereBetween('fecha_venta', [$fechaDesde, $fechaHasta])
            ->orderByDesc('fecha_venta')
            ->get()
            ->map(fn ($v) => [
                'recibo' => $v->recibo ?? '-',
                'fecha' => Carbon::parse($v->fecha_venta)->format('d/m/Y'),
                'cliente' => $v->cliente ? trim($v->cliente->persona->nombres.' '.$v->cliente->persona->apellido_paterno) : 'Sin cliente',
                'n_productos' => $v->detalles->count(),
                'tipo_pago' => $v->tipo_pago ?? '-',
                'monto' => (float) $v->total_bs,
                'descuento' => (float) ($v->descuento_bs ?? 0),
                'trabajador' => $v->trabajador ? trim($v->trabajador->persona->nombres.' '.$v->trabajador->persona->apellido_paterno) : '-',
                'pdf_url' => route('admin.ventas.reciboPdf', $v->id),
            ])->values();

        // Map pago_salario_id → worker name + session count for salary gastos
        $salariosMap = Sesione::whereNotNull('pago_salario_id')
            ->with('trabajador.persona')
            ->get()
            ->groupBy('pago_salario_id')
            ->map(fn ($items) => [
                'trabajador' => $items->first()->trabajador
                    ? trim($items->first()->trabajador->persona->nombres.' '.$items->first()->trabajador->persona->apellido_paterno)
                    : '-',
                'n_sesiones' => $items->count(),
            ]);

        $mesesEsLargo = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $gastosDetalleCategoria = Gasto::with('categoria.parent')
            ->whereRaw('(anio_correspondiente * 100 + mes_correspondiente) BETWEEN ? AND ?', [$desdeYM, $hastaYM])
            ->orderByDesc('anio_correspondiente')
            ->orderByDesc('mes_correspondiente')
            ->orderByDesc('fecha_gasto')
            ->get()
            ->groupBy(fn ($g) => $g->categoria->nombre ?? 'Sin categoría')
            ->map(fn ($items) => $items->map(fn ($g) => [
                'fecha' => $g->mes_correspondiente && $g->anio_correspondiente
                    ? ($mesesEsLargo[$g->mes_correspondiente - 1].' '.$g->anio_correspondiente)
                    : Carbon::parse($g->fecha_gasto)->format('d/m/Y'),
                'fecha_pago' => Carbon::parse($g->fecha_gasto)->format('d/m/Y'),
                'descripcion' => $g->descripcion,
                'monto' => (float) $g->monto_bs,
                'observacion' => $g->observacion,
                'trabajador' => $salariosMap[$g->id]['trabajador'] ?? null,
                'n_sesiones' => $salariosMap[$g->id]['n_sesiones'] ?? null,
            ])->values()->all())
            ->toArray();

        // DAILY INGRESOS (for daily income chart)
        $dailyIngresos = $this->getDailyIngresos($fechaDesde, $fechaHasta);

        return view('admin.finanzas.index', compact(
            'fechaDesde',
            'fechaHasta',
            'mesSeleccionado',
            'gestionSeleccionada',
            'gestionesDisponibles',
            'ingresoSuscripciones',
            'ingresoSuscripcionesNormal',
            'ingresoSuscripcionesEspecial',
            'ingresoVentas',
            'totalIngresos',
            'totalGastosRegistrados',
            'totalGastos',
            'balance',
            'gastosPorCategoria',
            'tendenciaMensual',
            'pagosSuscripcionesDetalle',
            'ventasDetalleModal',
            'gastosDetalleCategoria',
            'dailyIngresos',
        ));
    }

    /**
     * Convierte el rango de fechas a un rango (anio*100 + mes) para filtrar gastos por periodo correspondiente.
     */
    private function rangoPeriodo($fechaDesde, $fechaHasta): array
    {
        $desde = Carbon::parse($fechaDesde);
        $hasta = Carbon::parse($fechaHasta);

        return [
            $desde->year * 100 + $desde->month,
            $hasta->year * 100 + $hasta->month,
        ];
    }

    /**
     * Get daily ingresos (suscripciones + ventas) grouped by date
     */
    private function getDailyIngresos($fechaDesde, $fechaHasta)
    {
        $desde = Carbon::parse($fechaDesde);
        $hasta = Carbon::parse($fechaHasta);
        $mismoMes = $desde->month === $hasta->month && $desde->year === $hasta->year;

        // Generar todos los días del rango
        $allDates = [];
        $fechaActual = $desde->copy();
        while ($fechaActual->lte($hasta)) {
            $label = $mismoMes
                ? $fechaActual->format('j')   // solo día: 1, 2, 3...
                : $fechaActual->format('j M'); // día + mes: 1 Ene, 15 Feb...
            $allDates[] = [
                'dateKey' => $fechaActual->format('Y-m-d'),
                'label'   => $label,
            ];
            $fechaActual->addDay();
        }

        // Ingresos por suscripciones agrupados por día
        $suscDB = Pago::whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
            ->selectRaw('DATE(fecha_pago) as fecha, SUM(pago_bs) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy(fn ($item) => Carbon::parse($item['fecha'])->format('Y-m-d'));

        // Ingresos por ventas agrupados por día
        $ventasDB = Venta::whereBetween('fecha_venta', [$fechaDesde, $fechaHasta])
            ->selectRaw('DATE(fecha_venta) as fecha, SUM(total_bs) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy(fn ($item) => Carbon::parse($item['fecha'])->format('Y-m-d'));

        // Mapear cada día con sus valores (0 si no hay datos)
        $chartLabels = [];
        $suscripcionesValues = [];
        $ventasValues = [];
        $totalValues = [];

        foreach ($allDates as $day) {
            $key = $day['dateKey'];
            $chartLabels[] = $day['label'];
            $sus = (float) ($suscDB[$key]->total ?? 0);
            $ven = (float) ($ventasDB[$key]->total ?? 0);
            $suscripcionesValues[] = $sus;
            $ventasValues[] = $ven;
            $totalValues[] = $sus + $ven;
        }

        return [
            'suscripciones' => $suscripcionesValues,
            'ventas'        => $ventasValues,
            'total'         => [
                'fechas'  => $chartLabels,
                'valores' => $totalValues,
            ],
            'dates'              => $chartLabels,
            'suscripcionesValues'=> $suscripcionesValues,
            'ventasValues'       => $ventasValues,
            'totalValues'        => $totalValues,
        ];
    }
}
