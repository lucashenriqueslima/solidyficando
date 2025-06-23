<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EducationLevel: string implements HasLabel

{
    case PRESCHOOL_INCOMPLETE = 'preschool_incomplete';
    case PRESCHOOL = 'preschool';
    case HIGH_SCHOOL_INCOMPLETE = 'high_school_incomplete';
    case HIGH_SCHOOL = 'high_school';
    case HIGHER_EDUCATION_INCOMPLETE = 'higher_education_incomplete';
    case HIGHER_EDUCATION = 'higher_education';

    case NO_SCHOOL = 'no_school';

    public function getLabel(): string
    {
        return match ($this) {
            self::PRESCHOOL_INCOMPLETE => 'Ensino Fundamental Incompleto',
            self::PRESCHOOL => 'Ensino Fundamental Completo',
            self::HIGH_SCHOOL_INCOMPLETE => 'Ensino Médio Incompleto',
            self::HIGH_SCHOOL => 'Ensino Médio Completo',
            self::HIGHER_EDUCATION_INCOMPLETE => 'Ensino Superior Incompleto',
            self::HIGHER_EDUCATION => 'Ensino Superior Completo',
            self::NO_SCHOOL => 'Sem Escolaridade',
        };
    }
}
