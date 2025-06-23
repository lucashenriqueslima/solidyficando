<?php

namespace App\Services\Asaas;

use App\Enums\FinancialMovementStatus;
use App\Models\FinancialMovement;
use Illuminate\Http\Request;


class AsaasWebhookService
{
    public function handlePaymentConfirmedEvent(Request $request): void
    {

        FinancialMovement::where('asaas_id', $request->id)
            ->where('status', 'pending')
            ->update([
                'status' => FinancialMovementStatus::PAID,
                'payment_date' => now(),
            ]);
    }
}
