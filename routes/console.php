<?php

use App\Console\Commands\HandleBillingGenerationCommand;
use App\Console\Commands\UpdateFinancialMovementStatusToOverdueCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(HandleBillingGenerationCommand::class)
    ->hourly();

Schedule::command(UpdateFinancialMovementStatusToOverdueCommand::class)
    ->twiceDaily(
        1,
        13
    );
