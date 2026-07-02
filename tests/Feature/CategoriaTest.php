<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_redirecionado_para_login(): void
    {
        $response = $this->get('/categorias');

        $response->assertRedirect('/login');
    }

    public function test_leitor_recebe_403_ao_acessar_categorias(): void
    {
        $leitor = User::factory()->leitor()->create();

        $response = $this->actingAs($leitor)->get('/categorias');

        $response->assertForbidden();
    }

    public function test_admin_consegue_listar_categorias(): void
    {
        $admin = User::factory()->admin()->create();
        Categoria::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/categorias');

        $response->assertOk();
    }

    public function test_admin_consegue_criar_categoria(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/categorias', [
            'nome' => 'Ficção Científica',
        ]);

        $response->assertRedirect('/categorias');
        $this->assertDatabaseHas('categorias', ['nome' => 'Ficção Científica']);
    }

    public function test_criacao_de_categoria_exige_nome(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/categorias', ['nome' => '']);

        $response->assertSessionHasErrors('nome');
    }

    public function test_admin_consegue_editar_categoria(): void
    {
        $admin = User::factory()->admin()->create();
        $categoria = Categoria::factory()->create(['nome' => 'Antigo Nome']);

        $response = $this->actingAs($admin)->put("/categorias/{$categoria->id}", [
            'nome' => 'Novo Nome',
        ]);

        $response->assertRedirect('/categorias');
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'nome' => 'Novo Nome']);
    }

    public function test_admin_consegue_excluir_categoria_sem_livros(): void
    {
        $admin = User::factory()->admin()->create();
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($admin)->delete("/categorias/{$categoria->id}");

        $response->assertRedirect('/categorias');
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }
}
