<?php

namespace App\DTOs;

use App\Helpers\FormatHelper;

abstract class WhatsAppEvolutionMessageDTO
{
    public const string ACCOUNT_NAME = 'solidyficando';
    protected string $number;
    public function __construct(string $number)
    {
        $this->number = FormatHelper::formatPhoneNumber($number);
    }
}
