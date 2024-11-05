<?php

namespace App\Filament\Resources\PartinerResource\Pages;

use App\Filament\Resources\PartinerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartiner extends EditRecord
{
    protected static string $resource = PartinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
