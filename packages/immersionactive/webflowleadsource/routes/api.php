<?php

Route::post('/webflow/insert-lead/{webflow_source_config}', 'InsertLeadController@insert')->name('webflow.insert-lead');
