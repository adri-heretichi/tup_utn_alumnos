<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrarAlumnoRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegistrarAlumnoRequest $request): RedirectResponse
    {
        // Almacenamos la foto de perfil en el disco public (folder 'perfiles')
        $rutaFoto = $request->file('photo')->store('perfiles', 'public');

        try {
            // El registro público nunca recibe privilegios desde la petición.
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'professional_url' => $request->professional_url,
                'photo_path' => $rutaFoto,
            ]);
            $user->rol = User::ROL_ALUMNO;
            $user->is_admin = false;
            $user->save();
        } catch (\Throwable $e) {
            // Si falla la creación del usuario, eliminamos la imagen guardada para evitar archivos huérfanos
            if ($rutaFoto && Storage::disk('public')->exists($rutaFoto)) {
                Storage::disk('public')->delete($rutaFoto);
            }
            throw $e;
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
