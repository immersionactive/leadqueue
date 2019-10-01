<?php

use App\Http\Controllers\Backend\DashboardController;
// use App\Http\Controllers\Backend\ClientController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('client', 'ClientController');

// We define these routes separately, because we want them to support an additional /:type/ segment
Route::get('/client/{client}/lead_source/create/{lead_source_type}', 'LeadSourceController@create')->name('client.lead_source.create');
Route::post('/client/{client}/lead_source/{lead_source_type}', 'LeadSourceController@store')->name('client.lead_source.store');
Route::resource('client.lead_source', 'LeadSourceController')->except(['create', 'store']);

Route::resource('client.lead_source.lead', 'LeadController')->only(['index', 'show']);

// We define these routes separately, because we want them to support an additional /:type/ segment
Route::get('/client/{client}/lead_destination/create/{lead_destination_type}', 'LeadDestinationController@create')->name('client.lead_destination.create');
Route::post('/client/{client}/lead_destination/{lead_destination_type}', 'LeadDestinationController@store')->name('client.lead_destination.store');
Route::resource('client.lead_destination', 'LeadDestinationController')->except(['create', 'store']);
