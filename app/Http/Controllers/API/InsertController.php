<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsertController extends Controller
{

    public function insert(Request $request, LeadSource $lead_source)
    {

        if (
            !$lead_source->is_active ||
            !$lead_source->client->is_active
        ) {
            Log::notice('The API insert endpoint for lead source ' . $lead_source->id . ' (' . $lead_source->name . ') was invoked, but the lead_source and/or client are inactive.');
            abort(404);
        }

        Log::info('The API insert endpoint for lead source ' . $lead_source->id . ' (' . $lead_source->name . ') was invoked.');

        $lead = $this->saveInitialLead($request, $lead_source->id);

        // TODO: parse request and hand it off somewhere. (to a mapper?)

    }

    protected function saveInitialLead(Request $request, $lead_source_id): Lead
    {

        $lead = new Lead();

        $lead->lead_source_id = $lead_source_id;

        $lead->request_url = $request->fullUrl();
        $lead->request_method = $request->method();
        $lead->request_headers_json = json_encode($request->header());

        $lead->save();

        return $lead;

    }

}
