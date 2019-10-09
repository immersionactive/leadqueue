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
 * LeadSourceRequests
 */

Route::get('client/{client}/lead_source/{lead_source}/requests', 'LeadSourceRequestController@index')->name('client.lead_source.lead_source_request.index');

Route::get('client/{client}/lead_source/{lead_source}/requests/{lead_source_request}', 'LeadSourceRequestController@show')->name('client.lead_source.lead_source_request.show');

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

Route::post('client/{client}/mapping/delete/{mapping}', 'MappingController@destroy')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.destroy');

/**
 * MappingFields
 */

Route::get('client/{client}/mapping/{mapping}/mapping_field', 'MappingFieldController@index')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.mapping_field.index');

Route::get('client/{client}/mapping/{mapping}/mapping_field/{mapping_field}', 'MappingFieldController@show')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*',
        'mapping_field' => '[1-9][0-9]*',
    ])
    ->name('client.mapping.mapping_field.show');

Route::match(['get', 'post'], 'client/{client}/mapping/{mapping}/mapping_field/edit/{mapping_field?}', 'MappingFieldController@edit')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*',
        'mapping_field' => '[1-9][0-9]*',
    ])
    ->name('client.mapping.mapping_field.edit');

Route::post('client/{client}/mapping/{mapping}/mapping_field/delete/{mapping_field}', 'MappingFieldController@destroy')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*',
        'mapping_field' => '[1-9][0-9]*',
    ])
    ->name('client.mapping.mapping_field.destroy');

/**
 * Leads
 */

Route::get('client/{client}/mapping/{mapping}/lead', 'LeadController@index')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*'
    ])
    ->name('client.mapping.lead.index');

Route::get('client/{client}/mapping/{mapping}/lead/{lead}', 'LeadController@show')
    ->where([
        'client' => '[1-9][0-9]*',
        'mapping' => '[1-9][0-9]*',
        'lead' => '[1-9][0-9]*',
    ])
    ->name('client.mapping.lead.show');
