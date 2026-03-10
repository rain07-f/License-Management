<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\DomainController;
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
Route::middleware(['auth', 'login.cache'])->prefix('admin')->name('admin.')->group(function () {
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
        Route::post('/licenses/{license}/upgrade', [LicenseController::class, 'upgradePlan'])->name('licenses.upgrade');
        Route::get('/licenses/{license}/domains', [LicenseController::class, 'domains'])->name('licenses.domains');
    });

    Route::resource('licenses', LicenseController::class)->only(['index', 'show']);

    // Domains & Logs
    Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
    Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Super Admin Only
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('plans', PlanController::class);
        Route::post('/users/{user}/quota', [UserController::class, 'addQuota'])->name('users.quota');

        // API Key Management
        Route::resource('api-keys', \App\Http\Controllers\Admin\ApiKeyController::class)
            ->only(['index', 'store', 'destroy'])
            ->parameters(['api-keys' => 'apiKey']);
        Route::post('/api-keys/{apiKey}/activate', [\App\Http\Controllers\Admin\ApiKeyController::class, 'activate'])->name('api-keys.activate');
        Route::delete('/api-keys/{apiKey}/permanent-delete', [\App\Http\Controllers\Admin\ApiKeyController::class, 'permanentDelete'])->name('api-keys.permanent-delete');
        Route::post('/api-keys/{apiKey}/reveal', [\App\Http\Controllers\Admin\ApiKeyController::class, 'reveal'])
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
    Route::post('/profile/sessions/revoke-all', [ProfileController::class, 'revokeAllOtherSessions'])->name('profile.sessions.revoke_all');
    Route::post('/profile/sessions/{sessionId}/revoke', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');


});