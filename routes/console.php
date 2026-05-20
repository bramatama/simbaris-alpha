<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Event;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Automated Event Status Updater
Schedule::call(function () {
    $now = now();

    // 1. Update to 'registration_open' if within registration period
    Event::where('registration_start_time', '<=', $now)
        ->where('registration_end_time', '>=', $now)
        ->where('status', '!=', 'registration_open')
        ->update(['status' => 'registration_open']);

    Event::where('registration_end_time', '<', $now)
        ->where('status', '!=', 'registration_closed')
        ->update(['status' => 'registration_closed']);


    // 2. Update to 'active' if event execution has started
    Event::where('start_time', '<=', $now)
        ->where('end_time', '>=', $now)
        ->where('status', '!=', 'active')
        ->update(['status' => 'active']);

    // 3. Update to 'finished' if event execution has ended
    Event::where('end_time', '<', $now)
        ->where('status', '!=', 'finished')
        ->update(['status' => 'finished']);
        
})->everyMinute()->name('update-event-statuses')->withoutOverlapping();
