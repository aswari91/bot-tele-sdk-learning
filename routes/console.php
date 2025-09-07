<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --queue=default --stop-when-empty')
    ->everyMinute();

// Schedule the command in the Console Kernel
// Add the following to the `schedule` method in `app/Console/Kernel.php`:

// Schedule::command('tg:bot-reminder')->twiceDaily(8, 20);
Schedule::command('tg:bot-reminder')
    ->everySecond();
