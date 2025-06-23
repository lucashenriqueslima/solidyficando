<?php

namespace App\Filament\Resources\RecruitResource\Pages;

use App\Filament\Resources\RecruitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecruits extends ListRecords
{
    protected static string $resource = RecruitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
