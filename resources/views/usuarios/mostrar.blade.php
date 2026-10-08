<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-black">{{ $titulo }} · Detalle</h1></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto px-4 sm:px-6">
        <section class="utn-card p-5 sm:p-8 space-y-6">
            @if(session('status'))<p role="status">{{ session('status') }}</p>@endif
            <div class="flex flex-col sm:flex-row gap-5 sm:items-center">
                <img src="{{ $usuario->obtenerUrlFoto() }}" alt="Foto de {{ $usuario->name }}" class="w-24 h-24 rounded-full object-cover border border-neutral-200" />
                <div class="min-w-0"><h2 class="text-2xl font-semibold break-words">{{ $usuario->name }}</h2><p class="text-neutral-600">{{ $usuario->nombreRol() }}</p></div>
            </div>
            <dl class="grid sm:grid-cols-2 gap-5 border-t border-neutral-200 pt-6">
                <div><dt class="text-sm text-neutral-600">Correo Gmail</dt><dd class="break-all mt-1">{{ $usuario->email }}</dd></div>
                <div><dt class="text-sm text-neutral-600">Teléfono</dt><dd class="break-all mt-1">{{ $usuario->phone ?: 'No informado' }}</dd></div>
                <div><dt class="text-sm text-neutral-600">WhatsApp</dt><dd class="mt-1">
                    @if($usuario->obtenerEnlaceWhatsApp())<a class="text-emerald-700 underline" href="{{ $usuario->obtenerEnlaceWhatsApp() }}" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a>@else No informado @endif
                </dd></div>
                <div class="min-w-0"><dt class="text-sm text-neutral-600">Red profesional</dt><dd class="mt-1 break-all">
                    @if($usuario->professional_url)<a class="utn-link" href="{{ $usuario->professional_url }}" target="_blank" rel="noopener noreferrer">{{ $usuario->professional_url }}</a>@else No informada @endif
                </dd></div>
                <div><dt class="text-sm text-neutral-600">Fecha de registro</dt><dd class="mt-1">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd></div>
            </dl>
            <div class="flex flex-wrap gap-3 border-t border-neutral-200 pt-6">
                <a class="utn-secondary" href="{{ route($baseRuta.'.indice') }}">Volver al listado</a>
                @if($administrable)
                    <a class="utn-button" href="{{ route($baseRuta.'.editar', $usuario) }}">Editar</a>
                    @can('delete', $usuario)<a class="utn-secondary text-red-700" href="{{ route($baseRuta.'.confirmarEliminacion', $usuario) }}">Eliminar</a>@endcan
                @endif
            </div>
        </section>
    </div></div>
</x-app-layout>
