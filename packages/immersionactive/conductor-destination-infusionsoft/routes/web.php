<?php

Route::group(

    [
        'namespace' => 'ImmersionActive\Conductor\Destinations\Infusionsoft\Http\Controllers\Backend',
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['web' /* , 'admin' */]
    ],
    
    function () {

        Route::get('infusionsoft/authorize/{lead_destination}', 'AuthorizeController@begin')
            ->where([
                'lead_destination' => '[1-9][0-9]*'
            ])
            ->name('infusionsoft.authorize.begin');

        Route::get('infusionsoft/authorize/{lead_destination}/callback', 'AuthorizeController@callback')
            ->where([
                'lead_destination' => '[1-9][0-9]*'
            ])
            ->name('infusionsoft.authorize.callback');

        Route::post('infusionsoft/authorize/{lead_destination}/deauthorize', 'AuthorizeController@deauthorize')
            ->where([
                'lead_destination' => '[1-9][0-9]*'
            ])
            ->name('infusionsoft.authorize.deauthorize');

    }

);
