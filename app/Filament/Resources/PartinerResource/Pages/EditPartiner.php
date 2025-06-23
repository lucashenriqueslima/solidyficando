<?php

namespace App\Filament\Resources\PartinerResource\Pages;

use App\Enums\SignInAccountType;
use App\Filament\Resources\PartinerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPartiner extends EditRecord
{
    protected static string $resource = PartinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (Str::length($data['cpf']) === 14) {
            $data['document_type'] = SignInAccountType::CPF->value;

            return $data;
        }

        $data['document_type'] = SignInAccountType::CNPJ->value;

        return $data;
    }
}
