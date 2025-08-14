<?php

namespace App\Console\Commands;

use App\Actions\HandleBillingGenerationAction;
use App\DTOs\WhatsAppEvolutionTextMessageDTO;
use App\Enums\BillingType;
use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Enums\WhatsAppTextMessageLangKey;
use App\Models\FinancialMovement;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Services\Asaas\AsaasApiService;
use App\Services\WhatsAppEvolution\WhatsAppEvolutionApiService;
use Carbon\Carbon;
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
    public function handle(WhatsAppEvolutionApiService $whatsAppService)
    {

        $date = Carbon::now();
        $date->locale('pt_BR');
        $monthName = $date->isoFormat('MMMM');

        $partiners = Partiner::where('is_to_charge', true)
            ->where('billing_day', date('d'))
            ->get();

        if ($partiners->isEmpty()) {
            $this->info('No partiners to charge today.');
            return;
        }

        $financialMovementCategory = FinancialMovementCategory::where('name', 'Contribuição Mensal (Boleto)')
            ->first();

        $partiners->each(function (Partiner $partiner) use ($financialMovementCategory, $whatsAppService) {

            $existingBilling = $partiner->financialMovements()
                ->whereMonth('due_date', now()->month)
                ->exists();

            if ($existingBilling) {
                Log::info("Billing already exists for partiner: {$partiner->name}");
                return;
            }



            $financialMovement = (new HandleBillingGenerationAction())->execute(
                partiner: $partiner,
                financialMovementCategory: $financialMovementCategory,
            );

            if (!$financialMovement) {
                Log::error("Failed to send message for partiner: {$partiner->name}");
                return;
            }

            sleep(rand(15, 25));

            $whatsAppService->sendTextMessage(
                (new WhatsAppEvolutionTextMessageDTO(
                    number: $partiner->phone,
                ))->generateRandomMessage(
                    number: $partiner->phone,
                    langKey: WhatsAppTextMessageLangKey::MONTHLY_CHARGE,
                    replace: [
                        'name' => $partiner->name,
                        'month' => $financialMovement->value,
                        '' => $financialMovement->due_date->format('d/m/Y'),
                    ],
                ),
            );
        });
    }
}
