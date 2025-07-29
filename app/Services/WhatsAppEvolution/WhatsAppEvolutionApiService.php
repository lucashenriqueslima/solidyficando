<?php

namespace App\Services\WhatsAppEvolution;

use App\DTOs\WhatsAppEvolutionMessageDTO;
use App\DTOs\WhatsAppEvolutionTextMessageDTO;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class WhatsAppEvolutionApiService
{

    private readonly PendingRequest $client;
    public function __construct()
    {
        $this->client = Http::baseUrl(config('whatsapp-evolution.api.url'))

            ->withHeaders(
                [
                    'apiKey' => config('whatsapp-evolution.api.token'),
                ]

            );
    }

    public function sendTextMessage(WhatsAppEvolutionTextMessageDTO $messageDTO): mixed
    {
        try {
            $result = $this->client->post(
                '/message/sendText/' . $messageDTO::ACCOUNT_NAME,
                $messageDTO->toArray(),
            );

            if ($result->failed()) {
                throw new \Exception('Failed to send message: ' . $result->body());
            }

            return $result->json();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to send message: ' . $e->getMessage());
        }
    }
}
