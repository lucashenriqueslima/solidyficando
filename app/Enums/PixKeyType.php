<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;


enum PixKeyType: string implements HasLabel
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
    case PHONE = 'phone';
    case EMAIL = 'email';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::CPF => 'CPF',
            self::CNPJ => 'CNPJ',
            self::PHONE => 'Telefone',
            self::EMAIL => 'E-mail',
            self::OTHER => 'Outro',
        };
    }
}
