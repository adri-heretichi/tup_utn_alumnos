<x-guest-layout>
    <h1 class="mb-4 text-2xl font-semibold text-black">Recuperar contraseña</h1>
    <div class="mb-4 text-sm text-neutral-600">
        {{ __('¿Olvidaste tu contraseña? Ingresá tu correo electrónico y te enviaremos un enlace para restablecerla.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex mt-6">
            <x-primary-button class="w-full justify-center text-center">
                {{ __('Enviar enlace para restablecer contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
