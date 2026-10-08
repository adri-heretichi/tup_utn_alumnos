<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BedelSeeder extends Seeder
{
    public function run(): void
    {
        $bedel = User::firstOrNew(['email' => 'bedel1@gmail.com']);
        $bedel->name = 'Bedel 1';
        $bedel->password = Hash::make('Bedel123!');
        $bedel->rol = User::ROL_BEDEL;
        $bedel->is_admin = true;
        $bedel->email_verified_at = now();
        $bedel->save();
    }
}
