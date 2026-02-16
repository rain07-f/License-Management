<?php

use App\Http\Controllers\API\LicenseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:60,1'])->group(function () {
    Route::match(['get', 'post'], '/license', [LicenseController::class, 'validate']); // Base endpoint
    Route::post('/license/activate', [LicenseController::class, 'activate']);
    Route::match(['get', 'post'], '/license/validate', [LicenseController::class, 'validate']);
    Route::match(['get', 'post'], '/license/active', [LicenseController::class, 'validate']); // Alias for better DX
    Route::post('/license/deactivate', [LicenseController::class, 'deactivate']);
});