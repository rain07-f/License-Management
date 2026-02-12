<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
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
    });

    Route::middleware(['role:super_admin,distributor'])->group(function () {
        Route::get('/quota-history', [UserController::class, 'quotaHistory'])->name('users.quota_history');
    });
});