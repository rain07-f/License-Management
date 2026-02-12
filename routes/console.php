<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\License;

Schedule::call(function () {
    License::where('status', 'active')
        ->where('expires_at', '<', now())
        ->update(['status' => 'expired']);
})->daily();
