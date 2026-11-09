# Especificación: Panel de Estadísticas en Dashboard Admin

**Fecha:** 2026-04-08
**Proyecto:** Dino Baby Spa - Módulo de Estadísticas
**Estado:** Aprobado

---

## 1. Resumen General

Agregar sección de estadísticas al dashboard administrativo existente (`/admin/dashboard`) con 3 pestañas: Servicios, Productos y Stock. Cada pestaña permitirá filtrar por rango de fechas personalizado y mostrará gráficos interactivos.

---

## 2. Estructura UI

### 2.1 Layout General
- **Ubicación:** Debajo de las estadísticas existentes del dashboard
- **Navegación:** Tabs horizontales con 3 pestañas
- **Filtros:** Selector de rango de fechas en cada pestaña (esquina superior derecha)
- **Tipo de gráficos:** Mixto (barras para rankings, líneas para tendencias, dona para distribuciones)

### 2.2 Componentes UI
- Contenedor de tabs con estilos del tema Dino Baby Spa
- Filtro de fechas con calendario (datepicker)
- Cards para cada widget con header y contenido
- Gráficos usando librería ApexCharts o Chart.js
- Tabla responsive para inventario

---

## 3. Pestaña: Servicios Más Utilizados

### 3.1 Widget: Top 5 Servicios
- **Tipo:** Gráfico de barras horizontales
- **Datos:** Ranking de 5 servicios más usados en el período
- **Eje Y:** Nombre del servicio
- **Eje X:** Cantidad de sesiones
- **Orden:** Descendente

### 3.2 Widget: Desglose por Paquete → Plan → Servicio
- **Tipo:** Gráfico de barras agrupadas o tabla colapsable
- **Jerarquía:** Paquete → Plan → Servicio
- **Mostrar:** Nombre, cantidad de usos, porcentaje del total
- **Interacción:** Click para expandir y ver planes de cada paquete

### 3.3 Widget: Tendencia Semanal
- **Tipo:** Gráfico de líneas
- **Eje X:** Semanas del período seleccionado
- **Eje Y:** Cantidad de sesiones
- **Líneas:** Una por servicio top (máximo 5)

### 3.4 Filtros
- Rango de fechas personalizado (inicio - fin)
- Selector de paquete (opcional, "Todos" por defecto)

---

## 4. Pestaña: Productos Más Vendidos

### 4.1 Widget: Top 5 Productos
- **Tipo:** Gráfico de barras verticales
- **Datos:** Ranking de 5 productos más vendidos
- **Eje Y:** Nombre del producto
- **Eje X:** Cantidad vendida
- **Información adicional:** Monto total en tooltip

### 4.2 Widget: Comparación por Tipo de Venta
- **Tipo:** Gráfico de barras agrupadas o Side-by-side
- **Categorías:**
  - Venta Directa: cantidad y monto
  - Venta por Sesión: cantidad (productos consumidos en citas)
- **Mostrar:** Gráfico comparativo + tabla con detalles

### 4.3 Widget: Tendencia
- **Tipo:** Gráfico de líneas
- **Opciones:** Ver por semana o por mes según rango de fechas
- **Líneas:** Una por tipo de venta (directa vs sesión)

### 4.4 Filtros
- Rango de fechas personalizado
- Tipo de venta: Todos / Directa / Sesión

---

## 5. Pestaña: Stock de Productos

### 5.1 Widget: Alerta de Stock Bajo
- **Tipo:** Lista de tarjetas o tabla filtrable
- **Criterio:** Productos con stock menor al mínimo definido
- **Indicadores de color:**
  - 🔴 Rojo: Agotado (stock < 50% del mínimo)
  - 🟡 Amarillo: Stock Bajo (stock 50%-99% del mínimo)
  - 🟢 Verde: OK (stock >= mínimo)
- **Campos:** Nombre, stock actual, stock mínimo, diferencia

### 5.2 Widget: Inventario General
- **Tipo:** Tabla responsive
- **Columnas:** Producto, Categoría, Stock Actual, Stock Mínimo, Estado
- **Ordenamiento:** Por estado (agotados primero), luego por nombre
- **Paginación:** Si hay más de 20 productos

### 5.3 Widget: Resumen por Categoría
- **Tipo:** Gráfico de dona (pie chart)
- **Datos:** Distribución de productos por categoría
- **Colores:** Diferentes por categoría

### 5.4 Filtros
- Ver: Todos / Solo bajos / Solo agotados
- Categoría: Todas / específica

---

## 6. Backend y Datos

### 6.1 Modelos a utilizar
- `Sesione` - Sesiones realizadas (para servicios)
- `Servicio` - Catálogo de servicios
- `Plane` - Planes
- `Paquete` - Paquetes
- `Venta` - Ventas de productos
- `VentaDetalle` - Detalle de ventas
- `Producto` - Catálogo de productos
- `CategoriaProducto` - Categorías de productos

### 6.2 Relaciones
- Paquete → Planes (hasMany)
- Plan → Servicios (many-to-many via PlanesServicios)
- Sesión → Servicio (belongsTo)
- Venta → VentaDetalle (hasMany)
- Producto → CategoriaProducto (belongsTo)

### 6.3 Endpoints necesarios
- `GET /admin/dashboard/estadisticas/servicios` - Datos de servicios
- `GET /admin/dashboard/estadisticas/productos` - Datos de productos
- `GET /admin/dashboard/estadisticas/stock` - Datos de inventario

### 6.4 Parámetros
- `fecha_inicio` (required)
- `fecha_fin` (required)
- `paquete_id` (opcional, solo para servicios)
- `tipo_venta` (opcional, para productos)
- `categoria_id` (opcional, para stock)
- `filtro_stock` (opcional: todos/bajo/agotado)

---

## 7. Consideraciones Técnicas

### 7.1 Librería de gráficos
- Usar ApexCharts (ya incluido en el proyecto) o Chart.js
- Implementar tema de colores Dino Baby Spa en los gráficos

### 7.2 Rendimiento
- Implementar cache para consultas frecuentes
- Paginación en tablas grandes
- Carga diferida de gráficos (lazy load)

### 7.3 Responsive
- Gráficos adaptativos para móvil
- Tablas con scroll horizontal en dispositivos pequeños

### 7.4 Tema oscuro
- Gráficos deben adaptarse al tema oscuro del dashboard
- Colores de gráficos visibles tanto en modo claro como oscuro

---

## 8. Validaciones

- Rango de fechas no puede ser vacío
- Fecha inicio no puede ser mayor a fecha fin
- Mostrar mensaje cuando no hay datos en el período seleccionado
- Manejo de productos sin stock mínimo definido (tratar como stock OK)

---

## 9. Fuera de Alcance (YAGNI)

- Exportar a PDF/Excel (futuro)
- Notificaciones automáticas de stock bajo
- Predicciones de demanda
- Comparación períodos anteriores

---

## 10. Éxito del Proyecto

El feature será exitoso cuando:
1. Las 3 pestañas muestren datos correctos según el período seleccionado
2. Los filtros funcionen correctamente para todas las secciones
3. Los gráficos sean legibles y responsivos
4. El diseño sea consistente con el resto del dashboard
5. No afecte el rendimiento de carga del dashboard