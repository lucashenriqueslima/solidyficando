<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasLabel, HasColor, HasIcon
{
    case IN_PLANNING = 'in_planning';
    case PLANNED = 'planned';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN_PLANNING => 'Em Planejamento',
            self::PLANNED => 'Planejado',
            self::IN_PROGRESS => 'Em Progresso',
            self::COMPLETED => 'Concluído',
            self::ON_HOLD => 'Em Espera',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::IN_PLANNING => 'warning',
            self::PLANNED => 'info',
            self::IN_PROGRESS => 'warning',
            self::COMPLETED => 'success',
            self::ON_HOLD => 'warning',
            self::CANCELLED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::IN_PLANNING => 'heroicon-o-clock',
            self::PLANNED => 'heroicon-o-calendar',
            self::IN_PROGRESS => 'heroicon-o-play',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::ON_HOLD => 'heroicon-o-pause',
            self::CANCELLED => 'heroicon-o-x-circle',
        };
    }
}
