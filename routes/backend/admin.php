<?php

use App\Http\Controllers\Backend\DashboardController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

/**
 * Clients
 */

Route::get('client', 'ClientController@index')->name('client.index');
Route::get('client/{client}', 'ClientController@show')->where('client', '[1-9][0-9]*')->name('client.show');
Route::match(['get', 'post'], 'client/edit/{client?}', 'ClientController@edit')->where('client', '[1-9][0-9]*')->name('client.edit');
Route::post('client/delete/{client}', 'ClientController@delete')->where('client', '[1-9][0-9]*')->name('client.delete');

/**
 * LeadSources
 */

// We define these routes separately from the resource, because we want them to support an additional /:type/ segment
Route::get('/client/{client}/lead_source/create/{lead_source_type}', 'LeadSourceController@create')->name('client.lead_source.create');
Route::post('/client/{client}/lead_source/{lead_source_type}', 'LeadSourceController@store')->name('client.lead_source.store');
Route::resource('client.lead_source', 'LeadSourceController')->except(['create', 'store']);

Route::resource('client.lead_source.lead', 'LeadController')->only(['index', 'show']);

/**
 * LeadDestinations
 */

Route::get('client/{client}/lead_destination', 'LeadDestinationController@index')->where('client', '[1-9][0-9]*')->name('client.lead_destination.index');
Route::get('client/{client}/lead_destination/{lead_destination}', 'LeadDestinationController@show')
    ->where([
        'client' => '[1-9][0-9]*',
        'lead_destination' => '[1-9][0-9]*'
    ])
    ->name('client.lead_destination.show');
Route::match(['get', 'post'], 'client/{client}/lead_destination/edit/{lead_destination?}', 'LeadDestinationController@edit')
    ->where([
        'client' => '[1-9][0-9]*',
        'lead_destination' => '[1-9][0-9]*'
    ])
    ->name('client.lead_destination.edit');

// Route::get('/client/{client}/lead_destination/create/{lead_destination_type}', 'LeadDestinationController@create')->name('client.lead_destination.create');
// Route::post('/client/{client}/lead_destination/{lead_destination_type}', 'LeadDestinationController@store')->name('client.lead_destination.store');
// Route::resource('client.lead_destination', 'LeadDestinationController')->except(['create', 'store']);

/**
 * Mappings
 */

Route::resource('client.mapping', 'MappingController');
Route::resource('client.mapping.mapping_field', 'MappingFieldController');
