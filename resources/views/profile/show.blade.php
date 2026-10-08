<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="utn-card p-5 sm:p-8">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-neutral-900">
                                {{ __('Información Personal') }}
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600">
                                {{ __('Datos registrados de tu cuenta de usuario.') }}
                            </p>
                        </header>

                        <!-- Fotografía de Perfil -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <img src="{{ $user->obtenerUrlFoto() }}" alt="Foto de {{ $user->name }}" class="w-24 h-24 shrink-0 rounded-full object-cover border border-neutral-200 shadow-sm" />
                            <div>
                                <h3 class="text-xl font-semibold text-black break-words">{{ $user->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800 border border-neutral-200">
                                    {{ $user->nombreRol() }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-neutral-200 pt-4 space-y-4">
                            <!-- Correo electrónico -->
                            <div>
                                <span class="block text-sm font-medium text-neutral-500">Correo electrónico Gmail:</span>
                                <span class="block text-base text-neutral-900 font-semibold break-all">{{ $user->email }}</span>
                            </div>

                            <!-- Teléfono y WhatsApp -->
                            <div>
                                <span class="block text-sm font-medium text-neutral-500">Teléfono:</span>
                                @if($user->phone)
                                    <div class="flex flex-wrap items-center gap-3 mt-1">
                                        <span class="text-base text-neutral-900 font-semibold break-all">{{ $user->phone }}</span>
                                        @if($user->obtenerEnlaceWhatsApp())
                                            <a href="{{ $user->obtenerEnlaceWhatsApp() }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-md hover:bg-emerald-700 transition">
                                                <svg class="w-4 h-4 shrink-0 me-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                Abrir WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="block text-base text-neutral-400 italic">No informado</span>
                                @endif
                            </div>

                            <!-- Red Profesional -->
                            <div>
                                <span class="block text-sm font-medium text-neutral-500">Red profesional:</span>
                                @if($user->professional_url)
                                    <a href="{{ $user->professional_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-w-0 max-w-full text-base text-black hover:text-neutral-600 underline underline-offset-4 font-semibold mt-1 rounded focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                                        <svg class="w-4 h-4 shrink-0 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        <span class="min-w-0 break-all">{{ $user->professional_url }}</span>
                                    </a>
                                @else
                                    <span class="block text-base text-neutral-400 italic">No informada</span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 border-t border-neutral-100 flex items-center justify-start">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-800 active:bg-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150">
                                Editar Perfil
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
