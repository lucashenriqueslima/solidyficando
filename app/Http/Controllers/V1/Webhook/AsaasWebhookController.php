<?php

namespace App\Http\Controllers\V1\Webhook;

use App\Http\Controllers\Controller;
use App\Services\Asaas\AsaasWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, AsaasWebhookService $assasWebhookService)
    {

        Log::info('Asaas Webhook Received', [
            'event' => $request->event,
            'data' => $request->all(),
        ]);

        if (!$request->has('event')) {
            return response()->json(['error' => 'Event not specified'], 400);
        }



        match ($request->event) {
            'PAYMENT_CONFIRMED' => $assasWebhookService->handlePaymentConfirmedEvent($request),
            default => response()->json(['error' => 'Unsupported event'], 400),
        };

        return response()->json(['status' => 'success'], 200);
    }
}
