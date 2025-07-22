<?php

use App\Http\Controllers\V1\Webhook\Asaas\BillingEventAsaasWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('webhooks')->group(function () {
        Route::prefix('asaas')->middleware(\App\Http\Middleware\AsaasWebhookMiddleware::class)->group(function () {
            Route::post('/billig/event', BillingEventAsaasWebhookController::class);
        });
    });
});
