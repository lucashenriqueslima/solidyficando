<?php

namespace App\Enums;

enum BillingType: string
{
    case BANK_SLIP = 'BANK_SLIP';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';

    public function getLabel(): string
    {
        return match ($this) {
            self::BANK_SLIP => 'Boleto Bancário',
            self::CREDIT_CARD => 'Cartão de Crédito',
            self::PIX => 'PIX',
        };
    }

    public function getValueForAsaas(): string
    {
        return match ($this) {
            self::BANK_SLIP => 'BOLETO',
            self::CREDIT_CARD => 'CREDIT_CARD',
            self::PIX => 'PIX',
        };
    }
}
