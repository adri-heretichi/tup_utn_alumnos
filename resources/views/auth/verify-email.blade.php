<x-guest-layout>
    <h1 class="mb-4 text-2xl font-semibold text-black">Verificar correo electrónico</h1>
    <div class="mb-4 text-sm text-neutral-600">
        {{ __('Para continuar, verificá tu correo electrónico mediante el enlace que te enviamos. Si no recibiste el mensaje, podés solicitar otro correo de verificación.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Enviamos un nuevo enlace de verificación al correo electrónico que ingresaste al registrarte.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="w-full justify-center text-center">
                    {{ __('Reenviar correo de verificación') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline underline-offset-4 text-sm text-neutral-600 hover:text-neutral-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                {{ __('Cerrar sesión') }}
            </button>
        </form>
    </div>
</x-guest-layout>
