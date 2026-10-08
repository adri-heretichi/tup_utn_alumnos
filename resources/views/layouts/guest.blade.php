<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sistema de Gestión de Alumnos - TUP</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-neutral-900 antialiased bg-neutral-50">
        <main class="min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:py-12">
            <div class="w-full max-w-lg px-6 py-8 sm:p-10 bg-white border border-neutral-200 shadow-sm rounded-xl">
                <header class="mb-6 pb-6 border-b border-neutral-200 text-center">
                    <a href="/" class="inline-block max-w-full rounded focus:outline-none focus:ring-2 focus:ring-black" aria-label="Inicio del Sistema de Gestión de Alumnos">
                        <x-application-logo class="block w-52 h-auto mx-auto" />
                    </a>
                    <p class="mt-4 text-base font-semibold text-black">Universidad Tecnológica Nacional</p>
                    <p class="mt-1 text-sm text-neutral-600">Tecnicatura Universitaria en Programación – TUP</p>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-neutral-500">Sistema de Gestión de Alumnos</p>
                </header>
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
