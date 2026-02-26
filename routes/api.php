<?php

use App\Http\Controllers\API\LicenseController;
use App\Http\Controllers\API\LicenseActivationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:license_api'])->group(function () {
    // Existing License Endpoints
    Route::match(['get', 'post'], '/license', [LicenseController::class, 'validate']);
    Route::post('/license/activate', [LicenseController::class, 'activate']);
    Route::match(['get', 'post'], '/license/validate', [LicenseController::class, 'validate']);
    Route::match(['get', 'post'], '/license/active', [LicenseController::class, 'validate']);
    Route::post('/license/deactivate', [LicenseController::class, 'deactivate']);

    // New License Pair Binding Endpoints
    Route::prefix('license-pair')->group(function () {
        Route::post('/activate', [LicenseActivationController::class, 'activate']);
        Route::post('/revoke', [LicenseActivationController::class, 'revoke']);
        Route::post('/validate', [LicenseActivationController::class, 'validatePair']);
    });
});