<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Leitor = 'leitor';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Bibliotecário(a)',
            self::Leitor => 'Leitor(a)',
        };
    }
}
