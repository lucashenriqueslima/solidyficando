<?php

namespace App\Filament\Resources\PartinerResource\Pages;

use App\Filament\Resources\PartinerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartiners extends ListRecords
{
    protected static string $resource = PartinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
