<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_redirecionado_para_login(): void
    {
        $response = $this->get('/livros');

        $response->assertRedirect('/login');
    }

    public function test_leitor_consegue_listar_livros_mas_nao_ve_botao_de_criar(): void
    {
        $leitor = User::factory()->leitor()->create();
        Livro::factory()->create();

        $response = $this->actingAs($leitor)->get('/livros');

        $response->assertOk();
        $response->assertDontSee('Novo livro');
    }

    public function test_leitor_recebe_403_ao_tentar_criar_livro(): void
    {
        $leitor = User::factory()->leitor()->create();
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($leitor)->post('/livros', [
            'titulo' => 'Livro Teste',
            'autor' => 'Autor Teste',
            'categoria_id' => $categoria->id,
            'quantidade_total' => 2,
        ]);

        $response->assertForbidden();
    }

    public function test_admin_consegue_criar_livro(): void
    {
        $admin = User::factory()->admin()->create();
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($admin)->post('/livros', [
            'titulo' => 'Dom Casmurro',
            'autor' => 'Machado de Assis',
            'categoria_id' => $categoria->id,
            'quantidade_total' => 3,
        ]);

        $response->assertRedirect('/livros');
        $this->assertDatabaseHas('livros', [
            'titulo' => 'Dom Casmurro',
            'quantidade_total' => 3,
            'quantidade_disponivel' => 3,
        ]);
    }

    public function test_criacao_de_livro_exige_categoria_valida(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/livros', [
            'titulo' => 'Livro Teste',
            'autor' => 'Autor Teste',
            'categoria_id' => 999,
            'quantidade_total' => 1,
        ]);

        $response->assertSessionHasErrors('categoria_id');
    }

    public function test_admin_consegue_editar_livro(): void
    {
        $admin = User::factory()->admin()->create();
        $categoria = Categoria::factory()->create();
        $livro = Livro::factory()->create(['categoria_id' => $categoria->id, 'quantidade_total' => 2, 'quantidade_disponivel' => 2]);

        $response = $this->actingAs($admin)->put("/livros/{$livro->id}", [
            'titulo' => 'Título Atualizado',
            'autor' => $livro->autor,
            'categoria_id' => $categoria->id,
            'quantidade_total' => 5,
        ]);

        $response->assertRedirect('/livros');
        $this->assertDatabaseHas('livros', [
            'id' => $livro->id,
            'titulo' => 'Título Atualizado',
            'quantidade_total' => 5,
            'quantidade_disponivel' => 5,
        ]);
    }

    public function test_admin_consegue_excluir_livro_sem_emprestimos_abertos(): void
    {
        $admin = User::factory()->admin()->create();
        $livro = Livro::factory()->create();

        $response = $this->actingAs($admin)->delete("/livros/{$livro->id}");

        $response->assertRedirect('/livros');
        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }
}
