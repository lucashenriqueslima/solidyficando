<?php

namespace App\Enums;

enum WhatsAppTextMessageLangKey: string
{
    private const string WHATSAPP_TEXT_MESSAGE_PREFIX = 'wpp-message.';
    case MONTHLY_CHARGE = self::WHATSAPP_TEXT_MESSAGE_PREFIX . 'charge.monthly';
    case SINGLE_CHARGE = self::WHATSAPP_TEXT_MESSAGE_PREFIX . 'charge.single';
}
