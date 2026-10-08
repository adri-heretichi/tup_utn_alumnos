@php
    $enlaces = [['Inicio', 'dashboard', 'dashboard']];
    if (Auth::user()->esDocente()) {
        $enlaces[] = ['Alumnos', 'docente.alumnos.indice', 'docente.alumnos.*'];
    }
    if (Auth::user()->esBedel()) {
        foreach (['alumnos' => 'Alumnos', 'docentes' => 'Docentes', 'bedeles' => 'Bedeles'] as $seccion => $nombre) {
            $enlaces[] = [$nombre, 'bedel.'.$seccion.'.indice', 'bedel.'.$seccion.'.*'];
        }
    }
    $enlaces[] = ['Mi Perfil', 'profile.show', 'profile.*'];
@endphp
<nav x-data="{ open: false }" class="utn-navigation bg-white border-b border-neutral-200" aria-label="Navegación principal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 py-4">
            <a href="{{ route('dashboard') }}" class="flex items-center min-w-0 gap-3 rounded focus:ring-2 focus:ring-black">
                <x-application-logo class="w-20 sm:w-24 h-auto shrink-0" />
                <span class="min-w-0 max-w-xs">
                    <span class="block text-xs sm:text-sm font-semibold text-black">Universidad Tecnológica Nacional</span>
                    <span class="block mt-1 text-xs text-neutral-600">Tecnicatura Universitaria en Programación – TUP</span>
                </span>
            </a>
            <div class="hidden xl:flex gap-5 items-center shrink-0">
                @foreach($enlaces as [$nombre, $ruta, $patron])
                    <a href="{{ route($ruta) }}" @if(request()->routeIs($patron)) aria-current="page" @endif class="py-3 text-sm whitespace-nowrap border-b-2 {{ request()->routeIs($patron) ? 'border-black text-black font-semibold' : 'border-transparent text-neutral-600 hover:text-black' }} focus:outline-none focus:ring-2 focus:ring-black">{{ $nombre }}</a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="utn-secondary" type="submit">Cerrar sesión</button></form>
            </div>
            <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="utn-mobile-menu" aria-label="Abrir o cerrar menú" class="xl:hidden shrink-0 p-2 border border-neutral-300 rounded focus:ring-2 focus:ring-black">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>
    </div>
    <div id="utn-mobile-menu" :class="{ 'hidden': !open }" class="hidden xl:hidden border-t border-neutral-200 px-4 py-3 space-y-1">
        <p class="text-sm text-neutral-600 break-words py-2">{{ Auth::user()->name }} · {{ Auth::user()->nombreRol() }}</p>
        @foreach($enlaces as [$nombre, $ruta, $patron])
            <a href="{{ route($ruta) }}" @if(request()->routeIs($patron)) aria-current="page" @endif class="block px-3 py-3 rounded text-sm {{ request()->routeIs($patron) ? 'bg-neutral-100 font-semibold text-black' : 'text-neutral-700 hover:bg-neutral-50' }}">{{ $nombre }}</a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full text-left px-3 py-3 text-sm text-black">Cerrar sesión</button></form>
    </div>
</nav>
