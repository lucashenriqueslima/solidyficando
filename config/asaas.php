<?php

return [
    'api' =>
    [
        'url' => env('ASAAS_API_URL', 'https://api.asaas.com/v3'),
        'key' => "$" . env('ASAAS_API_KEY'),
    ],
    'webhook' =>
    [
        'access_token' => env('ASAAS_WEBHOOK_TOKEN'),
    ],
];
