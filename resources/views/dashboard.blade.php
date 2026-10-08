<x-app-layout>
    <x-slot name="header"><h1 class="font-semibold text-xl text-black">Inicio</h1></x-slot>
    <div class="py-8 sm:py-12"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="utn-card p-6 sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Sistema de Gestión de Alumnos</p>
            <h2 class="mt-3 text-2xl sm:text-3xl font-semibold text-black break-words">Bienvenido/a, {{ Auth::user()->name }}</h2>
            <p class="mt-5 font-medium text-neutral-900">Universidad Tecnológica Nacional</p>
            <p class="mt-1 text-sm text-neutral-600">Tecnicatura Universitaria en Programación – TUP</p>
        </section>
        <section aria-labelledby="accesos-title">
            <h2 id="accesos-title" class="mb-4 text-lg font-semibold">Accesos principales</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                @if(Auth::user()->esDocente())
                    <article class="utn-card p-6">
                        <h3 class="text-lg font-semibold">Alumnos</h3>
                        <p class="mt-2 text-sm text-neutral-600">Consultá la información de los alumnos registrados.</p>
                        <a class="utn-button mt-5" href="{{ route('docente.alumnos.indice') }}">Ver alumnos</a>
                    </article>
                @elseif(Auth::user()->esBedel())
                    @foreach(['alumnos' => 'alumno', 'docentes' => 'docente', 'bedeles' => 'bedel'] as $seccion => $singular)
                        <article class="utn-card p-6">
                            <h3 class="text-lg font-semibold">{{ ucfirst($seccion) }}</h3>
                            <p class="mt-2 text-sm text-neutral-600">Administrá los datos de {{ $seccion }} registrados.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <a class="utn-button" href="{{ route('bedel.'.$seccion.'.indice') }}">Ver {{ $seccion }}</a>
                                <a class="utn-secondary" href="{{ route('bedel.'.$seccion.'.crear') }}">Agregar {{ $singular }}</a>
                            </div>
                        </article>
                    @endforeach
                @endif
                <article class="utn-card p-6">
                    <h3 class="text-lg font-semibold">Mi Perfil</h3>
                    <p class="mt-2 text-sm text-neutral-600">{{ Auth::user()->esAlumno() ? 'Consultá tu información personal registrada en el sistema.' : 'Consultá tu información personal.' }}</p>
                    <a class="utn-button mt-5" href="{{ route('profile.show') }}">Ver mi perfil</a>
                </article>
                @if(Auth::user()->esAlumno())
                    <article class="utn-card p-6">
                        <h3 class="text-lg font-semibold">Mi Tarjeta</h3>
                        <div class="mt-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <img src="{{ Auth::user()->obtenerUrlFoto() }}" alt="Foto de {{ Auth::user()->name }}" class="w-24 h-24 rounded-full object-cover border border-neutral-200 shrink-0" />
                            <div class="min-w-0">
                                <p class="text-lg font-semibold break-words">{{ Auth::user()->name }}</p>
                                <p class="mt-1 text-sm text-neutral-600 break-all">{{ Auth::user()->email }}</p>
                                <span class="mt-3 inline-block rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1 text-xs font-semibold">Alumno</span>
                            </div>
                        </div>
                    </article>
                @endif
            </div>
        </section>
    </div></div>
</x-app-layout>
