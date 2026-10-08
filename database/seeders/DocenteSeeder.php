<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscamos si ya existe el usuario docente por su correo o instanciamos uno nuevo
        $docente = User::firstOrNew(['email' => 'docente1@gmail.com']);

        // Asignación explícita sobre el modelo de forma segura
        $docente->name = 'Docente 1';
        $docente->password = Hash::make('Docente123!');
        $docente->is_admin = true;
        $docente->rol = User::ROL_DOCENTE;
        $docente->email_verified_at = now();
        $docente->save();
    }
}
