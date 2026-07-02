<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Ficção', 'Não Ficção', 'Técnico', 'Infantil', 'Biografia',
            'Fantasia', 'Suspense', 'História', 'Poesia', 'Autoajuda',
        ])->each(fn (string $nome) => Categoria::query()->firstOrCreate(['nome' => $nome]));
    }
}
