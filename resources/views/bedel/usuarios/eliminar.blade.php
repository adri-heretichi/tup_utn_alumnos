<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold">Eliminar {{ $singular }}</h1></x-slot>
    <div class="py-8"><div class="max-w-2xl mx-auto px-4 sm:px-6">
        <section class="utn-card p-6 sm:p-8 space-y-5">
            <h2 class="text-lg font-semibold">¿Estás seguro de que querés eliminar a este {{ $singular }}?</h2>
            <p class="break-words">{{ $usuario->name }} · {{ $usuario->email }}</p>
            <p class="text-neutral-600">Esta acción es permanente y eliminará también su fotografía almacenada.</p>
            <form method="POST" action="{{ route($baseRuta.'.eliminar', $usuario) }}" class="flex flex-wrap gap-3">
                @csrf @method('DELETE')
                <a class="utn-secondary" href="{{ route($baseRuta.'.mostrar', $usuario) }}">Cancelar</a>
                <x-danger-button>Eliminar</x-danger-button>
            </form>
        </section>
    </div></div>
</x-app-layout>
