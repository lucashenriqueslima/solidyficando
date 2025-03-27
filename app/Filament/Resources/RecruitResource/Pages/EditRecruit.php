<?php

namespace App\Filament\Resources\RecruitResource\Pages;

use App\Filament\Resources\RecruitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecruit extends EditRecord
{
    protected static string $resource = RecruitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
