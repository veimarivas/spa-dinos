# Diseño: Modo Oscuro Automático

**Fecha:** 2026-04-08  
**Proyecto:** Dino Baby Spa - Sistema de Gestión

## 1. Objetivo

Implementar modo oscuro automático que detecte la preferencia del sistema operativo del usuario y aplique el tema correspondiente, con opción de cambio manual.

## 2. Requisitos Funcionales

### 2.1 Detección de Preferencia del Sistema
- Usar `window.matchMedia('(prefers-color-scheme: dark)')` para detectar si el usuario tiene modo oscuro activado en su dispositivo
- Aplicar automáticamente el tema correspondiente al cargar la página

### 2.2 Persistencia de Preferencia
- Guardar preferencia del usuario en `localStorage` (clave: `theme` o similar)
- Si existe preferencia guardada, usarla en lugar de la del sistema

### 2.3 Toggle Manual
- Mantener botón existente en el header para cambiar entre tema claro y oscuro
- Al cambiar manualmente, guardar la nueva preferencia en localStorage
- Actualizar icono del botón (moon/sun)

## 3. Implementación

### 3.1 Archivos a Modificar

| Archivo | Acción |
|---------|--------|
| `resources/views/admin/dashboard.blade.php` | Agregar script de inicialización de tema |
| `resources/views/admin/body/header.blade.php` | Agregar evento click al botón de modo oscuro |
| `resources/views/admin/body/sidebar.blade.php` | Verificar compatibilidad con tema oscuro |
| `public/backend/assets/css/app.min.css` o CSS personalizado | Agregar estilos para tema oscuro |

### 3.2 Lógica JavaScript

```javascript
// Pseudocódigo
function initTheme() {
    const storedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    const prefersDark = storedTheme ? storedTheme === 'dark' : systemPrefersDark;
    applyTheme(prefersDark);
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-layout-mode');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme === 'dark');
    localStorage.setItem('theme', newTheme);
}

function applyTheme(isDark) {
    document.documentElement.setAttribute('data-layout-mode', isDark ? 'dark' : 'light');
}
```

### 3.3 Estilos CSS

El tema oscuro debe incluir:
- Fondo principal: `#1a1d21` o similar (gris muy oscuro)
- Fondo de tarjetas: `#2d3748` (gris oscuro)
- Texto principal: `#e2e8f0` (blanco suave)
- Texto secundario: `#a0aec0` (gris claro)
- Bordes: `#4a5568`
- Mantener acentos de la marca (verde dino) para activos y hover

## 4. Compatibilidad

- Verificar que todos los componentes de Velzon soporten el cambio de tema
- Probar en las páginas principales: Dashboard, Clientes, Servicios, Finanzas
- Asegurar que modales, dropdowns y tablas funcionen correctamente en ambos temas

## 5. Consideraciones Adicionales

- El sistema debe ser reactivo a cambios en la preferencia del sistema mientras el usuario navega
- El toggle debe tener transiciones suaves entre temas
- Mantener consistencia visual con el sidebar oscuro ya existente

## 6. Éxito

El modo oscuro funciona correctamente cuando:
1. Al cargar la página, se aplica el tema correcto según la preferencia del sistema
2. El usuario puede cambiar manualmente el tema con el botón del header
3. La preferencia se mantiene al recargar la página
4. Todos los elementos de la interfaz se ven correctamente en ambos temas