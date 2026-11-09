# Rediseño visual Dino Baby Spa — Dirección A1

**Fecha:** 2026-11-08
**Alcance:** login, autenticación y todos los módulos administrativos que heredan de `admin.dashboard`. Excluye PDFs.
**Objetivo:** interfaz profesional, elegante y amigable, con identidad verde + morado/lila, adaptable a escritorio, tablet y móvil, sin alterar la lógica del sistema.

## 1. Dirección de diseño

Se eligió la propuesta **A1: verde protagonista, morado/lila como acento**.

- Verde: acciones, navegación activa, estados positivos y foco.
- Morado/lila: énfasis, pestañas, badges, datos secundarios y detalles.
- Fondo crema cálido, tarjetas blancas, bordes suaves y sombras discretas.
- Tipografía: **Manrope** para interfaz y **DM Serif Display** para títulos de página y bienvenida.

### Paleta

| Token | Valor | Uso |
|---|---|---|
| `--dino-petrol` | `#0f3b43` | Sidebar, estructura |
| `--dino-green` | `#2f9e7a` | Botones primarios, enlaces |
| `--dino-mint` | `#6fc2a3` | Acentos, iconos, foco |
| `--dino-lilac` | `#7c3aed` | Énfasis, pestañas, badges |
| `--dino-lilac-soft` | `#f3e8ff` | Fondos de énfasis |
| `--dino-coral` | `#f2a9a0` | Detalles cálidos |
| `--dino-bg` | `#f7f6f2` | Fondo general |
| `--dino-surface` | `#ffffff` | Tarjetas |
| `--dino-text` | `#22333b` | Texto principal |
| `--dino-border` | `#e8e4dc` | Bordes |

## 2. Login 3D

La pantalla de acceso usa una tarjeta centrada con efecto de vidrio esmerilado sobre una escena Three.js.

- Dino bebé low-poly construido con primitivas: cuerpo verde pistacho, pancita con charca turquesa, pecas coral, mejillas, sonrisa, ojos con brillo y brazo que saluda.
- Nube base, burbujas menta/lila y aros flotantes.
- Parallax suave con el puntero; pestañeo y balanceo.
- En móvil, el dino se reubica como compañero en la esquina inferior derecha.
- Respeta `prefers-reduced-motion` (render estático) y pausa la animación cuando la pestaña no está visible.
- Three.js se sirve localmente (`public/backend/assets/libs/three/three.module.min.js`), sin CDN. Si WebGL falla, la tarjeta sigue siendo usable con el degradado CSS.

## 3. Shell administrativo

- Sidebar petróleo plano, logo real, navegación con píldora menta activa y submenús legibles.
- Header blanco translúcido, borde inferior suave, avatar con anillo menta y bloque de usuario limpio.
- Fondo crema, contenido con padding consistente y tarjetas de 15 px de radio.
- Títulos de página en DM Serif con tarjeta blanca; se neutralizan los márgenes negativos de la plantilla.
- Tablas con cabecera menta, texto oscuro, hover suave y desplazamiento horizontal en pantallas pequeñas.
- Botones, formularios, modales, dropdowns, pestañas, paginación y badges normalizados con los tokens A1.
- Modo oscuro completo con superficies petróleo profundas y contraste AA.

## 4. Arquitectura de implementación

Para no tocar la lógica ni duplicar vistas:

- `public/backend/assets/css/dino-theme.css`: capa de diseño cargada después del CSS inline de `admin.dashboard`.
- `resources/views/admin/dashboard.blade.php`: clase `dino-theme` en `<body>` y enlace al tema.
- `admin/body/header|sidebar|footer`: ajustes mínimos de marca y contenido.
- Login: `auth/login.blade.php` + `login-3d.css` + `login-3d.js`.
- Autenticación secundaria: componente `components/auth-layout.blade.php` reutilizado por recuperar/restablecer/confirmar/verificar/registro.
- Se preservan rutas, CSRF, nombres de campos, IDs usados por JavaScript y directivas Blade.

## 5. Responsive

- ≥1200 px: layout completo con sidebar fijo.
- 992–1199 px: tipografía y paddings reducidos.
- 768–991 px: sidebar colapsable, contenido con padding de 82 px superior.
- <768 px: títulos apilados, acciones a ancho completo, header compacto, tablas con scroll horizontal y paginación con wrap.
- Se corrigió el desbordamiento horizontal causado por la paginación y el header móvil; verificación con `scrollWidth === clientWidth` en 390, 768 y 1440 px.

## 6. Verificación realizada

- Login correcto/incorrecto, cierre de sesión y recuperación de contraseña.
- Dashboard + 20 rutas administrativas con HTTP 200.
- Modales, formularios de creación, calendario, estadísticas y gráficos.
- Consola sin errores; sin peticiones 404.
- Capturas Playwright en 1440×900, 1280×800, 768×1024 y 390×844.
- Modo claro y oscuro.
- `prefers-reduced-motion`.

## 7. Riesgos y reversión

- El tema se apoya en `!important` puntual para vencer estilos inline de la plantilla. Si se actualiza la plantilla, revisar las capturas de regresión.
- Los PDFs no cargan el tema, por lo que mantienen su diseño actual.
- Reversión: eliminar el enlace a `dino-theme.css` y la clase `dino-theme`; restaurar `auth/login.blade.php` y el footer si se desea el diseño anterior.
