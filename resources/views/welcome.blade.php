<x-guest-layout>
    <h1 class="text-2xl font-semibold text-black">Bienvenido/a</h1>
    <p class="mt-3 text-sm text-neutral-600">Accedé al Sistema de Gestión de Alumnos de la Tecnicatura Universitaria en Programación.</p>

    @if (Route::has('login'))
        <nav class="mt-6 flex flex-col gap-3" aria-label="Accesos al sistema">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-4 py-3 rounded-md bg-black text-white text-sm font-semibold hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                    Inicio
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-4 py-3 rounded-md bg-black text-white text-sm font-semibold hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                    Iniciar sesión
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-4 py-3 rounded-md border border-neutral-300 bg-white text-black text-sm font-semibold hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                        Registrarse
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</x-guest-layout>
