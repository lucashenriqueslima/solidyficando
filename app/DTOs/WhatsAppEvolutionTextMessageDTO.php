<?php

namespace App\DTOs;

use App\Enums\WhatsAppTextMessageLangKey;
use Illuminate\Support\Facades\Lang;

class WhatsAppEvolutionTextMessageDTO extends WhatsAppEvolutionMessageDTO
{
    public readonly string $textMessage;

    public function generateRandomMessage(
        string $number,
        WhatsAppTextMessageLangKey $langKey,
        array $replace,
    ): self {

        $length = count(
            Lang::get(
                $langKey->value,
            )
        );

        $randomIndex = rand(0, $length - 1);

        $this->textMessage = Lang::get(
            "{$langKey->value}.{$randomIndex}",
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
