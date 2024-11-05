<?php

namespace App\Filament\App\Resources\PersonResource\Pages;

use App\Filament\App\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;

    protected  function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Auth::user()->id;

        return $data;
    }
}
