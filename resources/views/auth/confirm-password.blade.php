<x-auth-layout title="Confirmar contraseña">
    <h1>Confirma tu contraseña</h1>
    <p class="sub">Esta es un área segura. Confirma tu contraseña para continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field">
            <label for="password">Contraseña</label>
            <div class="inp @error('password') has-error @enderror">
                <span class="ico"><i class="ri-lock-2-line"></i></span>
                <input id="password" type="password" name="password" placeholder="••••••••" required
                    autocomplete="current-password" autofocus>
                <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar contraseña">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            @error('password')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit">Confirmar <span class="arrow">→</span></button>
    </form>

    <x-slot:support>
        <a href="{{ route('login') }}">Volver a iniciar sesión</a>
    </x-slot:support>
</x-auth-layout>
