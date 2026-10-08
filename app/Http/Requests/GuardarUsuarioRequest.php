<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class GuardarUsuarioRequest extends RegistrarAlumnoRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esBedel() === true;
    }

    public function rules(): array
    {
        $reglas = parent::rules();
        $usuario = $this->route('usuario');
        $reglas['email'] = ['required', 'string', 'lowercase', 'email', 'max:255',
            Rule::unique('users')->ignore($usuario instanceof User ? $usuario->id : null), 'ends_with:@gmail.com'];
        $obligatoria = ! $usuario && $this->route('rolObjetivo') === User::ROL_ALUMNO;
        $reglas['photo'][0] = $obligatoria ? 'required' : 'nullable';
        if ($usuario) {
            unset($reglas['password']);
        }

        return $reglas;
    }
}
