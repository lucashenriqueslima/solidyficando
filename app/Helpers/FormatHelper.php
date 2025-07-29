<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format a phone number to E.164 format.
     *
     * @param string $phoneNumber
     * @return string
     */
    public static function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/\D/', '', $phoneNumber);

        // Check if the number starts with a country code (e.g., 55 for Brazil)
        if (strlen($cleaned) === 11 && substr($cleaned, 0, 2) === '55') {
            return $cleaned;
        }

        return '55' . $cleaned;
    }
}
