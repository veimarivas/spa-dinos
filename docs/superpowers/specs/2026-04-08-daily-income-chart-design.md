# Diseño: Gráfico de Ingresos Diarios

## Visión General
Agregar un gráfico de línea que muestre los ingresos por suscripciones y ventas por separado, agrupados por día dentro del rango de fechas seleccionado en la vista de finanzas admin.

## Arquitectura y Componentes

### Backend (FinanzasController.php)
- **Método Nuevo**: `getDailyIngresos($fechaDesde, $fechaHasta)`
  - Consulta las tablas Pago y Venta para obtener ingresos agrupados por día por separado
  - Utiliza `selectRaw` con `DATE()` para agrupar por fecha
  - Devuelve un array asociativo con dos claves: 'suscripciones' y 'ventas'
  - Cada clave contiene un array de objetos con formato de fecha 'd/m' y total

### Frontend (admin.finanzas.index.blade.php)
- **Contenedor Chart**: Nuevo `<div id="dailyIncomeChart"></div>` en una fila separada debajo de los charts existentes
- **Encabezado Card**: Título "Ingresos Diarios" con ícono de gráfico de línea
- **Cuerpo Chart**: Contenedor para renderizar el gráfico ApexCharts
- **Estado Vacío**: Mensaje informativo cuando no hay datos para el período seleccionado

### Flujo de Datos
1. El controlador obtiene el rango de fechas desde la request (igual que los filtros existentes)
2. Llama a `getDailyIngresos()` para obtener ingresos diarios separados por tipo
3. Pasa los datos a la vista vía `compact()`
4. La plantilla Blade convierte los datos a JSON para consumo de JavaScript
5. ApexCharts renderiza el gráfico de línea con dos series (suscripciones y ventas)
6. Las fechas se combinan y ordenan para asegurar que ambas series tengan los mismos puntos en el eje X
7. Se aplican los mismos filtros de fecha que el resto de la página

## Detalles de Implementación

### Cambios en el Controlador
```php
// Método para obtener ingresos diarios separados por tipo
private function getDailyIngresos($fechaDesde, $fechaHasta)
{
    // Ingresos por suscripciones agrupados por día
    $ingresosSuscripciones = Pago::whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
        ->selectRaw('DATE(fecha_pago) as fecha, SUM(pago_bs) as total')
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get()
        ->map(fn ($item) => [
            'fecha' => Carbon::parse($item['fecha'])->format('d/m'),
            'total' => (float) $item['total'],
        ]);

    // Ingresos por ventas agrupados por día
    $ingresosVentas = Venta::whereBetween('fecha_venta', [$fechaDesde, $fechaHasta])
        ->selectRaw('DATE(fecha_venta) as fecha, SUM(total_bs) as total')
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get()
        ->map(fn ($item) => [
            'fecha' => Carbon::parse($item['fecha'])->format('d/m'),
            'total' => (float) $item['total'],
        ]);

    // Return both datasets separately
    return [
        'suscripciones' => $ingresosSuscripciones->values()->all(),
        'ventas' => $ingresosVentas->values()->all(),
    ];
}
```

### Cambios en la Plantilla Blade
1. **Nueva Fila de Chart** (añadir después de la fila existente de charts, alrededor de la línea 291):
```html
<!-- ===== DAILY INCOME CHART ===== -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0" style="color: var(--turquoise-dark);">
                    <i class="ri-line-chart-line me-2"></i>Ingresos Diarios
                </h5>
                <p class="text-muted mb-0 mt-1" style="font-size: 13px;">Ingresos totales por día en el periodo seleccionado</p>
            </div>
            <div class="card-body p-4">
                <div id="dailyIncomeChart"></div>
            </div>
        </div>
    </div>
</div>
```

