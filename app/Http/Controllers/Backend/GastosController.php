<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoriaGasto;
use App\Models\Gasto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GastosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $categoriaId = $request->get('categoria_gasto_id', '');

        // ── Resolver filtros ──
        $mesSeleccionado     = $request->input('mes');
        $gestionSeleccionada = $request->input('gestion');
        $fechaDesde          = $request->get('fecha_desde', '');
        $fechaHasta          = $request->get('fecha_hasta', '');

        $filtrarPorMesGestion = $mesSeleccionado && $gestionSeleccionada;

        // Inferir mes/gestion cuando el rango coincide con un mes completo
        if (!$filtrarPorMesGestion && $fechaDesde && $fechaHasta) {
            $desdeC = Carbon::parse($fechaDesde);
            $hastaC = Carbon::parse($fechaHasta);
            if ($desdeC->isSameDay($desdeC->copy()->startOfMonth())
                && $hastaC->isSameDay($hastaC->copy()->endOfMonth())
                && $desdeC->isSameMonth($hastaC)) {
                $mesSeleccionado      = (int) $desdeC->month;
                $gestionSeleccionada  = (int) $desdeC->year;
                $filtrarPorMesGestion = true;
            }
        }

        $anioActual = (int) Carbon::now()->year;
        $gestionesDisponibles = range($anioActual, $anioActual - 5);

        $query = Gasto::with(['categoria.parent', 'compra.proveedor']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                    ->orWhere('comprobante', 'like', "%{$search}%");
            });
        }

        if ($categoriaId) {
            $query->where('categoria_gasto_id', $categoriaId);
        }

        // Filtrar por periodo (mes_correspondiente / anio_correspondiente) o por rango de fecha_gasto
        if ($filtrarPorMesGestion) {
            $query->where('mes_correspondiente', $mesSeleccionado)
                  ->where('anio_correspondiente', $gestionSeleccionada);
        } else {
            if ($fechaDesde) {
                $query->whereDate('fecha_gasto', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $query->whereDate('fecha_gasto', '<=', $fechaHasta);
            }
        }

        $gastos = $query->orderBy('fecha_gasto', 'desc')->paginate(10);

        // Stats (de acuerdo al filtro aplicado)
        $totalGastos = (clone $query)->count();
        $totalMonto = (float) (clone $query)->sum('monto_bs');
        $gastosPeriodo = $totalMonto;
        $gastosMes = Gasto::where('mes_correspondiente', Carbon::now()->month)
            ->where('anio_correspondiente', Carbon::now()->year)
            ->sum('monto_bs');

        $categorias = CategoriaGasto::whereNull('parent_id')
            ->with('children')
            ->orderBy('nombre')
            ->get();

        // Gastos por categoria (en el periodo filtrado) con jerarquia
        $catQuery = DB::table('gastos')
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
            ->orderByDesc('total');

        if ($search) {
            $catQuery->where(function ($q) use ($search) {
                $q->where('gastos.descripcion', 'like', "%{$search}%")
                  ->orWhere('gastos.comprobante', 'like', "%{$search}%");
            });
        }

        if ($categoriaId) {
            $catQuery->where('gastos.categoria_gasto_id', $categoriaId);
        }

        if ($filtrarPorMesGestion) {
            $catQuery->where('gastos.mes_correspondiente', $mesSeleccionado)
                     ->where('gastos.anio_correspondiente', $gestionSeleccionada);
        } else {
            if ($fechaDesde) {
                $catQuery->whereDate('gastos.fecha_gasto', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $catQuery->whereDate('gastos.fecha_gasto', '<=', $fechaHasta);
            }
        }

        $gastosPorCategoria = $catQuery->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.gastos.partials.table-body', compact('gastos'))->render(),
                'pagination' => (string) $gastos->links('pagination::bootstrap-5'),
                'total' => $gastos->total(),
                'from' => $gastos->firstItem(),
                'to' => $gastos->lastItem(),
                'totalGastos' => $totalGastos,
                'totalMonto' => number_format($totalMonto, 2),
                'gastosMes' => number_format($gastosMes, 2),
                'gastosPeriodo' => number_format($gastosPeriodo, 2),
                'gastosPorCategoriaHtml' => view('admin.gastos.partials.gastos-categoria', compact('gastosPorCategoria'))->render(),
            ]);
        }

        return view('admin.gastos.listar', compact(
            'gastos', 'categorias', 'totalGastos', 'totalMonto', 'gastosMes',
            'gastosPeriodo', 'gastosPorCategoria',
            'fechaDesde', 'fechaHasta',
            'mesSeleccionado', 'gestionSeleccionada', 'gestionesDisponibles'
        ));
    }

    public function verificarPeriodo(Request $request)
    {
        $query = Gasto::where('categoria_gasto_id', $request->input('categoria_gasto_id'))
            ->where('mes_correspondiente', $request->input('mes_correspondiente'))
            ->where('anio_correspondiente', $request->input('anio_correspondiente'));

        if ($request->filled('id')) {
            $query->where('id', '!=', $request->input('id'));
        }

        $gasto = $query->with('categoria')->first();

        if ($gasto) {
            return response()->json([
                'exists' => true,
                'gasto'  => [
                    'id'          => $gasto->id,
                    'descripcion' => $gasto->descripcion,
                    'monto_bs'    => $gasto->monto_bs,
                    'fecha_gasto' => $gasto->fecha_gasto->format('d/m/Y'),
                    'categoria'   => $gasto->categoria->nombre ?? '',
                ],
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'categoria_gasto_id' => 'required|exists:categorias_gastos,id',
                'descripcion'        => 'required|string|max:500',
                'monto_bs'           => 'required|numeric|min:0.01',
                'fecha_gasto'          => 'required|date',
                'mes_correspondiente'  => 'required|integer|between:1,12',
                'anio_correspondiente' => 'required|integer|between:2000,2100',
                'comprobante'          => 'nullable|string|max:255',
                'observacion'          => 'nullable|string|max:1000',
            ], [
                'categoria_gasto_id.required' => 'La categoria es obligatoria',
                'categoria_gasto_id.exists'   => 'La categoria seleccionada no existe',
                'descripcion.required'        => 'La descripcion es obligatoria',
                'descripcion.max'             => 'La descripcion no puede exceder los 500 caracteres',
                'monto_bs.required'           => 'El monto es obligatorio',
                'monto_bs.numeric'            => 'El monto debe ser un numero valido',
                'monto_bs.min'                => 'El monto debe ser al menos 0.01 Bs',
                'fecha_gasto.required'         => 'La fecha es obligatoria',
                'fecha_gasto.date'             => 'La fecha debe ser una fecha valida',
                'mes_correspondiente.required' => 'El mes al que corresponde el gasto es obligatorio',
                'mes_correspondiente.between'  => 'El mes debe estar entre 1 y 12',
                'anio_correspondiente.required'=> 'El anio al que corresponde el gasto es obligatorio',
                'anio_correspondiente.between' => 'El anio debe ser valido',
            ]);

            $duplicado = Gasto::where('categoria_gasto_id', $validated['categoria_gasto_id'])
                ->where('mes_correspondiente', $validated['mes_correspondiente'])
                ->where('anio_correspondiente', $validated['anio_correspondiente'])
                ->exists();

            if ($duplicado) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'Ya existe un gasto registrado para esta categoria en el periodo seleccionado'
                ], 422);
            }

            Gasto::create($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Gasto registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validacion'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al registrar el gasto'
            ], 500);
        }
    }

    public function modificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'                 => 'required|exists:gastos,id',
                'categoria_gasto_id' => 'required|exists:categorias_gastos,id',
                'descripcion'        => 'required|string|max:500',
                'monto_bs'           => 'required|numeric|min:0.01',
                'fecha_gasto'          => 'required|date',
                'mes_correspondiente'  => 'required|integer|between:1,12',
                'anio_correspondiente' => 'required|integer|between:2000,2100',
                'comprobante'          => 'nullable|string|max:255',
                'observacion'          => 'nullable|string|max:1000',
            ], [
                'categoria_gasto_id.required' => 'La categoria es obligatoria',
                'categoria_gasto_id.exists'   => 'La categoria seleccionada no existe',
                'descripcion.required'        => 'La descripcion es obligatoria',
                'descripcion.max'             => 'La descripcion no puede exceder los 500 caracteres',
                'monto_bs.required'           => 'El monto es obligatorio',
                'monto_bs.numeric'            => 'El monto debe ser un numero valido',
                'monto_bs.min'                => 'El monto debe ser al menos 0.01 Bs',
                'fecha_gasto.required'         => 'La fecha es obligatoria',
                'fecha_gasto.date'             => 'La fecha debe ser una fecha valida',
                'mes_correspondiente.required' => 'El mes al que corresponde el gasto es obligatorio',
                'mes_correspondiente.between'  => 'El mes debe estar entre 1 y 12',
                'anio_correspondiente.required'=> 'El anio al que corresponde el gasto es obligatorio',
                'anio_correspondiente.between' => 'El anio debe ser valido',
            ]);

            $gasto = Gasto::findOrFail($request->id);

            if ($gasto->compra_id !== null) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede modificar un gasto generado automaticamente por una compra'
                ], 400);
            }

            $duplicado = Gasto::where('categoria_gasto_id', $validated['categoria_gasto_id'])
                ->where('mes_correspondiente', $validated['mes_correspondiente'])
                ->where('anio_correspondiente', $validated['anio_correspondiente'])
                ->where('id', '!=', $gasto->id)
                ->exists();

            if ($duplicado) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'Ya existe otro gasto registrado para esta categoria en el periodo seleccionado'
                ], 422);
            }

            $gasto->update($validated);

            return response()->json([
                'success' => true,
                'msg'     => 'Gasto actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors'  => $e->errors(),
                'message' => 'Error de validacion'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al actualizar el gasto'
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $gasto = Gasto::findOrFail($id);

            if ($gasto->compra_id !== null) {
                return response()->json([
                    'success' => false,
                    'msg'     => 'No se puede eliminar un gasto generado automaticamente por una compra'
                ], 400);
            }

            $gasto->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Gasto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg'     => 'Error al eliminar el gasto'
            ], 500);
        }
    }

    public function detallePdf($id)
    {
        $gasto = Gasto::with('categoria')->findOrFail($id);

        $pdf = Pdf::loadView('admin.gastos.pdf.detalle-gasto', compact('gasto'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', false);
        $pdf->setOption('defaultFont', 'DejaVu Sans');

        $nombreArchivo = 'GASTO-' . str_pad($gasto->id, 4, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
