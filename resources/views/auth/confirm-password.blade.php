<x-guest-layout>
    <h1 class="mb-4 text-2xl font-semibold text-black">Confirmar contraseña</h1>
    <div class="mb-4 text-sm text-neutral-600">
        {{ __('Esta es un área segura. Confirmá tu contraseña antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex mt-6">
            <x-primary-button class="w-full justify-center text-center">
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
