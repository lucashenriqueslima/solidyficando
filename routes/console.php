<?php

use App\Console\Commands\HandleBillingGenerationCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(HandleBillingGenerationCommand::class)
    ->dailyAt('12:00');
