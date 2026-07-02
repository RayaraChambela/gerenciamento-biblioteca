<?php

namespace App\Models;

use Database\Factories\LivroFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['titulo', 'autor', 'categoria_id', 'isbn', 'ano_publicacao', 'quantidade_total', 'quantidade_disponivel'])]
class Livro extends Model
{
    /** @use HasFactory<LivroFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'ano_publicacao' => 'integer',
            'quantidade_total' => 'integer',
            'quantidade_disponivel' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Categoria, $this>
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * @return HasMany<Emprestimo, $this>
     */
    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    public function possuiExemplarDisponivel(): bool
    {
        return $this->quantidade_disponivel > 0;
    }

    public function reservarExemplar(): void
    {
        if (! $this->possuiExemplarDisponivel()) {
            throw new \RuntimeException('Não há exemplares disponíveis para este livro.');
        }

        $this->decrement('quantidade_disponivel');
    }

    public function liberarExemplar(): void
    {
        if ($this->quantidade_disponivel >= $this->quantidade_total) {
            return;
        }

        $this->increment('quantidade_disponivel');
    }
}
