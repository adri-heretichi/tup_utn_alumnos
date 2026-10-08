<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold">{{ $usuario->exists ? 'Editar' : 'Agregar' }} {{ $singular }}</h1></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto px-4 sm:px-6">
        <section class="utn-card p-5 sm:p-8">
            <form method="POST" action="{{ $usuario->exists ? route($baseRuta.'.actualizar', $usuario) : route($baseRuta.'.guardar') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($usuario->exists) @method('PATCH') @endif
                <div><x-input-label for="name" value="Nombre completo" /><x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $usuario->name)" required autocomplete="name" /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label for="email" value="Correo Gmail" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $usuario->email)" required autocomplete="email" /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
                @unless($usuario->exists)
                    <div><x-input-label for="password" value="Contraseña" /><x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
                    <div><x-input-label for="password_confirmation" value="Confirmar contraseña" /><x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" /></div>
                @endunless
                <div><x-input-label for="phone" value="Teléfono (opcional)" /><x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $usuario->phone)" autocomplete="tel" /><x-input-error :messages="$errors->get('phone')" class="mt-2" /></div>
                <div><x-input-label for="professional_url" value="Red profesional (opcional)" /><x-text-input id="professional_url" name="professional_url" type="url" class="mt-1 block w-full" :value="old('professional_url', $usuario->professional_url)" /><x-input-error :messages="$errors->get('professional_url')" class="mt-2" /></div>
                <div>
                    @if($usuario->exists)<p class="text-sm mb-2">Fotografía actual</p><img src="{{ $usuario->obtenerUrlFoto() }}" alt="Foto de {{ $usuario->name }}" class="w-24 h-24 rounded-full object-cover border border-neutral-200 mb-4" />@endif
                    <x-input-label for="photo" :value="$usuario->exists ? 'Nueva fotografía (opcional)' : ($singular === 'alumno' ? 'Fotografía (obligatoria)' : 'Fotografía (opcional)')" />
                    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" @required(!$usuario->exists && $singular === 'alumno') class="mt-2 block w-full text-sm text-neutral-700" aria-describedby="photo-help" />
                    <p id="photo-help" class="text-sm text-neutral-600 mt-2">JPG, JPEG, PNG o WEBP. Máximo 2 MB.</p>
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>
                <div class="flex flex-wrap gap-3"><x-primary-button>{{ $usuario->exists ? 'Guardar cambios' : 'Crear '.$singular }}</x-primary-button><a class="utn-secondary" href="{{ route($baseRuta.'.indice') }}">Cancelar</a></div>
            </form>
        </section>
    </div></div>
</x-app-layout>
