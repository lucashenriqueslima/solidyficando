<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

// $table->enum('job_type', [
//     'full_time',
//     'part_time',
//     'freelancer',
//     'internship',
//     'apprentice',
//     'trainee'
// ]);

enum JobType: string implements HasLabel, HasColor
{


    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case FREELANCER = 'freelancer';
    case INTERNSHIP = 'internship';
    case APPRENTICE = 'apprentice';
    case TRAINEE = 'trainee';

    public function getLabel(): string
    {
        return match ($this) {
            self::FULL_TIME => 'Tempo Integral',
            self::PART_TIME => 'Meio Período',
            self::FREELANCER => 'Freelancer',
            self::INTERNSHIP => 'Estágio',
            self::APPRENTICE => 'Aprendiz',
            self::TRAINEE => 'Trainee',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::FULL_TIME => 'success',
            self::PART_TIME => 'warning',
            self::FREELANCER => 'danger',
            self::INTERNSHIP => 'info',
            self::APPRENTICE => 'primary',
            self::TRAINEE => 'secondary',
        };
    }
}
