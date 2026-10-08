<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    public function indice(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('usuarios.indice', [
            'usuarios' => User::where('rol', User::ROL_ALUMNO)->orderBy('name')->get(),
            'titulo' => 'Alumnos', 'singular' => 'alumno', 'baseRuta' => 'docente.alumnos', 'administrable' => false,
        ]);
    }

    public function mostrar(User $usuario): View
    {
        abort_unless($usuario->esAlumno(), 403);
        Gate::authorize('view', $usuario);

        return view('usuarios.mostrar', [
            'usuario' => $usuario, 'titulo' => 'Alumnos', 'baseRuta' => 'docente.alumnos', 'administrable' => false,
        ]);
    }
}
