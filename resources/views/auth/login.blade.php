<x-guest-layout>
    <h1 class="mb-6 text-2xl font-semibold text-black">Iniciar sesión</h1>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full focus:!border-black focus:!ring-black" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full focus:!border-black focus:!ring-black"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-neutral-300 text-black shadow-sm focus:ring-black" name="remember">
                <span class="ms-2 text-sm text-neutral-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-stretch gap-4 mt-6">
            @if (Route::has('password.request'))
                <a class="underline underline-offset-4 text-sm text-neutral-600 hover:text-black rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-black text-white border border-transparent rounded-md font-semibold text-sm hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                {{ __('Iniciar sesión') }}
            </button>
        </div>
        @if (Route::has('register'))
            <p class="mt-6 pt-5 border-t border-neutral-200 text-center text-sm text-neutral-600">
                ¿Todavía no tenés una cuenta?
                <a href="{{ route('register') }}" class="font-semibold text-black underline underline-offset-4 rounded focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">Registrarse</a>
            </p>
        @endif
    </form>
</x-guest-layout>
