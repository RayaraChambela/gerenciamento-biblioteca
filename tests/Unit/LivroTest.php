<?php

namespace Tests\Unit;

use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservar_exemplar_decrementa_quantidade_disponivel(): void
    {
        $livro = Livro::factory()->create(['quantidade_total' => 3, 'quantidade_disponivel' => 3]);

        $livro->reservarExemplar();

        $this->assertSame(2, $livro->fresh()->quantidade_disponivel);
    }

    public function test_reservar_exemplar_lanca_excecao_quando_indisponivel(): void
    {
        $livro = Livro::factory()->create(['quantidade_total' => 1, 'quantidade_disponivel' => 0]);

        $this->expectException(\RuntimeException::class);

        $livro->reservarExemplar();
    }

    public function test_liberar_exemplar_incrementa_quantidade_disponivel(): void
    {
        $livro = Livro::factory()->create(['quantidade_total' => 3, 'quantidade_disponivel' => 1]);

        $livro->liberarExemplar();

        $this->assertSame(2, $livro->fresh()->quantidade_disponivel);
    }

    public function test_liberar_exemplar_nao_ultrapassa_quantidade_total(): void
    {
        $livro = Livro::factory()->create(['quantidade_total' => 3, 'quantidade_disponivel' => 3]);

        $livro->liberarExemplar();

        $this->assertSame(3, $livro->fresh()->quantidade_disponivel);
    }

    public function test_possui_exemplar_disponivel(): void
    {
        $comEstoque = Livro::factory()->create(['quantidade_disponivel' => 1]);
        $semEstoque = Livro::factory()->create(['quantidade_disponivel' => 0]);

        $this->assertTrue($comEstoque->possuiExemplarDisponivel());
        $this->assertFalse($semEstoque->possuiExemplarDisponivel());
    }
}
