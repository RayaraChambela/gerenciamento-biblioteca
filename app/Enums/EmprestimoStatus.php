<?php

namespace App\Enums;

enum EmprestimoStatus: string
{
    case Emprestado = 'emprestado';
    case Devolvido = 'devolvido';
    case Atrasado = 'atrasado';

    public function label(): string
    {
        return match ($this) {
            self::Emprestado => 'Emprestado',
            self::Devolvido => 'Devolvido',
            self::Atrasado => 'Atrasado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Emprestado => 'bg-amber-100 text-amber-800',
            self::Devolvido => 'bg-emerald-100 text-emerald-800',
            self::Atrasado => 'bg-red-100 text-red-800',
        };
    }
}
