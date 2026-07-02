<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->randomElement([
                'Ficção', 'Não Ficção', 'Técnico', 'Infantil', 'Biografia',
                'Fantasia', 'Suspense', 'História', 'Poesia', 'Autoajuda',
                'Ciência', 'Quadrinhos',
            ]),
        ];
    }
}
