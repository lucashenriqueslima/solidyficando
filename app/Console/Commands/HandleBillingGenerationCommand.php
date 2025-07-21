<?php

namespace App\Console\Commands;

use App\Actions\HandleBillingGenerationAction;
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

        $financialMovementCategory = FinancialMovementCategory::where('name', 'Contribuição Mensal (Boleto)')
            ->first();

        $partiners->each(function (Partiner $partiner) use ($asaasService, $financialMovementCategory) {

            $existingBilling = $partiner->financialMovements()
                ->whereMonth('due_date', now()->month)
                ->exists();

            if ($existingBilling) {
                Log::info("Billing already exists for partiner: {$partiner->name}");
                return;
            }

            (new HandleBillingGenerationAction())->execute(
                partiner: $partiner,
                financialMovementCategory: $financialMovementCategory,
            );
        });
    }
}
