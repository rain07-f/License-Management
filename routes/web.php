<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\ActivationController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
Route::middleware(\Illuminate\Routing\Middleware\ThrottleRequests::with(10, 1))->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Basic Auth for all roles
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api-docs', function () {
        return view('admin.api_docs');
    })->name('api_docs');

    // Licenses (All roles have some level of access)
    Route::get('/licenses/export', [LicenseController::class, 'export'])->name('licenses.export');

    Route::middleware(['role:super_admin,distributor'])->group(function () {
        Route::resource('licenses', LicenseController::class)->except(['index', 'show']);
        Route::post('/licenses/{license}/assign', [LicenseController::class, 'assign'])->name('licenses.assign');
        Route::post('/licenses/{license}/revoke', [LicenseController::class, 'revoke'])->name('licenses.revoke');
        Route::post('/licenses/{license}/renew', [LicenseController::class, 'renew'])->name('licenses.renew');
        Route::post('/activations/{activation}/revoke', [LicenseController::class, 'revokeActivation'])->name('activations.revoke');
    });

    Route::resource('licenses', LicenseController::class)->only(['index', 'show']);

    // Activations & Logs
    Route::get('/activations', [ActivationController::class, 'index'])->name('activations.index');
    Route::get('/activations/{activation}', [ActivationController::class, 'show'])->name('activations.show');
    Route::delete('/activations/{activation}', [ActivationController::class, 'destroy'])->name('activations.destroy');
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Super Admin Only
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('plans', PlanController::class);
        Route::post('/users/{user}/quota', [UserController::class, 'addQuota'])->name('users.quota');

        // API Key Management
        Route::resource('api-keys', \App\Http\Controllers\Admin\ApiKeyController::class)->only(['index', 'store', 'destroy']);
        Route::post('/api-keys/{api_key}/activate', [\App\Http\Controllers\Admin\ApiKeyController::class, 'activate'])->name('api-keys.activate');
        Route::delete('/api-keys/{api_key}/permanent-delete', [\App\Http\Controllers\Admin\ApiKeyController::class, 'permanentDelete'])->name('api-keys.permanent-delete');
        Route::post('/api-keys/{api_key}/reveal', [\App\Http\Controllers\Admin\ApiKeyController::class, 'reveal'])
            ->name('api-keys.reveal')
            ->middleware('throttle:3,1');
    });

    Route::middleware(['role:super_admin,distributor'])->group(function () {
        Route::get('/quota-history', [UserController::class, 'quotaHistory'])->name('users.quota_history');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});