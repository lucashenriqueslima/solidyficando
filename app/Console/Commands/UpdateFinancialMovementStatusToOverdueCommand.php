<?php

namespace App\Console\Commands;

use App\Enums\FinancialMovementStatus;
use App\Models\FinancialMovement;
use Illuminate\Console\Command;

class UpdateFinancialMovementStatusToOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-financial-movement-status-to-overdue-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FinancialMovement::where(
            'due_date',
            '<',
            now()
        )
            ->where(
                'status',
                FinancialMovementStatus::PENDING
            )
            ->update([
                'status' => FinancialMovementStatus::OVERDUE
            ]);
    }
}
