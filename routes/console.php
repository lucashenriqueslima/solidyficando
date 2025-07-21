<?php

use App\Console\Commands\HandleBillingGenerationCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command(HandleBillingGenerationCommand::class)
    ->dailyAt('12:00');
