<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmprestimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leitores = User::where('role', UserRole::Leitor)->get();
        $livros = Livro::all();

        // Empréstimos já devolvidos (não afetam o estoque atual).
        for ($i = 0; $i < 6; $i++) {
            Emprestimo::factory()->devolvido()->create([
                'livro_id' => $livros->random()->id,
                'user_id' => $leitores->random()->id,
            ]);
        }

        // Empréstimos ativos dentro do prazo.
        for ($i = 0; $i < 6; $i++) {
            $livro = $livros->filter(fn (Livro $livro) => $livro->quantidade_disponivel > 0)->random();

            Emprestimo::factory()->create([
                'livro_id' => $livro->id,
                'user_id' => $leitores->random()->id,
            ]);

            $livro->reservarExemplar();
        }

        // Empréstimos atrasados.
        for ($i = 0; $i < 3; $i++) {
            $livro = $livros->filter(fn (Livro $livro) => $livro->quantidade_disponivel > 0)->random();

            Emprestimo::factory()->atrasado()->create([
                'livro_id' => $livro->id,
                'user_id' => $leitores->random()->id,
            ]);

            $livro->reservarExemplar();
        }
    }
}
