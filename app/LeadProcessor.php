<?php

namespace App;

use App\Models\Lead;
use App\Models\MappingField;
use ImmersionActive\USADataAPI\USADataAPIClient;
use ImmersionActive\USADataAPI\LeadList;
use ImmersionActive\USADataAPI\Lead as USADataLead;

class LeadProcessor
{

    protected $usadata_api;

    public function __construct()
    {
        $this->buildAppendClient();
    }

    /**
     * @param Lead $lead
     * @return TODO
     */
    public function process(Lead $lead) // TODO: type-hint return value
    {

        if ($lead->status === 'new') {
            // This lead has not yet been appended
            $this->appendLead($lead);
        }

    }

    /**
     * Methods for appends
     */

    protected function buildAppendClient(): void
    {

        $username = env('USADATA_USERNAME');
        $password = env('USADATA_PASSWORD');
        $api_key = env('USADATA_API_KEY');

        if (
            $username === null ||
            $password === null ||
            $api_key === null
        ) {
            throw new \Exception('Environment variables were not found for USADATA_USERNAME, USADATA_PASSWORD, and/or USADATA_API_KEY. Please make sure these variables are configured in your .env file.');
        }

        $this->usadata_api = new USADataAPIClient($username, $password, $api_key);

    }

    /**
     * @param Lead $lead
     */
    protected function appendLead(Lead $lead) // TODO: type-hint return value
    {

        // TODO

        $body = $this->buildAppendRequestBody($lead);
        $usadata_lead = new USADataLead($body);
        $lead_list = new LeadList();
        $lead_list->addLead($usadata_lead);

        $response = $this->usadata_api->portrait($lead_list);

        var_dump($response); exit;

    }

    protected function buildAppendRequestBody(Lead $lead) // TODO: type-hint return value
    {

        $body = [];
        // $mapping_fields = MappingField::where('mapping_id', $lead->mapping_id);

        // foreach ($mapping_fields as $mapping_field) {
        //     if ($mapping_field->append_input_property !== null) {
        //         $body[$mapping_field->append_input_property] = 
        //     }
        // }

        foreach ($lead->lead_inputs as $lead_input) {
            if ($lead_input->mapping_field->append_input_property !== null) {
                $value = $lead_input->value;
                $body[$lead_input->mapping_field->append_input_property] = $lead_input->value;
            }
        }

        return $body;

    }

}
