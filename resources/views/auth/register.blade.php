<x-auth-layout title="Crear cuenta">
    <h1>Crear cuenta</h1>
    <p class="sub">Registra tus datos para comenzar a usar Dino Baby Spa.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name">Nombre</label>
            <div class="inp @error('name') has-error @enderror">
                <span class="ico"><i class="ri-user-line"></i></span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Tu nombre"
                    required autofocus autocomplete="name">
            </div>
            @error('name')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="inp @error('email') has-error @enderror">
                <span class="ico"><i class="ri-mail-line"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    placeholder="tu@email.com" required autocomplete="username">
            </div>
            @error('email')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Contraseña</label>
            <div class="inp @error('password') has-error @enderror">
                <span class="ico"><i class="ri-lock-2-line"></i></span>
                <input id="password" type="password" name="password" placeholder="Mínimo 8 caracteres" required
                    autocomplete="new-password">
                <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar contraseña">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            @error('password')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmar contraseña</label>
            <div class="inp">
                <span class="ico"><i class="ri-lock-2-line"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Repite la contraseña" required autocomplete="new-password">
                <button type="button" class="password-toggle" data-target="password_confirmation"
                    aria-label="Mostrar contraseña"><i class="ri-eye-line"></i></button>
            </div>
        </div>

        <button type="submit" class="submit">Crear cuenta <span class="arrow">→</span></button>
    </form>

    <x-slot:support>
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
    </x-slot:support>
</x-auth-layout>
