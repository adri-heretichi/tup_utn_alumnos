<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-black">Registro de Alumno</h1>
        <p class="text-sm text-gray-600 mt-1">Completá el formulario para crear tu cuenta de alumno.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nombre (Obligatorio) -->
        <div>
            <x-input-label for="name" value="Nombre (Obligatorio)" />
            <x-text-input id="name" class="block mt-1 w-full focus:!border-black focus:!ring-black" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej. Juan Pérez" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Correo electrónico Gmail (Obligatorio) -->
        <div class="mt-4">
            <x-input-label for="email" value="Correo electrónico - Gmail (Obligatorio)" />
            <x-text-input id="email" class="block mt-1 w-full focus:!border-black focus:!ring-black" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@gmail.com" />
            <p class="text-xs text-gray-500 mt-1">El correo debe ser una dirección obligatoria de Gmail (@gmail.com).</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña (Obligatoria) -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña (Obligatoria)" />
            <x-text-input id="password" class="block mt-1 w-full focus:!border-black focus:!ring-black"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar Contraseña (Obligatoria) -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña (Obligatoria)" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full focus:!border-black focus:!ring-black"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Teléfono (Opcional) -->
        <div class="mt-4">
            <x-input-label for="phone" value="Teléfono (Opcional)" />
            <x-text-input id="phone" class="block mt-1 w-full focus:!border-black focus:!ring-black" type="text" name="phone" :value="old('phone')" placeholder="Ej. +54 9 11 1234 5678" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Red Profesional (Opcional) -->
        <div class="mt-4">
            <x-input-label for="professional_url" value="Red profesional (Opcional)" />
            <x-text-input id="professional_url" class="block mt-1 w-full focus:!border-black focus:!ring-black" type="url" name="professional_url" :value="old('professional_url')" placeholder="https://linkedin.com/in/tu-perfil" />
            <p class="text-xs text-gray-500 mt-1">LinkedIn, GitHub, portfolio u otra red profesional.</p>
            <x-input-error :messages="$errors->get('professional_url')" class="mt-2" />
        </div>

        <!-- Foto de Perfil (Obligatoria) -->
        <div class="mt-4">
            <x-input-label for="photo" value="Foto de perfil (Obligatoria)" />
            <input id="photo" class="block mt-1 w-full min-w-0 text-sm text-neutral-900 border border-neutral-300 rounded-md cursor-pointer bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-black p-2 file:mr-2 file:border-0 file:rounded file:bg-white file:px-2 file:py-1 file:text-neutral-900" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" required />
            <p class="text-xs text-gray-500 mt-1">Formatos admitidos: JPG, JPEG, PNG, WEBP (Máx. 2 MB).</p>
            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-5 border-t border-neutral-200">
            <a class="underline underline-offset-4 text-sm text-neutral-600 hover:text-black rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black" href="{{ route('login') }}">
                ¿Ya tenés una cuenta? Iniciar sesión
            </a>

            <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-black text-white border border-transparent rounded-md font-semibold text-sm hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>
