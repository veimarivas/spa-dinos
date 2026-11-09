<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Dino Baby Spa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de gestión Dino Baby Spa" name="description" />
    <meta content="Dino Baby Spa" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- jsvectormap css -->
    <link href="{{ asset('backend/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!--Swiper slider css-->
    <link href="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>

    <!-- MODO OSCURO - Se ejecuta inmediatamente para evitar flash -->
    <script>
        (function() {
            const THEME_KEY = 'theme';
            const html = document.documentElement;
            
            function getStoredTheme() {
                return localStorage.getItem(THEME_KEY);
            }
            
            function getCurrentTheme() {
                return getStoredTheme() || 'light';
            }
            
            function applyTheme(theme) {
                const isDark = theme === 'dark';
                html.setAttribute('data-layout-mode', isDark ? 'dark' : 'light');
            }
            
            // Aplicar tema inmediatamente
            applyTheme(getCurrentTheme());
        })();
    </script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ===== COLORES DINO BABY SPA ===== */
        :root {
            --dino-green: #95D5B2;
            --dino-green-dark: #52B788;
            --dino-green-light: #B7E4C7;
            --coral-spot: #FFB5A7;
            --coral-dark: #FF8F85;
            --turquoise: #A8DADC;
            --turquoise-dark: #457B9D;
            --soft-blue: #BDE0FE;
            --cream: #FFF9F0;
            --text-dark: #2D3436;
            --text-light: #636E72;
            --white: #ffffff;
            --sidebar-bg: #1a1d21;
            --sidebar-hover: rgba(149, 213, 178, 0.15);
            --sidebar-active: rgba(149, 213, 178, 0.25);
            --shadow-soft: 0 4px 20px rgba(149, 213, 178, 0.15);
            --radius-md: 12px;
            --radius-lg: 16px;
            
            /* Tema claro (default) */
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #2D3436;
            --text-secondary: #636E72;
            --border-color: #e9ecef;
        }

        /* Tema Oscuro */
        [data-layout-mode="dark"] {
            --bg-body: #1a1d21;
            --bg-card: #2d3748;
            --text-primary: #e2e8f0;
            --text-secondary: #a0aec0;
            --border-color: #4a5568;
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        [data-layout-mode="dark"] body {
            background-color: var(--bg-body);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .card {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .card-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .card-header .card-title {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        [data-layout-mode="dark"] .card-header h4,
        [data-layout-mode="dark"] .card-header h5,
        [data-layout-mode="dark"] .card-header h6 {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .card-header .text-muted {
            color: #94a3b8 !important;
        }

        [data-layout-mode="dark"] .card-header .text-dark {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table {
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .table th,
        [data-layout-mode="dark"] .table td {
            border-color: var(--border-color);
        }

        [data-layout-mode="dark"] .table thead th {
            background-color: #1a1d21;
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .table tbody tr {
            background-color: var(--bg-card);
        }

        [data-layout-mode="dark"] .table tbody tr:hover {
            background-color: rgba(149, 213, 178, 0.1);
        }

        [data-layout-mode="dark"] .form-control,
        [data-layout-mode="dark"] .form-select {
            background-color: #1a1d21;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .btn-primary {
            background-color: var(--dino-green-dark);
            border-color: var(--dino-green-dark);
        }

        [data-layout-mode="dark"] .btn-primary:hover {
            background-color: var(--dino-green);
            border-color: var(--dino-green);
        }

        [data-layout-mode="dark"] .page-title,
        [data-layout-mode="dark"] h1,
        [data-layout-mode="dark"] h2,
        [data-layout-mode="dark"] h3,
        [data-layout-mode="dark"] h4,
        [data-layout-mode="dark"] h5,
        [data-layout-mode="dark"] h6 {
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .dropdown-menu {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        [data-layout-mode="dark"] .dropdown-item {
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .dropdown-item:hover {
            background-color: rgba(149, 213, 178, 0.15);
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .modal-content {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        [data-layout-mode="dark"] .modal-header {
            border-bottom-color: var(--border-color);
        }

        [data-layout-mode="dark"] .modal-footer {
            border-top-color: var(--border-color);
        }

        [data-layout-mode="dark"] .page-content {
            background-color: var(--bg-body);
        }

        [data-layout-mode="dark"] .topbar {
            background-color: var(--bg-card);
            border-bottom-color: var(--border-color);
        }

        [data-layout-mode="dark"] .header-item {
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .btn-ghost-secondary {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .btn-ghost-secondary:hover {
            background-color: rgba(149, 213, 178, 0.15);
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .nav-tabs .nav-link {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .nav-tabs .nav-link.active {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .page-header {
            background-color: var(--bg-card);
            border-bottom-color: var(--border-color);
        }

        [data-layout-mode="dark"] .ecommerce-product-card {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        /* ===== MODO OSCURO COMPLETO ===== */
        [data-layout-mode="dark"] .main-content {
            background-color: var(--bg-body);
        }

        [data-layout-mode="dark"] .container-fluid {
            background-color: var(--bg-body);
        }

        [data-layout-mode="dark"] .navbar {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color);
        }

        [data-layout-mode="dark"] .bg-light {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] .bg-white {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] .shadow-sm,
        [data-layout-mode="dark"] .shadow {
            box-shadow: var(--shadow-soft) !important;
        }

        [data-layout-mode="dark"] .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .btn-outline-secondary:hover {
            background-color: rgba(149, 213, 178, 0.15);
            border-color: var(--dino-green);
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .btn-light {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .btn-light:hover {
            background-color: rgba(149, 213, 178, 0.15);
            border-color: var(--dino-green);
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .btn-dark {
            background-color: var(--dino-green-dark);
            border-color: var(--dino-green-dark);
        }

        [data-layout-mode="dark"] input::placeholder {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .form-label {
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .breadcrumb {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .breadcrumb-item a {
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] .pagination {
            --bs-pagination-bg: var(--bg-card);
            --bs-pagination-border-color: var(--border-color);
            --bs-pagination-color: var(--text-primary);
            --bs-pagination-hover-bg: rgba(149, 213, 178, 0.15);
            --bs-pagination-hover-color: var(--dino-green);
            --bs-pagination-hover-border-color: var(--dino-green);
            --bs-pagination-active-bg: var(--dino-green-dark);
            --bs-pagination-active-border-color: var(--dino-green-dark);
            --bs-pagination-active-color: #ffffff;
            --bs-pagination-disabled-bg: var(--bg-card);
            --bs-pagination-disabled-border-color: var(--border-color);
            --bs-pagination-disabled-color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .page-link {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .page-link:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
            border-color: var(--dino-green) !important;
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .page-item.active .page-link {
            background-color: var(--dino-green-dark) !important;
            border-color: var(--dino-green-dark) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .page-item.disabled .page-link {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
            opacity: 0.5;
        }

        [data-layout-mode="dark"] .pagination-wrapper {
            background-color: var(--bg-card);
            border-top: 1px solid var(--border-color);
        }

        [data-layout-mode="dark"] .dataTables_paginate {
            background-color: var(--bg-card);
        }

        [data-layout-mode="dark"] .dataTables_paginate .paginate_button {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .dataTables_paginate .paginate_button:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
            border-color: var(--dino-green) !important;
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .dataTables_paginate .paginate_button.current {
            background-color: var(--dino-green-dark) !important;
            border-color: var(--dino-green-dark) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .dataTables_paginate .paginate_button.disabled {
            background-color: var(--bg-card) !important;
            color: var(--text-secondary) !important;
            opacity: 0.5;
        }

        [data-layout-mode="dark"] .page-item.disabled .page-link {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .alert-secondary {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .list-group-item {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .list-group-item:hover {
            background-color: rgba(149, 213, 178, 0.1);
        }

        [data-layout-mode="dark"] .nav-pills .nav-link {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .nav-pills .nav-link.active {
            background-color: var(--dino-green-dark);
            color: #ffffff;
        }

        [data-layout-mode="dark"] .avatar-md,
        [data-layout-mode="dark"] .avatar-sm,
        [data-layout-mode="dark"] .avatar-lg {
            background-color: var(--bg-card);
            color: var(--text-primary);
        }

        [data-layout-mode="dark"] .badge-secondary {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        [data-layout-mode="dark"] .border {
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .text-dark {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .text-secondary {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] a {
            color: var(--dino-green);
        }

        [data-layout-mode="dark"] a:hover {
            color: var(--dino-green-light);
        }

        [data-layout-mode="dark"] .font-size-14 {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .font-size-13 {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .font-size-12 {
            color: var(--text-secondary);
        }

        [data-layout-mode="dark"] .apexcharts-canvas .apexcharts-text {
            fill: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .apexcharts-canvas .apexcharts-legend-text {
            color: var(--text-primary) !important;
        }

        /* Transiciones suaves */
        [data-layout-mode="dark"] body,
        [data-layout-mode="dark"] .card,
        [data-layout-mode="dark"] .navbar,
        [data-layout-mode="dark"] .modal-content,
        [data-layout-mode="dark"] .dropdown-menu {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* ===== MODO OSCURO: SIDEBAR Y HEADER ===== */
        [data-layout-mode="dark"] .navbar-brand-box {
            background: linear-gradient(135deg, #1a4d3a 0%, #2d5a6e 100%) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-layout-mode="dark"] .logo-text {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .logo-lg-img {
            filter: brightness(0.9) contrast(1.1);
        }

        [data-layout-mode="dark"] #page-topbar {
            background-color: var(--bg-card) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .topbar-hamburger {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .topbar-hamburger:hover {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .header-item {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .btn-topbar {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .btn-topbar:hover {
            color: var(--dino-green) !important;
            background-color: rgba(149, 213, 178, 0.15) !important;
        }

        [data-layout-mode="dark"] .topbar-user {
            color: var(--text-primary) !important;
            background-color: transparent !important;
        }

        [data-layout-mode="dark"] .topbar-user:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
        }

        [data-layout-mode="dark"] .topbar-user .btn {
            background-color: transparent !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .topbar-user .btn:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
        }

        /* ===== MODO OSCURO: FOOTER ===== */
        [data-layout-mode="dark"] .footer {
            background-color: var(--bg-card) !important;
            border-top: 1px solid var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .footer a {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .text-sm-end {
            color: var(--text-secondary) !important;
        }

        /* ===== MODO OSCURO: FORMULARIOS EN MODALS ===== */
        [data-layout-mode="dark"] .modal-content {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .modal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .modal-header .modal-title {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        [data-layout-mode="dark"] .modal-header .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        [data-layout-mode="dark"] .modal-header .btn-close:hover {
            opacity: 1;
        }

        [data-layout-mode="dark"] .modal-body {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .modal-footer {
            background-color: var(--bg-card) !important;
            border-top: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .form-label {
            color: var(--text-primary) !important;
            font-weight: 500 !important;
        }

        [data-layout-mode="dark"] .form-control {
            background-color: #1a1d21 !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .form-control:focus {
            background-color: #1a1d21 !important;
            border-color: var(--dino-green) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(149, 213, 178, 0.25) !important;
        }

        [data-layout-mode="dark"] .form-control::placeholder {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .form-control:disabled {
            background-color: #2d3748 !important;
            color: var(--text-secondary) !important;
            opacity: 0.6;
        }

        [data-layout-mode="dark"] .form-select {
            background-color: #1a1d21 !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .form-select:focus {
            background-color: #1a1d21 !important;
            border-color: var(--dino-green) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(149, 213, 178, 0.25) !important;
        }

        [data-layout-mode="dark"] textarea.form-control {
            background-color: #1a1d21 !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] input[type="checkbox"],
        [data-layout-mode="dark"] input[type="radio"] {
            accent-color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .form-check-label {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .form-check-input {
            background-color: #1a1d21 !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .form-check-input:checked {
            background-color: var(--dino-green) !important;
            border-color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .input-group-text {
            background-color: #2d3748 !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .form-text {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .invalid-feedback {
            color: #f87171 !important;
        }

        [data-layout-mode="dark"] .valid-feedback {
            color: var(--dino-green) !important;
        }

        /* ===== MODO OSCURO: PÁGINA SEGUIMIENTO HIJOS ===== */
        [data-layout-mode="dark"] .profile-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0d9488 100%) !important;
        }

        [data-layout-mode="dark"] .avatar-profile {
            border-color: var(--bg-card) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4) !important;
        }

        [data-layout-mode="dark"] .card-body.pt-5 .fw-bold[style*="color: #1e293b"] {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .stat-card {
            background: linear-gradient(135deg, rgba(149, 213, 178, 0.1) 0%, rgba(149, 213, 178, 0.05) 100%) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .stat-card .fw-bold[style*="color: #1e293b"] {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .stat-card i[style*="color: #06b6d4"] {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .stat-card i[style*="color: #22c55e"] {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .stat-card i[style*="color: #a855f7"] {
            color: #c084fc !important;
        }

        [data-layout-mode="dark"] .stat-card i[style*="color: #eab308"] {
            color: #fbbf24 !important;
        }

        [data-layout-mode="dark"] .btn.btn-outline-secondary {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .btn.btn-outline-secondary:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .badge[style*="background: rgba(6,182,212,0.1)"] {
            background-color: rgba(149, 213, 178, 0.15) !important;
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .card-header.border-0[style*="linear-gradient(180deg, #f8fafc"] {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills .nav-link {
            background-color: transparent !important;
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--dino-green-dark) 0%, var(--dino-green) 100%) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills .nav-link .badge.bg-white {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills .nav-link.active .badge.bg-white {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .card-body.p-4 {
            background-color: var(--bg-card) !important;
        }

        /* ===== MODO OSCURO: ESTADO PAGO E HISTORIAL PAGOS ===== */
        [data-layout-mode="dark"] .rounded-4.p-3.bg-success-subtle {
            background-color: rgba(34, 197, 94, 0.15) !important;
            border-color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .rounded-4.p-3.bg-danger-subtle {
            background-color: rgba(239, 68, 68, 0.15) !important;
            border-color: #ef4444 !important;
        }

        [data-layout-mode="dark"] .rounded-4.p-3.border[style*="background:#f8fafc"] {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .rounded-4.p-3 .bg-white {
            background-color: var(--bg-body) !important;
        }

        [data-layout-mode="dark"] .rounded-4.p-3 .text-primary {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .btn.btn-success.rounded-pill {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
            border-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .btn.btn-success.rounded-pill:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
        }

        [data-layout-mode="dark"] .table-sm.table-hover thead.table-light {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] .table-sm.table-hover thead th {
            background-color: var(--bg-card) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .table-sm.table-hover tbody td {
            background-color: transparent !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .badge.bg-primary-subtle.text-primary {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #60a5fa !important;
        }

        [data-layout-mode="dark"] .text-muted.small {
            color: var(--text-secondary) !important;
        }

        /* ===== MODO OSCURO: HISTORIAL CLÍNICO ===== */
        [data-layout-mode="dark"] .card.border-0.shadow-sm.rounded-4 .card-footer,
        [data-layout-mode="dark"] .card-footer.bg-light {
            background-color: var(--bg-card) !important;
            border-top: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .mt-4.p-4.rounded-4[style*="background: linear-gradient(135deg, #f8fafc"] {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .mt-4.p-4.rounded-4[style*="background: linear-gradient(135deg, #f8fafc"] .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .mt-4.p-4.rounded-4[style*="background: linear-gradient(135deg, #f8fafc"] .fw-semibold {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .rounded-circle[style*="background: linear-gradient(135deg, #0d9488, #14b8a6)"] {
            background: linear-gradient(135deg, var(--dino-green-dark), var(--dino-green)) !important;
        }

        [data-layout-mode="dark"] .rounded-circle[style*="background: linear-gradient(135deg, #f59e0b, #d97706)"] {
            background: linear-gradient(135deg, #d97706, #f59e0b) !important;
        }

        [data-layout-mode="dark"] .rounded-circle[style*="background: linear-gradient(135deg, #dc3545, #c82333)"] {
            background: linear-gradient(135deg, #dc2626, #ef4444) !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills.nav-fill .nav-link {
            background-color: transparent !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .nav.nav-pills.nav-fill .nav-link.active {
            background: linear-gradient(135deg, var(--dino-green-dark), var(--dino-green)) !important;
            color: #ffffff !important;
            border-color: transparent !important;
        }

        [data-layout-mode="dark"] .user-name-text {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .user-name-sub-text {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .header-profile-user {
            border: 2px solid var(--dino-green) !important;
        }

        /* ===== MODO OSCURO: SIDEBAR SIMPLEBAR ===== */
        [data-layout-mode="dark"] .simplebar-content {
            background-color: var(--sidebar-bg-dark, #0f172a) !important;
        }

        [data-layout-mode="dark"] #scrollbar {
            background-color: var(--sidebar-bg-dark, #0f172a) !important;
        }

        [data-layout-mode="dark"] .vertical-menu .simplebar-content {
            background-color: #0f172a !important;
        }

        [data-layout-mode="dark"] .hamburger-icon span {
            background-color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .hamburger-icon:hover span {
            background-color: var(--dino-green) !important;
        }

        /* ===== MODO OSCURO: SESIONES PROGRAMADAS ===== */
        [data-layout-mode="dark"] .card.border-0.shadow-lg.rounded-4 {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .card-header[style*="linear-gradient(135deg, #7c3aed"] {
            background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%) !important;
        }

        [data-layout-mode="dark"] #sesionesTabs {
            background-color: var(--bg-card) !important;
            border-bottom-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] #sesionesTabs .nav-link {
            color: var(--text-secondary) !important;
            background-color: transparent !important;
        }

        [data-layout-mode="dark"] #sesionesTabs .nav-link.active {
            color: #7c3aed !important;
            background-color: var(--bg-card) !important;
            border-bottom-color: #7c3aed !important;
        }

        [data-layout-mode="dark"] #sesionesTabs .nav-link:hover {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .tab-content {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] .tab-content .card.border-0.shadow-sm.rounded-4 {
            background-color: var(--bg-body) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .tab-content .text-dark {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .tab-content .badge {
            border: 1px solid var(--border-color);
        }

        /* ===== MODO OSCURO: TABS TOP CLIENTES, DEUDAS, RECIBOS ===== */
        [data-layout-mode="dark"] .card-header[style*="linear-gradient(180deg, #f8fafc"] {
            background: linear-gradient(180deg, var(--bg-card) 0%, var(--bg-card) 100%) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] #clientesTabs {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link {
            color: var(--text-secondary) !important;
            background-color: transparent !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link:hover {
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link.active {
            color: var(--dino-green-dark) !important;
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] #clientesTabs .badge {
            background-color: var(--dino-green-dark) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #dc2626"] {
            border-bottom-color: #dc2626 !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #dc2626"].active {
            color: #dc2626 !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #dc2626"] .badge {
            background-color: #dc2626 !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #7c3aed"] {
            border-bottom-color: #7c3aed !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #7c3aed"].active {
            color: #7c3aed !important;
        }

        [data-layout-mode="dark"] #clientesTabs .nav-link[style*="border-bottom: 3px solid #7c3aed"] .badge {
            background-color: #7c3aed !important;
        }

        /* ===== MODO OSCURO: TABLAS ===== */
        [data-layout-mode="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-color);
        }

        [data-layout-mode="dark"] .table thead th {
            background: linear-gradient(135deg, #1a1d21 0%, #2d3748 100%) !important;
            color: var(--text-primary) !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .table thead[style*="linear-gradient(135deg, #f0fdfa"] th {
            background: linear-gradient(135deg, #1a4d3a 0%, #0d9488 100%) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table thead[style*="linear-gradient(135deg, #fef2f2"] th {
            background: linear-gradient(135deg, #4a1a1a 0%, #dc2626 100%) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table tbody tr {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .table tbody tr:hover {
            background-color: #374151 !important;
            color: #f3f4f6 !important;
        }

        [data-layout-mode="dark"] .table tbody tr:hover td {
            color: #f3f4f6 !important;
        }

        [data-layout-mode="dark"] .table tbody td {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            background-color: transparent !important;
        }

        [data-layout-mode="dark"] .table .text-dark {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .table .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .table .badge.bg-light {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-color);
        }

        [data-layout-mode="dark"] .table .badge.bg-warning {
            background-color: #d97706 !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table .badge.bg-secondary {
            background-color: #4a5568 !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table .badge.bg-info {
            background-color: #0d9488 !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .table-responsive {
            background-color: var(--bg-card);
        }

        [data-layout-mode="dark"] .table-hover tbody tr:hover > * {
            background-color: rgba(149, 213, 178, 0.15) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .fw-semibold {
            color: var(--text-primary) !important;
        }

        /* ===== MODO OSCURO: CALENDARIO CUMPLEAÑOS ===== */
        [data-layout-mode="dark"] .page-title-box[style*="linear-gradient(135deg, #1a2d4c"] {
            background: linear-gradient(135deg, #0f172a 0%, #0d9488 100%) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-light {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-light:hover {
            background-color: var(--dino-green-dark) !important;
            color: #ffffff !important;
            border-color: var(--dino-green-dark) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-outline-light {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-outline-light:hover {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .card-title.mb-0.fw-bold.text-dark {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .card-header.bg-white.border-bottom {
            background-color: var(--bg-card) !important;
            border-bottom-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .form-select {
            background-color: #1a1d21 !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .badge[style*="color: #1a2d4c"] {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .badge[style*="color: #037e83"] {
            color: var(--dino-green) !important;
            border-color: var(--dino-green) !important;
            background-color: rgba(149, 213, 178, 0.15) !important;
        }

        [data-layout-mode="dark"] .badge[style*="color: #856404"] {
            color: #fbbf24 !important;
            border-color: #fbbf24 !important;
            background-color: rgba(251, 191, 36, 0.15) !important;
        }

        [data-layout-mode="dark"] .badge[style*="color: #6f42c1"] {
            color: #a78bfa !important;
            border-color: #a78bfa !important;
            background-color: rgba(167, 139, 250, 0.15) !important;
        }

        [data-layout-mode="dark"] .table.table-borderless thead th {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .calendar-day {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .calendar-day .day-number {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .calendar-day.today {
            background-color: var(--dino-green-dark) !important;
        }

        [data-layout-mode="dark"] .calendar-day.today .day-number {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .calendar-day.other-month .day-number {
            color: var(--text-secondary) !important;
            opacity: 0.5;
        }

        [data-layout-mode="dark"] .cumpleanos-badge {
            background-color: rgba(251, 191, 36, 0.2) !important;
            color: #fbbf24 !important;
        }

        [data-layout-mode="dark"] .border-bottom {
            border-color: var(--border-color) !important;
        }

        /* ===== MODO OSCURO: CALENDARIO CUMPLEAÑOS - ELEMENTOS ADICIONALES ===== */
        [data-layout-mode="dark"] .calendar-cell {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] {
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] .rounded-circle.fw-bold[style*="color: #1a2d4c"] {
            color: var(--text-primary) !important;
            background-color: var(--bg-card) !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] .rounded-circle.fw-bold[style*="background: #f4df16"] {
            background-color: var(--dino-green) !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] span[style*="color: #1a2d4c"] {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] div[style*="color: #1a2d4c"] {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] td[style*="vertical-align: top"] .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .birthday-badge {
            background-color: var(--bg-body) !important;
        }

        [data-layout-mode="dark"] .birthday-badge:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
        }

        [data-layout-mode="dark"] .opacity-50 {
            opacity: 0.5 !important;
        }

        [data-layout-mode="dark"] .avatar-title[style*="color: #1a2d4c"] {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .h4.mb-0.fw-bold.text-dark,
        [data-layout-mode="dark"] h4.mb-0.fw-bold.text-dark {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .avatar-lg .avatar-title[style*="color: #1a2d4c"] {
            background-color: rgba(149, 213, 178, 0.15) !important;
            color: var(--dino-green) !important;
        }

        [data-layout-mode="dark"] .avatar-lg .avatar-title[style*="color: #856404"] {
            background-color: rgba(251, 191, 36, 0.15) !important;
            color: #fbbf24 !important;
        }

        [data-layout-mode="dark"] .badge.bg-warning.text-dark {
            background-color: #d97706 !important;
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .badge.bg-light.text-muted.border {
            background-color: var(--bg-card) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }

        /* ===== MODO OSCURO: CABECERA CALENDARIO ===== */
        [data-layout-mode="dark"] .card.border-0.shadow-lg.rounded-4 .card-header.bg-white,
        [data-layout-mode="dark"] .card-header.bg-white.border-bottom {
            background-color: var(--bg-card) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .card-header .card-title.mb-0.fw-bold {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .card-header .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .card-header .avatar-title[style*="color: #856404"] {
            color: #fbbf24 !important;
        }

        /* ===== MODO OSCURO: CABECERAS DE TABLAS ===== */
        [data-layout-mode="dark"] .card-header.bg-primary-subtle {
            background-color: #0f172a !important;
            border-bottom: 1px solid #52B788 !important;
        }

        [data-layout-mode="dark"] .card-header.bg-primary-subtle .card-title {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .card-header.bg-primary-subtle .card-title i {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .card-header.bg-primary-subtle i {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] div.card-header h5.card-title,
        [data-layout-mode="dark"] .card-header .card-title.mb-0.fw-bold.text-primary {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] div.card-header h5.card-title i {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .page-title-box.d-sm-flex.align-items-center.justify-content-between.bg-light {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box .text-primary {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .page-title-box .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-layout-mode="dark"] .page-title-box {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box h4,
        [data-layout-mode="dark"] .page-title-box h3,
        [data-layout-mode="dark"] .page-title-box h5 {
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .page-title-box .text-white {
            color: #ffffff !important;
        }

        [data-layout-mode="dark"] .page-title-box .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-light {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-light:hover {
            background-color: var(--dino-green-dark) !important;
            color: #ffffff !important;
            border-color: var(--dino-green-dark) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-outline-light {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box .btn-outline-light:hover {
            background-color: rgba(149, 213, 178, 0.15) !important;
            color: var(--dino-green) !important;
        }

        /* page-title-box con bg-gradient-primary */
        [data-layout-mode="dark"] .page-title-box.d-sm-flex.align-items-center.justify-content-between.bg-gradient-primary {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box.d-sm-flex.align-items-center.justify-content-between.rounded-4.p-4.shadow-sm {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .page-title-box.rounded-4.p-4.shadow-sm {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
        }

        [data-layout-mode="dark"] .search-box .form-control {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-layout-mode="dark"] .search-box .search-icon {
            color: var(--text-secondary) !important;
        }

        /* ===== SIDEBAR PERSONALIZADO ===== */
        #scrollbar {
            padding: 0 0 20px;
        }

        .navbar-brand-box {
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--dino-green-dark) 0%, var(--turquoise-dark) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo img {
            max-height: 40px;
            transition: all 0.3s ease;
        }

        .logo-sm img {
            max-height: 32px;
        }

        .menu-title {
            padding: 24px 24px 12px;
            margin-bottom: 8px;
        }

        .menu-title-text {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.6) !important;
            text-transform: uppercase;
        }

        .navbar-nav .nav-link.menu-link {
            padding: 12px 24px;
            margin: 3px 16px;
            border-radius: var(--radius-md);
            transition: all 0.25s ease;
            position: relative;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .navbar-nav .nav-link.menu-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--white) !important;
            transform: translateX(4px);
        }

        .navbar-nav .nav-link.menu-link.active {
            background: linear-gradient(135deg, var(--sidebar-active) 0%, transparent 100%);
            color: var(--white) !important;
            font-weight: 600;
            border-left: 3px solid var(--dino-green);
        }

        .navbar-nav .nav-link.menu-link i {
            font-size: 18px;
            width: 24px;
            margin-right: 12px;
            color: rgba(255, 255, 255, 0.85);
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link.menu-link:hover i,
        .navbar-nav .nav-link.menu-link.active i {
            color: var(--dino-green);
        }

        .menu-item-text {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .menu-arrow {
            transition: transform 0.25s ease;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.6);
        }

        .nav-link[aria-expanded="true"] .menu-arrow {
            transform: rotate(90deg);
            color: var(--dino-green);
        }

        /* Submenús */
        .menu-dropdown {
            background: rgba(255, 255, 255, 0.03);
            margin: 0 16px 8px;
            border-radius: var(--radius-md);
            border-left: 2px solid var(--dino-green-light);
        }

        .menu-dropdown .nav-link {
            padding: 10px 24px 10px 52px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.75) !important;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
        }

        .menu-dropdown .nav-link:hover {
            background-color: rgba(149, 213, 178, 0.1);
            color: var(--dino-green) !important;
            padding-left: 56px;
        }

        .menu-dropdown .nav-link.active {
            background-color: var(--sidebar-active);
            color: var(--dino-green) !important;
            font-weight: 600;
        }

        .menu-dropdown .nav-link i {
            font-size: 14px;
            width: 20px;
            margin-right: 8px;
            color: rgba(255, 255, 255, 0.7);
        }

        .menu-dropdown .nav-link:hover i,
        .menu-dropdown .nav-link.active i {
            color: var(--dino-green);
        }

        .menu-dropdown .menu-dropdown .nav-link {
            padding-left: 68px;
        }

        .menu-dropdown .menu-dropdown .nav-link:hover {
            padding-left: 72px;
        }

        /* Badges */
        .navbar-nav .badge {
            font-size: 10px;
            padding: 4px 8px;
            font-weight: 600;
            background: var(--coral-spot);
            color: var(--text-dark);
        }

        /* Scrollbar personalizada */
        #scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        #scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        #scrollbar::-webkit-scrollbar-thumb {
            background: var(--dino-green);
            border-radius: 4px;
        }

        #scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--dino-green-dark);
        }

        /* ===== HEADER PERSONALIZADO ===== */
        #page-topbar {
            background: var(--white);
            box-shadow: var(--shadow-soft);
            border-bottom: 3px solid var(--dino-green-light);
        }

        .navbar-header {
            padding: 0 24px;
            min-height: 70px;
        }

        .header-item .btn-ghost-secondary {
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .header-item .btn-ghost-secondary:hover {
            background-color: var(--dino-green-light);
            color: var(--turquoise-dark);
        }

        .header-profile-user {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border: 2px solid var(--dino-green);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .topbar-user:hover .header-profile-user {
            border-color: var(--coral-spot);
            transform: scale(1.05);
        }

        .user-name-text {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
        }

        .user-name-sub-text {
            color: var(--text-light);
            font-size: 12px;
        }

        .dropdown-menu {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 8px 30px rgba(149, 213, 178, 0.2);
            border-top: 3px solid var(--dino-green);
        }

        .dropdown-header {
            background: linear-gradient(135deg, var(--dino-green-light) 0%, var(--turquoise) 100%);
            color: var(--turquoise-dark);
            font-weight: 700;
            padding: 12px 20px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .dropdown-item {
            padding: 10px 20px;
            color: var(--text-dark);
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--dino-green-light);
            color: var(--turquoise-dark);
            padding-left: 24px;
        }

        .dropdown-item i {
            color: var(--dino-green-dark);
            transition: color 0.2s ease;
        }

        .dropdown-item:hover i {
            color: var(--coral-dark);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            background: linear-gradient(135deg, var(--cream) 0%, var(--soft-blue) 100%);
            min-height: 100vh;
        }

        .page-title-box {
            padding: 24px 0;
            margin-bottom: 24px;
            background-color: transparent !important;
            border: none !important;
        }

        .page-title-box h4 {
            color: var(--turquoise-dark);
            font-weight: 800;
            font-size: 1.5rem;
        }

        .page-title-box h4.text-white {
            color: #ffffff !important;
        }

        .page-title-box .text-white-50 {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Page title box en modo claro con bg-light */
        .page-title-box.bg-light {
            background-color: #f8f9fa !important;
        }

        .page-title-box.bg-light h4,
        .page-title-box.bg-light h3,
        .page-title-box.bg-light h5 {
            color: #1e293b !important;
        }

        /* Page title box con gradiente en modo claro */
        .page-title-box.bg-gradient-primary {
            background: linear-gradient(135deg, #405189 0%, #5c6ac4 100%) !important;
        }

        .page-title-box.bg-gradient-primary h4,
        .page-title-box.bg-gradient-primary h3,
        .page-title-box.bg-gradient-primary h5 {
            color: #ffffff !important;
        }

        .breadcrumb-item a {
            color: var(--dino-green-dark);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: var(--coral-dark);
            font-weight: 600;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--dino-green-light);
        }

        /* ===== CARDS & COMPONENTS ===== */
        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
            background: var(--white);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(149, 213, 178, 0.3);
        }

        .card-header {
            background: linear-gradient(135deg, var(--dino-green-light) 0%, var(--turquoise) 100%);
            border-bottom: none;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            padding: 16px 20px;
        }

        .card-header h4,
        .card-header h5 {
            color: var(--turquoise-dark);
            font-weight: 700;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%);
            border: none;
            color: var(--text-dark);
            font-weight: 600;
            padding: 8px 20px;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(149, 213, 178, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(149, 213, 178, 0.4);
            background: linear-gradient(135deg, var(--dino-green-dark) 0%, var(--turquoise-dark) 100%);
            color: var(--white);
        }

        .btn-success {
            background: var(--dino-green);
            border: none;
            color: var(--text-dark);
        }

        .btn-success:hover {
            background: var(--dino-green-dark);
            color: var(--white);
        }

        .btn-danger {
            background: var(--coral-spot);
            border: none;
            color: var(--text-dark);
        }

        .btn-danger:hover {
            background: var(--coral-dark);
            color: var(--white);
        }

        /* ===== TABLES ===== */
        .table {
            background: var(--white);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--dino-green-light) 0%, var(--turquoise) 100%);
            color: var(--turquoise-dark);
            font-weight: 700;
            border: none;
            padding: 14px 16px;
        }

        .table tbody td {
            border-color: var(--dino-green-light);
            padding: 12px 16px;
            color: var(--text-dark);
        }

        .table-hover tbody tr:hover {
            background-color: var(--dino-green-light);
        }

        /* ===== FORMS ===== */
        .form-control {
            border: 2px solid var(--dino-green-light);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            background: var(--cream);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--dino-green);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(149, 213, 178, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        /* ===== ALERTS ===== */
        .alert-success {
            background: linear-gradient(135deg, var(--dino-green-light) 0%, var(--cream) 100%);
            border: 2px solid var(--dino-green);
            color: var(--turquoise-dark);
            border-radius: var(--radius-md);
        }

        .alert-danger {
            background: linear-gradient(135deg, #FFE5E5 0%, var(--cream) 100%);
            border: 2px solid var(--coral-spot);
            color: var(--coral-dark);
            border-radius: var(--radius-md);
        }

        /* ===== BACK TO TOP ===== */
        #back-to-top {
            background: linear-gradient(135deg, var(--dino-green) 0%, var(--turquoise) 100%);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
        }

        #back-to-top:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(149, 213, 178, 0.4);
        }

        #back-to-top i {
            color: var(--text-dark);
            font-weight: 700;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1199.98px) {
            .navbar-nav .nav-link.menu-link {
                margin: 2px 12px;
                padding: 10px 20px;
            }

            .menu-dropdown {
                margin: 0 12px 6px;
            }
        }

        @media (max-width: 991.98px) {
            .navbar-header {
                padding: 0 16px;
            }

            .page-title-box {
                padding: 16px 0;
            }
        }

        @media (max-width: 767.98px) {

            .user-name-text,
            .user-name-sub-text {
                display: none !important;
            }

            .header-profile-user {
                width: 32px;
                height: 32px;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes pulse-gentle {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.85;
            }
        }

        .navbar-nav .nav-link.menu-link.active {
            animation: pulse-gentle 2s ease-in-out infinite;
        }

        /* Decorative bubbles in background */
        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, var(--coral-spot) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, var(--dino-green) 0%, transparent 40%);
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
        }

        .page-content {
            position: relative;
        }

        /* ===== MODALES PERSONALIZADOS - COMPATIBLE CON VELZON ===== */
        .modal {
            z-index: 1055 !important;
            /* Valor estándar de Bootstrap */
        }

        .modal-backdrop {
            z-index: 1050 !important;
            background-color: rgba(45, 52, 54, 0.75);
        }

        .modal-backdrop.show {
            opacity: 1;
        }

        .modal-dialog {
            z-index: 1060;
            margin: 1.75rem auto;
        }

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            background: var(--white);
            box-shadow: 0 10px 40px rgba(149, 213, 178, 0.3);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--dino-green-light) 0%, var(--turquoise) 100%);
            border-bottom: 2px solid var(--dino-green);
            padding: 16px 20px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .modal-header .modal-title {
            color: var(--turquoise-dark);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .modal-header .btn-close {
            background: transparent;
            border: none;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 20px;
            color: var(--text-dark);
        }

        .modal-footer {
            padding: 12px 20px;
            border-top: 2px dashed var(--dino-green-light);
            gap: 10px;
        }

        /* Inputs dentro de modals */
        .modal .form-control {
            border: 2px solid var(--dino-green-light);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            background: var(--cream);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .modal .form-control:focus {
            border-color: var(--dino-green);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(149, 213, 178, 0.15);
        }

        .modal .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        /* Responsive para modals */
        @media (max-width: 767.98px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-footer {
                flex-direction: column-reverse;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }
    </style>

    <!-- Tema Dino Baby Spa (capa de diseño A1) -->
    <link href="{{ asset('backend/assets/css/dino-theme.css') }}?v={{ filemtime(public_path('backend/assets/css/dino-theme.css')) }}" rel="stylesheet" type="text/css" />
</head>

<body class="dino-theme">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.body.header')

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo -->
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Dino Baby Spa"
                            class="logo-sm-img">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Dino Baby Spa"
                            class="logo-lg-img">
                    </span>
                </a>

                <!-- Light Logo -->
                <a href="{{ url('/admin/dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Dino Baby Spa"
                            class="logo-sm-img">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Dino Baby Spa"
                            class="logo-lg-img">
                    </span>
                </a>

                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div class="dropdown sidebar-user m-1 rounded">
                <!-- User info can be added here if needed -->
            </div>

            @include('admin.body.sidebar')

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @yield('admin')

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('admin.body.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Vector map-->
    <script src="{{ asset('backend/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <!--Swiper slider js-->
    <script src="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- Dashboard init -->
    <script src="{{ asset('backend/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <!-- Script para manejar el cambio de logos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verticalHoverBtn = document.getElementById('vertical-hover');
            const appMenu = document.querySelector('.app-menu');
            const logoSm = document.querySelectorAll('.logo-sm-img');
            const logoLg = document.querySelectorAll('.logo-lg-img');

            function updateLogos() {
                const isCollapsed = appMenu.classList.contains('hover') ||
                    document.body.classList.contains('sidebar-enable');

                logoSm.forEach(img => {
                    if (img) img.src = "{{ asset('backend/assets/images/logo.png') }}";
                });
                logoLg.forEach(img => {
                    if (img) img.src = isCollapsed ?
                        "{{ asset('backend/assets/images/logo.png') }}" :
                        "{{ asset('backend/assets/images/logo.png') }}";
                });
            }

            updateLogos();

            if (verticalHoverBtn) {
                verticalHoverBtn.addEventListener('click', function() {
                    setTimeout(updateLogos, 150);
                });
            }

            window.addEventListener('resize', updateLogos);

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateLogos();
                    }
                });
            });

            if (appMenu) {
                observer.observe(appMenu, {
                    attributes: true
                });
            }
        });
    </script>

    <script>
        // ===== MODO OSCURO AUTOMÁTICO =====
        (function() {
            const THEME_KEY = 'theme';
            const html = document.documentElement;
            
            function getStoredTheme() {
                return localStorage.getItem(THEME_KEY);
            }
            
            function applyTheme(theme) {
                const isDark = theme === 'dark';
                html.setAttribute('data-layout-mode', isDark ? 'dark' : 'light');
                updateThemeIcon(isDark);
                updateSidebarBackground(isDark);
            }
            
            function updateThemeIcon(isDark) {
                const icon = document.querySelector('#theme-toggle i');
                if (icon) {
                    icon.className = isDark ? 'bx bx-sun fs-22' : 'bx bx-moon fs-22';
                }
            }
            
            function updateSidebarBackground(isDark) {
                // Actualizar el sidebar en navegación
                const scrollbar = document.getElementById('scrollbar');
                const simplebarContent = document.querySelector('.simplebar-content');
                
                if (scrollbar) {
                    scrollbar.style.backgroundColor = isDark ? '#0f172a' : '';
                }
                if (simplebarContent) {
                    simplebarContent.style.backgroundColor = isDark ? '#0f172a' : '';
                }
                
                // Forzar actualización de simplebar si existe
                if (window.SimpleBar) {
                    const simplebarInstances = document.querySelectorAll('[data-simplebar]');
                    simplebarInstances.forEach(function(el) {
                        if (el.SimpleBar) {
                            el.SimpleBar.refresh();
                        }
                    });
                }
            }
            
            // Toggle manual y eventos
            document.addEventListener('DOMContentLoaded', function() {
                const toggleBtn = document.getElementById('theme-toggle');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        const currentTheme = html.getAttribute('data-layout-mode');
                        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                        applyTheme(newTheme);
                        localStorage.setItem(THEME_KEY, newTheme);
                    });
                }
                
                // Actualizar icono según tema actual
                const currentTheme = html.getAttribute('data-layout-mode');
                updateThemeIcon(currentTheme === 'dark');
                updateSidebarBackground(currentTheme === 'dark');
            });
            
            // También ejecutar cuando se navega ( MutationObserver para detectar cambios )
            function setupNavigationObserver() {
                let lastUrl = location.href;
                new MutationObserver(function() {
                    if (location.href !== lastUrl) {
                        lastUrl = location.href;
                        setTimeout(function() {
                            const currentTheme = html.getAttribute('data-layout-mode');
                            updateSidebarBackground(currentTheme === 'dark');
                        }, 100);
                    }
                }).observe(document.body, { childList: true, subtree: true });
            }
            
            if (document.body) {
                setupNavigationObserver();
            } else {
                document.addEventListener('DOMContentLoaded', setupNavigationObserver);
            }
        })();
    </script>

    @stack('script')

    {{-- Etiquetas para convertir tablas anchas en tarjetas en pantallas pequeñas --}}
    <script>
        (function() {
            function etiquetarTablas() {
                document.querySelectorAll('table.table-card-responsive').forEach(function(table) {
                    var headers = Array.prototype.map.call(table.querySelectorAll('thead th'), function(th) {
                        return (th.textContent || '').replace(/\s+/g, ' ').trim();
                    });

                    table.querySelectorAll('tbody tr').forEach(function(row) {
                        Array.prototype.forEach.call(row.children, function(cell, index) {
                            if (cell.tagName !== 'TD') return;
                            var label = headers[index] || '';
                            if (cell.getAttribute('data-label') !== label) {
                                cell.setAttribute('data-label', label);
                            }
                        });
                    });

                    table.querySelectorAll('tfoot tr').forEach(function(row) {
                        var columnIndex = 0;
                        Array.prototype.forEach.call(row.children, function(cell) {
                            if (cell.tagName !== 'TD') return;
                            var span = parseInt(cell.getAttribute('colspan') || '1', 10);
                            if (span === 1) {
                                var label = headers[columnIndex] || '';
                                if (cell.getAttribute('data-label') !== label) {
                                    cell.setAttribute('data-label', label);
                                }
                            }
                            columnIndex += span;
                        });
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                etiquetarTablas();
                new MutationObserver(etiquetarTablas).observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });
        })();
    </script>
</body>

</html>
