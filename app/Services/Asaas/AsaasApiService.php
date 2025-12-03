<?php

namespace App\Services\Asaas;

use App\Enums\BillingType;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasApiService
{
    private PendingRequest $client;
    public function __construct()
    {
        $this->client = Http::baseUrl(config('asaas.api.url'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'access_token' => config('asaas.api.key'),
            ]);
    }

    public function getCustomers(string $cpfCnpj)
    {
        try {
            $response = $this->client->get('/customers', [
                'cpfCnpj' => $cpfCnpj,
            ]);

            if (!$response->successful()) {
                Log::error('Error fetching customers from Asaas: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error fetching customers from Asaas: ' . $e->getMessage());
            return null;
        }
    }

    public function createCustomer(
        string $name,
        string $cpfCnpj,
        ?string $email = null,
        ?string $phone = null,
    ) {
        try {
            $response = $this->client->post('/customers', [
                'name' => $name,
                'cpfCnpj' => $cpfCnpj,
                'email' => $email,
                'mobilePhone' => $phone,
            ]);

            if (!$response->successful()) {
                Log::error('Error creating customer in Asaas: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error creating customer in Asaas: ' . $e->getMessage());
            return null;
        }
    }

    public function createBilling(
        string $customerId,
        BillingType $billingType,
        float $value,
        Carbon $dueDate,
    ) {
        try {
            $response = $this->client->post('/payments', [
                'customer' => $customerId,
                'billingType' => $billingType->getValueForAsaas(),
                'value' => $value,
                'dueDate' => $dueDate->format('Y-m-d'),
                'description' => 'Contribuição Mensal',
            ]);

            if (!$response->successful()) {
                Log::error('Error creating billing in Asaas: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error creating billing in Asaas: ' . $e->getMessage());
            return null;
        }
    }

    public function getPixQrCode(string $paymentId)
    {
        try {
            $response = $this->client->get("/payments/{$paymentId}/pixQrCode");

            if (!$response->successful()) {
                Log::error('Error fetching Pix QR Code from Asaas: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error fetching Pix QR Code from Asaas: ' . $e->getMessage());
            return null;
        }
    }
}
