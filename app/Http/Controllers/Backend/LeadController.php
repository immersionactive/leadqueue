<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientRequest;
use App\Http\Requests\Backend\UpdateClientRequest;
use App\Models\Client;
use App\Models\LeadSource;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{

    // https://laravel.io/forum/how-to-properly-use-controller-middleware
    public function __construct()
    {
        $this->middleware('permission:client.lead_source.lead.index', ['only' => ['index']]);
        $this->middleware('permission:client.lead_source.lead.show', ['only' => ['show']]);
    }

    public function index(Client $client, LeadSource $lead_source)
    {

        $leads = Lead::where('lead_source_id', $lead_source->id)->paginate(20);

        return view('backend.client.lead_source.lead.index', [
            'client' => $client,
            'lead_source' => $lead_source,
            'leads' => $leads
        ]);

    }

    public function show(Client $client, LeadSource $lead_source, Lead $lead)
    {

        return view('backend.client.lead_source.lead.show', [
            'client' => $client,
            'lead_source' => $lead_source,
            'lead' => $lead
        ]);

    }

}
