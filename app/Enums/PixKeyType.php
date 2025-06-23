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

    public static function getMask(?string $pixKeyType): string
    {
        return match ($pixKeyType) {
            self::CPF->value => '999.999.999-99',
            self::CNPJ->value => '99.999.999/9999-99',
            self::PHONE->value => '(99) 99999-9999',
            default => '',
        };
    }

    public static function getMinLength(?string $pixKeyType): int
    {
        return match ($pixKeyType) {
            self::CPF->value => 14,
            self::CNPJ->value => 18,
            default => 0,
        };
    }

    public static function getRule(?string $pixKeyType): ?string
    {
        return match ($pixKeyType) {
            self::CPF->value => 'cpf',
            self::CNPJ->value => 'cnpj',
            default => null,
        };
    }
}
