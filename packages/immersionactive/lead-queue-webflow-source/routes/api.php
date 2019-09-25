<?php

Route::post('/webflow/insert-lead/{webflow_source_config}', '\ImmersionActive\LeadQueueWebflowSource\Http\Controllers\InsertLeadController@insert')->name('api.webflow.insert-lead');
