<?php

use App\Console\Commands\HandleBillingGenerationCommand;
use App\Console\Commands\UpdateFinancialMovementStatusToOverdueCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(HandleBillingGenerationCommand::class)
    ->dailyAt('12:00');

Schedule::command(UpdateFinancialMovementStatusToOverdueCommand::class)
    ->dailyAt('01:00');
