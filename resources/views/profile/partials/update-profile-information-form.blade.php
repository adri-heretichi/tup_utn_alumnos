<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900">
            {{ __('Información Personal') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600">
            {{ __('Actualizá tu información personal, correo electrónico, teléfono, red profesional y fotografía de perfil.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Nombre -->
        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:!border-black focus:!ring-black" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Correo electrónico Gmail -->
        <div>
            <x-input-label for="email" value="Correo electrónico - Gmail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:!border-black focus:!ring-black" :value="old('email', $user->email)" required autocomplete="username" />
            <p class="text-xs text-neutral-500 mt-1">El correo debe ser una dirección obligatoria de Gmail (@gmail.com).</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-neutral-800">
                        {{ __('Tu dirección de correo no está verificada.') }}

                        <button form="send-verification" class="underline text-sm text-neutral-600 hover:text-neutral-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            {{ __('Hacé clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Se envió un nuevo enlace de verificación a tu dirección de correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Teléfono -->
        <div>
            <x-input-label for="phone" value="Teléfono" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full focus:!border-black focus:!ring-black" :value="old('phone', $user->phone)" placeholder="Ej. +54 9 11 1234 5678" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Red Profesional -->
        <div>
            <x-input-label for="professional_url" value="Red profesional" />
            <x-text-input id="professional_url" name="professional_url" type="url" class="mt-1 block w-full focus:!border-black focus:!ring-black" :value="old('professional_url', $user->professional_url)" placeholder="https://linkedin.com/in/tu-perfil" />
            <p class="text-xs text-neutral-500 mt-1">LinkedIn, GitHub, portfolio u otra red profesional.</p>
            <x-input-error class="mt-2" :messages="$errors->get('professional_url')" />
        </div>

        <!-- Fotografía de Perfil -->
        <div>
            <x-input-label for="photo" value="Seleccionar nueva fotografía (opcional)" />
            <div class="flex flex-wrap items-center gap-4 mt-2 mb-3">
                <img src="{{ $user->obtenerUrlFoto() }}" alt="Foto actual" class="w-16 h-16 shrink-0 rounded-full object-cover border border-neutral-200 shadow-sm" />
                <span class="text-xs text-neutral-500">Fotografía actual</span>
            </div>
            <input id="photo" class="block mt-1 w-full text-sm text-neutral-900 border border-neutral-300 rounded-md cursor-pointer bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-black p-2 file:mr-2 file:border-0 file:rounded file:bg-white file:px-2 file:py-1 file:text-neutral-900" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" />
            <p class="text-xs text-neutral-500 mt-1">Opcional en edición. Si no seleccionás una nueva foto, se conservará la actual (Formatos: JPG, JPEG, PNG, WEBP, Máx 2 MB).</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <x-primary-button class="!bg-black hover:!bg-neutral-800 focus:!bg-neutral-800 active:!bg-black focus:!ring-black">Guardar cambios</x-primary-button>

            @if (session('status') === 'Perfil actualizado correctamente.' || session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600"
                >Perfil actualizado correctamente.</p>
            @endif
        </div>
    </form>
</section>
