<?php

use App\Http\Controllers\Bedel\UsuarioController;
use App\Http\Controllers\Docente\AlumnoController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Consulta docente y administración exclusiva del bedel.
Route::middleware(['auth', 'rol:docente'])->prefix('docente/alumnos')->name('docente.alumnos.')->group(function () {
    Route::get('/', [AlumnoController::class, 'indice'])->name('indice');
    Route::get('/{usuario}', [AlumnoController::class, 'mostrar'])->name('mostrar');
});

foreach (['alumnos' => User::ROL_ALUMNO, 'docentes' => User::ROL_DOCENTE, 'bedeles' => User::ROL_BEDEL] as $seccion => $rol) {
    Route::middleware(['auth', 'rol:bedel'])->prefix('bedel/'.$seccion)->name('bedel.'.$seccion.'.')->group(function () use ($rol) {
        foreach ([
            ['get', '/', 'indice'], ['get', '/agregar', 'crear'], ['post', '/', 'guardar'],
            ['get', '/{usuario}', 'mostrar'], ['get', '/{usuario}/editar', 'editar'],
            ['patch', '/{usuario}', 'actualizar'], ['get', '/{usuario}/eliminar', 'confirmarEliminacion'],
            ['delete', '/{usuario}', 'eliminar'],
        ] as [$metodo, $uri, $accion]) {
            Route::$metodo($uri, [UsuarioController::class, $accion])->defaults('rolObjetivo', $rol)->name($accion);
        }
    });
}

require __DIR__.'/auth.php';
