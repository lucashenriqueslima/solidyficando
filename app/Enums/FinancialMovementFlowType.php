<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum FinancialMovementFlowType: string implements HasLabel, HasColor, HasIcon
{
    case IN = 'in';
    case OUT = 'out';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN => 'Entrada',
            self::OUT => 'Saída',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::IN => 'success',
            self::OUT => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::IN => 'heroicon-o-arrow-up-circle',
            self::OUT => 'heroicon-o-arrow-down-circle',
        };
    }
}
