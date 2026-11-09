@props(['title' => 'Dino Baby Spa'])

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Dino Baby Spa</title>
    <meta name="description" content="Sistema de gestión Dino Baby Spa">
    <meta name="theme-color" content="#0f3b43">

    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/login-3d.css') }}?v={{ filemtime(public_path('backend/assets/css/login-3d.css')) }}" rel="stylesheet" type="text/css" />
</head>

<body class="dino-login">
    <canvas id="scene" aria-hidden="true"></canvas>
    <div class="vignette" aria-hidden="true"></div>

    <main class="stage">
        <div class="card-wrap">
            <section class="card">
                <div class="logo">
                    <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Dino Baby Spa">
                </div>

                {{ $slot }}

                <div class="support">
                    {{ $support ?? '' }}
                </div>
                <div class="legal">© {{ date('Y') }} Dino Baby Spa · Todos los derechos reservados</div>
            </section>
        </div>
    </main>

    <div class="hint">Mueve el mouse para inclinar la escena</div>
    <div id="loader"><div class="loader-dino">🦕</div></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.password-toggle').forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    var input = document.getElementById(this.dataset.target || 'password');
                    if (!input) return;
                    var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    var icon = this.querySelector('i');
                    if (icon) icon.className = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
                });
            });
        });
        window.setTimeout(function () {
            var loader = document.getElementById('loader');
            if (loader && !window.__dinoSceneReady) {
                loader.classList.add('hide');
                window.setTimeout(function () { loader.remove(); }, 700);
            }
        }, 6500);
    </script>
    <script type="module" src="{{ asset('backend/assets/js/login-3d.js') }}?v={{ filemtime(public_path('backend/assets/js/login-3d.js')) }}"></script>
</body>

</html>
