<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('webhooks')->group(function () {
        Route::post('asaas', \App\Http\Controllers\V1\Webhook\AsaasWebhookController::class);
    });
});
