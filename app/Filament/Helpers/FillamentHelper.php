<?php

namespace App\Filament\Helpers;

use App\Helpers\AddressHelper;

class FillamentHelper
{
    public static function handleAddressByZipCodeEvent(string $zipCode, callable $set): ?array
    {
        $address = AddressHelper::getAddressByZipCode($zipCode);

        if (!$address) {
            return null;
        }

        $set('address', $data['logradouro'] ?? '');
        $set('neighborhood', $data['bairro'] ?? '');
        $set('city', $data['localidade'] ?? '');
        $set('state', $data['uf'] ?? '');
    }
}
