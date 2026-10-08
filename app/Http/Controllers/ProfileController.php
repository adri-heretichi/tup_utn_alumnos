<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la vista del perfil propio.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Verificamos la autorización explícita mediante UsuarioPolicy@update
        Gate::authorize('update', $user);

        $fotoAnterior = $user->photo_path;
        $nuevaFoto = null;

        if ($request->hasFile('photo')) {
            $nuevaFoto = $request->file('photo')->store('perfiles', 'public');
            $user->photo_path = $nuevaFoto;
        }

        $user->fill($request->safe()->only(['name', 'email', 'phone', 'professional_url']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        try {
            $user->save();
        } catch (\Throwable $e) {
            // En caso de error en el guardado, eliminamos la foto recién cargada para evitar archivos huérfanos
            if ($nuevaFoto && Storage::disk('public')->exists($nuevaFoto)) {
                Storage::disk('public')->delete($nuevaFoto);
            }
            throw $e;
        }

        // Si se guardó exitosamente y había una foto previa distinta, la eliminamos
        if ($nuevaFoto && $fotoAnterior && $fotoAnterior !== $nuevaFoto && Storage::disk('public')->exists($fotoAnterior)) {
            Storage::disk('public')->delete($fotoAnterior);
        }

        return Redirect::route('profile.edit')->with('status', 'Perfil actualizado correctamente.');
    }
}
