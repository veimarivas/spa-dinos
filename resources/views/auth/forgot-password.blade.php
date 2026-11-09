<x-auth-layout title="Recuperar contraseña">
    <h1>Recuperar contraseña</h1>
    <p class="sub">Escribe tu correo y te enviaremos un enlace para restablecerla.</p>

    @if (session('status'))
        <div class="alert-box" style="background:#eefaf3;border-color:#c9eeda;color:#1d7d5f;">
            <i class="ri-checkbox-circle-line"></i>
            <div><strong>Revisa tu correo</strong>Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="inp @error('email') has-error @enderror">
                <span class="ico"><i class="ri-mail-line"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    placeholder="tu@email.com" required autofocus autocomplete="username">
            </div>
            @error('email')
                <div class="error-text"><i class="ri-error-warning-line"></i>No pudimos enviar el enlace. Verifica que el correo esté registrado.</div>
            @enderror
        </div>

        <button type="submit" class="submit">Enviar enlace <span class="arrow">→</span></button>
    </form>

    <x-slot:support>
        <a href="{{ route('login') }}">Volver a iniciar sesión</a>
    </x-slot:support>
</x-auth-layout>
