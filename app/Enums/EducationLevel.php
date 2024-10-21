<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EducationLevel: string implements HasLabel

{
    case PRESCHOOL = 'preschool';

    case HIGH_SCHOOL = 'high_school';

    case HIGHER_EDUCATION = 'higher_education';

    public function getLabel(): string
    {
        return match ($this) {
            self::PRESCHOOL => 'Ensino Fundamental Completo',
            self::HIGH_SCHOOL => 'Ensino Médio Completo',
            self::HIGHER_EDUCATION => 'Ensino Superior Completo',
        };
    }
}
