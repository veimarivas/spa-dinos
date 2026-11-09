<x-auth-layout title="Verificar correo">
    <h1>Verifica tu correo</h1>
    <p class="sub">Antes de continuar, confirma tu dirección haciendo clic en el enlace que te enviamos. Si no lo recibiste, podemos enviarte otro.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-box" style="background:#eefaf3;border-color:#c9eeda;color:#1d7d5f;">
            <i class="ri-checkbox-circle-line"></i>
            <div><strong>Enlace enviado</strong>Te enviamos un nuevo enlace de verificación.</div>
        </div>
    @endif

    <div class="auth-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="submit">Reenviar verificación <span class="arrow">→</span></button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="link-button">Cerrar sesión</button>
        </form>
    </div>

    <x-slot:support>
        <a href="{{ route('login') }}">Volver a iniciar sesión</a>
    </x-slot:support>
</x-auth-layout>
