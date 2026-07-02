<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::all();

        collect(range(1, 20))->each(
            fn () => Livro::factory()->create(['categoria_id' => $categorias->random()->id])
        );
    }
}
