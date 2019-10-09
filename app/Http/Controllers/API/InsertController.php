<?php

namespace App\Http\Controllers\API;

use DB;
use App\SourceConfigTypeRegistry;
use App\DestinationConfigTypeRegistry;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadInput;
use App\Models\LeadSource;
use App\Models\LeadSourceRequest;
use App\Models\Mapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsertController extends Controller
{

    protected $source_config_type_registry;
    protected $destination_config_type_registry;

    public function __construct(SourceConfigTypeRegistry $source_config_type_registry, DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        $this->source_config_type_registry = $source_config_type_registry;
        $this->destination_config_type_registry = $destination_config_type_registry;
    }

    public function insert(Request $request, LeadSource $lead_source)
    {

        if (!$lead_source->client->is_active) {
            Log::notice('The API insert endpoint for lead source ' . $lead_source->id . ' (' . $lead_source->name . ') was invoked, but the client is inactive.');
            abort(404);
        }

        // TODO: check that the HTTP method matches what's expected for this source type?

        Log::info('The API insert endpoint for lead source ' . $lead_source->id . ' (' . $lead_source->name . ') was invoked.');

        $lead_source_request = $this->createLeadSourceRequest($request, $lead_source);
        $this->processActiveMappings($request, $lead_source, $lead_source_request);

    }

    /**
     * Store the raw request, for debugging and reference purposes.
     * @param Request $request
     * @param LeadSource $lead_source
     * @return LeadSourceRequest
     */
    protected function createLeadSourceRequest(Request $request, LeadSource $lead_source): LeadSourceRequest
    {

        $lead_source_request = new LeadSourceRequest();
        $lead_source_request->lead_source_id = $lead_source->id;
        $lead_source_request->request_url = $request->fullUrl();
        $lead_source_request->request_origin_ip = $request->ip();
        $lead_source_request->request_method = $request->method();

        $request_headers = $request->header();
        // Don't store cookies. They could include session cookies for this
        // application (if a user visited the endpoint URL while logged in), which is a security risk. Besides, we don't really need them.
        if (array_key_exists('cookie', $request_headers)) {
            unset($request_headers['cookie']);
        }
        $lead_source_request->request_headers_json = json_encode($request_headers);

        $lead_source_request->request_content_type = $request->header('Content-Type');
        $lead_source_request->request_body_raw = $request->getContent();

        if ($request->isJson()) {
            $request_body_json = $request->getContent();
        } elseif ($request->method() === 'POST') {
            $request_body_json = json_encode($request->all());
        } else {
            $request_body_json = $request->getContent();
        }

        $lead_source_request->request_body_json = $request_body_json;

        $lead_source_request->save();

        return $lead_source_request;

    }

    protected function processActiveMappings(Request $request, LeadSource $lead_source, LeadSourceRequest $lead_source_request)
    {

        // Find active Mappings associated with this LeadSource
        $mappings = Mapping::where([
            'is_active' => true,
            'lead_source_id' => $lead_source->id
        ])->get();

        foreach ($mappings as $mapping) {
            try {
                $this->processMapping($request, $lead_source, $mapping, $lead_source_request);
            } catch (\Exception $e) {
                // TODO: probably email someone
                Log::error('Failed processing mapping ' . $mapping->id . ' for lead source request ' . $lead_source_request->id . ':' . $e->getMessage());
            }
        }

    }

    protected function processMapping(Request $request, LeadSource $lead_source, Mapping $mapping, LeadSourceRequest $lead_source_request)
    {

        $source_config_type_classname = $this->source_config_type_registry->getByModelClassname($lead_source->source_config_type);

        // Extract source fields from the request

        $source_fields = [];

        foreach ($mapping->mapping_fields as $mapping_field) {
            // TODO: If a configured source field isn't present in the request, should we create a LeadInput with an empty value, or create no LeadInput?
            $value = $source_config_type_classname::extractSourceFieldFromInsertRequest($request, $mapping_field);
            $source_fields[$mapping_field->id] = $value;
        }

        /**
         * Save the Lead and LeadInputs
         */

        $lead = new Lead();
        $lead->mapping_id = $mapping->id;
        $lead->lead_source_request_id = $lead_source_request->id;

        DB::transaction(function () use ($lead, $source_fields) {

            $lead->save();

            foreach ($source_fields as $mapping_field_id => $value) {
                
                $lead_input = new LeadInput();
                $lead_input->lead_id = $lead->id;
                $lead_input->mapping_field_id = $mapping_field_id;

                // Trying to insert null into a TEXT column will cause a
                // MySQL error. We'll make sure that doesn't happen.
                if ($value === null) {
                    $value = '';
                }
                $lead_input->value = $value;

                $lead_input->save();

            }
 
        });

        throw new \Exception('unimplemented');

        // $destination_config_type_classname = $this->destination_config_type_registry->getByModelClassname($mapping->lead_destination->destination_config_type);

    }

}
