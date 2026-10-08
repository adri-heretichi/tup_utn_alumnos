<?php

namespace App\Servicios;

use App\Http\Requests\GuardarUsuarioRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class GestorUsuarios
{
    public function guardar(GuardarUsuarioRequest $request, string $rol, ?User $usuario = null): User
    {
        $nuevo = $usuario === null;
        $usuario ??= new User;
        $anterior = $usuario->photo_path;
        $foto = $request->file('photo')?->store('perfiles', 'public');
        try {
            $usuario->fill($request->safe()->only(['name', 'email', 'phone', 'professional_url']));
            if ($nuevo) {
                $usuario->rol = $rol;
                $usuario->is_admin = $rol !== User::ROL_ALUMNO;
                $usuario->password = $request->validated('password');
            }
            if ($usuario->isDirty('email')) {
                $usuario->email_verified_at = null;
            }
            if ($foto) {
                $usuario->photo_path = $foto;
            }
            $usuario->save();
        } catch (\Throwable $error) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }
            throw $error;
        }
        if ($foto && $anterior) {
            Storage::disk('public')->delete($anterior);
        }

        return $usuario;
    }

    public function eliminar(User $actor, User $usuario): void
    {
        $foto = DB::transaction(function () use ($actor, $usuario) {
            // Serializa eliminaciones de bedeles y vuelve a comprobar al actor dentro del bloqueo.
            $bedeles = User::where('rol', User::ROL_BEDEL)->orderBy('id')->lockForUpdate()->get();
            $actorActual = $bedeles->firstWhere('id', $actor->id);
            abort_unless($actorActual, 403, 'Solamente un bedel puede eliminar usuarios.');
            $actual = User::whereKey($usuario->id)->lockForUpdate()->firstOrFail();
            abort_if($actual->is($actorActual), 403, 'No podés eliminar tu propia cuenta.');
            abort_if($actual->esBedel() && $bedeles->count() <= 1, 403, 'No se puede eliminar al último bedel.');
            Gate::forUser($actorActual)->authorize('delete', $actual);
            $foto = $actual->photo_path;
            $actual->delete();

            return $foto;
        }, 3);
        if ($foto) {
            Storage::disk('public')->delete($foto);
        }
    }
}
