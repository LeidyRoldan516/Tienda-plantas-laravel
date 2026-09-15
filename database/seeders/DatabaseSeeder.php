<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@tienda.com'],
            [
                'name' => 'Administrador',
                'password' => 'password',
                'rol' => 'administrador',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'cliente@tienda.com'],
            [
                'name' => 'Cliente de prueba',
                'password' => 'password',
                'rol' => 'cliente',
            ]
        );
    }
}
