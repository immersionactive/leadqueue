<?php

namespace ImmersionActive\LeadQueueWebflowSource\Http\Controllers;

use Illuminate\Http\Request;
use ImmersionActive\LeadQueueWebflowSource\Models\WebflowSourceConfig;

class InsertLeadController
{

    public function insert(Request $request, WebflowSourceConfig $webflow_source_config)
    {

        mail('travis@immersionactive.com', 'InsertLeadController::insert() invoked', print_r($request->input(), true));

    }

}
