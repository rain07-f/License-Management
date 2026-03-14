<?php

use App\Http\Controllers\API\LicenseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:license_api'])->group(function () {
    Route::match(['get', 'post'], '/license', [LicenseController::class, 'validate']); // Base endpoint
    Route::post('/license/activate', [LicenseController::class, 'activate']);
    Route::match(['get', 'post'], '/license/validate', [LicenseController::class, 'validate']);
    Route::match(['get', 'post'], '/license/active', [LicenseController::class, 'validate']); // Alias for better DX
    Route::post('/license/deactivate', [LicenseController::class, 'deactivate']);
    
    // Auto-update endpoints
    Route::get('/license/update-check', [\App\Http\Controllers\API\UpdateController::class, 'updateCheck']);
    Route::get('/license/download', [\App\Http\Controllers\API\UpdateController::class, 'download']);
});