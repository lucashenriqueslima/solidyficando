<?php

namespace App\Console\Commands;

use App\Enums\BillingType;
use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Models\FinancialMovement;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Services\Asaas\AsaasApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HandleBillingGenerationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-billing-generation-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(AsaasApiService $asaasService)
    {
        $partiners = Partiner::where('is_to_charge', true)
            ->where('billing_day', date('d'))
            ->get();

        if ($partiners->isEmpty()) {
            $this->info('No partiners to charge today.');
            return;
        }

        $financialMovementCategoryForMonthContributionId = FinancialMovementCategory::where('name', 'Contribuição Mensal (Boleto)')
            ->firstOrFail()
            ->id;

        if ($financialMovementCategoryForMonthContributionId === null) {
            $this->error('Financial Movement Category for "Contribuição Mensal (Boleto)" not found.');
            return;
        }

        $partiners->each(function (Partiner $partiner) use ($asaasService, $financialMovementCategoryForMonthContributionId) {
            if (!$partiner->asaas_id) {
                $customer = $asaasService->createCustomer(
                    $partiner->name,
                    $partiner->cpf,
                    $partiner->email,
                    $partiner->phone
                );


                if (!$customer) {
                    $this->error("Failed to create customer for partiner: {$partiner->name}");
                    return;
                }

                $partiner->update([
                    'asaas_id' => $customer['id'],
                ]);

                $partiner->refresh();
            }

            //check if already has a billing for this month
            $existingBilling = $partiner->financialMovements()
                ->whereMonth('due_date', now()->month)
                ->exists();

            if ($existingBilling) {
                Log::info("Billing already exists for partiner: {$partiner->name}");
                return;
            }

            $billing = $asaasService->createBilling(
                $partiner->asaas_id,
                BillingType::BANK_SLIP,
                $partiner->monthly_contribution,
                now()->addMonth(),
            );

            if (!$billing) {
                $this->error("Failed to create billing for partiner: {$partiner->name}");
                return;
            }

            $partiner->financialMovements()->create([
                'asaas_id' => $billing['id'],
                'value' => $billing['value'],
                'due_date' => $billing['dueDate'],
                'status' => FinancialMovementStatus::PENDING,
                'flow_type' => FinancialMovementFlowType::IN,
                'financial_movement_category_id' => $financialMovementCategoryForMonthContributionId,
                'invoice_url' => $billing['invoiceUrl'] ?? null,
                'bank_slip_url' => $billing['bankSlipUrl'] ?? null,
            ]);

            Log::info("Billing created for partiner: {$partiner->name}", [
                'billing_id' => $billing['id'],
                'value' => $billing['value'],
                'due_date' => $billing['dueDate'],
            ]);
        });
    }
}
