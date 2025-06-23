<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Housing: string implements HasLabel
{
    case owned = 'owned';

    case rented = 'rented';

    public function getLabel(): string
    {
        return match ($this) {
            self::owned => 'Própria',
            self::rented => 'Alugada',
        };
    }
}
