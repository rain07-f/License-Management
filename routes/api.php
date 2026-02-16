<?php

use App\Http\Controllers\API\LicenseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:60,1'])->group(function () {
    Route::post('/license/activate', [LicenseController::class, 'activate']);
    Route::post('/license/validate', [LicenseController::class, 'validate']);
    Route::post('/license/deactivate', [LicenseController::class, 'deactivate']);
});