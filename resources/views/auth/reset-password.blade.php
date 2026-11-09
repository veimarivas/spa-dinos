<x-auth-layout title="Restablecer contraseña">
    <h1>Nueva contraseña</h1>
    <p class="sub">Elige una contraseña segura para volver a tu cuenta.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="inp @error('email') has-error @enderror">
                <span class="ico"><i class="ri-mail-line"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                    placeholder="tu@email.com" required autofocus autocomplete="username">
            </div>
            @error('email')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Nueva contraseña</label>
            <div class="inp @error('password') has-error @enderror">
                <span class="ico"><i class="ri-lock-2-line"></i></span>
                <input id="password" type="password" name="password" placeholder="Mínimo 8 caracteres"
                    required autocomplete="new-password">
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
            <div class="inp @error('password_confirmation') has-error @enderror">
                <span class="ico"><i class="ri-lock-2-line"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Repite la contraseña" required autocomplete="new-password">
                <button type="button" class="password-toggle" data-target="password_confirmation"
                    aria-label="Mostrar contraseña"><i class="ri-eye-line"></i></button>
            </div>
            @error('password_confirmation')
                <div class="error-text"><i class="ri-error-warning-line"></i>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit">Guardar contraseña <span class="arrow">→</span></button>
    </form>

    <x-slot:support>
        <a href="{{ route('login') }}">Volver a iniciar sesión</a>
    </x-slot:support>
</x-auth-layout>
