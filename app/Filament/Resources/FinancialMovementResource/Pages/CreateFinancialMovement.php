<?php

namespace App\Filament\Resources\FinancialMovementResource\Pages;

use App\Filament\Resources\FinancialMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialMovement extends CreateRecord
{
    protected static string $resource = FinancialMovementResource::class;
}
