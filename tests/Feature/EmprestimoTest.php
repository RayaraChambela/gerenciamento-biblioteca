<?php

namespace Tests\Feature;

use App\Enums\EmprestimoStatus;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmprestimoTest extends TestCase
{
    use RefreshDatabase;

    public function test_leitor_ve_apenas_os_proprios_emprestimos(): void
    {
        $leitorA = User::factory()->leitor()->create();
        $leitorB = User::factory()->leitor()->create();

        $livroDoLeitorA = Livro::factory()->create(['titulo' => 'Livro do Leitor A']);
        $livroDoLeitorB = Livro::factory()->create(['titulo' => 'Livro do Leitor B']);

        Emprestimo::factory()->create(['livro_id' => $livroDoLeitorA->id, 'user_id' => $leitorA->id]);
        Emprestimo::factory()->create(['livro_id' => $livroDoLeitorB->id, 'user_id' => $leitorB->id]);

        $response = $this->actingAs($leitorA)->get('/emprestimos');

        $response->assertOk();
        $response->assertSee('Livro do Leitor A');
        $response->assertDontSee('Livro do Leitor B');
    }

    public function test_leitor_recebe_403_ao_tentar_registrar_emprestimo(): void
    {
        $leitor = User::factory()->leitor()->create();
        $livro = Livro::factory()->create();

        $response = $this->actingAs($leitor)->post('/emprestimos', [
            'livro_id' => $livro->id,
            'user_id' => $leitor->id,
        ]);

        $response->assertForbidden();
    }

    public function test_admin_consegue_registrar_emprestimo_e_disponibilidade_e_decrementada(): void
    {
        $admin = User::factory()->admin()->create();
        $leitor = User::factory()->leitor()->create();
        $livro = Livro::factory()->create(['quantidade_total' => 2, 'quantidade_disponivel' => 2]);

        $response = $this->actingAs($admin)->post('/emprestimos', [
            'livro_id' => $livro->id,
            'user_id' => $leitor->id,
        ]);

        $response->assertRedirect('/emprestimos');
        $this->assertDatabaseHas('emprestimos', [
            'livro_id' => $livro->id,
            'user_id' => $leitor->id,
            'status' => EmprestimoStatus::Emprestado->value,
        ]);
        $this->assertSame(1, $livro->fresh()->quantidade_disponivel);
    }

    public function test_nao_e_possivel_registrar_emprestimo_sem_exemplar_disponivel(): void
    {
        $admin = User::factory()->admin()->create();
        $leitor = User::factory()->leitor()->create();
        $livro = Livro::factory()->create(['quantidade_total' => 1, 'quantidade_disponivel' => 0]);

        $response = $this->actingAs($admin)->post('/emprestimos', [
            'livro_id' => $livro->id,
            'user_id' => $leitor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('emprestimos', 0);
        $this->assertSame(0, $livro->fresh()->quantidade_disponivel);
    }

    public function test_admin_consegue_registrar_devolucao_e_disponibilidade_e_incrementada(): void
    {
        $admin = User::factory()->admin()->create();
        $livro = Livro::factory()->create(['quantidade_total' => 2, 'quantidade_disponivel' => 1]);
        $emprestimo = Emprestimo::factory()->create([
            'livro_id' => $livro->id,
            'status' => EmprestimoStatus::Emprestado,
        ]);

        $response = $this->actingAs($admin)->patch("/emprestimos/{$emprestimo->id}/devolver");

        $response->assertRedirect('/emprestimos');
        $emprestimo->refresh();
        $this->assertSame(EmprestimoStatus::Devolvido, $emprestimo->status);
        $this->assertNotNull($emprestimo->data_devolucao);
        $this->assertSame(2, $livro->fresh()->quantidade_disponivel);
    }

    public function test_leitor_recebe_403_ao_tentar_registrar_devolucao(): void
    {
        $leitor = User::factory()->leitor()->create();
        $emprestimo = Emprestimo::factory()->create();

        $response = $this->actingAs($leitor)->patch("/emprestimos/{$emprestimo->id}/devolver");

        $response->assertForbidden();
    }
}
