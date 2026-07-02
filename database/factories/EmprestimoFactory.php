<?php

namespace Database\Factories;

use App\Enums\EmprestimoStatus;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Emprestimo>
 */
class EmprestimoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataEmprestimo = fake()->dateTimeBetween('-30 days', '-1 days');
        $dataPrevista = (clone $dataEmprestimo)->modify('+14 days');

        return [
            'livro_id' => Livro::factory(),
            'user_id' => User::factory(),
            'data_emprestimo' => $dataEmprestimo,
            'data_prevista_devolucao' => $dataPrevista,
            'data_devolucao' => null,
            'status' => EmprestimoStatus::Emprestado,
        ];
    }

    public function devolvido(): static
    {
        return $this->state(function (array $attributes) {
            $dataPrevista = $attributes['data_prevista_devolucao'];

            return [
                'status' => EmprestimoStatus::Devolvido,
                'data_devolucao' => fake()->dateTimeBetween($attributes['data_emprestimo'], $dataPrevista),
            ];
        });
    }

    public function atrasado(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_emprestimo' => fake()->dateTimeBetween('-60 days', '-30 days'),
            'data_prevista_devolucao' => fake()->dateTimeBetween('-25 days', '-5 days'),
            'data_devolucao' => null,
            'status' => EmprestimoStatus::Emprestado,
        ]);
    }
}
