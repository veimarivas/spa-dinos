@php
    $tieneTelefono = !empty($cumple['celular']);
    $waLink = $tieneTelefono
        ? 'https://wa.me/' . $cumple['celular'] . '?text=' . urlencode($cumple['mensaje'])
        : '#';
@endphp

<a href="{{ $waLink }}" target="_blank"
    class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none birthday-badge"
    style="background: {{ $cumple['color_hex'] }}15; border-left: 3px solid {{ $cumple['color_hex'] }};"
    title="{{ $cumple['rol_detalle'] }} &middot; {{ $cumple['edad'] }} a&ntilde;os">
    <i class="{{ $cumple['icono'] }}" style="color: {{ $cumple['color_hex'] }};"></i>
    <div class="flex-grow-1 min-w-0">
        <div class="fw-semibold text-truncate small" style="color: var(--dino-heading);">
            {{ $cumple['nombre'] }}
        </div>
        <div class="text-muted" style="font-size: .7rem;">{{ $cumple['edad'] }} a&ntilde;os</div>
    </div>
    @if ($tieneTelefono)
        <i class="ri-whatsapp-line fs-5" style="color: #25D366;"></i>
    @else
        <i class="ri-phone-off-line text-muted fs-6"></i>
    @endif
</a>
