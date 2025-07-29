<?php

namespace App\DTOs;

use App\Enums\WhatsAppTextMessageLangKey;
use Illuminate\Support\Facades\Lang;

class WhatsAppEvolutionTextMessageDTO extends WhatsAppEvolutionMessageDTO
{

    private const string WHATSAPP_TEXT_MESSAGE_PREFIX = 'wpp-messages.';
    public readonly string $textMessage;

    public function generateRandomMessage(
        string $number,
        WhatsAppTextMessageLangKey $langKey,
        array $replace,
    ): self {

        $length = count(
            Lang::get(
                self::WHATSAPP_TEXT_MESSAGE_PREFIX . $langKey->value,
            )
        );

        $randomIndex = rand(0, $length - 1);

        $this->textMessage = Lang::get(
            self::WHATSAPP_TEXT_MESSAGE_PREFIX . $langKey->value . $randomIndex,
            $replace
        );

        $this->number = $number;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'text' => $this->textMessage,
        ];
    }
}