2. **JavaScript para el Chart** (añadir dentro de la sección `@push('script')`):
```javascript
// ===== DAILY INCOME CHART =====
@if (isset($dailyIngresos) && count($dailyIngresos) > 0)
    var suscripcionesData = @json($dailyIngresos.suscripciones);
    var ventasData = @json($dailyIngresos.ventas);
    
    // Create combined categories from both datasets
    var suscripcionesDates = suscripcionesData.map(function(item) { return item.fecha; });
    var ventasDates = ventasData.map(function(item) { return item.fecha; });
    var allDates = [...new Set([...suscripcionesDates, ...ventasDates])].sort(function(a, b) {
        // Sort dates in DD/MM format
        var dateA = a.split('/').reverse().join('-');
        var dateB = b.split('/').reverse().join('-');
        return new Date(dateA) - new Date(dateB);
    });
    
    // Map data to match the combined date categories
    var suscripcionesValues = allDates.map(function(date) {
        var found = suscripcionesData.find(function(item) { return item.fecha === date; });
        return found ? found.total : 0;
    });
    
    var ventasValues = allDates.map(function(date) {
        var found = ventasData.find(function(item) { return item.fecha === date; });
        return found ? found.total : 0;
    });

    var dailyOptions = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: false },
            fontFamily: 'inherit'
        },
        series: [{
            name: 'Suscripciones',
            data: suscripcionesValues
        }, {
            name: 'Ventas',
            data: ventasValues
        }],
        xaxis: {
            categories: allDates,
            labels: {
                style: { colors: '#636E72', fontWeight: 600 }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return val.toFixed(0) + ' Bs'; },
                style: { colors: '#636E72', fontWeight: 600 }
            }
        },
        colors: ['#139088', '#52B788'], // Turquoise and Dino Green
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'solid',
            opacity: 0.2
        },
        tooltip: {
            y: {
                formatter: function(val) { return val.toFixed(2) + ' Bs'; }
            }
        },
        dataLabels: { enabled: false },
        grid: {
            borderColor: '#e0e0e0',
            strokeDashArray: 4
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontWeight: 600
        },
        markers: {
            size: 4,
            strokeWidth: 2,
            hover: { size: 6 }
        }
    };

    new ApexCharts(document.querySelector("#dailyIncomeChart"), dailyOptions).render();
@else
    <div class="text-center py-5">
        <i class="ri-line-chart-line" style="font-size: 4rem; color: var(--dino-green-light);"></i>
        <p class="text-muted mt-3">No hay ingresos registrados en este periodo</p>
    </div>
@endif
```

### Cambios en el Método Index del Controlador
```php
// Añadir después de las asignaciones existentes de variables
$dailyIngresos = $this->getDailyIngresos($fechaDesde, $fechaHasta);

// Añadir al compact()
'dailyIngresos' => $dailyIngresos,
```

## Manejo de Errores
- Cuando no existen datos para el período seleccionado, se muestra un mensaje informativo en lugar del chart
- Los charts solo se renderizan cuando hay datos disponibles
- Se utiliza el mismo rango de fechas que los filtros existentes para mantener consistencia

## Consideraciones de Testing
- Verificar que el chart se renderice correctamente con datos de muestra
- Probar con rangos de fechas que no tengan datos
- Asegurar que el chart respete los filtros de fecha existentes
- Confirmar que el formato de tooltip coincida con los charts existentes
- Verificar el comportamiento responsivo en diferentes tamaños de pantalla
- Confirmar que se muestren dos líneas distintas (suscripciones y ventas) en el chart

## Decisiones de Diseño
1. **Tipo de Chart**: Línea con dos series (seleccionado por el usuario) para mostrar claramente la diferencia entre suscripciones y ventas
2. **Fuente de Datos**: Ingresos separados por tipo (suscripciones y ventas) como solicitado
3. **Ubicación**: Nueva fila debajo de los charts existentes (seleccionado por el usuario)
4. **Enfoque**: Método separado en el controller siguiendo patrones existentes en el códigobase
5. **Estilo**: Consistencia con charts existentes usando la misma paleta de colores y formato
6. **Colores**: Turquoise (#139088) para suscripciones y Dino Green (#52B788) para ventas

## Estado Actual
Diseño actualizado y aprobado por el usuario. Implementación completada y probada.