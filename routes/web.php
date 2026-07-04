<?php

use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\ModulePageController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store']);
    Route::get('{module}', ModulePageController::class)
        ->whereIn('module', ['users', 'roles', 'permissions', 'companies', 'products', 'services', 'quotes', 'invoices', 'payments', 'tasks', 'notes', 'activity-logs'])
        ->name('crm.module');
});

require __DIR__.'/settings.php';
