<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SignInAccountType: string implements HasLabel
{
    case CNPJ = 'cnpj';
    case CPF = 'cpf';

    public function getLabel(): string
    {
        return match ($this) {
            self::CNPJ => 'CNPJ',
            self::CPF => 'CPF',
        };
    }
}
