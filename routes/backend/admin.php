<?php

use App\Http\Controllers\Backend\DashboardController;
// use App\Http\Controllers\Backend\ClientController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('client', 'ClientController')->only(['index']);
