<?php

namespace App\Helpers;

class AddressHelper
{
    public static function getAddressByZipCode(string $zipCode): ?array
    {
        try {
            if (empty($zipCode) || strlen($zipCode) !== 9) {
                return null;
            }

            $url = "https://viacep.com.br/ws/{$zipCode}/json/";

            $response = file_get_contents($url);

            if (!$response) {
                return null;
            }

            return json_decode($response, true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
