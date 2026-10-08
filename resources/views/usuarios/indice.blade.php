<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-black">{{ $titulo }}</h1></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <section class="utn-card p-4 sm:p-6 min-w-0">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <h2 class="text-lg font-semibold">{{ $titulo }} registrados</h2>
                @if($administrable)<a class="utn-button" href="{{ route($baseRuta.'.crear') }}">Agregar {{ $singular }}</a>@endif
            </div>
            @if(session('status'))<p role="status" class="mb-4 text-neutral-700">{{ session('status') }}</p>@endif
            @if($usuarios->isEmpty())
                <p class="py-8 text-neutral-600">No hay registros en esta sección.</p>
            @else
                <div class="overflow-x-auto border border-neutral-200 rounded-lg" tabindex="0" role="region" aria-label="Listado de {{ strtolower($titulo) }}, desplazable horizontalmente">
                    <table class="min-w-full text-sm text-left divide-y divide-neutral-200">
                        <thead class="bg-black text-white"><tr>
                            @foreach(['Foto', 'Nombre completo', 'Gmail', 'Teléfono', 'WhatsApp', 'Red profesional', 'Fecha de registro', 'Acciones'] as $columna)
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">{{ $columna }}</th>
                            @endforeach
                        </tr></thead>
                        <tbody class="divide-y divide-neutral-200">
                            @foreach($usuarios as $usuario)
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-4 py-4"><img src="{{ $usuario->obtenerUrlFoto() }}" alt="Foto de {{ $usuario->name }}" class="w-12 h-12 max-w-none object-cover rounded-full border border-neutral-200" /></td>
                                    <td class="px-4 py-4 min-w-40">{{ $usuario->name }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $usuario->email }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $usuario->phone ?: 'No informado' }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($usuario->obtenerEnlaceWhatsApp())<a class="text-emerald-700 underline" href="{{ $usuario->obtenerEnlaceWhatsApp() }}" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a>@else No informado @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($usuario->professional_url)<a class="utn-link" href="{{ $usuario->professional_url }}" target="_blank" rel="noopener noreferrer">Ver red profesional</a>@else No informada @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-4"><div class="flex gap-3 whitespace-nowrap">
                                        <a class="utn-link" href="{{ route($baseRuta.'.mostrar', $usuario) }}">Ver detalle</a>
                                        @if($administrable)
                                            <a class="utn-link" href="{{ route($baseRuta.'.editar', $usuario) }}">Editar</a>
                                            @can('delete', $usuario)<a class="text-red-700 underline" href="{{ route($baseRuta.'.confirmarEliminacion', $usuario) }}">Eliminar</a>@endcan
                                        @endif
                                    </div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div></div>
</x-app-layout>
