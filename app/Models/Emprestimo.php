<?php

namespace App\Models;

use App\Enums\EmprestimoStatus;
use Database\Factories\EmprestimoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['livro_id', 'user_id', 'data_emprestimo', 'data_prevista_devolucao', 'data_devolucao', 'status'])]
class Emprestimo extends Model
{
    /** @use HasFactory<EmprestimoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'data_emprestimo' => 'date',
            'data_prevista_devolucao' => 'date',
            'data_devolucao' => 'date',
            'status' => EmprestimoStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Livro, $this>
     */
    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function leitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estaAtrasado(): bool
    {
        return $this->status === EmprestimoStatus::Emprestado
            && $this->data_prevista_devolucao->isPast();
    }

    public function statusEfetivo(): EmprestimoStatus
    {
        return $this->estaAtrasado() ? EmprestimoStatus::Atrasado : $this->status;
    }

    public function registrarDevolucao(): void
    {
        $this->update([
            'status' => EmprestimoStatus::Devolvido,
            'data_devolucao' => now()->toDateString(),
        ]);

        $this->livro->liberarExemplar();
    }

    /**
     * @param  Builder<Emprestimo>  $query
     * @return Builder<Emprestimo>
     */
    public function scopeAtrasados(Builder $query): Builder
    {
        return $query->where('status', EmprestimoStatus::Emprestado)
            ->whereDate('data_prevista_devolucao', '<', now()->toDateString());
    }
}
