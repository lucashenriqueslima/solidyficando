<?php

namespace App\Http\Controllers\V1\Webhook\Asaas;

use App\Enums\FinancialMovementStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\BillingEventAsaasWebhookRequest;
use App\Models\FinancialMovement;
use App\Services\Asaas\AsaasWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingEventAsaasWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(BillingEventAsaasWebhookRequest $request): JsonResponse
    {
        Log::info('Asaas Webhook Received', [
            'data' => $request->all(),
        ]);

        $validated = $request->validated();

        $financialMovement = FinancialMovement::where('asaas_id', $validated['payment']['id'])
            ->first();

        if (!$financialMovement) {
            Log::warning('Financial Movement not found for Asaas webhook', [
                'asaas_id' => $validated['payment']['id'],
            ]);
            return response()->json(status: 404);
        }


        match ($request->input('event')) {
            'PAYMENT_CONFIRMED' => fn() => $this->updateFinancialMovement($financialMovement, $request),
            'PAYMENT_RECEIVED' => fn() => $this->updateFinancialMovement($financialMovement, $request),
        };

        return response()->json(['status' => 'success'], 200);
    }

    private function updateFinancialMovement(FinancialMovement $financialMovement, Request $request): void
    {
        $financialMovement->status = FinancialMovementStatus::PAID;
        $financialMovement->payment_date = now();
        $financialMovement->save();
    }
}
