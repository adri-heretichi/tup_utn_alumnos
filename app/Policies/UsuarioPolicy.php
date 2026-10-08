<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UsuarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->esDocente() || $user->esBedel();
    }

    public function view(User $user, User $model): bool
    {
        return $user->is($model) || $user->esBedel() || ($user->esDocente() && $model->esAlumno());
    }

    public function update(User $user, User $model): bool
    {
        return $user->is($model) || $user->esBedel();
    }

    public function create(User $user): bool
    {
        return $user->esBedel();
    }

    public function delete(User $user, User $model): Response
    {
        if (! $user->esBedel()) {
            return Response::deny('Solamente un bedel puede eliminar usuarios.');
        }
        if ($user->is($model)) {
            return Response::deny('No podés eliminar tu propia cuenta.');
        }
        if ($model->esBedel() && User::where('rol', User::ROL_BEDEL)->count() <= 1) {
            return Response::deny('No se puede eliminar al último bedel.');
        }

        return Response::allow();
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
