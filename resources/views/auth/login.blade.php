<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ingresar | Dino Baby Spa</title>
    <meta name="description" content="Sistema de gestión Dino Baby Spa - Cuidado y bienestar para tu bebé">
    <meta name="author" content="Dino Baby Spa">
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

                <h1>Bienvenido de nuevo</h1>
                <p class="sub">Ingresa a tu cuenta para acompañar sus primeros pasos.</p>

                @if (session('status'))
                    <div class="alert-box" style="background:#eefaf3;border-color:#c9eeda;color:#1d7d5f;">
                        <i class="ri-checkbox-circle-line"></i>
                        <div><strong>Listo</strong>{{ session('status') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                    @csrf

                    <div class="field">
                        <label for="email">Correo electrónico</label>
                        <div class="inp @error('email') has-error @enderror">
                            <span class="ico"><i class="ri-mail-line"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="tu@email.com" required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                            <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Contraseña</label>
                        <div class="inp @error('password') has-error @enderror">
                            <span class="ico"><i class="ri-lock-2-line"></i></span>
                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePassword"
                                aria-label="Mostrar contraseña">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <label class="remember">
                            <input type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            Recordarme
                        </label>
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="submit">
                        Ingresar al sistema <span class="arrow">→</span>
                    </button>
                </form>

                <div class="support">
                    ¿Necesitas ayuda? <a href="#">Contacta a soporte</a>
                </div>
                <div class="legal">© {{ date('Y') }} Dino Baby Spa · Todos los derechos reservados</div>
            </section>
        </div>
    </main>

    <div class="hint">Mueve el mouse para inclinar la escena</div>
    <div id="loader"><div class="loader-dino">🦕</div></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.getElementById('togglePassword');
            var password = document.getElementById('password');
            if (toggle && password) {
                toggle.addEventListener('click', function () {
                    var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    var icon = this.querySelector('i');
                    icon.className = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
                    this.setAttribute('aria-label', type === 'password' ? 'Mostrar contraseña' : 'Ocultar contraseña');
                });
            }
        });

        // La tarjeta siempre debe ser usable aunque el 3D tarde o falle.
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
