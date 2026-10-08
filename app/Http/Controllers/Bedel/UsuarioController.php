<?php

namespace App\Http\Controllers\Bedel;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarUsuarioRequest;
use App\Models\User;
use App\Servicios\GestorUsuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsuarioController extends Controller
{
    private function contexto(Request $request): array
    {
        $rol = $request->route('rolObjetivo');
        $seccion = match ($rol) {
            User::ROL_ALUMNO => 'alumnos', User::ROL_DOCENTE => 'docentes', User::ROL_BEDEL => 'bedeles',
        };

        return ['titulo' => ucfirst($seccion), 'singular' => $rol, 'baseRuta' => 'bedel.'.$seccion, 'administrable' => true];
    }

    private function comprobar(Request $request, User $usuario, string $accion): void
    {
        abort_unless($usuario->rol === $request->route('rolObjetivo'), 404);
        Gate::authorize($accion, $usuario);
    }

    public function indice(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        return view('usuarios.indice', $this->contexto($request) + [
            'usuarios' => User::where('rol', $request->route('rolObjetivo'))->orderBy('name')->get(),
        ]);
    }

    public function crear(Request $request)
    {
        Gate::authorize('create', User::class);

        return view('bedel.usuarios.formulario', $this->contexto($request) + ['usuario' => new User]);
    }

    public function guardar(GuardarUsuarioRequest $request, GestorUsuarios $gestor)
    {
        Gate::authorize('create', User::class);
        $gestor->guardar($request, $request->route('rolObjetivo'));

        return redirect()->route($this->contexto($request)['baseRuta'].'.indice')->with('status', 'Usuario creado correctamente.');
    }

    public function mostrar(Request $request, User $usuario)
    {
        $this->comprobar($request, $usuario, 'view');

        return view('usuarios.mostrar', $this->contexto($request) + compact('usuario'));
    }

    public function editar(Request $request, User $usuario)
    {
        $this->comprobar($request, $usuario, 'update');

        return view('bedel.usuarios.formulario', $this->contexto($request) + compact('usuario'));
    }

    public function actualizar(GuardarUsuarioRequest $request, User $usuario, GestorUsuarios $gestor)
    {
        $this->comprobar($request, $usuario, 'update');
        $gestor->guardar($request, $request->route('rolObjetivo'), $usuario);

        return redirect()->route($this->contexto($request)['baseRuta'].'.mostrar', $usuario)->with('status', 'Datos actualizados correctamente.');
    }

    public function confirmarEliminacion(Request $request, User $usuario)
    {
        $this->comprobar($request, $usuario, 'delete');

        return view('bedel.usuarios.eliminar', $this->contexto($request) + compact('usuario'));
    }

    public function eliminar(Request $request, User $usuario, GestorUsuarios $gestor)
    {
        abort_unless($usuario->rol === $request->route('rolObjetivo'), 404);
        $gestor->eliminar($request->user(), $usuario);

        return redirect()->route($this->contexto($request)['baseRuta'].'.indice')->with('status', 'Usuario eliminado correctamente.');
    }
}
