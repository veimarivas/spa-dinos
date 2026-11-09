<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CumpleanosController extends Controller
{
    // Paleta de colores consistente con el sistema
    private const COLORES_ROL = [
        'cliente'           => '#1a2d4c',  // Primary
        'trabajador'        => '#037e83',  // Secondary  
        'hijo'              => '#f4df16',  // Accent (amarillo)
        'cliente_trabajador' => '#6f42c1',  // Purple para combinación
    ];

    public function calendario(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        // Cargar personas con relaciones necesarias
        $personas = Persona::with([
            'cliente',
            'trabajador',
            'hijo.parentescos.cliente.persona'
        ])->whereNotNull('fecha_nacimiento')->get();

        $cumpleanos = collect();

        foreach ($personas as $persona) {
            $esCliente = $persona->cliente !== null;
            $esTrabajador = $persona->trabajador !== null;
            $esHijo = $persona->hijo !== null;

            $rol = null;
            $rolDetalle = null;
            $colorHex = null;
            $celular = $persona->celular;
            $clienteNombre = null;

            // Determinar rol con prioridad
            if ($esCliente && $esTrabajador) {
                $rol = 'cliente_trabajador';
                $rolDetalle = 'Cliente / Trabajador';
                $colorHex = self::COLORES_ROL['cliente_trabajador'];
            } elseif ($esCliente) {
                $rol = 'cliente';
                $rolDetalle = 'Cliente';
                $colorHex = self::COLORES_ROL['cliente'];
            } elseif ($esTrabajador) {
                $rol = 'trabajador';
                $rolDetalle = 'Trabajador';
                $colorHex = self::COLORES_ROL['trabajador'];
            } elseif ($esHijo) {
                $hijo = $persona->hijo;
                if ($hijo && $hijo->parentescos->isNotEmpty()) {
                    $primerParentesco = $hijo->parentescos->first();
                    if ($primerParentesco->cliente?->persona) {
                        $cliente = $primerParentesco->cliente->persona;
                        $celular = $cliente->celular;
                        $rol = 'hijo';
                        $rolDetalle = 'Hijo de ' . $this->nombreCompleto($cliente);
                        $colorHex = self::COLORES_ROL['hijo'];
                        $clienteNombre = $this->nombreCompleto($cliente);
                    }
                }
            }

            if (!$rol) continue;

            $fecha = Carbon::parse($persona->fecha_nacimiento);
            $edad = Carbon::parse($persona->fecha_nacimiento)->age;

            $cumpleanos->push([
                'nombre' => $this->nombreCompleto($persona),
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'rol' => $rol,
                'rol_detalle' => $rolDetalle,
                'celular' => $celular,
                'color_hex' => $colorHex,
                'cliente_nombre' => $clienteNombre,
                'dia' => $fecha->day,
                'mes' => $fecha->month,
                'edad' => $edad,
                'icono' => $this->getIconoRol($rol),
            ]);
        }

        // Filtrar y agrupar por día
        $cumpleanosDelMes = $cumpleanos->filter(fn($item) => $item['mes'] == $mes);

        $cumpleanosPorDia = $cumpleanosDelMes->groupBy('dia')->map(function ($grupo) {
            return $grupo->map(function ($item) {
                return [
                    'nombre' => $item['nombre'],
                    'rol' => $item['rol'],
                    'rol_detalle' => $item['rol_detalle'],
                    'celular' => $item['celular'],
                    'color_hex' => $item['color_hex'],
                    'edad' => $item['edad'],
                    'icono' => $item['icono'],
                    'mensaje' => $this->generarMensaje($item['rol'], $item['nombre'], $item['cliente_nombre'] ?? ''),
                ];
            });
        });

        // Datos para el calendario
        $primerDia = Carbon::create($anio, $mes, 1);
        $diasEnMes = $primerDia->daysInMonth;
        $offset = $primerDia->dayOfWeekIso - 1; // 0 = lunes

        // Stats para cards
        $stats = [
            'total' => $cumpleanosDelMes->count(),
            'clientes' => $cumpleanosDelMes->where('rol', 'cliente')->count(),
            'trabajadores' => $cumpleanosDelMes->where('rol', 'trabajador')->count(),
            'hijos' => $cumpleanosDelMes->where('rol', 'hijo')->count(),
        ];

        return view('admin.cumpleanos.calendario', compact(
            'cumpleanosPorDia',
            'mes',
            'anio',
            'offset',
            'diasEnMes',
            'stats'
        ));
    }

    private function nombreCompleto($persona)
    {
        return trim($persona->nombres . ' ' . $persona->apellido_paterno .
            ($persona->apellido_materno ? ' ' . $persona->apellido_materno : ''));
    }

    private function getIconoRol($rol)
    {
        return match ($rol) {
            'cliente' => 'ri-user-star-line',
            'trabajador' => 'ri-user-settings-line',
            'hijo' => 'ri-baby-line',
            'cliente_trabajador' => 'ri-double-quotes-l',
            default => 'ri-user-line'
        };
    }

    private function generarMensaje($rol, $nombre, $clienteNombre = '')
    {
        $mensajes = [
            'hijo' => "¡Feliz cumpleaños a {$nombre}! En este día especial, queremos saludarte a ti, {$clienteNombre}, y desearte que disfrutes de un nuevo año de vida de tu hijo(a). Que tengan un hermoso día. Atte. DINO Baby Spa",
            'cliente' => "¡Feliz cumpleaños, {$nombre}! Que tengas un día maravilloso lleno de alegría. Atte. DINO Baby Spa",
            'trabajador' => "¡Feliz cumpleaños, {$nombre}! Gracias por tu dedicación. Que pases un excelente día. Atte. DINO Baby Spa",
            'cliente_trabajador' => "¡Feliz cumpleaños, {$nombre}! Como cliente y trabajador, te deseamos un día especial. Gracias por formar parte de nuestra familia. Atte. DINO Baby Spa",
        ];
        return $mensajes[$rol] ?? "¡Feliz cumpleaños! ";
    }
}
