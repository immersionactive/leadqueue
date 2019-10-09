<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreClientRequest;
use App\Http\Requests\Backend\UpdateClientRequest;
use App\Models\Client;
use App\Models\Mapping;
use App\Models\Lead;
use App\SourceConfigTypeRegistry;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{

    protected $source_config_type_registry;

    public function __construct(SourceConfigTypeRegistry $source_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
    }

    public function index(Client $client, Mapping $mapping)
    {

        $this->authorize('client.mapping.lead.index');

        $leads = Lead::where('mapping_id', $mapping->id)->paginate(20);

        return view('backend.client.mapping.lead.index', [
            'client' => $client,
            'mapping' => $mapping,
            'leads' => $leads            
        ]);

    }

    public function show(Client $client, Mapping $mapping, Lead $lead)
    {

        $this->authorize('client.mapping.lead.show');
        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($mapping->lead_source->source_config_type);

        return view('backend.client.mapping.lead.show', [
            'client' => $client,
            'mapping' => $mapping,
            'lead' => $lead,
            'source_config_type_classname' => $source_config_type_classname
        ]);

    }

}
