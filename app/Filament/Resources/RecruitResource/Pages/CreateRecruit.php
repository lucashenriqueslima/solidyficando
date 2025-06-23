<?php

namespace App\Filament\Resources\RecruitResource\Pages;

use App\Filament\Resources\RecruitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecruit extends CreateRecord
{
    protected static string $resource = RecruitResource::class;

    protected function getFormActions(): array
    {
        return [];
    }
}
