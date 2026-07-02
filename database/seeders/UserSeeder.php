<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin Biblioteca',
            'email' => 'admin@biblioteca.com',
            'password' => 'password',
        ]);

        User::factory()->leitor()->create([
            'name' => 'Leitor Teste',
            'email' => 'leitor@biblioteca.com',
            'password' => 'password',
        ]);

        User::factory()->leitor()->create([
            'name' => 'Maria Leitora',
            'email' => 'maria@biblioteca.com',
            'password' => 'password',
        ]);

        User::factory()->count(5)->leitor()->create();
    }
}
