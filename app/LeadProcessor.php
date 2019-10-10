<?php

namespace App;

use App\AppendOutputTranslator;
use App\Models\Lead;
use App\Models\LeadAppendedValue;
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
            // TODO: if this fails, increment the append_failures column. If that column reaches max_append_attempts, then change the status to "append_failed" (and notify someone)
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
     * @return void
     */
    protected function appendLead(Lead $lead): void
    {

        $person = $this->callAppendAPI($lead);
        $this->saveAppendedData($lead, $person);

        $lead->status = 'appended';
        $lead->save();

    }

    /**
     * @return stdClass The object contained in the document.person property of the first entry in the usadataResponseList
     */
    protected function callAppendAPI(Lead $lead): \stdClass
    {
        $body = $this->buildAppendRequestBody($lead);
        $usadata_lead = new USADataLead($body);
        $lead_list = new LeadList();
        $lead_list->addLead($usadata_lead);
        $usadata_response = $this->usadata_api->portrait($lead_list);
        if ($usadata_response->usadataResponseList[0]->code !== 200) {
            // TODO: include the error message in the exception, if we can figure out what property of the response it's in...
            throw new \Exception('The USADATA Portrait endpoint returned code ' . $usadata_response->usadataResponseList[0]->code . ' for lead ' . $lead->id);
        }
        // TODO: make sure these properties exist
        return $usadata_response->usadataResponseList[0]->document->person;
    }

    protected function saveAppendedData(Lead $lead, \stdClass $person): void
    {

        foreach ($lead->mapping->lead_destination->destination_appends as $destination_append) {
            
            $bundle = $destination_append->append_output->bundle;
            $property = $destination_append->append_output->property;

            if (
                isset($person->$bundle) &&
                isset($person->$bundle->$property)
            ) {

                $value = $person->$bundle->$property;

                if ($destination_append->append_output->translator) {
                    $value = AppendOutputTranslator::translate($value, $destination_append->append_output->translator);
                }

                // make sure that we don't try to save null in a text column (which would cause a MySQL error)
                if ($value === null) {
                    $value = '';
                }

                $lead_appended_value = new LeadAppendedValue();
                $lead_appended_value->lead_id = $lead->id;
                $lead_appended_value->destination_append_id = $destination_append->id;
                $lead_appended_value->value = $value;
                $lead_appended_value->save();

            }

        }

    }

    protected function buildAppendRequestBody(Lead $lead): array
    {

        $body = [];

        foreach ($lead->lead_inputs as $lead_input) {
            if ($lead_input->mapping_field->append_input_property !== null) {
                $value = $lead_input->value;
                $body[$lead_input->mapping_field->append_input_property] = $lead_input->value;
            }
        }

        return $body;

    }

    /**
     * Methods for destination insertion
     */

}
