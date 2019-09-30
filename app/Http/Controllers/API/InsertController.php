<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadSource;
use App\SourceConfigTypeRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsertController extends Controller
{

    protected $source_config_type_registry;

    public function __construct(SourceConfigTypeRegistry $source_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
    }

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

        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($lead_source->source_config_type);

        // try {
            $source_config_type_classname::processInsert($request, $lead_source);
        // } catch (\Exception $e) {
        //     // TODO
        // }

    }

    protected function saveInitialLead(Request $request, $lead_source_id): Lead
    {

        $lead = new Lead();

        $lead->lead_source_id = $lead_source_id;

        $lead->request_url = $request->fullUrl();
        $lead->request_origin_ip = $request->ip();
        $lead->request_method = $request->method();
        $lead->request_headers_json = json_encode($request->header());
        $lead->request_content_type = $request->header('Content-Type');
        $lead->request_body_raw = $request->getContent();

        if ($request->isJson()) {
            $request_body_json = $request->getContent();
        } elseif ($request->method() === 'POST') {
            $request_body_json = json_encode($request->all());
        } else {
            $request_body_json = $request->getContent();
        }

        $lead->request_body_json = $request_body_json;

        $lead->save();

        return $lead;

    }

}
