<?php

namespace App\Actions;

use App\Enums\BillingType;
use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Services\Asaas\AsaasApiService;
use Illuminate\Support\Facades\Log;

class HandleBillingGenerationAction
{
    public function execute(
        Partiner $partiner,
        FinancialMovementCategory $financialMovementCategory,
        ?float $value = null,
    ): void {

        $asaasApiService = new AsaasApiService();

        // Check if the partiner has an Asaas ID
        if (!$partiner->asaas_id) {
            // If not, create a new customer in Asaas
            $customer = $asaasApiService->createCustomer(
                $partiner->name,
                $partiner->cpf,
                $partiner->email,
                $partiner->phone
            );

            if ($customer) {
                // Save the Asaas ID to the partiner model
                $partiner->asaas_id = $customer['id'];
                $partiner->save();
            } else {
                // Handle error if customer creation fails
                Log::error('Failed to create customer in Asaas for partiner: ' . $partiner->name);
                return;
            }
        }

        $billing = $asaasApiService->createBilling(
            $partiner->asaas_id,
            BillingType::BANK_SLIP,
            $value ?? $partiner->monthly_contribution,
            now()->addMonth(),
        );

        if (!$billing) {
            Log::error('Failed to create billing for partiner: ' . $partiner->name);
            return;
        }

        $partiner->financialMovements()->create([
            'asaas_id' => $billing['id'],
            'value' => $billing['value'],
            'due_date' => $billing['dueDate'],
            'status' => FinancialMovementStatus::PENDING,
            'flow_type' => FinancialMovementFlowType::IN,
            'financial_movement_category_id' => $financialMovementCategory->id,
            'invoice_url' => $billing['invoiceUrl'] ?? null,
            'bank_slip_url' => $billing['bankSlipUrl'] ?? null,
        ]);

        Log::info("Billing created for partiner: {$partiner->name}", [
            'billing_id' => $billing['id'],
            'value' => $billing['value'],
            'due_date' => $billing['dueDate'],
        ]);
    }
}
