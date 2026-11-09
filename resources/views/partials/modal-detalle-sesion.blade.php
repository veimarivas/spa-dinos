<!-- Modal de Detalle de Sesión - Diseño Velzon con Paleta Personalizada -->
<div class="modal fade" id="detalleSesionModal" tabindex="-1" aria-labelledby="detalleSesionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

            <!-- Header con gradiente personalizado -->
            <div class="modal-header border-0 py-3 px-4 rounded-top-4"
                style="background: linear-gradient(135deg, #1a2d4c 0%, #037e83 100%);">
                <h5 class="modal-title fw-bold text-white" id="detalleSesionModalLabel">
                    <i class="ri-calendar-check-line me-2"></i>Detalle de la Sesión
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4" style="background: #fafbfc;">
                <!-- Loader -->
                <div class="text-center py-5" id="detalle-loader" style="display: none;">
                    <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #1a2d4c;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Cargando información de la sesión...</p>
                </div>

                <!-- Contenido dinámico -->
                <div id="detalle-contenido"></div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-top bg-white py-3 px-4 rounded-bottom-4 flex-wrap gap-2">
                <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal"
                    style="border-color: #1a2d4c; color: #1a2d4c;">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <!-- Botones de acción dinámicos (llenados por JS según estado) -->
                <div id="detalle-acciones" class="d-flex gap-2 flex-wrap"></div>
                <a href="#" id="btnVerSuscripcion" class="btn rounded-pill px-4 shadow-sm" target="_blank"
                    style="background: linear-gradient(135deg, #1a2d4c 0%, #037e83 100%); border: none; color: #fff;">
                    <i class="ri-eye-line me-1"></i>Ver Suscripción
                </a>
            </div>

        </div>
    </div>
</div>

@push('style')
    <style>
        /* ===== PALETA PERSONALIZADA ===== */
        :root {
            --color-primary: #1a2d4c;
            --color-secondary: #037e83;
            --color-accent: #f4df16;
            --color-primary-light: rgba(26, 45, 76, 0.1);
            --color-secondary-light: rgba(3, 126, 131, 0.1);
            --color-accent-light: rgba(244, 223, 22, 0.15);
        }

        /* ===== BADGES DE ESTADO ===== */
        .badge-estado {
            font-size: 0.85rem;
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            text-transform: capitalize;
        }

        .badge-estado i {
            font-size: 1rem;
        }

        /* Estados específicos */
        .badge-programada {
            background: var(--color-accent-light);
            color: #856404;
            border: 1px solid #ffc107;
        }

        .badge-confirmada {
            background: var(--color-secondary-light);
            color: #037e83;
            border: 1px solid #037e83;
        }

        .badge-realizada {
            background: var(--color-primary-light);
            color: #1a2d4c;
            border: 1px solid #1a2d4c;
        }

        .badge-cancelada {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .badge-reprogramada {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid #6c757d;
        }

        /* ===== CARDS INFORMATIVAS ===== */
        .info-card {
            background: #fff;
            border: 1px solid rgba(26, 45, 76, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            transition: all 0.25s ease;
            height: 100%;
        }

        .info-card:hover {
            border-color: var(--color-secondary);
            box-shadow: 0 8px 25px rgba(3, 126, 131, 0.12);
            transform: translateY(-3px);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed rgba(26, 45, 76, 0.1);
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .info-icon.primary {
            background: var(--color-primary-light);
            color: var(--color-primary);
        }

        .info-icon.secondary {
            background: var(--color-secondary-light);
            color: var(--color-secondary);
        }

        .info-icon.accent {
            background: var(--color-accent-light);
            color: #856404;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #6c757d;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1.4;
        }

        .info-sub {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        /* ===== AVATARES ===== */
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-lg {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-weight: 600;
        }

        /* ===== ALERTAS DE REPROGRAMACIÓN ===== */
        .reprogramacion-alert {
            background: linear-gradient(135deg, rgba(244, 223, 22, 0.15) 0%, rgba(255, 248, 220, 0.8) 100%);
            border: 1px solid #f4df16;
            border-left: 4px solid #f4df16;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

        .reprogramacion-alert.info {
            background: linear-gradient(135deg, rgba(3, 126, 131, 0.1) 0%, rgba(224, 245, 246, 0.8) 100%);
            border-color: #037e83;
            border-left-color: #037e83;
        }

        .reprogramacion-link {
            color: var(--color-primary);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 2px dashed var(--color-secondary);
            transition: all 0.2s ease;
        }

        .reprogramacion-link:hover {
            color: var(--color-secondary);
            border-bottom-style: solid;
            text-decoration: none;
        }

        /* ===== INFORME ===== */
        .informe-card {
            background: #fff;
            border: 1px solid rgba(26, 45, 76, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
        }

        .informe-card .info-icon {
            background: var(--color-primary-light);
            color: var(--color-primary);
        }

        .informe-text {
            color: #495057;
            line-height: 1.6;
            font-size: 0.95rem;
            white-space: pre-wrap;
        }

        /* ===== UTILIDADES ===== */
        .text-primary-custom {
            color: var(--color-primary) !important;
        }

        .text-secondary-custom {
            color: var(--color-secondary) !important;
        }

        .text-accent-custom {
            color: #856404 !important;
        }

        .bg-primary-custom {
            background-color: var(--color-primary) !important;
        }

        .bg-secondary-custom {
            background-color: var(--color-secondary) !important;
        }

        .bg-accent-custom {
            background-color: var(--color-accent) !important;
        }

        /* ===== MODAL RESPONSIVE ===== */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.75rem;
            }

            .modal-content {
                border-radius: 16px !important;
            }

            .info-card {
                padding: 1rem;
            }

            .info-value {
                font-size: 1rem;
            }
        }

        /* ===== ANIMACIONES ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #detalle-contenido>.row {
            animation: fadeInUp 0.4s ease forwards;
        }
    </style>
@endpush
