<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeadSource;
use App\Models\LeadSourceRequest;

class LeadSourceRequestController extends Controller
{

    public function index(Client $client, LeadSource $lead_source)
    {

        $this->authorize('client.lead_source.lead_source_request.index');

        $lead_source_requests = LeadSourceRequest::where('lead_source_id', $lead_source->id)->orderBy('created_at', 'DESC')->paginate(20);

        return view('backend.client.lead_source.lead_source_request.index', [
            'client' => $client,
            'lead_source' => $lead_source,
            'lead_source_requests' => $lead_source_requests
        ]);

    }

    public function show(Client $client, LeadSource $lead_source, LeadSourceRequest $lead_source_request)
    {

        $this->authorize('client.lead_source.lead_source_request.show');

        return view('backend.client.lead_source.lead_source_request.show', [
            'client' => $client,
            'lead_source' => $lead_source,
            'lead_source_request' => $lead_source_request
        ]);

    }

}
