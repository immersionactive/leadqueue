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

Route::get('client/{client}/lead_source', 'LeadSourceController@index')->where('client', '[1-9][0-9]*')->name('client.lead_source.index');

Route::get('client/{client}/lead_source/{lead_source}', 'LeadSourceController@show')
    ->where([
        'client' => '[1-9][0-9]*',
        'lead_source' => '[1-9][0-9]*'
    ])
    ->name('client.lead_source.show');

Route::match(['get', 'post'], 'client/{client}/lead_source/edit/{lead_source?}', 'LeadSourceController@edit')
    ->where([
        'client' => '[1-9][0-9]*',
        'lead_source' => '[1-9][0-9]*'
    ])
    ->name('client.lead_source.edit');

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

/**
 * DestinationAppends
 */

Route::get('client/{client}/lead_destination/{lead_destination}/destination_append', 'DestinationAppendController@index')->where(['client' => '[1-9][0-9]*', 'lead_destination' => '[1-9][0-9]*'])->name('client.lead_destination.destination_append.index');

Route::match(['get', 'post'], 'client/{client}/lead_destination/{lead_destination}/destination_append/edit/{destination_append?}', 'DestinationAppendController@edit')->where(['client' => '[1-9][0-9]*', 'lead_destination' => '[1-9][0-9]*', 'destination_append' => '[1-9][0-9]*'])->name('client.lead_destination.destination_append.edit');

// Route::resource('client.lead_destination.destination_append', 'DestinationAppendController'); // ->where(['client' => '[1-9][0-9]*', 'lead_destination' => '[1-9][0-9]*']);

Route::post('client/{client}/lead_destination/{lead_destination}/destination_append/delete/{destination_append}', 'DestinationAppend@destroy')->where(['client' => '[1-9][0-9]*', 'lead_destination' => '[1-9][0-9]*', 'destination_append' => '[1-9][0-9]*'])->name('client.lead_destination.destination_append.destroy');

/**
 * Mappings
 */

Route::get('client/{client}/mapping', 'MappingController@index')->where('client', '[1-9][0-9]*')->name('client.mapping.index');

Route::get('client/{client}/mapping/{mapping}', 'MappingController@show')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.show');

Route::match(['get', 'post'], 'client/{client}/mapping/edit/{mapping?}', 'MappingController@edit')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.edit');

Route::delete('client/{client}/mapping/delete/{mapping}', 'MappingController@destroy')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.destroy');
