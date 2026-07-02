<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantidadeTotal = fake()->numberBetween(1, 5);

        return [
            'titulo' => ucfirst(fake()->words(fake()->numberBetween(2, 5), true)),
            'autor' => fake()->name(),
            'categoria_id' => Categoria::factory(),
            'isbn' => fake()->unique()->isbn13(),
            'ano_publicacao' => fake()->numberBetween(1960, 2026),
            'quantidade_total' => $quantidadeTotal,
            'quantidade_disponivel' => $quantidadeTotal,
        ];
    }
}
