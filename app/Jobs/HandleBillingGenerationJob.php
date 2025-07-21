<?php

namespace App\Jobs;

use App\Actions\HandleBillingGenerationAction;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Services\Asaas\AsaasApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleBillingGenerationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Partiner $partiner,
        protected float $value,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(HandleBillingGenerationAction $action): void
    {
        $action->execute(
            $this->partiner,
            FinancialMovementCategory::where(
                'name',
                'Contribuição Avulsa (Boleto)',
            )->first(),
            $this->value,
        );
    }
}
